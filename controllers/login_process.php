<?php
// ob_start();
require_once "../controllers/AuthController.php";

// Debug: Periksa apakah data dikirim dari form login
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Akses tidak sah!");
}

// Debug: Pastikan username dan password dikirim
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die("Harap isi username dan password!");
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Debug: Tampilkan username dan password yang dikirim
// echo "Username: " . htmlspecialchars($username) . "<br>";
// echo "Password: " . htmlspecialchars($password) . "<br>";

// Panggil AuthController untuk proses login
$auth = new AuthController();
$auth->login($username, $password);

// Debug: Jika tidak terjadi redirect, pastikan kode di dalam AuthController berjalan dengan baik
// echo "Login process selesai. Jika tidak berpindah halaman, periksa header location.";
// ob_end_flush();
?>
