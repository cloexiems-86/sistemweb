<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\SoalUjian; // Tambahkan ini di deretan use
use App\Models\Catin;
use App\Models\Materi;
use App\Models\Kuis;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    /**
     * LIST DATA UJIAN
     */
    public function index()
    {
        $ujianResults = Ujian::with(['catin.pendamping', 'catin.jadwals.pendamping'])->latest()->paginate(5);

        $totalUjian = Ujian::count();
        $totalLulus = Ujian::where('status_kelulusan', 'lulus')->count();
        $totalGagal = Ujian::where('status_kelulusan', 'tidak_lulus')->count();

        $persentaseLulus = $totalUjian > 0 
            ? round(($totalLulus / $totalUjian) * 100, 1) 
            : 0;

        $persentaseGagal = $totalUjian > 0 
            ? round(($totalGagal / $totalUjian) * 100, 1) 
            : 0;

        $chartData = [
            'labels' => ['Lulus', 'Tidak Lulus'],
            'data' => [$totalLulus, $totalGagal],
        ];

        $rataNilai = Ujian::avg('skor') ?? 0;
        $nilaiTertinggi = Ujian::max('skor') ?? 0;
        $nilaiTerendah = Ujian::min('skor') ?? 0;

        return view('ujian.data-ujian', compact(
            'ujianResults', 
            'totalUjian', 
            'totalLulus', 
            'totalGagal',
            'persentaseLulus',
            'persentaseGagal',
            'chartData',
            'rataNilai',
            'nilaiTertinggi',
            'nilaiTerendah'
        ));
    }

    /**
     * FORM TAMBAH UJIAN
     */
    public function create()
    {
        // Pastikan relasi 'ujian' sudah ada di Model Catin agar whereDoesntHave bisa jalan
        // Jika belum ada, gunakan Catin::all() sementara, tapi validasi di store() akan tetap menjaga data
        $catins = Catin::all();

        foreach ($catins as $catin) {
            $totalMateri = Materi::count();

            // Menggunakan catin_id sesuai kolom di tabel kuis
            $materiSelesai = Kuis::where('catin_id', $catin->id)
                                ->distinct('materi_id')
                                ->count('materi_id');

            $catin->boleh_ujian = ($totalMateri > 0) ? ($materiSelesai >= $totalMateri) : false;
            
            // Cek manual jika relasi model belum diperbaiki
            $sudahUjian = Ujian::where('catin_id', $catin->id)->exists();
            $catin->sudah_pernah_ujian = $sudahUjian;
        }

        return view('ujian.tambah-ujian', compact('catins'));
    }

    /**
     * SIMPAN DATA UJIAN
     */
    public function store(Request $request)
    {
        $request->validate([
            'catin_id' => 'required|exists:catins,id',
            'jawaban_benar' => 'required|numeric|min:0',
            'jawaban_salah' => 'required|numeric|min:0'
        ]);

        $cek = Ujian::where('catin_id', $request->catin_id)->first();
        if ($cek) {
            return back()->with('error', 'Catin sudah pernah mengikuti ujian!');
        }

        $total = (int)$request->jawaban_benar + (int)$request->jawaban_salah;
        $skor = $total > 0 
            ? round(($request->jawaban_benar / $total) * 100) 
            : 0;

        $status = $skor >= 70 ? 'lulus' : 'tidak_lulus';

        Ujian::create([
            'catin_id' => $request->catin_id,
            'jawaban_benar' => $request->jawaban_benar,
            'jawaban_salah' => $request->jawaban_salah,
            'skor' => $skor,
            'status_kelulusan' => $status
        ]);

        return redirect()->route('admin.ujian.index')
            ->with('success', 'Data ujian berhasil disimpan');
    }

    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $ujian = Ujian::with('catin')->findOrFail($id);
        return view('ujian.edit', compact('ujian'));
    }

    /**
     * UPDATE DATA
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jawaban_benar' => 'required|numeric|min:0',
            'jawaban_salah' => 'required|numeric|min:0'
        ]);

        $ujian = Ujian::findOrFail($id);
        $total = (int)$request->jawaban_benar + (int)$request->jawaban_salah;
        $skor = $total > 0 
            ? round(($request->jawaban_benar / $total) * 100) 
            : 0;

        $status = $skor >= 70 ? 'lulus' : 'tidak_lulus';

        $ujian->update([
            'jawaban_benar' => $request->jawaban_benar,
            'jawaban_salah' => $request->jawaban_salah,
            'skor' => $skor,
            'status_kelulusan' => $status
        ]);

        return redirect()->route('admin.ujian.index')
            ->with('success', 'Data ujian berhasil diperbarui');
    }

    /**
     * DETAIL
     */
    public function show($id)
    {
        $ujian = Ujian::with(['catin.pendamping'])->findOrFail($id);
        return view('ujian.show', compact('ujian'));
    }

    /**
     * RESET UJIAN
     */
    public function resetUjian($id)
    {
        $ujian = Ujian::findOrFail($id);
        $ujian->delete();
        return redirect()->back()->with('success', 'Data ujian berhasil direset.');
    }

    /**
     * HAPUS
     */
    public function destroy($id)
    {
        $ujian = Ujian::findOrFail($id);
        $ujian->delete();
        return redirect()->route('admin.ujian.index')
            ->with('success', 'Data ujian berhasil dihapus.');
    }


    /**
     * TAMPILKAN BANK SOAL (REVISI DOSPEM)
     */
    public function indexSoal()
    {
        $soals = SoalUjian::all();
        $history = Ujian::with('catin')->latest()->get(); 
        
        // dd($history); // Hapus tanda komentar ini untuk cek data di layar hitam
        
        return view('ujian.manage-soal', compact('soals', 'history'));
    }

    /**
     * SIMPAN SOAL BARU
     */
    public function storeSoal(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required',
            'pil_a' => 'required',
            'pil_b' => 'required',
            'pil_c' => 'required',
            'pil_d' => 'required',
            'kunci_jawaban' => 'required|in:a,b,c,d',
        ]);

        SoalUjian::create($request->all());

        return back()->with('success', 'Soal berhasil ditambahkan ke Bank Soal.');
    }

    /**
     * HAPUS SOAL
     */
    public function destroySoal($id)
    {
        SoalUjian::findOrFail($id)->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * TAMPILKAN LOG / HISTORY UJIAN
     */


        /**
     * UPDATE SOAL (REVISI)
     */
    public function updateSoal(Request $request, $id)
    {
        $request->validate([
            'pertanyaan' => 'required',
            'pil_a' => 'required',
            'pil_b' => 'required',
            'pil_c' => 'required',
            'pil_d' => 'required',
            'kunci_jawaban' => 'required|in:a,b,c,d',
        ]);

        $soal = SoalUjian::findOrFail($id);
        $soal->update($request->all());

        return redirect()->back()->with('success', 'Soal berhasil diperbarui!');
    }

    /**
     * SERTIFIKAT
     */
    public function sertifikat($id)
    {
        $ujian = Ujian::with('catin')->findOrFail($id);

        if ($ujian->status_kelulusan != 'lulus') {
            return back()->with('error', 'Belum lulus!');
        }

        return view('ujian.data-ujian', compact('ujian'));
    }
    public function exportPdf()
    {
        $ujianResults = \App\Models\Ujian::with(['catin.pendamping', 'catin.jadwals.pendamping'])->latest()->get();
        $pdf = \PDF::loadView('ujian.export-pdf', compact('ujianResults'));
        $filename = 'hasil-ujian-' . now()->format('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportExcel()
    {
        $ujians = \App\Models\Ujian::with(['catin.pendamping', 'catin.jadwals.pendamping'])->get();
        $filename = 'hasil-ujian-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'  . $filename . '"',
        ];

        $columns = ['NO','NAMA CATIN','PENDAMPING','JAWABAN BENAR','JAWABAN SALAH','SKOR','STATUS KELULUSAN','TANGGAL'];

        $callback = function() use ($ujians, $columns) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);
            $no = 1;
            foreach ($ujians as $ujian) {
                $row = [
                    $no++,
                    $ujian->catin?->nama_suami ?? '',
                    $ujian->catin?->pendamping_assigned ?? 'Tanpa Pendamping',
                    $ujian->jawaban_benar ?? '',
                    $ujian->jawaban_salah ?? '',
                    $ujian->skor ?? '',
                    $ujian->status_kelulusan ?? '',
                    $ujian->created_at ? $ujian->created_at->format('Y-m-d H:i:s') : '',
                ];
                fputcsv($out, $row);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}

