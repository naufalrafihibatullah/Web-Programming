<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_penulis']);
    $nama_depan = htmlspecialchars($_POST['nama_depan'], ENT_QUOTES, 'UTF-8');
    $nama_belakang = htmlspecialchars($_POST['nama_belakang'], ENT_QUOTES, 'UTF-8');
    $user_name = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password_raw = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT foto FROM penulis WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $foto_lama = $stmt->get_result()->fetch_assoc()['foto'];
    $stmt->close();

    $foto_baru = $foto_lama;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $foto = $_FILES['foto'];
        if ($foto['size'] > 2 * 1024 * 1024) {
            echo json_encode(['status' => 'error', 'pesan' => 'Ukuran foto maksimal 2 MB!']); exit;
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($foto['tmp_name']);
        if (!in_array($mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
            echo json_encode(['status' => 'error', 'pesan' => 'Tipe file tidak valid!']); exit;
        }
        
        $ekstensi = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $foto_baru = uniqid() . '.' . $ekstensi;
        if (move_uploaded_file($foto['tmp_name'], 'uploads_penulis/' . $foto_baru)) {
            if (!empty($foto_lama) && $foto_lama !== 'default.png' && file_exists('uploads_penulis/' . $foto_lama)) {
                unlink('uploads_penulis/' . $foto_lama);
            }
        }
    }

    if (!empty($password_raw)) {
        $password_hash = password_hash($password_raw, PASSWORD_BCRYPT);
        $query = "UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, password=?, foto=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $nama_depan, $nama_belakang, $user_name, $password_hash, $foto_baru, $id);
    } else {
        $query = "UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, foto=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $nama_depan, $nama_belakang, $user_name, $foto_baru, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'pesan' => 'Data berhasil diperbarui!']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal update: ' . $stmt->error]);
    }
    $stmt->close();
}
$conn->close();
?>