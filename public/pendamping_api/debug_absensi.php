<?php
include __DIR__ . '/../catin_api/koneksi.php';

echo "=== DESCRIBE absensis ===\n";
$q = mysqli_query($koneksi, "DESCRIBE absensis");
while($r = mysqli_fetch_assoc($q)) {
    print_r($r);
}

echo "\n=== ABSENSIS ROWS ===\n";
$q2 = mysqli_query($koneksi, "SELECT * FROM absensis");
while($r2 = mysqli_fetch_assoc($q2)) {
    print_r($r2);
}
?>
