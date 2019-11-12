<?php 
include "config.php";

include "products.php";
$price = 0;
$totalprice = 0;


function add(item,price) {
    $price = price;
    echo "added to basket";
    $totalprice == $price + $totalprice;
}


function remove(item,price) {
    $price = price;
    
    echo "removed from basket";
    $totalprice == $totalprice - $price;
    
}


?>
