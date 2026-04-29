<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        // Menggunakan latest() agar pengumuman terbaru muncul paling atas
        $pengumumans = Pengumuman::latest()->get();
        return view('pengumuman.index', compact('pengumumans'));
    }

    public function create()
    {
        return view('pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'target' => 'required|in:catin,pendamping,semua'
        ]);

        Pengumuman::create($request->all());

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diterbitkan!');
    }

    // --- FITUR EDIT ---
    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('pengumuman.edit', compact('pengumuman'));
    }

    // --- FITUR UPDATE ---
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'target' => 'required|in:catin,pendamping,semua'
        ]);

        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->update($request->all());

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    // --- FITUR DELETE ---
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman telah dihapus.');
    }
}