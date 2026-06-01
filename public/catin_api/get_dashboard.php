<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
error_reporting(0);
ini_set('display_errors', 0);

include 'koneksi.php';

if (!$koneksi) {
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal"]);
    exit;
}

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$nama_peserta = isset($_GET['nama_peserta']) ? mysqli_real_escape_string($koneksi, trim($_GET['nama_peserta'])) : '';

if ($user_id == 0) {
    echo json_encode(["status" => "error", "message" => "User ID tidak valid"]);
    exit;
}

$response = [
    "status" => "success",
    "data" => [
        "progress" => [
            "total_materi" => 0,
            "materi_dibaca" => 0,
            "kuis_selesai" => 0,
            "ujian_selesai" => 0,
            "persentase" => 0
        ],
        "jadwal_terdekat" => null,
        "materi_terbaru" => []
    ]
];

// 1. Hitung Total Materi
$q_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM materis WHERE status = 'aktif'");
$d_total = mysqli_fetch_assoc($q_total);
$total_materi = intval($d_total['total']);
$response["data"]["progress"]["total_materi"] = $total_materi;

// 2. Hitung Materi Dibaca
$q_materi_read_query = "SELECT COUNT(DISTINCT materi_id) as total FROM materi_logs WHERE catin_id = $user_id";
if ($nama_peserta !== '') {
    $q_materi_read_query .= " AND nama_peserta = '$nama_peserta'";
}
$q_materi_read = mysqli_query($koneksi, $q_materi_read_query);
$d_materi_read = mysqli_fetch_assoc($q_materi_read);
$materi_dibaca = intval($d_materi_read['total']);
$response["data"]["progress"]["materi_dibaca"] = $materi_dibaca;

// 3. Hitung Kuis Selesai (Gunakan DISTINCT agar tidak lebih dari 100%)
$q_selesai_query = "SELECT COUNT(DISTINCT kuis_id) as total FROM kuis_logs WHERE catin_id = $user_id";
if ($nama_peserta !== '') {
    $q_selesai_query .= " AND nama_peserta = '$nama_peserta'";
}
$q_selesai = mysqli_query($koneksi, $q_selesai_query);
$d_selesai = mysqli_fetch_assoc($q_selesai);
$kuis_selesai = intval($d_selesai['total']);
$response["data"]["progress"]["kuis_selesai"] = $kuis_selesai;

// 4. Hitung Ujian Selesai
$q_ujian_query = "SELECT COUNT(*) as total FROM ujian WHERE catin_id = $user_id";
if ($nama_peserta !== '') {
    $q_ujian_query .= " AND nama_peserta = '$nama_peserta'";
}
$q_ujian = mysqli_query($koneksi, $q_ujian_query);
$d_ujian = mysqli_fetch_assoc($q_ujian);
$ujian_selesai = intval($d_ujian['total']);
$response["data"]["progress"]["ujian_selesai"] = $ujian_selesai;

// 5. Hitung Persentase (Berdasarkan jumlah kuis yang diselesaikan dibanding total materi)
if ($total_materi > 0) {
    $persen = round(($kuis_selesai / $total_materi) * 100);
    $response["data"]["progress"]["persentase"] = ($persen > 100) ? 100 : $persen;
}

// 6. Jadwal Terdekat (SUDAH DIPERBAIKI MENGGUNAKAN INNER JOIN KE TABEL PIVOT)
$q_jadwal = mysqli_query($koneksi, "SELECT 
            j.id, 
            j.topik AS judul, 
            j.tanggal, 
            j.sesi AS jam, 
            j.lokasi AS tempat, 
            j.fasilitator AS narasumber, 
            j.status,
            'Silakan hadir tepat waktu sesuai jadwal yang tertera.' AS deskripsi
        FROM jadwals j
        INNER JOIN catin_jadwal cj ON j.id = cj.jadwal_id
        WHERE cj.catin_id = '$user_id' AND j.tanggal >= CURDATE() 
        ORDER BY j.tanggal ASC LIMIT 1");

if ($q_jadwal && mysqli_num_rows($q_jadwal) > 0) {
    $row = mysqli_fetch_assoc($q_jadwal);
    
    $statusDb = strtolower($row['status']);
    if ($statusDb == 'upcoming' || $statusDb == 'mendatang') {
        $row['status'] = 'mendatang';
    } elseif ($statusDb == 'completed' || $statusDb == 'selesai') {
        $row['status'] = 'selesai';
    }
    
    $response["data"]["jadwal_terdekat"] = $row;
}

// 7. Materi Terbaru (Ambil 2 materi terakhir yang aktif)
$baseUrl = "https://farel.dwirez.app/materi-display/";
$q_materi = mysqli_query($koneksi, "SELECT id, judul, deskripsi, file FROM materis WHERE status = 'aktif' ORDER BY created_at DESC LIMIT 2");
while ($row = mysqli_fetch_assoc($q_materi)) {
    $row['file_url'] = $baseUrl . $row['id'];
    $response["data"]["materi_terbaru"][] = $row;
}

echo json_encode($response);
?>