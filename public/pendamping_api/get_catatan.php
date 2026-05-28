<?php
header('Content-Type: application/json');
include "../catin_api/koneksi.php";

$catin_id = $_GET['catin_id'] ?? '';
$pendamping_id = $_GET['pendamping_id'] ?? '';

if (empty($catin_id) || empty($pendamping_id)) {
    echo json_encode(["status" => "error", "message" => "Parameter catin_id dan pendamping_id wajib diisi!"]);
    exit;
}

$query = mysqli_query($koneksi, "
    SELECT * FROM konsultasis 
    WHERE catin_id = '$catin_id' AND pendamping_id = '$pendamping_id'
    ORDER BY created_at DESC
");

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
