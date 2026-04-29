<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catin;
use App\Models\Jadwal;
use App\Models\Materi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATS CARDS
        $totalBimwin = Jadwal::count();
        $totalVideo = Materi::count();
        $totalCatin = Catin::count();
        $totalSertifikat = Catin::where('status', 'Lulus')->count();

        // 2. CHART DESA
        $daftarDesa = [
            'Blimbing', 'Jugo', 'Kedawung', 'Keniten', 'Kranding', 
            'Kraton', 'Maesan', 'Mlati', 'Mojo', 'Mondo', 
            'Ngadi', 'Ngetrep', 'Pamongan', 'Petok', 'Petungroto', 
            'Ploso', 'Ponggok', 'Sukoanyar', 'Surat', 'Tambibendo'
        ];

        // Ambil data desa sekaligus (Eager Grouping) untuk menghindari error jika kolom belum siap
        // Pastikan nama kolom di bawah ini sesuai dengan yang ada di DB (misal: 'desa')
        $kolomDesa = 'desa_istri'; // Ganti ke 'desa' jika 'desa_istri' belum di-migrate

        $dataPerDesa = Catin::select($kolomDesa, DB::raw('count(*) as total'))
            ->whereIn($kolomDesa, $daftarDesa)
            ->groupBy($kolomDesa)
            ->pluck('total', $kolomDesa)
            ->toArray();

        $sertifikatPerDesa = Catin::select($kolomDesa, DB::raw('count(*) as total'))
            ->where('status', 'Lulus')
            ->whereIn($kolomDesa, $daftarDesa)
            ->groupBy($kolomDesa)
            ->pluck('total', $kolomDesa)
            ->toArray();

        $desaLabels = $daftarDesa;
        $desaCatinData = [];
        $desaSertifikatData = [];

        foreach ($daftarDesa as $desa) {
            $desaCatinData[] = $dataPerDesa[$desa] ?? 0;
            $desaSertifikatData[] = $sertifikatPerDesa[$desa] ?? 0;
        }

        // 3. TREND REGISTRASI (6 Bulan Terakhir)
        $bulanLabels = [];
        $bulanCatinData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bulanLabels[] = $month->translatedFormat('M'); // Menggunakan translated agar sesuai bahasa Indo
            $bulanCatinData[] = Catin::whereYear('created_at', $month->year)
                                     ->whereMonth('created_at', $month->month)
                                     ->count();
        }

        return view('auth.admin-dashboard', compact(
            'totalBimwin', 'totalVideo', 'totalCatin', 'totalSertifikat',
            'desaLabels', 'desaCatinData', 'desaSertifikatData',
            'bulanLabels', 'bulanCatinData'
        ));
    }
}