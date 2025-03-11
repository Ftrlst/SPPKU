<?php
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
            $nominal = $_POST['nominal'] ?? '';
            $bulan = date('n'); // Ambil bulan saat ini
            $tahun = date('Y');

            if (empty($jurusan) || empty($kelas) || empty($jenis_tagihan) || empty($nominal)) {
                header("Location: ../views/admin/input_tagihan.php?error=Harap isi semua field");
                exit();
            }

            try {
                // Ambil semua siswa berdasarkan jurusan dan kelas yang dipilih
                $query = "SELECT id FROM users WHERE jurusan_id = :jurusan AND kelas_id = :kelas AND role = 'siswa'";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':jurusan', $jurusan);
                $stmt->bindParam(':kelas', $kelas);
                $stmt->execute();
                $siswaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!$siswaList) {
                    header("Location: ../views/admin/input_tagihan.php?error=Tidak ada siswa di kelas/jurusan ini");
                    exit();
                }

                // Tambahkan tagihan untuk setiap siswa
                foreach ($siswaList as $siswa) {
                    $this->tagihan->tambahTagihan($siswa['id'], $jenis_tagihan, $nominal, $bulan, $tahun);
                }

                header("Location: ../views/admin/input_tagihan.php?success=Tagihan berhasil ditambahkan untuk seluruh siswa");
                exit();
            } catch (Exception $e) {
                header("Location: ../views/admin/input_tagihan.php?error=Terjadi kesalahan: " . $e->getMessage());
                exit();
            }
        }
    }
}

// Jalankan fungsi jika ada request
$controller = new TagihanController();
$controller->tambahTagihan();
?>
