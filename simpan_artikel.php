<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = htmlspecialchars($_POST['judul'] ?? '', ENT_QUOTES, 'UTF-8');
    $id_penulis = intval($_POST['id_penulis'] ?? 0);
    $id_kategori = intval($_POST['id_kategori'] ?? 0);
    $isi = htmlspecialchars($_POST['isi'] ?? '', ENT_QUOTES, 'UTF-8');

    if (empty($judul) || empty($id_penulis) || empty($id_kategori) || empty($isi)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Semua kolom teks wajib diisi!']); 
        exit;
    }

    // --- FITUR AUTO-GENERATE TANGGAL (Sesuai Soal) ---
    date_default_timezone_set('Asia/Jakarta');
    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
    $sekarang = new DateTime();
    $nama_hari = $hari[$sekarang->format('w')];
    $tanggal = $sekarang->format('j');
    $nama_bulan = $bulan[(int)$sekarang->format('n')];
    $tahun = $sekarang->format('Y');
    $jam = $sekarang->format('H:i');
    
    // Hasilnya misal: "Senin, 13 April 2026 | 15:17"
    $hari_tanggal = "$nama_hari, $tanggal $nama_bulan $tahun | $jam";

    // --- KEAMANAN UPLOAD GAMBAR ---
    if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] === UPLOAD_ERR_NO_FILE) {
        echo json_encode(['status' => 'error', 'pesan' => 'Gambar artikel wajib diunggah!']); 
        exit;
    }

    $gambar = $_FILES['gambar'];
    if ($gambar['size'] > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'pesan' => 'Ukuran gambar maksimal 2 MB!']); 
        exit;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($gambar['tmp_name']);
    if (!in_array($mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
        echo json_encode(['status' => 'error', 'pesan' => 'Tipe file tidak valid!']); 
        exit;
    }

    $ekstensi = pathinfo($gambar['name'], PATHINFO_EXTENSION);
    $nama_gambar = uniqid() . '.' . $ekstensi;
    
    if (!move_uploaded_file($gambar['tmp_name'], 'uploads_artikel/' . $nama_gambar)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal mengunggah gambar ke server!']); 
        exit;
    }

    // --- SIMPAN KE DATABASE ---
    $stmt = $conn->prepare("INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $id_penulis, $id_kategori, $judul, $isi, $nama_gambar, $hari_tanggal);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'pesan' => 'Artikel berhasil diterbitkan!']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
}
?>