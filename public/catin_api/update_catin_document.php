<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
error_reporting(0);
ini_set('display_errors', 0);

include 'koneksi.php';

if (!$koneksi) {
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal"]);
    exit;
}

$catin_id = isset($_POST['catin_id']) ? intval($_POST['catin_id']) : 0;
$type = $_POST['type'] ?? ''; // KTP atau KK
$nama_aktif = $_POST['nama_aktif'] ?? '';

if ($catin_id == 0 || empty($type)) {
    echo json_encode(["status" => "error", "message" => "Data tidak valid"]);
    exit;
}

// Tentukan kolom mana yang akan diupdate berdasarkan siapa yang login
// Kita asumsikan jika nama_aktif cocok dengan nama_suami, maka itu pihak suami
$q_user = mysqli_query($koneksi, "SELECT * FROM catins WHERE id = $catin_id");
$user = mysqli_fetch_assoc($q_user);

$column = "";
if ($nama_aktif == $user['nama_suami']) {
    $column = ($type == 'KTP') ? 'ktp_suami' : 'kk_suami';
} else {
    $column = ($type == 'KTP') ? 'ktp_istri' : 'kk_istri';
}

// Simpan "Uploaded" sebagai tanda di database (atau path file jika sudah ada upload beneran)
$sql = "UPDATE catins SET $column = 'Uploaded', updated_at = NOW() WHERE id = $catin_id";

if (mysqli_query($koneksi, $sql)) {
    echo json_encode(["status" => "success", "message" => "$type berhasil diperbarui"]);
} else {
    echo json_encode(["status" => "error", "message" => mysqli_error($koneksi)]);
}
?>
