<?php
require_once __DIR__ . '/../config/database.php';

class Pembayaran {
    private $conn;
    private $table_name = "pembayaran";

    public $id_pembayaran;
    public $tagihan_id;
    public $user_id;
    public $metode_pembayaran;
    public $nomor_tujuan;
    public $nominal;
    public $bukti_pembayaran;
    public $status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Fungsi untuk menambahkan pembayaran baru
    public function tambahPembayaran() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (tagihan_id, user_id, metode_pembayaran, nomor_tujuan, nominal, bukti_pembayaran, status)
                  VALUES (:tagihan_id, :user_id, :metode_pembayaran, :nomor_tujuan, :nominal, :bukti_pembayaran, 'Menunggu Verifikasi')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tagihan_id", $this->tagihan_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":metode_pembayaran", $this->metode_pembayaran);
        $stmt->bindParam(":nomor_tujuan", $this->nomor_tujuan);
        $stmt->bindParam(":nominal", $this->nominal);
        $stmt->bindParam(":bukti_pembayaran", $this->bukti_pembayaran);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
