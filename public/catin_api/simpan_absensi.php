<?php
// Menahan semua peringatan/error PHP agar tidak merusak format JSON
ob_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

error_reporting(0);
ini_set('display_errors', 0);

include_once 'koneksi.php';

// Ambil data JSON yang dikirim dari Flutter
$data = json_decode(file_get_contents("php://input"), true);

$user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;
$jadwal_id = isset($data['jadwal_id']) ? intval($data['jadwal_id']) : 0;

// Bersihkan output apapun yang mendahului JSON
ob_clean();

if ($user_id == 0 || $jadwal_id == 0) {
    echo json_encode(["status" => "error", "message" => "Gagal! Data ID (Catin / Jadwal) tidak lengkap."]);
    exit;
}

// 1. CEK KETERSEDIAAN JADWAL & STATUS
$q_jadwal = mysqli_query($koneksi, "SELECT tanggal, status FROM jadwals WHERE id = $jadwal_id");
if (mysqli_num_rows($q_jadwal) == 0) {
    echo json_encode(["status" => "error", "message" => "Gagal! Jadwal bimbingan tidak ditemukan di sistem."]);
    exit;
}
$jadwal = mysqli_fetch_assoc($q_jadwal);
$statusJadwal = strtolower($jadwal['status']);

if ($statusJadwal == 'completed' || $statusJadwal == 'selesai' || $statusJadwal == 'batal') {
    echo json_encode(["status" => "error", "message" => "Gagal! Sesi bimbingan ini sudah ditutup oleh Admin KUA."]);
    exit;
}

// 2. VALIDASI WAKTU SERVER NYATA
$tanggal_jadwal = $jadwal['tanggal'];
date_default_timezone_set('Asia/Jakarta');
$sekarang = date('Y-m-d');

if ($sekarang > $tanggal_jadwal) {
    echo json_encode(["status" => "error", "message" => "Gagal! Waktu absensi untuk tanggal ini sudah terlewat."]);
    exit;
}

// 3. CEK DUPLIKASI ABSEN
$q_cek_absen = mysqli_query($koneksi, "SELECT id FROM absensis WHERE user_id = $user_id AND jadwal_id = $jadwal_id");
if (mysqli_num_rows($q_cek_absen) > 0) {
    echo json_encode(["status" => "error", "message" => "Peringatan! Anda sudah melakukan absensi kehadiran untuk sesi ini."]);
    exit;
}

// 4. SIMPAN KE DATABASE (Tabel absensis dengan waktu_hadir!)
$waktu_sekarang = date('Y-m-d H:i:s');

$sql_insert = "INSERT INTO absensis (user_id, jadwal_id, status, waktu_hadir, created_at, updated_at) 
               VALUES ($user_id, $jadwal_id, 'hadir', '$waktu_sekarang', '$waktu_sekarang', '$waktu_sekarang')";
$q_insert = mysqli_query($koneksi, $sql_insert);

if ($q_insert) {
    echo json_encode(["status" => "success", "message" => "Berhasil absen! Kehadiran Anda telah dicatat di sistem."]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyimpan absensi ke database."]);
}
?>