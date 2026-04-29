<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\MateriLog;
use App\Models\Kuis;
use App\Models\Soal;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View; 
use Illuminate\Http\RedirectResponse;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar materi
     */
    public function index()
    {
        // Update bagian ini di MateriController.php kamu
        $materi = Materi::with('kuis')->withCount('logs')->latest()->paginate(2);
        
        // Hitung total keseluruhan database di sini
        $counts = [
            'aktif' => Materi::where('status', 'aktif')->count(),
            'nonaktif' => Materi::where('status', 'nonaktif')->count(),
            'total_soal' => \App\Models\Kuis::count(), // Sesuaikan nama model kuis/soal kamu
            'total_akses' => \App\Models\MateriLog::count(), // Tambahkan hitungan total akses materi
        ];

        return view('materi.data-materi', compact('materi', 'counts'));
    }

    public function create(): View
    {
        return view('materi.tambah-materi');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'file' => 'nullable|file|mimes:pdf,doc,docx,mp4,mov,avi|max:10240'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materi', 'public');
        }

        Materi::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'file' => $filePath
        ]);

        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil ditambahkan');
    }

    public function edit($id): View
    {
        $materi = Materi::with('kuis')->findOrFail($id);
        return view('materi.edit-materi', compact('materi'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $materi = Materi::findOrFail($id);
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'file' => 'nullable|file|mimes:pdf,doc,docx,mp4,mov,avi|max:10240'
        ]);

        $data = ['judul' => $request->judul, 'deskripsi' => $request->deskripsi, 'status' => $request->status];

        if ($request->hasFile('file')) {
            if ($materi->file) Storage::disk('public')->delete($materi->file);
            $data['file'] = $request->file('file')->store('materi', 'public');
        }

        $materi->update($data);
        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil diperbarui');
    }

    public function destroy($id): RedirectResponse
    {
        $materi = Materi::findOrFail($id);
        if ($materi->file) Storage::disk('public')->delete($materi->file);

        if($materi->kuis) {
            $materi->kuis->soals()->delete();
            $materi->kuis()->delete();
        }

        $materi->delete();
        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil dihapus');
    }


    /**
     * Menampilkan siapa saja yang sudah melihat/mempelajari materi
     */
    public function showLogs($id): View
    {
        // Ambil materi beserta log-nya dan data user (catin) yang mengakses
        // Asumsi: di model MateriLog ada relasi 'user' atau 'catin'
        $materi = Materi::with(['logs.user'])->findOrFail($id); 
        
        return view('materi.logs-materi', compact('materi'));
    }

    
    public function manageKuis($id): View
    {
        $materi = Materi::with(['kuis.soals'])->findOrFail($id);
        return view('materi.manage-kuis', compact('materi'));
    }

    /**
     * Menyimpan banyak soal sekaligus
     */
    public function storeSoal(Request $request): RedirectResponse
    {
        $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'soal' => 'required|array|min:1',
            'soal.*.pertanyaan' => 'required|string',
            'soal.*.opsi_a' => 'required|string',
            'soal.*.opsi_b' => 'required|string',
            'soal.*.opsi_c' => 'required|string',
            'soal.*.opsi_d' => 'required|string',
            'soal.*.jawaban_benar' => 'required|in:a,b,c,d', 
        ]);

        // Cari atau buat kuis berdasarkan materi_id
        $kuis = Kuis::firstOrCreate(['materi_id' => $request->materi_id]);

        foreach ($request->soal as $item) {
            Soal::create([
                'kuis_id' => $kuis->id, // Hanya pakai kuis_id
                'pertanyaan' => $item['pertanyaan'],
                'opsi_a' => $item['opsi_a'],
                'opsi_b' => $item['opsi_b'],
                'opsi_c' => $item['opsi_c'],
                'opsi_d' => $item['opsi_d'],
                'jawaban_benar' => $item['jawaban_benar'],
            ]);
        }

        return redirect()->back()->with('success', count($request->soal) . ' Soal berhasil ditambahkan!');
    }

    public function destroySoal($id): RedirectResponse
    {
        Soal::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Soal berhasil dihapus');
    }

    public function updateInline(Request $r,$id){
    $s=Soal::findOrFail($id);
    $s->{$r->field}=$r->value;
    $s->save();
    return response()->json(['message'=>'Berhasil update']);
    }

    public function updateUrutan(Request $r){
        foreach($r->ids as $i=>$id){
            Soal::where('id',$id)->update(['urutan'=>$i+1]);
        }
    }

    // Tambahkan fungsi ini di dalam MateriController.php

    public function updateSoal(Request $request, $id): RedirectResponse
    {
        // 1. Validasi input
        $request->validate([
            'pertanyaan' => 'required|string',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'jawaban_benar' => 'required|in:a,b,c,d',
        ]);

        // 2. Cari soal dan update
        $soal = Soal::findOrFail($id);
        $soal->update([
            'pertanyaan' => $request->pertanyaan,
            'opsi_a' => $request->opsi_a,
            'opsi_b' => $request->opsi_b,
            'opsi_c' => $request->opsi_c,
            'opsi_d' => $request->opsi_d,
            'jawaban_benar' => $request->jawaban_benar,
        ]);

        // 3. Redirect balik dengan pesan sukses (sesuai gambar)
        return redirect()->back()->with('success', 'Soal berhasil diperbarui');
    }

    public function showKuisLogs($id): View
    {
        // Mengambil kuis berdasarkan materi_id yang diklik
        $kuis = Kuis::with(['logs.user'])->where('materi_id', $id)->firstOrFail();
        
        return view('materi.logs-kuis', compact('kuis'));
    }
}