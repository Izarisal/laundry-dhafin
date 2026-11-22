<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
// FITUR LOGIN GOOGLE 
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

// Halaman awal -> dashboard (jika belum login otomatis dialihkan ke login)
Route::redirect('/', '/dashboard');

// Route latihan sebelumnya (tetap dipertahankan)
Route::get('/home', [HomeController::class, 'index']);
Route::post('question/store', [QuestionController::class, 'store'])->name('question.store');

/*
|--------------------------------------------------------------------------
| Autentikasi (tamu)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

/*
|--------------------------------------------------------------------------
| Wajib login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard: isi menyesuaikan role (admin = semua data, pelanggan = miliknya)
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Khusus PELANGGAN: buat pesanan & pantau status ---
    Route::middleware('role:pelanggan')->prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/', [PesananController::class, 'index'])->name('index');
        Route::get('buat', [PesananController::class, 'create'])->name('create');
        Route::post('/', [PesananController::class, 'store'])->name('store');
    });

    // --- Khusus ADMIN: CRUD penuh + ubah status + kelola user ---
    Route::middleware('role:admin')->group(function () {
        Route::resource('pelanggan', PelangganController::class)->except('show');
        Route::patch('pelanggan/{pelanggan}/status', [PelangganController::class, 'updateStatus'])
            ->name('pelanggan.status');

        Route::resource('user', UserController::class)->except('show');
    });
});


Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
