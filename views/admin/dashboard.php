<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Tagihan.php';
require_once __DIR__ . '/../../models/Rekening.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Ambil data dari database
$user = new User($conn);
$tagihan = new Tagihan($conn);
$rekening = new Rekening($conn);
$pembayaran = new Pembayaran($conn);

$nama_sekolah = "SMK BIGHIT DARUSSALAM"; // Bisa diambil dari database jika ada
$jumlah_jurusan = $user->getJumlahJurusan();
$siswa_aktif = $user->getJumlahSiswaAktif();
$notif_pembayaran = $pembayaran->getRecentPembayaran();
$daftar_rekening = $rekening->getAllRekening();
$jumlah_tagihan = $tagihan->getTotalTagihan();
$jurusanList = $user->getAllJurusan(); // Ambil daftar jurusan dari database

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../../assets/css/dashboardAdmin.css">
    <link rel="stylesheet" href="../../assets/css/input_tagihan.css">
</head>

<body>
    <?php include "header.php"; ?>
    <div class="container">
        <?php include "sidebar.php"; ?>
        <main>
            <h1>DASHBOARD</h1>
            <div class="dashboard-header">
                <span><?php echo date('l, j F Y'); ?> | <?php echo date('H:i:s'); ?> | Selamat Siang, Admin</span>
            </div>
            <div class="dashboard-content">
                <div class="card">
                    <div class="card-icon">🏫</div>
                    <div class="card-content">
                        <p>Nama Sekolah</p>
                        <h3><?php echo $nama_sekolah; ?></h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon">💻</div>
                    <div class="card-content">
                        <p>Jumlah Jurusan</p>
                        <h3><?php echo $jumlah_jurusan; ?></h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon">👤</div>
                    <div class="card-content">
                        <p>Siswa Aktif</p>
                        <h3><?php echo $siswa_aktif; ?></h3>
                    </div>
                </div>
                <div class="card">
                    <button id="btnTambahTagihan">Tambah Tagihan</button>


                    <!-- Modal Form Input Tagihan -->
                    <div id="modalTagihan" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Input Tagihan</h2>
                            <form action="../../controllers/TagihanController.php" method="POST">
                                <label for="jurusan">Pilih Jurusan:</label>
                                <select name="jurusan" id="jurusan" required>
                                    <option value="">-- Pilih Jurusan --</option>
                                    <?php foreach ($jurusanList as $jurusan) { ?>
                                        <option value="<?= $jurusan['jurusan']; ?>"><?= $jurusan['jurusan']; ?></option>
                                    <?php } ?>
                                </select>

                                <label for="kelas">Pilih Kelas:</label>
                                <select name="kelas" id="kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <option value="X">X</option>
                                    <option value="XI">XI</option>
                                    <option value="XII">XII</option>
                                </select>

                                <label for="jenis_tagihan">Jenis Tagihan:</label>
                                <select name="jenis_tagihan" id="jenis_tagihan" required>
                                    <option value="">-- Pilih Jenis Tagihan --</option>
                                    <option value="SPP">SPP</option>
                                    <option value="Daftar Ulang">Daftar Ulang</option>
                                    <option value="Ujian">Ujian</option>
                                    <option value="Praktek">Praktek</option>
                                </select>

                                <label for="tagihan">Nominal:</label>
                                <input type="number" name="tagihan" id="tagihan" required min="0">

                                <button type="submit" name="submit">Simpan</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <div class="dashboard-info">
                <div class="notif">
                    <h3>Notif Pembayaran</h3>
                    <?php foreach ($notif_pembayaran as $notif): ?>
                        <div class="notif-item">
                            <p><?php echo $notif['nama']; ?> membayar SPP bulan <?php echo $notif['bulan']; ?> senilai Rp
                                <?php echo number_format($notif['jumlah']); ?></p>
                            <span><?php echo $notif['waktu']; ?> | <?php echo $notif['metode']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="rekening">
                    <h3>No Rekening</h3>
                    <?php foreach ($daftar_rekening as $rek): ?>
                        <div class="rekening-item">
                            <p><?php echo $rek['jenis_pembayaran']; ?> : <?php echo $rek['nomor_rekening']; ?></p>
                            <button class="btn-edit">✏️</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.getElementById('jurusan').addEventListener('change', function () {
        let jurusan = this.value;
        let kelasDropdown = document.getElementById('kelas');

        // Kosongkan pilihan kelas saat jurusan berubah
        kelasDropdown.innerHTML = '<option value="">-- Pilih Kelas --</option>';

        if (jurusan !== '') {
            fetch('../../controllers/TagihanController.php/get_kelas?jurusan=' + jurusan)
                .then(response => response.json())
                .then(data => {
                    data.forEach(kelas => {
                        let option = document.createElement('option');
                        option.value = kelas.kelas_id;
                        option.textContent = kelas.nama_kelas;
                        kelasDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>

    <script src="../../assets/js/sidebar.js"></script>
    <script src="../../assets/js/input_tagihan.js"></script>
</body>

</html>