<?php
include '../../config/Database.php';
include '../../models/User.php';

// Cek apakah ada parameter NIS
if (!isset($_GET['nis'])) {
    echo "NIS tidak ditemukan.";
    exit;
}

$nis = $_GET['nis'];

// Koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi model Siswa
$siswa = new User($db);
$siswaData = $siswa->getDetailSiswa($nis);

// Jika data tidak ditemukan
if (!$siswaData) {
    echo "Data siswa tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa</title>
    <link rel="stylesheet" href="../../assets/css/edit_siswa.css">
</head>
<body>
    <h2>Edit Data Siswa</h2>
    <form id="formEditSiswa" action="../../controllers/SiswaController.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="edit" value="true">
        <label>Tahun Ajaran:</label>
        <input type="text" name="tahun_ajaran" value="<?php echo htmlspecialchars($siswaData['tahun_ajaran']); ?>" required>
        
        <label>NIS:</label>
        <input type="text" name="NIS" value="<?php echo htmlspecialchars($siswaData['NIS']); ?>" readonly>
        
        <label>Nama Siswa:</label>
        <input type="text" name="nama_lengkap" value="<?php echo htmlspecialchars($siswaData['nama_lengkap']); ?>" required>
        
        <label>Nama Ibu Kandung:</label>
        <input type="text" name="nama_ibu" value="<?php echo htmlspecialchars($siswaData['nama_ibu']); ?>" required>
        
        <label>Kelas:</label>
        <input type="text" name="kelas" value="<?php echo htmlspecialchars($siswaData['kelas']); ?>" required>
        
        <label>Jurusan:</label>
        <input type="text" name="jurusan" value="<?php echo htmlspecialchars($siswaData['jurusan']); ?>" required>
        
        <label>No. Telepon:</label>
        <input type="text" name="no_telepon" value="<?php echo htmlspecialchars($siswaData['no_telepon']); ?>" required>
        
        <label>Foto Profil:</label>
        <input type="file" name="foto_profil" accept="image/*">
        <img src="/sppku/uploads/<?php echo htmlspecialchars($siswaData['foto_profil']); ?>" width="100" height="100" alt="Foto Siswa">
        <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($siswaData['foto_profil']); ?>">

        
        <button type="submit">Simpan Perubahan</button>
    </form>
    
    <script src="../../assets/js/edit_siswa.js"></script>
</body>
</html>
