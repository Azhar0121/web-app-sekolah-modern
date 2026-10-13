@extends('layouts.admin')

@section('title', 'Detail Pesan: ' . $contactMessage->subject)

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/contact-messages/index.css') }}">

<div class="inbox-page">

    <div class="inbox-header">
        <div class="inbox-header-content">
            <span class="inbox-badge">
                <i class="bi bi-envelope-open-fill"></i>
                DETAIL PESAN MASUK
            </span>
            <h4>{{ $contactMessage->subject }}</h4>
            <p>Diterima pada {{ $contactMessage->created_at->format('d F Y, H:i') }} WIB</p>
        </div>

        <div class="inbox-header-actions">
            <a href="{{ route('admin.contact-messages.index') }}" class="inbox-btn-back">
                &larr; Kembali ke Kotak Masuk
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="inbox-detail-card">

        <div class="inbox-detail-header">
            <div class="inbox-sender-info">
                <div class="inbox-sender-avatar">
                    {{ strtoupper(substr($contactMessage->name, 0, 1)) }}
                </div>
                <div>
                    <h5 class="mb-1 fw-bold text-dark">{{ $contactMessage->name }}</h5>
                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted" style="font-size: 13px;">
                        <span><i class="bi bi-envelope me-1"></i> <a href="mailto:{{ $contactMessage->email }}" class="text-decoration-none">{{ $contactMessage->email }}</a></span>
                        @if($contactMessage->phone)
                            <span><i class="bi bi-telephone me-1"></i> <a href="tel:{{ $contactMessage->phone }}" class="text-decoration-none">{{ $contactMessage->phone }}</a></span>
                        @endif
                        <span><i class="bi bi-clock me-1"></i> {{ $contactMessage->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <form method="POST" action="{{ route('admin.contact-messages.toggle-read', $contactMessage->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1" style="font-size: 12px; font-weight: 700;">
                        <i class="bi {{ $contactMessage->is_read ? 'bi-envelope' : 'bi-check2' }}"></i>
                        {{ $contactMessage->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('admin.contact-messages.destroy', $contactMessage->id) }}"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1" style="font-size: 12px; font-weight: 700;">
                        <i class="bi bi-trash"></i>
                        Hapus Pesan
                    </button>
                </form>
            </div>
        </div>

        <div class="mb-4">
            <label class="text-muted text-uppercase fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Subjek Pesan</label>
            <div class="fs-6 fw-bold text-dark">{{ $contactMessage->subject }}</div>
        </div>

        <div class="mb-4">
            <label class="text-muted text-uppercase fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Isi Pesan Lengkap</label>
            <div class="inbox-message-body">
{{ $contactMessage->message }}
            </div>
        </div>

        {{-- BALAS CEPAT VIA EMAIL --}}
        <div class="pt-3 border-top d-flex align-items-center gap-2">
            <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ rawurlencode($contactMessage->subject) }}"
               class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2"
               style="font-size: 13px; font-weight: 700;">
                <i class="bi bi-reply-fill"></i>
                Balas via Email ({{ $contactMessage->email }})
            </a>

            @if($contactMessage->phone)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $contactMessage->phone);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                @endphp
                <a href="https://wa.me/{{ $cleanPhone }}?text={{ rawurlencode('Halo ' . $contactMessage->name . ', menanggapi pesan Anda terkait "' . $contactMessage->subject . '"...') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn btn-whatsapp-reply d-inline-flex align-items-center gap-2 px-4 py-2"
                   style="font-size: 13px; font-weight: 700; color: #ffffff !important;">
                    <i class="bi bi-whatsapp" style="color: #ffffff !important;"></i>
                    <span style="color: #ffffff !important;">Balas via WhatsApp</span>
                </a>
            @endif
        </div>

    </div>

</div>

@endsection
