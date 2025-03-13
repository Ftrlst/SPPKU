<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt;
    }

    public function countSiswaAktif() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE role = 'siswa'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getJumlahJurusan() {
        $query = "SELECT COUNT(DISTINCT jurusan) AS total FROM users WHERE role = 'siswa'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    public function getJumlahSiswaAktif() {
        $query = "SELECT COUNT(*) AS total FROM users WHERE role = 'siswa'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function tambahSiswa($NIS, $nama, $ibu, $jurusan, $kelas, $username, $password) {
        try {
            $sql = "INSERT INTO users (NIS, nama_lengkap, nama_ibu, jurusan, kelas, username, password, role) 
                    VALUES (:NIS, :nama_lengkap, :nama_ibu, :jurusan, :kelas, :username, :password, 'siswa')";
            $stmt = $this->conn->prepare($sql);
            
            // Bind parameter ke query
            $stmt->bindParam(':NIS', $NIS, PDO::PARAM_STR);
            $stmt->bindParam(':nama_lengkap', $nama, PDO::PARAM_STR);
            $stmt->bindParam(':nama_ibu', $ibu, PDO::PARAM_STR);
            $stmt->bindParam(':jurusan', $jurusan, PDO::PARAM_STR);
            $stmt->bindParam(':kelas', $kelas, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            
            // Eksekusi query
            if ($stmt->execute()) {
                return ["message" => "Siswa berhasil ditambahkan"];
            } else {
                return ["error" => "Gagal menambahkan siswa"];
            }
        } catch (PDOException $e) {
            return ["error" => "Error: " . $e->getMessage()];
        }
    }

    public function getDetailSiswa($NIS) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE NIS = :NIS";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":NIS", $NIS, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSiswa($nis, $nama_lengkap, $nama_ibu, $kelas, $jurusan, $no_telepon, $tahun_ajaran, $foto_profil) {
        $query = "UPDATE siswa SET nama_lengkap = :nama_lengkap, nama_ibu = :nama_ibu, kelas = :kelas, 
                  jurusan = :jurusan, no_telepon = :no_telepon, tahun_ajaran = :tahun_ajaran, foto_profil = :foto_profil 
                  WHERE NIS = :nis";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_lengkap', $nama_lengkap);
        $stmt->bindParam(':nama_ibu', $nama_ibu);
        $stmt->bindParam(':kelas', $kelas);
        $stmt->bindParam(':jurusan', $jurusan);
        $stmt->bindParam(':no_telepon', $no_telepon);
        $stmt->bindParam(':tahun_ajaran', $tahun_ajaran);
        $stmt->bindParam(':foto_profil', $foto_profil);
        $stmt->bindParam(':nis', $nis);
    
        return $stmt->execute();
    }

    public function getAllJurusan() {
        $query = "SELECT DISTINCT jurusan FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKelasByJurusan($jurusan_id) {
        $query = "SELECT DISTINCT kelas FROM users WHERE jurusan = :jurusan AND role = 'siswa'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":jurusan", $jurusan_id);
        $stmt->execute();
        
        $kelasList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ubah format data agar bisa dipakai di JavaScript
        $result = [];
        foreach ($kelasList as $row) {
            $result[] = [
                "kelas_id" => $row["kelas"], // Gunakan kelas sebagai ID
                "nama_kelas" => "Kelas " . $row["kelas"]
            ];
        }
        
        return $result;
    }
    
    
    
    
    
    
    
    
    
    
    
}
?>