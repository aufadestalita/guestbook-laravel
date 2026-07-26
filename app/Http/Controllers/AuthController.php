<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function login()
    {
        return view('login');
    }

    // Memproses data email & password yang diinput
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Jika email dan password cocok di database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard'); // Arahkan ke dashboard
        }

        // Jika salah, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah',
        ])->onlyInput('email');
    }

    // Memproses fitur keluar / logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}