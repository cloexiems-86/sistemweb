<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. Kredensial untuk kolom username
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // 3. Proses Cek ke Database
        if (Auth::attempt($credentials)) {
            // Cek apakah role-nya benar admin
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['username' => 'Akses ditolak!']);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // 4. Jika gagal
        return back()->withErrors([
            'username' => 'USERNAME ATAU PASSWORD SALAH.',
        ])->withInput($request->only('username'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();

        return back()->with('success', 'Profil admin berhasil diperbarui!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Mengarahkan ke '/' (Landing Page) dengan membawa pesan sukses
        return redirect('/')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    // public function logout(Request $request)
    // {
    //     Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect('/')->with('success', 'Anda telah berhasil keluar dari sistem.');
    // }
}