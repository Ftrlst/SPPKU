<?php
session_start();
require_once "../config/database.php";

// Buat instance database
$db = new Database();
$conn = $db->conn; // Ambil koneksi dari class Database

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];
    
        // Cek apakah file dashboard sudah ada
        $admin_dashboard = "../views/admin/dashboard.php";
        $siswa_dashboard = "../views/siswa/dashboard.php";
    
        if ($user['role'] === 'admin' && file_exists($admin_dashboard)) {
            header("Location: $admin_dashboard");
            exit();
        } elseif ($user['role'] === 'siswa' && file_exists($siswa_dashboard)) {
            header("Location: $siswa_dashboard");
            exit();
        } else {
            $error = "Login berhasil, tetapi dashboard belum tersedia!";
        }
    }else {
        $error = "Username atau password salah!";
    }
    
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>LOGIN</h2>
            <?php if ($error) : ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
            <form action="../controllers/login_process.php" method="POST">
                <label>Username</label>
                <input type="text" name="username" required>
                <label>Password</label>
                <input type="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="login-image">
            <img src="../assets/img/hiasan.png" alt="Login Image">
        </div>
    </div>
</body>
</html>

