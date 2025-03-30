<?php
 session_start();
 require "connect.php";
 require "ReviewTableController.php";
 require "UserTableController.php";
 $UTC = New UserTableController();
 $RTC = New ReviewTableController();
 $username = $_SESSION["username"];
 $password = $_SESSION["password"];
 $userIdRecordArray = $UTC->getUserId($conn,$username,$password);
 $userIdRecord = $userIdRecordArray->fetch_assoc();
 $userId = $userIdRecord["userId"];
 $review = $_POST["review"];
 $rating = $_POST["rating"];
 $RTC->createReview($conn,$userId,$review,$rating);
 header("Location: OSP-main.php#!/createReview?created=True");
 exit();
?>