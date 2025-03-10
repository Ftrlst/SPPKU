<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<header>
    <div class="logo">
        <img src="logo.png" alt="Logo sppKU">
    </div>
    <div class="user-icon">
        <i class="fas fa-user"></i>
        <i class="fas fa-sign-out-alt"></i>
    </div>
</header>

<div class="dashboard-container">
    <div class="welcome-box">
        <h2>Halo, Fitriani</h2>
        <p>Selamat Datang Di Pembayaran sppKU</p>
    </div>

    <h3>List Tagihan</h3>
    <div class="tagihan-grid">
        <div class="tagihan-item">
            <p>Daftar Ulang</p>
            <span>Rp 600.000</span>
        </div>
        <div class="tagihan-item">
            <p>Pembayaran Praktek</p>
            <span>Rp 600.000</span>
        </div>
        <div class="tagihan-item">
            <p>Pembayaran Ujian</p>
            <span>Rp 600.000</span>
        </div>
        <div class="tagihan-item">
            <p>SPP Bulanan</p>
            <i class="fas fa-money-bill-wave"></i>
            <span>Rp 610.000</span>
            <a href="#">Lihat Rincian</a>
        </div>
    </div>

    <h3>Form Pembayaran</h3>
    <div class="form-pembayaran">
        <button class="btn-form">
            <i class="fas fa-server"></i> Form Input
        </button>
    </div>
</div>

</body>
</html>

