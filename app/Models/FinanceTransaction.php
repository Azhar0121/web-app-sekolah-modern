<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class FinanceTransaction extends Model
{
    protected $fillable = [
        'number', 'type', 'finance_category_id', 'transaction_date', 'amount',
        'description', 'party', 'payment_method', 'reference_number',
        'item_name', 'quantity', 'unit_price', 'room_id',
        'academic_year_id', 'billing_record_id',
        'proof_path', 'proof_original_name', 'notes',
        'status', 'void_reason', 'voided_by', 'voided_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
            'amount'           => 'decimal:2',
            'unit_price'       => 'decimal:2',
            'quantity'         => 'integer',
            'voided_at'        => 'datetime',
        ];
    }

    // ---------- Relasi ----------
    public function category(): BelongsTo { return $this->belongsTo(FinanceCategory::class, 'finance_category_id'); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function billing(): BelongsTo { return $this->belongsTo(BillingRecord::class, 'billing_record_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function voidedBy(): BelongsTo { return $this->belongsTo(User::class, 'voided_by'); }

    // ---------- Scope ----------
    public function scopePosted($query) { return $query->where('status', 'posted'); }
    public function scopeIncome($query) { return $query->where('type', 'income'); }
    public function scopeExpense($query) { return $query->where('type', 'expense'); }

    // ---------- Helper tampilan ----------
    public function isIncome(): bool { return $this->type === 'income'; }
    public function isVoid(): bool { return $this->status === 'void'; }
    public function isFromBilling(): bool { return ! empty($this->billing_record_id); }
    public function hasProof(): bool { return ! empty($this->proof_path); }

    /** Transaksi otomatis dari tagihan & transaksi yang sudah dibatalkan tidak boleh diedit. */
    public function isEditable(): bool
    {
        return ! $this->isVoid() && ! $this->isFromBilling();
    }

    public function typeLabel(): string
    {
        return $this->isIncome() ? 'Pemasukan' : 'Pengeluaran';
    }

    public function formattedAmount(): string
    {
        return self::rupiah($this->amount);
    }

    public static function rupiah($value): string
    {
        $value = (float) $value;

        return ($value < 0 ? '-Rp ' : 'Rp ') . number_format(abs($value), 0, ',', '.');
    }

    /** Format tanggal Indonesia, tidak bergantung pada APP_LOCALE (mis. "23 Oktober 2026" / "23 Okt 2026"). */
    public static function tanggal(?\DateTimeInterface $date, bool $short = false, bool $withTime = false): string
    {
        if (! $date) {
            return '-';
        }

        $full  = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $month = $full[(int) $date->format('n') - 1];
        $month = $short ? mb_substr($month, 0, 3) : $month;

        $text = $date->format('j') . ' ' . $month . ' ' . $date->format('Y');

        return $withTime ? $text . ' ' . $date->format('H:i') : $text;
    }

    public static function paymentMethods(): array
    {
        return ['tunai' => 'Tunai', 'transfer' => 'Transfer Bank', 'lainnya' => 'Lainnya'];
    }

    public function paymentMethodLabel(): string
    {
        return self::paymentMethods()[$this->payment_method] ?? '-';
    }

    public static function generateNumber(string $type, \DateTimeInterface $date): string
    {
        $year   = (int) $date->format('Y');
        $prefix = $type === 'income' ? 'KM' : 'KK';
        $like   = sprintf('%s-%d-', $prefix, $year);

        return DB::transaction(function () use ($like, $prefix, $year) {
            $last = static::where('number', 'like', $like . '%')
                ->orderByDesc('number')
                ->lockForUpdate()
                ->value('number');

            $sequence = $last ? ((int) substr($last, -5)) + 1 : 1;

            return sprintf('%s-%d-%05d', $prefix, $year, $sequence);
        });
    }

    public static function recordFromBilling(BillingRecord $billing): ?self
    {
        if ($billing->status !== 'paid') {
            return null;
        }

        $existing = static::where('billing_record_id', $billing->id)->first();
        if ($existing) {
            return $existing;
        }

        $category = FinanceCategory::where('system_key', 'tagihan_siswa')->first();
        if (! $category) {
            return null;
        }

        $date        = ($billing->paid_at ?? now())->copy();
        $studentName = $billing->student?->name ?? 'Siswa';

        return static::create([
            'number'              => static::generateNumber('income', $date),
            'type'                => 'income',
            'finance_category_id' => $category->id,
            'transaction_date'    => $date->toDateString(),
            'amount'              => $billing->amount,
            'description'         => "{$billing->description} - {$studentName}",
            'party'               => $studentName,
            'payment_method'      => 'tunai',
            'academic_year_id'    => $billing->academic_year_id,
            'billing_record_id'   => $billing->id,
            'status'              => 'posted',
            'created_by'          => auth()->id() ?? $billing->confirmed_by,
        ]);
    }
}