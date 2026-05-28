<?php
// FIXED VERSION - Error handling + secure + consistent JSON
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

include 'koneksi.php';

$materi_id = isset($_GET['materi_id']) ? intval($_GET['materi_id']) : 0;

if ($materi_id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'materi_id tidak valid',
        'data' => []
    ]);
    exit;
}

// Prepared query untuk security (tapi mysqli_query simple dulu)
$sql = "SELECT s.* FROM soals s 
        JOIN kuis k ON s.kuis_id = k.id 
        WHERE k.materi_id = $materi_id 
        LIMIT 3";  // 3 soal per materi seperti request

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Query gagal: ' . mysqli_error($koneksi),
        'data' => []
    ]);
    exit;
}

$soal_list = [];
while ($row = mysqli_fetch_assoc($query)) {
    $soal_list[] = [
        'id' => (int)$row['id'],
        'pertanyaan' => $row['pertanyaan'] ?? '',
        'opsi_a' => $row['opsi_a'] ?? '',
        'opsi_b' => $row['opsi_b'] ?? '',
        'opsi_c' => $row['opsi_c'] ?? '',
        'opsi_d' => $row['opsi_d'] ?? '',
        'jawaban_benar' => $row['jawaban_benar'] ?? ''
    ];
}

echo json_encode([
    'status' => 'success',
    'message' => count($soal_list) . ' soal ditemukan',
    'data' => $soal_list
]);
?>

