<?php
require_once __DIR__ . '/../ajax/pembayaran.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pembayaran = new Pembayaran();

    $pembayaran->tagihan_id = $_POST['tagihan_id'];
    $pembayaran->user_id = $_POST['user_id'];
    $pembayaran->metode_pembayaran = $_POST['metode_pembayaran'];
    $pembayaran->nomor_tujuan = $_POST['nomor_tujuan'];
    $pembayaran->nominal = $_POST['nominal'];

    // Upload bukti pembayaran
    $target_dir = "../uploads/bukti_pembayaran/";
    $file_name = time() . "_" . basename($_FILES["bukti_pembayaran"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
        $pembayaran->bukti_pembayaran = $file_name;

        if ($pembayaran->tambahPembayaran()) {
            echo "<script>alert('Pembayaran berhasil dikirim!'); window.location='../views/siswa/dashboard.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan pembayaran!');</script>";
        }
    } else {
        echo "<script>alert('Gagal mengunggah bukti pembayaran!');</script>";
    }
}
?>
