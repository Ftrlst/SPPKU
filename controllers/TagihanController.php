<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/Database.php';
require_once '../models/Tagihan.php';
require_once '../models/Pembayaran.php';

class TagihanController {
    private $tagihan;
    private $pembayaran;
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->tagihan = new Tagihan($this->conn);
        $this->pembayaran = new Pembayaran($this->conn);
    }

    public function tambahTagihan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jurusan = $_POST['jurusan'] ?? '';
            $kelas = $_POST['kelas'] ?? '';
            $jenis_tagihan = $_POST['jenis_tagihan'] ?? '';
            $nominal = $_POST['tagihan'] ?? '';
            $bulan = date('n');
            $tahun = date('Y');

            if (empty($jurusan) || empty($kelas) || empty($jenis_tagihan) || empty($nominal)) {
                header("Location: ../views/admin/dashboard.php?error=Harap isi semua field");
                exit();
            }

            try {
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

    public function prosesPembayaran() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_tagihan = $_POST['id_tagihan'] ?? '';
            $nominal = $_POST['nominal'] ?? '';

            if (empty($id_tagihan) || empty($nominal) || $nominal <= 0) {
                echo json_encode(["status" => "error", "message" => "Nominal tidak valid!"]);
                exit();
            }

            try {
                $tagihan = $this->tagihan->getTagihanById($id_tagihan);
                if (!$tagihan) {
                    echo json_encode(["status" => "error", "message" => "Tagihan tidak ditemukan!"]);
                    exit();
                }

                $sisaTagihan = $tagihan['tagihan'] - $tagihan['sudah_dibayar'];

                if ($nominal > $sisaTagihan) {
                    echo json_encode(["status" => "error", "message" => "Nominal melebihi sisa tagihan!"]);
                    exit();
                }

                if (!$this->pembayaran->tambahPembayaran($id_tagihan, $nominal)) {
                    echo json_encode(["status" => "error", "message" => "Gagal menyimpan pembayaran!"]);
                    exit();
                }

                if (method_exists($this->tagihan, 'prosesPembayaran')) {
                    $updateTagihan = $this->tagihan->prosesPembayaran($id_tagihan, $nominal);
                } else {
                    echo json_encode(["status" => "error", "message" => "Method prosesPembayaran tidak ditemukan di Tagihan"]);
                    exit();
                }
                
                if (!$updateTagihan['success']) {
                    echo json_encode(["status" => "error", "message" => $updateTagihan['message']]);
                    exit();
                }

                echo json_encode([
                    "status" => "success", 
                    "message" => "Pembayaran berhasil!", 
                    "new_amount" => $sisaTagihan - $nominal
                ]);
                exit();
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => "Terjadi kesalahan: " . $e->getMessage()]);
                exit();
            }
        }
    }

    // public function getKelas() {
    //     if (!isset($_GET['jurusan_id']) || empty($_GET['jurusan_id'])) {
    //         echo json_encode([]);
    //         return;
    //     }
    
    //     $jurusan = $_GET['jurusan_id'];
    
    //     try {
    //         // Ambil daftar kelas berdasarkan jurusan dari tabel users (PDO)
    //         $query = "SELECT DISTINCT kelas FROM users WHERE jurusan = :jurusan";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(':jurusan', $jurusan, PDO::PARAM_STR);
    //         $stmt->execute();
    //         $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua data dalam bentuk array asosiatif
    
    //         // Format hasil ke bentuk JSON yang sesuai
    //         $kelasList = [];
    //         foreach ($result as $row) {
    //             $kelasList[] = [
    //                 'kelas_id' => $row['kelas'],  // Bisa diganti dengan ID jika ada
    //                 'nama_kelas' => $row['kelas']
    //             ];
    //         }
    
    //         // Set header sebagai JSON dan kirim response
    //         header('Content-Type: application/json');
    //         echo json_encode($kelasList);
    //     } catch (PDOException $e) {
    //         // Jika ada error, tangani dengan response JSON
    //         echo json_encode(['error' => 'Gagal mengambil data kelas: ' . $e->getMessage()]);
    //     }
    // }

    public function getKelas() {
        if (!isset($_GET['jurusan_id']) || empty($_GET['jurusan_id'])) {
            echo json_encode([]);
            return;
        }
    
        $jurusan = $_GET['jurusan_id'];
    
        // Ambil daftar kelas berdasarkan jurusan dari tabel users
        $query = "SELECT DISTINCT kelas FROM users WHERE jurusan = :jurusan";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':jurusan', $jurusan, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Debugging: Cek apakah ada data
        error_log("Query Result: " . print_r($result, true)); // Ini akan muncul di error_log PHP
    
        // Format data untuk response JSON
        $kelasList = [];
        foreach ($result as $row) {
            $kelasList[] = [
                'kelas_id' => $row['kelas'], 
                'nama_kelas' => $row['kelas']
            ];
        }
    
        header('Content-Type: application/json');
        echo json_encode($kelasList);
        exit;
    }
    
    
}

// Panggil fungsi berdasarkan parameter `action`
$controller = new TagihanController();
$action = $_GET['action'] ?? '';

if ($action === 'getKelas') {
    $controller->getKelas();
}

$controller = new TagihanController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_tagihan'])) {
    $controller->prosesPembayaran();
} else {
    $controller->tambahTagihan();
}