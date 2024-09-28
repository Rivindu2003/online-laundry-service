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
    <title>Document</title>
    <link rel="stylesheet" href="styles/signup.css">
</head>
<body>

<div class="signup-container">
    <h2>Sign Up</h2>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign Up</button>
    </form>
</div>

</body>
</html>

