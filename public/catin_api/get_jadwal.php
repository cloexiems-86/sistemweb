<?php
header("Content-Type: application/json; charset=UTF-8");

// Hubungkan ke database (pastikan koneksi.php sudah mengarah ke database 'kuamojo')
require_once 'koneksi.php';

// Menangkap parameter user_id dari Flutter
$userId = isset($_GET['user_id']) ? $_GET['user_id'] : '';

if (empty($userId)) {
    echo json_encode([
        "status" => "error",
        "message" => "User ID (Catin) tidak ditemukan"
    ]);
    exit;
}

/**
 * PENYESUAIAN DENGAN image_566d56.png DAN jadwal_page.dart:
 * - database 'topik' -> flutter 'judul'
 * - database 'sesi'  -> flutter 'jam'
 * - database 'lokasi' -> flutter 'tempat'
 * - database 'fasilitator' -> flutter 'narasumber'
 * - database 'pendamping_id' -> filter berdasarkan user_id (catin)
 */
$sql = "SELECT 
            id, 
            topik AS judul, 
            tanggal, 
            sesi AS jam, 
            lokasi AS tempat, 
            fasilitator AS narasumber, 
            status,
            'Silakan hadir tepat waktu sesuai jadwal yang tertera.' AS deskripsi
        FROM jadwals 
        WHERE pendamping_id = '$userId' 
        ORDER BY tanggal ASC";

$result = mysqli_query($koneksi, $sql);
$daftarJadwal = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $statusDb = strtolower($row['status']);
        $tanggalDb = strtotime($row['tanggal']);
        $sekarang = strtotime(date('Y-m-d'));

        if ($tanggalDb < $sekarang && $statusDb != 'batal') {
            $row['status'] = 'selesai';
        } elseif ($statusDb == 'upcoming') {
            $row['status'] = 'mendatang';
        } elseif ($statusDb == 'completed') {
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
        "message" => "Gagal mengambil data: " . mysqli_error($koneksi)
    ]);
}

mysqli_close($koneksi);
?>