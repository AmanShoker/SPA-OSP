<?php 
session_start();
require "connect.php"; 
require "UserTableController.php";

$UTC = New UserTableController();

$username = $_POST['username'];
$password = $_POST['password'];

if ($UTC->validLogin($conn,$username,$password)){
    $passwordHash = $UTC->validLogin($conn,$username,$password);
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $passwordHash;
    header("Location: OSP-main.php");
    exit();
} else {
    header("Location: OSP-begin.html#!/signIn?signInError=insertfailed");
    exit();
}
$conn->close();
?>