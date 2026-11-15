@extends('layouts.admin.app')

@section('title', 'Transaksi Laundry')

@section('content')
    @include('partials.page-header', [
        'judul' => 'Transaksi Laundry',
        'sub' => 'Daftar seluruh pesanan & status cucian.',
        'tombol' => ['url' => route('pelanggan.create'), 'teks' => 'Tambah Pesanan', 'kelas' => 'btn-success text-white'],
    ])

    <div class="card border-0 shadow mb-4">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('pelanggan.index') }}" class="row g-2">
                <div class="col-12 col-md-5">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Cari nama / nomor HP">
                </div>
                <div class="col-8 col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach (\App\Models\Pelanggan::STATUS as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 col-md-3 d-grid d-md-flex gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-gray-600">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0">#</th>
                        <th class="border-0">Pelanggan</th>
                        <th class="border-0">Kontak</th>
                        <th class="border-0">Layanan</th>
                        <th class="border-0">Kilo</th>
                        <th class="border-0">Total Harga</th>
                        <th class="border-0">Status</th>
                        <th class="border-0 rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataPelanggan as $p)
                        <tr>
                            <td>{{ $p->pelanggan_id }}</td>
                            <td>
                                <div class="fw-bold">{{ $p->nama }}</div>
                                <div class="small text-muted text-wrap" style="max-width:220px">{{ $p->alamat }}</div>
                                @if ($p->deskripsi_layanan)
                                    <div class="small fst-italic text-wrap" style="max-width:220px">"{{ $p->deskripsi_layanan }}"</div>
                                @endif
                            </td>
                            <td>{{ $p->nomor_hp }}</td>
                            <td>{{ ucfirst($p->jenis_laundry) }}<br><small class="text-muted">{{ $p->jenis_pengerjaan }}</small></td>
                            <td>{{ rtrim(rtrim($p->total_kilo, '0'), '.') }} kg</td>
                            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('pelanggan.status', $p) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status_cucian" class="form-select form-select-sm" style="min-width:140px"
                                        onchange="this.form.submit()" aria-label="Ubah status">
                                        @foreach (\App\Models\Pelanggan::STATUS as $s)
                                            <option value="{{ $s }}" @selected($p->status_cucian === $s)>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('pelanggan.edit', $p) }}" class="btn btn-info btn-sm">Edit</a>
                                <form action="{{ route('pelanggan.destroy', $p) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus pesanan {{ e($p->nama) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($dataPelanggan->hasPages())
            <div class="card-footer">{{ $dataPelanggan->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
