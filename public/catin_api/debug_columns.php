<?php
include 'koneksi.php';
$table = $_GET['table'] ?? 'soal_ujians';
$result = mysqli_query($koneksi, "DESCRIBE $table");
$columns = [];
while ($row = mysqli_fetch_assoc($result)) {
    $columns[] = $row;
}
header('Content-Type: application/json');
echo json_encode($columns);
?>
