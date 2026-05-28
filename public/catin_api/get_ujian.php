<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

include 'koneksi.php';

// Ambil 10 soal ujian secara random
$query = "SELECT * FROM soal_ujians ORDER BY RAND() LIMIT 10";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal mengambil soal: " . mysqli_error($koneksi)
    ]);
    exit;
}

$soal = [];
while ($row = mysqli_fetch_assoc($result)) {
    $soal[] = [
        'id' => $row['id'],
        'pertanyaan' => $row['pertanyaan'],
        'opsi_a' => $row['pil_a'],
        'opsi_b' => $row['pil_b'],
        'opsi_c' => $row['pil_c'],
        'opsi_d' => $row['pil_d'],
        'jawaban_benar' => $row['kunci_jawaban']
    ];
}

// Kembalikan dalam format list yang bersih
echo json_encode($soal);
?>
