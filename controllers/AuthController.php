<?php
session_start();
include_once "../config/Database.php";
include_once "../models/User.php";

$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    $stmt = $user->login($username, $password);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row && $password === $row['password']) { 
        $_SESSION['user_id'] = $row['id_user'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'admin') {
            header("Location: ../views/admin/dashboard.php");
        } else {
            header("Location: ../views/siswa/dashboard.php");
        }
        exit;
    } else {
        echo "Login gagal!";
    }
}

?>
