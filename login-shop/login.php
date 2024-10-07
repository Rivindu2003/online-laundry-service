<?php
session_start();
include '../global-assets/db.php'; 

$successMessage = "";
$failedMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM shop_managers WHERE username = '$username'";
    $result = mysqli_query($connection, $sql);
    $manager = mysqli_fetch_assoc($result);

    if ($manager && password_verify($password, $manager['password_hash'])) {
        $_SESSION['shop_manager_id'] = $manager['manager_id'];
        $_SESSION['username'] = $manager['username'];
        $successMessage = "Login successful!";
    } else {
        $failedMessage = "Login Failed";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login-shop.css"> 
    <script src="../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
</head>
<body>
    <div class="login-container">
        <div class="image-container">
            <img src="../images/logo-black.png" alt="Logo" class="logo">
        </div>
        <h2>Shop Manager Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>

    <?php
if ($successMessage) {
    
    echo "<script>
            window.onload = function() {
                swal({
                    title: 'Success!',
                    text: '$successMessage',
                    icon: 'success',
                    button: 'OK',
                }).then(() => {
                    window.location.href = '../shop-manager.php'; 
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
                    window.location.href = 'login.php'; 
                });
            };
          </script>";
}
?>

</body>
</html>
