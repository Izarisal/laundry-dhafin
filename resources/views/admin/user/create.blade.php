@extends('layouts.admin.app')

@section('title', 'Tambah User')

@section('content')
    @include('partials.page-header', ['judul' => 'Tambah User', 'sub' => 'Buat akun baru.', 'tombol' => ['url' => route('user.index'), 'teks' => 'Kembali']])
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                @include('partials.form-user')
                <button type="submit" class="btn btn-success text-white">Simpan</button>
            </form>
        </div>
    </div>
@endsection
