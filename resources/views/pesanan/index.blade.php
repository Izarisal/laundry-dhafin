@extends('layouts.admin.app')

@section('title', 'Pesanan Saya')

@section('content')
    @include('partials.page-header', [
        'judul' => 'Pesanan Saya',
        'sub' => 'Pantau status cucian Anda.',
        'tombol' => ['url' => route('pesanan.create'), 'teks' => 'Buat Pesanan Baru', 'kelas' => 'btn-success text-white'],
    ])

    <div class="card border-0 shadow mb-4">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0">Tanggal</th>
                        <th class="border-0">Jenis Laundry</th>
                        <th class="border-0">Pengerjaan</th>
                        <th class="border-0">Kilo</th>
                        <th class="border-0">Total Harga</th>
                        <th class="border-0">Catatan</th>
                        <th class="border-0">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataPesanan as $p)
                        <tr>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ ucfirst($p->jenis_laundry) }}</td>
                            <td>{{ ucfirst($p->jenis_pengerjaan) }}</td>
                            <td>{{ rtrim(rtrim($p->total_kilo, '0'), '.') }} kg</td>
                            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td class="text-wrap" style="max-width:240px">{{ $p->deskripsi_layanan ?: '-' }}</td>
                            <td>@include('partials.badge-status', ['status' => $p->status_cucian])</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Anda belum memiliki pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($dataPesanan->hasPages())
            <div class="card-footer">{{ $dataPesanan->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
