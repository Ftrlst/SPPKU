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

// Update data rekening
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dana = $_POST['dana'];
    $gopay = $_POST['gopay'];
    $shopeepay = $_POST['shopeepay'];

    $sql = "UPDATE rekening SET dana='$dana', gopay='$gopay', shopeepay='$shopeepay' WHERE id=1";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Rekening berhasil diperbarui!'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
