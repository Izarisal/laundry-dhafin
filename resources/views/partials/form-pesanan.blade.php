{{--
  Field form pesanan, dipakai pelanggan (pesanan.create) dan admin (create/edit).
  Variabel: $pesanan (opsional, untuk edit), $admin (bool), $dataUser (admin saja)
--}}
@php
    $pesanan = $pesanan ?? null;
    $admin   = $admin ?? false;
    $val     = fn ($k, $default = null) => old($k, $pesanan->{$k} ?? $default);
@endphp

<div class="row">
    @if ($admin)
        <div class="col-md-6 mb-3">
            <label for="user_id" class="form-label">Akun Pelanggan <small class="text-muted">(opsional)</small></label>
            <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                <option value="">- Walk-in (tanpa akun) -</option>
                @foreach ($dataUser as $u)
                    <option value="{{ $u->id }}" @selected($val('user_id') == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
                @endforeach
            </select>
            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="status_cucian" class="form-label">Status Cucian</label>
            <select id="status_cucian" name="status_cucian" class="form-select @error('status_cucian') is-invalid @enderror" required>
                @foreach (\App\Models\Pelanggan::STATUS as $s)
                    <option value="{{ $s }}" @selected($val('status_cucian', 'Diproses') === $s)>{{ $s }}</option>
                @endforeach
            </select>
            @error('status_cucian')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endif

    <div class="col-md-6 mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" id="nama" name="nama" maxlength="100" required
            class="form-control @error('nama') is-invalid @enderror"
            value="{{ $val('nama', $admin ? '' : auth()->user()->name) }}">
        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="nomor_hp" class="form-label">Nomor HP</label>
        <input type="tel" id="nomor_hp" name="nomor_hp" maxlength="20" required placeholder="08xxxxxxxxxx"
            class="form-control @error('nomor_hp') is-invalid @enderror" value="{{ $val('nomor_hp') }}">
        @error('nomor_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea id="alamat" name="alamat" rows="2" maxlength="500" required
            class="form-control @error('alamat') is-invalid @enderror">{{ $val('alamat') }}</textarea>
        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="jenis_laundry" class="form-label">Jenis Laundry</label>
        <select id="jenis_laundry" name="jenis_laundry" class="form-select @error('jenis_laundry') is-invalid @enderror" required>
            <option value="">- Pilih -</option>
            @foreach (\App\Models\Pelanggan::JENIS_LAUNDRY as $j)
                <option value="{{ $j }}" @selected($val('jenis_laundry') === $j)>{{ ucfirst($j) }}</option>
            @endforeach
        </select>
        @error('jenis_laundry')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="jenis_pengerjaan" class="form-label">Jenis Pengerjaan</label>
        <select id="jenis_pengerjaan" name="jenis_pengerjaan" class="form-select @error('jenis_pengerjaan') is-invalid @enderror" required>
            <option value="">- Pilih -</option>
            @foreach (\App\Models\Pelanggan::JENIS_PENGERJAAN as $j)
                <option value="{{ $j }}" @selected($val('jenis_pengerjaan') === $j)>{{ ucfirst($j) }}</option>
            @endforeach
        </select>
        @error('jenis_pengerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="total_kilo" class="form-label">Total Kilo</label>
        <div class="input-group has-validation">
            <input type="number" id="total_kilo" name="total_kilo" step="0.1" min="0.1" max="999" required
                class="form-control @error('total_kilo') is-invalid @enderror" value="{{ $val('total_kilo') }}">
            <span class="input-group-text">kg</span>
            @error('total_kilo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-12 mb-3">
        <label for="deskripsi_layanan" class="form-label">Deskripsi Layanan <small class="text-muted">(opsional)</small></label>
        <textarea id="deskripsi_layanan" name="deskripsi_layanan" rows="2" maxlength="1000"
            placeholder="Catatan ekstra, mis. pisahkan baju putih"
            class="form-control @error('deskripsi_layanan') is-invalid @enderror">{{ $val('deskripsi_layanan') }}</textarea>
        @error('deskripsi_layanan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 mb-4">
        <div class="alert alert-info mb-0">
            Estimasi total harga: <strong id="estimasi-harga">Rp 0</strong>
            <div class="small">Harga final dihitung otomatis oleh sistem berdasarkan tarif standar.</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Estimasi di sisi tampilan saja; harga final tetap dihitung backend (Pelanggan::hitungTotalHarga).
    (function () {
        const tarif = @json(\App\Models\Pelanggan::TARIF);
        const el = {
            laundry: document.getElementById('jenis_laundry'),
            kerja: document.getElementById('jenis_pengerjaan'),
            kilo: document.getElementById('total_kilo'),
            out: document.getElementById('estimasi-harga'),
        };
        function hitung() {
            const t = (tarif[el.laundry.value] || {})[el.kerja.value] || 0;
            const total = Math.round(t * (parseFloat(el.kilo.value) || 0));
            el.out.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
        ['change', 'input'].forEach(e => [el.laundry, el.kerja, el.kilo].forEach(x => x.addEventListener(e, hitung)));
        hitung();
    })();
</script>
@endpush
