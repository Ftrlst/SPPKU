<?php
class Tagihan {
    private $conn;
    private $table_name = "tagihan";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Menghitung total jumlah tagihan yang ada
    public function getTotalTagihan() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Menambahkan tagihan baru
    public function tambahTagihan($user_id, $jenis_tagihan, $nominal, $bulan, $tahun) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, jenis_tagihan, tagihan, status, bulan, tahun) 
                  VALUES (:user_id, :jenis_tagihan, :tagihan, 'Belum Lunas', :bulan, :tahun)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':jenis_tagihan', $jenis_tagihan);
        $stmt->bindParam(':tagihan', $nominal);
        $stmt->bindParam(':bulan', $bulan);
        $stmt->bindParam(':tahun', $tahun);

        return $stmt->execute();
    }
}
?>
