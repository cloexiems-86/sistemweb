<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Jadwal;

class AbsensiController extends Controller
{
    public function show($jadwal_id)
    {
        // Ambil data jadwal berdasarkan ID
        $jadwal = Jadwal::findOrFail($jadwal_id);
        
        // Ambil data absen yang jadwal_id nya cocok
        $presensi = Absensi::where('jadwal_id', $jadwal_id)->with('user')->get();

        // Sesuaikan dengan nama folder view kamu
        return view('jadwalbimbingan.presensi', compact('jadwal', 'presensi'));
    }
}