<?php
include '../../config/Database.php';
include '../../models/Tagihan.php';

$database = new Database();
$db = $database->getConnection();
$tagihanModel = new Tagihan($db);
$tagihanList = $tagihanModel->getTagihanSPP(); // Mengambil hanya tagihan SPP
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Tagihan SPP</title>
    <link rel="stylesheet" href="../../assets/css/tagihan.css">
</head>

<body>
<?php include "sidebar.php"; ?>
    <h2>List Tagihan SPP</h2>

    <input type="text" id="search" placeholder="Cari..." onkeyup="searchTable()">

    <table border="1" id="tagihanTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Total Tagihan</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($tagihanList as $tagihan): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($tagihan['nama_lengkap']); ?></td>
                    <td>Rp.<?= number_format($tagihan['tagihan'], 0, ',', '.'); ?></td>
                    <td>
                        <?php if (isset($tagihan['status'])): ?>
                            <?= ($tagihan['status'] == 'Lunas') ? '<span style="color:green">Lunas</span>' : '<span style="color:red">Belum Lunas</span>'; ?>
                        <?php else: ?>
                            <span style="color:gray">Status Tidak Tersedia</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="detail_tagihan.php?id=<?= $tagihan['user_id']; ?>">👁️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        function searchTable() {
            let input = document.getElementById("search").value.toLowerCase();
            let rows = document.querySelectorAll("#tagihanTable tbody tr");

            rows.forEach(row => {
                let nama = row.cells[1].textContent.toLowerCase();
                row.style.display = nama.includes(input) ? "" : "none";
            });
        }
    </script>
    <script src="../../assets/js/sidebar.js"></script>

</body>

</html>