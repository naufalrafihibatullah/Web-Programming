<?php
// Panggil koneksi database
require_once 'koneksi.php';

// Siapkan array untuk menampung respons
$response = [];

// Query untuk mengambil semua data kategori
$query = "SELECT * FROM kategori_artikel ORDER BY id DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

// Ubah data array menjadi format JSON dan kirim ke frontend
header('Content-Type: application/json');
echo json_encode($response);
?>