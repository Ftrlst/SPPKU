<?php
class Tagihan {
    private $conn;
    private $table_name = "tagihan";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTotalTagihan() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    public function tambahTagihan($user_id, $jenis_tagihan, $tagihan, $bulan, $tahun) {
        $query = "INSERT INTO tagihan (user_id, jenis_tagihan, tagihan, sudah_dibayar, bulan, tahun, status) 
                  VALUES (:user_id, :jenis_tagihan, :tagihan, 0, :bulan, :tahun, 'Belum Lunas')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':jenis_tagihan', $jenis_tagihan);
        $stmt->bindParam(':tagihan', $tagihan);
        $stmt->bindParam(':bulan', $bulan);
        $stmt->bindParam(':tahun', $tahun);
        
        return $stmt->execute();
    }
    
    public function getAllTagihan() {
        $query = "SELECT t.id_tagihan, t.user_id, t.bulan, t.tahun, t.tagihan, t.sudah_dibayar, t.status, 
                         u.nama_lengkap 
                  FROM tagihan t 
                  JOIN users u ON t.user_id = u.id_user";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagihanSPP() {
        $query = "SELECT t.id_tagihan, t.user_id, t.jenis_tagihan, t.tagihan, t.sudah_dibayar, t.status, t.bulan, t.tahun, u.nama_lengkap
                  FROM " . $this->table_name . " t
                  JOIN users u ON t.user_id = u.id_user
                  WHERE t.jenis_tagihan = 'SPP'
                  ORDER BY t.tahun DESC, t.bulan DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagihanBySiswa($id_siswa) {
        $query = "SELECT t.id_tagihan, t.jenis_tagihan, t.tagihan, t.sudah_dibayar, t.status, t.bulan, t.tahun, u.nama_lengkap 
                  FROM tagihan t
                  JOIN users u ON t.user_id = u.id_user
                  WHERE t.user_id = :id_siswa
                  ORDER BY t.tahun DESC, t.bulan DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_siswa", $id_siswa);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagihanById($id_tagihan) {
        $query = "SELECT id_tagihan, jenis_tagihan, tagihan, 
                         (SELECT COALESCE(SUM(nominal), 0) FROM pembayaran WHERE id_tagihan = t.id_tagihan) AS sudah_dibayar
                  FROM tagihan t 
                  WHERE id_tagihan = :id_tagihan";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_tagihan', $id_tagihan, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    
      // Ambil total tagihan yang belum lunas untuk siswa tertentu
      public function getTotalTagihanBySiswa($user_id) {
        $query = "SELECT SUM(t.tagihan) as total_tagihan 
                  FROM tagihan t
                  WHERE t.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total_tagihan'] : 0;
    }
    

    

    // Mengambil total tagihan dalam satu tahun untuk dashboard siswa
    public function getTotalTagihanByYear($id_siswa, $tahun) {
        $query = "SELECT SUM(tagihan) as total_tagihan, 
                         SUM(sudah_dibayar) as total_dibayar 
                  FROM tagihan 
                  WHERE user_id = :id_siswa AND tahun = :tahun";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_siswa", $id_siswa);
        $stmt->bindParam(":tahun", $tahun);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>
