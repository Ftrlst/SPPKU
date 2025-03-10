<?php
include "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kelas = $_POST["kelas"];
    $jurusan = $_POST["jurusan"];
    $jenis_tagihan = $_POST["jenis_tagihan"];
    $nominal = $_POST["nominal"];

    // Cari user_id berdasarkan kelas dan jurusan
    $sql = "SELECT id_user FROM users WHERE kelas = '$kelas' AND jurusan = '$jurusan'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user_id = $row["id_user"];

            // Simpan tagihan ke database
            $insert = "INSERT INTO tagihan (user_id, jenis_tagihan, tagihan, status, sisa_tagihan) 
                       VALUES ('$user_id', '$jenis_tagihan', '$nominal', 'Belum Lunas', '$nominal')";
            $conn->query($insert);
        }
        echo "Tagihan berhasil disimpan!";
    } else {
        echo "Tidak ada siswa di kelas dan jurusan ini.";
    }
} else {
    echo "Metode tidak valid!";
}
?>
