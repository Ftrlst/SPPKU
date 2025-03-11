<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function countSiswaAktif() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE role = 'siswa'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt;
    }

    public function getJumlahJurusan() {
        $query = "SELECT COUNT(DISTINCT jurusan) AS total FROM users WHERE role = 'siswa'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    public function getJumlahSiswaAktif() {
        $query = "SELECT COUNT(*) AS total FROM users WHERE role = 'siswa'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
}
?>