<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_kategori']);
    $nama_kategori = htmlspecialchars($_POST['nama_kategori'], ENT_QUOTES, 'UTF-8');
    $keterangan = htmlspecialchars($_POST['keterangan'], ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("UPDATE kategori_artikel SET nama_kategori = ?, keterangan = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nama_kategori, $keterangan, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'pesan' => 'Kategori berhasil diperbarui!']);
    } else {
        if ($conn->errno == 1062) {
            echo json_encode(['status' => 'error', 'pesan' => 'Nama kategori sudah ada!']);
        } else {
            echo json_encode(['status' => 'error', 'pesan' => 'Gagal memperbarui: ' . $stmt->error]);
        }
    }
    $stmt->close();
}
$conn->close();
?>