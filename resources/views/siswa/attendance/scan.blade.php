@extends('layouts.app')

@section('title', 'Scan Presensi')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/siswa/attendance/scan.css') }}">

<div class="student-attendance-scan-page">

    <div class="student-attendance-scan-wrapper">

        {{-- =====================================================
            HEADER
        ====================================================== --}}
        <div class="student-attendance-scan-header">

            <div class="student-attendance-scan-header-content">

                <div class="student-attendance-scan-label">
                    <span class="student-attendance-scan-dot"></span>
                    PRESENSI SISWA
                </div>

                <h1>Scan Presensi</h1>

                <p>
                    Arahkan kamera ke QR Code yang ditampilkan guru di kelas.
                </p>

            </div>

            <a href="{{ route('siswa.attendance.index') }}"
               class="student-attendance-scan-header-back">

                <i class="bi bi-arrow-left"></i>

                <span>Kembali</span>

            </a>

        </div>


        {{-- =====================================================
            SCANNER CARD
        ====================================================== --}}
        <div class="student-attendance-scanner-card">

            <div class="student-attendance-scanner-top">

                <div class="student-attendance-scanner-title">

                    <div class="student-attendance-scanner-title-icon">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>

                    <div>
                        <span>PEMINDAI QR CODE</span>

                        <h2>Scan Kehadiran</h2>
                    </div>

                </div>

                <div class="student-attendance-scanner-status">

                    <span></span>

                    Kamera Aktif

                </div>

            </div>


            {{-- =================================================
                CAMERA
            ================================================== --}}
            <div class="student-attendance-camera">

                <video id="video"
                       class="student-attendance-video"
                       autoplay
                       playsinline
                       muted></video>

                <canvas id="canvas" class="d-none"></canvas>


                {{-- SCAN FRAME --}}
                <div class="student-attendance-scan-overlay"
                     id="scan-corners">

                    <div class="scan-corner top-left"></div>
                    <div class="scan-corner top-right"></div>
                    <div class="scan-corner bottom-left"></div>
                    <div class="scan-corner bottom-right"></div>

                    <div class="scan-line"></div>

                </div>


                {{-- CAMERA ERROR --}}
                <div id="camera-error"
                     class="student-attendance-camera-error d-none">

                    <div class="student-attendance-camera-error-icon">
                        <i class="bi bi-camera-video-off"></i>
                    </div>

                    <strong>Kamera tidak dapat diakses</strong>

                    <small>
                        Aktifkan izin kamera di browser lalu muat ulang halaman.
                    </small>

                </div>


                {{-- CAMERA LABEL --}}
                <div class="student-attendance-camera-label">

                    <i class="bi bi-camera-video"></i>

                    <span>Arahkan QR Code ke dalam area pemindaian</span>

                </div>

            </div>


            {{-- =================================================
                FEEDBACK
            ================================================== --}}
            <div id="scan-feedback"
                 class="student-attendance-feedback">

                <div class="student-attendance-feedback-box">

                    <div class="student-attendance-feedback-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div class="student-attendance-feedback-content">

                        <strong>Siap memindai</strong>

                        <span>
                            Scan otomatis aktif. Tahan kamera agar QR Code terlihat jelas.
                        </span>

                    </div>

                </div>

            </div>


            {{-- =================================================
                SWITCH CAMERA
            ================================================== --}}
            <button type="button"
                    class="student-attendance-camera-button"
                    onclick="switchCamera()">

                <i class="bi bi-camera2"></i>

                <span>Ganti Kamera</span>

                <small>Depan / Belakang</small>

            </button>

        </div>


    </div>

</div>


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<script>

const submitUrl  = @json(route('siswa.attendance.submit-scan'));
const csrfToken  = @json(csrf_token());
const urlToken   = @json($tokenFromUrl);

let currentStream = null,
    facingMode = 'environment',
    scanning = true;

let lastScannedData = null,
    lastScannedTime = 0;

const video  = document.getElementById('video');
const canvas = document.getElementById('canvas');
const ctx    = canvas.getContext('2d');


async function startCamera(mode = 'environment') {

    if (currentStream) {
        currentStream.getTracks().forEach(t => t.stop());
    }

    try {

        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: mode,
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        });

        currentStream = stream;

        video.srcObject = stream;

        video.play();

        document
            .getElementById('camera-error')
            .classList.add('d-none');

        scanning = true;

        requestAnimationFrame(tick);

    } catch(e) {

        const el = document.getElementById('camera-error');

        el.classList.remove('d-none');

        el.classList.add('d-flex');

    }

}


function switchCamera() {

    facingMode =
        facingMode === 'environment'
            ? 'user'
            : 'environment';

    startCamera(facingMode);

}


function tick() {

    if (!scanning) return;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {

        canvas.width = video.videoWidth;

        canvas.height = video.videoHeight;

        ctx.drawImage(
            video,
            0,
            0,
            canvas.width,
            canvas.height
        );

        const imageData =
            ctx.getImageData(
                0,
                0,
                canvas.width,
                canvas.height
            );

        const code = jsQR(
            imageData.data,
            imageData.width,
            imageData.height,
            {
                inversionAttempts: 'dontInvert'
            }
        );

        if (code) {

            const now = Date.now();

            if (
                code.data !== lastScannedData ||
                (now - lastScannedTime) > 3000
            ) {

                lastScannedData = code.data;

                lastScannedTime = now;

                handleQr(code.data);

            }

        }

    }

    requestAnimationFrame(tick);

}


async function handleQr(rawData) {

    let token = rawData;

    try {

        token =
            new URL(rawData).searchParams.get('token')
            || rawData;

    } catch(e) {}

    await submitToken(token);

}


async function submitToken(token) {

    setFeedback(
        'loading',
        'bi-hourglass-split',
        'Memproses...',
        ''
    );

    try {

        const res = await fetch(
            submitUrl,
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },

                body: JSON.stringify({
                    token
                })
            }
        );

        const data = await res.json();

        if (data.success) {

            setFeedback(
                data.already ? 'warning' : 'success',

                data.already
                    ? 'bi-check-circle'
                    : 'bi-check-circle-fill',

                data.already
                    ? 'Sudah tercatat hadir'
                    : 'Presensi berhasil!',

                `${data.subject} — ${data.classroom}`
            );

        } else {

            setFeedback(
                'danger',
                'bi-x-circle-fill',
                data.message,
                ''
            );

        }

    } catch(e) {

        setFeedback(
            'danger',
            'bi-wifi-off',
            'Terjadi kesalahan.',
            'Periksa koneksi internet.'
        );

    }

}


function setFeedback(type, icon, title, description) {

    const feedback =
        document.getElementById('scan-feedback');

    feedback.innerHTML = `

        <div class="student-attendance-feedback-box ${type}">

            <div class="student-attendance-feedback-icon">

                <i class="bi ${icon}"></i>

            </div>

            <div class="student-attendance-feedback-content">

                <strong>${title}</strong>

                ${
                    description
                        ? `<span>${description}</span>`
                        : ''
                }

            </div>

        </div>

    `;

}


startCamera(facingMode);


if (urlToken) {

    setTimeout(() => {
        submitToken(urlToken);
    }, 800);

}

</script>

@endpush

@endsection