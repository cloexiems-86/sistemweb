<?php
header('Content-Type: application/json');
include '../catin_api/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT id, judul, isi as deskripsi, created_at as waktu, target FROM pengumumans 
              WHERE target = 'pendamping' OR target = 'semua' 
              ORDER BY created_at DESC LIMIT 10";
    $result = $koneksi->query($query);

    $list = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['tipe'] = 'info'; 
            $row['is_read'] = false;
            $list[] = $row;
        }
    }

    echo json_encode(["status" => "success", "data" => $list]);
}
?>
