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
    <title>Dashboard Siswa</title>
</head>
<body>
    <h2>Selamat Datang di Dashboard Siswa!</h2>
    <a href="../logout.php">Logout</a>
</body>
</html>
