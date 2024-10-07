<?php

include '../global-assets/db.php';

$successMessage = '';
$failedMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $prev_password = $_POST['prev_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (strlen($new_password) < 6 || strlen($new_password) > 15) {
        echo "<p class='error'>Password must be between 6 and 15 characters.</p>";
    } elseif (!preg_match('/[A-Z]/', $new_password)) {
        echo "<p class='error'>Password must contain at least one capital letter.</p>";
    } elseif (!preg_match('/[0-9]/', $new_password)) {
        echo "<p class='error'>Password must contain at least one number.</p>";
    } elseif (!preg_match('/[\W]/', $new_password)) {
        echo "<p class='error'>Password must contain at least one special symbol.</p>";
    } elseif ($new_password !== $confirm_password) {
        echo "<p class='error'>Passwords do not match.</p>";
    } else {
        
        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($prev_password, $user['password'])) {
        
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $connection->prepare("UPDATE users SET password = ? WHERE username = ?");
            $update_stmt->bind_param("ss", $hashed_password, $username);
            $update_stmt->execute();

            $successMessage =  "Password successfully updated!";
        } else {
            $failedMessage =  "Incorrect username or previous password.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../css/reset.css">
</head>
<body>

<div class="form-container">
    <div class="image-container">
    <img src="../images/logo-black.png" alt="Logo" class="logo">
    <script src="../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
    </div>
    <h2>Change Password Portal</h2>
    <form action="" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="prev_password" placeholder="Previous Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
        <button type="submit">Change Password</button>
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
                            window.location.href = '../login/login.php'; 
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
                            window.location.href = 'reset.php'; 
                        });
                    };
                </script>";
        }
?>
</body>
</html>
