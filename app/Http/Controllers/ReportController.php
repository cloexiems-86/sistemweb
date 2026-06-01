<?php

namespace App\Http\Controllers;

use App\Models\Catin;
use App\Models\Ujian; 
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Catin::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Ambil semua data ujian diurutkan dari yang terbaru agar akurat
        $semuaUjian = Ujian::orderBy('created_at', 'desc')->get(); 

        // ========================================================
        // KECERDASAN FILTER: Membaca nama_peserta (Mengatasi Kolom Person NULL)
        // ========================================================
        if ($request->filled('status_ujian')) {
            $matchedCatinIds = [];
            $allCatinForFilter = Catin::all();
            
            foreach($allCatinForFilter as $cf) {
                // Cari Ujian Suami berdasarkan nama_peserta cadangan jika person NULL
                $uS = $semuaUjian->filter(function($u) use ($cf) {
                    return ($u->nama_peserta && strtolower(trim($u->nama_peserta)) == strtolower(trim($cf->nama_suami))) || 
                           ($u->catin_id == $cf->id && $u->person && strtolower(trim($u->person)) == 'suami');
                })->first();

                // Cari Ujian Istri berdasarkan nama_peserta cadangan jika person NULL
                $uI = $semuaUjian->filter(function($u) use ($cf) {
                    return ($u->nama_peserta && strtolower(trim($u->nama_peserta)) == strtolower(trim($cf->nama_istri))) || 
                           ($u->catin_id == $cf->id && $u->person && strtolower(trim($u->person)) == 'istri');
                })->first();

                $isLulus = ($uS && strtolower($uS->status_kelulusan) == 'lulus') || ($uI && strtolower($uI->status_kelulusan) == 'lulus');
                $isTidak = ($uS && strtolower($uS->status_kelulusan) == 'tidak_lulus') || ($uI && strtolower($uI->status_kelulusan) == 'tidak_lulus');
                $isBelum = (!$uS && !$uI);

                if ($request->status_ujian == 'lulus' && $isLulus) {
                    $matchedCatinIds[] = $cf->id;
                } elseif ($request->status_ujian == 'tidak_lulus' && $isTidak) {
                    $matchedCatinIds[] = $cf->id;
                } elseif ($request->status_ujian == 'belum' && $isBelum) {
                    $matchedCatinIds[] = $cf->id;
                }
            }
            $query->whereIn('id', $matchedCatinIds);
        }

        $catins = $query->latest()->paginate(15)->withQueryString();

        // ========================================================
        // LOGIKA EXECUTIVE SUMMARY CARDS (SUPER AKURAT)
        // ========================================================
        $totalPasangan = Catin::count();
        $lulusKeduanya = 0;

        $semuaCatin = Catin::all();
        foreach ($semuaCatin as $c) {
            $suamiLulus = $semuaUjian->filter(function($u) use ($c) {
                return (($u->nama_peserta && strtolower(trim($u->nama_peserta)) == strtolower(trim($c->nama_suami))) || 
                       ($u->catin_id == $c->id && $u->person && strtolower(trim($u->person)) == 'suami')) 
                       && strtolower(trim($u->status_kelulusan)) == 'lulus';
            })->isNotEmpty();

            $istriLulus = $semuaUjian->filter(function($u) use ($c) {
                return (($u->nama_peserta && strtolower(trim($u->nama_peserta)) == strtolower(trim($c->nama_istri))) || 
                       ($u->catin_id == $c->id && $u->person && strtolower(trim($u->person)) == 'istri')) 
                       && strtolower(trim($u->status_kelulusan)) == 'lulus';
            })->isNotEmpty();

            if ($suamiLulus && $istriLulus) {
                $lulusKeduanya++;
            }
        }

        $persentase = $totalPasangan > 0 ? round(($lulusKeduanya / $totalPasangan) * 100) : 0;

        return view('laporan.index', compact('catins', 'totalPasangan', 'lulusKeduanya', 'persentase'));
    }

    public function export(Request $request)
    {
        $query = Catin::query();
        $semuaUjian = Ujian::orderBy('created_at', 'desc')->get();

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('tanggal_dari')) $query->whereDate('created_at', '>=', $request->tanggal_dari);
        if ($request->filled('tanggal_sampai')) $query->whereDate('created_at', '<=', $request->tanggal_sampai);

        if ($request->filled('status_ujian')) {
            $matchedCatinIds = [];
            $allCatinForFilter = Catin::all();
            foreach($allCatinForFilter as $cf) {
                $uS = $semuaUjian->filter(function($u) use ($cf) {
                    return ($u->nama_peserta && strtolower(trim($u->nama_peserta)) == strtolower(trim($cf->nama_suami))) || 
                           ($u->catin_id == $cf->id && $u->person && strtolower(trim($u->person)) == 'suami');
                })->first();
                
                $uI = $semuaUjian->filter(function($u) use ($cf) {
                    return ($u->nama_peserta && strtolower(trim($u->nama_peserta)) == strtolower(trim($cf->nama_istri))) || 
                           ($u->catin_id == $cf->id && $u->person && strtolower(trim($u->person)) == 'istri');
                })->first();

                $isLulus = ($uS && strtolower($uS->status_kelulusan) == 'lulus') || ($uI && strtolower($uI->status_kelulusan) == 'lulus');
                $isTidak = ($uS && strtolower($uS->status_kelulusan) == 'tidak_lulus') || ($uI && strtolower($uI->status_kelulusan) == 'tidak_lulus');
                $isBelum = (!$uS && !$uI);

                if ($request->status_ujian == 'lulus' && $isLulus) $matchedCatinIds[] = $cf->id;
                elseif ($request->status_ujian == 'tidak_lulus' && $isTidak) $matchedCatinIds[] = $cf->id;
                elseif ($request->status_ujian == 'belum' && $isBelum) $matchedCatinIds[] = $cf->id;
            }
            $query->whereIn('id', $matchedCatinIds);
        }

        $dataExport = $query->latest()->get();
        $filename = 'Laporan_Bimbingan_KUA_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://memory', 'r+');

        fputcsv($handle, [
            'NO', 'PERAN', 'NAMA LENGKAP', 'NIK', 'ALAMAT DOMISILI', 'TGL RENCANA NIKAH', 'SKOR UJIAN', 'STATUS LULUS'
        ]);

        $no = 1;
        foreach ($dataExport as $catin) {
            $tglNikah = $catin->wedding_date ? \Carbon\Carbon::parse($catin->wedding_date)->format('d/m/Y') : '-';

            $ujianSuami = $semuaUjian->filter(function($u) use ($catin) {
                return ($u->nama_peserta && strtolower(trim($u->nama_peserta)) == strtolower(trim($catin->nama_suami))) || 
                       ($u->catin_id == $catin->id && $u->person && strtolower(trim($u->person)) == 'suami');
            })->first();

            $ujianIstri = $semuaUjian->filter(function($u) use ($catin) {
                return ($u->nama_peserta && strtolower(trim($u->nama_peserta)) == strtolower(trim($catin->nama_istri))) || 
                       ($u->catin_id == $catin->id && $u->person && strtolower(trim($u->person)) == 'istri');
            })->first();

            fputcsv($handle, [
                $no++, 'Suami', $catin->nama_suami, "'" . $catin->nik_suami, $catin->alamat_suami, $tglNikah,
                $ujianSuami ? $ujianSuami->skor : '-', $ujianSuami ? strtoupper(str_replace('_', ' ', $ujianSuami->status_kelulusan)) : 'BELUM UJIAN'
            ]);

            fputcsv($handle, [
                $no++, 'Istri', $catin->nama_istri, "'" . $catin->nik_istri, $catin->alamat_istri, $tglNikah,
                $ujianIstri ? $ujianIstri->skor : '-', $ujianIstri ? strtoupper(str_replace('_', ' ', $ujianIstri->status_kelulusan)) : 'BELUM UJIAN'
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)->header('Content-Type', 'text/csv')->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }
}