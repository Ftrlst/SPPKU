<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Tagihan.php';
require_once __DIR__ . '/../../models/Pembayaran.php';
require_once __DIR__ . '/../../models/Rekening.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../login.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$user = new User($conn);
$siswa = $user->getSiswaById($_SESSION['user_id']);

if (!$siswa) {
    echo "Data siswa tidak ditemukan.";
    exit;
}

$tagihan = new Tagihan($conn);
$pembayaran = new Pembayaran($conn);
$rekening = new Rekening($conn);

$tagihanList = $tagihan->getTagihanBySiswa($_SESSION['user_id']);
$rekeningList = $rekening->getAllRekening();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tagihan_id = $_POST['tagihan_id'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $nomor_tujuan = $_POST['nomor_tujuan'];
    $nominal = $_POST['nominal'];
    $status = "Menunggu Verifikasi";

    // Upload bukti pembayaran
    $bukti_pembayaran = "";
    if (!empty($_FILES["bukti_pembayaran"]["name"])) {
        $targetDir = "../../uploads/";
        $fileName = time() . "_" . basename($_FILES["bukti_pembayaran"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $targetFilePath);
        $bukti_pembayaran = $fileName;
    }

    if ($pembayaran->tambahPembayaran($tagihan_id, $_SESSION['user_id'], $metode_pembayaran, $nomor_tujuan, $nominal, $bukti_pembayaran, $status)) {
        echo "<script>alert('Pembayaran berhasil dikirim!'); window.location.href='dashboard_siswa.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, silakan coba lagi!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembayaran</title>
    <link rel="stylesheet" href="../../assets/css/dashboardsiswa.css">
    <script>
        function updateNomorRekening() {
            var metode = document.getElementById("metode_pembayaran").value;
            var rekeningData = <?php echo json_encode($rekeningList); ?>;
            var nomorRekeningField = document.getElementById("nomor_tujuan");

            var selectedRekening = rekeningData.find(r => r.jenis_pembayaran === metode);
            nomorRekeningField.value = selectedRekening ? selectedRekening.nomor_rekening : "";
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Form Pembayaran</h2>
        <form action="form_pembayaran.php" method="POST" enctype="multipart/form-data">
            <label for="tagihan_id">Pilih Tagihan:</label>
            <select name="tagihan_id" required>
                <?php foreach ($tagihanList as $t): ?>
                    <option value="<?php echo $t['id_tagihan']; ?>">
                        <?php echo htmlspecialchars($t['jenis_tagihan']); ?> - Rp <?php echo number_format($t['tagihan'], 0, ',', '.'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="metode_pembayaran">Metode Pembayaran:</label>
            <select name="metode_pembayaran" id="metode_pembayaran" onchange="updateNomorRekening()" required>
    <option value="">-- Pilih --</option>
    <option value="Dana">Dana</option>
    <option value="Gopay">Gopay</option>
    <option value="ShopeePay">ShopeePay</option>
    <option value="Bank">Bank Transfer</option>
</select>


            <label for="nomor_tujuan">Nomor Tujuan:</label>
            <input type="text" id="nomor_tujuan" name="nomor_tujuan" readonly>

            <label for="nominal">Nominal:</label>
            <input type="number" name="nominal" required>

            <label for="bukti_pembayaran">Upload Bukti Pembayaran:</label>
            <input type="file" name="bukti_pembayaran" required>

            <button type="submit">Kirim Pembayaran</button>
        </form>
    </div>
</body>
</html>
