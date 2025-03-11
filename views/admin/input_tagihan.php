<?php
// Pastikan admin sudah login
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../../login.php');
    exit();
}

require_once '../../config/Database.php';
require_once '../../models/Tagihan.php';

$database = new Database();
$db = $database->getConnection();
$tagihan = new Tagihan($db);

// Ambil data jurusan dan kelas untuk pilihan dropdown
$queryJurusan = "SELECT * FROM jurusan";
$queryKelas = "SELECT * FROM kelas";
$stmtJurusan = $db->prepare($queryJurusan);
$stmtKelas = $db->prepare($queryKelas);
$stmtJurusan->execute();
$stmtKelas->execute();

$jurusanList = $stmtJurusan->fetchAll(PDO::FETCH_ASSOC);
$kelasList = $stmtKelas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Tagihan - Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../admin/header.php'; ?>
    <?php include '../admin/sidebar.php'; ?>
    
    <main>
        <h2>Input Tagihan</h2>
        <form action="../../controllers/TagihanController.php" method="POST">
            <label for="jurusan">Pilih Jurusan:</label>
            <select name="jurusan" id="jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <?php foreach ($jurusanList as $jurusan) { ?>
                    <option value="<?php echo $jurusan['id']; ?>"><?php echo $jurusan['nama_jurusan']; ?></option>
                <?php } ?>
            </select>
            
            <label for="kelas">Pilih Kelas:</label>
            <select name="kelas" id="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($kelasList as $kelas) { ?>
                    <option value="<?php echo $kelas['id']; ?>"><?php echo $kelas['nama_kelas']; ?></option>
                <?php } ?>
            </select>
            
            <label for="jenis_tagihan">Jenis Tagihan:</label>
            <input type="text" name="jenis_tagihan" id="jenis_tagihan" required>
            
            <label for="nominal">Nominal:</label>
            <input type="number" name="nominal" id="nominal" required>
            
            <button type="submit" name="submit">Simpan</button>
        </form>
    </main>
</body>
</html>
