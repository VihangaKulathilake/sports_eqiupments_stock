//animate slide bar
document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.querySelector(".sidebar");
    const overlay = document.querySelector(".overlay");
    const menuToggle = document.querySelector(".menuToggle");
    const menuIcon = menuToggle.querySelector(".icon");
    const dropdowns = document.querySelectorAll(".sidebar .navlinks li.dropdown");

    sidebar.classList.remove("active");
    overlay.classList.remove("active");

    function toggleSidebar() {
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");
    }

    menuToggle.addEventListener("click", toggleSidebar);
    menuIcon.addEventListener("click", toggleSidebar);

    overlay.addEventListener("click", () => {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    });

    dropdowns.forEach(drop => {
        drop.addEventListener("click", function(e) {
            this.classList.toggle("active");
        });
    });
});

//handle back/forward navigation
window.addEventListener("pageshow", function(event) {
    if (event.persisted) {
        const sidebar = document.querySelector(".sidebar");
        const overlay = document.querySelector(".overlay");
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    }
});