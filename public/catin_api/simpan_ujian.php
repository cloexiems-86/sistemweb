<?php
header('Content-Type: application/json');
include 'koneksi.php';

$user_id = intval($_POST['user_id']);
$nama_peserta = isset($_POST['nama_peserta']) ? mysqli_real_escape_string($koneksi, $_POST['nama_peserta']) : 'User';
$person = isset($_POST['person']) ? mysqli_real_escape_string($koneksi, $_POST['person']) : null;
$jawaban = $_POST['jawaban']; // JSON string of answers
$skor = 0;

// Hitung soal yang benar dari database
$jawabanArray = json_decode($jawaban, true);
$benar = 0;
$total = count($jawabanArray);

foreach ($jawabanArray as $id => $jawabanUser) {
    // Gunakan ID soal yang dikirim dari mobile
    $query = "SELECT kunci_jawaban FROM soal_ujians WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        if (strtolower($row['kunci_jawaban']) == strtolower($jawabanUser)) {
            $benar++;
        }
    }
}

// Hitung skor (persentase)
$skor = ($total > 0) ? round(($benar / $total) * 100) : 0;
$salah = $total - $benar;
$status = ($skor >= 70) ? 'lulus' : 'tidak_lulus';

// Simpan ke tabel ujian
$columns = ['catin_id', 'nama_peserta', 'skor', 'jawaban_benar', 'jawaban_salah', 'status_kelulusan'];
$values = [
    $user_id,
    "'$nama_peserta'",
    $skor,
    $benar,
    $salah,
    "'$status'"
];

// Jika kolom person ada di tabel ujian, tambahkan juga
$personColumn = false;
$info = mysqli_query($koneksi, "SHOW COLUMNS FROM ujian LIKE 'person'");
if ($info && mysqli_num_rows($info) > 0 && $person) {
    $personColumn = true;
    $columns[] = 'person';
    $values[] = "'$person'";
}

$columns[] = 'created_at';
$columns[] = 'updated_at';
$values[] = 'NOW()';
$values[] = 'NOW()';

$query = "INSERT INTO ujian (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";

if (mysqli_query($koneksi, $query)) {
    echo json_encode([
        "status" => "success", 
        "skor" => $skor, 
        "benar" => $benar, 
        "total" => $total,
        "lulus" => $status
    ]);
} else {
    echo json_encode(["status" => "error", "message" => mysqli_error($koneksi)]);
}
?>
