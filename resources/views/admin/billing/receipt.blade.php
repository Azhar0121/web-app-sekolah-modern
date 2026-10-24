<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi Pembayaran #{{ str_pad($billing->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            color: #2c3e50;
            padding: 24px;
            font-size: 13px;
            line-height: 1.5;
        }

        .receipt-container {
            max-width: 680px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #dcdfe6;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            position: relative;
        }

        /* Kop Kuitansi */
        .receipt-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #0b294d;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .receipt-brand h1 {
            font-size: 20px;
            font-weight: 800;
            color: #0b294d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .receipt-brand p {
            font-size: 12px;
            color: #606266;
        }

        .receipt-title-box {
            text-align: right;
        }

        .receipt-title-box .badge-title {
            display: inline-block;
            background: #0b294d;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 4px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .receipt-title-box .receipt-number {
            margin-top: 6px;
            font-size: 12px;
            color: #606266;
            font-weight: 600;
        }

        /* Detail Siswa */
        .receipt-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 14px 18px;
            margin-bottom: 22px;
        }

        .meta-item {
            display: flex;
            font-size: 13px;
        }

        .meta-item .label {
            width: 110px;
            color: #64748b;
            flex-shrink: 0;
        }

        .meta-item .val {
            font-weight: 600;
            color: #1e293b;
        }

        /* Tabel Rincian */
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .receipt-table th {
            background: #f1f5f9;
            color: #0b294d;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 10px 14px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }

        .receipt-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }

        .receipt-table td.text-right,
        .receipt-table th.text-right {
            text-align: right;
        }

        /* Total Box */
        .receipt-total {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }

        .total-card {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 12px 20px;
            min-width: 280px;
            text-align: right;
        }

        .total-card .total-label {
            font-size: 12px;
            color: #166534;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .total-card .total-amount {
            font-size: 22px;
            font-weight: 800;
            color: #15803d;
            margin-top: 2px;
        }

        .terbilang-box {
            background: #fffbeb;
            border-left: 3px solid #f59e0b;
            padding: 8px 14px;
            font-size: 12px;
            font-style: italic;
            color: #92400e;
            margin-bottom: 24px;
            border-radius: 0 4px 4px 0;
        }

        /* Status Stempel */
        .stamp-lunas {
            display: inline-block;
            border: 3px solid #16a34a;
            color: #16a34a;
            font-weight: 900;
            font-size: 18px;
            padding: 4px 16px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 2px;
            transform: rotate(-8deg);
            opacity: 0.85;
        }

        /* Tanda Tangan */
        .receipt-signature {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 30px;
            padding-top: 10px;
        }

        .signature-box {
            width: 220px;
            text-align: center;
        }

        .signature-box .date-line {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 60px;
        }

        .signature-box .name-line {
            font-weight: 700;
            color: #0f172a;
            border-top: 1px solid #94a3b8;
            padding-top: 4px;
        }

        .signature-box .role-line {
            font-size: 11px;
            color: #64748b;
        }

        /* Footer Nota */
        .receipt-footer-note {
            margin-top: 28px;
            padding-top: 12px;
            border-top: 1px dashed #cbd5e1;
            font-size: 11px;
            color: #94a3b8;
            text-align: center;
        }

        /* Action Toolbar */
        .action-toolbar {
            max-width: 680px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-print {
            background: #0b294d;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-print:hover {
            background: #1769d5;
        }

        .btn-close-window {
            background: #e2e8f0;
            color: #475569;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-close-window:hover {
            background: #cbd5e1;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .action-toolbar {
                display: none;
            }

            .receipt-container {
                box-shadow: none;
                border: none;
                padding: 10px;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

@php
    $schoolName = \App\Models\Setting::get('school_name', config('app.name'));
    $schoolAddress = \App\Models\Setting::get('school_address', 'Jl. Pendidikan No. 1, Kota Sekolah');
    $schoolPhone = \App\Models\Setting::get('school_phone', '-');
    $student = $billing->student;
    $profile = $student?->studentProfile;
    $receiptNo = 'KWT-' . date('Ymd', strtotime($billing->paid_at ?: $billing->updated_at)) . '-' . str_pad($billing->id, 5, '0', STR_PAD_LEFT);
@endphp

<div class="action-toolbar">
    <button class="btn-close-window" onclick="window.close()">← Tutup / Kembali</button>
    <button class="btn-print" onclick="window.print()">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
        </svg>
        Cetak Kuitansi (PDF / Print)
    </button>
</div>

<div class="receipt-container">

    <div class="receipt-header">
        <div class="receipt-brand">
            <h1>{{ $schoolName }}</h1>
            <p>{{ $schoolAddress }} &bull; Telp: {{ $schoolPhone }}</p>
        </div>
        <div class="receipt-title-box">
            <span class="badge-title">Kuitansi Pembayaran</span>
            <div class="receipt-number">No: {{ $receiptNo }}</div>
        </div>
    </div>

    <div class="receipt-meta">
        <div class="meta-item">
            <span class="label">Nama Siswa</span>
            <span class="val">: {{ $student?->name ?? '-' }}</span>
        </div>
        <div class="meta-item">
            <span class="label">Tgl. Bayar</span>
            <span class="val">: {{ $billing->paid_at ? \Carbon\Carbon::parse($billing->paid_at)->translatedFormat('d F Y') : '-' }}</span>
        </div>
        <div class="meta-item">
            <span class="label">NISN</span>
            <span class="val">: {{ $profile?->nisn ?? '-' }}</span>
        </div>
        <div class="meta-item">
            <span class="label">Tahun Ajaran</span>
            <span class="val">: {{ $billing->academicYear?->name ?? '-' }}</span>
        </div>
        <div class="meta-item">
            <span class="label">Kelas</span>
            <span class="val">: {{ $classroom?->name ?? 'Siswa Terdaftar' }}</span>
        </div>
        <div class="meta-item">
            <span class="label">Metode Bayar</span>
            <span class="val">: Tunai / Transfer Kas</span>
        </div>
    </div>

    <table class="receipt-table">
        <thead>
            <tr>
                <th style="width: 45px;">No.</th>
                <th>Uraian Pembayaran</th>
                <th class="text-right" style="width: 170px;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    <strong>{{ $billing->description }}</strong>
                    @if($billing->notes)
                        <br><small style="color: #64748b;">Catatan: {{ $billing->notes }}</small>
                    @endif
                </td>
                <td class="text-right">
                    <strong>Rp {{ number_format($billing->amount, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="receipt-total">
        <div class="total-card">
            <div class="total-label">Total Dibayar</div>
            <div class="total-amount">Rp {{ number_format($billing->amount, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="receipt-signature">
        <div style="padding-bottom: 10px;">
            <div class="stamp-lunas">LUNAS</div>
        </div>

        <div class="signature-box">
            <div class="date-line">
                Dicetak pada {{ now()->translatedFormat('d F Y') }}<br>
                Petugas Tata Usaha,
            </div>
            <div class="name-line">{{ $billing->confirmedBy?->name ?? auth()->user()->name }}</div>
            <div class="role-line">Bagian Administrasi Keuangan</div>
        </div>
    </div>

    <div class="receipt-footer-note">
        Simpan kuitansi ini sebagai bukti pembayaran yang sah. Dokumen ini diterbitkan secara resmi melalui Sistem Informasi {{ $schoolName }}.
    </div>

</div>

</body>
</html>
