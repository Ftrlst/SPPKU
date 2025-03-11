<?php 
include "../../config/database.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Tagihan</title>
    <script src="../assets/script.js"></script>
</head>
<body>
    <h2>Input Tagihan SPP</h2>
    
    <form action="../ajax/proses_input_tagihan" method="POST">
        <!-- Pilih Kelas -->
        <label for="kelas">Pilih Kelas:</label>
        <select name="kelas" id="kelas" required>
            <option value="">-- Pilih Kelas --</option>
            <option value="X">X</option>
            <option value="XI">XI</option>
            <option value="XII">XII</option>
        </select>

        <!-- Pilih Jurusan -->
        <label for="jurusan">Pilih Jurusan:</label>
        <select name="jurusan" id="jurusan" required>
            <option value="">-- Pilih Jurusan --</option>
            <option value="RPL">RPL</option>
            <option value="TO">TO</option>
            <option value="TM">TM</option>
            <option value="TE">TE</option>
            <option value="TKL">TKL</option>
        </select>

        <!-- Pilih Jenis Tagihan -->
        <label for="jenis_tagihan">Pilih Jenis Tagihan:</label>
        <select name="jenis_tagihan" id="jenis_tagihan" required>
            <option value="">-- Pilih Jenis Tagihan --</option>
            <option value="SPP">SPP</option>
            <option value="Daftar Ulang">Daftar Ulang</option>
            <option value="Ujian">Ujian</option>
            <option value="Praktek">Praktek</option>
        </select>

        <!-- Input Nominal -->
        <label for="nominal">Nominal:</label>
        <input type="number" name="nominal" id="nominal" required>

        <!-- Tombol Simpan -->
        <button type="submit">Simpan Tagihan</button>
    </form>
</body>
</html>
