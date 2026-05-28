<?php
include __DIR__ . '/../catin_api/koneksi.php';

echo "=== PENDAMPINGS ===\n";
$q = mysqli_query($koneksi, "SELECT id, nama, email FROM pendamping");
while($r = mysqli_fetch_assoc($q)) {
    print_r($r);
}

echo "\n=== JADWALS ===\n";
$q = mysqli_query($koneksi, "SELECT id, topik, pendamping_id FROM jadwals");
while($r = mysqli_fetch_assoc($q)) {
    print_r($r);
}

echo "\n=== CATIN_JADWAL ===\n";
$q = mysqli_query($koneksi, "SELECT * FROM catin_jadwal");
while($r = mysqli_fetch_assoc($q)) {
    print_r($r);
}

echo "\n=== JOIN QUERY FOR ALL PENDAMPINGS ===\n";
$qp = mysqli_query($koneksi, "SELECT id, nama FROM pendamping");
while($p = mysqli_fetch_assoc($qp)) {
    $pid = $p['id'];
    echo "Catin for {$p['nama']} (ID: $pid):\n";
    $qc = mysqli_query($koneksi, "
        SELECT c.id, c.nama_suami, c.nama_istri 
        FROM catins c
        JOIN catin_jadwal cj ON c.id = cj.catin_id
        JOIN jadwals j ON cj.jadwal_id = j.id
        WHERE j.pendamping_id = '$pid'
    ");
    while($c = mysqli_fetch_assoc($qc)) {
        print_r($c);
    }
}
?>
