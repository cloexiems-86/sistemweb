<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Catin; // TAMBAHKAN INI
use App\Models\Pendamping; // TAMBAHKAN INI jika sudah ada modelnya
use App\Models\Materi; // 1. TAMBAHKAN INI
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

    // public function create(): View
    // {
    //     // AMBIL DATA AGAR TIDAK UNDEFINED
    //     $catins = Catin::all(); 
    //     $pendampings = Pendamping::all(); // Pastikan model Pendamping sudah ada

    //     return view('jadwalbimbingan.tambah-jadwal', compact('catins', 'pendampings'));
    // }

    public function create(): View
    {
        $catins = Catin::all(); 
        $pendampings = Pendamping::all();
        $materis = Materi::all(); // 2. AMBIL DATA MATERI UNTUK DROPDOWN

        return view('jadwalbimbingan.tambah-jadwal', compact('catins', 'pendampings', 'materis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tanggal' => 'required|date',
            'topik' => 'required|string|max:255',
            'sesi' => 'required|string|max:100',
            'fasilitator' => 'required|string|max:255',
            'status' => 'required|in:Upcoming,Completed',
            'lokasi' => 'required|string', // Tambahkan validasi lokasi
            'pendamping_id' => 'nullable|exists:pendamping,id', // Tambahkan validasi pendamping
            'catin_ids' => 'required|array', // Validasi harus pilih minimal 1 catin
        ]);

        // Simpan data Jadwal
        $jadwal = Jadwal::create([
            'tanggal' => $request->tanggal,
            'topik' => $request->topik,
            'sesi' => $request->sesi,
            'fasilitator' => $request->fasilitator,
            'status' => $request->status,
            'lokasi' => $request->lokasi,
            'pendamping_id' => $request->pendamping_id,
        ]);

        // SIMPAN RELASI CATIN (Pivot Table)
        // Pastikan di Model Jadwal sudah ada method: public function catins()
        $jadwal->catins()->attach($request->catin_ids);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

public function edit($id)
    {
        $jadwal = Jadwal::with('catins')->findOrFail($id);
        $pendampings = Pendamping::all();
        $catins = Catin::all();
        $materis = Materi::all(); // 3. AMBIL DATA MATERI JUGA DI EDIT

        return view('jadwalbimbingan.edit', compact('jadwal', 'pendampings', 'catins', 'materis'));
    }

public function update(Request $request, $id): RedirectResponse
{
    $request->validate([
        'tanggal' => 'required|date',
        'topik' => 'required|string|max:255',
        'sesi' => 'required|string|max:100', 
        'fasilitator' => 'required|string|max:255',
        'status' => 'required|in:Upcoming,Completed',
        'lokasi' => 'required|string', // Tambahkan ini
        'pendamping_id' => 'nullable|exists:pendamping,id', // Tambahkan ini (tabel pendamping tanpa s)
    ]);

    $jadwal = Jadwal::findOrFail($id);
    
    // Gunakan update manual agar data yang dikirim benar-benar terfilter
    $jadwal->update([
        'tanggal' => $request->tanggal,
        'topik' => $request->topik,
        'sesi' => $request->sesi,
        'fasilitator' => $request->fasilitator,
        'status' => $request->status,
        'lokasi' => $request->lokasi,
        'pendamping_id' => $request->pendamping_id,
    ]);

    if($request->has('catin_ids')) {
        $jadwal->catins()->sync($request->catin_ids);
    }

    return redirect()
        ->route('admin.jadwal.index')
        ->with('success', 'Jadwal berhasil diupdate');
}

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