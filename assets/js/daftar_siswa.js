document.addEventListener("DOMContentLoaded", function () {
    loadSiswa(); // Panggil saat halaman dimuat

    // Event listener untuk pencarian
    let searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            loadSiswa(1, this.value);
        });
    }

    // Event listener untuk form tambah siswa
    let formTambahSiswa = document.getElementById("formTambahSiswa");
    if (formTambahSiswa) {
        formTambahSiswa.addEventListener("submit", function (event) {
            event.preventDefault();
            tambahSiswa(this);
        });
    }
});

function loadSiswa(page = 1, search = "") {
    fetch(`/sppku/controllers/SiswaController.php?page=${page}&search=${encodeURIComponent(search)}`)
        .then(response => response.text()) // Gunakan text() dulu untuk debug
        .then(text => {
            console.log("Raw Response:", text);
            try {
                let data = JSON.parse(text); // Ubah ke JSON setelah debug
                console.log("Data yang diterima:", data);
                
                let tbody = document.getElementById("siswaTableBody");
                if (!tbody) return; // Cegah error jika elemen tidak ditemukan
                tbody.innerHTML = "";

                if (!data.siswa || !Array.isArray(data.siswa)) {
                    tbody.innerHTML = "<tr><td colspan='6'>Tidak ada data</td></tr>";
                    return;
                }

                let rows = data.siswa.map(siswa => `
                    <tr>
                        <td>${siswa.NIS}</td>
                        <td>${siswa.nama_lengkap}</td>
                        <td>${siswa.nama_ibu}</td>
                        <td>${siswa.jurusan}</td>
                        <td>${siswa.kelas}</td>
                        <td>
                            <button class="btn-view" onclick="viewSiswa(${siswa.NIS})">👁️ View</button>
                            <button class="btn-edit" onclick="editSiswa(${siswa.NIS})">✏️ Edit</button>
                            <button class="btn-delete" onclick="deleteSiswa(${siswa.NIS})">🗑️ Delete</button>
                        </td>
                    </tr>
                `).join("");

                tbody.innerHTML = rows;

                // Update pagination
                let pagination = document.getElementById("pagination");
                if (!pagination) return;
                pagination.innerHTML = "";
                for (let i = 1; i <= data.total_pages; i++) {
                    pagination.innerHTML += `<button onclick="loadSiswa(${i}, '${search}')" 
                                              class="${i === data.current_page ? 'active' : ''}">
                                              ${i}
                                            </button>`;
                }
            } catch (error) {
                console.error("Error parsing JSON:", error);
            }
        })
        .catch(error => console.error("Fetch Error:", error));
}

function tambahSiswa(form) {
    let formData = new FormData(form);

    fetch("/sppku/controllers/SiswaController.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text()) // Debug: Lihat respons mentah
    .then(text => {
        console.log("Raw Response:", text);
        try {
            let data = JSON.parse(text);
            if (data.message) {
                alert(data.message);
                form.reset();
                loadSiswa(); // Refresh data siswa setelah penambahan
            } else {
                alert("Error: " + (data.error || "Terjadi kesalahan"));
            }
        } catch (error) {
            console.error("Error parsing JSON:", error);
            alert("Gagal menambahkan siswa. Coba lagi.");
        }
    })
    .catch(error => console.error("Fetch Error:", error));
}


function loadDetailSiswa(nis) {
    fetch(`/sppku/controllers/SiswaController.php?NIS=${nis}`)
        .then(response => response.json())
        .then(data => {
            if (data.siswa) {
                document.getElementById("tahunAjaran").textContent = "2023/2024"; // Bisa diambil dari database jika ada
                document.getElementById("nis").textContent = data.siswa.NIS;
                document.getElementById("namaSiswa").textContent = data.siswa.nama_lengkap;
                document.getElementById("namaIbu").textContent = data.siswa.nama_ibu;
                document.getElementById("kelas").textContent = data.siswa.kelas;
                document.getElementById("jurusan").textContent = data.siswa.jurusan;
                document.getElementById("noTelepon").textContent = data.siswa.no_telepon;
                document.getElementById("fotoSiswa").src = `/uploads/${data.siswa.foto}`; // Sesuaikan dengan lokasi penyimpanan gambar
            } else {
                alert("Siswa tidak ditemukan!");
            }
        })
        .catch(error => console.error("Error fetching data:", error));
}

function viewSiswa(nis) {
    window.location.href = `/sppku/views/admin/view_siswa.php?nis=${nis}`;
}

function editSiswa(nis) {
    window.location.href = `/sppku/views/admin/edit_siswa.php?nis=${nis}`;
}



