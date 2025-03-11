<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
</head>
<body>
    <h2>Dashboard Admin</h2>
    <p>Nama Sekolah: SMK Negeri 1 Contoh</p>
    <p>Total Siswa Aktif: <?php echo $total_siswa; ?></p>
    <p>Notifikasi Pembayaran: <?php echo $total_notifikasi; ?></p>
    <h3>Informasi Rekening</h3>
    <ul>
        <?php foreach ($daftar_rekening as $rek) { ?>
            <li><?php echo $rek['jenis_pembayaran'] . " - " . $rek['nomor_rekening'] . " (a/n " . $rek['atas_nama'] . ")"; ?></li>
        <?php } ?>
    </ul>
</body>
</html>