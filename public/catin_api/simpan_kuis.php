<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
error_reporting(0);
ini_set('display_errors', 0);

include 'koneksi.php';

function kirimRespon($status, $pesan) {
    echo json_encode(["status" => $status, "message" => $pesan]);
    exit;
}

if (!$koneksi) {
    kirimRespon("error", "Database tidak terhubung");
}

$catin_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
$materi_id = isset($_REQUEST['materi_id']) ? intval($_REQUEST['materi_id']) : 0;
$skor = isset($_REQUEST['skor']) ? intval($_REQUEST['skor']) : 0;
$nama_peserta = isset($_REQUEST['nama_peserta']) ? mysqli_real_escape_string($koneksi, trim($_REQUEST['nama_peserta'])) : '';
$peran = isset($_REQUEST['peran']) ? strtolower(trim($_REQUEST['peran'])) : '';

if ($catin_id == 0 || $materi_id == 0) {
    kirimRespon("error", "Data tidak valid: user_id=$catin_id, materi_id=$materi_id");
}

// 1. Cari kuis_id
$q_kuis = mysqli_query($koneksi, "SELECT id FROM kuis WHERE materi_id = $materi_id LIMIT 1");
$d_kuis = mysqli_fetch_assoc($q_kuis);
if (!$d_kuis) {
    kirimRespon("error", "Soal kuis untuk materi ini belum ada di database admin");
}
$kuis_id = $d_kuis['id'];

// Auto-detect nama jika kosong tapi peran dikirim
if (empty($nama_peserta)) {
    $q_catin = mysqli_query($koneksi, "SELECT nama_suami, nama_istri FROM catins WHERE id = $catin_id LIMIT 1");
    $d_catin = mysqli_fetch_assoc($q_catin);
    if ($d_catin) {
        $nama_peserta = ($peran === 'istri') ? $d_catin['nama_istri'] : $d_catin['nama_suami'];
    }
}

// Auto-detect peran jika kosong tapi nama dikirim
$allowedPeran = ['suami', 'istri'];
if (!in_array($peran, $allowedPeran, true) && !empty($nama_peserta)) {
    $q_catin = mysqli_query($koneksi, "SELECT nama_suami, nama_istri FROM catins WHERE id = $catin_id LIMIT 1");
    $d_catin = mysqli_fetch_assoc($q_catin);
    if ($d_catin) {
        if (strtolower($d_catin['nama_suami']) === strtolower($nama_peserta)) {
            $peran = 'suami';
        } elseif (strtolower($d_catin['nama_istri']) === strtolower($nama_peserta)) {
            $peran = 'istri';
        }
    }
}
if (!in_array($peran, $allowedPeran, true)) {
    $peran = 'suami';
}

$nama_peserta_sql = mysqli_real_escape_string($koneksi, $nama_peserta);

mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS=0");

// Cek apakah sudah pernah ada log kuis ini untuk INDIVIDU ini
$cek = mysqli_query($koneksi, "SELECT id FROM kuis_logs WHERE catin_id = $catin_id AND kuis_id = $kuis_id AND nama_peserta = '$nama_peserta_sql'");

if (mysqli_num_rows($cek) > 0) {
    $sql = "UPDATE kuis_logs SET nilai = $skor, updated_at = NOW() WHERE catin_id = $catin_id AND kuis_id = $kuis_id AND nama_peserta = '$nama_peserta_sql'";
} else {
    $sql = "INSERT INTO kuis_logs (catin_id, kuis_id, nilai, nama_peserta, created_at, updated_at) 
            VALUES ($catin_id, $kuis_id, $skor, '$nama_peserta_sql', NOW(), NOW())";
}

if (mysqli_query($koneksi, $sql)) {
    // Pastikan materi_logs juga terisi dengan data yang benar
    mysqli_query($koneksi, "INSERT IGNORE INTO materi_logs (catin_id, materi_id, nama_peserta, accessed_at) 
                            VALUES ($catin_id, $materi_id, '$nama_peserta_sql', NOW())");
                            
    mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS=1");
    kirimRespon("success", "Progres kuis berhasil disimpan");
} else {
    $msg = mysqli_error($koneksi);
    mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS=1");
    kirimRespon("error", "Database error: " . $msg);
}
?>