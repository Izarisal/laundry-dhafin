@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    @include('partials.page-header', [
        'judul' => 'Dashboard',
        'sub' => auth()->user()->isAdmin() ? 'Ringkasan seluruh transaksi laundry' : 'Ringkasan pesanan Anda',
        'tombol' => auth()->user()->isAdmin()
            ? ['url' => route('pelanggan.create'), 'teks' => 'Tambah Pesanan', 'kelas' => 'btn-success text-white']
            : ['url' => route('pesanan.create'), 'teks' => 'Buat Pesanan Baru', 'kelas' => 'btn-success text-white'],
    ])

    <div class="row">
        @foreach ([
            ['Total Pesanan', $jumlah['total'], 'primary'],
            ['Diproses', $jumlah['diproses'], 'warning'],
            ['Selesai', $jumlah['selesai'], 'success'],
            ['Sudah Diambil', $jumlah['diambil'], 'secondary'],
        ] as [$label, $angka, $warna])
            <div class="col-6 col-xl-3 mb-4">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="text-gray-500 small">{{ $label }}</div>
                        <div class="h3 mb-0 text-{{ $warna }}">{{ $angka }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h2 class="fs-5 fw-bold mb-0">Pesanan Terbaru</h2>
                    <span class="text-muted">Total nilai transaksi: <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">Tanggal</th>
                                <th class="border-0">Nama</th>
                                <th class="border-0">Layanan</th>
                                <th class="border-0">Kilo</th>
                                <th class="border-0">Total</th>
                                <th class="border-0">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($terbaru as $p)
                                <tr>
                                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $p->nama }}</td>
                                    <td>{{ ucfirst($p->jenis_laundry) }} - {{ $p->jenis_pengerjaan }}</td>
                                    <td>{{ rtrim(rtrim($p->total_kilo, '0'), '.') }} kg</td>
                                    <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                    <td>@include('partials.badge-status', ['status' => $p->status_cucian])</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
