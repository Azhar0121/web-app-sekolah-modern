
@use('App\Models\FinanceTransaction', 'Tx')

@php
    $schoolName = \App\Models\Setting::get('school_name', config('app.name'));
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Keuangan {{ $year }}</title>

    <style>
        :root {
            --navy: #102a49;
            --navy-light: #1b426b;
            --blue-soft: #edf3fa;
            --border: #d9e1ea;
            --text: #243247;
            --muted: #64748b;
            --green: #16805d;
            --red: #c44545;
            --surface: #f6f8fb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #eef2f7;
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.55;
        }

        .report {
            max-width: 1050px;
            margin: 0 auto;
            padding: 36px 40px;
            background: #fff;
            border: 1px solid #e1e7ef;
            box-shadow: 0 8px 28px rgba(16, 42, 73, 0.07);
        }

        /* Toolbar */

        .toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            max-width: 1050px;
            margin: 0 auto 16px;
        }

        .toolbar button {
            padding: 10px 16px;
            border: 1px solid var(--navy);
            border-radius: 6px;
            background: var(--navy);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .toolbar button:hover {
            background: var(--navy-light);
        }

        .toolbar .btn-secondary {
            background: #fff;
            color: var(--navy);
        }

        .toolbar .btn-secondary:hover {
            background: var(--blue-soft);
        }

        /* Header */

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            padding-bottom: 22px;
            border-bottom: 3px solid var(--navy);
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 7px;
            color: var(--navy-light);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            color: var(--navy);
            font-size: 23px;
            line-height: 1.3;
            font-weight: 800;
        }

        .school-name {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .report-meta {
            min-width: 150px;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: var(--surface);
        }

        .meta-label {
            display: block;
            margin-bottom: 4px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .meta-value {
            color: var(--navy);
            font-size: 15px;
            font-weight: 800;
        }

        .printed-at {
            margin-top: 6px;
            color: var(--muted);
            font-size: 10px;
        }

        /* Section */

        .section {
            margin-top: 27px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 12px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
        }

        .section-number {
            display: inline-flex;
            width: 25px;
            height: 25px;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 5px;
            background: var(--blue-soft);
            color: var(--navy);
            font-size: 11px;
        }

        .section-description {
            margin: -5px 0 12px;
            color: var(--muted);
            font-size: 11px;
        }

        /* Summary */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .summary-card {
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-left: 4px solid var(--navy);
            border-radius: 6px;
            background: #fff;
        }

        .summary-card.income {
            border-left-color: var(--green);
        }

        .summary-card.expense {
            border-left-color: var(--red);
        }

        .summary-card.balance {
            border-left-color: var(--navy-light);
            background: var(--blue-soft);
        }

        .summary-label {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
        }

        .summary-value {
            color: var(--navy);
            font-size: 17px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .income .summary-value {
            color: var(--green);
        }

        .expense .summary-value {
            color: var(--red);
        }

        /* Tables */

        table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            border-spacing: 0;
            font-size: 11px;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        th {
            padding: 10px 9px;
            border: 1px solid var(--navy);
            background: var(--navy);
            color: #fff;
            font-weight: 700;
            text-align: left;
        }

        td {
            padding: 9px;
            border: 1px solid var(--border);
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: #f7f9fc;
        }

        tbody tr:nth-child(odd) {
            background: #fff;
        }

        .text-right {
            text-align: right;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .text-center {
            text-align: center;
        }

        .muted {
            color: var(--muted);
        }

        tfoot td {
            background: var(--blue-soft);
            color: var(--navy);
            font-weight: 800;
            border-top: 2px solid var(--navy);
        }

        .summary-table {
            width: 100%;
            max-width: 540px;
        }

        .summary-table td {
            padding: 5px 0;
            border: none;
            background: transparent;
        }

        .summary-table .summary-total td {
            padding-top: 9px;
            border-top: 1px solid var(--border);
            color: var(--navy);
            font-weight: 800;
        }

        .category-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
            padding: 10px 13px;
            border: 1px solid var(--border);
            border-radius: 5px;
            background: var(--surface);
        }

        .category-title strong {
            color: var(--navy);
            font-size: 12px;
        }

        .category-title span {
            color: var(--muted);
            font-size: 10px;
        }

        .category-section {
            margin-top: 22px;
        }

        /* Signature */

        .signature-area {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin-top: 55px;
            page-break-inside: avoid;
        }

        .signature {
            text-align: center;
        }

        .signature-role {
            min-height: 36px;
            color: var(--text);
            font-size: 11px;
        }

        .signature-space {
            height: 72px;
        }

        .signature-line {
            width: 80%;
            margin: 0 auto;
            padding-top: 6px;
            border-top: 1px solid #64748b;
            color: var(--text);
            font-size: 11px;
        }

        .report-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 9px;
            text-align: center;
        }

        /* Print / PDF */

        @page {
            size: A4 portrait;
            margin: 13mm;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background: #fff;
                font-size: 10px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .noprint {
                display: none !important;
            }

            .report {
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 0;
                border: none;
                box-shadow: none;
            }

            .report-header {
                padding-bottom: 14px;
            }

            h1 {
                font-size: 20px;
            }

            .section {
                margin-top: 19px;
            }

            .section-heading {
                margin-bottom: 9px;
            }

            .summary-card {
                padding: 10px 12px;
            }

            .summary-value {
                font-size: 14px;
            }

            th,
            td {
                padding: 6px 7px;
            }

            .category-section {
                margin-top: 16px;
            }

            .signature-area {
                margin-top: 38px;
            }

            .report-footer {
                margin-top: 20px;
            }
        }

        @media screen and (max-width: 650px) {
            body {
                padding: 12px;
            }

            .report {
                padding: 20px 15px;
            }

            .report-header {
                flex-direction: column;
            }

            .report-meta {
                width: 100%;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .table-wrap {
                overflow-x: auto;
            }

            table {
                min-width: 600px;
            }

            .summary-table {
                min-width: 0;
            }

            .signature-area {
                gap: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="toolbar noprint">
        <button type="button" onclick="window.print()">
            Cetak / Simpan sebagai PDF
        </button>

        <button type="button" class="btn-secondary" onclick="window.close()">
            Tutup
        </button>
    </div>

    <main class="report">
        <header class="report-header">
            <div>
                <span class="eyebrow">Laporan Resmi · Keuangan Sekolah</span>
                <h1>Laporan Keuangan</h1>
                <div class="school-name">{{ $schoolName }}</div>
                <div class="printed-at">
                    Dicetak pada {{ Tx::tanggal(now(), false, true) }}
                </div>
            </div>

            <div class="report-meta">
                <span class="meta-label">Periode Laporan</span>
                <div class="meta-value">Tahun {{ $year }}</div>
                <div class="printed-at">1 Januari – 31 Desember {{ $year }}</div>
            </div>
        </header>

        <section class="section">
            <h2 class="section-heading">
                <span class="section-number">01</span>
                Ringkasan Keuangan
            </h2>

            <div class="summary-grid">
                <div class="summary-card">
                    <span class="summary-label">Saldo Awal</span>
                    <div class="summary-value">{{ Tx::rupiah($openingBalance) }}</div>
                </div>

                <div class="summary-card income">
                    <span class="summary-label">Total Pemasukan</span>
                    <div class="summary-value">{{ Tx::rupiah($totalIncome) }}</div>
                </div>

                <div class="summary-card expense">
                    <span class="summary-label">Total Pengeluaran</span>
                    <div class="summary-value">{{ Tx::rupiah($totalExpense) }}</div>
                </div>

                <div class="summary-card balance">
                    <span class="summary-label">Saldo Akhir</span>
                    <div class="summary-value">{{ Tx::rupiah($closingBalance) }}</div>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-heading">
                <span class="section-number">02</span>
                Rekapitulasi Bulanan
            </h2>

            <p class="section-description">
                Rincian pemasukan, pengeluaran, selisih, dan saldo akhir setiap bulan.
            </p>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th class="text-right">Pemasukan</th>
                            <th class="text-right">Pengeluaran</th>
                            <th class="text-right">Selisih</th>
                            <th class="text-right">Saldo Akhir</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($months as $m)
                            <tr>
                                <td>{{ $m['label'] }}</td>
                                <td class="text-right">
                                    {{ $m['income'] > 0 ? Tx::rupiah($m['income']) : '-' }}
                                </td>
                                <td class="text-right">
                                    {{ $m['expense'] > 0 ? Tx::rupiah($m['expense']) : '-' }}
                                </td>
                                <td class="text-right">
                                    {{ $m['net'] != 0 ? Tx::rupiah($m['net']) : '-' }}
                                </td>
                                <td class="text-right">{{ Tx::rupiah($m['balance']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td class="text-right">{{ Tx::rupiah($totalIncome) }}</td>
                            <td class="text-right">{{ Tx::rupiah($totalExpense) }}</td>
                            <td class="text-right">
                                {{ Tx::rupiah($totalIncome - $totalExpense) }}
                            </td>
                            <td class="text-right">{{ Tx::rupiah($closingBalance) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        @foreach ([
            ['Pemasukan per Kategori', $incomeByCategory, $totalIncome],
            ['Pengeluaran per Kategori', $expenseByCategory, $totalExpense]
        ] as [$title, $rows, $grand])
            <section class="category-section">
                <div class="category-title">
                    <strong>{{ $title }}</strong>
                    <span>{{ $rows->count() }} kategori</span>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th class="text-right">Jumlah Transaksi</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Persentase</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row->category?->name ?? 'Tanpa kategori' }}</td>
                                    <td class="text-right">{{ $row->count }}</td>
                                    <td class="text-right">{{ Tx::rupiah($row->total) }}</td>
                                    <td class="text-right">
                                        {{ $grand > 0 ? round(($row->total / $grand) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="muted text-center">
                                        Tidak ada data kategori pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if ($rows->isNotEmpty())
                            <tfoot>
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td class="text-right">{{ Tx::rupiah($grand) }}</td>
                                    <td class="text-right">{{ $grand > 0 ? '100%' : '0%' }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </section>
        @endforeach

        <section class="signature-area">
            <div class="signature">
                <div class="signature-role">
                    Mengetahui,<br>
                    Kepala Sekolah
                </div>
                <div class="signature-space"></div>
                <div class="signature-line">( ........................................ )</div>
            </div>

            <div class="signature">
                <div class="signature-role">
                    Bendahara / Tata Usaha
                </div>
                <div class="signature-space"></div>
                <div class="signature-line">( ........................................ )</div>
            </div>
        </section>

        <footer class="report-footer">
            Dokumen laporan keuangan sekolah · Periode {{ $year }}
        </footer>
    </main>
</body>
</html>
