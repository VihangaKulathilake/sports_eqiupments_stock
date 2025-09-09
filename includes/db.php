<?php
$server="localhost";
$dbUsername="root";
$dbPassword="";
$dbName="sports_stock";

$connect=mysqli_connect($server,$dbUsername,$dbPassword,$dbName);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
?>