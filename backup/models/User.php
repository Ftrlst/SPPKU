<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function login($username, $password) {
        // $query = "SELECT id_user, username, role, password FROM users WHERE username = :username";
        // $stmt = $this->conn->prepare($query);
        // $stmt->bindParam(":username", $username);
        // $stmt->execute();
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return "Password salah!";
            }
        } else {
            return "Username tidak ditemukan!";
        }
    }

    public function getAllAdmins() {
        $query = "SELECT * FROM admin";
        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
