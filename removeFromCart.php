<?php 
session_start();
require "connect.php"; 
require "UserTableController.php";
require "ShoppingCartTableController.php";

$SCTC = New ShoppingCartTableController();
$UTC = New UserTableController();

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$userIdRecordArray = $UTC->getUserId($conn,$username,$password);
$userIdRecord = $userIdRecordArray->fetch_assoc();
$userId=$userIdRecord["userId"];

$itemId = $_GET['itemId'];
$SCTC->removeFromCart($conn,$itemId,$userId);
header("Location: index.php#!/shoppingCart");

?>