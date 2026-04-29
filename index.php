<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Blog (CMS)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #fff; box-shadow: 2px 0 5px rgba(0,0,0,0.05); }
        .header-top { background-color: #2c3e50; color: white; padding: 15px 20px; }
        .nav-link { color: #333; margin-bottom: 5px; padding: 10px 15px; border-radius: 5px; }
        .nav-link:hover { background-color: #f8f9fa; }
        .nav-link.active { background-color: #e9ecef; font-weight: bold; border-left: 4px solid #28a745; }
    </style>
</head>
<body>

    <div class="header-top d-flex align-items-center">
        <h5 class="m-0">Sistem Manajemen Blog (CMS)</h5>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-3">
                <p class="text-muted small fw-bold mb-3">MENU UTAMA</p>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" id="menu-penulis" onclick="loadMenu('penulis')">Kelola Penulis</a>
                    <a class="nav-link" href="#" id="menu-artikel" onclick="loadMenu('artikel')">Kelola Artikel</a>
                    <a class="nav-link" href="#" id="menu-kategori" onclick="loadMenu('kategori')">Kelola Kategori Artikel</a>
                </nav>
            </div>

            <div class="col-md-10 p-4">
                <div id="main-content">
                    <div class="alert alert-info">
                        Selamat datang! Silakan pilih menu di sebelah kiri untuk mengelola data.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm"> <div class="modal-content text-center p-4">
                <div class="modal-body">
                    <div class="mb-3">
                        <div style="width: 60px; height: 60px; background-color: #ffe6e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #dc3545; font-size: 28px;">
                            🗑️
                        </div>
                    </div>
                    <h5 class="mb-2 fw-bold">Hapus data ini?</h5>
                    <p class="text-muted small mb-4">Data yang dihapus tidak dapat dikembalikan.</p>
                    
                    <input type="hidden" id="id_hapus">
                    
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" onclick="eksekusiHapus()">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPenulis" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judulModalPenulis">Tambah Penulis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPenulis" onsubmit="simpanPenulis(event)">
                        <input type="hidden" id="id_penulis" name="id_penulis">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_depan" class="form-label">Nama Depan</label>
                                <input type="text" class="form-control" id="nama_depan" name="nama_depan" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_belakang" class="form-label">Nama Belakang</label>
                                <input type="text" class="form-control" id="nama_belakang" name="nama_belakang" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-muted" id="helpPassword"></small>
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Profil (Max 2MB)</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalArtikel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judulModalArtikel">Tambah Artikel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formArtikel" onsubmit="simpanArtikel(event)">
                        <input type="hidden" id="id_artikel" name="id_artikel">
                        
                        <div class="mb-3">
                            <label class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul artikel..." required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penulis</label>
                                <select class="form-select" id="select_penulis" name="id_penulis" required>
                                    <option value="">Pilih Penulis...</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" id="select_kategori" name="id_kategori" required>
                                    <option value="">Pilih Kategori...</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Isi Artikel</label>
                            <textarea class="form-control" id="isi" name="isi" rows="6" placeholder="Tulis isi artikel di sini..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Utama (Max 2MB)</label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi utama pergantian menu
        function loadMenu(menu) {
            document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
            document.getElementById('menu-' + menu).classList.add('active');
            
            const konten = document.getElementById('main-content');

            if (menu === 'kategori') {
                konten.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Data Kategori Artikel</h4>
                        <button class="btn btn-success" onclick="showModalTambahKategori()">+ Tambah Kategori</button>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA KATEGORI</th>
                                            <th>KETERANGAN</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-kategori">
                                        <tr><td colspan="4">Memuat data...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
                fetchKategori();
                
            } else if (menu === 'penulis') {
                konten.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Data Penulis</h4>
                        <button class="btn btn-success" onclick="showModalTambahPenulis()">+ Tambah Penulis</button>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>FOTO</th>
                                            <th>NAMA</th>
                                            <th>USERNAME</th>
                                            <th>PASSWORD</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-penulis">
                                        <tr><td colspan="5">Memuat data...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
                fetchPenulis();
                
            } else if (menu === 'artikel') {
                konten.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Data Artikel</h4>
                        <button class="btn btn-success" onclick="showModalTambahArtikel()">+ Tambah Artikel</button>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>GAMBAR</th>
                                            <th>JUDUL</th>
                                            <th>KATEGORI</th>
                                            <th>PENULIS</th>
                                            <th>TANGGAL</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-artikel">
                                        <tr><td colspan="6">Memuat data...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
                fetchArtikel();
                
            } else {
                konten.innerHTML = `<div class="alert alert-info">Menu ${menu} sedang dalam tahap pembangunan.</div>`;
            }
        }

        // Fetch API untuk data kategori
        function fetchKategori() {
            fetch('ambil_kategori.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('tbody-kategori');
                    tbody.innerHTML = ''; 
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-muted">Belum ada data kategori.</td></tr>';
                        return;
                    }

                    let no = 1;
                    data.forEach(item => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${no++}</td>
                                <td class="text-start">${item.nama_kategori}</td>
                                <td class="text-start">${item.keterangan}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editKategori(${item.id})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="hapusKategori(${item.id})">Hapus</button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('tbody-kategori').innerHTML = '<tr><td colspan="4" class="text-danger">Gagal memuat data!</td></tr>';
                });
        }

        // Fetch API untuk data Penulis
        function fetchPenulis() {
            fetch('ambil_penulis.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('tbody-penulis');
                    tbody.innerHTML = ''; 

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-muted">Belum ada data penulis.</td></tr>';
                        return;
                    }

                    data.forEach(item => {
                        let namaLengkap = item.nama_depan + ' ' + item.nama_belakang;
                        
                        tbody.innerHTML += `
                            <tr>
                                <td>
                                    <img src="uploads_penulis/${item.foto}" alt="Foto" width="50" height="50" class="rounded-circle object-fit-cover shadow-sm">
                                </td>
                                <td class="text-start">${namaLengkap}</td>
                                <td><span class="badge bg-light text-primary border">${item.user_name}</span></td>
                                <td>********</td> <td>
                                    <button class="btn btn-sm btn-primary" onclick="editPenulis(${item.id})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="hapusPenulis(${item.id})">Hapus</button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('tbody-penulis').innerHTML = '<tr><td colspan="5" class="text-danger">Gagal memuat data!</td></tr>';
                });
        }
        
        // Tambah Penulis
        function showModalTambahPenulis() {
            document.getElementById('judulModalPenulis').innerText = 'Tambah Penulis';
            document.getElementById('formPenulis').reset(); 
            document.getElementById('id_penulis').value = '';
            
            document.getElementById('password').required = true; 
            document.getElementById('helpPassword').innerText = ''; 
            
            let modal = new bootstrap.Modal(document.getElementById('modalPenulis'));
            modal.show();
        }

        // Menyimpan data Penulis
        function simpanPenulis(event) {
            event.preventDefault();
            let formData = new FormData(document.getElementById('formPenulis'));
            let idPenulis = document.getElementById('id_penulis').value;
            
            let urlTarget = idPenulis ? 'update_penulis.php' : 'simpan_penulis.php';

            fetch(urlTarget, {
                method: 'POST',
                body: formData 
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.pesan);
                    bootstrap.Modal.getInstance(document.getElementById('modalPenulis')).hide();
                    fetchPenulis(); 
                } else {
                    alert(data.pesan); 
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Tambah Kategori
        function showModalTambahKategori() {
            document.getElementById('judulModalKategori').innerText = 'Tambah Kategori';
            document.getElementById('formKategori').reset(); 
            document.getElementById('id_kategori').value = ''; 
            
            let modal = new bootstrap.Modal(document.getElementById('modalKategori'));
            modal.show();
        }

        // Fetch API
        function simpanKategori(event) {
            event.preventDefault();
            let formData = new FormData(document.getElementById('formKategori'));
            let idKategori = document.getElementById('id_kategori').value;
            
            let urlTarget = idKategori ? 'update_kategori.php' : 'simpan_kategori.php';

            fetch(urlTarget, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.pesan);
                    bootstrap.Modal.getInstance(document.getElementById('modalKategori')).hide();
                    fetchKategori(); 
                } else {
                    alert(data.pesan);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Form Edit dan ambil datanya
        function editKategori(id) {
            fetch('ambil_satu_kategori.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('judulModalKategori').innerText = 'Edit Kategori';
                document.getElementById('id_kategori').value = data.id;
                document.getElementById('nama_kategori').value = data.nama_kategori;
                document.getElementById('keterangan').value = data.keterangan;
                
                let modal = new bootstrap.Modal(document.getElementById('modalKategori'));
                modal.show();
            })
            .catch(error => console.error('Error fetching data:', error));
        }

        // Menghapus kategori
        let urlHapusAktif = ''; 

        // === FUNGSI EDIT & HAPUS PENULIS ===
        function editPenulis(id) {
            fetch('ambil_satu_penulis.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('judulModalPenulis').innerText = 'Edit Penulis';
                document.getElementById('id_penulis').value = data.id;
                document.getElementById('nama_depan').value = data.nama_depan;
                document.getElementById('nama_belakang').value = data.nama_belakang;
                document.getElementById('username').value = data.user_name;
                
                document.getElementById('password').required = false;
                document.getElementById('password').value = '';
                document.getElementById('helpPassword').innerText = 'Kosongkan jika tidak ingin mengganti password/foto';
                
                new bootstrap.Modal(document.getElementById('modalPenulis')).show();
            });
        }

        function hapusPenulis(id) {
            urlHapusAktif = 'hapus_penulis.php?id=' + id;
            new bootstrap.Modal(document.getElementById('modalHapus')).show();
        }

        // === PERBARUI FUNGSI HAPUS KATEGORI ===
        function hapusKategori(id) {
            urlHapusAktif = 'hapus_kategori.php?id=' + id;
            new bootstrap.Modal(document.getElementById('modalHapus')).show();
        }

        // === FUNGSI EKSEKUSI HAPUS GLOBAL ===
        function eksekusiHapus() {
            fetch(urlHapusAktif)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('modalHapus')).hide();
                    
                    // Refresh tabel yang sesuai dengan menu yang sedang aktif
                    if (urlHapusAktif.includes('kategori')) fetchKategori();
                    if (urlHapusAktif.includes('penulis')) fetchPenulis();
                    if (urlHapusAktif.includes('artikel')) fetchArtikel();
                } else {
                    alert(data.pesan);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Fetch API untuk Data Artikel
        function fetchArtikel() {
            fetch('ambil_artikel.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('tbody-artikel');
                    tbody.innerHTML = ''; 

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-muted">Belum ada data artikel.</td></tr>';
                        return;
                    }

                    data.forEach(item => {
                        let namaLengkap = item.nama_depan + ' ' + item.nama_belakang;
                        
                        tbody.innerHTML += `
                            <tr>
                                <td>
                                    <img src="uploads_artikel/${item.gambar}" alt="Gambar" width="80" height="60" class="rounded shadow-sm object-fit-cover">
                                </td>
                                <td class="text-start fw-bold">${item.judul}</td>
                                <td><span class="badge bg-info text-dark">${item.nama_kategori}</span></td>
                                <td>${namaLengkap}</td>
                                <td class="small text-muted">${item.hari_tanggal}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editArtikel(${item.id})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="hapusArtikel(${item.id})">Hapus</button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('tbody-artikel').innerHTML = '<tr><td colspan="6" class="text-danger">Gagal memuat data!</td></tr>';
                });
        }
        
        // Fungsi mengambil data Kategori & Penulis
        function loadDropdowns() {
            fetch('ambil_kategori.php')
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Pilih Kategori...</option>';
                data.forEach(k => options += `<option value="${k.id}">${k.nama_kategori}</option>`);
                document.getElementById('select_kategori').innerHTML = options; 
            });
            
            fetch('ambil_penulis.php')
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Pilih Penulis...</option>';
                data.forEach(p => options += `<option value="${p.id}">${p.nama_depan} ${p.nama_belakang}</option>`);
                document.getElementById('select_penulis').innerHTML = options; 
            });
        }

        // Fungsi memunculkan form Tambah Artikel
        function showModalTambahArtikel() {
            document.getElementById('judulModalArtikel').innerText = 'Tambah Artikel';
            document.getElementById('formArtikel').reset();
            document.getElementById('id_artikel').value = '';
            document.getElementById('gambar').required = true; 
            
            loadDropdowns(); 
            
            new bootstrap.Modal(document.getElementById('modalArtikel')).show();
        }

        function editArtikel(id) {
            // proses asinkron
            fetch('ambil_kategori.php').then(res => res.json()).then(dataKategori => {
                let optsKategori = '<option value="">Pilih Kategori...</option>';
                dataKategori.forEach(k => optsKategori += `<option value="${k.id}">${k.nama_kategori}</option>`);
                document.getElementById('select_kategori').innerHTML = optsKategori;
                
                fetch('ambil_penulis.php').then(res => res.json()).then(dataPenulis => {
                    let optsPenulis = '<option value="">Pilih Penulis...</option>';
                    dataPenulis.forEach(p => optsPenulis += `<option value="${p.id}">${p.nama_depan} ${p.nama_belakang}</option>`);
                    document.getElementById('select_penulis').innerHTML = optsPenulis;

                    fetch('ambil_satu_artikel.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('judulModalArtikel').innerText = 'Edit Artikel';
                        document.getElementById('id_artikel').value = data.id;
                        document.getElementById('judul').value = data.judul;
                        document.getElementById('select_penulis').value = data.id_penulis;
                        document.getElementById('select_kategori').value = data.id_kategori;
                        document.getElementById('isi').value = data.isi;
                        
                        document.getElementById('gambar').required = false; 
                        
                        new bootstrap.Modal(document.getElementById('modalArtikel')).show();
                    });
                });
            });
        }

        // Fungsi menyimpan artikel 
        function simpanArtikel(event) {
            event.preventDefault();
            let formData = new FormData(document.getElementById('formArtikel'));
            let idArtikel = document.getElementById('id_artikel').value;
            
            let urlTarget = idArtikel ? 'update_artikel.php' : 'simpan_artikel.php';

            fetch(urlTarget, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.pesan);
                    bootstrap.Modal.getInstance(document.getElementById('modalArtikel')).hide();
                    fetchArtikel(); 
                } else {
                    alert(data.pesan);
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // Fungsi Hapus Artikel
        function hapusArtikel(id) {
            urlHapusAktif = 'hapus_artikel.php?id=' + id;
            new bootstrap.Modal(document.getElementById('modalHapus')).show();
        }
        
    </script>


    <div class="modal fade" id="modalKategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judulModalKategori">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formKategori" onsubmit="simpanKategori(event)">
                        <input type="hidden" id="id_kategori" name="id_kategori">
                        
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Nama kategori" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Deskripsi kategori"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>