<?php
include 'koneksi.php';
$table = $_GET['table'] ?? 'ujian';
$limit = $_GET['limit'] ?? 10;
$result = mysqli_query($koneksi, "SELECT * FROM $table ORDER BY id DESC LIMIT $limit");
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
header('Content-Type: application/json');
echo json_encode($data);
?>
