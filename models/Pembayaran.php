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
}
?>