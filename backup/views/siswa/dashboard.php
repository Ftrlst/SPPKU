<?php
session_start();
require_once __DIR__ . '//../../config/database.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

// Ambil data siswa yang sedang login
$user_id = $_SESSION['id_user'];
$querySiswa = "SELECT nama_lengkap, kelas, jurusan, tahun_ajaran, foto_profil FROM users WHERE id_user = :user_id";
$stmtSiswa = $conn->prepare($querySiswa);
$stmtSiswa->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmtSiswa->execute();
$siswa = $stmtSiswa->fetch(PDO::FETCH_ASSOC);

// Ambil daftar tagihan siswa yang belum lunas
$query = "SELECT * FROM tagihan WHERE user_id = :user_id AND status != 'Lunas'";
$stmt = $conn->prepare($query);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$tagihan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <header>
        <div class="logo">
            <img src="../../assets/img/logo.png" alt="">
        </div>
        <div class="user-info">
            <i class="fa-solid fa-user"></i>
            <a href="../../logout.php"><i class="fa-solid fa-sign-out-alt"></i></a>
        </div>
    </header>

    <div class="dashboard-container">
        <div class="welcome-box">
            <h2>Selamat Datang, <?= $siswa['nama_lengkap']; ?></h2>
            <p>Kelas: <?= $siswa['kelas']; ?> | Jurusan: <?= $siswa['jurusan']; ?> | Tahun Ajaran:
                <?= $siswa['tahun_ajaran']; ?></p>
            <p>Berikut adalah daftar tagihan SPP Anda:</p>
        </div>

        <h3>List Tagihan</h3>
        <div class="tagihan-grid">
            <?php foreach ($tagihan as $row): ?>
                <div class="tagihan-item">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <p><?= $row['jenis_tagihan']; ?></p>
                    <p>Rp <?= number_format($row['tagihan'], 0, ',', '.'); ?></p>
                    <?php if ($row['jenis_tagihan'] === "SPP Bulanan"): ?>
                        <button class="btn-rincian" onclick="openRincian()">Lihat Rincian</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="popupRincian" class="popup-container">
            <div class="popup-content">
                <h2>Rincian SPP</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Bulan</th>
                            <th>Tagihan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Juli</td>
                            <td>Rp.100.000</td>
                            <td class="lunas">Lunas</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Agustus</td>
                            <td>Rp.100.000</td>
                            <td class="lunas">Lunas</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>September</td>
                            <td>Rp.100.000</td>
                            <td class="lunas">Lunas</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Oktober</td>
                            <td>Rp.100.000</td>
                            <td class="lunas">Lunas</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>November</td>
                            <td>Rp.100.000</td>
                            <td class="lunas">Lunas</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Desember</td>
                            <td>Rp.100.000</td>
                            <td class="lunas">Lunas</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Januari</td>
                            <td>Rp.100.000</td>
                            <td class="kurang">Kurang 10.000</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Februari</td>
                            <td>Rp.100.000</td>
                            <td class="belum">Belum Lunas</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Maret</td>
                            <td>Rp.100.000</td>
                            <td class="belum">Belum Lunas</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>April</td>
                            <td>Rp.100.000</td>
                            <td class="belum">Belum Lunas</td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>Mei</td>
                            <td>Rp.100.000</td>
                            <td class="belum">Belum Lunas</td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>Juni</td>
                            <td>Rp.100.000</td>
                            <td class="belum">Belum Lunas</td>
                        </tr>
                    </tbody>
                </table>
                <button onclick="closeRincian()">Tutup</button>
            </div>
        </div>

        <h3>Form Pembayaran</h3>
        <div class="btn-form">
        <img src="../../assets/img/logo-form.png" alt="">
        <button class="" onclick="openForm()">Form Input</button>
        </div>

        <div id="popupForm" class="popup-container">
    <div class="popup-content">
        <h2>Form Pembayaran</h2>

        <div class="form-group">
            <label for="jenisTagihan">Pilih Tagihan:</label>
            <select id="jenisTagihan">
                <option value="spp">SPP Bulanan</option>
                <option value="daftarulang">Daftar Ulang</option>
                <option value="praktek">Pembayaran Praktek</option>
                <option value="ujian">Pembayaran Ujian</option>
            </select>
        </div>

        <div class="form-group">
            <label for="metodePembayaran">Metode Pembayaran:</label>
            <select id="metodePembayaran" onchange="tampilkanRekening()">
                <option value="">Pilih Metode</option>
                <option value="Dana">Dana</option>
                <option value="GoPay">GoPay</option>
                <option value="ShopeePay">ShopeePay</option>
            </select>
        </div>

        <div class="form-group" id="rekeningContainer" style="display:none;">
        <p><strong>Nomor Rekening:</strong> <span id="nomorRekening"></span></p>
        </div>

        <div class="form-group">
            <label for="nominal">Nominal:</label>
            <input type="number" id="nominal" placeholder="Masukkan nominal pembayaran">
        </div>

        <div class="form-group">
            <label for="buktiPembayaran">Upload Bukti:</label>
            <input type="file" id="buktiPembayaran">
        </div>

        <button onclick="submitPembayaran()">Kirim</button>
        <button onclick="closeForm()">Batal</button>
    </div>
</div>

    </div>

    <script>
        function openPopup(id) {
            document.getElementById("tagihan_id").value = id;
            document.getElementById("popupPembayaran").style.display = "block";
        }

        function closePopup() {
            document.getElementById("popupPembayaran").style.display = "none";
        }

        function openRincian() {
            document.getElementById("popupRincian").style.display = "flex";
        }

        function closeRincian() {
            document.getElementById("popupRincian").style.display = "none";
        }

        function openForm() {
            document.getElementById("popupForm").style.display = "flex";
        }

        function closeForm() {
            document.getElementById("popupForm").style.display = "none";
        }

        function tampilkanRekening() {
            var metode = document.getElementById("metodePembayaran").value;
            var rekeningContainer = document.getElementById("rekeningContainer");
            var nomorRekening = document.getElementById("nomorRekening");

            if (metode === "Dana") {
                nomorRekening.innerText = "123-456-7890 (Dana)";
            } else if (metode === "GoPay") {
                nomorRekening.innerText = "987-654-3210 (GoPay)";
            } else if (metode === "ShopeePay") {
                nomorRekening.innerText = "555-333-2221 (ShopeePay)";
            } else {
                rekeningContainer.style.display = "none";
                return;
            }

            rekeningContainer.style.display = "block";
        }

        function submitPembayaran() {
            alert("Pembayaran berhasil dikirim!");
            closeForm();
        }
    </script>

</body>

</html>