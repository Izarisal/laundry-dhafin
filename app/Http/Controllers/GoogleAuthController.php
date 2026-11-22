<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class GoogleAuthController extends Controller
{
    // Mengarahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menerima data balikan dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(str::random(16)), 
                    'role' => User::ROLE_PELANGGAN ?? 'pelanggan', // Menyesuaikan dengan konstanta role di model User kamu
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            // Jika ingin melihat pesan error aslinya sementara waktu, ubah return redirect ini menjadi:
            dd($e->getMessage());
            return redirect('/login')->with('error', 'Gagal login menggunakan Google: ' . $e->getMessage());
        }
    }
}
