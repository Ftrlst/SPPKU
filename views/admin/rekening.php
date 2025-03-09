<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Cek sesi admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Koneksi database
$db = new Database();
$conn = $db->conn;

// Ambil data rekening
$result = $conn->query("SELECT * FROM rekening LIMIT 1");
$rekening = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Rekening</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Kelola Rekening Pembayaran</h2>
    <form action="update_rekening.php" method="POST">
        <label>Dana:</label>
        <input type="text" name="dana" value="<?= $rekening['dana']; ?>" required>
        
        <label>GoPay:</label>
        <input type="text" name="gopay" value="<?= $rekening['gopay']; ?>" required>

        <label>ShopeePay:</label>
        <input type="text" name="shopeepay" value="<?= $rekening['shopeepay']; ?>" required>

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
