{{-- Form bersama untuk create & edit. Variabel: $type, $transaction, $categories, $currentCategory, $rooms, $methods --}}
@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

<input type="hidden" name="type" value="{{ $type }}">

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="transaction_date" class="form-label">Tanggal Transaksi</label>
        <input type="date" name="transaction_date" id="transaction_date" class="form-control" max="{{ today()->toDateString() }}"
               value="{{ old('transaction_date', $transaction->transaction_date?->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="finance_category_id" class="form-label">Kategori</label>
        <select name="finance_category_id" id="finance_category_id" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            @if ($currentCategory && ! $categories->contains('id', $currentCategory->id))
                <option value="{{ $currentCategory->id }}" selected>{{ $currentCategory->name }} (nonaktif)</option>
            @endif
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('finance_category_id', $transaction->finance_category_id) === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Uraian Transaksi</label>
    <input type="text" name="description" id="description" class="form-control" maxlength="255"
           placeholder="{{ $type === 'income' ? 'Contoh: Dana BOS Tahap 2 Tahun 2026' : 'Contoh: Perbaikan 10 kursi siswa Kelas X IPA 1' }}"
           value="{{ old('description', $transaction->description) }}" required>
</div>

@if ($type === 'expense')
    {{-- RINCIAN BARANG (opsional) --}}
    <div class="card bg-light border-0 mb-3">
        <div class="card-body">
            <div class="fw-semibold mb-1"><i class="bi bi-tools me-1"></i> Rincian Barang / Perbaikan <span class="text-muted fw-normal small">(opsional)</span></div>
            <p class="small text-muted mb-3">Isi bila pengeluaran untuk membeli atau memperbaiki barang (meja, kursi, proyektor, dll). Jika jumlah dan harga satuan diisi, nominal dihitung otomatis.</p>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="item_name" class="form-label small mb-1">Nama Barang</label>
                    <input type="text" name="item_name" id="item_name" class="form-control" maxlength="150"
                           placeholder="Contoh: Kursi siswa"
                           value="{{ old('item_name', $transaction->item_name) }}">
                </div>
                <div class="col-md-6">
                    <label for="room_id" class="form-label small mb-1">Lokasi / Ruangan</label>
                    <select name="room_id" id="room_id" class="form-select">
                        <option value="">-- Tidak spesifik --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" @selected((string) old('room_id', $transaction->room_id) === (string) $room->id)>{{ $room->code }} - {{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="quantity" class="form-label small mb-1">Jumlah</label>
                    <input type="number" min="1" name="quantity" id="quantity" class="form-control"
                           value="{{ old('quantity', $transaction->quantity) }}">
                </div>
                <div class="col-md-8">
                    <label for="unit_price" class="form-label small mb-1">Harga Satuan (Rp)</label>
                    <input type="number" min="0" step="1" name="unit_price" id="unit_price" class="form-control"
                           value="{{ old('unit_price', $transaction->unit_price !== null ? (int) $transaction->unit_price : '') }}">
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="amount" class="form-label">Nominal (Rp)</label>
        <input type="number" min="1" step="1" name="amount" id="amount" class="form-control"
               value="{{ old('amount', $transaction->amount !== null ? (int) $transaction->amount : '') }}">
        <div class="form-text" id="amount-hint"></div>
    </div>
    <div class="col-md-6 mb-3">
        <label for="payment_method" class="form-label">Metode Pembayaran</label>
        <select name="payment_method" id="payment_method" class="form-select" required>
            @foreach ($methods as $value => $label)
                <option value="{{ $value }}" @selected(old('payment_method', $transaction->payment_method) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="party" class="form-label">{{ $type === 'income' ? 'Sumber Dana' : 'Dibayarkan Kepada (Toko / Vendor / Penerima)' }}</label>
        <input type="text" name="party" id="party" class="form-control" maxlength="150"
               value="{{ old('party', $transaction->party) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label for="reference_number" class="form-label">No. Nota / Kwitansi / Bukti Transfer</label>
        <input type="text" name="reference_number" id="reference_number" class="form-control" maxlength="100"
               value="{{ old('reference_number', $transaction->reference_number) }}">
    </div>
</div>

<div class="mb-3">
    <label for="notes" class="form-label">Catatan (opsional)</label>
    <textarea name="notes" id="notes" class="form-control" rows="2" maxlength="1000">{{ old('notes', $transaction->notes) }}</textarea>
</div>

<div class="mb-3">
    <label for="proof" class="form-label">Bukti Transaksi (nota / kwitansi / foto, PDF/JPG/PNG, maks 5 MB)</label>
    <input type="file" name="proof" id="proof" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
    @if ($transaction->proof_path)
        <div class="form-text">
            File saat ini: <strong>{{ $transaction->proof_original_name }}</strong>. Unggah file baru untuk mengganti.
        </div>
        <div class="form-check mt-1">
            <input type="checkbox" name="remove_proof" id="remove_proof" class="form-check-input" value="1">
            <label for="remove_proof" class="form-check-label small">Hapus file ini (tanpa mengganti)</label>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const qty = document.getElementById('quantity');
        const price = document.getElementById('unit_price');
        const amount = document.getElementById('amount');
        const hint = document.getElementById('amount-hint');
        if (!qty || !price || !amount) return;

        const rupiah = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');

        const recalc = () => {
            const q = parseFloat(qty.value);
            const p = parseFloat(price.value);
            if (q > 0 && !isNaN(p)) {
                amount.value = Math.round(q * p);
                amount.readOnly = true;
                hint.textContent = 'Dihitung otomatis: ' + q + ' x ' + rupiah(p) + ' = ' + rupiah(q * p);
            } else {
                amount.readOnly = false;
                hint.textContent = '';
            }
        };

        qty.addEventListener('input', recalc);
        price.addEventListener('input', recalc);
        recalc();
    });
</script>
@endpush
