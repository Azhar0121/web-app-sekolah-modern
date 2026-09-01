@extends('layouts.app')

@section('title', 'Kelola Presensi')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">{{ $schedule->teachingAssignment->subject->name }}</h4>
            <p class="text-muted mb-0">
                Kelas <strong>{{ $schedule->teachingAssignment->classroom->name }}</strong>
                &middot; {{ $attendanceSession->date->translatedFormat('l, d F Y') }}
                &middot; {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
            </p>
        </div>
        <div>
            @if ($attendanceSession->isOpen())
                <span class="badge text-bg-success fs-6">Sesi Berlangsung</span>
            @else
                <span class="badge text-bg-dark fs-6">Sesi Selesai</span>
            @endif
        </div>
    </div>
</div>

@if ($attendanceSession->isOpen())
    <div class="row g-3 mb-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Scan QR Kartu Pelajar</div>
                <div class="card-body">
                    <div id="reader" style="width: 100%;"></div>
                    <div id="scan-feedback" class="mt-3"></div>
                    <p class="text-muted small mt-2 mb-0">
                        Arahkan kamera ke QR pada kartu pelajar digital siswa. Status di tabel
                        sebelah akan otomatis terupdate begitu QR terbaca.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            @include('guru.attendance.partials.roster')
        </div>
    </div>

    <form method="POST" action="{{ route('guru.attendance.close', $attendanceSession) }}"
          onsubmit="return confirm('Tutup sesi presensi? Siswa yang belum tercatat akan otomatis ditandai Alpha.');">
        @csrf
        <button type="submit" class="btn btn-outline-danger">Tutup Sesi Presensi</button>
    </form>
@else
    @include('guru.attendance.partials.roster')
@endif

<div class="mt-3">
    <a href="{{ route('guru.attendance.index') }}" class="text-decoration-none">&larr; Kembali ke Presensi Kelas</a>
</div>

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
            box.innerHTML = `<div class="alert ${ok ? 'alert-success' : 'alert-danger'} py-2 mb-0">${message}</div>`;
        }

        function updateRosterRow(studentName, statusLabel, badgeClass) {
            const row = document.querySelector(`tr[data-student-name="${CSS.escape(studentName)}"]`);
            if (!row) return;
            const cell = row.querySelector('.status-cell');
            if (cell) {
                cell.innerHTML = `<span class="badge ${badgeClass}">${statusLabel}</span>`;
            }
        }

        function onScanSuccess(decodedText) {
            const now = Date.now();
            if (decodedText === lastToken && (now - lastScanTime) < 3000) {
                return; // debounce — kamera masih melihat QR yang sama
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
                body: JSON.stringify({ token: decodedText }),
            })
                .then((res) => res.json().then((data) => ({ status: res.status, data })))
                .then(({ data }) => {
                    showFeedback(data.message, data.success);
                    if (data.success && data.student_name) {
                        updateRosterRow(data.student_name, 'Hadir', 'text-bg-success');
                    }
                })
                .catch(() => showFeedback('Terjadi kesalahan, coba scan ulang.', false));
        }

        const html5QrCode = new Html5Qrcode('reader');
        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: 240 },
            onScanSuccess
        ).catch(() => {
            document.getElementById('reader').innerHTML =
                '<div class="alert alert-warning py-2 mb-0">Tidak bisa mengakses kamera. Pastikan izin kamera browser diaktifkan.</div>';
        });
    </script>
    @endpush
@endif
@endsection
