<?php
require_once 'includes/db.php';
$pageTitle="Checkout";
$cssFile="css/checkout.css";
include 'userHeader.php';

//Check whether the user logged in or not
if(!isset($_SESSION['customer_id'])){
    echo "<p>Please <a href='login.php'>log in</a> to proceed to checkout.</p>";
    include 'footer.php';
    exit();
}

$customerId=$_SESSION['customer_id'];

//Buy now option
if(isset($_POST['product_id'])&&isset($_POST['quantity'])&&!isset($_POST['confirm_order'])){
    $buyProductId=(int)$_POST['product_id'];
    $buyQuantity=(int)$_POST['quantity'];
    $_SESSION['cart']=[$buyProductId=>$buyQuantity];
}

//Cart option
$cart=$_SESSION['cart']??[];
if(empty($cart)){
    $sql="SELECT product_id,quantity FROM customer_cart WHERE customer_id=?";
    if($stmt=mysqli_prepare($connect,$sql)){
        mysqli_stmt_bind_param($stmt,"i",$customerId);
        mysqli_stmt_execute($stmt);
        $result=mysqli_stmt_get_result($stmt);
        $cart=[];
        while($row=mysqli_fetch_assoc($result)){
            $cart[$row['product_id']]=$row['quantity'];
        }
        $_SESSION['cart']=$cart;
    }
}

if(empty($cart)){
    echo "<p>Your cart is empty.</p>";
    include 'footer.php';
    exit();
}

$productIds=array_keys($cart);
$placeholders=implode(',',array_fill(0,count($productIds),'?'));
$sql="SELECT * FROM products WHERE product_id IN ($placeholders)";
$stmt=mysqli_prepare($connect,$sql);
$types=str_repeat('i',count($productIds));
mysqli_stmt_bind_param($stmt,$types,...$productIds);
mysqli_stmt_execute($stmt);
$result=mysqli_stmt_get_result($stmt);

$products=[];
while($row=mysqli_fetch_assoc($result)){
    $products[$row['product_id']]=$row;
}

//Confirmation of the order
if(isset($_POST['confirm_order'])){
    $error=false;
    mysqli_begin_transaction($connect);

    $sqlOrder="INSERT INTO orders (customer_id,order_date,order_status) VALUES (?,CURDATE(),'pending')";
    if($stmtOrder=mysqli_prepare($connect,$sqlOrder)){
        mysqli_stmt_bind_param($stmtOrder,"i",$customerId);
        if(mysqli_stmt_execute($stmtOrder)){
            $orderId=mysqli_insert_id($connect);

            $sqlItem="INSERT INTO order_items (order_id,product_id,quantity) VALUES (?,?,?)";
            foreach($cart as $pId=>$qty){
                if($stmtItem=mysqli_prepare($connect,$sqlItem)){
                    mysqli_stmt_bind_param($stmtItem,"iii",$orderId,$pId,$qty);
                    if(!mysqli_stmt_execute($stmtItem)){
                        $error=true;
                        break;
                    }
                }else{
                    $error=true;
                    break;
                }
            }
        }else{
            $error=true;
        }
    }else{
        $error=true;
    }

    if($error){
        mysqli_rollback($connect);
        echo "<script>alert('Failed to place the order. Please try again.');</script>";
    }else{
        mysqli_commit($connect);

        //Clear the cart after order completion
        if(!isset($_POST['product_id'])&&!isset($_POST['quantity'])){
            $_SESSION['cart']=[];
            $sql="DELETE FROM customer_cart WHERE customer_id=?";
            if($stmt=mysqli_prepare($connect,$sql)){
                mysqli_stmt_bind_param($stmt,"i",$customerId);
                mysqli_stmt_execute($stmt);
            }
        }else{
            $_SESSION['cart']=[];
        }

        //Redirect to thank you page
        header("Location: thankyou.php?order_id=$orderId");
        exit();
    }
}

//Checkout table
$grandTotal=0;
echo '<div class="checkout-container">';
echo '<h2>Checkout</h2>';
echo '<div class="checkout-table-container">';
echo '<table class="checkout-table">';
echo '<thead><tr>
        <th>Image</th>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
      </tr></thead><tbody>';

foreach($cart as $pId=>$qty){
    if(!isset($products[$pId])){
        continue;
    }

    $product=$products[$pId];
    $total=$product['price']*$qty;
    $grandTotal+=$total;

    echo '<tr>';
    echo '<td><img src="productImgs/'.htmlspecialchars($product['image_path']).'" alt="'.htmlspecialchars($product['name']).'"></td>';
    echo '<td>'.htmlspecialchars($product['name']).'</td>';
    echo '<td>$'.number_format($product['price'],2).'</td>';
    echo '<td>'.$qty.'</td>';
    echo '<td>$'.number_format($total,2).'</td>';
    echo '</tr>';
}

echo '<tr class="grand-total">';
echo '<td colspan="4">Grand Total</td>';
echo '<td>$'.number_format($grandTotal,2).'</td>';
echo '</tr>';
echo '</tbody></table>';
echo '</div>';

//Confirm order form
echo '<form method="POST" class="confirm-order-form">';
echo '<h3>Select Payment Method</h3>';
echo '<label><input type="radio" name="payment_method" value="Credit Card" required> Credit Card</label>';
echo '<label><input type="radio" name="payment_method" value="PayPal"> PayPal</label>';
echo '<label><input type="radio" name="payment_method" value="Cash on Delivery"> Cash on Delivery</label>';
echo '<div class="form-buttons">';

if(isset($_POST['product_id'])&&isset($_POST['quantity'])){
    $backProductId=(int)$_POST['product_id'];
    echo '<a href="viewProductPurchase.php?product_id='.$backProductId.'" class="btn-back">Back</a>';
}else{
    echo '<a href="cart.php" class="btn-back">Back</a>';
}

echo '<button type="submit" name="confirm_order" class="btn-confirm">Confirm Order</button>';
echo '</div>';
echo '</form>';
echo '</div>';

include 'footer.php';
?>
