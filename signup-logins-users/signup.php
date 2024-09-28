<?php
// Database connection
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    if (mysqli_query($connection, $sql)) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
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

<script src="js/signup.js"></script>
</body>
</html>


