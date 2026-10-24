<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\FinanceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        if ($category->isSystem()) {
            // Nama kategori bawaan dikunci supaya laporan & integrasi tetap konsisten
            unset($validated['name']);
        } else {
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

    public function destroy(FinanceCategory $category): RedirectResponse
    {
        if ($category->isSystem()) {
            return back()->with('error', 'Kategori bawaan sistem tidak dapat dihapus.');
        }

        if ($category->transactions()->exists()) {
            return back()->with('error', 'Kategori sudah dipakai transaksi. Nonaktifkan saja agar riwayat tetap utuh.');
        }

        $name = $category->name;
        $category->delete();

        AuditLog::log('delete', "Menghapus kategori keuangan: {$name}");

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
