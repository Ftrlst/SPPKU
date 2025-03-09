<?php
session_start();
// ob_start(); 
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function login($username, $password) {
        $user = $this->user->login($username, $password);
        if ($user) {
            // session_start();
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['role'] = $user['role']; // admin atau siswa

            echo "<pre>";
        print_r($_SESSION); // Menampilkan isi sesi untuk debugging
        echo "</pre>";

        // exit(); // Hentikan sementara untuk mengecek apakah sesi tersimpan

            if ($user['role'] === 'admin') {
                if (headers_sent()) {
                    die("Headers sudah dikirim! Redirect gagal.");
                }
                header("Location: ../views/admin/dashboard.php");
                exit();
            } else {
                if (headers_sent()) {
                    die("Headers sudah dikirim! Redirect gagal.");
                }
                header("Location: ../views/siswa/dashboard.php");
                exit();
            }
            // var_dump($_SESSION); // Debugging
            // exit(); // Hentikan eksekusi agar kita bisa melihat hasilnya
            
        } else {
            header("Location: ../views/login.php?error=Invalid credentials");
            exit();
        }
    }
}
// ob_end_flush();
?>
