@extends('layouts.admin.app')

@section('title', 'Edit User')

@section('content')
    @include('partials.page-header', ['judul' => 'Edit User', 'sub' => $dataUser->email, 'tombol' => ['url' => route('user.index'), 'teks' => 'Kembali']])
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('user.update', $dataUser) }}" method="POST">
                @csrf
                @method('PUT')
                @include('partials.form-user', ['u' => $dataUser, 'edit' => true])
                <button type="submit" class="btn btn-success text-white">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection
