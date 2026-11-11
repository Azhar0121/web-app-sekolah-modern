<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\FinanceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FinanceCategoryController extends Controller
{
    public function index(): View
    {
        $categories = FinanceCategory::withCount('transactions')
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return view('admin.finance.categories', [
            'income'  => $categories->get('income', collect()),
            'expense' => $categories->get('expense', collect()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:120'],
            'type'        => ['required', Rule::in(['income', 'expense'])],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $exists = FinanceCategory::where('type', $validated['type'])->where('name', $validated['name'])->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['name' => 'Kategori dengan nama tersebut sudah ada pada jenis yang sama.']);
        }

        $category = FinanceCategory::create($validated + ['is_active' => true]);

        AuditLog::log('create', "Menambahkan kategori keuangan: {$category->name} ({$category->typeLabel()})", $category);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, FinanceCategory $category): RedirectResponse
    {
        $rules = [
            'description' => ['nullable', 'string', 'max:255'],
        ];

        if (! $category->isSystem()) {
            $rules['name'] = ['required', 'string', 'max:120'];
        }

        $validated = $request->validate($rules);

        if (! $category->isSystem()) {
            $duplicate = FinanceCategory::where('type', $category->type)
                ->where('name', $validated['name'])
                ->where('id', '!=', $category->id)
                ->exists();
            if ($duplicate) {
                return back()->withInput()->withErrors(['name' => 'Kategori dengan nama tersebut sudah ada pada jenis yang sama.']);
            }
        }

        $old = $category->toArray();
        $category->update($validated);

        AuditLog::log('update', "Memperbarui kategori keuangan: {$category->name}", $category, $old, $category->toArray());

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggle(FinanceCategory $category): RedirectResponse
    {
        if ($category->system_key === 'tagihan_siswa') {
            return back()->with('error', 'Kategori otomatis tagihan siswa tidak boleh dinonaktifkan.');
        }

        $category->update(['is_active' => ! $category->is_active]);

        AuditLog::log('update', sprintf('%s kategori keuangan: %s', $category->is_active ? 'Mengaktifkan' : 'Menonaktifkan', $category->name), $category);

        return back()->with('success', $category->is_active ? 'Kategori diaktifkan.' : 'Kategori dinonaktifkan dan tidak muncul lagi di form.');
    }

    public function destroy(Request $request, FinanceCategory $category): RedirectResponse|JsonResponse
    {
        if ($category->isSystem()) {
            $msg = 'Kategori bawaan sistem tidak dapat dihapus demi keutuhan data & integrasi.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            return back()->with('error', $msg);
        }

        $name = $category->name;
        $id = $category->id;
        $affectedCount = 0;
        $fallbackName = null;

        DB::transaction(function () use ($category, &$affectedCount, &$fallbackName) {
            if ($category->transactions()->exists()) {
                $fallback = FinanceCategory::where('type', $category->type)
                    ->where('id', '!=', $category->id)
                    ->where(function ($q) {
                        $q->where('name', 'like', '%Lainnya%')
                          ->orWhere('name', 'like', '%Umum%');
                    })
                    ->first() ?? FinanceCategory::where('type', $category->type)->where('id', '!=', $category->id)->first();

                if (! $fallback) {
                    $fallback = FinanceCategory::create([
                        'name'        => $category->type === 'income' ? 'Pemasukan Lainnya' : 'Pengeluaran Lainnya',
                        'type'        => $category->type,
                        'description' => 'Kategori umum transaksi keuangan',
                        'is_active'   => true,
                    ]);
                }

                $fallbackName = $fallback->name;
                $affectedCount = $category->transactions()->count();
                $category->transactions()->update(['finance_category_id' => $fallback->id]);
            }

            $category->delete();
        });

        $logMsg = "Menghapus kategori keuangan: {$name}";
        if ($affectedCount > 0 && $fallbackName) {
            $logMsg .= " ({$affectedCount} transaksi dialihkan ke kategori '{$fallbackName}')";
        }
        AuditLog::log('delete', $logMsg);

        $successMsg = "Kategori '{$name}' berhasil dihapus.";
        if ($affectedCount > 0 && $fallbackName) {
            $successMsg .= " Sebanyak {$affectedCount} transaksi terkait dialihkan ke kategori '{$fallbackName}'.";
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'           => true,
                'message'           => $successMsg,
                'data'              => [
                    'id'                => $id,
                    'name'              => $name,
                    'reassigned_count'  => $affectedCount,
                    'fallback_category' => $fallbackName,
                ],
            ]);
        }

        return back()->with('success', $successMsg);
    }
}