<?php
require_once '../config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Ambil semua data siswa yang punya foto
$query = "SELECT NIS, foto_profil FROM users WHERE foto_profil IS NOT NULL";
$stmt = $conn->prepare($query);
$stmt->execute();
$siswaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($siswaList as $siswa) {
    $nis = $siswa['NIS'];
    $fotoBlob = $siswa['foto_profil']; // Jika foto disimpan sebagai BLOB

    if ($fotoBlob) {
        // Simpan file di folder uploads
        $filePath = "uploads/$nis.jpg"; 
        file_put_contents($filePath, $fotoBlob);

        // Update database agar hanya menyimpan path
        $updateQuery = "UPDATE users SET foto_profil = ? WHERE NIS = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([$filePath, $nis]);
    }
}

echo "Migrasi selesai!";
?>
