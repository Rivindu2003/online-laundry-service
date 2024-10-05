<?php
session_start();
include '../global-assets/db.php'; 

$successMessage = "";
$failedMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $connection->prepare("SELECT admin_id, username, password_hash, first_name, last_name, email FROM website_administrators WHERE username = ?");
    $stmt->bind_param("s", $username); // "s" means string type
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Store necessary session data
        $_SESSION['ses_admin_id'] = $user['admin_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];
        
        $successMessage = "Login successful!";
    } else {
        $failedMessage = "Login failed. Please check your credentials.";
    }

    $stmt->close();
    $connection->close();
}
?>
<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login-admin.css"> 
    <script src="../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
</head>
<body>
    <div class="login-container">
        <h2>Administrator LOGIN</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p>Don't have a account? <span><a href="signup.php">Sign Up Now</a></span></p>
        </form>
    </div>

    <?php
if ($successMessage) {
    // Use echo to add the script after the body has loaded
    echo "<script>
            window.onload = function() {
                swal({
                    title: 'Success!',
                    text: '$successMessage',
                    icon: 'success',
                    button: 'OK',
                }).then(() => {
                    window.location.href = '../admin-panel.php'; // Redirect after alert
                });
            };
          </script>";
}else if ($failedMessage){
    echo "<script>
            window.onload = function() {
                swal({
                    title: 'Oops..!',
                    text: '$failedMessage',
                    icon: 'error',
                    button: 'OK',
                }).then(() => {
                    window.location.href = 'login-admin.php'; // Redirect after alert
                });
            };
          </script>";
}
?>

</body>
</html>
