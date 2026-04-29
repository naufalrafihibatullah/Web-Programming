<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("SELECT foto FROM penulis WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $foto_penulis = $stmt->get_result()->fetch_assoc()['foto'];
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM penulis WHERE id = ?");
    $stmt->bind_param("i", $id);

    try {
        if ($stmt->execute()) {
            // Hapus file fisik di server (kecuali default.png)
            if (!empty($foto_penulis) && $foto_penulis !== 'default.png' && file_exists('uploads_penulis/' . $foto_penulis)) {
                unlink('uploads_penulis/' . $foto_penulis);
            }
            echo json_encode(['status' => 'success', 'pesan' => 'Penulis berhasil dihapus!']);
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) { // Constraint violation
            echo json_encode(['status' => 'error', 'pesan' => 'Gagal: Penulis ini masih memiliki artikel!']);
        } else {
            echo json_encode(['status' => 'error', 'pesan' => 'Error: ' . $e->getMessage()]);
        }
    }
    $stmt->close();
}
$conn->close();
?>