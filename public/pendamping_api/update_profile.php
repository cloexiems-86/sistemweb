<?php
header('Content-Type: application/json');
include "../catin_api/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $nip = $_POST['nip'] ?? '';
    $email = $_POST['email'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($id) || empty($nama) || empty($email)) {
        echo json_encode(["status" => "error", "message" => "ID, Nama, dan Email wajib diisi!"]);
        exit;
    }

    // Cek apakah email sudah dipakai oleh pendamping lain
    $cek_email = mysqli_query($koneksi, "SELECT id FROM pendamping WHERE email = '$email' AND id != '$id'");
    if (mysqli_num_rows($cek_email) > 0) {
        echo json_encode(["status" => "error", "message" => "Email sudah digunakan oleh pendamping lain!"]);
        exit;
    }

    // Query update
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE pendamping SET nama='$nama', nip='$nip', email='$email', no_hp='$no_hp', password='$hashed_password' WHERE id='$id'";
    } else {
        $query = "UPDATE pendamping SET nama='$nama', nip='$nip', email='$email', no_hp='$no_hp' WHERE id='$id'";
    }

    $run = mysqli_query($koneksi, $query);

    if ($run) {
        // Ambil data terbaru untuk dikembalikan ke Flutter
        $get = mysqli_query($koneksi, "SELECT * FROM pendamping WHERE id='$id'");
        $updatedUser = mysqli_fetch_assoc($get);
        $updatedUser['role'] = 'pendamping';

        echo json_encode([
            "status" => "success",
            "message" => "Profil berhasil diperbarui!",
            "user" => $updatedUser
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui profil: " . mysqli_error($koneksi)]);
    }
}
?>
