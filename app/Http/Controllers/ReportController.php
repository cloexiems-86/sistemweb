<?php

namespace App\Http\Controllers;

use App\Models\Catin;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Catin::with(['pendamping', 'ujianSuami', 'ujianIstri']);

        // Filter by Status Akun
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Tanggal Registrasi
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Filter by Status Ujian
        if ($request->filled('status_ujian')) {
            if ($request->status_ujian == 'lulus') {
                $query->whereHas('ujians', function ($q) {
                    $q->where('status_kelulusan', 'lulus');
                });
            } elseif ($request->status_ujian == 'tidak_lulus') {
                $query->whereHas('ujians', function ($q) {
                    $q->where('status_kelulusan', 'tidak_lulus');
                });
            } elseif ($request->status_ujian == 'belum') {
                $query->doesntHave('ujians');
            }
        }

        // Pagination
        $catins = $query->latest()->paginate(15)->withQueryString();

        // Untuk dropdown filter
        $daftarDesa = [
            'Blimbing', 'Jugo', 'Kedawung', 'Keniten', 'Kranding',
            'Kraton', 'Maesan', 'Mlati', 'Mojo', 'Mondo',
            'Ngadi', 'Ngetrep', 'Pamongan', 'Petok', 'Petungroto',
            'Ploso', 'Ponggok', 'Sukoanyar', 'Surat', 'Tambibendo'
        ];

        return view('laporan.index', compact('catins', 'daftarDesa'));
    }

    public function export(Request $request)
    {
        $query = Catin::with(['pendamping', 'ujianSuami', 'ujianIstri']);

        // Apply same filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('status_ujian')) {
            if ($request->status_ujian == 'lulus') {
                $query->whereHas('ujians', function ($q) {
                    $q->where('status_kelulusan', 'lulus');
                });
            } elseif ($request->status_ujian == 'tidak_lulus') {
                $query->whereHas('ujians', function ($q) {
                    $q->where('status_kelulusan', 'tidak_lulus');
                });
            } elseif ($request->status_ujian == 'belum') {
                $query->doesntHave('ujians');
            }
        }

        $catins = $query->latest()->get();

        // Generate CSV
        $filename = 'laporan_catin_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://memory', 'r+');

        // Header
        fputcsv($handle, [
            'No', 'Nama Peserta', 'Peran', 'NIK', 'Email',
            'No WA', 'Alamat', 'Username', 'Status Akun', 'Skor',
            'Status Ujian', 'Tanggal Daftar'
        ]);

        // Data
        $no = 1;
        foreach ($catins as $catin) {
            $rows = [
                [
                    'name' => $catin->nama_suami,
                    'role' => 'Suami',
                    'nik' => $catin->nik_suami,
                    'email' => $catin->email_suami,
                    'phone' => $catin->phone_suami,
                    'alamat' => $catin->alamat_suami,
                    'score' => $catin->ujianSuami?->skor,
                    'status' => $catin->ujianSuami ? ucfirst(str_replace('_', ' ', $catin->ujianSuami->status_kelulusan)) : 'Belum',
                ],
                [
                    'name' => $catin->nama_istri,
                    'role' => 'Istri',
                    'nik' => $catin->nik_istri,
                    'email' => $catin->email_istri,
                    'phone' => $catin->phone_istri,
                    'alamat' => $catin->alamat_istri,
                    'score' => $catin->ujianIstri?->skor,
                    'status' => $catin->ujianIstri ? ucfirst(str_replace('_', ' ', $catin->ujianIstri->status_kelulusan)) : 'Belum',
                ],
            ];

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $no++,
                    $row['name'],
                    $row['role'],
                    $row['nik'],
                    $row['email'],
                    $row['phone'],
                    $row['alamat'],
                    $catin->username,
                    ucfirst($catin->status),
                    $row['score'] !== null ? $row['score'] : '-',
                    $row['status'],
                    $catin->created_at->format('d/m/Y')
                ]);
            }
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }
}
