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
    public function tambahTagihan($user_id, $jenis_tagihan, $tagihan, $bulan, $tahun) {
        $query = "INSERT INTO tagihan (user_id, jenis_tagihan, tagihan, bulan, tahun, status) 
                  VALUES (:user_id, :jenis_tagihan, :tagihan, :bulan, :tahun, 'Belum Lunas')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':jenis_tagihan', $jenis_tagihan);
        $stmt->bindParam(':tagihan', $tagihan);
        $stmt->bindParam(':bulan', $bulan);
        $stmt->bindParam(':tahun', $tahun);
        
        return $stmt->execute();
    }
    

    public function getAllTagihan() {
        // $query = "SELECT t.id_tagihan, u.nama_lengkap, t.jenis_tagihan, t.tagihan, t.status, t.bulan, t.tahun
        //           FROM " . $this->table_name . " t
        //           JOIN users u ON t.user_id = u.id_user
        //           ORDER BY t.tahun DESC, t.bulan DESC";
        $query = "SELECT t.id_tagihan, t.id_siswa, t.bulan, t.tahun, t.tagihan, t.status, 
       s.nama_siswa AS nama_lengkap 
FROM tagihan t 
JOIN users s ON t.id_siswa = s.id;
";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagihanSPP() {
        $query = "SELECT t.id_tagihan, t.user_id,  t.jenis_tagihan, t.tagihan, t.status, t.bulan, t.tahun, u.nama_lengkap
                  FROM " . $this->table_name . " t
                  JOIN users u ON t.user_id = u.id_user
                  WHERE t.jenis_tagihan = 'SPP'
                  ORDER BY t.tahun DESC, t.bulan DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTagihanBySiswa($id_siswa) {
        $query = "SELECT t.id_tagihan, t.jenis_tagihan, t.tagihan, t.status, t.bulan, t.tahun, u.nama_lengkap 
                  FROM tagihan t
                  JOIN users u ON t.user_id = u.id_user
                  WHERE t.user_id = :id_siswa
                  ORDER BY t.tahun DESC, t.bulan DESC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_siswa", $id_siswa);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
    
    
    
}
?>
