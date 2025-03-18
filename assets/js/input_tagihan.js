document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("modalTagihan");
    const btn = document.getElementById("btnTambahTagihan");
    const closeBtn = modal.querySelector(".close");
    const jurusanSelect = document.getElementById("jurusan");
    const kelasSelect = document.getElementById("kelas");

    // Tampilkan modal saat tombol diklik
    if (btn) {
        btn.addEventListener("click", function () {
            modal.style.display = "flex";
        });
    }

    // Tutup modal saat tombol close diklik
    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            modal.style.display = "none";
        });
    }

    // Tutup modal saat klik di luar modal-content
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Fetch data kelas berdasarkan jurusan
    async function getKelas(jurusan_id) {
        kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>'; // Reset kelas

        if (!jurusan_id) return; // Jika tidak ada jurusan yang dipilih, hentikan proses

        try {
            const response = await fetch(`../../controllers/TagihanController.php?action=getKelas=${jurusan_id}`);
            if (!response.ok) throw new Error("Gagal mengambil data kelas!");

            const data = await response.json();
            console.log("Data kelas:", data); // Debugging

            if (data.length === 0) {
                console.warn("Tidak ada kelas ditemukan untuk jurusan ini.");
                return;
            }
    
            data.forEach(kelas => {
                const option = document.createElement("option");
                option.value = kelas.kelas_id;
                option.textContent = kelas.nama_kelas;
                kelasSelect.appendChild(option);
            });
        } catch (error) {
            console.error("Error fetching kelas:", error);
        }
    }

    // Ketika jurusan berubah, ambil kelas terkait
    if (jurusanSelect) {
        jurusanSelect.addEventListener("change", function () {
            getKelas(this.value);
        });
    }

    // async function getKelas(jurusan_id) {
    //     kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>'; // Reset kelas
    
    //     if (!jurusan_id) return; // Jika tidak ada jurusan yang dipilih, hentikan proses
    
    //     try {
    //         const response = await fetch(`../../controllers/TagihanController.php?action=getKelas=${jurusan_id}`);
    //         if (!response.ok) throw new Error("Gagal mengambil data kelas!");
    
    //         const data = await response.json();
    //         console.log("Data kelas:", data); // Debugging
    
    //         if (data.length === 0) {
    //             console.warn("Tidak ada kelas ditemukan untuk jurusan ini.");
    //             return;
    //         }
    
    //         data.forEach(kelas => {
    //             const option = document.createElement("option");
    //             option.value = kelas.kelas_id;
    //             option.textContent = kelas.nama_kelas;
    //             kelasSelect.appendChild(option);
    //         });
    //     } catch (error) {
    //         console.error("Error fetching kelas:", error);
    //     }
    // }
    
    async function getKelas(jurusan_id) {
        kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>'; // Reset kelas
    
        if (!jurusan_id) return; // Jika tidak ada jurusan yang dipilih, hentikan proses
    
        try {
            const response = await fetch(`../../controllers/TagihanController.php?action=getKelas&jurusan_id=${jurusan_id}`);
            const text = await response.text(); // Ambil teks response sebelum diubah ke JSON
            console.log("Response Text:", text); // Debugging: lihat isi response dari server
            
            const data = JSON.parse(text); // Ubah manual ke JSON
            console.log("Data kelas:", data); // Debugging
            
            if (data.length === 0) {
                console.warn("Tidak ada kelas ditemukan untuk jurusan ini.");
                return;
            }
    
            data.forEach(kelas => {
                const option = document.createElement("option");
                option.value = kelas.kelas_id;
                option.textContent = kelas.nama_kelas;
                kelasSelect.appendChild(option);
            });
        } catch (error) {
            console.error("Error fetching kelas:", error);
        }
    }
    
});
