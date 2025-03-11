<?php
// require_once '../config/database.php';
require_once '../models/Siswa.php';

class AdminController {
    private $userModel;

    public function __construct($database) {
        $this->userModel = new UserModel($database);
    }

    // Fungsi menampilkan daftar siswa
    public function daftarSiswa() {
        // require_once __DIR__ . '/../models/UserModel.php'; // Load model
        // $userModel = new UserModel(); // Buat objek model
        $data_siswa = $this->userModel->getAllSiswa(); // Ambil data siswa
    
        include __DIR__ . '/../views/admin/daftarSiswa.php'; // Kirim data ke view
    }

    public function tambahSiswa() {
        require_once __DIR__ . '/../models/UserModel.php';
        // $userModel = new UserModel();
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $nama_lengkap = $_POST['nama_lengkap'];
            $NIS = $_POST['NIS'];
            $nama_ibu = $_POST['nama_ibu'];
            $kelas = $_POST['kelas'];
            $tahun_ajaran = $_POST['tahun_ajaran'];
            $no_telepon = $_POST['no_telepon'];
            $jurusan = $_POST['jurusan'];
            $role = 'siswa'; // Otomatis siswa
    
            $hasil = $userModel->tambahSiswa($username, $password, $nama_lengkap, $NIS, $nama_ibu, $kelas, $tahun_ajaran, $no_telepon, $jurusan, $role);
    
            if ($hasil) {
                header("Location: ../views/admin/daftarSiswa.php?success=1");
                exit();
            } else {
                echo "Gagal menambahkan siswa.";
            }
        }
    }
    
}
// Inisialisasi controller dengan koneksi database
// $database = new Database();
// $db = $database->getConnection();
// $adminController = new AdminController($db);



// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $nama_lengkap = $_POST['nama_lengkap'];
//     $nama_ibu = $_POST['nama_ibu'];
//     $kelas = $_POST['kelas'];
//     $jurusan = $_POST['jurusan'];
//     $tahun_ajaran = $_POST['tahun_ajaran'];
//     $nis = $_POST['nis'];
//     $no_telepon = $_POST['no_telepon'];
//     $username = $_POST['username'];
//     $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

//     // Proses Upload Foto
//     $foto_profil = null;
//     if (!empty($_FILES['foto_profil']['name'])) {
//         $uploadDir = '../uploads/';
//         $fileName = basename($_FILES['foto_profil']['name']);
//         $targetFilePath = $uploadDir . $fileName;
//         $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

//         // Hanya izinkan file gambar
//         $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
//         if (in_array($fileType, $allowedTypes)) {
//             if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $targetFilePath)) {
//                 $foto_profil = $fileName;
//             }
//         }
//     }

//     // Simpan ke database
//     $sql = "INSERT INTO users (username, password, role, nama_lengkap, NIS, nama_ibu, kelas, tahun_ajaran, no_telepon, jurusan, foto_profil)
//             VALUES (?, ?, 'siswa', ?, ?, ?, ?, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ssssssssss", $username, $password, $nama_lengkap, $nis, $nama_ibu, $kelas, $tahun_ajaran, $no_telepon, $jurusan, $foto_profil);

//     if ($stmt->execute()) {
//         echo json_encode(["status" => "success", "message" => "Siswa berhasil ditambahkan!"]);
//     } else {
//         echo json_encode(["status" => "error", "message" => "Gagal menambahkan siswa."]);
//     }
// }
?>
