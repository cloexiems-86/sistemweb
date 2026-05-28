<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use Illuminate\Http\Request;
// Import Facade PDF agar fungsi download bekerja
use Barryvdh\DomPDF\Facade\Pdf;

class SertifikatController extends Controller
{
    /**
     * Menampilkan daftar catin yang berhak mendapatkan sertifikat.
     */
    public function index()
    {
        // 1. Filter hanya yang 'lulus' DAN skor > 70 sesuai arahan dospem
        // 2. Eager loading relasi catin dan pendamping untuk efisiensi
        $sertifikats = Ujian::with(['catin'])
            ->where('status_kelulusan', 'lulus')
            ->where('skor', '>=', 70) 
            ->latest()
            ->paginate(10);

        // Sesuaikan path view dengan folder kamu, misalnya 'admin.sertifikat.index'
        return view('sertifikat.index', compact('sertifikats'));
    }

    /**
     * Fungsi untuk mengunduh sertifikat dalam format PDF.
     */
    public function download($id, $person = null)
    {
        $ujian = Ujian::with(['catin'])->findOrFail($id);

        // Pastikan hanya yang lulus dan skor >= 70 yang dapat mengunduh
        if ($ujian->status_kelulusan !== 'lulus' || $ujian->skor < 70) {
            abort(403, 'Tidak diizinkan mengunduh sertifikat.');
        }

        // Tentukan nama target pada sertifikat (suami / istri / pasangan)
        $person = $person ? strtolower($person) : null;
        // Jika ujian record sendiri sudah menyimpan 'person', gunakan itu sebagai default
        if (is_null($person) && isset($ujian->person) && in_array($ujian->person, ['suami', 'istri'])) {
            $person = $ujian->person;
        }
        if ($person === 'suami') {
            $displayName = $ujian->catin->nama_suami;
        } elseif ($person === 'istri') {
            $displayName = $ujian->catin->nama_istri;
        } else {
            $displayName = $ujian->catin->nama_lengkap; // default
        }

        $fileName = 'Sertifikat_Bimwin_' . str_replace(' ', '_', $displayName) . '.pdf';

        // Load view khusus desain sertifikat dan set ukuran kertas A4 Landscape
        $pdf = Pdf::loadView('sertifikat.cetak', compact('ujian', 'person'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    /**
     * Fungsi untuk melihat sertifikat di browser/aplikasi secara inline (PDF).
     */
    public function stream($id, $person = null)
    {
        $ujian = Ujian::with(['catin'])->findOrFail($id);

        if ($ujian->status_kelulusan !== 'lulus' || $ujian->skor < 70) {
            abort(403, 'Tidak diizinkan melihat sertifikat.');
        }

        $person = $person ? strtolower($person) : null;
        if (is_null($person) && isset($ujian->person) && in_array($ujian->person, ['suami', 'istri'])) {
            $person = $ujian->person;
        }
        if ($person === 'suami') {
            $displayName = $ujian->catin->nama_suami;
        } elseif ($person === 'istri') {
            $displayName = $ujian->catin->nama_istri;
        } else {
            $displayName = $ujian->catin->nama_lengkap;
        }

        $fileName = 'Sertifikat_Bimwin_' . str_replace(' ', '_', $displayName) . '.pdf';

        $pdf = Pdf::loadView('sertifikat.cetak', compact('ujian', 'person'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream($fileName);
    }

    /**
     * Preview sertifikat di browser (HTML) sebelum diunduh.
     */
    public function preview($id, $person = null)
    {
        $ujian = Ujian::with(['catin'])->findOrFail($id);

        // Safety: only allow preview for lulus >=70
        if ($ujian->status_kelulusan !== 'lulus' || $ujian->skor < 70) {
            abort(403, 'Tidak diizinkan melihat sertifikat.');
        }

        $person = $person ? strtolower($person) : null;
        return view('sertifikat.cetak', compact('ujian', 'person'));
    }
}