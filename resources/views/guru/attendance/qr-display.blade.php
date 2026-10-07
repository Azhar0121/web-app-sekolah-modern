@extends('layouts.admin')

@section('title', 'QR Presensi — ' . $attendanceSession->schedule->teachingAssignment->subject->name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/guru/attendance/qr.css') }}">

<div class="guru-attendance-qr">

    {{-- HERO --}}
    <div class="qr-page-header">

        <div class="qr-header-content">

            <div class="qr-header-icon">
                <i class="bi bi-qr-code"></i>
            </div>

            <div class="qr-header-info">

                <div class="qr-header-label">
                    PRESENSI KELAS
                </div>

                <h1>
                    {{ $attendanceSession->schedule->teachingAssignment->subject->name }}
                </h1>

                <p>
                    <span>
                        <i class="bi bi-door-open-fill"></i>
                        {{ $attendanceSession->schedule->teachingAssignment->classroom->name }}
                    </span>

                    <span class="qr-header-separator">•</span>

                    <span>
                        <i class="bi bi-calendar3"></i>
                        {{ $attendanceSession->date->translatedFormat('l, d F Y') }}
                    </span>

                    <span class="qr-header-separator">•</span>

                    <span>
                        <i class="bi bi-clock"></i>
                        {{ $attendanceSession->schedule->start_time->format('H:i') }}
                        –
                        {{ $attendanceSession->schedule->end_time->format('H:i') }}
                    </span>
                </p>

            </div>

        </div>

        <div class="qr-header-badge">
            <i class="bi bi-broadcast-pin"></i>
            Sesi Presensi
        </div>

        <div class="qr-hero-decoration one"></div>
        <div class="qr-hero-decoration two"></div>

    </div>


    {{-- QR CARD --}}
    <div class="qr-main-card">

        <div class="qr-card-header">

            <div class="qr-card-title">

                <div class="qr-section-icon">
                    <i class="bi bi-qr-code-scan"></i>
                </div>

                <div>
                    <div class="qr-card-eyebrow">
                        KODE PRESENSI
                    </div>

                    <h2>
                        Scan untuk Presensi
                    </h2>

                    <p>
                        Siswa dapat memindai kode ini melalui portal presensi.
                    </p>
                </div>

            </div>

        </div>


        <div class="qr-card-body">

            {{-- QR CODE --}}
            <div class="qr-code-area">

                <div class="qr-code-frame">

                    <div id="qr-svg-container">
                        {!! $qrSvg !!}
                    </div>

                    <div
                        id="expired-overlay"
                        class="qr-expired-overlay d-none"
                    >
                        <div class="qr-expired-icon">
                            <i class="bi bi-hourglass-bottom"></i>
                        </div>

                        <strong>
                            QR Kadaluarsa
                        </strong>

                        <span>
                            Klik Refresh untuk melanjutkan.
                        </span>
                    </div>

                </div>

            </div>


            {{-- TIMER --}}
            <div class="qr-timer-section">

                <div class="qr-timer-heading">

                    <span>
                        <i class="bi bi-clock-history"></i>
                        Waktu berlaku QR
                    </span>

                    <strong id="qr-countdown">
                        {{ $ttlMinutes }}:00
                    </strong>

                </div>

                <div class="qr-progress">
                    <div
                        id="qr-progress"
                        class="qr-progress-bar"
                        style="width:100%;"
                    ></div>
                </div>

                <div class="qr-timer-note">
                    Kode akan diperbarui setelah waktu berakhir.
                </div>

            </div>


            {{-- ATTENDANCE COUNT --}}
            <div class="qr-attendance-count">

                <div class="qr-attendance-icon">
                    <i class="bi bi-person-check-fill"></i>
                </div>

                <div>
                    <div class="qr-attendance-label">
                        Kehadiran Siswa
                    </div>

                    <span id="scan-count-text">
                        Memuat jumlah hadir...
                    </span>
                </div>

            </div>


            {{-- ACTIONS --}}
            <div class="qr-actions">

                <button
                    type="button"
                    class="qr-refresh-button"
                    id="refresh-btn"
                    onclick="refreshQr()"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Refresh QR</span>
                </button>

                <a
                    href="{{ route('guru.attendance.session', $attendanceSession->schedule) }}"
                    class="qr-student-button"
                >
                    <i class="bi bi-list-ul"></i>
                    <span>Lihat Daftar Siswa</span>
                </a>

            </div>

        </div>

    </div>


    {{-- INSTRUCTION --}}
    <div class="qr-instruction">

        <div class="qr-instruction-icon">
            <i class="bi bi-info-circle-fill"></i>
        </div>

        <div class="qr-instruction-content">

            <strong>
                Cara presensi siswa
            </strong>

            <p>
                Buka portal siswa
                <span>→</span>
                menu <strong>Presensi</strong>
                <span>→</span>
                <strong>Scan QR Guru</strong>
                <span>→</span>
                arahkan kamera ke QR ini.
            </p>

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


/*
|--------------------------------------------------------------------------
| FORMAT TIME
|--------------------------------------------------------------------------
*/

function formatTime(s) {
    return `${Math.floor(s / 60)}:${String(s % 60).padStart(2, '0')}`;
}


/*
|--------------------------------------------------------------------------
| UPDATE TIMER
|--------------------------------------------------------------------------
*/

function updateTimer() {

    const countdown = document.getElementById('qr-countdown');
    const progress  = document.getElementById('qr-progress');
    const overlay   = document.getElementById('expired-overlay');

    if (secondsLeft <= 0) {

        clearInterval(countInterval);

        countdown.textContent = '0:00';

        countdown.classList.add('expired');

        progress.style.width = '0%';

        progress.classList.remove('warning');
        progress.classList.add('expired');

        overlay.classList.remove('d-none');
        overlay.classList.add('active');

        return;
    }

    secondsLeft--;

    countdown.textContent = formatTime(secondsLeft);

    progress.style.width =
        ((secondsLeft / ttlSeconds) * 100) + '%';

    if (secondsLeft <= 30) {

        countdown.classList.add('warning');

        progress.classList.remove('normal');
        progress.classList.add('warning');

    }
}

countInterval = setInterval(updateTimer, 1000);


/*
|--------------------------------------------------------------------------
| REFRESH QR
|--------------------------------------------------------------------------
*/

async function refreshQr() {

    if (isRefreshing) {
        return;
    }

    isRefreshing = true;

    const btn = document.getElementById('refresh-btn');

    btn.disabled = true;

    btn.innerHTML =
        '<span class="qr-spinner"></span>' +
        '<span>Memperbarui...</span>';

    try {

        const res = await fetch(refreshUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        if (data.success) {

            document.getElementById('qr-svg-container').innerHTML =
                data.qrSvg;

            const overlay =
                document.getElementById('expired-overlay');

            overlay.classList.add('d-none');
            overlay.classList.remove('active');

            secondsLeft =
                data.ttlSeconds ?? ttlSeconds;

            const countdown =
                document.getElementById('qr-countdown');

            countdown.classList.remove('warning');
            countdown.classList.remove('expired');

            const progress =
                document.getElementById('qr-progress');

            progress.classList.remove('warning');
            progress.classList.remove('expired');

            progress.classList.add('normal');

            progress.style.width = '100%';

            clearInterval(countInterval);

            countInterval =
                setInterval(updateTimer, 1000);
        }

    } catch (e) {

        alert(
            'Gagal memperbarui QR. Periksa koneksi dan coba lagi.'
        );

    } finally {

        isRefreshing = false;

        btn.disabled = false;

        btn.innerHTML =
            '<i class="bi bi-arrow-clockwise"></i>' +
            '<span>Refresh QR</span>';
    }
}


/*
|--------------------------------------------------------------------------
| FETCH SCAN COUNT
|--------------------------------------------------------------------------
*/

async function fetchScanCount() {

    try {

        const res = await fetch(
            `/guru/presensi/sesi/${sessionId}/hadir-count`,
            {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }
        );

        if (!res.ok) {
            return;
        }

        const data = await res.json();

        document.getElementById('scan-count-text').textContent =
            `${data.count} siswa sudah tercatat Hadir`;

    } catch (e) {
        // Silent fail agar polling tidak mengganggu halaman.
    }
}


fetchScanCount();

setInterval(fetchScanCount, 8000);
</script>
@endpush

@endsection