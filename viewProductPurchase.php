<?php
$pageTitle = "View Product Purchase";
$cssFile = "css/viewProductPurchase.css";
$extraCss = "css/toast.css";
include 'includes/db.php';
include 'userHeader.php';

$pId = $_GET["product_id"] ?? null;

if (isset($_SESSION['toast_message'])) {
    $msg = $_SESSION['toast_message'];
    $type = $_SESSION['toast_type'] ?? 'error';
    echo "<script>document.addEventListener('DOMContentLoaded', function() { showToast(".json_encode($msg).", ".json_encode($type)."); });</script>";
    unset($_SESSION['toast_message'], $_SESSION['toast_type']);
}

if (!$pId) {
    echo "<p>No products selected.</p>";
    include_once 'footer.php';
    exit();
}

//fetch product
$sql = "SELECT p.*, COALESCE(SUM(s.quantity),0) AS quantity FROM products p
        LEFT JOIN stocks s ON p.product_id = s.product_id WHERE p.product_id = ?;";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $pId);
mysqli_stmt_execute($stmt);
$pResults = mysqli_stmt_get_result($stmt);

if ($product = mysqli_fetch_assoc($pResults)) {
    echo '<div class="view-product-container">';
        echo '<div class="view-pro-cont-left">';
            $pImgPath = 'productImgs/' . htmlspecialchars($product['image_path'] ?? 'default.png');
            echo '<img src="' . $pImgPath . '" class="view-pro-img" alt="' . htmlspecialchars($product['name']) . '">';
            echo '<hr>';
        echo '</div>';

        echo '<div class="view-pro-cont-right">';
            echo "<h1>" . htmlspecialchars($product['name']) . "</h1>";

            // Stock level
            echo '<div class="stock-level">';
                echo '<p id="stockText" class="">';
                if ($product['quantity'] == 0) {
                    echo 'OUT OF STOCK';
                } elseif ($product['quantity'] < 10) {
                    echo 'LIMITED STOCKS LEFT';
                } else {
                    echo 'IN STOCKS';
                }
                echo '</p>';
            echo '</div>';

            echo "<p><strong>Category:</strong> " . htmlspecialchars($product['category']) . "</p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($product['description']) . "</p><br>";
            echo "<h2>Unit Price: LKR " . number_format($product['price'], 2) . "</h2><br>";

            //quantity selector
            echo '<div class="quantity-selector">';
                echo '<label for="quantity">Quantity:</label>';
                echo '<div class="qty-controls">';
                    echo '<button type="button" class="qty-btn" id="decrease">-</button>';
                    echo '<input type="text" class="quantity" id="quantity" value="1" readonly>';
                    echo '<button type="button" class="qty-btn" id="increase">+</button>';
                echo '</div>';
            echo '</div>';

            //action buttons
            echo '<div class="action-buttons">';
                echo '<form method="POST" action="includes/addToCart.inc.php">';
                    echo '<input type="hidden" name="product_id" value="'.$product['product_id'].'">';
                    echo '<input type="hidden" name="quantity" id="cartQuantity" value="1">';
                    echo '<input type="hidden" name="fromProduct" value="1">';
                    echo '<button type="submit" name="add_to_cart" class="btn add-to-cart">Add to Cart</button>';
                echo '</form>';

                echo '<form method="POST" action="checkout.php">';
                    echo '<input type="hidden" name="product_id" value="'.$product['product_id'].'">';
                    echo '<input type="hidden" name="quantity" id="buyQuantity" value="1">';
                    echo '<button type="submit" class="btn buy-now">Buy Now</button>';
                echo '</form>';
            echo '</div>';

            //social media logos
            echo "<p><strong>Share:</strong></p><br>";
            echo "<div class='social-media-logos'>
                    <img src='imgs/twitter.png' alt='twitter-logo'>
                    <img src='imgs/facebook-logo.png' alt='facebook-logo'>
                    <img src='imgs/whatsapp.png' alt='whatsapp-logo'>
                </div>";
        echo '</div>';
    echo '</div>';
} else {
    echo "<p>Product not found.</p>";
}

mysqli_stmt_close($stmt);
mysqli_close($connect);
?>

<!-- animate product card -->
<div id="toast-container"></div>

<script>
const availableStock = <?php echo (int)$product['quantity']; ?>;

const decreaseBtn = document.getElementById('decrease');
const increaseBtn = document.getElementById('increase');
const quantityInput = document.getElementById('quantity');
const cartQuantity = document.getElementById('cartQuantity');
const buyQuantity = document.getElementById('buyQuantity');
const addToCartBtn = document.querySelector('.add-to-cart');
const buyNowBtn = document.querySelector('.buy-now');
const stockText = document.getElementById('stockText');

function showToast(message, type = "error") {
    const container = document.getElementById("toast-container");
    if (!container) return;

    const toast = document.createElement("div");
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<span>${message}</span><button class="toast-close">&times;</button>`;
    container.appendChild(toast);

    setTimeout(() => toast.classList.add("show"), 100);
    setTimeout(() => toast.classList.remove("show"), 3000);
    setTimeout(() => toast.remove(), 3500);

    toast.querySelector(".toast-close").addEventListener("click", () => toast.remove());
}

//update stock text dynamically
function updateStockText(qty) {
    const remainingStock = availableStock - qty;
    if (remainingStock <= 0) {
        stockText.textContent = 'OUT OF STOCK';
        stockText.className = 'out-of-stock';
    } else if (remainingStock < 10) {
        stockText.textContent = 'LIMITED STOCKS LEFT';
        stockText.className = 'limited-stock';
    } else {
        stockText.textContent = 'IN STOCKS';
        stockText.className = 'in-stock';
    }
}

//update quantities and stock
function updateQuantities(qty) {
    cartQuantity.value = qty;
    buyQuantity.value = qty;

    const remainingStock = availableStock - qty;

    if (availableStock === 0 || remainingStock <= 0) {
        addToCartBtn.disabled = true;
        buyNowBtn.disabled = true;
        showToast('Sorry, this product is out of stock!');
    } else if (qty > availableStock) {
        addToCartBtn.disabled = true;
        buyNowBtn.disabled = true;
        showToast('You reached the maximum available stock!');
        quantityInput.value = availableStock;
        cartQuantity.value = availableStock;
        buyQuantity.value = availableStock;
    } else {
        addToCartBtn.disabled = false;
        buyNowBtn.disabled = false;
        if (qty === availableStock) {
            showToast('You reached the maximum available stock!');
        }
    }

    updateStockText(qty);
}

updateQuantities(parseInt(quantityInput.value));

decreaseBtn.addEventListener('click', () => {
    let qty = parseInt(quantityInput.value);
    if (qty > 1) {
        qty -= 1;
        quantityInput.value = qty;
        updateQuantities(qty);
    } else {
        showToast('Quantity cannot be less than 1', 'info');
    }
});

increaseBtn.addEventListener('click', () => {
    let qty = parseInt(quantityInput.value);
    if (availableStock === 0) {
        showToast('Sorry, this product is out of stock!');
        addToCartBtn.disabled = true;
        buyNowBtn.disabled = true;
        return;
    }

    if (qty < availableStock) {
        qty += 1;
        quantityInput.value = qty;
        updateQuantities(qty);
    } else {
        quantityInput.value = availableStock;
        showToast('You reached the maximum available stock!');
        updateQuantities(qty);
    }
});
</script>

<?php include_once 'footer.php'; ?>
