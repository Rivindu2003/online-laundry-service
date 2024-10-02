<?php
session_start();
include 'db.php'; 

$successMessage = "";
$failedMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $successMessage = "Login successful!";
    } else {
        $failedMessage = "Login Failed";
    }
}

?>
<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/signin.css"> <!-- Make sure to link your CSS file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="login-container">
        <h2>Customer Login</h2>
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
                swal.fire({
                    title: 'Success!',
                    text: '$successMessage',
                    icon: 'success',
                    button: 'OK',
                }).then(() => {
                    window.location.href = 'user_details.php'; // Redirect after alert
                });
            };
          </script>";
}else if ($failedMessage){
    echo "<script>
            window.onload = function() {
                swal.fire({
                    title: 'Oops..!',
                    text: '$failedMessage',
                    icon: 'error',
                    button: 'OK',
                }).then(() => {
                    window.location.href = 'login.php'; // Redirect after alert
                });
            };
          </script>";
}
?>

</body>
</html>
