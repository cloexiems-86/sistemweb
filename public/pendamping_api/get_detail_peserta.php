<?php
header('Content-Type: application/json');
include "../catin_api/koneksi.php";

$catin_id = $_GET['catin_id'] ?? '';
$nama = $_GET['nama'] ?? '';
$peran = $_GET['peran'] ?? '';

if (empty($catin_id) || empty($nama)) {
    echo json_encode(["status" => "error", "message" => "Parameter catin_id dan nama wajib diisi!"]);
    exit;
}

// 1. Ambil Profil Detail dari catins
$q_catin = mysqli_query($koneksi, "SELECT * FROM catins WHERE id = '$catin_id'");
$catin = mysqli_fetch_assoc($q_catin);

if (!$catin) {
    echo json_encode(["status" => "error", "message" => "Data Catin tidak ditemukan!"]);
    exit;
}

// Filter profil sesuai suami/istri
$detail_profil = [];
if (strtolower($peran) === 'suami') {
    $detail_profil = [
        'nama' => $catin['nama_suami'],
        'nik' => $catin['nik_suami'] ?? 'Belum Diisi',
        'phone' => $catin['phone_suami'] ?? 'Belum Diisi',
        'email' => $catin['email_suami'] ?? 'Belum Diisi',
        'alamat' => $catin['alamat_suami'] ?? 'Belum Diisi',
        'ktp' => $catin['ktp_suami'] ?? '',
        'kk' => $catin['kk_suami'] ?? '',
        'wedding_date' => $catin['wedding_date'] ?? 'Belum Diisi',
        'peran' => 'Suami'
    ];
} else {
    $detail_profil = [
        'nama' => $catin['nama_istri'],
        'nik' => $catin['nik_istri'] ?? 'Belum Diisi',
        'phone' => $catin['phone_istri'] ?? 'Belum Diisi',
        'email' => $catin['email_istri'] ?? 'Belum Diisi',
        'alamat' => $catin['alamat_istri'] ?? 'Belum Diisi',
        'ktp' => $catin['ktp_istri'] ?? '',
        'kk' => $catin['kk_istri'] ?? '',
        'wedding_date' => $catin['wedding_date'] ?? 'Belum Diisi',
        'peran' => 'Istri'
    ];
}

// 2. Hitung Progress secara dinamis
$q_total_materi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM materis WHERE status = 'aktif'");
$total_materi = mysqli_fetch_assoc($q_total_materi)['total'] ?? 10;
if ($total_materi == 0) $total_materi = 10; // Fallback

$q_materi_selesai = mysqli_query($koneksi, "SELECT COUNT(DISTINCT materi_id) as total FROM materi_logs WHERE catin_id = '$catin_id' AND nama_peserta = '$nama'");
$materi_selesai = mysqli_fetch_assoc($q_materi_selesai)['total'] ?? 0;

$progress = (int) min(round(($materi_selesai / $total_materi) * 100), 100);

// 3. Ambil List Materi yang Selesai & Sedang Dipelajari
$materi_list = [];
$q_logs = mysqli_query($koneksi, "
    SELECT m.judul, ml.accessed_at 
    FROM materi_logs ml 
    JOIN materis m ON ml.materi_id = m.id 
    WHERE ml.catin_id = '$catin_id' AND ml.nama_peserta = '$nama'
    ORDER BY ml.accessed_at DESC
");
while ($r = mysqli_fetch_assoc($q_logs)) {
    $materi_list[] = [
        'judul' => $r['judul'],
        'status' => 'Selesai',
        'waktu' => date('d M Y', strtotime($r['accessed_at']))
    ];
}

// Ambil materi yang belum dibaca
$q_unread = mysqli_query($koneksi, "
    SELECT judul FROM materis 
    WHERE status = 'aktif' AND id NOT IN (
        SELECT DISTINCT materi_id FROM materi_logs WHERE catin_id = '$catin_id' AND nama_peserta = '$nama'
    )
");
while ($r = mysqli_fetch_assoc($q_unread)) {
    $materi_list[] = [
        'judul' => $r['judul'],
        'status' => 'Belum Dibaca',
        'waktu' => 'Belum dimulai'
    ];
}

// 4. Ambil Ujian / Evaluasi Scores
$ujian_list = [];
$q_ujian = mysqli_query($koneksi, "
    SELECT skor, jawaban_benar, jawaban_salah, status_kelulusan, created_at 
    FROM ujian 
    WHERE catin_id = '$catin_id' AND nama_peserta = '$nama'
    ORDER BY created_at DESC
");
$rata_skor = 0;
$total_skor = 0;
$count_ujian = 0;

while ($r = mysqli_fetch_assoc($q_ujian)) {
    $ujian_list[] = [
        'skor' => (int) $r['skor'],
        'benar' => (int) $r['jawaban_benar'],
        'salah' => (int) $r['jawaban_salah'],
        'status' => ucfirst($r['status_kelulusan']),
        'tanggal' => date('d M Y', strtotime($r['created_at']))
    ];
    $total_skor += $r['skor'];
    $count_ujian++;
}

$rata_skor = ($count_ujian > 0) ? (int) round($total_skor / $count_ujian) : 0;

// 4b. Ambil Kuis Logs (Kuis per Materi) dengan join yang benar ke tabel kuis dan materis
$kuis_list = [];
$q_kuis = mysqli_query($koneksi, "
    SELECT kl.nilai, kl.created_at, m.judul 
    FROM kuis_logs kl 
    JOIN kuis k ON kl.kuis_id = k.id
    JOIN materis m ON k.materi_id = m.id 
    WHERE kl.catin_id = '$catin_id' AND kl.nama_peserta = '$nama'
    ORDER BY kl.created_at DESC
");
while ($r = mysqli_fetch_assoc($q_kuis)) {
    $kuis_list[] = [
        'materi' => $r['judul'],
        'nilai' => (int) $r['nilai'],
        'tanggal' => date('d M Y', strtotime($r['created_at']))
    ];
}

// 5. Total Sesi Jadwal (Kehadiran)
$q_jadwal = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM catin_jadwal WHERE catin_id = '$catin_id'");
$total_jadwal_row = mysqli_fetch_assoc($q_jadwal);
$total_jadwal = (int) ($total_jadwal_row['total'] ?? 0);

echo json_encode([
    "status" => "success",
    "data" => [
        "profil" => $detail_profil,
        "progress" => $progress,
        "total_jadwal" => $total_jadwal,
        "materi" => $materi_list,
        "kuis" => $kuis_list,
        "ujian" => [
            "rata_skor" => $rata_skor,
            "riwayat" => $ujian_list
        ]
    ]
]);
?>
