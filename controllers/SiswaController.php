<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';

header("Content-Type: application/json"); // Pastikan respons hanya JSON

// Buat koneksi menggunakan PDO
$database = new Database();
$conn = $database->getConnection();

// Jika request adalah POST (Tambah Siswa)
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST["edit"])) {
    try {
        // Pastikan semua input tersedia
        $NIS = isset($_POST["NIS"]) ? trim($_POST["NIS"]) : null;
        $nama_lengkap = isset($_POST["nama_lengkap"]) ? trim($_POST["nama_lengkap"]) : null;
        $nama_ibu = isset($_POST["nama_ibu"]) ? trim($_POST["nama_ibu"]) : null;
        $jurusan = isset($_POST["jurusan"]) ? trim($_POST["jurusan"]) : null;
        $kelas = isset($_POST["kelas"]) ? trim($_POST["kelas"]) : null;
        $username = isset($_POST["username"]) ? trim($_POST["username"]) : null;
        $password = isset($_POST["password"]) ? trim($_POST["password"]) : null;

        // Validasi input (Cek apakah ada yang kosong)
        if (!$NIS || !$nama_lengkap || !$nama_ibu || !$jurusan || !$kelas || !$username || !$password) {
            echo json_encode(["error" => "Semua field harus diisi"]);
            exit;
        }

        // Query insert ke database
        $sql = "INSERT INTO users (NIS, nama_lengkap, nama_ibu, jurusan, kelas, username, password, role) 
                VALUES (:NIS, :nama_lengkap, :nama_ibu, :jurusan, :kelas, :username, :password, 'siswa')";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":NIS", $NIS, PDO::PARAM_STR);
        $stmt->bindParam(":nama_lengkap", $nama_lengkap, PDO::PARAM_STR);
        $stmt->bindParam(":nama_ibu", $nama_ibu, PDO::PARAM_STR);
        $stmt->bindParam(":jurusan", $jurusan, PDO::PARAM_STR);
        $stmt->bindParam(":kelas", $kelas, PDO::PARAM_STR);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Siswa berhasil ditambahkan"]);
        } else {
            echo json_encode(["error" => "Gagal menambahkan siswa"]);
        }

    } catch (PDOException $e) {
        echo json_encode(["error" => "Database Error: " . $e->getMessage()]);
    }
    exit;
}

// Jika request adalah POST (Edit Siswa)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit"])) {
    try {
        $nis = $_POST['NIS'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $nama_ibu = $_POST['nama_ibu'];
        $kelas = $_POST['kelas'];
        $jurusan = $_POST['jurusan'];
        $no_telepon = $_POST['no_telepon'];
        $tahun_ajaran = $_POST['tahun_ajaran'];
        
        // Cek apakah ada file yang diupload
        if (!empty($_FILES['foto_profil']['name'])) {
            $target_dir = "../uploads/";
            $foto_nama = basename($_FILES["foto_profil"]["name"]);
            $target_file = $target_dir . $foto_nama;
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Hanya izinkan file gambar tertentu
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
                    $foto_path = "uploads/" . $foto_nama;
                } else {
                    echo json_encode(["error" => "Gagal mengunggah foto."]);
                    exit;
                }
            } else {
                echo json_encode(["error" => "Format file tidak didukung."]);
                exit;
            }
        } else {
            // Jika tidak ada foto yang diupload, gunakan foto lama
            $foto_path = $_POST['foto_lama'];
        }

        // Update data siswa
        $sql = "UPDATE users SET 
                    nama_lengkap = :nama_lengkap, 
                    nama_ibu = :nama_ibu, 
                    kelas = :kelas, 
                    jurusan = :jurusan, 
                    no_telepon = :no_telepon, 
                    tahun_ajaran = :tahun_ajaran, 
                    foto_profil = :foto_profil
                WHERE NIS = :nis";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nama_lengkap", $nama_lengkap);
        $stmt->bindParam(":nama_ibu", $nama_ibu);
        $stmt->bindParam(":kelas", $kelas);
        $stmt->bindParam(":jurusan", $jurusan);
        $stmt->bindParam(":no_telepon", $no_telepon);
        $stmt->bindParam(":tahun_ajaran", $tahun_ajaran);
        $stmt->bindParam(":foto_profil", $foto_path);
        $stmt->bindParam(":nis", $nis);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Data siswa berhasil diperbarui"]);
        } else {
            echo json_encode(["error" => "Gagal memperbarui data siswa"]);
        }

    } catch (PDOException $e) {
        echo json_encode(["error" => "Database Error: " . $e->getMessage()]);
    }
    exit;
}

// Jika request adalah GET (Load Siswa)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$sql_count = "SELECT COUNT(*) AS total FROM users WHERE role = 'siswa' 
              AND (NIS LIKE :search OR nama_lengkap LIKE :search OR 
              nama_ibu LIKE :search OR jurusan LIKE :search OR kelas LIKE :search)";
$stmt_count = $conn->prepare($sql_count);
$search_param = "%$search%";
$stmt_count->bindParam(":search", $search_param, PDO::PARAM_STR);
$stmt_count->execute();
$total_rows = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_rows / $limit);

$sql = "SELECT NIS, nama_lengkap, nama_ibu, jurusan, kelas, foto_profil 
        FROM users 
        WHERE role = 'siswa' 
        AND (NIS LIKE :search OR nama_lengkap LIKE :search OR 
             nama_ibu LIKE :search OR jurusan LIKE :search OR kelas LIKE :search) 
        ORDER BY nama_lengkap ASC
        LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":search", $search_param, PDO::PARAM_STR);
$stmt->execute();
$siswa = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'siswa' => $siswa,
    'total_pages' => $total_pages,
    'current_page' => $page
]);

?>
