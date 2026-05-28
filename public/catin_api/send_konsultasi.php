<?php
header('Content-Type: application/json');
include "koneksi.php";

$user_id = $_POST['user_id'] ?? '';
$pesan = $_POST['pesan'] ?? '';
$role = $_POST['role'] ?? 'catin';

if (empty($user_id) || empty($pesan)) {
    echo json_encode(["status" => "error", "message" => "Parameter user_id dan pesan wajib diisi!"]);
    exit;
}

// 1. Cari pendamping_id dari jadwal terbaru catin ini
$q_jadwal = mysqli_query($koneksi, "
    SELECT j.pendamping_id 
    FROM catin_jadwal cj
    JOIN jadwals j ON cj.jadwal_id = j.id
    WHERE cj.catin_id = '$user_id'
    ORDER BY j.created_at DESC
    LIMIT 1
");

$pendamping_id = 0;
if ($row = mysqli_fetch_assoc($q_jadwal)) {
    $pendamping_id = $row['pendamping_id'];
}

// Jika belum dijadwalkan dengan pendamping, set pendamping_id default ke pendamping pertama di database
if (empty($pendamping_id)) {
    $q_pendamping = mysqli_query($koneksi, "SELECT id FROM pendamping LIMIT 1");
    if ($p_row = mysqli_fetch_assoc($q_pendamping)) {
        $pendamping_id = $p_row['id'];
    } else {
        echo json_encode(["status" => "error", "message" => "Belum ada pendamping di database."]);
        exit;
    }
}

$now = date('Y-m-d H:i:s');
$query = mysqli_query($koneksi, "
    INSERT INTO konsultasis (catin_id, pendamping_id, pengirim, pesan, is_read, created_at, updated_at) 
    VALUES ('$user_id', '$pendamping_id', '$role', '$pesan', 0, '$now', '$now')
");

if ($query) {
    echo json_encode(["status" => "success", "message" => "Pesan berhasil dikirim"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal mengirim pesan"]);
}
?>
