<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatinController extends Controller
{
    // Fungsi untuk menampilkan halaman utama tabel Catin (CRUD)
    public function index()
    {
        // Pastikan Anda sudah membuat file ini di resources/views/auth/data-catin.blade.php
        return view('auth.data-catin');
    }

    // TAMBAHKAN FUNGSI INI
    public function create()
    {
        // Ganti 'auth.tambah-catin' dengan nama file blade form yang saya buatkan di awal
        return view('auth.tambah-catin'); 
    }

    // Fungsi untuk menampilkan detail verifikasi (seperti gambar Ahmad Fauzi)
    public function show($id)
    {
        return view('auth.catin-detail');
    }

    
}