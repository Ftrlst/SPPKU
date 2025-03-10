<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Pastikan pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Inisialisasi koneksi database
$db = new Database();
$conn = $db->conn;

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal!");
}

// Query jumlah jurusan
$query_jurusan = $conn->prepare("SELECT COUNT(DISTINCT jurusan) AS total FROM users");
$query_jurusan->execute();
$result_jurusan = $query_jurusan->get_result();
$jumlah_jurusan = $result_jurusan->fetch_assoc()['total'] ?? 0;

// Query jumlah siswa aktif
$query_siswa = $conn->prepare("SELECT COUNT(*) AS total FROM users WHERE role = ?");
$query_siswa->bind_param("s", $role_siswa);
$role_siswa = "siswa";
$query_siswa->execute();
$result_siswa = $query_siswa->get_result();
$jumlah_siswa = $result_siswa->fetch_assoc()['total'] ?? 0;

// Query notifikasi pembayaran (JOIN users & tagihan)
$query_notif = $conn->prepare("
    SELECT 
        u.nama_lengkap AS nama_siswa, 
        t.jenis_tagihan, 
        p.nominal, 
        p.tanggal_pembayaran, 
        p.jenis_pembayaran 
    FROM pembayaran p
    JOIN users u ON p.user_id = u.id_user
    JOIN tagihan t ON p.tagihan_id = t.id_tagihan
    ORDER BY p.tanggal_pembayaran DESC 
    LIMIT 5
");
$query_notif->execute();
$notifikasi_pembayaran = $query_notif->get_result()->fetch_all(MYSQLI_ASSOC);

// Query rekening
$query_rekening = $conn->prepare("SELECT * FROM rekening LIMIT 1");
$query_rekening->execute();
$rekening = $query_rekening->get_result()->fetch_assoc() ?? [];

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../../assets/img/logo.png" alt="Logo SPPKU">
        </div>
        <div class="user-icon">
            <img src="../../assets/img/person.png" alt="User">
        </div>
    </header>

    <h2 class="fw-bold">DASHBOARD</h2>
    <div class="dashboard-container">
        <div class="date">
            <div class="d-flex justify-content-between">
                <p><strong><?= date('l, d F Y | H:i:s'); ?> | Selamat Datang, Admin</strong></p>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <img src="../../assets/img/sekolah.png" alt="Sekolah">
                <div>
                    <p>Nama Sekolah</p>
                    <h3>SMK BIGHIT DARUSSALAM</h3>
                </div>
            </div>
            <div class="card">
                <img src="../../assets/img/jurusan.png" alt="Jurusan">
                <div>
                    <p>Jumlah Jurusan</p>
                    <h3>5</h3>
                </div>
            </div>
            <div class="card">
                <img src="../../assets/img/person.png" alt="Siswa Aktif">
                <div>
                    <p>Siswa Aktif</p>
                    <h3>500</h3>
                </div>
            </div>
            <div class="form-tagihan">
                <p>Form Input Tagihan</p>
            </div>
        </div>

        <div class="notif-rekening">
        <div class="notif-rekening-box">
                <h3>Notifikasi Pembayaran</h3>
                <?php if (!empty($notifikasi_pembayaran)): ?>
                    <?php foreach ($notifikasi_pembayaran as $notif): ?>
                        <p><?= htmlspecialchars($notif['nama_siswa']); ?> membayar <?= htmlspecialchars($notif['jenis_tagihan']); ?> 
                        senilai Rp <?= number_format($notif['nominal'], 0, ',', '.'); ?>  
                        | <?= date('d/m/Y | H:i:s', strtotime($notif['tanggal_pembayaran'])); ?> | <?= htmlspecialchars($notif['jenis_pembayaran']); ?></p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Belum ada pembayaran terbaru.</p>
                <?php endif; ?>
            </div>

        <div class="notif-rekening-box">
                <h3>No Rekening</h3>
                <?php if ($rekening): ?>
                    <p>Dana: <?= htmlspecialchars($rekening['dana'] ?? 'Belum tersedia'); ?> <a href="rekening.php">✏️</a></p>
                    <p>GoPay: <?= htmlspecialchars($rekening['gopay'] ?? 'Belum tersedia'); ?> <a href="rekening.php">✏️</a></p>
                    <p>ShopeePay: <?= htmlspecialchars($rekening['shopeepay'] ?? 'Belum tersedia'); ?> <a href="rekening.php">✏️</a></p>
                <?php else: ?>
                    <p>Data rekening belum tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </div>
</body>