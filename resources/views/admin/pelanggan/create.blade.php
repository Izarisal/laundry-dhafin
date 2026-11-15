@extends('layouts.admin.app')

@section('title', 'Tambah Pesanan')

@section('content')
    @include('partials.page-header', [
        'judul' => 'Tambah Pesanan',
        'sub' => 'Catat transaksi laundry baru.',
        'tombol' => ['url' => route('pelanggan.index'), 'teks' => 'Kembali'],
    ])

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('pelanggan.store') }}" method="POST">
                @csrf
                @include('partials.form-pesanan', ['admin' => true])
                <button type="submit" class="btn btn-success text-white">Simpan</button>
            </form>
        </div>
    </div>
@endsection
