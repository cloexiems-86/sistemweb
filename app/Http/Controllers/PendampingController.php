<?php

namespace App\Http\Controllers;

use App\Models\Pendamping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View; // Memperbaiki error return type
use Illuminate\Http\RedirectResponse; // Memperbaiki return type redirect

class PendampingController extends Controller
{
    // ======================
    // TAMPILKAN DATA
    // ======================
    public function index()
    {
        $pendamping = Pendamping::latest()->paginate(2);
        
        // Hitung total di sini
        $totalAktif = Pendamping::where('status', 'aktif')->count();
        $totalNonAktif = Pendamping::where('status', '!=', 'aktif')->count();

        return view('pendamping.data-pendamping', compact('pendamping', 'totalAktif', 'totalNonAktif'));
    }

    // ======================
    // FORM TAMBAH
    // ======================
    public function create(): View
    {
        return view('pendamping.tambah-pendamping');
    }

    // ======================
    // SIMPAN DATA
    // ======================
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'nip'      => 'nullable|string|max:50',
            'email'    => 'required|email|unique:pendamping,email',
            'no_hp'    => 'nullable|string|max:20',
            'password' => 'required|min:6',
            'status'   => 'required|in:aktif,nonaktif'
        ]);

        Pendamping::create([
            'nama'     => $request->nama,
            'nip'      => $request->nip,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'password' => Hash::make($request->password),
            'status'   => $request->status,
        ]);

        return redirect()
            ->route('admin.pendamping.index')
            ->with('success', 'Data pendamping berhasil ditambahkan');
    }

    // ======================
    // FORM EDIT
    // ======================
    public function edit($id): View
    {
        $pendamping = Pendamping::findOrFail($id);
        return view('pendamping.edit-pendamping', compact('pendamping'));
    }

    // ======================
    // UPDATE DATA
    // ======================
    public function update(Request $request, $id): RedirectResponse
    {
        $pendamping = Pendamping::findOrFail($id);

        $request->validate([
            'nama'   => 'required|string|max:255',
            'nip'    => 'nullable|string|max:50',
            'email'  => 'required|email|unique:pendamping,email,' . $id,
            'no_hp'  => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $data = [
            'nama'   => $request->nama,
            'nip'    => $request->nip,
            'email'  => $request->email,
            'no_hp'  => $request->no_hp,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pendamping->update($data);

        return redirect()
            ->route('admin.pendamping.index')
            ->with('success', 'Data pendamping berhasil diupdate');
    }

    // ======================
    // HAPUS DATA
    // ======================
    public function destroy($id): RedirectResponse
    {
        $pendamping = Pendamping::findOrFail($id);
        $pendamping->delete();

        return redirect()
            ->route('admin.pendamping.index')
            ->with('success', 'Data pendamping berhasil dihapus');
    }
}