@extends('layouts.admin')

@section('title', 'Ruang Komunikasi Orang Tua')

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/communication/index.css') }}">

<div class="guru-communication">

    {{-- =========================================================
        HERO / PAGE HEADER
    ========================================================== --}}
    <section class="communication-page-header">

        <div class="communication-header-decoration communication-decoration-one"></div>
        <div class="communication-header-decoration communication-decoration-two"></div>

        <div class="communication-header-content">

            <div class="communication-label">
                RUANG KOMUNIKASI
            </div>

            <h1>
                Komunikasi Guru & Orang Tua
            </h1>

            <p>
                Bangun komunikasi yang terarah dengan orang tua untuk membahas
                perkembangan akademik, kehadiran, kedisiplinan, dan kebutuhan siswa.
            </p>

        </div>

        <button
            type="button"
            class="communication-new-btn"
            data-bs-toggle="modal"
            data-bs-target="#newCommunicationModal"
        >
            <i class="bi bi-plus-circle"></i>
            <span>Mulai Konsultasi Baru</span>
        </button>

    </section>


    {{-- =========================================================
        FLASH MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="communication-alert communication-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="communication-alert communication-alert-danger">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}
    <div class="communication-layout">

        {{-- =====================================================
            DAFTAR PERCAKAPAN
        ====================================================== --}}
        <div class="communication-card">

            <div class="communication-list-header">

                <div class="communication-list-title">

                    <div class="communication-list-heading">

                        <div class="communication-section-icon">
                            <i class="bi bi-chat-square-text-fill"></i>
                        </div>

                        <div>
                            <h2>Daftar Percakapan</h2>

                            <span class="communication-list-subtitle">
                                Riwayat komunikasi
                            </span>
                        </div>

                    </div>

                    <span class="communication-count">
                        {{ $threads->count() }}
                    </span>

                </div>


                {{-- FILTER --}}
                <div class="communication-filter">

                    <a
                        href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
                        class="{{ request('status', 'all') === 'all' ? 'active' : '' }}"
                    >
                        Semua
                    </a>

                    <a
                        href="{{ request()->fullUrlWithQuery(['status' => 'open']) }}"
                        class="{{ request('status') === 'open' ? 'active' : '' }}"
                    >
                        Aktif
                    </a>

                    <a
                        href="{{ request()->fullUrlWithQuery(['status' => 'resolved']) }}"
                        class="{{ request('status') === 'resolved' ? 'active' : '' }}"
                    >
                        Selesai
                    </a>

                </div>

            </div>


            {{-- THREAD LIST --}}
            <div class="communication-thread-list">

                @forelse($threads as $thread)

                    @php
                        $isActive = isset($activeThread)
                            && $activeThread->id === $thread->id;

                        $categoryClass = match($thread->category ?? '') {
                            'academic' => 'academic',
                            'discipline' => 'discipline',
                            'attendance' => 'attendance',
                            default => 'other',
                        };

                        $categoryLabel = match($thread->category ?? '') {
                            'academic' => 'Akademik',
                            'discipline' => 'Kedisiplinan',
                            'attendance' => 'Kehadiran',
                            default => 'Lainnya',
                        };

                        $statusClass = ($thread->status ?? '') === 'resolved'
                            ? 'resolved'
                            : 'open';

                        $statusLabel = ($thread->status ?? '') === 'resolved'
                            ? 'Selesai'
                            : 'Aktif';
                    @endphp

                    <a
                        href="{{ route('guru.communication.show', $thread->id) }}"
                        class="communication-thread {{ $isActive ? 'active' : '' }}"
                    >

                        <div class="communication-thread-top">

                            <span class="communication-category {{ $categoryClass }}">
                                {{ $categoryLabel }}
                            </span>

                            <span class="communication-time">
                                {{ $thread->updated_at?->diffForHumans() }}
                            </span>

                        </div>


                        <h3>
                            {{ $thread->subject }}
                        </h3>


                        <div class="communication-person">

                            <i class="bi bi-person-fill"></i>

                            <span>
                                Siswa:
                                <strong>
                                    {{ $thread->student->name ?? '-' }}
                                </strong>
                            </span>

                        </div>


                        <div class="communication-person">

                            <i class="bi bi-person-heart"></i>

                            <span>
                                Ortu:
                                <strong>
                                    {{ $thread->parent->name ?? '-' }}
                                </strong>
                            </span>

                        </div>


                        <div class="communication-thread-bottom">

                            <span class="communication-status {{ $statusClass }}">

                                @if($statusClass === 'resolved')
                                    <i class="bi bi-check-circle-fill"></i>
                                @else
                                    <i class="bi bi-clock-fill"></i>
                                @endif

                                {{ $statusLabel }}

                            </span>


                            @if(isset($thread->unread_count) && $thread->unread_count > 0)

                                <span class="communication-unread">
                                    {{ $thread->unread_count }} Baru
                                </span>

                            @endif

                        </div>

                    </a>

                @empty

                    <div class="communication-empty">

                        <div class="communication-empty-icon">
                            <i class="bi bi-chat-square-text"></i>
                        </div>

                        <h3>
                            Belum Ada Percakapan
                        </h3>

                        <p>
                            Belum terdapat percakapan dengan orang tua siswa.
                            Mulai konsultasi baru untuk membuka komunikasi.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- =====================================================
            DETAIL PERCAKAPAN
        ====================================================== --}}
        <div class="communication-card">

            @if(isset($activeThread) && $activeThread)

                @php
                    $activeCategoryClass = match($activeThread->category ?? '') {
                        'academic' => 'academic',
                        'discipline' => 'discipline',
                        'attendance' => 'attendance',
                        default => 'other',
                    };

                    $activeCategoryLabel = match($activeThread->category ?? '') {
                        'academic' => 'Akademik',
                        'discipline' => 'Kedisiplinan',
                        'attendance' => 'Kehadiran',
                        default => 'Lainnya',
                    };

                    $activeStatusClass = ($activeThread->status ?? '') === 'resolved'
                        ? 'resolved'
                        : 'open';
                @endphp


                {{-- DETAIL HEADER --}}
                <div class="communication-detail-header">

                    <div class="communication-detail-info">

                        <div class="communication-detail-meta">

                            <span class="communication-category {{ $activeCategoryClass }}">
                                {{ $activeCategoryLabel }}
                            </span>

                            <span class="communication-detail-status {{ $activeStatusClass }}">

                                @if($activeStatusClass === 'resolved')
                                    <i class="bi bi-check-circle-fill"></i>
                                    Selesai
                                @else
                                    <i class="bi bi-clock-fill"></i>
                                    Aktif
                                @endif

                            </span>

                        </div>


                        <h2>
                            {{ $activeThread->subject }}
                        </h2>


                        <div class="communication-detail-students">

                            <span>
                                <i class="bi bi-person-fill"></i>
                                {{ $activeThread->student->name ?? '-' }}
                            </span>

                            <span class="communication-detail-divider"></span>

                            <span>
                                <i class="bi bi-person-heart"></i>
                                {{ $activeThread->parent->name ?? '-' }}
                            </span>

                        </div>

                    </div>


                    {{-- STATUS BUTTON --}}
                    @if(($activeThread->status ?? '') === 'open')

                        <form
                            action="{{ route('guru.communication.resolve', $activeThread->id) }}"
                            method="POST"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="communication-status-btn finish"
                            >
                                <i class="bi bi-check-circle"></i>
                                Tandai Selesai
                            </button>

                        </form>

                    @else

                        <form
                            action="{{ route('guru.communication.reopen', $activeThread->id) }}"
                            method="POST"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="communication-status-btn reopen"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Buka Kembali
                            </button>

                        </form>

                    @endif

                </div>


                {{-- MESSAGE AREA --}}
                <div class="communication-messages">

                    @forelse($activeThread->messages as $message)

                        @php
                            $isMine = isset($message->sender_id)
                                && auth()->id() === $message->sender_id;
                        @endphp

                        <div class="communication-message-row {{ $isMine ? 'mine' : 'other' }}">

                            <div class="communication-message {{ $isMine ? 'mine' : '' }}">

                                <div class="communication-message-head">

                                    <span class="communication-message-sender">
                                        {{ $isMine
                                            ? 'Anda'
                                            : ($message->sender->name ?? 'Orang Tua')
                                        }}
                                    </span>

                                    <span class="communication-message-time">
                                        {{ $message->created_at?->format('d M Y, H:i') }}
                                    </span>

                                </div>


                                <p class="communication-message-text">
                                    {{ $message->message }}
                                </p>


                                @if(!empty($message->attachment))

                                    <a
                                        href="{{ asset('storage/' . $message->attachment) }}"
                                        target="_blank"
                                        class="communication-attachment"
                                    >
                                        <i class="bi bi-paperclip"></i>
                                        Lihat Lampiran
                                    </a>

                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="communication-empty">

                            <div class="communication-empty-icon">
                                <i class="bi bi-chat-dots"></i>
                            </div>

                            <h3>
                                Belum Ada Pesan
                            </h3>

                            <p>
                                Belum ada pesan dalam percakapan ini.
                            </p>

                        </div>

                    @endforelse

                </div>


                {{-- REPLY --}}
                @if(($activeThread->status ?? '') === 'open')

                    <div class="communication-reply">

                        <form
                            action="{{ route('guru.communication.reply', $activeThread->id) }}"
                            method="POST"
                            enctype="multipart/form-data"
                        >

                            @csrf

                            <textarea
                                name="message"
                                placeholder="Tulis balasan untuk orang tua..."
                                required
                            ></textarea>


                            <div class="communication-reply-bottom">

                                <div class="communication-file">

                                    <label for="reply_attachment">
                                        <i class="bi bi-paperclip"></i>
                                    </label>

                                    <input
                                        type="file"
                                        name="attachment"
                                        id="reply_attachment"
                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                    >

                                </div>


                                <button
                                    type="submit"
                                    class="communication-send-btn"
                                >
                                    <i class="bi bi-send-fill"></i>
                                    Kirim Balasan
                                </button>

                            </div>

                        </form>

                    </div>

                @else

                    <div class="communication-reply">

                        <div class="communication-alert communication-alert-success mb-0">

                            <i class="bi bi-check-circle-fill"></i>

                            <span>
                                Percakapan ini telah selesai. Buka kembali percakapan
                                jika ingin mengirim pesan baru.
                            </span>

                        </div>

                    </div>

                @endif

            @else

                {{-- EMPTY DETAIL --}}
                <div class="communication-no-thread">

                    <div class="communication-no-thread-icon">
                        <i class="bi bi-chat-square-dots"></i>
                    </div>

                    <h2>
                        Pilih Percakapan
                    </h2>

                    <p>
                        Pilih salah satu percakapan dari daftar di sebelah kiri
                        untuk melihat detail komunikasi dengan orang tua siswa.
                    </p>

                    <button
                        type="button"
                        class="communication-new-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#newCommunicationModal"
                    >
                        <i class="bi bi-plus-circle"></i>
                        <span>Mulai Konsultasi Baru</span>
                    </button>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =============================================================
    MODAL KONSULTASI BARU
============================================================== --}}
<div
    class="modal fade"
    id="newCommunicationModal"
    tabindex="-1"
    aria-labelledby="newCommunicationModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content communication-modal">

            <div class="modal-header communication-modal-header">

                <div class="communication-modal-icon">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>

                <h2 id="newCommunicationModalLabel">
                    Mulai Konsultasi Baru
                </h2>

                <button
                    type="button"
                    class="btn-close btn-close-white ms-auto"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <form
                action="{{ route('guru.communication.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="communication-modal-body">

                    {{-- SISWA --}}
                    <div class="communication-form-group">

                        <label for="student_id">
                            Siswa
                        </label>

                        <select
                            name="student_id"
                            id="student_id"
                            required
                        >

                            <option value="">
                                Pilih siswa
                            </option>

                            @foreach($students as $student)

                                <option value="{{ $student->id }}">
                                    {{ $student->name }}

                                    @if(isset($student->classroom))
                                        — {{ $student->classroom->name }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- KATEGORI --}}
                    <div class="communication-form-group">

                        <label for="category">
                            Kategori Konsultasi
                        </label>

                        <select
                            name="category"
                            id="category"
                            required
                        >

                            <option value="">
                                Pilih kategori
                            </option>

                            <option value="academic">
                                Akademik
                            </option>

                            <option value="attendance">
                                Kehadiran
                            </option>

                            <option value="discipline">
                                Kedisiplinan
                            </option>

                            <option value="other">
                                Lainnya
                            </option>

                        </select>

                    </div>


                    {{-- SUBJECT --}}
                    <div class="communication-form-group">

                        <label for="subject">
                            Topik Konsultasi
                        </label>

                        <input
                            type="text"
                            name="subject"
                            id="subject"
                            placeholder="Contoh: Perkembangan nilai matematika"
                            required
                        >

                    </div>


                    {{-- MESSAGE --}}
                    <div class="communication-form-group">

                        <label for="message">
                            Pesan Awal
                        </label>

                        <textarea
                            name="message"
                            id="message"
                            rows="5"
                            placeholder="Tuliskan pesan atau hal yang ingin dikonsultasikan..."
                            required
                        ></textarea>

                        <small class="communication-help">
                            Jelaskan informasi konsultasi secara singkat dan jelas
                            agar orang tua dapat memahami topik pembicaraan.
                        </small>

                    </div>


                    {{-- ATTACHMENT --}}
                    <div class="communication-form-group">

                        <label for="new_attachment">
                            Lampiran
                        </label>

                        <input
                            type="file"
                            name="attachment"
                            id="new_attachment"
                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                        >

                        <small class="communication-help">
                            Format yang diperbolehkan: JPG, PNG, PDF, DOC, DOCX.
                        </small>

                    </div>

                </div>


                <div class="communication-modal-footer">

                    <button
                        type="button"
                        class="communication-modal-cancel"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="communication-modal-submit"
                    >
                        <i class="bi bi-send-fill"></i>
                        Mulai Konsultasi
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection