<?php 
session_start();
require "connect.php"; 
require "ShoppingCartTableController.php";
require "UserTableController.php";
require "ItemTableController.php";
$SCTC = New ShoppingCartTableController();
$UTC = New UserTableController();
$ITC = New ItemTableController();
$itemId = $_GET["itemId"];
$username = $_SESSION["username"];
$password = $_SESSION["password"];


$userIdRecordArray = $UTC->getUserId($conn,$username,$password);
$userIdRecord = $userIdRecordArray->fetch_assoc();
$userId=$userIdRecord["userId"];

$ItemRecordArray = $ITC->getSpecificItem($conn,$itemId);
$ItemRecord = $ItemRecordArray->fetch_assoc();
$itemName=$ItemRecord["itemName"];
$price=$ItemRecord["price"];
$madeIn=$ItemRecord["madeIn"];
$departmentCode=$ItemRecord["departmentCode"];

if (($SCTC->getSpecificCartItem($conn,$userId,$itemId))->num_rows == 0){
    $SCTC->addToCart($conn,$itemId,$userId,$itemName,$price,$madeIn,$departmentCode);
    header("Location: index.php#!/catalogue?added=True");
    exit();
}
else{
    header("Location: index.php#!/catalogue?added=False");
    exit();
}
?>