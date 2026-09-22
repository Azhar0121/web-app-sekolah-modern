@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('admin.billing.index') }}" class="btn btn-secondary">Kembali</a>
        <h2 class="mb-0">Detail Tagihan: {{ $student->name }}</h2>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Riwayat Tagihan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Deskripsi</th>
                            <th>Jatuh Tempo</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($billings as $billing)
                        <tr>
                            <td>
                                <strong>{{ $billing->description }}</strong>
                                @if($billing->notes)
                                    <br><small class="text-muted">{{ $billing->notes }}</small>
                                @endif
                            </td>
                            <td>{{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('d/m/Y') : '-' }}</td>
                            <td>Rp {{ number_format($billing->amount, 0, ',', '.') }}</td>
                            <td>
                                @if($billing->status === 'paid')
                                    <span class="badge bg-success">Lunas</span><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($billing->paid_at)->format('d/m/Y H:i') }}</small>
                                @else
                                    <span class="badge bg-danger">Belum Lunas</span>
                                @endif
                            </td>
                            <td>
                                @if($billing->status === 'unpaid')
                                    <form action="{{ route('admin.billing.confirm', $billing->id) }}" method="POST" enctype="multipart/form-data" class="d-inline">
                                        @csrf
                                        <div class="input-group input-group-sm mb-1">
                                            <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success w-100 mb-1">Konfirmasi Bayar</button>
                                    </form>
                                    <form action="{{ route('admin.billing.destroy', $billing->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tagihan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">Hapus</button>
                                    </form>
                                @else
                                    @if($billing->payment_proof_path)
                                    <a href="{{ Storage::url($billing->payment_proof_path) }}" target="_blank" class="btn btn-sm btn-info text-white">Lihat Bukti</a>
                                    @else
                                    <span class="text-muted">Tidak ada bukti file</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada tagihan untuk siswa ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Tambah Tagihan Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.billing.store', $student->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="description" class="form-label">Deskripsi Tagihan <span class="text-danger">*</span></label>
                        <input type="text" name="description" id="description" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="due_date" class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="due_date" id="due_date" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
                        <select name="academic_year_id" id="academic_year_id" class="form-select">
                            <option value="">-- Pilih (Opsional) --</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}">{{ $ay->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan Tambahan</label>
                    <textarea name="notes" id="notes" rows="2" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Tagihan</button>
            </form>
        </div>
    </div>
</div>
@endsection
