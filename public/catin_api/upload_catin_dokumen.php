<?php
header('Content-Type: application/json');
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catin_id = $_POST['catin_id'];
    $nama_aktif = $_POST['nama_aktif'];
    $type = $_POST['type']; // 'KTP' atau 'KK'
    
    if (!isset($_FILES['file'])) {
        echo json_encode(["status" => "error", "message" => "File tidak ditemukan"]);
        exit;
    }

    // Tentukan kolom database
    $query_cek = "SELECT nama_suami, nama_istri FROM catins WHERE id = '$catin_id'";
    $res_cek = mysqli_query($koneksi, $query_cek);
    $data_cek = mysqli_fetch_assoc($res_cek);

    $column = "";
    $folder = "";
    
    if ($type == 'KTP') {
        $folder = "ktp";
        $column = ($nama_aktif == $data_cek['nama_suami']) ? "ktp_suami" : "ktp_istri";
    } else {
        $folder = "kk";
        $column = ($nama_aktif == $data_cek['nama_suami']) ? "kk_suami" : "kk_istri";
    }

    // Proses Upload
    $target_dir = "../storage/$folder/"; // Naik ke public, lalu masuk ke storage
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
    $file_name = $column . "_" . $catin_id . "_" . time() . "." . $file_ext;
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // Simpan path ke DB (Format: 'ktp/namafile.jpg' agar sesuai Laravel)
        $db_path = "$folder/$file_name";
        $sql = "UPDATE catins SET $column = '$db_path' WHERE id = '$catin_id'";
        
        if (mysqli_query($koneksi, $sql)) {
            echo json_encode([
                "status" => "success", 
                "message" => "$type berhasil diunggah",
                "path" => $db_path
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal update database"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal upload file"]);
    }
}
?>
