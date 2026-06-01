<?php

namespace App\Http\Controllers;

use App\Models\Pendamping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View; 
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\Http; // Tambahan wajib untuk memanggil API Fonnte

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
            'nip'      => 'nullable|string|max:50|unique:pendamping,nip',
            'email'    => 'required|email|unique:pendamping,email',
            'no_hp'    => 'nullable|string|max:20|unique:pendamping,no_hp',
            'password' => 'required|min:6',
            'status'   => 'required|in:aktif,nonaktif'
        ]);

        // Simpan ke Database
        Pendamping::create([
            'nama'     => $request->nama,
            'nip'      => $request->nip,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'password' => Hash::make($request->password),
            'status'   => $request->status,
        ]);

        // ==========================================
        // PROSES KIRIM WHATSAPP VIA FONNTE
        // ==========================================
        $pesanWa = "Halo Bapak/Ibu *{$request->nama}*,\n\n";
        $pesanWa .= "Anda telah berhasil didaftarkan sebagai Pendamping / Pembimbing dalam sistem E-Learning Bimbingan Perkawinan KUA Mojo.\n\n";
        $pesanWa .= "Berikut adalah detail akses akun Anda untuk login ke sistem:\n";
        $pesanWa .= "Email Login: *{$request->email}*\n"; // Menggunakan Email karena tidak ada field username
        $pesanWa .= "Password: *{$request->password}*\n\n"; // Menggunakan password asli dari inputan
        $pesanWa .= "Silakan gunakan akun ini untuk masuk melalui portal Web Admin KUA Mojo.\n";
        $pesanWa .= "Mohon untuk menjaga kerahasiaan data akun Anda demi keamanan bersama. Terima kasih.";

        $pesanFlash = 'Data pendamping berhasil ditambahkan';

        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $request->no_hp,
                'message' => $pesanWa,
                'countryCode' => '62',
            ]);

            $responFonnte = $response->json();
            
            if (isset($responFonnte['status']) && $responFonnte['status'] == true) {
                $pesanFlash = 'Data Pendamping berhasil disimpan dan notifikasi akun telah dikirim via WhatsApp!';
            } else {
                $pesanFlash = 'Data tersimpan, tapi WhatsApp gagal dikirim: ' . ($responFonnte['reason'] ?? 'Error API');
            }
        } catch (\Exception $e) {
            // PERBAIKAN DIAGNOSTIK: Menampilkan detail pesan error asli dari jaringan server
            $pesanFlash = 'Data tersimpan, tetapi gagal terhubung ke Fonnte. Detail: ' . $e->getMessage();
        }
        // ==========================================
        // END PROSES FONNTE
        // ==========================================

        return redirect()
            ->route('admin.pendamping.index')
            ->with('success', $pesanFlash);
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
            'nip'    => 'nullable|string|max:50|unique:pendamping,nip,' . $id,
            'email'  => 'required|email|unique:pendamping,email,' . $id,
            'no_hp'  => 'nullable|string|max:20|unique:pendamping,no_hp,' . $id,
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

    // ======================
    // CHECK NIP UNIQUE (AJAX)
    // ======================
    public function checkNip(Request $request)
    {
        $nip = $request->input('nip');
        $exists = Pendamping::where('nip', $nip)->exists();
        
        return response()->json(['exists' => $exists]);
    }

    // ======================
    // CHECK EMAIL UNIQUE (AJAX)
    // ======================
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = Pendamping::where('email', $email)->exists();
        
        return response()->json(['exists' => $exists]);
    }

    // ======================
    // CHECK WHATSAPP UNIQUE (AJAX)
    // ======================
    public function checkWhatsapp(Request $request)
    {
        $no_hp = $request->input('no_hp');
        $exists = Pendamping::where('no_hp', $no_hp)->exists();
        
        return response()->json(['exists' => $exists]);
    }
}