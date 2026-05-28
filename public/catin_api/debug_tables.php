<?php
include 'koneksi.php';
$result = mysqli_query($koneksi, "SHOW TABLES");
$tables = [];
while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}
header('Content-Type: application/json');
echo json_encode($tables);
?>
