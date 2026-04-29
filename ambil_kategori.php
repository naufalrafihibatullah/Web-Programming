<?php
require_once 'koneksi.php';

$response = [];

$query = "SELECT * FROM kategori_artikel ORDER BY id DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>