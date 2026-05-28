<?php
include __DIR__ . '/../catin_api/koneksi.php';

// 1. Perbarui materi_logs
$q1 = mysqli_query($koneksi, "
    UPDATE materi_logs 
    SET nama_peserta = 'Ciko Kurniawan' 
    WHERE catin_id = 2 AND (nama_peserta = '' OR nama_peserta IS NULL)
");
echo "Update materi_logs: " . ($q1 ? "SUCCESS" : "FAILED - " . mysqli_error($koneksi)) . "\n";

// 2. Perbarui kuis_logs
$q2 = mysqli_query($koneksi, "
    UPDATE kuis_logs 
    SET nama_peserta = 'Ciko Kurniawan' 
    WHERE catin_id = 2 AND (nama_peserta = '' OR nama_peserta IS NULL)
");
echo "Update kuis_logs: " . ($q2 ? "SUCCESS" : "FAILED - " . mysqli_error($koneksi)) . "\n";

// 3. Masukkan record materi_logs yang terlewat untuk kuis_logs yang sudah selesai
$q_kuis = mysqli_query($koneksi, "SELECT DISTINCT kuis_id FROM kuis_logs WHERE catin_id = 2 AND nama_peserta = 'Ciko Kurniawan'");
while ($row = mysqli_fetch_assoc($q_kuis)) {
    $kuis_id = $row['kuis_id'];
    // Cari materi_id dari kuis_id
    $q_mat = mysqli_query($koneksi, "SELECT materi_id FROM kuis WHERE id = $kuis_id LIMIT 1");
    if ($mat_row = mysqli_fetch_assoc($q_mat)) {
        $materi_id = $mat_row['materi_id'];
        // Cek jika belum ada di materi_logs
        $cek_log = mysqli_query($koneksi, "SELECT id FROM materi_logs WHERE catin_id = 2 AND materi_id = $materi_id AND nama_peserta = 'Ciko Kurniawan'");
        if (mysqli_num_rows($cek_log) == 0) {
            mysqli_query($koneksi, "
                INSERT INTO materi_logs (catin_id, materi_id, nama_peserta, accessed_at)
                VALUES (2, $materi_id, 'Ciko Kurniawan', NOW())
            ");
            echo "Inserted missing materi_log for materi_id $materi_id\n";
        }
    }
}

// 4. Dump data untuk memverifikasi
$q_dump = mysqli_query($koneksi, "SELECT * FROM materi_logs WHERE catin_id = 2");
echo "\n=== VERIFIED MATERI LOGS ===\n";
while($r = mysqli_fetch_assoc($q_dump)) {
    print_r($r);
}

$q_dump_kuis = mysqli_query($koneksi, "SELECT * FROM kuis_logs WHERE catin_id = 2");
echo "\n=== VERIFIED KUIS LOGS ===\n";
while($r = mysqli_fetch_assoc($q_dump_kuis)) {
    print_r($r);
}
?>
