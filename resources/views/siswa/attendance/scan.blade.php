@extends('layouts.app')
@section('title', 'Scan Presensi')
@section('content')

<div class="row justify-content-center py-3">
    <div class="col-md-7 col-lg-5">

        <div class="text-center mb-4">
            <h5 class="fw-bold mb-1">
                <i class="bi bi-qr-code-scan text-success me-2"></i>Scan Presensi
            </h5>
            <p class="text-muted small">Arahkan kamera ke QR yang ditampilkan guru di kelas</p>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-3">

                <div class="position-relative bg-dark rounded-3 overflow-hidden mb-3"
                     style="aspect-ratio:1;">
                    <video id="video" class="w-100 h-100 object-fit-cover" autoplay playsinline muted></video>
                    <canvas id="canvas" class="d-none"></canvas>

                    <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none" id="scan-corners">
                        <svg width="100%" height="100%" viewBox="0 0 300 300" preserveAspectRatio="none" style="position:absolute;top:0;left:0;">
                            <path d="M40,40 L40,80 M40,40 L80,40" stroke="#20c997" stroke-width="4" fill="none" stroke-linecap="round"/>
                            <path d="M260,40 L220,40 M260,40 L260,80" stroke="#20c997" stroke-width="4" fill="none" stroke-linecap="round"/>
                            <path d="M40,260 L40,220 M40,260 L80,260" stroke="#20c997" stroke-width="4" fill="none" stroke-linecap="round"/>
                            <path d="M260,260 L220,260 M260,260 L260,220" stroke="#20c997" stroke-width="4" fill="none" stroke-linecap="round"/>
                            <line id="scan-line" x1="50" y1="50" x2="250" y2="50" stroke="#20c997" stroke-width="2" opacity="0.8"/>
                        </svg>
                    </div>

                    <div id="camera-error"
                         class="position-absolute top-0 start-0 w-100 h-100 d-none flex-column align-items-center justify-content-center text-white text-center p-3">
                        <i class="bi bi-camera-video-off fs-1 mb-2 text-danger"></i>
                        <strong>Kamera tidak dapat diakses</strong>
                        <small class="text-white-50 mt-1">Aktifkan izin kamera di browser lalu muat ulang halaman.</small>
                    </div>
                </div>

                <div id="scan-feedback" class="mb-3">
                    <div class="alert alert-secondary py-2 mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle small"></i>
                        <span class="small">Scan otomatis aktif. Tahan kamera agar QR terlihat jelas.</span>
                    </div>
                </div>

                <button class="btn btn-outline-secondary btn-sm w-100" onclick="switchCamera()">
                    <i class="bi bi-camera2 me-1"></i> Ganti Kamera (Depan / Belakang)
                </button>

            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('siswa.attendance.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Presensi
            </a>
        </div>

    </div>
</div>

<style>
#scan-line { animation: scanMove 2s ease-in-out infinite; }
@keyframes scanMove {
    0%   { transform: translateY(0); }
    50%  { transform: translateY(200px); }
    100% { transform: translateY(0); }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
const submitUrl  = @json(route('siswa.attendance.submit-scan'));
const csrfToken  = @json(csrf_token());
const urlToken   = @json($tokenFromUrl);

let currentStream = null, facingMode = 'environment', scanning = true;
let lastScannedData = null, lastScannedTime = 0;

const video  = document.getElementById('video');
const canvas = document.getElementById('canvas');
const ctx    = canvas.getContext('2d');

async function startCamera(mode = 'environment') {
    if (currentStream) currentStream.getTracks().forEach(t => t.stop());
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: mode, width:{ideal:1280}, height:{ideal:720} } });
        currentStream = stream;
        video.srcObject = stream;
        video.play();
        document.getElementById('camera-error').classList.add('d-none');
        scanning = true;
        requestAnimationFrame(tick);
    } catch(e) {
        const el = document.getElementById('camera-error');
        el.classList.remove('d-none');
        el.classList.add('d-flex');
    }
}

function switchCamera() {
    facingMode = facingMode === 'environment' ? 'user' : 'environment';
    startCamera(facingMode);
}

function tick() {
    if (!scanning) return;
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' });
        if (code) {
            const now = Date.now();
            if (code.data !== lastScannedData || (now - lastScannedTime) > 3000) {
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
    try { token = new URL(rawData).searchParams.get('token') || rawData; } catch(e) {}
    await submitToken(token);
}

async function submitToken(token) {
    setFeedback('secondary', 'bi-hourglass-split', 'Memproses...');
    try {
        const res  = await fetch(submitUrl, {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':csrfToken, 'Accept':'application/json' },
            body: JSON.stringify({ token })
        });
        const data = await res.json();
        if (data.success) {
            setFeedback(data.already ? 'warning' : 'success',
                        data.already ? 'bi-check-circle' : 'bi-check-circle-fill',
                        `${data.already ? 'Sudah tercatat hadir' : '✅ Presensi berhasil!'}<br><small class="opacity-75">${data.subject} — ${data.classroom}</small>`);
        } else {
            setFeedback('danger', 'bi-x-circle-fill', data.message);
        }
    } catch(e) {
        setFeedback('danger', 'bi-wifi-off', 'Terjadi kesalahan. Periksa koneksi internet.');
    }
}

function setFeedback(type, icon, text) {
    document.getElementById('scan-feedback').innerHTML = `
        <div class="alert alert-${type} py-2 mb-0 d-flex align-items-center gap-2">
            <i class="bi ${icon}"></i>
            <span class="small">${text}</span>
        </div>`;
}

startCamera(facingMode);
if (urlToken) setTimeout(() => submitToken(urlToken), 800);
</script>
@endpush

@endsection
