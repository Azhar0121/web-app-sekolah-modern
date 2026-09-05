@extends('layouts.app')

@section('title', 'Kartu Pelajar Digital')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <span class="badge text-bg-primary mb-2">KARTU PELAJAR DIGITAL</span>
                <h4 class="fw-bold mb-1">{{ $student->name }}</h4>
                <p class="text-muted mb-3">
                    {{ $classroom?->name ?? 'Kelas belum ditentukan' }}
                    &middot; {{ $student->email }}
                </p>

                <div id="qr-code" class="d-flex justify-content-center my-3"></div>

                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                    <div class="progress" style="height: 6px; width: 140px;">
                        <div id="qr-progress" class="progress-bar bg-primary" style="width: 100%;"></div>
                    </div>
                    <span id="qr-countdown" class="text-muted small">{{ $ttl }}s</span>
                </div>

                <p class="text-muted small mb-0">
                    QR ini <strong>otomatis berganti setiap {{ $ttl }} detik</strong> demi keamanan —
                    supaya tidak bisa dipakai titip absen lewat screenshot.
                    Tunjukkan langsung dari HP Anda ke kamera guru saat presensi berlangsung.
                    Jangan bagikan tangkapan layar QR ini ke orang lain.
                </p>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('siswa.attendance.index') }}" class="text-decoration-none">
                Lihat Riwayat Presensi Saya &rarr;
            </a>
        </div>

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

    // Hitung mundur tiap detik, dan minta token baru begitu waktunya habis.
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
