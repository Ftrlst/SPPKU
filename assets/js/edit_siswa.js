document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formEditSiswa");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Mencegah reload halaman

        let formData = new FormData(form); // Ambil data dari form

        fetch("../../controllers/SiswaController.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json()) // Konversi response ke JSON
        .then(data => {
            if (data.error) {
                alert("Error: " + data.error);
            } else {
                alert("Data siswa berhasil diperbarui!");
                window.location.href = "daftar_siswa.php"; // Redirect ke daftar siswa
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Terjadi kesalahan saat menyimpan data.");
        });
    });
});
