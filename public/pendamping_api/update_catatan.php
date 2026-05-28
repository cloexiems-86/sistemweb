<?php
header('Content-Type: application/json');
include "../catin_api/koneksi.php";

$catin_id = $_POST['catin_id'] ?? '';
$pendamping_id = $_POST['pendamping_id'] ?? '';
$catatan = $_POST['catatan'] ?? '';

if (empty($catin_id) || empty($catatan)) {
    echo json_encode(["status" => "error", "message" => "Parameter catin_id dan catatan wajib diisi!"]);
    exit;
}

// Fallback jika pendamping_id tidak dikirim
if (empty($pendamping_id)) {
    // Ambil pendamping_id dari jadwal terbaru catin ini
    $q_jadwal = mysqli_query($koneksi, "
        SELECT j.pendamping_id 
        FROM catin_jadwal cj
        JOIN jadwals j ON cj.jadwal_id = j.id
        WHERE cj.catin_id = '$catin_id'
        ORDER BY j.created_at DESC
        LIMIT 1
    ");
    if ($row = mysqli_fetch_assoc($q_jadwal)) {
        $pendamping_id = $row['pendamping_id'];
    }
}

if (empty($pendamping_id)) {
    $q_p = mysqli_query($koneksi, "SELECT id FROM pendamping LIMIT 1");
    if ($row_p = mysqli_fetch_assoc($q_p)) {
        $pendamping_id = $row_p['id'];
    }
}

// 1. Tetap simpan ke catatan_pendamping di tabel catins untuk kompatibilitas
$query_catin = mysqli_query($koneksi, "UPDATE catins SET catatan_pendamping = '$catatan' WHERE id = '$catin_id'");

// 2. Simpan ke tabel konsultasis sebagai pesan dari pendamping
$now = date('Y-m-d H:i:s');
$query_konsul = mysqli_query($koneksi, "
    INSERT INTO konsultasis (catin_id, pendamping_id, pengirim, pesan, is_read, created_at, updated_at) 
    VALUES ('$catin_id', '$pendamping_id', 'pendamping', '$catatan', 0, '$now', '$now')
");

if ($query_konsul) {
    echo json_encode(["status" => "success", "message" => "Catatan berhasil disimpan ke chat dan database"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyimpan ke database"]);
}
?>
