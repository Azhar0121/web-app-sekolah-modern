@extends('layouts.admin')

@section('title', 'Kotak Masuk Pesan Publik')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/contact-messages/index.css') }}">

<div class="inbox-page">

    <div class="inbox-header">
        <div class="inbox-header-content">
            <span class="inbox-badge">
                <i class="bi bi-chat-left-text-fill"></i>
                LAYANAN KOMUNIKASI PUBLIK
            </span>
            <h4>Kotak Masuk Pesan</h4>
            <p>Daftar pesan dan formulir pertanyaan yang dikirimkan oleh pengunjung melalui halaman Hubungi Kami.</p>
        </div>

        <div class="inbox-header-actions">
            @if(auth()->user()->hasRole('tu'))
                <a href="{{ route('tu.dashboard') }}" class="inbox-btn-back">
                    &larr; Dashboard TU
                </a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="inbox-btn-back">
                    &larr; Dashboard Admin
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="inbox-tabs">
        <a href="{{ route('admin.contact-messages.index', ['status' => 'all', 'search' => $search]) }}"
           class="inbox-tab {{ $status === 'all' ? 'active' : '' }}">
            <span>Semua Pesan</span>
            <span class="inbox-count">{{ $counts['all'] }}</span>
        </a>

        <a href="{{ route('admin.contact-messages.index', ['status' => 'unread', 'search' => $search]) }}"
           class="inbox-tab {{ $status === 'unread' ? 'active' : '' }}">
            <span>Belum Dibaca</span>
            <span class="inbox-count {{ $counts['unread'] > 0 ? 'unread' : '' }}">{{ $counts['unread'] }}</span>
        </a>

        <a href="{{ route('admin.contact-messages.index', ['status' => 'read', 'search' => $search]) }}"
           class="inbox-tab {{ $status === 'read' ? 'active' : '' }}">
            <span>Sudah Dibaca</span>
            <span class="inbox-count">{{ $counts['read'] }}</span>
        </a>
    </div>

    <div class="inbox-search-card">
        <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="inbox-search-form">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="inbox-search-input">
                <i class="bi bi-search"></i>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari berdasarkan nama pengirim, email, subjek, atau isi pesan...">
            </div>
            <button type="submit" class="btn btn-primary px-4 py-2" style="font-size: 13px; font-weight: 700; height: 42px;">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.contact-messages.index', ['status' => $status]) }}" class="btn btn-outline-secondary px-3 py-2" style="font-size: 13px; height: 42px;">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="inbox-table-card">
        @if($messages->isNotEmpty())
            <div class="table-responsive">
                <table class="inbox-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">Status</th>
                            <th style="width: 200px;">Pengirim</th>
                            <th style="width: 220px;">Subjek</th>
                            <th>Isi Pesan Singkat</th>
                            <th style="width: 140px;">Waktu Kirim</th>
                            <th style="width: 130px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $msg)
                            <tr class="{{ ! $msg->is_read ? 'unread' : '' }}">
                                <td style="text-align: center;">
                                    <span class="inbox-status-dot {{ ! $msg->is_read ? 'unread' : 'read' }}"
                                          title="{{ ! $msg->is_read ? 'Belum dibaca' : 'Sudah dibaca' }}">
                                    </span>
                                </td>
                                <td>
                                    <div class="text-dark fw-bold">{{ $msg->name }}</div>
                                    <small class="text-muted d-block">{{ $msg->email }}</small>
                                    @if($msg->phone)
                                        <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $msg->phone }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-dark fw-semibold">{{ $msg->subject }}</span>
                                </td>
                                <td>
                                    <span class="text-muted" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                        {{ $msg->message }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted d-block">{{ $msg->created_at->format('d M Y') }}</small>
                                    <small class="text-muted">{{ $msg->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td style="text-align: center;">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <a href="{{ route('admin.contact-messages.show', $msg->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Buka & Baca Pesan">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <form method="POST" action="{{ route('admin.contact-messages.toggle-read', $msg->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm {{ ! $msg->is_read ? 'btn-outline-success' : 'btn-outline-secondary' }}"
                                                    title="{{ ! $msg->is_read ? 'Tandai sudah dibaca' : 'Tandai belum dibaca' }}">
                                                <i class="bi {{ ! $msg->is_read ? 'bi-check2' : 'bi-envelope' }}"></i>
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('admin.contact-messages.destroy', $msg->id) }}"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Hapus Pesan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($messages->hasPages())
                <div class="p-3 border-top">
                    {{ $messages->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 42px;"></i>
                <h6 class="mt-3 fw-bold text-dark">Tidak ada pesan ditemukan</h6>
                <p class="text-muted mb-0" style="font-size: 13px;">
                    @if($search)
                        Tidak ada pesan yang sesuai dengan kata kunci pencarian "{{ $search }}".
                    @else
                        Belum ada pesan yang masuk pada filter ini.
                    @endif
                </p>
            </div>
        @endif
    </div>

</div>

@endsection
