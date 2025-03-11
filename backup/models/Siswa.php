<?php
class UserModel {
    private $db;

    public function __construct($db) {
        // require_once __DIR__ . '/../config/database.php';
        $this->db = $db;
    }

    public function getAllSiswa() {
        $query = "SELECT id_user, NIS, nama_lengkap, nama_ibu, jurusan, kelas FROM users WHERE role = 'siswa'";
        $stmt = $this->db->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahSiswa($username, $password, $nama_lengkap, $NIS, $nama_ibu, $kelas, $tahun_ajaran, $no_telepon, $jurusan, $role) {
        $query = "INSERT INTO users (username, password, role, nama_lengkap, NIS, nama_ibu, kelas, tahun_ajaran, no_telepon, jurusan) 
                  VALUES (:username, :password, :role, :nama_lengkap, :NIS, :nama_ibu, :kelas, :tahun_ajaran, :no_telepon, :jurusan)";
        $stmt = $this->db->conn->prepare($query);
    
        return $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':role' => $role, // Selalu siswa
            ':nama_lengkap' => $nama_lengkap,
            ':NIS' => $NIS,
            ':nama_ibu' => $nama_ibu,
            ':kelas' => $kelas,
            ':tahun_ajaran' => $tahun_ajaran,
            ':no_telepon' => $no_telepon,
            ':jurusan' => $jurusan
        ]);
    }
    
}

?>