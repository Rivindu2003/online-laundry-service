<?php
session_start();

if (!isset($_SESSION['ses_admin_id'])) {
    echo '<h1>Unauthorized Access</h1>';
    echo '<p>You do not have permission to access this page.</p>';
    echo '<p><a href="../../login-admin/login-admin.php">Click to login</a></p>';
    exit;
}

include '../../global-assets/db.php';

$success_message = '';

if (isset($_GET['username'])) {
    $username = htmlspecialchars($_GET['username']);

        $query = "SELECT * FROM shop_managers WHERE username = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("User not found.");
    }

    $user = $result->fetch_assoc();
} else {
    die("Username not provided.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

        $update_query = "UPDATE shop_managers SET first_name = ?, last_name = ?, email = ?, phone_number = ? WHERE username = ?";
    $update_stmt = $connection->prepare($update_query);
    $update_stmt->bind_param("sssss", $first_name, $last_name, $email, $phone_number, $username);
    $update_stmt->execute();

    $success_message = "User Updated";
}

if (isset($_POST['delete_user'])) {
    $delete_query = "DELETE FROM shop_managers WHERE username = ?";
    $delete_stmt = $connection->prepare($delete_query);
    $delete_stmt->bind_param("s", $username);
    $delete_stmt->execute();

    header("Location: manage-shop-managers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../../css/admin-sidebar.css">
    <link rel="stylesheet" href="../../css/admin-panel.css">
    <script src="../../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
</head>
<body>
    <?php $IPATH = "../../global-assets/"; include($IPATH . "administrator-sidebar.html"); ?>
    
    <div class="edit-user-container">
        <h1>Edit User Details</h1>
        <form method="POST" action="edit-user-shopmgr.php?username=<?php echo urlencode(htmlspecialchars($user['username'])); ?>">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
            <div>
                <label>First Name:</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div>
                <label>Last Name:</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div>
                <label>Phone Number:</label>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
            </div>
            <div class="button-group">
                <button class="manage-btn" onclick="window.location.href='edit-user-shopmgr.php?username=<?php echo urlencode(htmlspecialchars($user['username'])); ?>'">Save Changes ✅</button>
                <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</button>
            </div>
        </form>
    </div>
    <?php
    if ($success_message) {
            echo "<script>
                swal({
                    title: 'Done!',
                    text: 'User updated!',
                    icon: 'success'
                }).then((value) => {
            window.location.href = 'manage-shop-managers.php';
        });
            </script>";
}
    ?>
</body>
</html>

<style>
    .edit-user-container {
        width: 50%;
        margin: 0 auto;         padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;     }

    h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .button-group {
        display: flex;
        justify-content: space-around;     }

    button {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background-color: #007BFF;         color: white;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;     }
</style>
