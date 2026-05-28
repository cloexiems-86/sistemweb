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
$nama_peserta = isset($_REQUEST['nama_peserta']) ? mysqli_real_escape_string($koneksi, trim($_REQUEST['nama_peserta'])) : '';

if ($catin_id == 0 || $materi_id == 0) {
    kirimRespon("error", "Data tidak lengkap");
}

if (empty($nama_peserta)) {
    // Cari nama_suami / nama_istri jika peran dikirim
    $peran = isset($_REQUEST['peran']) ? strtolower(trim($_REQUEST['peran'])) : '';
    $cekCatin = mysqli_query($koneksi, "SELECT nama_suami, nama_istri FROM catins WHERE id = $catin_id LIMIT 1");
    if ($row = mysqli_fetch_assoc($cekCatin)) {
        if ($peran === 'suami') {
            $nama_peserta = $row['nama_suami'];
        } elseif ($peran === 'istri') {
            $nama_peserta = $row['nama_istri'];
        } else {
            // Default ke suami
            $nama_peserta = $row['nama_suami'];
        }
    }
}

// Cek apakah sudah pernah dicatat untuk nama_peserta ini
$check = mysqli_query($koneksi, "SELECT id FROM materi_logs WHERE catin_id = $catin_id AND materi_id = $materi_id AND nama_peserta = '$nama_peserta'");

if (mysqli_num_rows($check) == 0) {
    $query = "INSERT INTO materi_logs (catin_id, materi_id, nama_peserta, accessed_at) 
              VALUES ($catin_id, $materi_id, '$nama_peserta', NOW())";
    
    if (mysqli_query($koneksi, $query)) {
        kirimRespon("success", "Progress belajar tersimpan");
    } else {
        kirimRespon("error", "Gagal menyimpan progress: " . mysqli_error($koneksi));
    }
} else {
    kirimRespon("success", "Sudah tercatat sebelumnya");
}
?>