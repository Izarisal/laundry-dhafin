@extends('layouts.auth')

@section('title', 'Login')
@section('heading', 'Masuk ke Akun Anda')

@section('content')
    <form action="{{ route('login') }}" method="POST" novalidate>
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" autofocus required
                class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" required
                class="form-control @error('password') is-invalid @enderror" placeholder="Password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label mb-0" for="remember">Ingat saya</label>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-gray-800">Login</button>
        </div>
    </form>
    <div class="d-grid pt-2">
        <a href="{{ route('google.login') }}" class="btn btn-danger">
            Login dengan Google
        </a>
    </div>
    <div class="text-center mt-4">
        <span class="fw-normal">Belum punya akun? <a href="{{ route('register') }}" class="fw-bold">Daftar</a></span>
    </div>
@endsection
