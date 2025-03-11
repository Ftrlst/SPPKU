<?php
class Database {
    private $host = "localhost";
    private $db_name = "spp_db";
    private $username = "root";
    private $password = "fitriSQL";
    private $port = "3306"; // Pastikan port sesuai dengan Workbench
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>