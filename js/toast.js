document.addEventListener("DOMContentLoaded", function() {
    const toasts = document.querySelectorAll(".toast");

    toasts.forEach(toast => {
        // show toast
        setTimeout(() => toast.classList.add("show"), 100);

        // auto hide after 3 seconds
        setTimeout(() => toast.classList.remove("show"), 3000);

        // remove from DOM after hiding
        setTimeout(() => toast.remove(), 3500);

        // close button
        const closeBtn = toast.querySelector(".toast-close");
        if(closeBtn) {
            closeBtn.addEventListener("click", () => toast.remove());
        }
    });
});
