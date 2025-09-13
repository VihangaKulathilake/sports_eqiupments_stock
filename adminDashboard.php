<?php
$pageTitle = "Dashboard";
$cssFile = "css/adminDashboard.css";
include 'adminHeader.php';

$year  = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
            
?>

<div class="dashboardcontainer">
    <!--dashboard overviewcards -->
    <h2>Quick Access</h2>
    <div class="overviewcards">
        <a href="topProducts.php" class="dashboard-card top-products">
            <div class="card-title">
                <img src="imgs/best-product.png" alt="Top products icon">
                <h2>Top Products</h2>
            </div>
        </a>

        <a href="lowStockItems.php" class="dashboard-card low-stock-items">
            <div class="card-title">
                <img src="imgs/graph.png" alt="Low stock items icon">
                <h2>Low Stock Items</h2>
            </div>
        </a>

        <a href="recent-orders.php" class="dashboard-card recent-orders">
            <div class="card-title">
                <img src="imgs/history.png" alt="Recent orders icon">
                <h2>Recent Orders</h2>
            </div>
        </a>

        <a href="top-customers.php" class="dashboard-card top-customers">
            <div class="card-title">
                <img src="imgs/best-customer-experience.png" alt="Top customers icon">
                <h2>Top Customers</h2>
            </div>
        </a>

        <a href="top-suppliers.php" class="dashboard-card top-suppliers">
            <div class="card-title">
                <img src="imgs/supplier.png" alt="Top suppliers icon">
                <h2>Top Suppliers</h2>
            </div>
        </a>

        <a href="insertProduct.php" class="dashboard-card insert-product">
            <div class="card-title">
                <img src="imgs/add.png" alt="Add icon">
                <h2>Register Product</h2>
            </div>
        </a>
    </div>

    <h2>Year Summary</h2>
    <div class="bar-chart-header">
            <form class="bar-chart-calendar" method="GET">
                <label for="year"><strong>Year : </strong></label>
                <select name="year" class="bar-chart-years" id="year" onchange="this.form.submit()">
                    <?php
                    $currentYear=date('Y');
                    for ($y=$currentYear; $y >= $currentYear-5 ; $y--){
                        $selected=($y==$year)?"selected":"";
                        echo "<option class='bar-chart-years-options' value='$y' $selected>$y</option>";
                    }
                    ?>
                </select>
        </form>
    </div>
    <div class="charts">
        <div class="charts-left">
            <h2>Orders</h2>
            <?php
            //bar chart
            $sql = "SELECT MONTH(o.order_date) AS month_num, 
            SUM(oi.quantity * p.price) AS total_sales FROM orders o
            JOIN order_items oi ON o.order_id = oi.order_id
            JOIN products p ON oi.product_id = p.product_id
            WHERE YEAR(o.order_date) = $year 
            GROUP BY MONTH(o.order_date)";

            $results = mysqli_query($connect, $sql);

            $labels=[
                1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",
                7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec",
            ];

            $values=array_fill(1,12,0);

            while ($row=mysqli_fetch_assoc($results)) {
                $values[(int)$row['month_num']] =(int)$row['total_sales'];
            }
            ?>
            <div class="bar-chart-container">
                <div class="bar-chart">
                    <?php
                    $maxValue = max($values)?:1;
                    foreach ($labels as $i => $label) {
                        $height = ($values[$i]/$maxValue)*200;

                        echo "<div class='bar'>
                            <div class='bar-fill' data-height='{$height}'></div>
                            <span class='bar-label'>{$label}</span>";

                            if ($values[$i] != 0) {
                            echo "<span class='bar-value'>LKR " . number_format($values[$i], 2) . "</span>";
                            }

                        echo "</div>";

                    }
                    ?>
                </div>
                <div class="bar-chart-stats">
                    <?php
                    //bar chart stats
                    $totalSales=array_sum($values);
                    $monthsWithSales=count($values);
                    $avgMonthlySales=$monthsWithSales ? $totalSales/$monthsWithSales:0;
                    ?>

                    <div class="total-sales">
                        <h2>Total Sales in <?php echo $year ?></h2>
                        <p>LKR <?php echo number_format($totalSales, 2); ?></p>
                    </div>

                    <div class="avg-sales">
                        <h2>Average Sales (per Month)</h2>
                        <p>LKR <?php echo number_format($avgMonthlySales, 2); ?></p>
                    </div>
                </div>

            </div>

            <div class="pie-chart">
                <?php
                //pie chart sql
                $sql = "SELECT p.category, SUM(oi.quantity * p.price) AS total_sales
                FROM orders o
                JOIN order_items oi ON o.order_id = oi.order_id
                JOIN products p ON oi.product_id = p.product_id
                WHERE YEAR(o.order_date) = $year
                GROUP BY p.category";

                $categoriesResults=mysqli_query($connect, $sql);

                $categories=[];
                $totalsPerCtgry=[]; 

                while ($row=mysqli_fetch_assoc($categoriesResults)) {
                    $categories[]=$row['category'];
                    $totalsPerCtgry[]=$row['total_sales'];
                }
                ?>

                    <div class="pie-chart-container">
                        <h2>Category-wise Sales</h2>
                        <svg viewBox="0 0 50 50" class="pie">
                            <?php
                            $cumulative=25;
                            $count=count($totalsPerCtgry);
                            $totalSalesPie=array_sum($totalsPerCtgry);

                            $colors = [];

                            foreach ($totalsPerCtgry as $i => $value) {
                                $percent = $totalSalesPie ? $value / $totalSalesPie : 0;
                                $dashArray = ($percent * 100) . " " . (100 - $percent * 100);

                                $hue = ($i * 360 / $count);
                                $color = "hsl($hue, 70%, 50%)";
                                $colors[] = $color;

                                echo "<circle r='16' cx='25' cy='25' 
                                        stroke='{$color}' 
                                        stroke-width='10' 
                                        fill='transparent' 
                                        stroke-dasharray='{$dashArray}' 
                                        stroke-dashoffset='{$cumulative}' />
                                ";
                                $cumulative -= $percent * 100;
                            }
                            ?>
                        </svg>

                        <!-- Legend under the pie chart -->
                        <div class="legend">
                            <?php
                            foreach ($categories as $i => $category) {
                                echo "<div><span style='background-color: {$colors[$i]}'></span> {$category} - LKR " . number_format($totalsPerCtgry[$i],2) . "</div>";
                            }
                            ?>
                        </div>
                    </div>


                    <div class="pie-chart-stats">
                        <?php
                        //pie chart stats
                        $maxIndex = array_keys($totalsPerCtgry, max($totalsPerCtgry))[0];
                        $minIndex = array_keys($totalsPerCtgry, min($totalsPerCtgry))[0];
                        ?>

                        <div class="top-category">
                            <h2>Top Category</h2>
                            <p>
                                <?php
                                echo "<p>" . $categories[$maxIndex]."<br>";
                                echo "LKR " . number_format($totalsPerCtgry[$maxIndex], 2) . "</p>";
                                ?>
                            </p>
                        </div>

                        <div class="low-category">
                            <h2>Lowest Category</h2>
                            <p>
                                <?php
                                echo "<p>" . $categories[$minIndex]."<br>";
                                echo "LKR " . number_format($totalsPerCtgry[$minIndex], 2) . "</p>";
                                ?>
                            </p>
                        </div>
                    </div>
            </div>
            <div class="progress-bars">
                <div class="order-progress-container">
                    <?php
                    $totalOrdersSql="SELECT COUNT(*) AS total FROM orders WHERE YEAR(order_date)=$year";
                    $totalOrdersResults=mysqli_query($connect, $totalOrdersSql);
                    $totalOrders=mysqli_fetch_assoc($totalOrdersResults)['total'];

                    $completedOrdersSql="SELECT COUNT(*) AS completed FROM orders WHERE YEAR(order_date) = $year AND order_status='completed'";
                    $completedOrdersResults=mysqli_query($connect, $completedOrdersSql);
                    $completedOrders=mysqli_fetch_assoc($completedOrdersResults)['completed'];

                    $completedPercentage=$totalOrders>0 ? ($completedOrders/$totalOrders)*100 : 0;
                    ?>

                    <h2>Completed Customer Orders</h2>
                    <div class="order-progress-bar">
                        <div class="order-progress-fill" style="width: <?php echo $completedPercentage; ?>%;"></div>
                    </div>
                    <p><strong><?php echo round($completedPercentage, 2); ?>%</strong> of Customer Orders are Completed</p>
                </div>
            </div>

        </div>
        <div class="charts-right">
            <h2>Purchases</h2>
            <?php
            //bar chart
            $purSql = "SELECT MONTH(so.order_date) AS month_num, 
                    SUM(soi.quantity * p.price) AS total_purchases
                    FROM supplier_orders so
                    JOIN supplier_order_items soi ON so.supplier_order_id = soi.supplier_order_id
                    JOIN products p ON soi.product_id = p.product_id
                    WHERE YEAR(so.order_date) = $year
                    GROUP BY MONTH(so.order_date)
                    ORDER BY month_num;";

            $purResults = mysqli_query($connect, $purSql);

            $labels=[
                1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",
                7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec",
            ];

            $purValues = array_fill(1, 12, 0);

            while($row=mysqli_fetch_assoc($purResults)) {
                $purValues[(int)$row['month_num']] =(int)$row['total_purchases'];
            }
            ?>
    
            <div class="bar-chart-container">
                <div class="bar-chart">
                    <?php
                    $purMaxValue = max($purValues)?:1;

                    foreach ($labels as $i => $label) {
                        $purHeight = ($purValues[$i]/$purMaxValue)*200;

                        echo "<div class='bar'>
                            <div class='bar-fill' data-height='{$purHeight}'></div>
                            <span class='bar-label'>{$label}</span>";

                            if ($purValues[$i] != 0) {
                            echo "<span class='bar-value'>LKR " . number_format($purValues[$i], 2) . "</span>";
                            }

                        echo "</div>";

                    }
                    ?>
                </div>
                <div class="bar-chart-stats">
                    <?php
                    //bar chart stats
                    $totalPurchases=array_sum($purValues);
                    $monthsWithPurchases=count($purValues);
                    $avgMonthlyPurchases=$monthsWithPurchases ? $totalPurchases/$monthsWithPurchases:0;
                    ?>

                    <div class="total-sales">
                        <h2>Total Purchases in <?php echo $year ?></h2>
                        <p>LKR <?php echo number_format($totalPurchases, 2); ?></p>
                    </div>

                    <div class="avg-sales">
                        <h2>Average Purchases (per Month)</h2>
                        <p>LKR <?php echo number_format($avgMonthlyPurchases, 2); ?></p>
                    </div>
                </div>

            </div>

            <div class="pie-chart">
                <?php
                //pie chart sql
                $sql = "SELECT s.name AS supplier_name, SUM(soi.quantity * p.price) AS total_purchases
                        FROM supplier_orders so
                        JOIN supplier_order_items soi ON so.supplier_order_id = soi.supplier_order_id
                        JOIN products p ON soi.product_id = p.product_id
                        JOIN suppliers s ON so.supplier_id = s.supplier_id
                        WHERE YEAR(so.order_date) = $year
                        GROUP BY s.supplier_id";

                $categoriesResults = mysqli_query($connect, $sql);

                $suppliers = [];
                $totalsPerSupplier = [];

                while ($row = mysqli_fetch_assoc($categoriesResults)) {
                    $suppliers[] = $row['supplier_name'];
                    $totalsPerSupplier[] = $row['total_purchases'];
                }
                ?>

            <div class="pie-chart-container">
                <h2>Supplier-wise Purchases</h2>
                <svg viewBox="0 0 50 50" class="pie">
                    <?php
                    $purCumulative = 25;
                    $purCount = count($totalsPerSupplier);
                    $purTotalPie = array_sum($totalsPerSupplier);

                    $purColors = [];

                    foreach ($totalsPerSupplier as $j => $purValue) {
                        $purPercent = $purTotalPie ? $purValue / $purTotalPie : 0;
                        $purDashArray = ($purPercent * 100) . " " . (100 - $purPercent * 100);

                        $purHue = ($j * 360 / $purCount);
                        $purColor = "hsl($purHue, 70%, 50%)";
                        $purColors[] = $purColor;

                        echo "<circle r='16' cx='25' cy='25' 
                                stroke='{$purColor}' 
                                stroke-width='10' 
                                fill='transparent' 
                                stroke-dasharray='{$purDashArray}' 
                                stroke-dashoffset='{$purCumulative}' />
                        ";
                        $purCumulative -= $purPercent * 100;
                    }
                    ?>
                </svg>

                <div class="legend">
                        <?php
                        foreach ($suppliers as $j => $supplier) {
                            echo "<div><span style='background-color: {$purColors[$j]}'></span> {$supplier} - LKR " . number_format($totalsPerSupplier[$j], 2) . "</div>";
                        }
                        ?>
                    </div>
                </div>

                <div class="pie-chart-stats">
                    <?php
                    //pie chart stats
                    $maxIndexSup = !empty($totalsPerSupplier) ? array_keys($totalsPerSupplier, max($totalsPerSupplier))[0] : -1;
                    $minIndexSup = !empty($totalsPerSupplier) ? array_keys($totalsPerSupplier, min($totalsPerSupplier))[0] : -1;
                    ?>

                    <div class="top-supplier">
                        <h2>Top Supplier</h2>
                        <p>
                            <?php
                            echo "<p>" . $suppliers[$maxIndexSup]."<br>";
                            echo "LKR " . number_format($totalsPerSupplier[$maxIndexSup], 2) . "</p>";
                            ?>
                        </p>
                    </div>

                    <div class="low-supplier">
                        <h2>Lowest Supplier</h2>
                        <p>
                            <?php
                            echo "<p>" . $suppliers[$minIndexSup]."<br>";
                            echo "LKR " . number_format($totalsPerSupplier[$minIndexSup], 2) . "</p>";
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="progress-bars">
                <div class="sup-order-progress-container">
                    <?php
                    $totalSupOrdersSql="SELECT COUNT(*) AS total FROM supplier_orders WHERE YEAR(order_date) = $year";
                    $totalSupOrdersResults=mysqli_query($connect, $totalSupOrdersSql);
                    $totalSupOrders=mysqli_fetch_assoc($totalSupOrdersResults)['total'];

                    $completedSupOrdersSql="SELECT COUNT(*) AS completed FROM supplier_orders WHERE YEAR(order_date) = $year AND order_status='completed'";
                    $completedSupOrdersResults=mysqli_query($connect, $completedSupOrdersSql);
                    $completedSupOrders=mysqli_fetch_assoc($completedSupOrdersResults)['completed'];

                    $completedSupPercentage=$totalSupOrders>0 ? ($completedSupOrders/$totalSupOrders)*100 : 0;
                    ?>

                    <h2>Completed Purchases</h2>
                    <div class="sup-order-progress-bar">
                        <div class="sup-order-progress-fill" style="width: <?php echo $completedSupPercentage; ?>%;"></div>
                    </div>
                    <p><strong><?php echo round($completedSupPercentage, 2); ?>%</strong> of Purchases are Completed</p>
                </div>
            </div>

        </div>
        </div>
</div>

<!-- animate barchart -->
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const bars=document.querySelectorAll('.bar-fill');

    bars.forEach(bar=>{
        const finalHeight=bar.getAttribute('data-height');
        bar.style.height='0px';

        setTimeout(()=>{
            bar.style.height=finalHeight+'px';
            },50);
        });
    });

//hovering effect for bar charts
document.addEventListener('DOMContentLoaded', () => {
    const bars = document.querySelectorAll('.bar-fill');

    bars.forEach(bar => {
        const finalHeight = bar.getAttribute('data-height');
        bar.style.height = '0px';

        setTimeout(() => {
            bar.style.height = finalHeight + 'px';
        }, 50);
    });
});


//hovering effects for pie charts
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.pie-chart-container').forEach(container => {
        const tooltip = container.querySelector('.pie-tooltip');
        const slices = container.querySelectorAll('circle');

        slices.forEach(slice => {
            slice.addEventListener('mousemove', e => {
                tooltip.style.opacity = '1';

                const offsetX = 15;
                const offsetY = 15;
                tooltip.style.left = (e.pageX + offsetX) + 'px';
                tooltip.style.top = (e.pageY + offsetY) + 'px';

                tooltip.innerText = slice.getAttribute('data-label');
            });

            slice.addEventListener('mouseleave', () => {
                tooltip.style.opacity = '0';
            });
        });
    });
});


</script>

<?php
include 'footer.php';
?>
