<?php

namespace App\Http\Controllers;

use App\Models\Catin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class CatinController extends Controller
{
    // public function index()
    // {
    //     // Tetap menggunakan paginate(10) sesuai keinginanmu
    //     // withQueryString() ditambahkan agar jika nanti ada fitur filter/search, 
    //     // halaman ke-2 dan seterusnya tidak error/hilang filternya.
    //     $catin = Catin::latest()->paginate(2)->withQueryString();
        
    //     return view('catin.data-catin', compact('catin'));
    // }

public function index() {
    $catin = Catin::latest()->paginate(2);
    $totalAktif = Catin::where('status', 'aktif')->count();
    $totalNonAktif = Catin::where('status', '!=', 'aktif')->count();
    
    return view('catin.data-catin', compact('catin', 'totalAktif', 'totalNonAktif'));
}

    public function create()
    {
        return view('catin.tambah-catin');
    }

    public function store(Request $request)
    {
        // 1. VALIDASI
        $request->validate([
            'username' => 'required|unique:catins,username',
            'password' => 'required|min:6',

            'nama_suami' => 'required',
            'nik_suami' => 'required|digits:16|unique:catins,nik_suami',
            'phone_suami' => 'required|unique:catins,phone_suami',
            'email_suami' => 'required|email|unique:catins,email_suami',
            'alamat_suami' => 'required', 

            'nama_istri' => 'required',
            'nik_istri' => 'required|digits:16|unique:catins,nik_istri',
            'phone_istri' => 'required|unique:catins,phone_istri',
            'email_istri' => 'required|email|unique:catins,email_istri',
            'alamat_istri' => 'required', 

            'wedding_date' => 'required|date',

            'ktp_suami' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ktp_istri' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk_suami' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk_istri' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'username.unique' => 'Username sudah terdaftar, gunakan username lain',
            'nik_suami.unique' => 'NIK Suami sudah terdaftar',
            'nik_istri.unique' => 'NIK Istri sudah terdaftar',
            'phone_suami.unique' => 'No WA Suami sudah terdaftar',
            'phone_istri.unique' => 'No WA Istri sudah terdaftar',
            'email_suami.unique' => 'Email Suami sudah terdaftar',
            'email_istri.unique' => 'Email Istri sudah terdaftar',
        ]);

        // 2. AMBIL DATA
        $data = $request->only([
            'username', 'nama_suami', 'nik_suami', 'phone_suami', 'email_suami', 'alamat_suami',
            'nama_istri', 'nik_istri', 'phone_istri', 'email_istri', 'alamat_istri',
            'wedding_date'
        ]);

        // 3. HASH PASSWORD
        $data['password'] = Hash::make($request->password);

        // 4. UPLOAD FILE
        $data['ktp_suami'] = $request->file('ktp_suami')->store('ktp', 'public');
        $data['ktp_istri'] = $request->file('ktp_istri')->store('ktp', 'public');
        $data['kk_suami']  = $request->file('kk_suami')->store('kk', 'public');
        $data['kk_istri']  = $request->file('kk_istri')->store('kk', 'public');

        $data['status'] = 'aktif';
        $data['role'] = 'catin'; // Menandakan ini adalah catin, bukan pendamping
        // 5. SIMPAN
        Catin::create($data);

        return redirect()
            ->route('admin.catin.index')
            ->with('success', 'Data Pasangan Catin Berhasil Ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $catin = Catin::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:catins,username,' . $id,
            'nama_suami' => 'required',
            'nik_suami' => 'required|digits:16|unique:catins,nik_suami,' . $id,
            'phone_suami' => 'required|unique:catins,phone_suami,' . $id,
            'email_suami' => 'required|email|unique:catins,email_suami,' . $id,
            'alamat_suami' => 'required',
            
            'nama_istri' => 'required',
            'nik_istri' => 'required|digits:16|unique:catins,nik_istri,' . $id,
            'phone_istri' => 'required|unique:catins,phone_istri,' . $id,
            'email_istri' => 'required|email|unique:catins,email_istri,' . $id,
            'alamat_istri' => 'required',
            
            'status' => 'required|in:aktif,nonaktif'
        ], [
            'username.unique' => 'Username sudah terdaftar, gunakan username lain',
            'nik_suami.unique' => 'NIK Suami sudah terdaftar',
            'nik_istri.unique' => 'NIK Istri sudah terdaftar',
            'phone_suami.unique' => 'No WA Suami sudah terdaftar',
            'phone_istri.unique' => 'No WA Istri sudah terdaftar',
            'email_suami.unique' => 'Email Suami sudah terdaftar',
            'email_istri.unique' => 'Email Istri sudah terdaftar',
        ]);

        $data = $request->only([
            'username', 'nama_suami', 'nama_istri', 'alamat_suami', 'alamat_istri', 'status',
            'nik_suami', 'nik_istri', 'phone_suami', 'phone_istri', 'email_suami', 'email_istri', 'wedding_date'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        foreach (['ktp_suami','ktp_istri','kk_suami','kk_istri'] as $file) {
            if ($request->hasFile($file)) {
                if ($catin->$file) {
                    Storage::disk('public')->delete($catin->$file);
                }
                $data[$file] = $request->file($file)->store(
                    str_contains($file, 'ktp') ? 'ktp' : 'kk',
                    'public'
                );
            }
        }

        $catin->update($data);

        return redirect()
            ->route('admin.catin.index')
            ->with('success', 'Data Catin Berhasil Diupdate');
    }

    public function edit($id)
    {
        $catin = Catin::findOrFail($id);
        return view('catin.edit-catin', compact('catin'));
    }


    public function destroy($id)
    {
        $catin = Catin::findOrFail($id);

        foreach (['ktp_suami','ktp_istri','kk_suami','kk_istri'] as $file) {
            if ($catin->$file) {
                Storage::disk('public')->delete($catin->$file);
            }
        }

        $catin->delete();

        return redirect()
            ->route('admin.catin.index')
            ->with('success', 'Data Catin Berhasil Dihapus');
    }

    public function show($id)
    {
        // Mengambil data catin spesifik berdasarkan ID
        $item = \App\Models\Catin::findOrFail($id);
        
        return view('catin.show', compact('item'));
    }

    public function ujian()
    {
        return $this->hasOne(Ujian::class, 'catin_id');
    }
}