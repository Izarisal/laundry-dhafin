@extends('layouts.admin.app')

@section('title', 'Buat Pesanan')

@section('content')
    @include('partials.page-header', [
        'judul' => 'Buat Pesanan Laundry',
        'sub' => 'Isi data pesanan. Status awal pesanan adalah "Diproses".',
        'tombol' => ['url' => route('pesanan.index'), 'teks' => 'Kembali'],
    ])

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('pesanan.store') }}" method="POST">
                @csrf
                @include('partials.form-pesanan', ['admin' => false])
                <button type="submit" class="btn btn-success text-white">Kirim Pesanan</button>
            </form>
        </div>
    </div>
@endsection
