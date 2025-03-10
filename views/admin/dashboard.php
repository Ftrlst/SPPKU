<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Pastikan pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Inisialisasi koneksi database
$db = new Database();
$conn = $db->conn;

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal!");
}

// Query jumlah jurusan
$query_jurusan = $conn->prepare("SELECT COUNT(DISTINCT jurusan) AS total FROM users");
$query_jurusan->execute();
$result_jurusan = $query_jurusan->get_result();
$jumlah_jurusan = $result_jurusan->fetch_assoc()['total'] ?? 0;

// Query jumlah siswa aktif
$query_siswa = $conn->prepare("SELECT COUNT(*) AS total FROM users WHERE role = ?");
$query_siswa->bind_param("s", $role_siswa);
$role_siswa = "siswa";
$query_siswa->execute();
$result_siswa = $query_siswa->get_result();
$jumlah_siswa = $result_siswa->fetch_assoc()['total'] ?? 0;

// Query notifikasi pembayaran (JOIN users & tagihan)
$query_notif = $conn->prepare("
    SELECT 
        u.nama_lengkap AS nama_siswa, 
        t.jenis_tagihan, 
        p.nominal, 
        p.tanggal_pembayaran, 
        p.jenis_pembayaran 
    FROM pembayaran p
    JOIN users u ON p.user_id = u.id_user
    JOIN tagihan t ON p.tagihan_id = t.id_tagihan
    ORDER BY p.tanggal_pembayaran DESC 
    LIMIT 5
");
$query_notif->execute();
$notifikasi_pembayaran = $query_notif->get_result()->fetch_all(MYSQLI_ASSOC);

// Query rekening
$query_rekening = $conn->prepare("SELECT * FROM rekening LIMIT 1");
$query_rekening->execute();
$rekening = $query_rekening->get_result()->fetch_assoc() ?? [];

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../../assets/img/logo.png" alt="Logo SPPKU">
        </div>
        <div class="user-icon">
            <img src="../../assets/img/person.png" alt="User">
        </div>
    </header>

    <h2 class="fw-bold">DASHBOARD</h2>
    <div class="dashboard-container">
        <div class="date">
            <div class="d-flex justify-content-between">
                <p><strong><?= date('l, d F Y | H:i:s'); ?> | Selamat Datang, Admin</strong></p>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <img src="../../assets/img/sekolah.png" alt="Sekolah">
                <div>
                    <p>Nama Sekolah</p>
                    <h3>SMK BIGHIT DARUSSALAM</h3>
                </div>
            </div>
            <div class="card">
                <img src="../../assets/img/jurusan.png" alt="Jurusan">
                <div>
                    <p>Jumlah Jurusan</p>
                    <h3><?= $jumlah_jurusan; ?></h3>
                </div>
            </div>
            <div class="card">
                <img src="../../assets/img/person.png" alt="Siswa Aktif">
                <div>
                    <p>Siswa Aktif</p>
                    <h3><?= $jumlah_siswa; ?></h3>
                </div>
            </div>
            <div class="form-tagihan">
                <!-- Tombol untuk membuka modal -->
                <button id="openModal">Input Tagihan</button>

                <!-- Modal -->
                <div id="modal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Input Tagihan SPP</h2>
                        <form action="../ajax/proses_input_tagihan.php" method="POST">
                            <label for="kelas">Pilih Kelas:</label>
                            <select name="kelas" id="kelas" required>
                                <option value="">-- Pilih Kelas --</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>

                            <label for="jurusan">Pilih Jurusan:</label>
                            <select name="jurusan" id="jurusan" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="RPL">RPL</option>
                                <option value="TO">TO</option>
                                <option value="TM">TM</option>
                                <option value="TE">TE</option>
                                <option value="TKL">TKL</option>
                            </select>

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

        <div class="notif-rekening">
            <div class="notif-rekening-box">
                <h3>Notifikasi Pembayaran</h3>
                <?php if (!empty($notifikasi_pembayaran)): ?>
                    <?php foreach ($notifikasi_pembayaran as $notif): ?>
                        <p><?= htmlspecialchars($notif['nama_siswa']); ?> membayar
                            <?= htmlspecialchars($notif['jenis_tagihan']); ?>
                            senilai Rp <?= number_format($notif['nominal'], 0, ',', '.'); ?>
                            | <?= date('d/m/Y | H:i:s', strtotime($notif['tanggal_pembayaran'])); ?> |
                            <?= htmlspecialchars($notif['jenis_pembayaran']); ?></p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Belum ada pembayaran terbaru.</p>
                <?php endif; ?>
            </div>

            <div class="notif-rekening-box">
                <h3>No Rekening</h3>
                <?php if ($rekening): ?>
                    <p>Dana: <?= htmlspecialchars($rekening['dana'] ?? 'Belum tersedia'); ?> <a href="rekening.php">✏️</a>
                    </p>
                    <p>GoPay: <?= htmlspecialchars($rekening['gopay'] ?? 'Belum tersedia'); ?> <a href="rekening.php">✏️</a>
                    </p>
                    <p>ShopeePay: <?= htmlspecialchars($rekening['shopeepay'] ?? 'Belum tersedia'); ?> <a
                            href="rekening.php">✏️</a></p>
                <?php else: ?>
                    <p>Data rekening belum tersedia.</p>
                <?php endif; ?>
                <!-- Tombol untuk membuka modal edit rekening -->
                <button id="openModalRekening">Edit Nomor Rekening</button>

                <!-- Modal Edit Rekening -->
                <div id="modalRekening" class="modal">
                    <div class="modal-content">
                        <span class="close closeRekening">&times;</span>
                        <h2>Edit Nomor Rekening</h2>
                        <form action="../ajax/proses_edit_rekening.php" method="POST">
                            <label for="nama_bank">Nama Bank:</label>
                            <input type="text" name="nama_bank" id="nama_bank" required>

                            <label for="no_rekening">Nomor Rekening:</label>
                            <input type="text" name="no_rekening" id="no_rekening" required>

                            <label for="atas_nama">Atas Nama:</label>
                            <input type="text" name="atas_nama" id="atas_nama" required>

                            <button type="submit">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ambil elemen modal
        const modal = document.getElementById("modal");
        const btn = document.getElementById("openModal");
        const closeBtn = document.querySelector(".close");

        // Ketika tombol diklik, buka modal
        btn.onclick = function () {
            modal.style.display = "block";
        }

        // Ketika tombol close diklik, tutup modal
        closeBtn.onclick = function () {
            modal.style.display = "none";
        }

        // Tutup modal jika klik di luar area modal
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>