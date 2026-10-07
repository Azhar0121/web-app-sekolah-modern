@extends('layouts.admin')

@section('title', 'Pengajuan Izin Siswa')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/leave-requests/index.css') }}">

<div class="guru-leave-page">

    {{-- HERO --}}
    <div class="leave-page-header">

        <div class="leave-header-content">

            <div class="leave-header-icon">
                <i class="bi bi-clipboard-check-fill"></i>
            </div>

            <div>
                <div class="leave-header-label">
                    KEGIATAN GURU
                </div>

                <h1>Pengajuan Izin Siswa</h1>

                <p>
                    Kelola dan proses pengajuan izin siswa yang masuk.
                </p>
            </div>

        </div>

        <div class="leave-header-badge">
            <i class="bi bi-file-earmark-text-fill"></i>
            Izin Siswa
        </div>

        <div class="leave-hero-decoration one"></div>
        <div class="leave-hero-decoration two"></div>

    </div>


    {{-- MAIN CARD --}}
    <div class="leave-main-card">

        {{-- TAB HEADER --}}
        <div class="leave-filter-header">

            <div class="leave-filter-title">

                <div class="leave-section-icon">
                    <i class="bi bi-funnel-fill"></i>
                </div>

                <div>
                    <div class="leave-filter-eyebrow">
                        FILTER PENGAJUAN
                    </div>

                    <h2>Status Pengajuan</h2>

                    <p>
                        Pilih status untuk melihat daftar pengajuan.
                    </p>
                </div>

            </div>


            <div class="leave-tabs">

                <a
                    class="leave-tab {{ request('status', 'pending') === 'pending' ? 'active pending' : '' }}"
                    href="{{ route('guru.leave-requests.index', ['status' => 'pending']) }}"
                >
                    <span class="leave-tab-icon">
                        <i class="bi bi-clock"></i>
                    </span>

                    <span>Menunggu</span>
                </a>

                <a
                    class="leave-tab {{ request('status') === 'approved' ? 'active approved' : '' }}"
                    href="{{ route('guru.leave-requests.index', ['status' => 'approved']) }}"
                >
                    <span class="leave-tab-icon">
                        <i class="bi bi-check-circle"></i>
                    </span>

                    <span>Disetujui</span>
                </a>

                <a
                    class="leave-tab {{ request('status') === 'rejected' ? 'active rejected' : '' }}"
                    href="{{ route('guru.leave-requests.index', ['status' => 'rejected']) }}"
                >
                    <span class="leave-tab-icon">
                        <i class="bi bi-x-circle"></i>
                    </span>

                    <span>Ditolak</span>
                </a>

                <a
                    class="leave-tab {{ request('status') === 'all' ? 'active all' : '' }}"
                    href="{{ route('guru.leave-requests.index', ['status' => 'all']) }}"
                >
                    <span class="leave-tab-icon">
                        <i class="bi bi-grid"></i>
                    </span>

                    <span>Semua</span>
                </a>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="leave-table-wrapper">

            <table class="leave-table">

                <thead>
                    <tr>
                        <th class="leave-student-column">
                            Siswa
                        </th>

                        <th class="leave-type-column">
                            Jenis
                        </th>

                        <th class="leave-date-column">
                            Tanggal
                        </th>

                        <th class="leave-reason-column">
                            Alasan
                        </th>

                        <th class="leave-status-column">
                            Status
                        </th>

                        <th class="leave-action-column">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($requests as $req)

                        <tr>

                            {{-- SISWA --}}
                            <td>

                                <div class="leave-student">

                                    <div class="leave-student-avatar">
                                        {{ strtoupper(substr($req->student->name ?? 'S', 0, 1)) }}
                                    </div>

                                    <div class="leave-student-info">

                                        <strong>
                                            {{ $req->student->name ?? 'Siswa' }}
                                        </strong>

                                        <span>
                                            NISN:
                                            {{ $req->student->studentProfile->nisn ?? '-' }}
                                        </span>

                                        <span>
                                            <i class="bi bi-person"></i>
                                            Ortu:
                                            {{ $req->parent->name ?? '-' }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- JENIS --}}
                            <td>

                                @if($req->type === 'sakit')

                                    <span class="leave-type sakit">
                                        <i class="bi bi-heart-pulse-fill"></i>
                                        Sakit
                                    </span>

                                @else

                                    <span class="leave-type izin">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                        {{ ucfirst($req->type) }}
                                    </span>

                                @endif

                            </td>


                            {{-- TANGGAL --}}
                            <td>

                                <div class="leave-date">

                                    <div class="leave-date-icon">
                                        <i class="bi bi-calendar3"></i>
                                    </div>

                                    <div>
                                        <strong>
                                            {{ \Carbon\Carbon::parse($req->start_date)->format('d/m/Y') }}
                                        </strong>

                                        @if($req->start_date != $req->end_date)
                                            <span>
                                                s/d
                                                {{ \Carbon\Carbon::parse($req->end_date)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span>
                                                1 hari
                                            </span>
                                        @endif
                                    </div>

                                </div>

                            </td>


                            {{-- ALASAN --}}
                            <td>

                                <div class="leave-reason">

                                    <div class="leave-reason-text">
                                        {{ $req->reason }}
                                    </div>

                                    @if($req->attachment_path)

                                        <a
                                            href="{{ \Illuminate\Support\Facades\Storage::url($req->attachment_path) }}"
                                            target="_blank"
                                            class="leave-attachment"
                                        >
                                            <i class="bi bi-paperclip"></i>
                                            Lihat Lampiran
                                        </a>

                                    @endif

                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if($req->status === 'pending')

                                    <span class="leave-status pending">
                                        <span class="leave-status-dot"></span>
                                        Menunggu
                                    </span>

                                @elseif($req->status === 'approved')

                                    <span class="leave-status approved">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Disetujui
                                    </span>

                                @elseif($req->status === 'rejected')

                                    <span class="leave-status rejected">
                                        <i class="bi bi-x-circle-fill"></i>
                                        Ditolak
                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td>

                                @if($req->status === 'pending')

                                    <form
                                        action="{{ route('guru.leave-requests.process', $req->id) }}"
                                        method="POST"
                                        class="leave-process-form"
                                    >

                                        @csrf

                                        <select
                                            name="decision"
                                            class="leave-decision"
                                            required
                                        >
                                            <option value="">Pilih keputusan</option>
                                            <option value="approved">Setujui</option>
                                            <option value="rejected">Tolak</option>
                                        </select>

                                        <textarea
                                            name="process_notes"
                                            class="leave-process-notes"
                                            placeholder="Catatan (opsional)"
                                            rows="2"
                                        ></textarea>

                                        <button
                                            type="submit"
                                            class="leave-process-button"
                                        >
                                            <i class="bi bi-check2-square"></i>
                                            Proses Pengajuan
                                        </button>

                                    </form>

                                @else

                                    <div class="leave-processed">

                                        <div class="leave-processed-item">

                                            <span class="leave-processed-label">
                                                <i class="bi bi-person-check"></i>
                                                Diproses oleh
                                            </span>

                                            <strong>
                                                {{ $req->processedBy->name ?? '-' }}
                                            </strong>

                                        </div>

                                        <div class="leave-processed-item">

                                            <span class="leave-processed-label">
                                                <i class="bi bi-chat-left-text"></i>
                                                Catatan
                                            </span>

                                            <span class="leave-processed-note">
                                                {{ $req->process_notes ?: '-' }}
                                            </span>

                                        </div>

                                    </div>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="leave-empty">

                                    <div class="leave-empty-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>

                                    <h3>
                                        Tidak Ada Data Pengajuan
                                    </h3>

                                    <p>
                                        Belum ada pengajuan izin siswa pada status yang dipilih.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($requests->hasPages())

            <div class="leave-pagination">
                {{ $requests->links() }}
            </div>

        @endif

    </div>

</div>
@endsection