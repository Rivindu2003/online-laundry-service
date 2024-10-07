<?php
session_start(); 


$_SESSION = [];


session_destroy();


header('Location: ../login-shop/login.php'); 
exit;
?>
