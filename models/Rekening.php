<?php
class Rekening {
    private $conn;
    private $table_name = "rekening";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getRekening() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRekening() {
        $query = "SELECT jenis_pembayaran, nomor_rekening, atas_nama FROM rekening";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
?>
