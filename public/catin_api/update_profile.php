<?php
header('Content-Type: application/json');
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    
    // Logika upload foto jika ada
    $foto_name = $_POST['current_foto']; // Default foto lama
    if (isset($_FILES['foto'])) {
        $target_dir = "uploads/profile/";
        $foto_name = time() . "_" . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $foto_name);
    }

    $query = "UPDATE users SET nama = ?, email = ?, foto = ? WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("sssi", $nama, $email, $foto_name, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Profil berhasil diperbarui"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui profil"]);
    }
}
?>