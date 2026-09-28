@extends('layouts.admin')
@section('title', 'QR Presensi — ' . $attendanceSession->schedule->teachingAssignment->subject->name)
@section('content')

<div class="text-center mb-4">
    <span class="badge bg-primary-subtle text-primary fw-semibold mb-2">
        <i class="bi bi-book-fill me-1"></i>
        {{ $attendanceSession->schedule->teachingAssignment->subject->name }}
    </span>
    <h4 class="fw-bold mb-1">{{ $attendanceSession->schedule->teachingAssignment->classroom->name }}</h4>
    <p class="text-muted small mb-0">
        <i class="bi bi-calendar3 me-1"></i>{{ $attendanceSession->date->translatedFormat('l, d F Y') }}
        &nbsp;•&nbsp;
        <i class="bi bi-clock me-1"></i>{{ $attendanceSession->schedule->start_time->format('H:i') }} – {{ $attendanceSession->schedule->end_time->format('H:i') }}
    </p>
</div>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="card border-0 shadow text-center">
            <div class="card-body p-4">

                <small class="text-muted text-uppercase fw-bold letter-spacing-1 d-flex align-items-center justify-content-center gap-1 mb-3">
                    <i class="bi bi-qr-code text-primary"></i> Scan untuk Presensi
                </small>

                <div class="position-relative d-inline-block mb-3">
                    <div id="qr-svg-container" class="border rounded-3 p-2 bg-white shadow-sm d-inline-block">
                        {!! $qrSvg !!}
                    </div>
                    <div id="expired-overlay"
                         class="position-absolute top-0 start-0 w-100 h-100 d-none flex-column align-items-center justify-content-center bg-white bg-opacity-90 rounded-3">
                        <i class="bi bi-hourglass-bottom text-danger fs-1 mb-1"></i>
                        <span class="fw-bold text-danger small">QR Kadaluarsa</span>
                        <span class="text-muted" style="font-size:.7rem;">Klik Refresh untuk lanjutkan</span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Waktu berlaku QR</span>
                        <span id="qr-countdown" class="fw-bold text-dark">{{ $ttlMinutes }}:00</span>
                    </div>
                    <div class="progress" style="height:8px;">
                        <div id="qr-progress" class="progress-bar bg-success progress-bar-animated" style="width:100%;transition:width 1s linear;"></div>
                    </div>
                </div>

                <div class="alert alert-success py-2 mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-person-check-fill text-success"></i>
                    <span id="scan-count-text" class="small fw-semibold">Memuat jumlah hadir...</span>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary fw-bold" id="refresh-btn" onclick="refreshQr()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Refresh QR
                    </button>
                    <a href="{{ route('guru.attendance.session', $attendanceSession->schedule) }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-list-ul me-2"></i>Lihat Daftar Siswa
                    </a>
                </div>

            </div>
        </div>

        <div class="alert alert-light border mt-3 text-center small">
            <strong>Cara presensi siswa:</strong> Buka portal → menu <strong>Presensi</strong> →
            <strong>Scan QR Guru</strong> → arahkan kamera ke QR ini.
        </div>
    </div>
</div>

@push('scripts')
<script>
const refreshUrl  = @json(route('guru.attendance.refresh-qr', $attendanceSession));
const csrfToken   = @json(csrf_token());
const ttlMinutes  = @json($ttlMinutes);
const ttlSeconds  = ttlMinutes * 60;
const sessionId   = @json($attendanceSession->id);

let secondsLeft  = ttlSeconds;
let isRefreshing = false;
let countInterval;

function formatTime(s) {
    return `${Math.floor(s/60)}:${String(s%60).padStart(2,'0')}`;
}

function updateTimer() {
    const countdown = document.getElementById('qr-countdown');
    const progress  = document.getElementById('qr-progress');
    const overlay   = document.getElementById('expired-overlay');

    if (secondsLeft <= 0) {
        clearInterval(countInterval);
        countdown.textContent = '0:00';
        countdown.classList.add('text-danger');
        progress.style.width = '0%';
        progress.classList.remove('bg-success');
        progress.classList.add('bg-danger');
        overlay.classList.remove('d-none');
        overlay.classList.add('d-flex');
        return;
    }
    secondsLeft--;
    countdown.textContent = formatTime(secondsLeft);
    progress.style.width = ((secondsLeft / ttlSeconds) * 100) + '%';
    if (secondsLeft <= 30) {
        countdown.classList.add('text-danger');
        progress.classList.replace('bg-success','bg-danger');
    }
}

countInterval = setInterval(updateTimer, 1000);

async function refreshQr() {
    if (isRefreshing) return;
    isRefreshing = true;
    const btn = document.getElementById('refresh-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memperbarui...';

    try {
        const res  = await fetch(refreshUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('qr-svg-container').innerHTML = data.qrSvg;
            const overlay = document.getElementById('expired-overlay');
            overlay.classList.add('d-none');
            overlay.classList.remove('d-flex');
            secondsLeft = data.ttlSeconds ?? ttlSeconds;
            const countdown = document.getElementById('qr-countdown');
            countdown.classList.remove('text-danger');
            const progress = document.getElementById('qr-progress');
            progress.classList.replace('bg-danger','bg-success');
            progress.style.width = '100%';
            clearInterval(countInterval);
            countInterval = setInterval(updateTimer, 1000);
        }
    } catch(e) {
        alert('Gagal memperbarui QR. Periksa koneksi dan coba lagi.');
    } finally {
        isRefreshing = false;
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Refresh QR';
    }
}

async function fetchScanCount() {
    try {
        const res  = await fetch(`/guru/presensi/sesi/${sessionId}/hadir-count`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        });
        if (!res.ok) return;
        const data = await res.json();
        document.getElementById('scan-count-text').textContent = `${data.count} siswa sudah tercatat Hadir`;
    } catch(e) {}
}
fetchScanCount();
setInterval(fetchScanCount, 8000);
</script>
@endpush

@endsection
