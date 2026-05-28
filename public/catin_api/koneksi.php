<?php
// JANGAN pakai https://... di sini
$host = "localhost"; 
$user = "root";
$pass = ""; // Kosongkan jika pakai Laragon standar
$db   = "kuamojo"; // Pastikan nama database di Laragon kamu benar

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>