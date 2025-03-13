<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/Database.php';
require_once '../models/Tagihan.php';

class TagihanController {
    private $tagihan;
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->tagihan = new Tagihan($this->conn);
    }

    public function tambahTagihan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jurusan = $_POST['jurusan'] ?? '';
            $kelas = $_POST['kelas'] ?? '';
            $jenis_tagihan = $_POST['jenis_tagihan'] ?? '';
            $nominal = $_POST['tagihan'] ?? '';
            $bulan = date('n'); // Bulan saat ini
            $tahun = date('Y');
    
            if (empty($jurusan) || empty($kelas) || empty($jenis_tagihan) || empty($nominal)) {
                header("Location: ../views/admin/dashboard.php?error=Harap isi semua field");
                exit();
            }
    
            try {
                // Ambil semua siswa berdasarkan jurusan dan kelas
                $query = "SELECT id_user FROM users WHERE jurusan = :jurusan AND kelas = :kelas AND role = 'siswa'";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':jurusan', $jurusan);
                $stmt->bindParam(':kelas', $kelas);
                $stmt->execute();
                $siswaList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                if (!$siswaList) {
                    header("Location: ../views/admin/dashboard.php?error=Tidak ada siswa di kelas/jurusan ini");
                    exit();
                }
    
                // Tambahkan tagihan untuk setiap siswa
                foreach ($siswaList as $siswa) {
                    $this->tagihan->tambahTagihan($siswa['id_user'], $jenis_tagihan, $nominal, $bulan, $tahun);
                }
    
                header("Location: ../views/admin/dashboard.php?success=Tagihan berhasil ditambahkan");
                exit();
            } catch (Exception $e) {
                header("Location: ../views/admin/dashboard.php?error=Terjadi kesalahan: " . $e->getMessage());
                exit();
            }
        }
    }

    // public function getTagihanSPP() {
    //     if (!isset($_SESSION['admin_id'])) {
    //         header("Location: ../login.php");
    //         exit();
    //     }
    
    //     $query = "SELECT t.id_tagihan, u.nama_lengkap, t.jenis_tagihan, t.nominal, t.sudah_dibayar, 
    //                      CASE 
    //                         WHEN t.sudah_dibayar >= t.nominal THEN 'Lunas' 
    //                         ELSE 'Belum Lunas' 
    //                      END AS status
    //               FROM tagihan t
    //               JOIN users u ON t.user_id = u.id_user
    //               WHERE t.jenis_tagihan = 'SPP'";
    
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    public function getListTagihanSPP() {
        $query = "SELECT t.id_tagihan, u.nama_lengkap, t.jenis_tagihan, t.nominal, 
                         (CASE WHEN t.sudah_dibayar >= t.nominal THEN 'Lunas' ELSE 'Belum Lunas' END) AS status
                  FROM tagihan t
                  JOIN users u ON t.user_id = u.id_user
                  WHERE t.jenis_tagihan = 'SPP'
                  ORDER BY u.nama_lengkap ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getTagihanNonSPP($id_user) {
        $query = "SELECT id_tagihan, jenis_tagihan, nominal, sudah_dibayar, 
                         CASE 
                            WHEN sudah_dibayar >= nominal THEN 'Lunas' 
                            ELSE 'Belum Lunas' 
                         END AS status
                  FROM tagihan 
                  WHERE user_id = :user_id AND jenis_tagihan != 'SPP'";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $id_user);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
}

if (isset($_GET['action']) && $_GET['action'] == 'getKelas') {
    require_once __DIR__ . '/../config/Database.php';
    require_once __DIR__ . '/../models/User.php';

    $db = new Database();
    $conn = $db->getConnection();
    $user = new User($conn);

    $jurusan_id = $_GET['jurusan_id'] ?? '';

    if ($jurusan_id) {
        $kelasList = $user->getKelasByJurusan($jurusan_id);
        echo json_encode($kelasList);
    } else {
        echo json_encode([]);
    }
    exit;
}


// Jalankan fungsi jika ada request POST untuk tambah tagihan
$controller = new TagihanController();
$controller->tambahTagihan();

?>
