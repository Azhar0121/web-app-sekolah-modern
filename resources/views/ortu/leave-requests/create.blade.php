@extends('layouts.app')

@section('title', 'Ajukan Izin Baru')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ortu/leave-requests/create.css') }}">

<div class="ortu-leave-create">
    <div class="olc-container">

        {{-- HERO --}}
        <section class="olc-hero">
            <div class="olc-hero-decoration olc-decoration-one"></div>
            <div class="olc-hero-decoration olc-decoration-two"></div>

            <div class="olc-hero-content">
                <div class="olc-eyebrow">
                    <i class="bi bi-file-earmark-plus"></i>
                    <span>PORTAL ORANG TUA / WALI</span>
                </div>

                <h1>Ajukan Izin Baru</h1>

                <p>
                    Sampaikan informasi ketidakhadiran anak dengan mengisi
                    formulir berikut secara lengkap dan benar.
                </p>

                <div class="olc-hero-note">
                    <i class="bi bi-info-circle"></i>
                    <span>Kolom bertanda <strong>*</strong> wajib diisi.</span>
                </div>
            </div>

            <div class="olc-hero-icon">
                <i class="bi bi-calendar2-check"></i>
            </div>
        </section>

        {{-- ERROR VALIDASI UMUM --}}
        @if ($errors->any())
            <div class="olc-error-alert" role="alert">
                <div class="olc-alert-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <strong>Periksa kembali formulir Anda</strong>
                    <p>Masih ada informasi yang perlu diperbaiki sebelum pengajuan dikirim.</p>
                </div>
            </div>
        @endif

        {{-- FORM --}}
        <section class="olc-form-card">
            <div class="olc-form-heading">
                <div class="olc-form-heading-icon">
                    <i class="bi bi-clipboard2-check"></i>
                </div>
                <div>
                    <h2>Formulir Pengajuan Izin</h2>
                    <p>Lengkapi data pengajuan izin anak Anda.</p>
                </div>
            </div>

            <form
                action="{{ route('ortu.leave-requests.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                {{-- PILIH ANAK --}}
                <div class="olc-field">
                    <label for="student_id" class="olc-label">
                        Pilih Anak <span class="olc-required">*</span>
                    </label>

                    <div class="olc-input-wrap">
                        <span class="olc-input-icon">
                            <i class="bi bi-person"></i>
                        </span>

                        <select
                            name="student_id"
                            id="student_id"
                            class="olc-control olc-select @error('student_id') is-invalid @enderror"
                            required
                        >
                            <option value="">-- Pilih anak --</option>
                            @foreach ($children as $child)
                                <option
                                    value="{{ $child->id }}"
                                    {{ old('student_id') == $child->id ? 'selected' : '' }}
                                >
                                    {{ $child->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @error('student_id')
                        <div class="olc-field-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- JENIS IZIN --}}
                <div class="olc-field">
                    <label class="olc-label">
                        Jenis Izin <span class="olc-required">*</span>
                    </label>

                    <div class="olc-type-options">
                        <label
                            for="type_sakit"
                            class="olc-type-option {{ old('type') === 'sakit' ? 'is-selected' : '' }}"
                        >
                            <input
                                class="olc-radio"
                                type="radio"
                                name="type"
                                id="type_sakit"
                                value="sakit"
                                {{ old('type') === 'sakit' ? 'checked' : '' }}
                                required
                            >

                            <span class="olc-type-icon olc-type-icon-sick">
                                <i class="bi bi-heart-pulse"></i>
                            </span>

                            <span class="olc-type-copy">
                                <strong>Sakit</strong>
                                <small>Ketidakhadiran karena kondisi kesehatan.</small>
                            </span>

                            <span class="olc-type-check">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>
                        </label>

                        <label
                            for="type_izin"
                            class="olc-type-option {{ old('type') === 'izin' ? 'is-selected' : '' }}"
                        >
                            <input
                                class="olc-radio"
                                type="radio"
                                name="type"
                                id="type_izin"
                                value="izin"
                                {{ old('type') === 'izin' ? 'checked' : '' }}
                                required
                            >

                            <span class="olc-type-icon olc-type-icon-permission">
                                <i class="bi bi-calendar2-event"></i>
                            </span>

                            <span class="olc-type-copy">
                                <strong>Izin Lainnya</strong>
                                <small>Keperluan lain di luar kondisi sakit.</small>
                            </span>

                            <span class="olc-type-check">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>
                        </label>
                    </div>

                    @error('type')
                        <div class="olc-field-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- RENTANG TANGGAL --}}
                <div class="olc-section-divider">
                    <span class="olc-section-number">01</span>
                    <div>
                        <h3>Periode Izin</h3>
                        <p>Tentukan tanggal mulai dan tanggal berakhirnya izin.</p>
                    </div>
                </div>

                <div class="olc-date-grid">
                    <div class="olc-field">
                        <label for="start_date" class="olc-label">
                            Tanggal Mulai <span class="olc-required">*</span>
                        </label>

                        <div class="olc-input-wrap">
                            <span class="olc-input-icon">
                                <i class="bi bi-calendar-event"></i>
                            </span>

                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                class="olc-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date') }}"
                                required
                                min="{{ date('Y-m-d') }}"
                            >
                        </div>

                        @error('start_date')
                            <div class="olc-field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="olc-field">
                        <label for="end_date" class="olc-label">
                            Tanggal Selesai <span class="olc-required">*</span>
                        </label>

                        <div class="olc-input-wrap">
                            <span class="olc-input-icon">
                                <i class="bi bi-calendar-check"></i>
                            </span>

                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                class="olc-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date') }}"
                                required
                                min="{{ old('start_date', date('Y-m-d')) }}"
                            >
                        </div>

                        @error('end_date')
                            <div class="olc-field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- ALASAN --}}
                <div class="olc-section-divider">
                    <span class="olc-section-number">02</span>
                    <div>
                        <h3>Informasi Pengajuan</h3>
                        <p>Berikan alasan yang jelas untuk mendukung pengajuan izin.</p>
                    </div>
                </div>

                <div class="olc-field">
                    <label for="reason" class="olc-label">
                        Alasan / Keterangan <span class="olc-required">*</span>
                    </label>

                    <textarea
                        name="reason"
                        id="reason"
                        rows="5"
                        class="olc-control olc-textarea @error('reason') is-invalid @enderror"
                        placeholder="Tuliskan alasan izin secara jelas..."
                        required
                    >{{ old('reason') }}</textarea>

                    <div class="olc-field-hint">
                        <i class="bi bi-chat-left-text"></i>
                        Jelaskan alasan dan informasi tambahan yang perlu diketahui pihak sekolah.
                    </div>

                    @error('reason')
                        <div class="olc-field-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- LAMPIRAN --}}
                <div class="olc-field olc-attachment-field">
                    <label for="attachment" class="olc-label">
                        Lampiran
                        <span class="olc-optional">Opsional</span>
                    </label>

                    <label for="attachment" class="olc-upload-area">
                        <span class="olc-upload-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </span>

                        <span class="olc-upload-copy">
                            <strong>Pilih dokumen pendukung</strong>
                            <small>Surat dokter atau bukti pendukung lainnya</small>
                            <span class="olc-upload-format">PDF, JPG, atau PNG · Maksimal 5 MB</span>
                            <span class="olc-file-name" id="olc-file-name">
                                Belum ada file dipilih
                            </span>
                        </span>

                        <span class="olc-upload-button">Pilih File</span>
                    </label>

                    <input
                        type="file"
                        name="attachment"
                        id="attachment"
                        class="olc-file-input @error('attachment') is-invalid @enderror"
                        accept=".pdf,.jpg,.jpeg,.png"
                    >

                    @error('attachment')
                        <div class="olc-field-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- AKSI FORM --}}
                <div class="olc-form-actions">
                    <a
                        href="{{ route('ortu.leave-requests.index') }}"
                        class="olc-cancel-button"
                    >
                        <i class="bi bi-arrow-left"></i>
                        <span>Batal</span>
                    </a>

                    <button type="submit" class="olc-submit-button">
                        <i class="bi bi-send"></i>
                        <span>Kirim Pengajuan</span>
                    </button>
                </div>
            </form>
        </section>

        {{-- CATATAN --}}
        <div class="olc-footer-note">
            <i class="bi bi-shield-check"></i>
            <p>
                Pastikan informasi yang Anda masukkan sudah benar.
                Pengajuan akan diteruskan kepada pihak sekolah untuk diproses.
            </p>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const attachment = document.getElementById('attachment');
        const fileName = document.getElementById('olc-file-name');
        const typeRadios = document.querySelectorAll('.olc-radio');

        function updateEndDateMinimum() {
            if (!startDate || !endDate) return;

            endDate.min = startDate.value || startDate.min;

            if (startDate.value && endDate.value && endDate.value < startDate.value) {
                endDate.value = startDate.value;
            }
        }

        startDate?.addEventListener('change', updateEndDateMinimum);

        typeRadios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                document.querySelectorAll('.olc-type-option').forEach(function (option) {
                    option.classList.remove('is-selected');
                });

                if (radio.checked) {
                    radio.closest('.olc-type-option')?.classList.add('is-selected');
                }
            });
        });

        attachment?.addEventListener('change', function () {
            if (attachment.files && attachment.files.length > 0) {
                fileName.textContent = attachment.files[0].name;
                fileName.classList.add('has-file');
            } else {
                fileName.textContent = 'Belum ada file dipilih';
                fileName.classList.remove('has-file');
            }
        });

        updateEndDateMinimum();
    });
</script>
@endsection
