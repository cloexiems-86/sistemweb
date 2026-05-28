<?php
header('Content-Type: application/json');
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];

    // Contoh: Hapus token dari database jika Anda menggunakan sistem token di server
    $query = "UPDATE users SET session_token = NULL WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Berhasil logout"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal logout"]);
    }
}
?>