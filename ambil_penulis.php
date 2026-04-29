<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

// Ambil data penulis, diurutkan dari yang terbaru
$query = "SELECT id, nama_depan, nama_belakang, user_name, foto, password FROM penulis ORDER BY id DESC";
$result = $conn->query($query);
$response = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Cek jika foto kosong, gunakan default.png
        if (empty($row['foto'])) {
            $row['foto'] = 'default.png';
        }
        $response[] = $row;
    }
}

echo json_encode($response);
$conn->close();
?>