<?php
session_start(); // Start the session
$_SESSION = array();
session_destroy();

header("Location: index.php");
exit();
?>