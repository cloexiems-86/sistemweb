<?php
include __DIR__ . '/../catin_api/koneksi.php';

$q = mysqli_query($koneksi, "ALTER TABLE catins ADD COLUMN catatan_pendamping TEXT NULL");
if ($q) {
    echo "SUCCESS: Added column catatan_pendamping to catins table!\n";
} else {
    echo "FAILED: " . mysqli_error($koneksi) . "\n";
}
?>
