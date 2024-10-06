<?php
session_start();
include '../../global-assets/db.php'; // Include your database connection

if (!isset($_SESSION['ses_admin_id'])) {
    echo '<h1>Unauthorized Access</h1>';
    echo '<p>You do not have permission to access this page.</p>';
    echo '<p><a href="../../login-admin/login-admin.php">Click to login</a></p>';
    exit;
}

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Escape the username to prevent SQL injection
    $username = mysqli_real_escape_string($connection, $username);

    // Define the default password
    $defaultPassword = password_hash('Bubble@1234', PASSWORD_DEFAULT); // Hash the default password

    // Update the user's password in the database
    $resetPasswordQuery = "UPDATE shop_managers SET password_hash='$defaultPassword' WHERE username='$username'";
    if (mysqli_query($connection, $resetPasswordQuery)) {
        echo '<h1>Password Reset Successful</h1>';
        echo '<p>The password for user ' . htmlspecialchars($username) . ' has been reset to the default value. (Bubble@1234) <a href="/account/admin/manage-shop-managers.php">Return</a></p>';
    } else {
        echo '<h1>Error</h1>';
        echo '<p>Could not reset the password. Please try again later.</p>';
    }
} else {
    echo '<h1>Error</h1>';
    echo '<p>No username specified for password reset.</p>';
}
?>