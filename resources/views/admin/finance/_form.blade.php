{{-- Form bersama untuk create & edit transaksi keuangan --}}
{{-- Variabel: $type, $transaction, $categories, $currentCategory, $rooms, $methods --}}

<link rel="stylesheet" href="{{ asset('css/admin/finance/form.css') }}">

@if ($errors->any())
    <div class="finance-form-alert" role="alert">
        <div class="finance-form-alert__icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
            <div class="finance-form-alert__title">Periksa kembali data transaksi</div>
            <ul class="finance-form-alert__list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<input type="hidden" name="type" value="{{ $type }}">

<div class="finance-form">

    {{-- INFORMASI UTAMA --}}
    <section class="finance-form-section">
        <div class="finance-form-section__heading">
            <div class="finance-form-section__icon">
                <i class="bi bi-receipt"></i>
            </div>
            <div>
                <h6>Informasi Transaksi</h6>
                <p>Masukkan tanggal, kategori, dan uraian transaksi.</p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="transaction_date" class="finance-form-label">
                    Tanggal Transaksi <span class="required-mark">*</span>
                </label>
                <input
                    type="date"
                    name="transaction_date"
                    id="transaction_date"
                    class="form-control finance-form-control"
                    max="{{ today()->toDateString() }}"
                    value="{{ old('transaction_date', $transaction->transaction_date?->format('Y-m-d')) }}"
                    required
                >
            </div>

            <div class="col-md-6">
                <label for="finance_category_id" class="finance-form-label">
                    Kategori <span class="required-mark">*</span>
                </label>
                <select
                    name="finance_category_id"
                    id="finance_category_id"
                    class="form-select finance-form-control"
                    required
                >
                    <option value="">Pilih kategori transaksi</option>

                    @if ($currentCategory && ! $categories->contains('id', $currentCategory->id))
                        <option
                            value="{{ $currentCategory->id }}"
                            selected
                        >
                            {{ $currentCategory->name }} (nonaktif)
                        </option>
                    @endif

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected((string) old('finance_category_id', $transaction->finance_category_id) === (string) $category->id)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label for="description" class="finance-form-label">
                    Uraian Transaksi <span class="required-mark">*</span>
                </label>
                <input
                    type="text"
                    name="description"
                    id="description"
                    class="form-control finance-form-control"
                    maxlength="255"
                    placeholder="{{ $type === 'income' ? 'Contoh: Dana BOS Tahap 2 Tahun 2026' : 'Contoh: Perbaikan 10 kursi siswa Kelas X IPA 1' }}"
                    value="{{ old('description', $transaction->description) }}"
                    required
                >
            </div>
        </div>
    </section>

    {{-- RINCIAN BARANG --}}
    @if ($type === 'expense')
        <section class="finance-form-section finance-form-section--items">
            <div class="finance-form-section__heading">
                <div class="finance-form-section__icon finance-form-section__icon--items">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <h6>Rincian Barang / Perbaikan</h6>
                    <p>Opsional, untuk pembelian barang atau biaya perbaikan.</p>
                </div>
                <span class="finance-form-badge">Opsional</span>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="item_name" class="finance-form-label">Nama Barang</label>
                    <input
                        type="text"
                        name="item_name"
                        id="item_name"
                        class="form-control finance-form-control"
                        maxlength="150"
                        placeholder="Contoh: Kursi siswa"
                        value="{{ old('item_name', $transaction->item_name) }}"
                    >
                </div>

                <div class="col-md-6">
                    <label for="room_id" class="finance-form-label">Lokasi / Ruangan</label>
                    <select name="room_id" id="room_id" class="form-select finance-form-control">
                        <option value="">Tidak ada lokasi khusus</option>
                        @foreach ($rooms as $room)
                            <option
                                value="{{ $room->id }}"
                                @selected((string) old('room_id', $transaction->room_id) === (string) $room->id)
                            >
                                {{ $room->code }} - {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="quantity" class="finance-form-label">Jumlah</label>
                    <input
                        type="number"
                        min="1"
                        name="quantity"
                        id="quantity"
                        class="form-control finance-form-control"
                        placeholder="Mis. 10"
                        value="{{ old('quantity', $transaction->quantity) }}"
                    >
                </div>

                <div class="col-md-8">
                    <label for="unit_price" class="finance-form-label">Harga Satuan (Rp)</label>
                    <div class="finance-form-input-group">
                        <span>Rp</span>
                        <input
                            type="number"
                            min="0"
                            step="1"
                            name="unit_price"
                            id="unit_price"
                            class="form-control finance-form-control"
                            placeholder="Masukkan harga per unit"
                            value="{{ old('unit_price', $transaction->unit_price !== null ? (int) $transaction->unit_price : '') }}"
                        >
                    </div>
                </div>
            </div>

            <div class="finance-form-info">
                <i class="bi bi-info-circle"></i>
                <span>Isi jumlah dan harga satuan untuk menghitung nominal secara otomatis.</span>
            </div>
        </section>
    @endif

    {{-- NOMINAL DAN PEMBAYARAN --}}
    <section class="finance-form-section">
        <div class="finance-form-section__heading">
            <div class="finance-form-section__icon finance-form-section__icon--amount">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <h6>Nominal & Pembayaran</h6>
                <p>Tentukan jumlah transaksi dan metode pembayaran.</p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="amount" class="finance-form-label">
                    Nominal Transaksi (Rp) <span class="required-mark">*</span>
                </label>
                <div class="finance-form-input-group">
                    <span>Rp</span>
                    <input
                        type="number"
                        min="1"
                        step="1"
                        name="amount"
                        id="amount"
                        class="form-control finance-form-control"
                        placeholder="Masukkan nominal"
                        value="{{ old('amount', $transaction->amount !== null ? (int) $transaction->amount : '') }}"
                        required
                    >
                </div>
                <div class="finance-form-hint" id="amount-hint" aria-live="polite"></div>
            </div>

            <div class="col-md-6">
                <label for="payment_method" class="finance-form-label">
                    Metode Pembayaran <span class="required-mark">*</span>
                </label>
                <select
                    name="payment_method"
                    id="payment_method"
                    class="form-select finance-form-control"
                    required
                >
                    @foreach ($methods as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(old('payment_method', $transaction->payment_method) === $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="party" class="finance-form-label">
                    {{ $type === 'income' ? 'Sumber Dana' : 'Penerima Pembayaran' }}
                </label>
                <input
                    type="text"
                    name="party"
                    id="party"
                    class="form-control finance-form-control"
                    maxlength="150"
                    placeholder="{{ $type === 'income' ? 'Nama sumber dana' : 'Nama toko, vendor, atau penerima' }}"
                    value="{{ old('party', $transaction->party) }}"
                >
            </div>

            <div class="col-md-6">
                <label for="reference_number" class="finance-form-label">
                    Nomor Nota / Kwitansi
                </label>
                <input
                    type="text"
                    name="reference_number"
                    id="reference_number"
                    class="form-control finance-form-control"
                    maxlength="100"
                    placeholder="Nomor bukti transaksi"
                    value="{{ old('reference_number', $transaction->reference_number) }}"
                >
            </div>
        </div>
    </section>

    {{-- CATATAN --}}
    <section class="finance-form-section">
        <div class="finance-form-section__heading">
            <div class="finance-form-section__icon finance-form-section__icon--notes">
                <i class="bi bi-card-text"></i>
            </div>
            <div>
                <h6>Catatan Tambahan</h6>
                <p>Tambahkan keterangan jika diperlukan.</p>
            </div>
        </div>

        <label for="notes" class="finance-form-label">Catatan (opsional)</label>
        <textarea
            name="notes"
            id="notes"
            class="form-control finance-form-control"
            rows="3"
            maxlength="1000"
            placeholder="Tuliskan informasi tambahan mengenai transaksi..."
        >{{ old('notes', $transaction->notes) }}</textarea>
    </section>

    {{-- BUKTI TRANSAKSI --}}
    <section class="finance-form-section">
        <div class="finance-form-section__heading">
            <div class="finance-form-section__icon finance-form-section__icon--proof">
                <i class="bi bi-paperclip"></i>
            </div>
            <div>
                <h6>Bukti Transaksi</h6>
                <p>Lampirkan dokumen pendukung transaksi.</p>
            </div>
        </div>

        <label for="proof" class="finance-form-label">Unggah Nota / Kwitansi / Foto</label>
        <input
            type="file"
            name="proof"
            id="proof"
            class="form-control finance-form-control finance-form-file"
            accept=".pdf,.jpg,.jpeg,.png"
        >
        <div class="finance-form-hint">
            Format PDF, JPG, JPEG, atau PNG. Ukuran maksimal 5 MB.
        </div>

        @if ($transaction->proof_path)
            <div class="finance-form-current-file">
                <div class="finance-form-current-file__icon">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <div class="finance-form-current-file__details">
                    <span class="finance-form-current-file__label">Bukti transaksi saat ini</span>
                    <strong>{{ $transaction->proof_original_name }}</strong>
                    <span class="finance-form-current-file__hint">Unggah file baru untuk menggantinya.</span>
                </div>
            </div>

            <label class="finance-form-remove-file" for="remove_proof">
                <input
                    type="checkbox"
                    name="remove_proof"
                    id="remove_proof"
                    class="form-check-input"
                    value="1"
                >
                <span>Hapus file bukti transaksi saat ini</span>
            </label>
        @endif
    </section>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const qty = document.getElementById('quantity');
        const price = document.getElementById('unit_price');
        const amount = document.getElementById('amount');
        const hint = document.getElementById('amount-hint');

        if (!qty || !price || !amount || !hint) return;

        const rupiah = (number) => 'Rp ' + Math.round(number).toLocaleString('id-ID');

        const recalc = () => {
            const q = Number(qty.value);
            const p = Number(price.value);
            const hasQuantity = qty.value.trim() !== '' && q > 0;
            const hasPrice = price.value.trim() !== '' && p >= 0;

            if (hasQuantity && hasPrice) {
                amount.value = Math.round(q * p);
                amount.readOnly = true;
                amount.classList.add('finance-form-control--readonly');
                hint.textContent = `Dihitung otomatis: ${q} × ${rupiah(p)} = ${rupiah(q * p)}`;
            } else {
                amount.readOnly = false;
                amount.classList.remove('finance-form-control--readonly');
                hint.textContent = '';
            }
        };

        qty.addEventListener('input', recalc);
        price.addEventListener('input', recalc);
        recalc();
    });
</script>
@endpush