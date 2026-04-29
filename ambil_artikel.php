<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

$query = "SELECT a.id, a.judul, a.isi, a.gambar, a.hari_tanggal, 
                 p.nama_depan, p.nama_belakang, 
                 k.nama_kategori 
          FROM artikel a 
          JOIN penulis p ON a.id_penulis = p.id 
          JOIN kategori_artikel k ON a.id_kategori = k.id 
          ORDER BY a.id DESC";

$result = $conn->query($query);
$response = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

echo json_encode($response);
$conn->close();
?>