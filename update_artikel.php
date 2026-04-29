<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_artikel']);
    $judul = htmlspecialchars($_POST['judul'], ENT_QUOTES, 'UTF-8');
    $id_penulis = intval($_POST['id_penulis']);
    $id_kategori = intval($_POST['id_kategori']);
    $isi = htmlspecialchars($_POST['isi'], ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("SELECT gambar FROM artikel WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $gambar_lama = $stmt->get_result()->fetch_assoc()['gambar'];
    $stmt->close();

    $gambar_baru = $gambar_lama; 

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $gambar = $_FILES['gambar'];
        if ($gambar['size'] > 2 * 1024 * 1024) {
            echo json_encode(['status' => 'error', 'pesan' => 'Ukuran gambar maksimal 2 MB!']); exit;
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($gambar['tmp_name']);
        if (!in_array($mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
            echo json_encode(['status' => 'error', 'pesan' => 'Tipe file tidak valid!']); exit;
        }
        
        $ekstensi = pathinfo($gambar['name'], PATHINFO_EXTENSION);
        $gambar_baru = uniqid() . '.' . $ekstensi;
        if (move_uploaded_file($gambar['tmp_name'], 'uploads_artikel/' . $gambar_baru)) {
            // Hapus gambar fisik yang lama di server
            if (!empty($gambar_lama) && file_exists('uploads_artikel/' . $gambar_lama)) {
                unlink('uploads_artikel/' . $gambar_lama);
            }
        }
    }

    $query = "UPDATE artikel SET id_penulis=?, id_kategori=?, judul=?, isi=?, gambar=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisssi", $id_penulis, $id_kategori, $judul, $isi, $gambar_baru, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'pesan' => 'Artikel berhasil diperbarui!']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal update: ' . $stmt->error]);
    }
    $stmt->close();
}
$conn->close();
?>