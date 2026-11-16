<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
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

            // Cek apakah user dengan email atau google_id ini sudah ada di database
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                // Jika user sudah ada (misal dari dummy account yang emailnya sama), update google_id-nya
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            } else {
                // Jika belum ada sama sekali, otomatis buatkan akun baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    // Password dikosongkan karena login via Google
                ]);
            }

            // Login-kan user ke dalam sistem session Laravel kamu
            Auth::login($user);

            // Arahkan ke halaman dashboard/home milikmu
            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Gagal login menggunakan Google: ' . $e->getMessage());
        }
    }
}
