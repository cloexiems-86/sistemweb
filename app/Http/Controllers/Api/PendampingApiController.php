<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Catin;
use App\Models\Pendamping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendampingApiController extends Controller
{
    /**
     * Get daftar Catin yang dibimbing oleh Pendamping
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            // Cari Pendamping berdasarkan user yang login
            $pendamping = Pendamping::where('email', $user->email)
                                    ->orWhere('no_hp', $user->username)
                                    ->first();

            if (!$pendamping) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data pendamping tidak ditemukan'
                ], 404);
            }

            // Ambil semua Catin yang dibimbing oleh Pendamping ini
            $catins = Catin::where('pendamping_id', $pendamping->id)
                           ->select([
                               'id',
                               'nama_suami',
                               'nama_istri',
                               'phone_suami',
                               'phone_istri',
                               'email_suami',
                               'status',
                               'created_at'
                           ])
                           ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data catin berhasil diambil',
                'data' => $catins,
                'total' => count($catins)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simpan Feedback untuk Catin
     */
    public function storeFeedback(Request $request)
    {
        try {
            $validated = $request->validate([
                'catin_id' => 'required|exists:catins,id',
                'feedback' => 'required|string',
                'rating' => 'nullable|integer|min:1|max:5',
            ]);

            $user = $request->user();
            
            // Cari Pendamping berdasarkan user yang login
            $pendamping = Pendamping::where('email', $user->email)
                                    ->orWhere('no_hp', $user->username)
                                    ->first();

            if (!$pendamping) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data pendamping tidak ditemukan'
                ], 404);
            }

            // Cek apakah Catin ini milik Pendamping ini
            $catin = Catin::where('id', $validated['catin_id'])
                          ->where('pendamping_id', $pendamping->id)
                          ->first();

            if (!$catin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses ke catin ini'
                ], 403);
            }

            // TODO: Simpan feedback ke database (buat model FeedbackCatin jika belum ada)
            // FeedbackCatin::create([
            //     'catin_id' => $validated['catin_id'],
            //     'pendamping_id' => $pendamping->id,
            //     'feedback' => $validated['feedback'],
            //     'rating' => $validated['rating'] ?? null,
            // ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Feedback berhasil disimpan'
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
}
