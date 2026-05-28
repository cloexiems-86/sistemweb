<?php
header('Content-Type: application/json');
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $catin_id = $_GET['id'];

    $query = "SELECT * FROM catins WHERE id = '$catin_id'";
    $res = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($res);

    if ($user) {
        echo json_encode(["status" => "success", "user" => $user]);
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak ditemukan"]);
    }
}
?>
