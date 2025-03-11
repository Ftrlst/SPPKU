<?php
require_once "../config/database.php";

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kelas = $_POST["kelas"];
    $jurusan = $_POST["jurusan"];
    $jenis_tagihan = $_POST["jenis_tagihan"];
    $nominal = $_POST["nominal"];

    // Cari user_id berdasarkan kelas dan jurusan
    $sql = "SELECT id_user FROM users WHERE kelas = :kelas AND jurusan = :jurusan";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':kelas', $kelas);
    $stmt->bindParam(':jurusan', $jurusan);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($users) > 0) {
        foreach ($users as $user) {
            $user_id = $user["id_user"];

            // Simpan tagihan ke database
            $insert = "INSERT INTO tagihan (user_id, jenis_tagihan, tagihan, status, sisa_tagihan) 
                       VALUES (:user_id, :jenis_tagihan, :nominal, 'Belum Lunas', :nominal)";
            $stmt = $conn->prepare($insert);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':jenis_tagihan', $jenis_tagihan);
            $stmt->bindParam(':nominal', $nominal);
            $stmt->execute();
        }
        echo "Tagihan berhasil disimpan!";
    } else {
        echo "Tidak ada siswa di kelas dan jurusan ini.";
    }
} else {
    echo "Metode tidak valid!";
}
?>
