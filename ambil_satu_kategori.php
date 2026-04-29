<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Prepared statement untuk keamanan
    $stmt = $conn->prepare("SELECT * FROM kategori_artikel WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo json_encode($result->fetch_assoc());
    $stmt->close();
}
$conn->close();
?>