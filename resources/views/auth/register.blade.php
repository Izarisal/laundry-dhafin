@extends('layouts.auth')

@section('title', 'Registrasi')
@section('heading', 'Buat Akun Pelanggan')

@section('content')
    <form action="{{ route('register') }}" method="POST" novalidate>
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                class="form-control @error('name') is-invalid @enderror">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                class="form-control @error('email') is-invalid @enderror">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" required
                class="form-control @error('password') is-invalid @enderror">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">Minimal 6 karakter.</div>
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control">
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-gray-800">Daftar</button>
        </div>
    </form>
    <div class="text-center mt-4">
        <span class="fw-normal">Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold">Login</a></span>
    </div>
@endsection
