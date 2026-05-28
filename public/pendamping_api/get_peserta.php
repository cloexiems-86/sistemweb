<?php
include "../catin_api/koneksi.php";

$id = $_GET['pendamping_id'];

// Ambil catin yang bimbingan dengan pendamping ini lewat tabel jadwals & catin_jadwal
$query = mysqli_query($koneksi, "
    SELECT DISTINCT c.* 
    FROM catins c
    JOIN catin_jadwal cj ON c.id = cj.catin_id
    JOIN jadwals j ON cj.jadwal_id = j.id
    WHERE j.pendamping_id = '$id'
");
$data = [];

// Hitung total materi aktif
$q_total_materi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM materis WHERE status = 'aktif'");
$total_materi = mysqli_fetch_assoc($q_total_materi)['total'] ?? 5;
if ($total_materi == 0) $total_materi = 5; // Fallback

while($row = mysqli_fetch_assoc($query)){
    $catin_id = $row['id'];
    
    // Hitung progress suami secara dinamis dari database
    $nama_suami = $row['nama_suami'];
    $q_suami = mysqli_query($koneksi, "SELECT COUNT(DISTINCT materi_id) as total FROM materi_logs WHERE catin_id = '$catin_id' AND nama_peserta = '$nama_suami'");
    $prog_suami = mysqli_fetch_assoc($q_suami)['total'] ?? 0;
    $row['progress_suami'] = (int) min(round(($prog_suami / $total_materi) * 100), 100);
    
    // Hitung progress istri secara dinamis dari database
    $nama_istri = $row['nama_istri'];
    $q_istri = mysqli_query($koneksi, "SELECT COUNT(DISTINCT materi_id) as total FROM materi_logs WHERE catin_id = '$catin_id' AND nama_peserta = '$nama_istri'");
    $prog_istri = mysqli_fetch_assoc($q_istri)['total'] ?? 0;
    $row['progress_istri'] = (int) min(round(($prog_istri / $total_materi) * 100), 100);
    
    $data[] = $row;
}

echo json_encode(["status" => "success", "data" => $data]);
?>
