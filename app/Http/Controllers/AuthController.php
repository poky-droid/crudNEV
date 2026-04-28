<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Anggota;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'      => 'required|string',
            'password' => 'required|string',
        ]);

        $anggota = Anggota::where('email', $request->email)->first();
        if (!$anggota || !\Hash::check($request->password, $anggota->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        Auth::guard('anggota')->login($anggota);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('anggota')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
