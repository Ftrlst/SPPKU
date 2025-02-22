<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="dashboardAdmin.css">
    <style>
        .sidebar {
            position: absolute; /* Ubah dari fixed ke absolute */
            width: 250px;
            height: 100vh;
            background: white;
            top: 0;
            left: -250px;
            transition: 0.3s;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            padding: 60px 20px 20px;
            color: black;
            overflow-y: auto;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar h2 {
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            padding: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .sidebar ul li:hover {
            background: #f0f0f0;
        }

        .sidebar ul li i {
            margin-right: 10px;
        }

        .menu-toggle {
            font-size: 24px;
            cursor: pointer;
            position: absolute; /* Ubah dari fixed ke absolute */
            left: 15px;
            top: 10px;
            z-index: 1001; /* Pastikan tombol tetap di atas sidebar */
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- <div class="menu-toggle" onclick="toggleSidebar()">☰</div> -->
        <?php include 'sidebar.php'; ?>
        <div class="image1" style="margin-left: 25px">
            <img src="../asset/logo.png">
        </div>
        <div class="image2">
            <img src="../asset/ion_person-sharp.png" alt="">
        </div>
    </div>
    <h2>Dashboard</h2>
    <div class="main">
        <div class="container">
            <div class="time">
                <p>Senin, 1 Januari 2025 | 12:12:12 | Selamat Siang, Admin</p>
            </div>
            <div class="bagian">
                <div class="card">
                    <div class="icon">
                        <img src="../asset/twemoji_school.png">
                    </div>
                    <div class="teks1" style="padding-left: 10px;">
                        <p><strong>Nama Sekolah</strong></p>
                        <p>SMK BIGHIT DARUSSALAM</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icon">
                        <img src="../asset/fluent-emoji-flat_desktop-computer.png">
                    </div>
                    <div class="teks" style="padding-left: 10px;">
                        <p><strong>Jumlah Jurusan</strong></p>
                        <p>5</p>
                    </div>
                </div>
            </div>
            <div class="bagian">
                <div class="card">
                    <div class="icon">
                        <img src="../asset/ion_person-sharp.png">
                    </div>
                    <div class="teks" style="padding-left: 10px;">
                        <p><strong>Siswa Aktif</strong></p>
                        <p>500</p>
                    </div>
                </div>
                <div class="card form-card">
                    <h3>Form Input Tagihan</h3>
                    <form>
                        <input type="text" placeholder="Nama Siswa" required>
                        <input type="number" placeholder="Jumlah Tagihan" required>
                        <select required>
                            <option value="" disabled selected>Pilih Jenis Tagihan</option>
                            <option value="SPP">SPP</option>
                            <option value="Praktek">Praktek</option>
                            <option value="Daftar Ulang">Daftar Ulang</option>
                            <option value="Ujian">Ujian</option>
                        </select>
                        <button type="submit">Tambahkan</button>
                    </form>
                </div>
            </div>
            <div class="bagian2" style="display: flex;">
                <div class="notif">
                    <h3>Notifikasi Pembayaran</h3>
                    <div class="boxnotif">
                        <p>Ahmad Sobri</p>
                        <p>Membayar SPP bulan Oktober senilai Rp 150.000</p>
                        <p>10/10/2025 | 12.12.12 | Transfer</p>
                    </div>
                    <div class="boxnotif">
                        <p>Kamal Jalaludin</p>
                        <p>Membayar SPP bulan Oktober senilai Rp 150.000</p>
                        <p>10/10/2025 | 12.12.12 | Setor Langsung</p>
                    </div>
                </div>
                <div class="payment-list">
                    <h3>Daftar Pembayaran</h3>
                    <div class="payment" style="display: flex;">
                        <div class="payment-box" style="background: rgba(127, 157, 176, 1); width: 110px; height: 50px;">
                            <h4>SPP</h4>
                            <p>Rp 150.000</p>
                        </div>
                        <div class="payment-box" style="background: rgba(127, 157, 176, 1); width: 110px; height: 50px; margin-left: 10px;">
                            <h4>Praktek</h4>
                            <p>Rp 200.000</p>
                        </div>
                    </div>
                    <div class="payment" style="display: flex;">
                        <div class="payment-box" style="background: rgba(127, 157, 176, 1); width: 110px; height: 50px;">
                            <h4>Daftar Ulang</h4>
                            <p>Rp 500.000</p>
                        </div>
                        <div class="payment-box" style="background: rgba(127, 157, 176, 1); width: 110px; height: 50px; margin-left: 10px;">
                            <h4>Ujian</h4>
                            <p>Rp 20.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>
