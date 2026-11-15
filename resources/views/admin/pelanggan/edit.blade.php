@extends('layouts.admin.app')

@section('title', 'Edit Pesanan')

@section('content')
    @include('partials.page-header', [
        'judul' => 'Edit Pesanan #' . $pesanan->pelanggan_id,
        'sub' => 'Ubah data pesanan. Total harga dihitung ulang otomatis.',
        'tombol' => ['url' => route('pelanggan.index'), 'teks' => 'Kembali'],
    ])

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('pelanggan.update', $pesanan) }}" method="POST">
                @csrf
                @method('PUT')
                @include('partials.form-pesanan', ['admin' => true])
                <button type="submit" class="btn btn-success text-white">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection
