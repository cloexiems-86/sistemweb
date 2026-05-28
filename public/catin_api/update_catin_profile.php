<?php
header('Content-Type: application/json');
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catin_id = $_POST['catin_id'];
    $nama_aktif = $_POST['nama_aktif']; // Nama yang sedang login
    $nama_baru = $_POST['nama'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $nik = $_POST['nik'];

    // Cari tahu ini suami atau istri berdasarkan nama_aktif
    $query_cek = "SELECT nama_suami, nama_istri FROM catins WHERE id = '$catin_id'";
    $res_cek = mysqli_query($koneksi, $query_cek);
    $data_cek = mysqli_fetch_assoc($res_cek);

    if ($data_cek) {
        if ($nama_aktif == $data_cek['nama_suami']) {
            // Update Suami
            $sql = "UPDATE catins SET nama_suami=?, phone_suami=?, email_suami=?, alamat_suami=?, nik_suami=? WHERE id=?";
        } else {
            // Update Istri
            $sql = "UPDATE catins SET nama_istri=?, phone_istri=?, email_istri=?, alamat_istri=?, nik_istri=? WHERE id=?";
        }

        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sssssi", $nama_baru, $phone, $email, $alamat, $nik, $catin_id);

        if ($stmt->execute()) {
            // Ambil data terbaru untuk dikembalikan ke Flutter
            $query_new = "SELECT * FROM catins WHERE id = '$catin_id'";
            $res_new = mysqli_query($koneksi, $query_new);
            $data_new = mysqli_fetch_assoc($res_new);
            
            echo json_encode([
                "status" => "success", 
                "message" => "Profil berhasil diperbarui",
                "user" => $data_new
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui database: " . $koneksi->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Data catin tidak ditemukan"]);
    }
}
?>
