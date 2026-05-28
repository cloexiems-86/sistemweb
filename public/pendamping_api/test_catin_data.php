<?php
include __DIR__ . '/../catin_api/koneksi.php';

function checkData($koneksi, $catin_id, $nama, $peran) {
    echo "=== DATA FOR $nama ($peran, ID: $catin_id) ===\n";
    
    // Materi logs
    echo "Materi Logs:\n";
    $q1 = mysqli_query($koneksi, "SELECT ml.*, m.judul FROM materi_logs ml JOIN materis m ON ml.materi_id = m.id WHERE ml.catin_id = '$catin_id' AND ml.nama_peserta = '$nama'");
    while($r1 = mysqli_fetch_assoc($q1)) {
        print_r($r1);
    }
    
    // Kuis logs
    echo "Kuis Logs:\n";
    $q2 = mysqli_query($koneksi, "SELECT * FROM kuis_logs WHERE catin_id = '$catin_id' AND nama_peserta = '$nama'");
    while($r2 = mysqli_fetch_assoc($q2)) {
        print_r($r2);
    }
    
    // Ujian
    echo "Ujian:\n";
    $q3 = mysqli_query($koneksi, "SELECT * FROM ujian WHERE catin_id = '$catin_id' AND nama_peserta = '$nama'");
    while($r3 = mysqli_fetch_assoc($q3)) {
        print_r($r3);
    }
    
    echo "\n";
}

checkData($koneksi, 2, 'Ciko Kurniawan', 'suami');
checkData($koneksi, 2, 'Puri Wahyuni', 'istri');
?>
