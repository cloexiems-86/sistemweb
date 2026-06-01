<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\User; // Tambahkan ini untuk mengambil data user di form manual

class AbsensiController extends Controller
{
    public function show($jadwal_id)
    {
        // Ambil data jadwal berdasarkan ID
        $jadwal = Jadwal::findOrFail($jadwal_id);
        
        // Ambil data absen yang jadwal_id nya cocok
        $presensi = Absensi::where('jadwal_id', $jadwal_id)->with('user')->get();

        // Ambil semua user untuk pilihan dropdown absen manual oleh Admin
        $users = User::all();

        // Sesuaikan dengan nama folder view kamu
        return view('jadwalbimbingan.presensi', compact('jadwal', 'presensi', 'users'));
    }

    // Fungsi untuk Absen Manual via Web (Penyelamat saat sidang)
    public function storeManual(Request $request, $jadwal_id)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        // Cek apakah user sudah absen di jadwal ini
        $sudahAbsen = Absensi::where('jadwal_id', $jadwal_id)
                             ->where('user_id', $request->user_id)
                             ->first();

        if ($sudahAbsen) {
            return back()->with('error', 'Peserta ini sudah melakukan absensi!');
        }

        // Simpan absen manual
        Absensi::create([
            'jadwal_id' => $jadwal_id,
            'user_id' => $request->user_id,
            'status' => 'hadir'
        ]);

        return back()->with('success', 'Absensi manual berhasil ditambahkan!');
    }

    // Fungsi untuk membatalkan absen (jika admin salah klik)
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return back()->with('success', 'Data absensi berhasil dibatalkan!');
    }
}