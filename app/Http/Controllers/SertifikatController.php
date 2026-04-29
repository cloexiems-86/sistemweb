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
        $sertifikats = Ujian::with(['catin.pendamping'])
            ->where('status_kelulusan', 'lulus')
            ->where('skor', '>', 70) 
            ->latest()
            ->paginate(10);

        // Sesuaikan path view dengan folder kamu, misalnya 'admin.sertifikat.index'
        return view('sertifikat.index', compact('sertifikats'));
    }

    /**
     * Fungsi untuk mengunduh sertifikat dalam format PDF.
     */
    public function download($id)
    {
        $ujian = Ujian::with(['catin.pendamping'])->findOrFail($id);
        
        // Mengatur nama file PDF yang akan diunduh
        $fileName = 'Sertifikat_Bimwin_' . str_replace(' ', '_', $ujian->catin->nama) . '.pdf';

        // Load view khusus desain sertifikat dan set ukuran kertas A4 Landscape
        $pdf = Pdf::loadView('admin.sertifikat.cetak', compact('ujian'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    /**
     * Preview sertifikat di browser (HTML) sebelum diunduh.
     */
    public function preview($id)
    {
        $ujian = Ujian::with(['catin.pendamping'])->findOrFail($id);
        
        return view('sertifikat.cetak', compact('ujian'));
    }
}