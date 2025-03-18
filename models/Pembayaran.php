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
    
    // public function tambahPembayaran($id_tagihan, $nominal, $metode_pembayaran, $bukti_pembayaran) {
    //     try {
    //         $query = "INSERT INTO pembayaran (id_tagihan, nominal, metode_pembayaran, bukti_pembayaran, status, tanggal_pembayaran) 
    //                   VALUES (:id_tagihan, :nominal, :metode_pembayaran, :bukti_pembayaran, 'Menunggu Verifikasi', NOW())";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(":id_tagihan", $id_tagihan);
    //         $stmt->bindParam(":nominal", $nominal);
    //         $stmt->bindParam(":metode_pembayaran", $metode_pembayaran);
    //         $stmt->bindParam(":bukti_pembayaran", $bukti_pembayaran);
    
    //         return $stmt->execute();
    //     } catch (Exception $e) {
    //         return false;
    //     }
    // }
    // public function tambahPembayaran($user_id, $id_tagihan, $metode_pembayaran, $nomor_tujuan, $nominal, $bukti_pembayaran, $status) {
    //     try {
    //         $query = "INSERT INTO pembayaran (user_id, tagihan_id, metode_pembayaran, nomor_tujuan, nominal, bukti_pembayaran, status, tanggal_pembayaran) 
    //                   VALUES (:user_id, :tagihan_id, :metode_pembayaran, :nomor_tujuan, :nominal, :bukti_pembayaran, :status, NOW())";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(":user_id", $user_id);
    //         $stmt->bindParam(":tagihan_id", $id_tagihan);
    //         $stmt->bindParam(":metode_pembayaran", $metode_pembayaran);
    //         $stmt->bindParam(":nomor_tujuan", $nomor_tujuan);
    //         $stmt->bindParam(":nominal", $nominal);
    //         $stmt->bindParam(":bukti_pembayaran", $bukti_pembayaran);
    //         $stmt->bindParam(":status", $status);
    
    //         return $stmt->execute();
    //     } catch (Exception $e) {
    //         return false;
    //     }
    // }

    public function tambahPembayaran($user_id, $id_tagihan, $metode_pembayaran, $nomor_tujuan, $nominal, $bukti_pembayaran, $status) {
        try {
            $query = "INSERT INTO pembayaran (user_id, tagihan_id, metode_pembayaran, nomor_tujuan, nominal, bukti_pembayaran, status, tanggal_pembayaran) 
                      VALUES (:user_id, :tagihan_id, :metode_pembayaran, :nomor_tujuan, :nominal, :bukti_pembayaran, :status, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":tagihan_id", $id_tagihan);
            $stmt->bindParam(":metode_pembayaran", $metode_pembayaran);
            $stmt->bindParam(":nomor_tujuan", $nomor_tujuan);
            $stmt->bindParam(":nominal", $nominal);
            $stmt->bindParam(":bukti_pembayaran", $bukti_pembayaran);
            $stmt->bindParam(":status", $status);
    
            if ($stmt->execute()) {
                return true;
            } else {
                var_dump($stmt->errorInfo()); // Tambahkan debugging error SQL
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    

    
    public function prosesPembayaran($id_tagihan, $nominal) {
        $query = "SELECT tagihan, sudah_dibayar FROM tagihan WHERE id_tagihan = :id_tagihan";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_tagihan", $id_tagihan);
        $stmt->execute();
        $tagihan = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$tagihan) {
            return ["success" => false, "message" => "Tagihan tidak ditemukan."];
        }
    
        $total_dibayar = $tagihan['sudah_dibayar'] + $nominal;
        $status = ($total_dibayar >= $tagihan['tagihan']) ? 'Lunas' : 'Belum Lunas';
    
        $query_update = "UPDATE tagihan SET sudah_dibayar = :total_dibayar, status = :status WHERE id_tagihan = :id_tagihan";
        $stmt_update = $this->conn->prepare($query_update);
        $stmt_update->bindParam(":total_dibayar", $total_dibayar);
        $stmt_update->bindParam(":status", $status);
        $stmt_update->bindParam(":id_tagihan", $id_tagihan);
    
        if ($stmt_update->execute()) {
            return ["success" => true, "message" => "Pembayaran berhasil."];
        } else {
            return ["success" => false, "message" => "Gagal memproses pembayaran."];
        }
    }
    
    
 // Ambil riwayat pembayaran siswa tertentu
 public function getPembayaranBySiswa($user_id) {
    $query = "SELECT p.nominal, p.tanggal_pembayaran, p.metode_pembayaran, 
                     t.jenis_tagihan, t.bulan, t.tahun
              FROM " . $this->table_name . " p
              JOIN tagihan t ON p.tagihan_id = t.id_tagihan
              WHERE p.user_id = :user_id
              ORDER BY p.tanggal_pembayaran DESC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    
}
?>