<?php
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');
include 'koneksi.php';

$catin_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$baseUrl = "https://farel.dwirez.app/materi-display/"; 

// Ambil semua materi aktif
$sql = "SELECT id, judul, deskripsi, file FROM materis WHERE status = 'aktif' ORDER BY id ASC";
$query = mysqli_query($koneksi, $sql);
$all_materi = [];

while ($row = mysqli_fetch_assoc($query)) {
    $row['file_url'] = $baseUrl . $row['id'];
    $m_id = $row['id'];
    
    // 1. Cek log materi (Sudah dibuka/dibaca)
    $cek_materi = mysqli_query($koneksi, "SELECT id FROM materi_logs WHERE catin_id = $catin_id AND materi_id = $m_id");
    $is_baca = mysqli_num_rows($cek_materi) > 0;
    
    // 2. Cek log kuis (Sudah dikerjakan)
    // Kita cek apakah ada kuis_logs untuk kuis yang terhubung dengan materi_id ini
    $sql_kuis_log = "SELECT kl.id FROM kuis_logs kl 
                     JOIN kuis k ON kl.kuis_id = k.id 
                     WHERE kl.catin_id = $catin_id AND k.materi_id = $m_id";
    $cek_kuis = mysqli_query($koneksi, $sql_kuis_log);
    $is_kuis = mysqli_num_rows($cek_kuis) > 0;
    
    // Materi dianggap SELESAI jika KUIS sudah dikerjakan (is_kuis = true)
    // is_baca tetap dicatat sebagai statistik tambahan
    $row['is_selesai'] = $is_kuis; 
    $all_materi[] = $row;
}

// LOGIKA LOCKING
$result = [];
$prev_selesai = true;

foreach ($all_materi as $materi) {
    $materi['is_locked'] = !$prev_selesai;
    $prev_selesai = $materi['is_selesai'];
    $result[] = $materi;
}

echo json_encode([
    'status' => 'success',
    'data' => $result
]);
?>