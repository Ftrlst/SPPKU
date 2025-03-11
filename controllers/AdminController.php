<?php
session_start();
include_once "../config/Database.php";
include_once "../models/User.php";
include_once "../models/Pembayaran.php";
include_once "../models/Rekening.php";

$db = new Database();
$conn = $db->getConnection();

$user = new User($conn);
$pembayaran = new Pembayaran($conn);
$rekening = new Rekening($conn);

$total_siswa = $user->countSiswaAktif();
$total_notifikasi = $pembayaran->getNotifikasiPembayaran();
$daftar_rekening = $rekening->getRekening();
?>