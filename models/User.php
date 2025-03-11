<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id_user;
    public $username;
    public $password;
    public $role;
    public $nama_lengkap;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        return $stmt;
    }
}
?>