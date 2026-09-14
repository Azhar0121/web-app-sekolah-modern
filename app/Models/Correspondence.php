<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Correspondence extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'number', 'letter_date', 'category', 'subject', 'correspondent',
        'description', 'disposition', 'status', 'file_path', 'file_original_name', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'letter_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hasFile(): bool
    {
        return ! empty($this->file_path);
    }

    /** Status default & pilihan status yang valid, tergantung jenis surat. */
    public static function statusOptions(string $type): array
    {
        return $type === 'masuk'
            ? ['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai']
            : ['draft' => 'Draft', 'terkirim' => 'Terkirim'];
    }

    public static function defaultStatus(string $type): string
    {
        return $type === 'masuk' ? 'baru' : 'draft';
    }

    public static function categoryOptions(): array
    {
        return ['Undangan', 'Pemberitahuan', 'Permohonan', 'Keputusan', 'Edaran', 'Lainnya'];
    }

    /**
     * Generate nomor otomatis berdasarkan tanggal surat & jenisnya.
     * - Surat Masuk : nomor agenda internal, format "SM-{tahun}-{5 digit urut}".
     * - Surat Keluar: format resmi umum sekolah/instansi Indonesia,
     *   "{urut}/{kode sekolah}/{bulan romawi}/{tahun}", urut & romawi
     *   mengikuti tahun & bulan dari tanggal surat, RESET tiap tahun.
     */
    public static function generateNumber(string $type, \DateTimeInterface $letterDate): string
    {
        $year = (int) $letterDate->format('Y');

        return DB::transaction(function () use ($type, $letterDate, $year) {
            $count = static::where('type', $type)
                ->whereYear('letter_date', $year)
                ->lockForUpdate()
                ->count();

            $sequence = $count + 1;

            if ($type === 'masuk') {
                return sprintf('SM-%d-%05d', $year, $sequence);
            }

            $roman = self::toRoman((int) $letterDate->format('n'));
            $abbreviation = config('school.abbreviation', 'SEKOLAH');

            return sprintf('%03d/%s/%s/%d', $sequence, $abbreviation, $roman, $year);
        });
    }

    private static function toRoman(int $month): string
    {
        $map = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        return $map[$month - 1] ?? 'I';
    }
}
