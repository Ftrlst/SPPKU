document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
    const closeBtn = document.getElementById("closeSidebar");

    toggleBtn.addEventListener("click", function () {
        sidebar.classList.add("active");
        toggleBtn.style.display = "none"; // Sembunyikan tombol
    });

    closeBtn.addEventListener("click", function () {
        sidebar.classList.remove("active");
        toggleBtn.style.display = "block"; // Munculkan tombol lagi
    });
});
