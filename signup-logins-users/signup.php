<?php
// Database connection
include 'db.php'; 

$successMessage = ""; // Variable to hold success message
$errorMessage_email = "";
$errorMessage_username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email - username already exists
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $checUsername = "SELECT * FROM users WHERE username = '$username'";
    $result_email = mysqli_query($connection, $checkEmail);
    $result_username = mysqli_query($connection, $checUsername);

    if (mysqli_num_rows($result_email) > 0) {
        // Email already taken
        $errorMessage_email = "This email is already taken. Please try another.";
    } elseif(mysqli_num_rows($result_username) > 0){
        $errorMessage_username = "This username is already taken. Please try another.";
    } else {
        // Prepare the insert statement
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        
        if (mysqli_query($connection, $sql)) {
            // Set success message
            $successMessage = "Signup successful!";
        } else {
            // Error handling for insert failure
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
    <link rel="stylesheet" href="styles/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="signup-container">
    <h2>Sign Up</h2>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        
        <div class="pass-field">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <i class="fa-solid fa-eye"></i>
        </div>

        <button type="submit">Sign Up</button>
    </form>

    <!-- Conditions List -->
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
    // Use echo to add the script after the body has loaded
    echo "<script>
            window.onload = function() {
                swal.fire({
                    title: 'Success!',
                    text: '$successMessage',
                    icon: 'success',
                    button: 'OK',
                }).then(() => {
                    window.location.href = 'login.php'; // Redirect after alert
                });
            };
          </script>";
}elseif ($errorMessage_email){
    echo "<script>
            window.onload = function() {
                swal.fire({
                    title: 'Error!',
                    text: '$errorMessage_email',
                    icon: 'error',
                    button: 'OK',
                }).then(() => {
                    window.location.href = 'signup.php'; // Redirect after alert
                });
            };
          </script>";
}
elseif ($errorMessage_username){
    echo "<script>
            window.onload = function() {
                swal.fire({
                    title: 'Error!',
                    text: '$errorMessage_username',
                    icon: 'error',
                    button: 'OK',
                }).then(() => {
                    window.location.href = 'signup.php'; // Redirect after alert
                });
            };
          </script>";
}
?>

<script src="js/signup.js"></script>
</body>
</html>
