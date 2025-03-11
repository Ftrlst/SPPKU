<?php
require_once '../config/database.php';
require_once '../controllers/daftarSiswa.php';

// Inisialisasi koneksi
$database = new Database();
$db = $database->getConnection();

$adminController = new AdminController($db);

$page = $_GET['page'] ?? 'home';

if ($page == 'daftar_siswa') {
    $adminController->daftarSiswa();
} else {
    echo "<h2 class='text-center mt-5'>Selamat Datang di Aplikasi Pembayaran SPP</h2>";
}
?>
