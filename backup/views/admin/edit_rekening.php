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
$conn = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_rekening = $_POST['id_rekening'] ?? null;
    $nomor_rekening = trim($_POST['nomor_rekening'] ?? '');
    $atas_nama = trim($_POST['atas_nama'] ?? '');

    // Validasi input
    if (!$id_rekening || empty($nomor_rekening) || empty($atas_nama)) {
        echo "<script>alert('Semua kolom harus diisi!'); window.location='dashboard.php';</script>";
        exit();
    }

    try {
        $stmt = $conn->prepare("UPDATE rekening SET nomor_rekening = :nomor_rekening, atas_nama = :atas_nama WHERE id_rekening = :id_rekening");
        $stmt->bindParam(":nomor_rekening", $nomor_rekening, PDO::PARAM_STR);
        $stmt->bindParam(":atas_nama", $atas_nama, PDO::PARAM_STR);
        $stmt->bindParam(":id_rekening", $id_rekening, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Rekening berhasil diperbarui!'); window.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui rekening!'); window.location='dashboard.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "'); window.location='dashboard.php';</script>";
    }
}
?>
