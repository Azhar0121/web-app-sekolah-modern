@extends('layouts.app')

@section('title', 'Info Tagihan — ' . $student->name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/ortu/billing/index.css') }}">

<div class="ortu-billing-page">

    @include('layouts.partials.announcements-banner')

    {{-- HERO --}}
    <section class="ob-hero">
        <div class="ob-hero-content">
            <span class="ob-eyebrow">
                <i class="bi bi-wallet2"></i>
                PORTAL ORANG TUA / WALI
            </span>

            <h1>Info Tagihan</h1>

            <p>
                Pantau rincian biaya pendidikan, status pembayaran,
                dan jatuh tempo tagihan untuk
                <strong>{{ $student->name }}</strong>.
            </p>

            <div class="ob-hero-meta">
                <span>
                    <i class="bi bi-person-check"></i>
                    {{ $student->name }}
                </span>
                <span>
                    <i class="bi bi-receipt"></i>
                    Informasi administrasi siswa
                </span>
            </div>
        </div>

        <div class="ob-hero-icon">
            <i class="bi bi-cash-stack"></i>
        </div>
    </section>

    {{-- RINGKASAN PEMBAYARAN --}}
    <section class="ob-summary-section">
        <div class="ob-section-heading">
            <div class="ob-section-heading-copy">
                <span class="ob-section-label">RINGKASAN KEUANGAN</span>
                <h2>Ringkasan Pembayaran</h2>
                <p>Ikhtisar total tagihan berdasarkan status pembayaran.</p>
            </div>

            <div class="ob-heading-icon">
                <i class="bi bi-bar-chart-line"></i>
            </div>
        </div>

        <div class="ob-summary-grid">
            <article class="ob-summary-card ob-unpaid">
                <div class="ob-summary-top">
                    <div class="ob-summary-icon">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                    <span class="ob-summary-status">Perlu dibayar</span>
                </div>

                <span class="ob-summary-label">Total Belum Lunas</span>

                <strong class="ob-summary-amount">
                    Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                </strong>

                <div class="ob-summary-foot">
                    <i class="bi bi-info-circle"></i>
                    Total kewajiban yang belum dibayar
                </div>
            </article>

            <article class="ob-summary-card ob-paid">
                <div class="ob-summary-top">
                    <div class="ob-summary-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <span class="ob-summary-status">Pembayaran selesai</span>
                </div>

                <span class="ob-summary-label">Total Telah Dibayar</span>

                <strong class="ob-summary-amount">
                    Rp {{ number_format($totalPaid, 0, ',', '.') }}
                </strong>

                <div class="ob-summary-foot">
                    <i class="bi bi-shield-check"></i>
                    Total pembayaran yang telah lunas
                </div>
            </article>
        </div>
    </section>

    {{-- RINCIAN TAGIHAN --}}
    <section class="ob-billing-section">
        <div class="ob-table-card">

            <div class="ob-table-heading">
                <div class="ob-table-heading-main">
                    <div class="ob-table-heading-icon">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>

                    <div>
                        <span class="ob-section-label">DAFTAR ADMINISTRASI</span>
                        <h2>Rincian Tagihan</h2>
                        <p>Periksa nominal, jatuh tempo, dan status setiap tagihan.</p>
                    </div>
                </div>

                <span class="ob-total-count">
                    <i class="bi bi-list-check"></i>
                    {{ $billings->count() }} tagihan
                </span>
            </div>

            <div class="table-responsive ob-table-responsive">
                <table class="table ob-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ob-number-column">NO.</th>
                            <th>DESKRIPSI TAGIHAN</th>
                            <th>JATUH TEMPO</th>
                            <th>NOMINAL</th>
                            <th>STATUS</th>
                            <th>TANGGAL LUNAS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($billings as $billing)
                            <tr>
                                <td class="ob-number-column">
                                    <span class="ob-row-number">{{ $loop->iteration }}</span>
                                </td>

                                <td>
                                    <div class="ob-description">
                                        <span class="ob-description-icon">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </span>

                                        <div class="ob-description-content">
                                            <strong>{{ $billing->description }}</strong>

                                            @if ($billing->notes)
                                                <small>{{ $billing->notes }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="ob-date">
                                        <i class="bi bi-calendar-event"></i>
                                        {{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('d/m/Y') : '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="ob-amount">
                                        Rp {{ number_format($billing->amount, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td>
                                    @if ($billing->status === 'paid')
                                        <span class="ob-status ob-status-paid">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Lunas
                                        </span>
                                    @else
                                        <span class="ob-status ob-status-unpaid">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($billing->paid_at)
                                        <span class="ob-date ob-paid-date">
                                            <i class="bi bi-calendar-check"></i>
                                            {{ \Carbon\Carbon::parse($billing->paid_at)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="ob-no-date">Belum dibayar</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="ob-empty-state">
                                        <div class="ob-empty-icon">
                                            <i class="bi bi-receipt"></i>
                                        </div>

                                        <h3>Belum Ada Data Tagihan</h3>

                                        <p>
                                            Belum ada tagihan yang tercatat untuk
                                            <strong>{{ $student->name }}</strong>.
                                            Informasi tagihan akan ditampilkan di sini jika tersedia.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="ob-table-footer">
                <span>
                    <i class="bi bi-shield-check"></i>
                    Informasi administrasi dan pembayaran siswa
                </span>
            </div>
        </div>
    </section>

    {{-- KEMBALI --}}
    <div class="ob-footer">
        <a href="{{ route('ortu.dashboard') }}" class="ob-back-button">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Dashboard
        </a>

        <span class="ob-footer-note">
            <i class="bi bi-lock-fill"></i>
            Informasi pembayaran siswa
        </span>
    </div>

</div>
@endsection