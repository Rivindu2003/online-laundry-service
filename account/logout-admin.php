<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the home page
header('Location: ../login-admin/login-admin.php'); // Change 'index.php' to your home page file
exit;
?>
