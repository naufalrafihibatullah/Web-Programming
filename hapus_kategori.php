<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM kategori_artikel WHERE id = ?");
    $stmt->bind_param("i", $id);

    try {
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'pesan' => 'Kategori berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'pesan' => 'Gagal menghapus data.']);
        }
    } catch (mysqli_sql_exception $e) {
        // Error code 1451 adalah Foreign Key Constraint Violation
        if ($e->getCode() == 1451) {
            echo json_encode(['status' => 'error', 'pesan' => 'Gagal: Kategori masih memiliki artikel!']);
        } else {
            echo json_encode(['status' => 'error', 'pesan' => 'Error: ' . $e->getMessage()]);
        }
    }
    $stmt->close();
}
$conn->close();
?>