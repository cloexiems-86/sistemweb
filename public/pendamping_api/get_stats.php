<?php
include "../catin_api/koneksi.php";

$id = $_GET['id'];

// Hitung total catin yang didampingi secara individual
$q_couples = mysqli_query($koneksi, "
    SELECT DISTINCT c.id, c.nama_suami, c.nama_istri
    FROM catins c
    JOIN catin_jadwal cj ON c.id = cj.catin_id
    JOIN jadwals j ON cj.jadwal_id = j.id
    WHERE j.pendamping_id = '$id'
");

$total_catin = 0;
$sum_progress = 0;
$count_individus = 0;

// Hitung total materi aktif
$q_total_materi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM materis WHERE status = 'aktif'");
$total_materi = mysqli_fetch_assoc($q_total_materi)['total'] ?? 5;
if ($total_materi == 0) $total_materi = 5; // Fallback

while ($row = mysqli_fetch_assoc($q_couples)) {
    $catin_id = $row['id'];
    
    // Hitung progres suami jika namanya ada
    if (!empty($row['nama_suami'])) {
        $total_catin++;
        $nama_suami = $row['nama_suami'];
        $q_suami = mysqli_query($koneksi, "SELECT COUNT(DISTINCT materi_id) as total FROM materi_logs WHERE catin_id = '$catin_id' AND nama_peserta = '$nama_suami'");
        $prog_suami = mysqli_fetch_assoc($q_suami)['total'] ?? 0;
        $progress_suami = min(round(($prog_suami / $total_materi) * 100), 100);
        $sum_progress += $progress_suami;
        $count_individus++;
    }
    
    // Hitung progres istri jika namanya ada
    if (!empty($row['nama_istri'])) {
        $total_catin++;
        $nama_istri = $row['nama_istri'];
        $q_istri = mysqli_query($koneksi, "SELECT COUNT(DISTINCT materi_id) as total FROM materi_logs WHERE catin_id = '$catin_id' AND nama_peserta = '$nama_istri'");
        $prog_istri = mysqli_fetch_assoc($q_istri)['total'] ?? 0;
        $progress_istri = min(round(($prog_istri / $total_materi) * 100), 100);
        $sum_progress += $progress_istri;
        $count_individus++;
    }
}

$rerata_progres = ($count_individus > 0) ? round($sum_progress / $count_individus) : 0;
$rerata_progres_formatted = $rerata_progres . "%";

// Hitung catin yang aktif (sudah baca materi > 0 di materi_logs)
$q_aktif = mysqli_query($koneksi, "
    SELECT COUNT(DISTINCT catin_id, nama_peserta) as aktif 
    FROM materi_logs 
    WHERE catin_id IN (
        SELECT DISTINCT c.id 
        FROM catins c
        JOIN catin_jadwal cj ON c.id = cj.catin_id
        JOIN jadwals j ON cj.jadwal_id = j.id
        WHERE j.pendamping_id = '$id'
    ) AND nama_peserta IS NOT NULL AND nama_peserta != ''
");
$aktif = mysqli_fetch_assoc($q_aktif)['aktif'] ?? 0;

// Ambil agenda H-7
$hari_ini = date('Y-m-d');
$tujuh_hari_lagi = date('Y-m-d', strtotime('+7 days'));
$q_agenda = mysqli_query($koneksi, "
    SELECT DISTINCT c.nama_suami, c.nama_istri, j.tanggal as tanggal_pendampingan 
    FROM catins c
    JOIN catin_jadwal cj ON c.id = cj.catin_id
    JOIN jadwals j ON cj.jadwal_id = j.id
    WHERE j.pendamping_id = '$id' AND j.tanggal BETWEEN '$hari_ini' AND '$tujuh_hari_lagi'
");

$agenda = [];
if ($q_agenda) {
    while($row = mysqli_fetch_assoc($q_agenda)){
        $agenda[] = [
            'nama_catin' => $row['nama_suami'] . " & " . $row['nama_istri'],
            'tanggal' => $row['tanggal_pendampingan']
        ];
    }
}

echo json_encode([
    "status" => "success",
    "data" => [
        "total_catin" => $total_catin,
        "catin_aktif" => $aktif,
        "rerata_progres" => $rerata_progres_formatted,
        "reminders" => $agenda
    ]
]);
?>
