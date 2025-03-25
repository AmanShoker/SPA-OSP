<?php 

require "connect.php"; 
require "UserTableController.php";

$UTC = New UserTableController();

$username = $_POST['username'];
$password = $_POST['password'];
$fullName = $_POST['name'];
$telNo = $_POST['telno'];
$address = $_POST['address'];
$cityCode = $_POST['citycode'];
$email = $_POST['email'];
// note: the current total cost of all items together is $3350, a max of 5000 is to ensure there are cases where a user is able to buy everything
$balance = 0;

$salt = base64_encode(random_bytes(12));
if ($UTC->insertRecord($conn,$fullName,$telNo,$email,$address,$cityCode,$username,$password,$balance,$salt)) {
    header("Location: SignIn.html");
    exit();
} else {
    header("Location: SignUp.html?error=insertfailed");
    exit();
}




$conn->close();
?>