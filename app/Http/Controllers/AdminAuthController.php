<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        // Nantinya di sini ada proses cek database.
        // Untuk sekarang, kita langsung arahkan ke dashboard admin.
        return redirect()->route('admin.dashboard');
    }
}