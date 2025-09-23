document.addEventListener("DOMContentLoaded", function() {
    const decreaseBtn = document.getElementById('decrease');
    const increaseBtn = document.getElementById('increase');
    const quantityInput = document.getElementById('quantity');
    const cartQuantity = document.getElementById('cartQuantity');
    const buyQuantity = document.getElementById('buyQuantity');
    const addToCartBtn = document.querySelector('.add-to-cart');
    const buyNowBtn = document.querySelector('.buy-now');

    const availableStock = window.availableStock || 0;

    function showToast(message) {
        if (document.querySelector(".toast.toast-error")) return; // prevent duplicates
        const toast = document.createElement("div");
        toast.className = "toast toast-error";
        toast.innerHTML = `${message} <span class="toast-close">&times;</span>`;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add("show"), 100);
        setTimeout(() => toast.classList.remove("show"), 3000);
        setTimeout(() => toast.remove(), 3500);

        const closeBtn = toast.querySelector(".toast-close");
        if (closeBtn) closeBtn.addEventListener("click", () => toast.remove());
    }

    function updateButtons(qty) {
        cartQuantity.value = qty;
        buyQuantity.value = qty;

        if (availableStock === 0) {
            addToCartBtn.disabled = true;
            buyNowBtn.disabled = true;
            quantityInput.value = 0;
            showToast("This product is out of stock!");
        } else {
            addToCartBtn.disabled = false;
            buyNowBtn.disabled = false;
        }
    }

    //initial state
    updateButtons(parseInt(quantityInput.value));

    decreaseBtn.addEventListener('click', () => {
        let qty = parseInt(quantityInput.value);
        if (qty > 1) qty -= 1;
        quantityInput.value = qty;
        updateButtons(qty);
    });

    increaseBtn.addEventListener('click', () => {
        let qty = parseInt(quantityInput.value);
        if (qty < availableStock) qty += 1;
        quantityInput.value = qty;
        updateButtons(qty);
    });
});
