<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_depan = htmlspecialchars($_POST['nama_depan'] ?? '', ENT_QUOTES, 'UTF-8');
    $nama_belakang = htmlspecialchars($_POST['nama_belakang'] ?? '', ENT_QUOTES, 'UTF-8');
    $user_name = htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8');
    $password_raw = $_POST['password'] ?? '';

    if (empty($nama_depan) || empty($nama_belakang) || empty($user_name) || empty($password_raw)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Semua kolom teks wajib diisi!']);
        exit;
    }

    $password_hash = password_hash($password_raw, PASSWORD_BCRYPT);
    $foto_nama = ''; 

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $foto = $_FILES['foto'];
        
        if ($foto['size'] > 2 * 1024 * 1024) {
            echo json_encode(['status' => 'error', 'pesan' => 'Ukuran foto maksimal 2 MB!']);
            exit;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($foto['tmp_name']);
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($mime_type, $allowed_types)) {
            echo json_encode(['status' => 'error', 'pesan' => 'Tipe file manipulasi terdeteksi! Hanya JPG/PNG/GIF.']);
            exit;
        }

        $ekstensi = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $foto_nama = uniqid() . '.' . $ekstensi;
        $tujuan = 'uploads_penulis/' . $foto_nama;

        if (!move_uploaded_file($foto['tmp_name'], $tujuan)) {
            echo json_encode(['status' => 'error', 'pesan' => 'Gagal mengunggah foto ke server!']);
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama_depan, $nama_belakang, $user_name, $password_hash, $foto_nama);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'pesan' => 'Penulis berhasil ditambahkan!']);
    } else {
        if ($conn->errno == 1062) { // Error code untuk duplicate entry
            echo json_encode(['status' => 'error', 'pesan' => 'Username sudah terdaftar, gunakan yang lain!']);
        } else {
            echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan: ' . $stmt->error]);
        }
    }
    $stmt->close();
    $conn->close();
}
?>