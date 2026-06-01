<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Jadwal;
use Carbon\Carbon;

class AbsensiApiController extends Controller
{
    public function submitAbsen(Request $request)
    {
        $request->validate([
            'user_id'   => 'required',
            'jadwal_id' => 'required',
            'peserta'   => 'required|in:suami,istri',
        ]);

        $jadwal = Jadwal::find($request->jadwal_id);

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal tidak ditemukan.'
            ], 404);
        }

        $statusJadwal = strtolower($jadwal->status ?? '');

        if (
            $statusJadwal === 'completed' ||
            $statusJadwal === 'selesai' ||
            $statusJadwal === 'batal'
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi bimbingan ini sudah ditutup.'
            ], 403);
        }

        $batasWaktuAkhir = Carbon::parse($jadwal->tanggal)->endOfDay();

        if (now()->greaterThan($batasWaktuAkhir)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Waktu absensi sudah terlewat.'
            ], 403);
        }

        $sudahAbsen = Absensi::where('user_id', $request->user_id)
            ->where('jadwal_id', $request->jadwal_id)
            ->where('peserta', $request->peserta)
            ->first();

        if ($sudahAbsen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah melakukan absensi.'
            ], 400);
        }

        try {
            $absenBaru = new Absensi();

            $absenBaru->user_id = $request->user_id;
            $absenBaru->jadwal_id = $request->jadwal_id;
            $absenBaru->peserta = $request->peserta;
            $absenBaru->status = 'hadir';

            $absenBaru->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil absen.',
                'data' => $absenBaru
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan absensi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}