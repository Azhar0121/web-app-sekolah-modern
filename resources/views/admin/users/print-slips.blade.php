<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Slip Akun Guru & Staf TU — {{ $schoolName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #0b294d;
            --primary-light: #1769d5;
            --text-dark: #10223d;
            --text-muted: #5a6b82;
            --border: #d4e0ee;
            --bg-slip: #ffffff;
            --bg-accent: #f4f8fc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-dark);
            background: #eef3f8;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Non-print control bar */
        .no-print-toolbar {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            background: #0b294d;
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .no-print-toolbar .toolbar-info h1 {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 2px;
        }

        .no-print-toolbar .toolbar-info p {
            font-size: 12px;
            color: #b0c6df;
            margin: 0;
        }

        .no-print-toolbar .toolbar-actions {
            display: flex;
            gap: 10px;
        }

        .btn-toolbar {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all .15s ease;
        }

        .btn-toolbar-print {
            background: #1769d5;
            color: #ffffff;
        }
        .btn-toolbar-print:hover {
            background: #125bbd;
        }

        .btn-toolbar-back {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-toolbar-back:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Printable Sheet Container */
        .print-sheet {
            max-width: 210mm;
            margin: 20px auto;
            padding: 10mm;
            background: #ffffff;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
        }

        .slips-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12mm 10mm;
        }

        /* Individual Slip Card */
        .slip-card {
            border: 1.5px dashed #9cb5cf;
            border-radius: 8px;
            padding: 14px 16px;
            background: var(--bg-slip);
            position: relative;
            page-break-inside: avoid;
            break-inside: avoid;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 220px;
        }

        .slip-scissors {
            position: absolute;
            top: -10px;
            right: 14px;
            background: #ffffff;
            padding: 0 4px;
            font-size: 11px;
            color: #8da4be;
        }

        .slip-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .slip-school {
            font-size: 11px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .slip-badge {
            font-size: 9.5px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .slip-badge-guru {
            background: #e8f4ff;
            color: #125bbd;
            border: 1px solid #c2defc;
        }

        .slip-badge-tu {
            background: #fdf3e7;
            color: #a85400;
            border: 1px solid #fae1c3;
        }

        .slip-title {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .slip-name {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 10px;
            line-height: 1.25;
        }

        .slip-credentials {
            background: var(--bg-accent);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 9px 11px;
            margin-bottom: 10px;
        }

        .slip-field {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .slip-field:last-child {
            margin-bottom: 0;
        }

        .slip-field-label {
            color: var(--text-muted);
            font-weight: 600;
        }

        .slip-field-value {
            color: var(--text-dark);
            font-weight: 700;
            text-align: right;
            word-break: break-all;
        }

        .slip-password-box {
            font-family: 'JetBrains Mono', monospace;
            background: #ffffff;
            border: 1px solid #bfd5ec;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 12px;
            letter-spacing: 0.8px;
            color: #0b294d;
        }

        .slip-portal-url {
            font-size: 10px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 8px;
        }

        .slip-portal-url strong {
            color: var(--primary-light);
        }

        .slip-footer-note {
            font-size: 8.5px;
            color: #7b8ea5;
            line-height: 1.35;
            border-top: 1px dashed #dbe5f0;
            padding-top: 6px;
            font-style: italic;
        }

        /* PRINT STYLES */
        @media print {
            body {
                background: #ffffff !important;
            }

            .no-print-toolbar {
                display: none !important;
            }

            .print-sheet {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            .slips-grid {
                gap: 8mm 6mm !important;
            }

            .slip-card {
                border-color: #666666 !important;
                background: #ffffff !important;
            }

            @page {
                size: A4 portrait;
                margin: 12mm 10mm;
            }
        }
    </style>
</head>
<body>

    {{-- Non-print Toolbar --}}
    <div class="no-print-toolbar">
        <div class="toolbar-info">
            <h1><i class="bi bi-printer-fill me-2"></i>Cetak Slip Akun Guru & Staf TU</h1>
            <p>Total {{ count($credentials) }} slip akun siap dicetak di kertas A4 (isi 6–8 slip per lembar). Potong di sepanjang garis putus-putus.</p>
        </div>
        <div class="toolbar-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-toolbar btn-toolbar-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Kelola User
            </a>
            <button type="button" onclick="window.print()" class="btn-toolbar btn-toolbar-print">
                <i class="bi bi-printer"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Printable Sheet --}}
    <div class="print-sheet">
        <div class="slips-grid">
            @foreach ($credentials as $cred)
                <div class="slip-card">
                    <span class="slip-scissors"><i class="bi bi-scissors"></i> gunting</span>

                    <div>
                        <div class="slip-header">
                            <span class="slip-school">{{ $schoolName }}</span>
                            <span class="slip-badge {{ ($cred['role_slug'] ?? '') === 'tu' ? 'slip-badge-tu' : 'slip-badge-guru' }}">
                                {{ $cred['role_name'] ?? strtoupper($cred['role_slug'] ?? 'PENGGUNA') }}
                            </span>
                        </div>

                        <div class="slip-title">Kartu Akses Portal Sekolah</div>
                        <div class="slip-name">{{ $cred['name'] }}</div>

                        <div class="slip-credentials">
                            <div class="slip-field">
                                <span class="slip-field-label">Email Login</span>
                                <span class="slip-field-value">{{ $cred['email'] }}</span>
                            </div>
                            <div class="slip-field">
                                <span class="slip-field-label">Password Awal</span>
                                <span class="slip-field-value">
                                    <span class="slip-password-box">{{ $cred['plain_password'] }}</span>
                                </span>
                            </div>
                        </div>

                        <div class="slip-portal-url">
                            <i class="bi bi-globe2"></i> Alamat Login: <strong>{{ url('/login') }}</strong>
                        </div>
                    </div>

                    <div class="slip-footer-note">
                        * Simpan kartu ini dengan baik. Jangan bagikan password kepada siswa. Bapak/Ibu dapat mengubah password secara mandiri di menu Profil setelah login.
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>
