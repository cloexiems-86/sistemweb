<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catin;
use App\Models\Jadwal;
use App\Models\Materi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. STATS CARDS
        // Mengambil jadwal bimbingan perkawinan yang aktif di minggu ini saja
        $totalBimwin = Jadwal::whereBetween('tanggal', [
            Carbon::now()->startOfWeek(), 
            Carbon::now()->endOfWeek()
        ])->count();
        
        $totalVideo = Materi::count();
        $totalCatin = Catin::count();

        // Mengambil jumlah catin unik (distinct) yang sudah lulus/selesai ujian 
        // PERBAIKAN: Mengubah nama tabel dari 'ujians' menjadi 'ujian'
        $totalSertifikat = DB::table('ujian')
            ->where('skor', '>=', 70)
            ->distinct('catin_id')
            ->count('catin_id');

        // 2. CHART DESA (Data tetap dihitung agar API Flutter untuk Android Catin tidak error)
        $daftarDesa = [
            'Blimbing', 'Jugo', 'Kedawung', 'Keniten', 'Kranding', 
            'Kraton', 'Maesan', 'Mlati', 'Mojo', 'Mondo', 
            'Ngadi', 'Ngetrep', 'Pamongan', 'Petok', 'Petungroto', 
            'Ploso', 'Ponggok', 'Sukoanyar', 'Surat', 'Tambibendo'
        ];

        $kolomDesa = 'desa_istri'; 

        $dataPerDesa = Catin::select($kolomDesa, DB::raw('count(*) as total'))
            ->whereIn($kolomDesa, $daftarDesa)
            ->groupBy($kolomDesa)
            ->pluck('total', $kolomDesa)
            ->toArray();

        // Menyesuaikan logika kueri kelulusan desa dengan data ujian unik
        // PERBAIKAN: Mengubah seluruh referensi tabel 'ujians' menjadi 'ujian' (pada table, select, join, dan where)
        $sertifikatPerDesa = DB::table('ujian')
            ->select('catins.' . $kolomDesa, DB::raw('count(distinct ujian.catin_id) as total'))
            ->join('catins', 'catins.id', '=', 'ujian.catin_id')
            ->where('ujian.skor', '>=', 70)
            ->whereIn('catins.' . $kolomDesa, $daftarDesa)
            ->groupBy('catins.' . $kolomDesa)
            ->pluck('total', 'catins.' . $kolomDesa)
            ->toArray();

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
            $bulanLabels[] = $month->translatedFormat('M'); 
            $bulanCatinData[] = Catin::whereYear('created_at', $month->year)
                                     ->whereMonth('created_at', $month->month)
                                     ->count();
        }

        // --- LOGIKA FIX UNTUK FLUTTER ---
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data Dashboard Berhasil Diambil',
                'data' => [
                    'stats' => [
                        'totalBimwin' => $totalBimwin,
                        'totalVideo' => $totalVideo,
                        'totalCatin' => $totalCatin,
                        'totalSertifikat' => $totalSertifikat,
                    ],
                    'chart_desa' => [
                        'labels' => $daftarDesa,
                        'total_catin' => $desaCatinData,
                        'total_lulus' => $desaSertifikatData,
                    ],
                    'trend_registrasi' => [
                        'labels' => $bulanLabels,
                        'data' => $bulanCatinData,
                    ]
                ]
            ], 200);
        }

        // Jika akses lewat browser biasa, tampilkan View Web
        return view('auth.admin-dashboard', compact(
            'totalBimwin', 'totalVideo', 'totalCatin', 'totalSertifikat',
            'daftarDesa', 'desaCatinData', 'desaSertifikatData',
            'bulanLabels', 'bulanCatinData'
        ));
    }
}