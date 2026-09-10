@extends('layouts.app')

@section('title', 'Kartu Pelajar Digital')

@section('content')

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      
<link rel="stylesheet" href="{{ asset('css/siswa/qr/show.css') }}">

<div class="student-qr-page">

    <div class="student-qr-wrapper">

        <div class="student-qr-card">

            <div class="student-qr-header">
                <span class="student-qr-badge">
                    <i class="bi bi-person-badge-fill"></i>
                    Kartu Pelajar Digital
                </span>

                <div class="student-qr-title">
                    <h4>{{ $student->name }}</h4>

                    <p>
                        {{ $classroom?->name ?? 'Kelas belum ditentukan' }}
                        <span>•</span>
                        {{ $student->email }}
                    </p>
                </div>
            </div>


            <div class="student-qr-body">

                <div class="student-qr-label">
                    <i class="bi bi-qr-code-scan"></i>
                    QR Presensi Siswa
                </div>

                <div id="qr-code" class="student-qr-code"></div>

                <div class="student-qr-timer">

                    <div class="student-qr-progress">
                        <div
                            id="qr-progress"
                            class="student-qr-progress-bar"
                            style="width: 100%;">
                        </div>
                    </div>

                    <span id="qr-countdown">{{ $ttl }}s</span>

                </div>

                <div class="student-qr-security">
                    <i class="bi bi-shield-check"></i>

                    <div>
                        <strong>QR otomatis diperbarui</strong>

                        <p>
                            QR berganti setiap {{ $ttl }} detik demi keamanan
                            dan mencegah penggunaan screenshot untuk titip absen.
                        </p>
                    </div>
                </div>

            </div>


            <div class="student-qr-footer">

                <div class="student-qr-footer-icon">
                    <i class="bi bi-camera"></i>
                </div>

                <div>
                    <strong>Tunjukkan QR ke kamera guru</strong>

                    <span>
                        Jangan bagikan tangkapan layar QR kepada orang lain.
                    </span>
                </div>

            </div>

        </div>


        <a
            href="{{ route('siswa.attendance.index') }}"
            class="student-qr-history"
        >
            <span>
                <i class="bi bi-clock-history"></i>
                Lihat Riwayat Presensi Saya
            </span>

            <i class="bi bi-arrow-right"></i>
        </a>

    </div>

</div>


@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    const refreshUrl = @json(route('siswa.qr-code.refresh'));
    const csrfToken = @json(csrf_token());
    let ttlSeconds = @json($ttl);
    let secondsLeft = ttlSeconds;
    let isRotating = false;

    const qrCode = new QRCode(document.getElementById('qr-code'), {
        text: @json($token),
        width: 220,
        height: 220,
        colorDark: '#071b35',
        colorLight: '#ffffff',
    });

    function updateCountdownUI() {
        document.getElementById('qr-countdown').textContent = secondsLeft + 's';
        const pct = Math.max(0, (secondsLeft / ttlSeconds) * 100);
        document.getElementById('qr-progress').style.width = pct + '%';
    }

    async function rotateToken() {
        if (isRotating) return;
        isRotating = true;

        try {
            const res = await fetch(refreshUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await res.json();

            qrCode.makeCode(data.token);

            ttlSeconds = data.ttl;
            secondsLeft = ttlSeconds;

            updateCountdownUI();

        } catch (e) {

        } finally {
            isRotating = false;
        }
    }

    setInterval(() => {
        secondsLeft -= 1;

        if (secondsLeft <= 0) {
            rotateToken();
        } else {
            updateCountdownUI();
        }
    }, 1000);
</script>

@endpush

@endsection