@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Izin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ortu/leave-requests/index.css') }}">

<div class="ortu-leave-page">
    <div class="olp-container">

        {{-- PENGUMUMAN --}}
        @include('layouts.partials.announcements-banner')

        {{-- HERO --}}
        <section class="olp-hero">
            <div class="olp-hero-content">
                <span class="olp-eyebrow">
                    <i class="bi bi-calendar2-check-fill"></i>
                    PORTAL ORANG TUA / WALI
                </span>

                <h1>Riwayat Pengajuan Izin</h1>

                <p>
                    Pantau pengajuan izin anak, periksa status persetujuan,
                    dan baca catatan dari guru dalam satu halaman.
                </p>

                <div class="olp-hero-meta">
                    <span>
                        <i class="bi bi-clipboard-check"></i>
                        Riwayat pengajuan
                    </span>
                    <span>
                        <i class="bi bi-chat-left-text"></i>
                        Catatan guru
                    </span>
                </div>
            </div>

            <div class="olp-hero-visual" aria-hidden="true">
                <div class="olp-visual-circle olp-circle-one"></div>
                <div class="olp-visual-circle olp-circle-two"></div>

                <div class="olp-illustration-card">
                    <div class="olp-illustration-icon">
                        <i class="bi bi-calendar2-check-fill"></i>
                    </div>
                    <div class="olp-illustration-line olp-line-wide"></div>
                    <div class="olp-illustration-line olp-line-medium"></div>
                    <div class="olp-illustration-line olp-line-short"></div>
                    <div class="olp-illustration-check">
                        <i class="bi bi-check-lg"></i>
                    </div>
                </div>
            </div>
        </section>

        {{-- JUDUL DAN TOMBOL AJUKAN IZIN --}}
        <section class="olp-history-section">
            <div class="olp-section-heading">
                <div class="olp-section-copy">
                    <span class="olp-section-label">DATA KEHADIRAN</span>
                    <h2>Daftar Pengajuan Izin</h2>
                    <p>
                        Pantau semua pengajuan izin yang tercatat,
                        termasuk status dan tanggapan dari guru.
                    </p>
                </div>

                <a href="{{ route('ortu.leave-requests.create') }}"
                   class="olp-create-button">
                    <i class="bi bi-plus-lg"></i>
                    <span>Ajukan Izin Baru</span>
                </a>
            </div>

            {{-- NOTIFIKASI SUKSES --}}
            @if(session('success'))
                <div class="olp-alert-success" role="status">
                    <span class="olp-alert-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            {{-- TABEL RIWAYAT --}}
            <div class="olp-table-card">

                <div class="olp-table-heading">
                    <div class="olp-table-heading-icon">
                        <i class="bi bi-list-check"></i>
                    </div>

                    <div class="olp-table-heading-copy">
                        <h3>Riwayat Izin</h3>
                        <p>Informasi pengajuan dan tindak lanjut dari guru.</p>
                    </div>

                    <div class="olp-record-count">
                        <i class="bi bi-files"></i>
                        <span>{{ $requests->total() }} pengajuan</span>
                    </div>
                </div>

                <div class="table-responsive olp-table-responsive">
                    <table class="table olp-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama Anak</th>
                                <th>Jenis Izin</th>
                                <th>Tanggal</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Catatan Guru</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($requests as $req)
                                <tr>
                                    {{-- NAMA ANAK --}}
                                    <td>
                                        <div class="olp-student-cell">
                                            <span class="olp-student-icon">
                                                <i class="bi bi-person-fill"></i>
                                            </span>
                                            <span class="olp-student-name">
                                                {{ $req->student->name }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- JENIS IZIN --}}
                                    <td>
                                        @if($req->type === 'sakit')
                                            <span class="olp-type-badge olp-type-sick">
                                                <i class="bi bi-heart-pulse-fill"></i>
                                                Sakit
                                            </span>
                                        @else
                                            <span class="olp-type-badge olp-type-permission">
                                                <i class="bi bi-calendar-event-fill"></i>
                                                {{ ucfirst($req->type) }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- TANGGAL --}}
                                    <td>
                                        <div class="olp-date-cell">
                                            <i class="bi bi-calendar3"></i>
                                            <span>
                                                {{ \Carbon\Carbon::parse($req->start_date)->format('d/m/Y') }}

                                                @if($req->start_date != $req->end_date)
                                                    <span class="olp-date-separator">s.d.</span>
                                                    {{ \Carbon\Carbon::parse($req->end_date)->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    {{-- ALASAN --}}
                                    <td>
                                        <div class="olp-reason-cell">
                                            {{ \Illuminate\Support\Str::limit($req->reason, 50) }}
                                        </div>
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($req->status === 'pending')
                                            <span class="olp-status-badge olp-status-pending">
                                                <span class="olp-status-dot"></span>
                                                Menunggu
                                            </span>
                                        @elseif($req->status === 'approved')
                                            <span class="olp-status-badge olp-status-approved">
                                                <span class="olp-status-dot"></span>
                                                Disetujui
                                            </span>
                                        @elseif($req->status === 'rejected')
                                            <span class="olp-status-badge olp-status-rejected">
                                                <span class="olp-status-dot"></span>
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="olp-status-badge olp-status-unknown">
                                                {{ ucfirst($req->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- CATATAN GURU --}}
                                    <td>
                                        <div class="olp-notes-cell">
                                            <i class="bi bi-chat-left-text"></i>
                                            <span>{{ $req->process_notes ?: 'Belum ada catatan' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="olp-empty-state">
                                            <div class="olp-empty-icon">
                                                <i class="bi bi-calendar2-x"></i>
                                            </div>

                                            <h4>Belum Ada Pengajuan Izin</h4>

                                            <p>
                                                Riwayat pengajuan izin belum tersedia.
                                                Anda dapat membuat pengajuan baru
                                                melalui tombol di bawah ini.
                                            </p>

                                            <a href="{{ route('ortu.leave-requests.create') }}"
                                               class="olp-empty-button">
                                                <i class="bi bi-plus-lg"></i>
                                                Ajukan Izin
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($requests->hasPages())
                    <div class="olp-pagination">
                        <div class="olp-pagination-info">
                            Menampilkan
                            <strong>
                                {{ $requests->firstItem() ?? 0 }}–{{ $requests->lastItem() ?? 0 }}
                            </strong>
                            dari <strong>{{ $requests->total() }}</strong> pengajuan
                        </div>

                        <div class="olp-pagination-links">
                            {{ $requests->links() }}
                        </div>
                    </div>
                @elseif($requests->total() > 0)
                    <div class="olp-pagination">
                        <div class="olp-pagination-info">
                            Menampilkan
                            <strong>
                                {{ $requests->firstItem() ?? 0 }}–{{ $requests->lastItem() ?? 0 }}
                            </strong>
                            dari <strong>{{ $requests->total() }}</strong> pengajuan
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- INFORMASI --}}
        <div class="olp-information">
            <span class="olp-information-icon">
                <i class="bi bi-info-circle-fill"></i>
            </span>

            <div>
                <strong>Informasi pengajuan</strong>
                <p>
                    Periksa status pengajuan secara berkala. Jika pengajuan
                    telah diproses, baca bagian catatan guru untuk melihat
                    keterangan tambahan jika tersedia.
                </p>
            </div>
        </div>

        
        {{-- TOMBOL KEMBALI DI BAGIAN BAWAH --}}
        <div class="olp-bottom-actions">
            <a href="{{ route('ortu.dashboard') }}" class="olp-back-button">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Dasbor</span>
            </a>
        </div>
        

    </div>
</div>
@endsection
