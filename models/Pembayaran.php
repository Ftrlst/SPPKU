<?php
class Pembayaran {
    private $conn;
    private $table_name = "pembayaran";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getNotifikasiPembayaran() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = 'Menunggu Verifikasi'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getRecentPembayaran() {
        $query = "SELECT u.nama_lengkap, t.bulan, p.nominal, p.metode_pembayaran, p.status 
                  FROM pembayaran p 
                  JOIN users u ON p.user_id = u.id_user 
                  JOIN tagihan t ON p.tagihan_id = t.id_tagihan 
                  ORDER BY p.id_pembayaran DESC 
                  LIMIT 5";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
}
?>