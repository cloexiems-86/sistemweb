<?php
header('Content-Type: application/json');
include 'koneksi.php'; 

// Ambil data dari Flutter (bisa berisi username catin atau email pendamping)
$inputUser = $_POST['username'] ?? ''; 
$password = $_POST['password'] ?? '';

if (empty($inputUser) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Username/Email dan Password wajib diisi!"]);
    exit;
}

// --- 1. CEK DI TABEL CATINS (Pakai kolom 'username') ---
$queryCatin = mysqli_query($koneksi, "SELECT * FROM catins WHERE username = '$inputUser'");
$userCatin = mysqli_fetch_assoc($queryCatin);

if ($userCatin && password_verify($password, $userCatin['password'])) {
    $userCatin['role'] = 'catin';
    echo json_encode([
        "status" => "success",
        "message" => "Login Berhasil sebagai Catin",
        "user" => $userCatin
    ]);
    exit;
}

// --- 2. CEK DI TABEL PENDAMPING (Pakai kolom 'email') ---
$queryPendamping = mysqli_query($koneksi, "SELECT * FROM pendamping WHERE email = '$inputUser'");
$userPendamping = mysqli_fetch_assoc($queryPendamping);

if ($userPendamping && password_verify($password, $userPendamping['password'])) {
    $userPendamping['role'] = 'pendamping';
    echo json_encode([
        "status" => "success",
        "message" => "Login Berhasil sebagai Pendamping",
        "role" => "pendamping",
        "user" => $userPendamping
    ]);
    exit;
}

// --- 3. JIKA TIDAK DITEMUKAN ---
echo json_encode([
    "status" => "error",
    "message" => "Data tidak ditemukan atau Password salah!"
]);
?>