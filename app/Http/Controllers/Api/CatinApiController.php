<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Catin;
use App\Models\Materi;
use App\Models\Ujian;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Pengumuman;

class CatinApiController extends Controller
{
    /**
     * Get Profile Catin
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            
            // Ambil data Catin berdasarkan user
            $catin = Catin::where('username', $user->username)->first();

            if (!$catin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data catin tidak ditemukan'
                ], 404);
            }

            // Ambil data ujian untuk cek kelulusan
            $ujian = Ujian::where('catin_id', $catin->id)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Data profile berhasil diambil',
                'data' => [
                    'id' => $catin->id,
                    'nama_suami' => $catin->nama_suami,
                    'nik_suami' => $catin->nik_suami,
                    'phone_suami' => $catin->phone_suami,
                    'email_suami' => $catin->email_suami,
                    'alamat_suami' => $catin->alamat_suami,
                    'nama_istri' => $catin->nama_istri,
                    'nik_istri' => $catin->nik_istri,
                    'phone_istri' => $catin->phone_istri,
                    'email_istri' => $catin->email_istri,
                    'alamat_istri' => $catin->alamat_istri,
                    'wedding_date' => $catin->wedding_date,
                    'status' => $catin->status,
                    'skor_ujian' => $ujian->skor ?? 0,
                    'status_kelulusan' => $ujian->status_kelulusan ?? 'belum_ujian',
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Profile Catin
     */
    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone_suami' => 'nullable|string',
                'phone_istri' => 'nullable|string',
                'email_suami' => 'nullable|email',
                'email_istri' => 'nullable|email',
                'alamat_suami' => 'nullable|string',
                'alamat_istri' => 'nullable|string',
            ]);

            $user = $request->user();
            $catin = Catin::where('username', $user->username)->first();

            if (!$catin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data catin tidak ditemukan'
                ], 404);
            }

            // Update data
            $catin->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile berhasil diperbarui',
                'data' => $catin
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // /**
    //  * Get Materi (Bahan pembelajaran)
    //  */
    // public function getMateri(Request $request)
    // {
    //     try {
    //         $user = $request->user();
    //         $catin = Catin::where('username', $user->username)->first();

    //         if (!$catin) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Data catin tidak ditemukan'
    //             ], 404);
    //         }

    //         // Ambil semua materi yang aktif
    //         $materis = Materi::where('status', 'active')
    //                           ->orWhere('status', '1')
    //                           ->select(['id', 'judul', 'deskripsi', 'file', 'created_at'])
    //                           ->orderBy('created_at', 'desc')
    //                           ->get();

    //         // Untuk setiap materi, tambahkan info progress belajar
    //         $materis->each(function ($materi) {
    //             // TODO: Implementasi tracking progress belajar jika ada table materi_logs
    //             $materi->progress = 0;
    //             $materi->is_completed = false;
    //         });

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Data materi berhasil diambil',
    //             'data' => $materis,
    //             'total' => count($materis)
    //         ], 200);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function getMateri(Request $request)
    {
        try {
            $user = $request->user();
            // Ambil data catin berdasarkan user yang login
            $catin = \App\Models\Catin::where('username', $user->username)->first();
            
            // Ambil parameter peran (suami/istri) dari request Flutter
            $peran = $request->query('peran'); 

            if (!$catin || !$peran) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data catin atau peran tidak ditemukan'
                ], 404);
            }

            $baseUrl = "https://farel.dwirez.app/materi-display/";

            // Ambil semua materi aktif
            $materis = \App\Models\Materi::where('status', 'aktif')
                ->orderBy('id', 'asc')
                ->get()
                ->map(function ($materi) use ($baseUrl, $catin, $peran) {
                    // Cek apakah materi ini sudah dibaca oleh peran ini di MateriLog
                    $isCompleted = \App\Models\MateriLog::where('catin_id', $catin->id)
                        ->where('materi_id', $materi->id)
                        ->where('peran', $peran) // Membedakan progres suami & istri
                        ->exists();

                    return [
                        'id' => $materi->id,
                        'judul' => $materi->judul,
                        'deskripsi' => $materi->deskripsi,
                        'file_url' => $baseUrl . $materi->id,
                        'is_completed' => $isCompleted, // Digunakan Flutter untuk tanda centang/hijau
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $materis
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

        /**
     * Mengambil daftar materi yang sudah diselesaikan (untuk tanda centang di Flutter)
     */
    public function getMateriLog(Request $request)
    {
        try {
            $user = $request->user();
            $peran = $request->query('peran'); // 'suami' atau 'istri'

            if (!$peran) {
                return response()->json(['status' => 'error', 'message' => 'Parameter peran diperlukan'], 400);
            }

            $catin = \App\Models\Catin::where('username', $user->username)->first();

            // Ambil ID materi saja yang sudah pernah dibaca oleh user & peran tersebut
            $completedMateriIds = \App\Models\MateriLog::where('catin_id', $catin->id)
                ->where('peran', $peran)
                ->pluck('materi_id'); // Menghasilkan array [1, 2, 5]

            return response()->json([
                'status' => 'success',
                'data' => [
                    'completed_ids' => $completedMateriIds,
                    'total_completed' => count($completedMateriIds)
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

        /**
     * Mencatat bahwa materi telah selesai dibaca
     */
    public function updateMateriLog(Request $request)
    {
        try {
            $validated = $request->validate([
                'materi_id' => 'required|exists:materis,id',
                'peran' => 'required|in:suami,istri',
            ]);

            $user = $request->user();
            $catin = \App\Models\Catin::where('username', $user->username)->first();

            // Gunakan updateOrCreate agar tidak ada data ganda untuk materi yang sama
            \App\Models\MateriLog::updateOrCreate(
                [
                    'catin_id' => $catin->id,
                    'materi_id' => $validated['materi_id'],
                    'peran' => $validated['peran']
                ],
                [
                    'updated_at' => now()
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'Progres berhasil disimpan'], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Absensi Catin
     */
    public function absensi(Request $request)
    {
        try {
            $validated = $request->validate([
                'jadwal_id' => 'required|exists:jadwals,id',
                'status' => 'required|in:hadir,alfa,sakit',
            ]);

            $user = $request->user();
            $catin = Catin::where('username', $user->username)->first();

            if (!$catin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data catin tidak ditemukan'
                ], 404);
            }

            // Cek apakah jadwal valid dan ada di catin yang login
            $jadwal = Jadwal::find($validated['jadwal_id']);
            
            if (!$jadwal) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            // Cek atau buat record absensi
            $absensi = Absensi::where('jadwal_id', $validated['jadwal_id'])
                              ->where('user_id', $catin->id)
                              ->first();

            if ($absensi) {
                $absensi->update(['status' => $validated['status']]);
            } else {
                Absensi::create([
                    'user_id' => $catin->id,
                    'jadwal_id' => $validated['jadwal_id'],
                    'status' => $validated['status'],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Absensi berhasil disimpan'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check Progress Pembelajaran
     * Syarat: Skor Ujian > 70 untuk lulus
     */
    public function checkProgres(Request $request)
    {
        try {
            $user = $request->user();
            $catin = Catin::where('username', $user->username)->first();

            if (!$catin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data catin tidak ditemukan'
                ], 404);
            }

            // Ambil data ujian
            $ujian = Ujian::where('catin_id', $catin->id)->first();

            if (!$ujian) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Belum mengikuti ujian',
                    'data' => [
                        'is_passed' => false,
                        'skor' => 0,
                        'status' => 'belum_ujian',
                        'jawaban_benar' => 0,
                        'jawaban_salah' => 0,
                    ]
                ], 200);
            }

            // Tentukan lulus atau tidak (syarat > 70)
            $isPassed = $ujian->skor > 70;

            return response()->json([
                'status' => 'success',
                'message' => $isPassed ? 'Selamat, Anda lulus!' : 'Nilai Anda belum mencukupi',
                'data' => [
                    'is_passed' => $isPassed,
                    'skor' => $ujian->skor,
                    'status' => $isPassed ? 'lulus' : 'belum_lulus',
                    'jawaban_benar' => $ujian->jawaban_benar,
                    'jawaban_salah' => $ujian->jawaban_salah,
                    'status_kelulusan' => $ujian->status_kelulusan,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSoalUjian() {
        // Mengambil 10 soal acak agar suami dan istri mendapat soal berbeda
        $soals = Soal::inRandomOrder()->limit(10)->get();
        return response()->json($soals);
    }

    /**
     * Get Jadwal (Schedule) untuk Catin
     * Menampilkan jadwal bimbingan yang diikuti oleh catin yang login
     */
    public function getJadwal(Request $request)
    {
        try {
            $user = $request->user();
            $catin = Catin::where('username', $user->username)->first();

            if (!$catin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data catin tidak ditemukan'
                ], 404);
            }

            // Ambil jadwal yang terkait dengan catin melalui pivot table
            $jadwals = $catin->jadwals()
                             ->with('pendamping:id,nama_pendamping,phone_pendamping')
                             ->orderBy('tanggal', 'asc')
                             ->get()
                             ->map(function ($jadwal) {
                                 return [
                                     'id' => $jadwal->id,
                                     'tanggal' => $jadwal->tanggal,
                                     'topik' => $jadwal->topik,
                                     'sesi' => $jadwal->sesi,
                                     'fasilitator' => $jadwal->fasilitator,
                                     'status' => $jadwal->status,
                                     'lokasi' => $jadwal->lokasi,
                                     'pendamping' => $jadwal->pendamping ? [
                                         'id' => $jadwal->pendamping->id,
                                         'nama' => $jadwal->pendamping->nama_pendamping,
                                         'phone' => $jadwal->pendamping->phone_pendamping,
                                     ] : null,
                                     'created_at' => $jadwal->created_at,
                                     'updated_at' => $jadwal->updated_at,
                                 ];
                             });

            return response()->json([
                'status' => 'success',
                'message' => 'Data jadwal berhasil diambil',
                'data' => $jadwals,
                'total' => count($jadwals)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPengumuman(Request $request)
    {
        $user = $request->user();
        // Cari data catin yang sedang login
        $catin = \App\Models\Catin::where('username', $user->username)->first();

        $data = \App\Models\Pengumuman::where(function($query) use ($catin) {
                    $query->where('target_peran', 'catin') // Pengumuman umum untuk semua catin
                        ->orWhere('target_peran', 'semua')
                        ->orWhere('catin_id', $catin->id); // Pengumuman khusus untuk dia saja
                })
                ->orderBy('created_at', 'desc')
                ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

        public function getKonsultasi(Request $request) {
        $user = $request->user();
        $catin = \App\Models\Catin::where('username', $user->username)->first();

        $chat = \App\Models\Konsultasi::where('catin_id', $catin->id)
                ->orderBy('created_at', 'asc')
                ->get();

        return response()->json(['status' => 'success', 'data' => $chat]);
    }

        public function kirimKonsultasi(Request $request) {
        $user = $request->user();
        $catin = \App\Models\Catin::where('username', $user->username)->first();

        // 1. Cari jadwal terbaru yang diikuti oleh Catin ini
        $jadwalUser = \DB::table('catin_jadwal')
            ->join('jadwals', 'catin_jadwal.jadwal_id', '=', 'jadwals.id')
            ->where('catin_jadwal.catin_id', $catin->id)
            ->select('jadwals.pendamping_id')
            ->latest('jadwals.created_at')
            ->first();

        if (!$jadwalUser || !$jadwalUser->pendamping_id) {
            return response()->json(['message' => 'Anda belum dijadwalkan dengan pendamping'], 403);
        }

        // 2. Simpan pesan dengan pendamping_id yang didapat dari jadwal
        \App\Models\Konsultasi::create([
            'catin_id' => $catin->id,
            'pendamping_id' => $jadwalUser->pendamping_id,
            'pengirim' => 'catin',
            'pesan' => $request->pesan,
        ]);

        return response()->json(['status' => 'success']);
    }

    
} 