<?php
require_once 'koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = htmlspecialchars($_POST['nama_kategori'] ?? '', ENT_QUOTES, 'UTF-8');
    $keterangan = htmlspecialchars($_POST['keterangan'] ?? '', ENT_QUOTES, 'UTF-8');

    if (empty($nama_kategori)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Nama kategori tidak boleh kosong!']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO kategori_artikel (nama_kategori, keterangan) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama_kategori, $keterangan);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'pesan' => 'Kategori berhasil ditambahkan!']);
    } else {
        // Tangani jika ada duplikasi nama kategori (karena di database kita set UNIQUE)
        if ($conn->errno == 1062) {
            echo json_encode(['status' => 'error', 'pesan' => 'Nama kategori sudah ada!']);
        } else {
            echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan data: ' . $stmt->error]);
        }
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Metode tidak valid!']);
}
?>