<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Pastikan pengguna sudah login sebagai admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Inisialisasi koneksi database
$db = new Database();
$conn = $db->getConnection();

// Query jumlah jurusan
$query_jurusan = $conn->query("SELECT COUNT(DISTINCT jurusan) AS total FROM users");
$jumlah_jurusan = $query_jurusan->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Query jumlah siswa aktif
$query_siswa = $conn->prepare("SELECT COUNT(*) AS total FROM users WHERE role = :role");
$query_siswa->execute(['role' => 'siswa']);
$jumlah_siswa = $query_siswa->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Query notifikasi pembayaran
$query_notif = $conn->query("SELECT u.nama_lengkap, t.jenis_tagihan, p.nominal, p.tanggal_pembayaran, p.metode_pembayaran
    FROM pembayaran p
    JOIN users u ON p.user_id = u.id_user
    JOIN tagihan t ON p.tagihan_id = t.id_tagihan
    ORDER BY p.tanggal_pembayaran DESC 
    LIMIT 5");
$notifikasi_pembayaran = $query_notif->fetchAll(PDO::FETCH_ASSOC);

// Query rekening
$query_rekening = $conn->query("SELECT * FROM rekening");
$rekening_list = $query_rekening->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
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

<h2>DASHBOARD</h2>

<div class="dashboard-container">
    <p><strong><?= date('l, d F Y | H:i:s'); ?> | Selamat Datang, Admin</strong></p>

    <div class="dashboard-grid">
        <div class="card">
            <img src="../../assets/img/jurusan.png" alt="Jurusan">
            <p>Jumlah Jurusan</p>
            <h3><?= htmlspecialchars($jumlah_jurusan); ?></h3>
        </div>
        <div class="card">
            <img src="../../assets/img/person.png" alt="Siswa Aktif">
            <p>Siswa Aktif</p>
            <h3><?= htmlspecialchars($jumlah_siswa); ?></h3>
        </div>
    </div>

    <div class="notif-rekening">
        <div class="notif-rekening-box">
            <h3>Notifikasi Pembayaran</h3>
            <?php if (!empty($notifikasi_pembayaran)): ?>
                <?php foreach ($notifikasi_pembayaran as $notif): ?>
                    <p><?= htmlspecialchars($notif['nama_lengkap']); ?> membayar <?= htmlspecialchars($notif['jenis_tagihan']); ?> 
                    Rp <?= number_format($notif['nominal'], 0, ',', '.'); ?> | 
                    <?= date('d/m/Y H:i', strtotime($notif['tanggal_pembayaran'])); ?> | 
                    <?= htmlspecialchars($notif['metode_pembayaran']); ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada pembayaran terbaru.</p>
            <?php endif; ?>
        </div>

        <div class="notif-rekening-box">
            <h3>No Rekening</h3>
            <?php foreach ($rekening_list as $rek): ?>
                <p>
                    <?= htmlspecialchars($rek['jenis_pembayaran']); ?>: 
                    <?= htmlspecialchars($rek['nomor_rekening']); ?>
                    <button class="edit-btn" data-id="<?= $rek['id_rekening']; ?>"
                            data-jenis="<?= htmlspecialchars($rek['jenis_pembayaran']); ?>"
                            data-nomor="<?= htmlspecialchars($rek['nomor_rekening']); ?>"
                            data-atasnama="<?= htmlspecialchars($rek['atas_nama']); ?>">✏️</button>
                </p>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            const jenis = this.getAttribute("data-jenis");
            const nomor = this.getAttribute("data-nomor");
            const atasNama = this.getAttribute("data-atasnama");

            document.getElementById("edit_id").value = id;
            document.getElementById("edit_jenis").value = jenis;
            document.getElementById("edit_nomor").value = nomor;
            document.getElementById("edit_atasnama").value = atasNama;

            document.getElementById("modalRekening").style.display = "block";
        });
    });
});
</script>

</body>
</html>
