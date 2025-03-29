<?php
session_start();
$_SESSION["cartInformation"] = "";
$cart = $_POST['cart'];
$_SESSION["cartInformation"] .= "<table class='cart_table'>";
$_SESSION["cartInformation"] .= "<tr><th>Name</th><th>Quantity</th><th>Price</th></tr>";
$subTotal = 0;
foreach ($cart as $itemId => $itemData) {
    $name = $itemData['name'];
    $quantity = $itemData['quantity'];
    $price = $itemData['price'];

    $_SESSION["cartInformation"] .= "<tr><td>$name</td><td>x$quantity</td><td>$price</td></tr>";
    $temp = substr($price, 1);
    $subTotal += $temp;
    }
    $_SESSION["cartInformation"] .= "</table>";
    $_SESSION["cartInformation"] .= "<div class='total_price'>SUBTOTAL: $$subTotal</div>";
?>   