<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

    if ($user_id == 0) {
        echo json_encode(["status" => "error", "message" => "User ID tidak valid"]);
        exit;
    }

    // 1. Cek skor ujian akhir sesuai peserta yang memasuki aplikasi
    $nama_peserta = isset($_GET['nama_peserta']) ? mysqli_real_escape_string($koneksi, $_GET['nama_peserta']) : '';
    $ujianCondition = "catin_id = $user_id";
    if ($nama_peserta !== '') {
        $ujianCondition .= " AND nama_peserta = '$nama_peserta'";
    }

    $sql_ujian = "SELECT id, skor FROM ujian WHERE $ujianCondition ORDER BY skor DESC LIMIT 1";
    $res_ujian = mysqli_query($koneksi, $sql_ujian);
    $data_ujian = mysqli_fetch_assoc($res_ujian);
    $skor_ujian = $data_ujian ? intval($data_ujian['skor']) : 0;
    $ujian_id = $data_ujian ? intval($data_ujian['id']) : 0;

    // 2. Hitung total materi aktif
    $total_materi_res = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM materis WHERE status = 'aktif'");
    $total_materi = intval(mysqli_fetch_assoc($total_materi_res)['total']);
    
    // 3. Hitung materi yang sudah selesai (dibaca DAN kuis dikerjakan)
    // Kita anggap materi selesai jika ada di kuis_logs (karena kuis dikerjakan setelah baca materi)
    $materi_selesai_res = mysqli_query($koneksi, "SELECT COUNT(DISTINCT k.materi_id) as total 
                                                 FROM kuis_logs kl 
                                                 JOIN kuis k ON kl.kuis_id = k.id 
                                                 WHERE kl.catin_id = $user_id");
    $materi_selesai = intval(mysqli_fetch_assoc($materi_selesai_res)['total']);

    // Syarat Lulus: Skor >= 70 DAN Semua materi selesai
    $is_lulus = ($skor_ujian >= 70 && $materi_selesai >= $total_materi);

    if ($is_lulus) {
        $url_sertifikat = $ujian_id ? "https://farel.dwirez.app/sertifikat/public/stream/" . $ujian_id : "";
        $url_download = $ujian_id ? "https://farel.dwirez.app/sertifikat/public/download/" . $ujian_id : "";
        echo json_encode([
            "status" => "success",
            "is_lulus" => true,
            "skor" => $skor_ujian,
            "total_materi" => $total_materi,
            "materi_selesai" => $materi_selesai,
            "url_sertifikat" => $url_sertifikat,
            "url_download" => $url_download
        ]);
    } else {
        $msg = "Anda belum memenuhi syarat kelulusan.";
        if ($materi_selesai < $total_materi) {
            $msg = "Selesaikan semua materi dan kuis terlebih dahulu ($materi_selesai/$total_materi).";
        } else if (!$data_ujian) {
            $msg = "Silakan ikuti ujian akhir terlebih dahulu.";
        } else {
            $msg = "Skor ujian Anda ($skor_ujian) belum mencapai batas minimal 70.";
        }

        echo json_encode([
            "status" => "success",
            "is_lulus" => false,
            "message" => $msg,
            "skor" => $skor_ujian,
            "total_materi" => $total_materi,
            "materi_selesai" => $materi_selesai
        ]);
    }
}
?>