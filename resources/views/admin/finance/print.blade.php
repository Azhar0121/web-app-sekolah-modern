<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan {{ $year }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #111; margin: 24px; }
        h1 { font-size: 18px; margin: 0 0 2px; }
        h2 { font-size: 14px; margin: 24px 0 8px; }
        .muted { color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #999; padding: 5px 8px; }
        th { background: #eee; text-align: left; }
        .r { text-align: right; }
        tfoot td { font-weight: bold; background: #f5f5f5; }
        .summary td { border: none; padding: 2px 0; }
        .sign { margin-top: 48px; display: flex; justify-content: flex-end; }
        .sign div { width: 240px; text-align: center; }
        .noprint { margin-bottom: 16px; }
        @media print { .noprint { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
@use('App\Models\FinanceTransaction', 'Tx')
@php($schoolName = \App\Models\Setting::get('school_name', config('app.name')))

<div class="noprint">
    <button onclick="window.print()">Cetak / Simpan sebagai PDF</button>
    <button onclick="window.close()">Tutup</button>
</div>

<h1>Laporan Keuangan {{ $schoolName }}</h1>
<div class="muted">Periode: 1 Januari - 31 Desember {{ $year }} &middot; Dicetak {{ Tx::tanggal(now(), false, true) }}</div>

<h2>Ringkasan</h2>
<table class="summary">
    <tr><td style="width:200px">Saldo awal</td><td>: {{ Tx::rupiah($openingBalance) }}</td></tr>
    <tr><td>Total pemasukan</td><td>: {{ Tx::rupiah($totalIncome) }}</td></tr>
    <tr><td>Total pengeluaran</td><td>: {{ Tx::rupiah($totalExpense) }}</td></tr>
    <tr><td><strong>Saldo akhir</strong></td><td>: <strong>{{ Tx::rupiah($closingBalance) }}</strong></td></tr>
</table>

<h2>Rekap Bulanan</h2>
<table>
    <thead>
        <tr><th>Bulan</th><th class="r">Pemasukan</th><th class="r">Pengeluaran</th><th class="r">Selisih</th><th class="r">Saldo Akhir</th></tr>
    </thead>
    <tbody>
        @foreach ($months as $m)
            <tr>
                <td>{{ $m['label'] }}</td>
                <td class="r">{{ $m['income'] > 0 ? Tx::rupiah($m['income']) : '-' }}</td>
                <td class="r">{{ $m['expense'] > 0 ? Tx::rupiah($m['expense']) : '-' }}</td>
                <td class="r">{{ $m['net'] != 0 ? Tx::rupiah($m['net']) : '-' }}</td>
                <td class="r">{{ Tx::rupiah($m['balance']) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>Total</td>
            <td class="r">{{ Tx::rupiah($totalIncome) }}</td>
            <td class="r">{{ Tx::rupiah($totalExpense) }}</td>
            <td class="r">{{ Tx::rupiah($totalIncome - $totalExpense) }}</td>
            <td class="r">{{ Tx::rupiah($closingBalance) }}</td>
        </tr>
    </tfoot>
</table>

@foreach ([['Pemasukan per Kategori', $incomeByCategory, $totalIncome], ['Pengeluaran per Kategori', $expenseByCategory, $totalExpense]] as [$title, $rows, $grand])
    <h2>{{ $title }}</h2>
    <table>
        <thead><tr><th>Kategori</th><th class="r">Jumlah Transaksi</th><th class="r">Total</th><th class="r">%</th></tr></thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row->category?->name ?? 'Tanpa kategori' }}</td>
                    <td class="r">{{ $row->count }}</td>
                    <td class="r">{{ Tx::rupiah($row->total) }}</td>
                    <td class="r">{{ $grand > 0 ? round(($row->total / $grand) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
@endforeach

<div class="sign">
    <div>
        Mengetahui,<br>Kepala Sekolah
        <br><br><br><br>
        ( ................................... )
    </div>
    <div>
        Bendahara / Tata Usaha
        <br><br><br><br>
        ( ................................... )
    </div>
</div>

</body>
</html>
