@extends('layouts.app')

@section('title', 'Kelola Presensi')

@section('content')

{{-- Bootstrap Icons --}}
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- CSS halaman --}}
<link rel="stylesheet" href="{{ asset('css/guru/attendance/show.css') }}">

<div class="attendance-page">


{{-- =========================
    HEADER PRESENSI
========================== --}}
<div class="attendance-header">
    <div class="attendance-header-content">
        <div class="attendance-title">
            <span class="attendance-label">PRESENSI KELAS</span>

            <h2>
                {{ $schedule->teachingAssignment->subject->name }}
            </h2>

            <div class="attendance-meta">
                <span>
                    <i class="bi bi-people"></i>
                    {{ $schedule->teachingAssignment->classroom->name }}
                </span>

                <span class="meta-divider">•</span>

                <span>
                    <i class="bi bi-calendar3"></i>
                    {{ $attendanceSession->date->translatedFormat('l, d F Y') }}
                </span>

                <span class="meta-divider">•</span>

                <span>
                    <i class="bi bi-clock"></i>
                    {{ $schedule->start_time->format('H:i') }}
                    -
                    {{ $schedule->end_time->format('H:i') }}
                </span>
            </div>
        </div>

        <div class="attendance-status">
            @if ($attendanceSession->isOpen())
                <span class="status-badge status-open">
                    <span class="status-dot"></span>
                    Sesi Berlangsung
                </span>
            @else
                <span class="status-badge status-closed">
                    <span class="status-dot"></span>
                    Sesi Selesai
                </span>
            @endif
        </div>
    </div>
</div>


{{-- =========================
    PRESENSI AKTIF
========================== --}}
@if ($attendanceSession->isOpen())

    <div class="attendance-grid">

        {{-- =========================
            SCANNER QR
        ========================== --}}
        <div class="scanner-card">

            <div class="card-heading">
                <div class="heading-icon">
                    <i class="bi bi-qr-code-scan"></i>
                </div>

                <div>
                    <h5>Scan Kartu Pelajar</h5>
                    <p>Scan QR siswa untuk mencatat kehadiran</p>
                </div>
            </div>

            <div class="scanner-wrapper">

                <div class="scanner-frame">
                    <div id="reader"></div>

                    <div class="scanner-corners"></div>
                </div>

                <div id="scan-feedback"></div>

                <div class="scanner-info">
                    <i class="bi bi-info-circle"></i>

                    <span>
                        Arahkan kamera ke QR pada kartu pelajar digital siswa.
                        Data kehadiran akan otomatis diperbarui setelah QR berhasil dipindai.
                    </span>
                </div>

            </div>
        </div>


        {{-- =========================
            DAFTAR SISWA
        ========================== --}}
        <div class="roster-card">
            @include('guru.attendance.partials.roster')
        </div>

    </div>


    {{-- =========================
        ACTION
    ========================== --}}
    <div class="attendance-actions">

        <form method="POST"
              action="{{ route('guru.attendance.close', $attendanceSession) }}"
              onsubmit="return confirm('Tutup sesi presensi? Siswa yang belum tercatat akan otomatis ditandai Alpha.');">

            @csrf

            <button type="submit" class="btn-close-session">
                <i class="bi bi-stop-circle"></i>
                Tutup Sesi Presensi
            </button>

        </form>

    </div>

@else

    {{-- =========================
        SESI SELESAI
    ========================== --}}
    <div class="roster-card roster-closed">
        @include('guru.attendance.partials.roster')
    </div>

@endif


{{-- =========================
    BACK BUTTON
========================== --}}
<div class="attendance-back">
    <a href="{{ route('guru.attendance.index') }}" class="btn-attendance-back">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Presensi Kelas</span>
    </a>
</div>


</div>

{{-- =========================
QR SCANNER SCRIPT
========================== --}}
@if ($attendanceSession->isOpen())


@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<script>
    const scanUrl = @json(route('guru.attendance.scan', $attendanceSession));
    const csrfToken = @json(csrf_token());

    let lastToken = null;
    let lastScanTime = 0;

    function showFeedback(message, ok) {
        const box = document.getElementById('scan-feedback');

        box.innerHTML = `
            <div class="scan-alert ${ok ? 'scan-success' : 'scan-error'}">
                <i class="bi ${ok ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i>
                <span>${message}</span>
            </div>
        `;
    }

    function updateRosterRow(studentName, statusLabel, badgeClass) {

        const row = document.querySelector(
            `tr[data-student-name="${CSS.escape(studentName)}"]`
        );

        if (!row) return;

        const cell = row.querySelector('.status-cell');

        if (cell) {
            cell.innerHTML = `
                <span class="badge ${badgeClass}">
                    ${statusLabel}
                </span>
            `;
        }
    }

    function onScanSuccess(decodedText) {

        const now = Date.now();

        if (
            decodedText === lastToken &&
            (now - lastScanTime) < 3000
        ) {
            return;
        }

        lastToken = decodedText;
        lastScanTime = now;

        fetch(scanUrl, {
            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },

            body: JSON.stringify({
                token: decodedText
            }),
        })

        .then((res) =>
            res.json().then((data) => ({
                status: res.status,
                data
            }))
        )

        .then(({ data }) => {

            showFeedback(
                data.message,
                data.success
            );

            if (
                data.success &&
                data.student_name
            ) {
                updateRosterRow(
                    data.student_name,
                    'Hadir',
                    'text-bg-success'
                );
            }

        })

        .catch(() => {

            showFeedback(
                'Terjadi kesalahan, coba scan ulang.',
                false
            );

        });
    }


    const html5QrCode = new Html5Qrcode('reader');

    html5QrCode.start(
        {
            facingMode: 'environment'
        },

        {
            fps: 10,
            qrbox: 240
        },

        onScanSuccess

    ).catch(() => {

        document.getElementById('reader').innerHTML = `
            <div class="camera-error">
                <i class="bi bi-camera-video-off"></i>
                <strong>Kamera tidak dapat diakses</strong>
                <span>
                    Pastikan izin kamera browser sudah diaktifkan.
                </span>
            </div>
        `;

    });
</script>

@endpush


@endif

@endsection
