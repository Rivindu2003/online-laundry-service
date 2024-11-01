<?php

include '../global-assets/db.php'; 

$successMessage = ""; 
$errorMessage_email = "";
$errorMessage_username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $checUsername = "SELECT * FROM users WHERE username = '$username'";
    $result_email = mysqli_query($connection, $checkEmail);
    $result_username = mysqli_query($connection, $checUsername);

    if (mysqli_num_rows($result_email) > 0) {
        
        $errorMessage_email = "This email is already taken. Please try another.";
    } elseif(mysqli_num_rows($result_username) > 0){
        $errorMessage_username = "This username is already taken. Please try another.";
    } else {
        
        $sql = "INSERT INTO users (first_name, last_name, username, password, email, phone_number) VALUES ('$first_name','$last_name','$username', '$password', '$email', '$phone')";
        
        if (mysqli_query($connection, $sql)) {
            
            $successMessage = "Signup successful!";
        } else {
            
            $errorMessage = "Error: " . mysqli_error($connection);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
</head>
<body>

<div class="signup-container">
    <h2>Register to BubbleShine</h2>
    <form method="POST" action="">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="phone" name="phone" placeholder="Phone Number" required>
        
        <div class="pass-field">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <i class="fa-solid fa-eye"></i>
        </div>

        <button type="submit">Sign Up</button>
    </form>

    <div class="content">
        <p>Password must contain:</p>
        <ul id="password-conditions" class="requirement-list">
            <li><i class="fa-solid fa-circle"></i> Minimum 6 characters</li>
            <li><i class="fa-solid fa-circle"></i> Maximum 15 characters</li>
            <li><i class="fa-solid fa-circle"></i> At least 1 number (0...9)</li>
            <li><i class="fa-solid fa-circle"></i> At least 1 lowercase letter (a...z)</li>
            <li><i class="fa-solid fa-circle"></i> At least 1 special character (!...$)</li>
            <li><i class="fa-solid fa-circle"></i> At least 1 uppercase letter (A...Z)</li>
        </ul>
    </div>
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
}elseif ($errorMessage_email){
    echo "<script>
            window.onload = function() {
                swal({
                    title: 'Error!',
                    text: '$errorMessage_email',
                    icon: 'error',
                    button: 'OK',
                }).then(() => {
                    window.location.href = 'signup.php'; 
                });
            };
          </script>";
}

elseif ($errorMessage_username){
    echo "<script>
            window.onload = function() {
                swal({
                    title: 'Error!',
                    text: '$errorMessage_username',
                    icon: 'error',
                    button: 'OK',
                }).then(() => {
                    window.location.href = 'signup.php'; 
                });
            };
          </script>";
}
?>

<script src="../js/signup.js"></script>
</body>
</html>
