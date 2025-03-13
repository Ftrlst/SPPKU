<?php
include '../../config/Database.php';
include '../../models/User.php';
include '../../models/Tagihan.php';

// Pastikan parameter NIS ada
if (!isset($_GET['nis']) || empty($_GET['nis'])) {
    echo "NIS tidak ditemukan.";
    exit;
}

$nis = $_GET['nis']; // Ambil NIS dari URL

// Koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi model Siswa
$siswa = new User($db);
$siswaData = $siswa->getDetailSiswa($nis);

// Jika data siswa tidak ditemukan
if (!$siswaData) {
    echo "Data siswa tidak ditemukan.";
    exit;
}

// Inisialisasi model Tagihan
$tagihanModel = new Tagihan($db);
$tagihanSiswa = $tagihanModel->getTagihanBySiswa($nis);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Siswa</title>
    <link rel="stylesheet" href="../../assets/css/view_siswa.css">
</head>
<body>
    <div class="container">
        <h2>Data Siswa</h2>
        <div class="table-container">
            <table>
                <tr><th>Tahun Ajaran</th><td><?= htmlspecialchars($siswaData['tahun_ajaran']); ?></td></tr>
                <tr><th>NIS</th><td><?= htmlspecialchars($siswaData['NIS']); ?></td></tr>
                <tr><th>Nama Siswa</th><td><?= htmlspecialchars($siswaData['nama_lengkap']); ?></td></tr>
                <tr><th>Nama Ibu Kandung</th><td><?= htmlspecialchars($siswaData['nama_ibu']); ?></td></tr>
                <tr><th>Kelas</th><td><?= htmlspecialchars($siswaData['kelas']); ?></td></tr>
                <tr><th>Jurusan</th><td><?= htmlspecialchars($siswaData['jurusan']); ?></td></tr>
                <tr><th>No. Telepon</th><td><?= htmlspecialchars($siswaData['no_telepon']); ?></td></tr>
                <tr><th>Foto Profil</th>
                    <td>
                        <?php if (!empty($siswaData['foto_profil'])): ?>
                            <img src="/sppku/uploads/<?= basename($siswaData['foto_profil']); ?>" alt="Foto Siswa">
                        <?php else: ?>
                            <img src="../uploads/default.png" alt="Foto Default">
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        <h2>Tagihan Siswa</h2>
        <table border="1">
            <tr>
                <th>No</th>
                <th>Jenis Tagihan</th>
                <th>Jumlah Tagihan</th>
                <th>Sudah Dibayar</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php if ($tagihanSiswa): ?>
                <?php $no = 1; foreach ($tagihanSiswa as $tagihan): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($tagihan['jenis_tagihan']) . ' ' . htmlspecialchars($tagihan['tahun']); ?></td>
                        <td>Rp.<?= number_format($tagihan['tagihan'], 0, ',', '.'); ?></td>
                        <td>Rp.<?= number_format($tagihan['sudah_dibayar'] ?? 0, 0, ',', '.'); ?></td>
                        <td style="color: <?= $tagihan['status'] == 'Lunas' ? 'green' : 'red'; ?>;">
                            <?= htmlspecialchars($tagihan['status']); ?>
                        </td>
                        <td><a href="bayar.php?id=<?= $tagihan['id_tagihan']; ?>">Bayar</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">Tidak ada tagihan.</td></tr>
            <?php endif; ?>
        </table>
        <a href="daftar_siswa.php">Kembali</a>
    </div>
</body>
</html>
