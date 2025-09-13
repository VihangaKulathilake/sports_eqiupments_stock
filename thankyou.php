<?php
require_once 'includes/db.php';
$pageTitle="Thank You";
$cssFile="css/thankyou.css";
include 'userHeader.php';

$customerId=$_SESSION['customer_id']??null;
$orderId=$_GET['order_id']??null;

$orderDetails=[];

if($customerId&&$orderId){
    $sql="SELECT oi.quantity,p.name,p.price,p.image_path FROM order_items oi JOIN products p ON oi.product_id=p.product_id
          WHERE oi.order_id=?";
    if($stmt=mysqli_prepare($connect,$sql)){
        mysqli_stmt_bind_param($stmt,"i",$orderId);
        mysqli_stmt_execute($stmt);
        $result=mysqli_stmt_get_result($stmt);
        while($row=mysqli_fetch_assoc($result)){
            $orderDetails[]=$row;
        }
    }
}
?>

<div class="thankyou-container">
    <h2>Thank You for Your Purchase!</h2>
    <p>Your order has been successfully placed.</p>

    <?php if($orderDetails): ?>
        <div class="order-summary">
            <h3>Order Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grandTotal=0;
                    foreach($orderDetails as $item):
                        $total=$item['price']*$item['quantity'];
                        $grandTotal+=$total;
                    ?>
                    <tr>
                        <td><img src="productImgs/<?=htmlspecialchars($item['image_path'])?>" alt="<?=htmlspecialchars($item['name'])?>" width="60"></td>
                        <td><?=htmlspecialchars($item['name'])?></td>
                        <td>$<?=number_format($item['price'],2)?></td>
                        <td><?=$item['quantity']?></td>
                        <td>$<?=number_format($total,2)?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4"><strong>Grand Total</strong></td>
                        <td><strong>$<?=number_format($grandTotal,2)?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="displayProducts.php" class="btn">Continue Shopping</a>
</div>

<?php include 'footer.php'; ?>
