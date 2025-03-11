<?php 
session_start();
require_once __DIR__ . '/../../config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="fw-bold">Daftar Siswa</h2>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- <a href="index.php?page=tambah_siswa" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Siswa
        </a> -->
        <button id="btnTambahSiswa" class="btn-primary">+ Tambah Siswa</button>

<!-- Modal -->
<div id="modalTambahSiswa" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Tambah Siswa</h2>
        <form id="formTambahSiswa" enctype="multipart/form-data" action="../../controllers/daftarSiswa.php" method="post">
        <label for="username">Username:</label>
    <input type="text" name="username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <label for="nama_lengkap">Nama Lengkap:</label>
    <input type="text" name="nama_lengkap" required>

    <label for="NIS">NIS:</label>
    <input type="text" name="NIS" required>

    <label for="nama_ibu">Nama Ibu:</label>
    <input type="text" name="nama_ibu" required>

    <label for="kelas">Kelas:</label>
    <select name="kelas" required>
        <option value="X">X</option>
        <option value="XI">XI</option>
        <option value="XII">XII</option>
    </select>

    <label for="tahun_ajaran">Tahun Ajaran:</label>
    <input type="text" name="tahun_ajaran" required>

    <label for="no_telepon">No. Telepon:</label>
    <input type="text" name="no_telepon" required>

    <label for="jurusan">Jurusan:</label>
    <select name="jurusan" required>
        <option value="RPL">RPL</option>
        <option value="TO">TO</option>
        <option value="TM">TM</option>
        <option value="TE">TE</option>
        <option value="TKL">TKL</option>
    </select>

    <button type="submit">Tambah Siswa</button>
        </form>
    </div>
</div>


        <!-- <div class="input-group search-box">
            <input type="text" id="search" class="form-control" placeholder="Search....">
            <button class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
        </div> -->
</div>

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-primary">
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Nama Ibu</th>
                    <th>Jurusan</th>
                    <th>Kelas</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (isset($data_siswa) && !empty($data_siswa)) :
                    foreach ($data_siswa as $siswa) : ?>
                        <tr>
                            <td><?= htmlspecialchars($siswa['NIS']); ?></td>
                            <td><?= htmlspecialchars($siswa['nama_lengkap']); ?></td>
                            <td><?= htmlspecialchars($siswa['nama_ibu']); ?></td>
                            <td><?= htmlspecialchars($siswa['jurusan']); ?></td>
                            <td><?= htmlspecialchars($siswa['kelas']); ?></td>
                            <td>
                                <a href="index.php?page=view_siswa&id=<?= $siswa['id_user']; ?>" class="btn btn-light btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="index.php?page=edit_siswa&id=<?= $siswa['id_user']; ?>" class="btn btn-light btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?page=hapus_siswa&id=<?= $siswa['id_user']; ?>" class="btn btn-light btn-sm text-danger" onclick="return confirm('Yakin ingin menghapus?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data siswa</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <nav class="pagination-container">
        <ul class="pagination">
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item disabled"><a class="page-link" href="#">.....</a></li>
        </ul>
    </nav>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    let modal = document.getElementById("modalTambahSiswa");
    let btnOpen = document.getElementById("btnTambahSiswa");
    let btnClose = document.getElementById("closeModal");

    // Tampilkan modal saat tombol diklik
    btnOpen.addEventListener("click", function () {
        modal.style.display = "block";
    });

    // Tutup modal saat tombol close diklik
    btnClose.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Tutup modal jika klik di luar modal-content
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Form submission dengan AJAX
    document.getElementById("formTambahSiswa").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("../../controllers/SiswaController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                modal.style.display = "none";
                location.reload(); // Reload halaman jika sukses
            }
        })
        .catch(error => console.error("Error:", error));
    });
});


document.addEventListener("DOMContentLoaded", function () {
    let modal = document.getElementById("modalTambahSiswa");
    let btnOpen = document.getElementById("btnTambahSiswa");
    let btnClose = document.getElementById("closeModal");

    btnOpen.addEventListener("click", function () {
        modal.style.display = "block";
    });

    btnClose.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    document.getElementById("formTambahSiswa").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("../../controllers/SiswaController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                form.reset(); // Reset form setelah submit sukses
                modal.style.display = "none";
                location.reload();
            }
        })
        .catch(error => console.error("Error:", error));
    });
});


</script>
</body>
</html>
