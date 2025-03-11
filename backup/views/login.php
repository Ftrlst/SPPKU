<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];

        $redirect_url = ($user['role'] === 'admin') ? "../views/admin/dashboard.php" : "../views/siswa/dashboard.php";

        header("Location: $redirect_url");
        exit();
    } else {
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

