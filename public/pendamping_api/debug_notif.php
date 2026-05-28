<?php
include __DIR__ . '/../catin_api/koneksi.php';

echo "=== PENGUMUMANS ===\n";
$q = mysqli_query($koneksi, "SELECT * FROM pengumumans");
if (!$q) {
    echo "Query failed: " . mysqli_error($koneksi) . "\n";
} else {
    while($r = mysqli_fetch_assoc($q)) {
        print_r($r);
    }
}
?>
