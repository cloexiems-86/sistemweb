<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Catin;
use App\Models\Pendamping;
use App\Models\Materi;
use Illuminate\View\View; 
use Illuminate\Http\RedirectResponse; 

class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal dengan pagination
     */
    public function index() {
        // Eager loading relasi catins dan pendamping agar tidak lambat/error
        $jadwal = Jadwal::with(['catins', 'pendamping'])->latest()->paginate(5);
        return view('jadwalbimbingan.data-jadwal', compact('jadwal'));
    }

    /**
     * Menampilkan form tambah jadwal
     */
    public function create(): View
    {
        $catins = Catin::all(); 
        $pendampings = Pendamping::all();
        $materis = Materi::all(); // AMBIL DATA MATERI UNTUK DROPDOWN

        return view('jadwalbimbingan.tambah-jadwal', compact('catins', 'pendampings', 'materis'));
    }

    /**
     * Menyimpan jadwal baru ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'jenis_jadwal'  => 'required|in:Bimbingan,Rapak',
            'tanggal'       => 'required|date',
            'sesi'          => 'required|string',
            'lokasi'        => 'required|string',
            'fasilitator'   => 'required|string',
            'pendamping_id' => 'required|exists:pendamping,id', // SUDAH DI-FIX: Mengarah ke tabel pendamping (tanpa s)
            'status'        => 'required|string',
            'catin_ids'     => 'required|array',
            'catin_ids.*'   => 'exists:catins,id'
        ]);

        // 2. LOGIKA VALIDASI H-10 KHUSUS RAPAK
        if ($request->jenis_jadwal === 'Rapak') {
            $tanggal_rapak = \Carbon\Carbon::parse($request->tanggal)->startOfDay();

            foreach ($request->catin_ids as $catin_id) {
                $catin = \App\Models\Catin::find($catin_id);
                
                // Pastikan catin memiliki tanggal nikah
                if ($catin && $catin->tanggal_nikah) {
                    $tanggal_nikah = \Carbon\Carbon::parse($catin->tanggal_nikah)->startOfDay();
                    
                    // Hitung batas maksimal Rapak (H-10 dari tanggal nikah)
                    $batas_maksimal_rapak = $tanggal_nikah->copy()->subDays(10);

                    // Jika tanggal rapak lebih dari batas maksimal (misal H-5), tolak!
                    if ($tanggal_rapak->greaterThan($batas_maksimal_rapak)) {
                        return back()->withErrors([
                            'tanggal' => 'Gagal! Jadwal Rapak untuk Catin ' . $catin->nama_suami . ' & ' . $catin->nama_istri . ' melanggar aturan H-10. Maksimal Rapak harus dilakukan sebelum tanggal ' . $batas_maksimal_rapak->format('d-m-Y') . '.'
                        ])->withInput();
                    }
                } else {
                    return back()->withErrors([
                        'tanggal' => 'Catin ' . $catin->nama_suami . ' belum memiliki data Tanggal Nikah. Harap lengkapi data catin terlebih dahulu.'
                    ])->withInput();
                }
            }
        }

        // 3. Tentukan isi kolom "topik" berdasarkan jenis kegiatan
        $topik_yang_disimpan = $request->jenis_jadwal === 'Bimbingan' 
                                ? $request->topik 
                                : $request->agenda_rapak;

        // 4. Simpan Data ke Database
        $jadwal = \App\Models\Jadwal::create([
            'jenis_jadwal'  => $request->jenis_jadwal,
            'tanggal'       => $request->tanggal,
            'sesi'          => $request->sesi,
            'topik'         => $topik_yang_disimpan,
            'lokasi'        => $request->lokasi,
            'fasilitator'   => $request->fasilitator,
            'pendamping_id' => $request->pendamping_id,
            'status'        => $request->status,
        ]);

        // 5. Hubungkan Catin dengan Jadwal (Relasi Many-to-Many)
        $jadwal->catins()->sync($request->catin_ids);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal ' . $request->jenis_jadwal . ' berhasil dibuat!');
    }

    /**
     * Menampilkan form edit jadwal
     */
    public function edit($id)
    {
        $jadwal = Jadwal::with('catins')->findOrFail($id);
        $pendampings = Pendamping::all();
        $catins = Catin::all();
        $materis = Materi::all(); // AMBIL DATA MATERI JUGA DI EDIT

        return view('jadwalbimbingan.edit', compact('jadwal', 'pendampings', 'catins', 'materis'));
    }

    /**
     * Memperbarui data jadwal di database
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'jenis_jadwal'  => 'required|in:Bimbingan,Rapak',
            'tanggal'       => 'required|date',
            'topik'         => 'nullable|string|max:255', 
            'sesi'          => 'required|string|max:100', 
            'fasilitator'   => 'required|string|max:255',
            'status'        => 'required|in:Upcoming,Completed',
            'lokasi'        => 'required|string',
            'pendamping_id' => 'nullable|exists:pendamping,id', // SUDAH DI-FIX: Mengarah ke tabel pendamping (tanpa s)
        ]);

        $jadwal = Jadwal::findOrFail($id);

        // 2. LOGIKA VALIDASI H-10 KHUSUS RAPAK
        if ($request->jenis_jadwal === 'Rapak' && $request->has('catin_ids')) {
            $tanggal_rapak = \Carbon\Carbon::parse($request->tanggal)->startOfDay();

            foreach ($request->catin_ids as $catin_id) {
                $catin = \App\Models\Catin::find($catin_id);
                
                if ($catin && $catin->tanggal_nikah) {
                    $tanggal_nikah = \Carbon\Carbon::parse($catin->tanggal_nikah)->startOfDay();
                    
                    // Hitung batas maksimal Rapak (H-10 dari tanggal nikah)
                    $batas_maksimal_rapak = $tanggal_nikah->copy()->subDays(10);

                    // Jika melanggar H-10, tolak dan kembalikan pesan error
                    if ($tanggal_rapak->greaterThan($batas_maksimal_rapak)) {
                        return back()->withErrors([
                            'tanggal' => 'Gagal! Jadwal Rapak untuk Catin ' . $catin->nama_suami . ' & ' . $catin->nama_istri . ' melanggar aturan H-10. Maksimal Rapak harus dilakukan sebelum tanggal ' . $batas_maksimal_rapak->format('d-m-Y') . '.'
                        ])->withInput();
                    }
                } else {
                    return back()->withErrors([
                        'tanggal' => 'Catin ' . $catin->nama_suami . ' belum memiliki data Tanggal Nikah. Harap lengkapi data catin terlebih dahulu.'
                    ])->withInput();
                }
            }
        }

        // 3. Tentukan isi kolom "topik" berdasarkan jenis kegiatan
        $topik_yang_disimpan = $request->jenis_jadwal === 'Bimbingan' 
                                ? $request->topik 
                                : $request->agenda_rapak;

        // 4. Update Data ke Database
        $jadwal->update([
            'jenis_jadwal'  => $request->jenis_jadwal,
            'tanggal'       => $request->tanggal,
            'topik'         => $topik_yang_disimpan,
            'sesi'          => $request->sesi,
            'fasilitator'   => $request->fasilitator,
            'status'        => $request->status,
            'lokasi'        => $request->lokasi,
            'pendamping_id' => $request->pendamping_id,
        ]);

        // 5. Update Relasi Catin
        if($request->has('catin_ids')) {
            // Jika ada yang dicentang, sinkronkan data pivotnya
            $jadwal->catins()->sync($request->catin_ids);
        } else {
            // Jika admin menghapus semua centangan catin saat edit, hapus relasinya
            $jadwal->catins()->detach();
        }

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal ' . $request->jenis_jadwal . ' berhasil diupdate');
    }

    /**
     * Menghapus data jadwal dari database
     */
    public function destroy($id): RedirectResponse
    {
        $jadwal = Jadwal::findOrFail($id);
        
        // Hapus relasi di tabel pivot dulu sebelum hapus jadwalnya
        $jadwal->catins()->detach();
        $jadwal->delete();

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }
}