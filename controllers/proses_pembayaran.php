<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../login.php");
    exit;
}

// // Cek apakah form telah dikirim
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $id_siswa = $_SESSION['user_id'];
//     $id_tagihan = $_POST['id_tagihan'];
//     $jumlah = $_POST['jumlah'];
//     $metode = $_POST['metode'];
//     $bukti_pembayaran = null;

//     // Cek apakah metode pembayaran transfer dan ada file bukti
//     if ($metode === 'transfer' && isset($_FILES['bukti']) && $_FILES['bukti']['size'] > 0) {
//         $uploadDir = __DIR__ . "/../../uploads/";
//         $fileName = time() . "_" . basename($_FILES['bukti']['name']);
//         $uploadPath = $uploadDir . $fileName;

//         // Pindahkan file ke folder uploads
//         if (move_uploaded_file($_FILES['bukti']['tmp_name'], $uploadPath)) {
//             $bukti_pembayaran = $fileName;
//         } else {
//             echo "Gagal mengupload bukti pembayaran.";
//             exit;
//         }
//     }

//     // Simpan ke database
//     $db = new Database();
//     $conn = $db->getConnection();
//     $pembayaran = new Pembayaran($conn);

//     $result = $pembayaran->tambahPembayaran($id_siswa, $id_tagihan, $jumlah, $metode, $bukti_pembayaran);

//     if ($result) {
//         echo "Pembayaran berhasil!";
//         header("Location: dashboard_siswa.php");
//     } else {
//         echo "Terjadi kesalahan saat memproses pembayaran.";
//     }
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tagihan_id = $_POST['tagihan_id'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $nomor_tujuan = $_POST['nomor_tujuan'];
    $nominal = $_POST['nominal'];
    $status = "Menunggu Verifikasi";
    $user_id = $_SESSION['user_id'];

    // Upload bukti pembayaran jika ada
    $bukti_pembayaran = "";
    if (!empty($_FILES["bukti_pembayaran"]["name"])) {
        $targetDir = "../../uploads/";
        $fileName = time() . "_" . basename($_FILES["bukti_pembayaran"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        
        if (move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $targetFilePath)) {
            $bukti_pembayaran = $fileName;
        } else {
            echo "<script>alert('Gagal mengupload bukti pembayaran.');</script>";
            exit;
        }
    }

    // Panggil fungsi tambahPembayaran dengan urutan parameter yang benar
    if ($pembayaran->tambahPembayaran($user_id, $tagihan_id, $metode_pembayaran, $nomor_tujuan, $nominal, $bukti_pembayaran, $status)) {
        echo "<script>alert('Pembayaran berhasil dikirim!'); window.location.href='dashboard_siswa.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, silakan coba lagi!');</script>";
    }
}


?>
