<?php

namespace App\Http\Controllers;

use App\Models\Catin;
use App\Models\Ujian;
use Illuminate\Http\Request;
// Import Facade PDF agar fungsi download bekerja
use Barryvdh\DomPDF\Facade\Pdf;

class SertifikatController extends Controller
{
    /**
     * Menampilkan daftar pasangan (Catin) yang salah satu / keduanya sudah lulus
     */
    public function index()
    {
        // Cari ID Catin (Pasangan) yang sudah punya ujian lulus
        $catinLulusIds = Ujian::where('status_kelulusan', 'LIKE', '%lulus%')
            ->where('skor', '>=', 70)
            ->pluck('catin_id')
            ->unique();

        // Tampilkan data Pasangan (Catin)
        $sertifikats = Catin::whereIn('id', $catinLulusIds)
            ->latest()
            ->paginate(10);

        return view('sertifikat.index', compact('sertifikats'));
    }

    /**
     * Fungsi untuk mengunduh sertifikat dalam format PDF.
     */
    public function download(Request $request, $id, $person)
    {
        // 1. Coba cari menggunakan ID Ujian (prioritas untuk Android)
        $ujian = Ujian::with('catin')->find($id);

        // 2. Jika tidak ketemu sebagai ID Ujian, fallback cari sebagai ID Catin (untuk Web Admin)
        if (!$ujian) {
            $ujian = $this->getNilaiIndividu($id, $person);
        }

        if (!$ujian) {
            // JIKA DIAKSES DARI API/ANDROID: Kembalikan Teks, JANGAN Redirect
            if ($request->expectsJson() || $request->is('api/*') || strpos($request->url(), 'api') !== false || $request->header('User-Agent') == 'Dart/3.0 (dart:io)') {
                 return response('Gagal! Data riwayat ujian tidak ditemukan atau belum lulus.', 404);
            }
            // JIKA DIAKSES DARI WEB: Boleh Redirect
            return redirect()->route('admin.sertifikat.index')->with('error', 'Gagal! Calon ' . ucfirst($person) . ' belum mengerjakan ujian atau nilainya belum lulus.');
        }

        $catin = $ujian->catin;
        $displayName = $person === 'suami' ? $catin->nama_suami : $catin->nama_istri;
        $fileName = 'Sertifikat_Bimwin_' . str_replace(' ', '_', $displayName) . '.pdf';

        $pdf = Pdf::loadView('sertifikat.cetak', compact('ujian', 'catin', 'person'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    /**
     * Fungsi untuk melihat sertifikat di browser/aplikasi secara inline (PDF).
     */
    public function stream(Request $request, $id, $person)
    {
        // 1. Coba cari menggunakan ID Ujian (prioritas untuk Android)
        $ujian = Ujian::with('catin')->find($id);

        // 2. Jika tidak ketemu sebagai ID Ujian, fallback cari sebagai ID Catin (untuk Web Admin)
        if (!$ujian) {
             $ujian = $this->getNilaiIndividu($id, $person);
        }

        if (!$ujian) {
             // JIKA DIAKSES DARI API/ANDROID: Kembalikan Teks, JANGAN Redirect
             // Flutter HTTP client defaults to Dart/x.x User-Agent, but checking expectsJson is safer
             if ($request->wantsJson() || strpos($request->url(), 'api') !== false || str_contains($request->header('User-Agent'), 'Dart')) {
                  return response('Gagal! Data riwayat ujian tidak ditemukan atau belum lulus.', 404);
             }
             // JIKA DIAKSES DARI WEB: Boleh Redirect
             return redirect()->route('admin.sertifikat.index')->with('error', 'Gagal! Calon ' . ucfirst($person) . ' belum mengerjakan ujian atau nilainya belum lulus.');
        }

        $catin = $ujian->catin;
        $displayName = $person === 'suami' ? $catin->nama_suami : $catin->nama_istri;
        $fileName = 'Sertifikat_Bimwin_' . str_replace(' ', '_', $displayName) . '.pdf';

        $pdf = Pdf::loadView('sertifikat.cetak', compact('ujian', 'catin', 'person'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream($fileName);
    }

    /**
     * Preview sertifikat di browser (HTML) sebelum diunduh.
     */
    public function preview($id, $person)
    {
        // Preview biasanya hanya diakses dari Web Admin, jadi getNilaiIndividu (ID Catin) sudah tepat
        $ujian = $this->getNilaiIndividu($id, $person);

        if (!$ujian) {
            return redirect()->route('admin.sertifikat.index')->with('error', 'Gagal! Calon ' . ucfirst($person) . ' belum mengerjakan ujian atau nilainya belum lulus.');
        }

        $catin = $ujian->catin;
        $mode = 'preview';

        return view('sertifikat.cetak', compact('ujian', 'catin', 'person', 'mode'));
    }

    /**
     * ========================================================
     * FUNGSI HELPER: Pelacak Nilai Super Cerdas (Kebal Bug Android)
     * ========================================================
     */
    private function getNilaiIndividu($catin_id, $person)
    {
        $person = strtolower($person);
        if (!in_array($person, ['suami', 'istri'])) return null;

        $catin = Catin::find($catin_id);
        if (!$catin) return null;

        // Ambil nama spesifik sebagai kunci pencarian cadangan
        $namaTujuan = $person === 'suami' ? $catin->nama_suami : $catin->nama_istri;

        return Ujian::where('catin_id', $catin_id)
            ->where(function ($query) use ($person, $namaTujuan) {
                // Cari berdasarkan kolom 'person' ATAU cocokkan dengan 'nama_peserta'
                $query->where('person', 'LIKE', '%' . $person . '%')
                      ->orWhere('nama_peserta', 'LIKE', '%' . $namaTujuan . '%');
            })
            ->where('status_kelulusan', 'LIKE', '%lulus%')
            ->where('skor', '>=', 70)
            ->latest()
            ->first();
    }
}