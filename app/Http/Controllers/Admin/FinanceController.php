<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AuditLog;
use App\Models\BillingRecord;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->filters($request);

        $base = $this->filteredQuery($filters);

        $summaryQuery = (clone $base)->where('status', 'posted');
        $totalIncome  = (clone $summaryQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $summaryQuery)->where('type', 'expense')->sum('amount');

        // Saldo kas kumulatif sampai akhir periode yang dipilih
        $cashBalance = $this->balanceUntil($filters['to']);

        $transactions = (clone $base)
            ->with(['category', 'room'])
            ->when($filters['status'] !== 'all', fn ($q) => $q->where('status', $filters['status']))
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $categories = FinanceCategory::orderBy('type')->orderBy('name')->get();

        return view('admin.finance.index', [
            'transactions' => $transactions,
            'filters'      => $filters,
            'categories'   => $categories,
            'totalIncome'  => $totalIncome,
            'totalExpense' => $totalExpense,
            'cashBalance'  => $cashBalance,
            'canManage'    => $this->canManage(),
        ]);
    }

    public function show(FinanceTransaction $transaction): View
    {
        $transaction->load(['category', 'room', 'academicYear', 'billing.student', 'creator', 'voidedBy']);

        return view('admin.finance.show', [
            'transaction' => $transaction,
            'canManage'   => $this->canManage(),
        ]);
    }

    public function create(Request $request): View
    {
        $type = $this->normalizeType($request->query('type'));

        return view('admin.finance.create', $this->formData($type, new FinanceTransaction([
            'type'             => $type,
            'transaction_date' => today(),
            'payment_method'   => 'tunai',
        ])));
    }

    public function store(Request $request): RedirectResponse
    {
        $type      = $this->normalizeType($request->input('type'));
        $validated = $this->validateTransaction($request, $type);

        $date = Carbon::parse($validated['transaction_date']);

        $transaction = new FinanceTransaction($validated);
        $transaction->type             = $type;
        $transaction->number           = FinanceTransaction::generateNumber($type, $date);
        $transaction->status           = 'posted';
        $transaction->academic_year_id = AcademicYear::active()?->id;
        $transaction->created_by       = auth()->id();

        if ($request->hasFile('proof')) {
            $file = $request->file('proof');
            $transaction->proof_path          = $file->store('keuangan', 'local');
            $transaction->proof_original_name = $file->getClientOriginalName();
        }

        $transaction->save();

        AuditLog::log(
            'create',
            sprintf('Mencatat %s %s: %s (%s)', $type === 'income' ? 'kas masuk' : 'kas keluar', $transaction->number, $transaction->description, $transaction->formattedAmount()),
            $transaction,
            null,
            $transaction->toArray()
        );

        return redirect()
            ->route('admin.finance.show', $transaction)
            ->with('success', "Transaksi berhasil dicatat dengan nomor {$transaction->number}.");
    }

    public function edit(FinanceTransaction $transaction): View|RedirectResponse
    {
        if (! $transaction->isEditable()) {
            return redirect()->route('admin.finance.show', $transaction)
                ->with('error', $this->lockedMessage($transaction));
        }

        return view('admin.finance.edit', $this->formData($transaction->type, $transaction));
    }

    public function update(Request $request, FinanceTransaction $transaction): RedirectResponse
    {
        if (! $transaction->isEditable()) {
            return redirect()->route('admin.finance.show', $transaction)
                ->with('error', $this->lockedMessage($transaction));
        }

        // Jenis transaksi & nomor bukti permanen setelah dibuat.
        $validated = $this->validateTransaction($request, $transaction->type);
        $old       = $transaction->toArray();

        $transaction->fill($validated);

        if ($request->hasFile('proof')) {
            if ($transaction->proof_path) {
                Storage::disk('local')->delete($transaction->proof_path);
            }
            $file = $request->file('proof');
            $transaction->proof_path          = $file->store('keuangan', 'local');
            $transaction->proof_original_name = $file->getClientOriginalName();
        } elseif ($request->boolean('remove_proof') && $transaction->proof_path) {
            Storage::disk('local')->delete($transaction->proof_path);
            $transaction->proof_path          = null;
            $transaction->proof_original_name = null;
        }

        $transaction->save();

        AuditLog::log('update', "Memperbarui transaksi {$transaction->number}: {$transaction->description}", $transaction, $old, $transaction->toArray());

        return redirect()->route('admin.finance.show', $transaction)
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /** Transaksi tidak dihapus permanen; dibatalkan dengan alasan supaya jejak audit tetap ada. */
    public function void(Request $request, FinanceTransaction $transaction): RedirectResponse
    {
        if ($transaction->isVoid()) {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        $validated = $request->validate([
            'void_reason' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'void_reason.required' => 'Alasan pembatalan wajib diisi.',
            'void_reason.min'      => 'Alasan pembatalan minimal 5 karakter.',
        ]);

        $old = $transaction->toArray();

        $transaction->update([
            'status'      => 'void',
            'void_reason' => $validated['void_reason'],
            'voided_by'   => auth()->id(),
            'voided_at'   => now(),
        ]);

        AuditLog::log('update', "Membatalkan transaksi {$transaction->number}: {$validated['void_reason']}", $transaction, $old, $transaction->toArray());

        return redirect()->route('admin.finance.show', $transaction)
            ->with('success', 'Transaksi dibatalkan dan tidak dihitung lagi dalam saldo.');
    }

    public function proof(FinanceTransaction $transaction)
    {
        abort_unless($transaction->hasProof() && Storage::disk('local')->exists($transaction->proof_path), 404);

        return response()->file(
            Storage::disk('local')->path($transaction->proof_path),
            ['Content-Disposition' => 'inline; filename="' . addslashes($transaction->proof_original_name ?? 'bukti') . '"']
        );
    }

    public function syncBilling(): RedirectResponse
    {
        $linked  = FinanceTransaction::whereNotNull('billing_record_id')->pluck('billing_record_id');
        $created = 0;

        BillingRecord::with('student')
            ->where('status', 'paid')
            ->whereNotIn('id', $linked)
            ->orderBy('paid_at')
            ->chunkById(100, function ($billings) use (&$created) {
                foreach ($billings as $billing) {
                    if (FinanceTransaction::recordFromBilling($billing)) {
                        $created++;
                    }
                }
            });

        if ($created > 0) {
            AuditLog::log('create', "Sinkronisasi tagihan lunas ke kas masuk: {$created} transaksi baru.");
        }

        return back()->with(
            $created > 0 ? 'success' : 'warning',
            $created > 0
                ? "{$created} pembayaran tagihan lunas berhasil dicatat sebagai kas masuk."
                : 'Tidak ada pembayaran tagihan lunas yang belum tercatat (atau kategori bawaan belum tersedia, jalankan FinanceSeeder).'
        );
    }

    public function report(Request $request): View
    {
        return view('admin.finance.report', $this->reportData($request) + ['canManage' => $this->canManage()]);
    }

    public function print(Request $request): View
    {
        return view('admin.finance.print', $this->reportData($request));
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $this->filters($request);

        $query = $this->filteredQuery($filters)
            ->with(['category', 'room'])
            ->when($filters['status'] !== 'all', fn ($q) => $q->where('status', $filters['status']))
            ->orderBy('transaction_date')
            ->orderBy('id');

        AuditLog::log('download', sprintf('Mengunduh CSV keuangan periode %s s.d. %s', $filters['from']->toDateString(), $filters['to']->toDateString()));

        $filename = sprintf('keuangan-%s_%s.csv', $filters['from']->format('Ymd'), $filters['to']->format('Ymd'));

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM supaya Excel membaca UTF-8

            fputcsv($out, [
                'No. Bukti', 'Tanggal', 'Jenis', 'Kategori', 'Uraian', 'Sumber / Penerima',
                'Metode', 'No. Referensi', 'Nama Barang', 'Jumlah', 'Harga Satuan', 'Ruangan',
                'Pemasukan', 'Pengeluaran', 'Status',
            ], ';');

            $query->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $t) {
                    fputcsv($out, [
                        $t->number,
                        $t->transaction_date->format('d/m/Y'),
                        $t->typeLabel(),
                        $t->category?->name,
                        $t->description,
                        $t->party,
                        $t->paymentMethodLabel(),
                        $t->reference_number,
                        $t->item_name,
                        $t->quantity,
                        $t->unit_price !== null ? (int) $t->unit_price : '',
                        $t->room?->name,
                        $t->isIncome() ? (int) $t->amount : '',
                        $t->isIncome() ? '' : (int) $t->amount,
                        $t->isVoid() ? 'Dibatalkan' : 'Sah',
                    ], ';');
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function canManage(): bool
    {
        $user = auth()->user();

        return $user->hasRole('super-admin') || $user->hasPermission('keuangan.manage');
    }

    private function normalizeType(?string $type): string
    {
        return $type === 'expense' ? 'expense' : 'income';
    }

    private function lockedMessage(FinanceTransaction $transaction): string
    {
        return $transaction->isVoid()
            ? 'Transaksi yang sudah dibatalkan tidak dapat diubah.'
            : 'Transaksi otomatis dari tagihan siswa tidak dapat diedit. Jika keliru, batalkan transaksi ini dan perbaiki di menu Tagihan Siswa.';
    }

    private function filters(Request $request): array
    {
        $parse = function (?string $value, Carbon $fallback): Carbon {
            try {
                return $value ? Carbon::parse($value) : $fallback;
            } catch (\Throwable) {
                return $fallback;
            }
        };

        $from = $parse($request->query('from'), now()->startOfYear())->startOfDay();
        $to   = $parse($request->query('to'), now()->endOfYear())->endOfDay();

        if ($from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        $type = $request->query('type');
        $status = $request->query('status', 'posted');

        return [
            'from'        => $from,
            'to'          => $to,
            'type'        => in_array($type, ['income', 'expense'], true) ? $type : null,
            'category_id' => $request->query('category_id') ?: null,
            'search'      => $request->string('search')->trim()->toString(),
            'status'      => in_array($status, ['posted', 'void', 'all'], true) ? $status : 'posted',
        ];
    }

    private function filteredQuery(array $f): Builder
    {
        return FinanceTransaction::query()
            ->whereBetween('transaction_date', [$f['from']->toDateString(), $f['to']->toDateString()])
            ->when($f['type'], fn ($q, $v) => $q->where('type', $v))
            ->when($f['category_id'], fn ($q, $v) => $q->where('finance_category_id', $v))
            ->when($f['search'], fn ($q, $s) => $q->where(function ($qq) use ($s) {
                $qq->where('description', 'like', "%{$s}%")
                    ->orWhere('number', 'like', "%{$s}%")
                    ->orWhere('party', 'like', "%{$s}%")
                    ->orWhere('item_name', 'like', "%{$s}%")
                    ->orWhere('reference_number', 'like', "%{$s}%");
            }));
    }

    /** Saldo kas = total kas masuk - total kas keluar (transaksi sah) sampai tanggal tertentu. */
    private function balanceUntil(Carbon $date): float
    {
        $rows = FinanceTransaction::posted()
            ->whereDate('transaction_date', '<=', $date->toDateString())
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        return (float) ($rows['income'] ?? 0) - (float) ($rows['expense'] ?? 0);
    }

    private function formData(string $type, FinanceTransaction $transaction): array
    {
        return [
            'type'        => $type,
            'transaction' => $transaction,
            'categories'  => FinanceCategory::manualSelectable($type)->get(),
            // Bila kategori lama sudah dinonaktifkan, tetap tampil agar data lama bisa diedit
            'currentCategory' => $transaction->exists ? $transaction->category : null,
            'rooms'       => Room::where('is_active', true)->orderBy('code')->get(),
            'methods'     => FinanceTransaction::paymentMethods(),
        ];
    }

    private function validateTransaction(Request $request, string $type): array
    {
        $validated = $request->validate([
            'finance_category_id' => [
                'required',
                Rule::exists('finance_categories', 'id')
                    ->where('type', $type)
                    ->where(fn ($q) => $q->whereNull('system_key')->orWhere('system_key', '!=', 'tagihan_siswa')),
            ],
            'transaction_date'  => ['required', 'date', 'before_or_equal:today'],
            'description'       => ['required', 'string', 'max:255'],
            'party'             => ['nullable', 'string', 'max:150'],
            'payment_method'    => ['required', Rule::in(array_keys(FinanceTransaction::paymentMethods()))],
            'reference_number'  => ['nullable', 'string', 'max:100'],
            'amount'            => ['required_without_all:quantity,unit_price', 'nullable', 'numeric', 'min:1', 'max:99999999999'],
            'item_name'         => ['nullable', 'string', 'max:150'],
            'quantity'          => ['nullable', 'integer', 'min:1', 'max:100000'],
            'unit_price'        => ['nullable', 'numeric', 'min:0', 'max:99999999999'],
            'room_id'           => ['nullable', 'exists:rooms,id'],
            'notes'             => ['nullable', 'string', 'max:1000'],
            'proof'             => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'amount.required_without_all' => 'Nominal wajib diisi (atau isi jumlah barang dan harga satuan).',
            'transaction_date.before_or_equal' => 'Tanggal transaksi tidak boleh melebihi hari ini.',
        ]);

        unset($validated['proof']);

        if ($type === 'income') {
            // Rincian barang & ruangan hanya relevan untuk pengeluaran
            $validated['item_name'] = $validated['quantity'] = $validated['unit_price'] = $validated['room_id'] = null;
        } elseif (! empty($validated['quantity']) && isset($validated['unit_price']) && $validated['unit_price'] !== '') {
            // Nominal dihitung otomatis di server = jumlah x harga satuan
            $validated['amount'] = $validated['quantity'] * $validated['unit_price'];
        }

        if (empty($validated['amount']) || $validated['amount'] <= 0) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount' => 'Nominal transaksi harus lebih dari 0.',
            ]);
        }

        return $validated;
    }

    private function reportData(Request $request): array
    {
        $year = (int) $request->query('year', now()->year);
        $year = max(2000, min($year, 2100));

        $from = Carbon::create($year, 1, 1)->toDateString();
        $to   = Carbon::create($year, 12, 31)->toDateString();

        $openingBalance = $this->balanceUntil(Carbon::create($year, 1, 1)->subDay());

        $rows = FinanceTransaction::posted()
            ->whereBetween('transaction_date', [$from, $to])
            ->selectRaw('MONTH(transaction_date) as month, type, SUM(amount) as total')
            ->groupBy('month', 'type')
            ->get();

        $months   = [];
        $running  = $openingBalance;
        $labels   = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        for ($m = 1; $m <= 12; $m++) {
            $income  = (float) $rows->where('month', $m)->where('type', 'income')->sum('total');
            $expense = (float) $rows->where('month', $m)->where('type', 'expense')->sum('total');
            $running += $income - $expense;

            $months[] = [
                'label'   => $labels[$m - 1],
                'income'  => $income,
                'expense' => $expense,
                'net'     => $income - $expense,
                'balance' => $running,
            ];
        }

        $byCategory = FinanceTransaction::posted()
            ->whereBetween('transaction_date', [$from, $to])
            ->selectRaw('finance_category_id, type, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('finance_category_id', 'type')
            ->with('category')
            ->get()
            ->sortByDesc('total');

        $totalIncome  = array_sum(array_column($months, 'income'));
        $totalExpense = array_sum(array_column($months, 'expense'));

        $years = FinanceTransaction::selectRaw('YEAR(transaction_date) as y')->distinct()->pluck('y')->push(now()->year, $year)->unique()->sortDesc()->values();

        $chart = array_map(fn ($m) => [
            'label'   => mb_substr($m['label'], 0, 3),
            'income'  => $m['income'],
            'expense' => $m['expense'],
        ], $months);

        return [
            'chart'          => $chart,
            'year'           => $year,
            'years'          => $years,
            'months'         => $months,
            'openingBalance' => $openingBalance,
            'closingBalance' => $openingBalance + $totalIncome - $totalExpense,
            'totalIncome'    => $totalIncome,
            'totalExpense'   => $totalExpense,
            'incomeByCategory'  => $byCategory->where('type', 'income')->values(),
            'expenseByCategory' => $byCategory->where('type', 'expense')->values(),
        ];
    }
}