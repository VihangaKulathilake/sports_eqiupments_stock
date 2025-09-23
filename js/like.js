document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".like-btn").forEach(function(button) {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            window.location.href = this.getAttribute("data-href");
        });
    });
});
