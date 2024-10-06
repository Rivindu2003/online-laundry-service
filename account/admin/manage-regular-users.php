<?php
// Start the session
session_start();
include '../../global-assets/db.php'; // Include your database connection

if (!isset($_SESSION['ses_admin_id'])) {
    echo '<h1>Unauthorized Access</h1>';
    echo '<p>You do not have permission to access this page.</p>';
    echo '<p><a href="../../login-admin/login-admin.php">Click to login</a></p>';
    exit;
}

// Initialize filter variable
$filter = isset($_GET['user_type']) ? $_GET['user_type'] : 'all';

// Fetch users based on filter
$usersQuery = "SELECT created_at , first_name, last_name, phone_number, email, username FROM users";

$result = mysqli_query($connection, $usersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../../css/manage-shop-manager.css"> 
    <link rel="stylesheet" href="../../css/admin-sidebar.css">
</head>
<body>
    <?php $IPATH = "../../global-assets/"; include($IPATH."administrator-sidebar.html"); ?>
    <h1 class="table-title">Manage Customers</h1>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . htmlspecialchars($user['last_name'])); ?></td>
                        <td><?php echo htmlspecialchars($user['phone_number'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars('USER'); ?></td> 
                        <td>
                        <button class="manage-btn" onclick="window.location.href='edit-user-customer.php?username=<?php echo urlencode(htmlspecialchars($user['username'])); ?>'">Edit</button>
                        <button class="reset-password-btn" onclick="resetPassword('<?php echo htmlspecialchars($user['username']); ?>')">Reset Password</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        function manageUser(username) {
            // Redirect to manage user page or modal for further actions
            alert("Manage user: " + username);
            // You can replace the alert with a redirect or a modal call
            // window.location.href = "edit-user.php?username=" + username;
        }

        function resetPassword(username) {
        if (confirm('Are you sure you want to reset the password for ' + username + '? This action cannot be undone.')) {
            // Redirect to the reset password PHP script
            window.location.href = 'reset-password.php?username=' + encodeURIComponent(username);
        }
    }
    </script>
</body>
</html>
