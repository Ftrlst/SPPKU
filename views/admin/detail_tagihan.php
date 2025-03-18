<?php
include '../../config/Database.php';
include '../../models/Tagihan.php';
include '../../models/Pembayaran.php';

$database = new Database();
$db = $database->getConnection();
$tagihanModel = new Tagihan($db);
$pembayaranModel = new Pembayaran($db);

// Ambil ID siswa dari parameter URL
$id_siswa = isset($_GET['id']) ? $_GET['id'] : die("ID tidak ditemukan");

$detailTagihan = $tagihanModel->getTagihanBySiswa($id_siswa);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tagihan SPP</title>
    <!-- <link rel="stylesheet" href="../../assets/css/tagihan.css"> -->
    <style>
        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 30%;
            text-align: center;
        }
        .close {
            float: right;
            font-size: 28px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Tagihan SPP Siswa</h2>
    <table border="1">
        <tr>
            <th>Nama Bulan</th>
            <th>Tagihan</th>
            <th>Keterangan</th>
            <th>Action</th>
        </tr>
        <?php foreach ($detailTagihan as $tagihan): ?>
            <tr>
                <td><?= date('F', mktime(0, 0, 0, $tagihan['bulan'], 10)) . ' ' . $tagihan['tahun']; ?></td>
                <td>Rp.<?= number_format($tagihan['tagihan'], 0, ',', '.'); ?></td>
                <td><?= ($tagihan['status'] == 'Lunas') ? '<span style="color:green">Lunas</span>' : '<span style="color:red">Belum Lunas</span>'; ?></td>
                <td>
                    <?php if ($tagihan['status'] != 'Lunas'): ?>
                        <button onclick="openModal(<?= $tagihan['id_tagihan']; ?>, <?= $tagihan['tagihan']; ?>)">Bayar</button>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Modal Form Pembayaran -->
    <div id="modalPembayaran" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Bayar Tagihan SPP</h3>
            <input type="hidden" id="id_tagihan">
            <input type="number" id="nominal" placeholder="Masukkan nominal" min="1">
            <button onclick="submitPembayaran()">Kirim</button>
        </div>
    </div>

    <script>
        function openModal(idTagihan, maxNominal) {
            console.log("ID Tagihan:", idTagihan);  // Debugging
            document.getElementById('id_tagihan').value = idTagihan;
            document.getElementById('nominal').value = '';
            document.getElementById('nominal').setAttribute('max', maxNominal);
            document.getElementById('modalPembayaran').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modalPembayaran').style.display = 'none';
        }

        function submitPembayaran() {
            let idTagihan = document.getElementById('id_tagihan').value;
            let nominal = document.getElementById('nominal').value;
            let maxNominal = document.getElementById('nominal').getAttribute('max');

            if (nominal <= 0 || nominal > maxNominal) {
                alert('Nominal tidak valid!');
                return;
            }

            // fetch('../../controllers/TagihanController.php', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            //     body: `id_tagihan=${idTagihan}&nominal=${nominal}`
            // })
            // .then(response => response.json())
            // .then(data => {
            //     if (data.success) {
            //         let newAmount = data.new_amount;
            //         let statusCell = document.querySelector(`.status-${idTagihan}`);
            //         let amountCell = document.querySelector(`.tagihan-amount[data-id='${idTagihan}']`);
                    
            //         if (newAmount == 0) {
            //             statusCell.innerHTML = '<span style="color:green">Lunas</span>';
            //         }
            //         amountCell.innerHTML = 'Rp.' + newAmount.toLocaleString('id-ID');
            //     }
            //     alert(data.message);
            //     closeModal();
            // })
            // .catch(error => console.error('Error:', error));
            fetch('../../controllers/Pembayaran.php?action=pembayaran', { 
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id_tagihan=${idTagihan}&nominal=${nominal}`
})
.then(response => response.text())  // Ubah sementara ke .text() untuk melihat respon mentah
.then(data => {
    console.log("Server Response:", data); // Debug di console
    try {
        let jsonData = JSON.parse(data); // Coba parse ke JSON
        if (jsonData.status === "success") {
            alert(jsonData.message);
            location.reload();
        } else {
            alert(jsonData.message);
        }
    } catch (error) {
        console.error("JSON Parse Error:", error, "Response:", data);
    }
})
.catch(error => console.error('Fetch Error:', error));

        }
    </script>
</body>
</html>
