@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ajukan Izin Baru</h2>

    <div class="card mt-4 col-md-8">
        <div class="card-body">
            <form action="{{ route('ortu.leave-requests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="student_id" class="form-label">Pilih Anak <span class="text-danger">*</span></label>
                    <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        @foreach($children as $child)
                            <option value="{{ $child->id }}" {{ old('student_id') == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                        @endforeach
                    </select>
                    @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Izin <span class="text-danger">*</span></label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="type_sakit" value="sakit" {{ old('type') == 'sakit' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="type_sakit">Sakit</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="type_izin" value="izin" {{ old('type') == 'izin' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="type_izin">Izin Lainnya</label>
                        </div>
                    </div>
                    @error('type')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required min="{{ date('Y-m-d') }}">
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required min="{{ date('Y-m-d') }}">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Alasan / Keterangan <span class="text-danger">*</span></label>
                    <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason') }}</textarea>
                    @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="attachment" class="form-label">Lampiran (Surat Dokter / Bukti Lain)</label>
                    <input type="file" name="attachment" id="attachment" class="form-control @error('attachment') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text">Format: PDF, JPG, PNG. Maksimal 5MB. (Opsional)</div>
                    @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                    <a href="{{ route('ortu.leave-requests.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
