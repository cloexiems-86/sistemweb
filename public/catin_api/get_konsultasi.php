<?php
header('Content-Type: application/json');
include "koneksi.php";

$user_id = $_GET['user_id'] ?? '';

if (empty($user_id)) {
    echo json_encode(["status" => "error", "message" => "Parameter user_id wajib diisi!"]);
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM konsultasis WHERE catin_id = '$user_id' ORDER BY created_at DESC");
$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = [
        'id' => (int) $row['id'],
        'catin_id' => (int) $row['catin_id'],
        'pendamping_id' => (int) $row['pendamping_id'],
        'pengirim' => $row['pengirim'],
        'pesan' => $row['pesan'],
        'waktu' => $row['created_at'],
        'is_read' => (int) $row['is_read']
    ];
}

echo json_encode(["status" => "success", "data" => $data]);
?>
