<div class="menu-toggle" onclick="toggleSidebar()">☰</div>
<div class="sidebar" id="sidebar">
    <h2>📌 DASHBOARD</h2>
    <ul>
        <li>📋 Daftar Siswa</li>
        <li>💰 Transaksi SPP</li>
        <li>📜 Riwayat SPP</li>
        <li>📑 Riwayat Daftar Ulang</li>
        <li>🔧 Riwayat Praktek</li>
        <li>📖 Riwayat Ujian</li>
    </ul>
    <ul>
        <li>⬅️ Keluar</li>
    </ul>
</div>
<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }
</script>
