<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../login.php");
    exit();
}


// Inisialisasi koneksi database
$db = new Database();
$conn = $db->conn;

if (!isset($_SESSION['nama_lengkap'])) {
    $id_user = $_SESSION['id_user'];
    $query = "SELECT nama_lengkap FROM users WHERE id_user = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $_SESSION['nama_lengkap'] = $user['nama_lengkap']; // Set ulang session
}

// Mengambil data tagihan siswa dari database
$query = "SELECT * FROM tagihan WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$tagihan = $result->fetch_all(MYSQLI_ASSOC);

// Tagihan default jika kosong di database
$tagihan_list = [
    "Daftar Ulang" => ["id" => null, "nominal" => 0, "status" => "Belum Bayar"],
    "Pembayaran Praktek" => ["id" => null, "nominal" => 0, "status" => "Belum Bayar"],
    "Pembayaran Ujian" => ["id" => null, "nominal" => 0, "status" => "Belum Bayar"],
    "SPP Bulanan" => ["id" => null, "nominal" => 0, "status" => "Belum Bayar"]
];

$query_rekening = "SELECT * FROM rekening LIMIT 1";
$rekening_result = $conn->query($query_rekening);
$rekening = $rekening_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<header>
    <div class="logo">
        <img src="../../assets/img/logo.png" alt="Logo sppKU">
    </div>
    <div class="user-icon">
        <i class="fas fa-user"></i>
        <i class="fas fa-sign-out-alt"></i>
    </div>
</header>

<div class="dashboard-container">
<div class="welcome-box">
    <h2>Halo, <?php echo htmlspecialchars($_SESSION['nama_lengkap'], ENT_QUOTES, 'UTF-8'); ?></h2>
    <p>Selamat Datang Di Pembayaran sppKU</p>
</div>

    <h3>List Tagihan</h3>
    <!-- <div class="tagihan-grid">
        <div class="tagihan-item">
            <p>Daftar Ulang</p>
            <img src="../../assets/img/daftar-ulang.png" alt="">
            <span>Rp 600.000</span>
        </div>
        <div class="tagihan-item">
            <p>Pembayaran Praktek</p>
            <img src="../../assets/img/logo-praktek.png" alt="">
            <span>Rp 600.000</span>
        </div>
        <div class="tagihan-item">
            <p>Pembayaran Ujian</p>
            <img src="../../assets/img/logo-ujian.png" alt="">
            <span>Rp 600.000</span>
        </div>
        <div class="tagihan-item">
            <p>SPP Bulanan</p>
            <i class="fas fa-money-bill-wave"></i>
            <img src="../../assets/img/logo-form.png" alt="">
            <span>Rp 610.000</span>
            <a href="#">Lihat Rincian</a>
        </div>
    </div> -->
    <div class="tagihan-grid">
        <?php foreach ($tagihan_list as $jenis => $item) : ?>
            <div class="tagihan-item">
                <p><?php echo $jenis; ?></p>
                <img src="../../assets/img/logo-form.png" alt="">
                <span>Rp <?php echo number_format($item['nominal'], 0, ',', '.'); ?></span>
                <?php if ($item['status'] !== 'Lunas' && $item['id'] !== null) : ?>
                    <a href="#" class="bayar-btn" data-id="<?php echo $item['id']; ?>">Bayar Sekarang</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>>

    <div class="form-tagihan">
        <button id="openModal">Input Tagihan</button>

        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Input Tagihan SPP</h2>
                <form action="../ajax/proses_input_tagihan.php" method="POST">
                    <label for="jenis_tagihan">Pilih Jenis Tagihan:</label>
                    <select name="jenis_tagihan" id="jenis_tagihan" required>
                        <option value="">-- Pilih Jenis Tagihan --</option>
                        <option value="SPP">SPP</option>
                        <option value="Daftar Ulang">Daftar Ulang</option>
                        <option value="Ujian">Ujian</option>
                        <option value="Praktek">Praktek</option>
                    </select>

                    <label for="nominal">Nominal:</label>
                    <input type="number" name="nominal" id="nominal" required>

                    <button type="submit">Simpan Tagihan</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
const modal = document.getElementById("modal");
const btn = document.getElementById("openModal");
const closeBtn = document.querySelector(".close");

btn.onclick = function () {
    modal.style.display = "block";
}

closeBtn.onclick = function () {
    modal.style.display = "none";
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
</body>
</html>

