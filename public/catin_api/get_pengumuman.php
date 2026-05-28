<?php
header('Content-Type: application/json');
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Filter hanya untuk catin atau semua
    $query = "SELECT id, judul, isi, created_at as tgl_post FROM pengumumans 
              WHERE target = 'catin' OR target = 'semua' 
              ORDER BY created_at DESC";
    $result = $koneksi->query($query);

    $list_pengumuman = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $list_pengumuman[] = $row;
        }
    }

    echo json_encode(["status" => "success", "data" => $list_pengumuman]);
}
?>