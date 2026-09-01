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

                <p class="text-muted small mb-0">
                    Tunjukkan QR ini ke kamera guru saat presensi kelas berlangsung.
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
    new QRCode(document.getElementById('qr-code'), {
        text: @json($token),
        width: 220,
        height: 220,
        colorDark: '#071b35',
        colorLight: '#ffffff',
    });
</script>
@endpush
@endsection
