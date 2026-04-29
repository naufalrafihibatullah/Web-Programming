<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Ambil nama gambar sebelum dihapus
    $stmt = $conn->prepare("SELECT gambar FROM artikel WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $gambar_artikel = $stmt->get_result()->fetch_assoc()['gambar'];
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM artikel WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Hapus file fisik gambar di server
        if (!empty($gambar_artikel) && file_exists('uploads_artikel/' . $gambar_artikel)) {
            unlink('uploads_artikel/' . $gambar_artikel);
        }
        echo json_encode(['status' => 'success', 'pesan' => 'Artikel berhasil dihapus!']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menghapus artikel.']);
    }
    $stmt->close();
}
$conn->close();
?>