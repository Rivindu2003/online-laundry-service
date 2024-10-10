<?php
session_start();
include '../../global-assets/db.php'; 
if (!isset($_SESSION['ses_admin_id'])) {
    echo '<h1>Unauthorized Access</h1>';
    echo '<p>You do not have permission to access this page.</p>';
    echo '<p><a href="../../login-admin/login-admin.php">Click to login</a></p>';
    exit;
}

$usernameError = "";
$emailError = "";

$successMessage = "";
$failedMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
    $phone_number = mysqli_real_escape_string($connection, $_POST['phone_number']);
    $account_type = mysqli_real_escape_string($connection, $_POST['account_type']);
    $address = isset($_POST['address']) ? mysqli_real_escape_string($connection, $_POST['address']) : '';

        $default_password = 'Bubble@1234';
    $password_hash = password_hash($default_password, PASSWORD_DEFAULT);

        $usernameCheckQuery = "SELECT username FROM users WHERE username = '$username'
                            UNION ALL
                            SELECT username FROM shop_managers WHERE username = '$username'";
    $usernameResult = mysqli_query($connection, $usernameCheckQuery);

    if (!$usernameResult) {
                die("Error checking username: " . mysqli_error($connection));
    }

    if (mysqli_num_rows($usernameResult) > 0) {
        $usernameError = "Username already exists. Please choose a different one.";
    }

        $emailCheckQuery = "SELECT email FROM users WHERE email = '$email'
                        UNION ALL
                        SELECT email FROM shop_managers WHERE email = '$email'";
    $emailResult = mysqli_query($connection, $emailCheckQuery);

    if (!$emailResult) {
                die("Error checking email: " . mysqli_error($connection));
    }

    if (mysqli_num_rows($emailResult) > 0) {
        $emailError = "Email already exists. Please choose a different one.";
    }

        if (empty($usernameError) && empty($emailError)) {
        if ($account_type == 'user') {
                        $insertUserQuery = "INSERT INTO users (username, password, email, created_at, address, phone_number, first_name, last_name)
                                VALUES ('$username', '$password_hash', '$email', NOW(), '$address', '$phone_number', '$first_name', '$last_name')";
            if (mysqli_query($connection, $insertUserQuery)) {
                $successMessage = "User added successfully!";
            } else {
                $failedMessage = "Error adding user: " . mysqli_error($connection);
            }
        } else if ($account_type == 'shop_manager') {
                        $insertManagerQuery = "INSERT INTO shop_managers (username, password_hash, email, created_at, phone_number, first_name, last_name)
                                   VALUES ('$username', '$password_hash', '$email', NOW(), '$phone_number', '$first_name', '$last_name')";
            if (mysqli_query($connection, $insertManagerQuery)) {
                $successMessage = "Shop Manager added successfully!";
            } else {
                $failedMessage = "Error adding shop manager: " . mysqli_error($connection);
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User/Manager</title>
    <link rel="stylesheet" href="../../css/admin-sidebar.css">
    <link rel="stylesheet" href="../../css/add-user.css">
    <script src="../../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
</head>
<body>

    <?php $IPATH = "../../global-assets/"; include($IPATH."administrator-sidebar.html"); ?>

    <div class="container">
        <h2>Add New User or Shop Manager</h2>
        <form id="add-user-form" action="add-user.php" method="POST">
            
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <div id="username-error" class="error">Invalid username</div>
            <?php if (!empty($usernameError)) { echo '<p class="error">' . $usernameError . '</p>'; } ?>

            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <?php if (!empty($emailError)) { echo '<p class="error">' . $emailError . '</p>'; } ?>
            
            
            <label for="phone_number">Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" required>

            
            <label for="address">Address (for Users only):</label>
            <input type="text" id="address-field" name="address">

            
            <label for="account_type">Account Type:</label>
            <select id="account_type" name="account_type" required>
                <option value="user">User</option>
                <option value="shop_manager">Shop Manager</option>
            </select>

            
            <input type="submit" value="Add New Account">
        </form>
    </div>
    <script>
    <?php if ($successMessage): ?>
            swal({
                title: "Success!",
                text: "<?php echo $successMessage; ?>",
                icon: "success"
            }).then(function() {
                window.location = "../../admin-panel.php";             });
        <?php elseif ($failedMessage): ?>
            swal({
                title: "Error!",
                text: "<?php echo $failedMessage; ?>",
                icon: "error"
            });
        <?php endif; ?>
    </script>
    <style>
        .error {
            color: red;
            display: none;
        }
    </style>
    <script>
        function validateUsername() {
            const usernameInput = document.getElementById('username');
            const usernameError = document.getElementById('username-error');
            const username = usernameInput.value;

                        const regex = /^(?!.*[_.]{2})[a-zA-Z0-9._]{3,}$/;

            if (username.length < 3 || !regex.test(username) || username.startsWith('_') || username.endsWith('_') || username.startsWith('.') || username.endsWith('.')) {
                usernameError.style.display = 'block';
                usernameError.innerText = 'Invalid username. Must be at least 3 characters long and can only contain letters, numbers, underscores, and periods.';
                return false;
            } else {
                usernameError.style.display = 'none';                 return true;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('add-user-form');
            form.addEventListener('submit', function(event) {
                if (!validateUsername()) {
                    event.preventDefault();                 }
            });
        });
    </script>
    <script src="../../js/address-field.js"></script>
</body>
</html>

