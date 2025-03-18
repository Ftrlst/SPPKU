<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Tagihan.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

// Pastikan siswa sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../login.php");
    exit;
}

// Inisialisasi koneksi database
$db = new Database();
$conn = $db->getConnection();

// Ambil data siswa
$user = new User($conn);
$siswa = $user->getSiswaById($_SESSION['user_id']);

// Jika data siswa tidak ditemukan
if (!$siswa) {
    echo "Data siswa tidak ditemukan.";
    exit;
}

// Ambil data tagihan dan pembayaran siswa
$tagihan = new Tagihan($conn);
$totalTagihan = $tagihan->getTotalTagihanBySiswa($_SESSION['user_id']);

$pembayaran = new Pembayaran($conn);
$riwayatPembayaran = $pembayaran->getPembayaranBySiswa($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="../../assets/css/dashboardsiswa.css">
</head>
<body>
    <div class="container">
        <main>
            <div class="welcome">
                <h2>Halo, <?php echo htmlspecialchars($siswa['nama_lengkap']); ?></h2>
                <p>Selamat Datang di Pembayaran sppKU</p>
            </div>
            
            <section class="list-tagihan">
                <h3>List Tagihan</h3>
                <div class="tagihan-container">
                    <?php foreach ($tagihan->getTagihanBySiswa($_SESSION['user_id']) as $t): ?>
                        <div class="tagihan-card">
                            <div class="tagihan-icon">💰</div>
                            <p><?php echo htmlspecialchars($t['jenis_tagihan']); ?></p>
                            <h4>Rp <?php echo number_format($t['tagihan'], 0, ',', '.'); ?></h4>
                            <?php if ($t['jenis_tagihan'] == 'SPP Bulanan'): ?>
                                <a href="rincian.php?id=<?php echo $t['id_tagihan']; ?>">Lihat Rincian</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            
            <section class="form-pembayaran">
                <h3>Form Pembayaran</h3>
                <a href="form_pembayaran.php" class="form-button">
                    <span class="form-icon">📝</span> Form Input
                </a>
            </section>
        </main>
    </div>
</body>
</html>
