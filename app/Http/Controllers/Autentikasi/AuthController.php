<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input dari form
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Coba autentikasi user
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Jika login sukses, arahkan ke dashboard
            return redirect()->intended('/dashboard')
                             ->with('success', 'Login berhasil, selamat datang '.Auth::user()->name);
        }

        // Jika login gagal, kembali ke form login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Setelah logout kembali ke halaman login
        return redirect('/login')->with('success', 'Anda sudah logout.');
    }
}
