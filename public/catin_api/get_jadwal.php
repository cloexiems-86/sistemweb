<?php
header("Content-Type: application/json; charset=UTF-8");

// Hubungkan ke database (pastikan koneksi.php sudah mengarah ke database 'kuamojo')
require_once 'koneksi.php';

// Menangkap parameter user_id dari Flutter
$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($userId == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User ID (Catin) tidak ditemukan",
        "data" => []
    ]);
    exit;
}

/**
 * PENYESUAIAN DENGAN TABEL PIVOT LARAVEL:
 * - Menggunakan INNER JOIN ke tabel `catin_jadwal` untuk mencocokkan pendaftaran bimbingan
 * - database 'topik' -> flutter 'judul'
 * - database 'sesi'   -> flutter 'jam'
 * - database 'lokasi' -> flutter 'tempat'
 * - database 'fasilitator' -> flutter 'narasumber'
 */
$sql = "SELECT 
            j.id, 
            j.topik AS judul, 
            j.tanggal, 
            j.sesi AS jam, 
            j.lokasi AS tempat, 
            j.fasilitator AS narasumber, 
            j.status,
            'Silakan hadir tepat waktu sesuai jadwal yang tertera.' AS deskripsi
        FROM jadwals j
        INNER JOIN catin_jadwal cj ON j.id = cj.jadwal_id
        WHERE cj.catin_id = '$userId' 
        ORDER BY j.tanggal ASC";

$result = mysqli_query($koneksi, $sql);
$daftarJadwal = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $statusDb = strtolower($row['status']);
        $tanggalDb = strtotime($row['tanggal']);
        $sekarang = strtotime(date('Y-m-d'));

        // Logika konversi status bawaan kamu tetap dipertahankan aman
        if ($tanggalDb < $sekarang && $statusDb != 'batal') {
            $row['status'] = 'selesai';
        } elseif ($statusDb == 'upcoming' || $statusDb == 'mendatang') {
            $row['status'] = 'mendatang';
        } elseif ($statusDb == 'completed' || $statusDb == 'selesai') {
            $row['status'] = 'selesai';
        }
        
        $daftarJadwal[] = $row;
    }
    
    // Dibungkus dalam key 'data' karena Flutter mengecek: (data['data'] ?? [])
    echo json_encode([
        "status" => "success",
        "data" => $daftarJadwal
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal mengambil data: " . mysqli_error($koneksi),
        "data" => []
    ]);
}

mysqli_close($koneksi);
?>