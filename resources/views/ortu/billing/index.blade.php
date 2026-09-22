@extends('layouts.app')

@section('content')
<div class="container">
    @include('layouts.partials.announcements-banner')

    <h2>Info Tagihan: {{ $student->name }}</h2>

    <div class="row mt-4 mb-4">
        <div class="col-md-6">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Belum Lunas</h5>
                    <h3 class="mb-0">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Telah Dibayar</h5>
                    <h3 class="mb-0">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Rincian Tagihan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Deskripsi</th>
                            <th>Jatuh Tempo</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Tanggal Lunas</th>
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
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-danger">Belum Lunas</span>
                                @endif
                            </td>
                            <td>{{ $billing->paid_at ? \Carbon\Carbon::parse($billing->paid_at)->format('d/m/Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data tagihan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
