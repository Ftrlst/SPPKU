<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Memanggil database yang sudah benar
    }

    public function login($username, $password) {
        $conn = $this->db->conn;
        $query = "SELECT id_user, username, role, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                return $user;
            }else {
                die("Password salah!"); // Debugging
            }         
        }else {
            die("Username tidak ditemukan!"); // Debugging
        }
        // return false;
    }

    public function getAllAdmins() {
        $conn = $this->db->conn;
        $query = "SELECT * FROM admin";
        $stmt = $conn->query($query);

        $admins = [];
        while ($row = $stmt->fetch_assoc()) {
            $admins[] = $row;
        }

        return $admins;
    }
}
?>
