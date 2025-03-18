<link rel="stylesheet" href="../../assets/css/sidebar.css">

<!-- Tombol untuk menampilkan sidebar -->
<button id="toggleSidebar" class="menu-btn">☰</button>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <img src="../../assets/img/logo.png" alt="SPPKU Logo" class="logo">
        <button id="closeSidebar" class="close-btn">✖</button>
    </div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
        <li><a href="daftar_siswa.php"><i class="icon-users"></i> Daftar Siswa</a></li>
        <li><a href="tagihan.php"><i class="icon-list"></i> List SPP</a></li>
        <li><a href="transaksi.php"><i class="icon-history"></i> Riwayat Transaksi</a></li>
    </ul>
    <div class="logout">
        <form action="../../controllers/logout.php" method="POST">
    <button type="submit" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</button>
</form>
    </div>

</aside>


