<?php include 'header.php'; ?>
<?php include "sidebar.php"; ?>
<link rel="stylesheet" href="../../assets/css/daftar_siswa.css">
<div class="container">
    <h2>Daftar Siswa</h2>
    <button id="tambahSiswa">+ Tambah Siswa</button>
    <input type="text" id="searchInput" placeholder="Cari siswa...">
    
    <table>
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Nama Ibu</th>
                <th>Jurusan</th>
                <th>Kelas</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="siswaTableBody"></tbody>
    </table>

    <div id="pagination"></div>
</div>

<!-- Pop-up Form Tambah Siswa -->
<div id="popupForm" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeForm()">&times;</span>
        <h2>Tambah Siswa</h2>
        <form id="formTambahSiswa" method="POST" enctype="multipart/form-data">
            <label>Nama Siswa:</label>
            <input type="text" name="nama_lengkap" required>

            <label>Nama Ibu:</label>
            <input type="text" name="nama_ibu" required>

            <label>Kelas:</label>
            <input type="text" name="kelas" required>

            <label>Jurusan:</label>
            <input type="text" name="jurusan" required>

            <label>Tahun Ajaran:</label>
            <input type="text" name="tahun_ajaran" required>

            <label>NIS:</label>
            <input type="text" name="NIS" required>

            <label>No Telepon:</label>
            <input type="text" name="no_telepon" required>

            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Foto:</label>
            <input type="file" name="foto_profil" accept="image/*" required>

            <button type="submit">Simpan</button>
        </form>
    </div>
</div>

<script>
document.getElementById("tambahSiswa").addEventListener("click", function() {
    document.getElementById("popupForm").style.display = "block";
});

function closeForm() {
    document.getElementById("popupForm").style.display = "none";
}
</script>
<script src="../../assets/js/sidebar.js"></script>
<script src="../../assets/js/daftar_siswa.js"></script>
