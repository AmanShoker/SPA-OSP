<?php session_start(); ?>
<?php 
echo  $_SESSION["checkOutInformation"];
$_SESSION["checkOutInformation"] = "";
?>