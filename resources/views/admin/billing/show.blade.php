@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/billing/show.css') }}">

<div class="container-fluid billing-detail-page">

    {{-- =========================================
         HERO
    ========================================== --}}
    <section class="billing-hero">

        <div class="billing-hero-main">

            <div class="billing-hero-icon">
                <i class="bi bi-receipt-cutoff"></i>
            </div>

            <div class="billing-hero-content">

                <div class="billing-hero-label">
                    KELOLA TAGIHAN SISWA
                </div>

                <h1>Detail Tagihan</h1>

                <p>
                    Kelola tagihan, pembayaran, dan riwayat transaksi siswa
                    dengan lebih mudah.
                </p>

            </div>

        </div>


        <div class="billing-hero-student">

            <div class="billing-student-icon">
                <i class="bi bi-person-fill"></i>
            </div>

            <div>

                <span>SISWA</span>

                <strong>
                    {{ $student->name }}
                </strong>

            </div>

        </div>

    </section>


    {{-- =========================================
         RIWAYAT TAGIHAN
    ========================================== --}}
    <section class="billing-section-card">

        <div class="billing-section-header">

            <div class="billing-section-title">

                <div class="billing-section-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>

                    <span class="billing-section-label">
                        RIWAYAT
                    </span>

                    <h2>
                        Riwayat Tagihan
                    </h2>

                    <p>
                        Daftar seluruh tagihan siswa.
                    </p>

                </div>

            </div>

        </div>


        <div class="billing-table-container">

            <div class="table-responsive">

                <table class="table billing-table align-middle">

                    <thead>

                        <tr>

                            <th>
                                Deskripsi
                            </th>

                            <th>
                                Jatuh Tempo
                            </th>

                            <th>
                                Nominal
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($billings as $billing)

                        <tr>

                            {{-- DESKRIPSI --}}
                            <td>

                                <div class="billing-description">

                                    <strong>
                                        {{ $billing->description }}
                                    </strong>

                                    @if($billing->notes)

                                        <span>
                                            {{ $billing->notes }}
                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- JATUH TEMPO --}}
                            <td>

                                <div class="billing-date">

                                    <i class="bi bi-calendar3"></i>

                                    <span>
                                        {{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('d/m/Y') : '-' }}
                                    </span>

                                </div>

                            </td>


                            {{-- NOMINAL --}}
                            <td>

                                <span class="billing-amount">
                                    Rp {{ number_format($billing->amount, 0, ',', '.') }}
                                </span>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if($billing->status === 'paid')

                                    <div class="billing-status-area">

                                        <span class="billing-status paid">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Lunas

                                        </span>

                                        <small>
                                            {{ \Carbon\Carbon::parse($billing->paid_at)->format('d/m/Y H:i') }}
                                        </small>

                                    </div>

                                @else

                                    <span class="billing-status unpaid">

                                        <i class="bi bi-exclamation-circle-fill"></i>

                                        Belum Lunas

                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td>

                                @if($billing->status === 'unpaid')

                                    <div class="billing-actions">

                                        {{-- KONFIRMASI PEMBAYARAN --}}
                                        <form
                                            action="{{ route('admin.billing.confirm', $billing->id) }}"
                                            method="POST"
                                            enctype="multipart/form-data"
                                        >

                                            @csrf

                                            <div class="billing-file">

                                                <i class="bi bi-paperclip"></i>

                                                <input
                                                    type="file"
                                                    name="payment_proof"
                                                    class="form-control"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                >

                                            </div>


                                            <button
                                                type="submit"
                                                class="billing-confirm-btn"
                                            >

                                                <i class="bi bi-check-lg"></i>

                                                Konfirmasi Bayar

                                            </button>

                                        </form>


                                        {{-- HAPUS TAGIHAN --}}
                                        <form
                                            action="{{ route('admin.billing.destroy', $billing->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus tagihan ini?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="billing-delete-btn"
                                            >

                                                <i class="bi bi-trash3"></i>

                                                Hapus

                                            </button>

                                        </form>

                                    </div>

                                @else

                                    @if($billing->payment_proof_path)

                                        <a
                                            href="{{ Storage::url($billing->payment_proof_path) }}"
                                            target="_blank"
                                            class="billing-proof-btn"
                                        >

                                            <i class="bi bi-file-earmark-check"></i>

                                            Lihat Bukti

                                        </a>

                                    @else

                                        <span class="billing-no-proof">
                                            Tidak ada bukti file
                                        </span>

                                    @endif

                                @endif

                            </td>

                        </tr>


                        @empty

                        {{-- EMPTY STATE --}}
                        <tr>

                            <td colspan="5">

                                <div class="billing-empty">

                                    <div class="billing-empty-icon">

                                        <i class="bi bi-receipt"></i>

                                    </div>

                                    <div>

                                        <strong>
                                            Belum ada tagihan
                                        </strong>

                                        <span>
                                            Belum ada tagihan untuk siswa ini.
                                        </span>

                                    </div>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>


    {{-- =========================================
         TAMBAH TAGIHAN BARU
    ========================================== --}}
    <section class="billing-section-card billing-create-card">

        <div class="billing-section-header billing-create-header">

            <div class="billing-section-title">

                <div class="billing-section-icon">

                    <i class="bi bi-plus-lg"></i>

                </div>

                <div>

                    <span class="billing-section-label">
                        TAGIHAN BARU
                    </span>

                    <h2>
                        Tambah Tagihan Baru
                    </h2>

                    <p>
                        Tambahkan tagihan baru untuk siswa ini.
                    </p>

                </div>

            </div>

        </div>


        <div class="billing-form-container">

            <form
                action="{{ route('admin.billing.store', $student->id) }}"
                method="POST"
            >

                @csrf


                {{-- FORM GRID --}}
                <div class="billing-form-grid">

                    {{-- DESKRIPSI --}}
                    <div class="billing-field">

                        <label for="description">

                            Deskripsi Tagihan

                            <span>*</span>

                        </label>

                        <input
                            type="text"
                            name="description"
                            id="description"
                            class="form-control"
                            placeholder="Contoh: SPP Bulan Oktober"
                            required
                        >

                    </div>


                    {{-- NOMINAL --}}
                    <div class="billing-field">

                        <label for="amount">

                            Nominal (Rp)

                            <span>*</span>

                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            class="form-control"
                            min="0"
                            placeholder="Contoh: 500000"
                            required
                        >

                    </div>


                    {{-- JATUH TEMPO --}}
                    <div class="billing-field">

                        <label for="due_date">
                            Tanggal Jatuh Tempo
                        </label>

                        <input
                            type="date"
                            name="due_date"
                            id="due_date"
                            class="form-control"
                        >

                    </div>


                    {{-- TAHUN AJARAN --}}
                    <div class="billing-field">

                        <label for="academic_year_id">
                            Tahun Ajaran
                        </label>

                        <select
                            name="academic_year_id"
                            id="academic_year_id"
                            class="form-select"
                        >

                            <option value="">
                                -- Pilih (Opsional) --
                            </option>

                            @foreach($academicYears as $ay)

                                <option value="{{ $ay->id }}">
                                    {{ $ay->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- CATATAN --}}
                <div class="billing-field billing-notes">

                    <label for="notes">
                        Catatan Tambahan
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        rows="3"
                        class="form-control"
                        placeholder="Tambahkan catatan tambahan jika diperlukan..."
                    ></textarea>

                </div>


                {{-- FOOTER --}}
                <div class="billing-form-footer">

                    <div class="billing-form-info">

                        <i class="bi bi-info-circle"></i>

                        <span>
                            Kolom bertanda
                            <strong>*</strong>
                            wajib diisi.
                        </span>

                    </div>


                    <div class="billing-form-actions">

                        {{-- KEMBALI --}}
                        <a
                            href="{{ route('admin.billing.index') }}"
                            class="billing-footer-back-btn"
                        >

                            <i class="bi bi-arrow-left"></i>

                            Kembali

                        </a>


                        {{-- SIMPAN --}}
                        <button
                            type="submit"
                            class="billing-save-btn"
                        >

                            <i class="bi bi-check2"></i>

                            Simpan Tagihan

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </section>

</div>

@endsection