<?php session_start(); ?>
<?php 
echo  $_SESSION["searchResultInformation"];
$_SESSION["searchResultInformation"] = "";
?>