<?php
// Start the session
session_start();
include '../global-assets/db.php'; // Include your database connection

// Initialize filter variable
$filter = isset($_GET['user_type']) ? $_GET['user_type'] : 'all';

// Fetch users based on filter
$usersQuery = "
    SELECT created_at , first_name, last_name, phone_number, email, username FROM shop_managers";

$result = mysqli_query($connection, $usersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles/manage-shop-manager.css"> <!-- Include your CSS file -->
    <link rel="stylesheet" href="../global-assets/admin-sidebar.css">
</head>
<body>
    <?php $IPATH = "../global-assets/"; include($IPATH."admin-sidebar.html"); ?>
    <h1 class="table-title">Manage Shop Managers</h1>

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
                        <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars('SHOP MANAGER'); ?></td> 
                        <td>
                        <button class="manage-btn" onclick="window.location.href='edit-user-shopmgr.php?username=<?php echo urlencode(htmlspecialchars($user['username'])); ?>'">Edit</button>
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
    </script>
</body>
</html>
