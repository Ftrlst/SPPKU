<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "fitriSQL"; 
    private $dbname = "spp_db";
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>