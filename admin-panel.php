<?php 

session_start();

if (!isset($_SESSION['ses_admin_id'])) {
    header("Location: login-admin/login-admin.php");
    exit();
}

include 'global-assets/db.php';

$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = $connection->query($user_query);
$user_data = $user_result->fetch_assoc();
$total_users = $user_data['total_users'];

// Get total shop managers
$manager_query = "SELECT COUNT(*) AS total_managers FROM shop_managers";
$manager_result = $connection->query($manager_query);
$manager_data = $manager_result->fetch_assoc();
$total_managers = $manager_data['total_managers'];

$recent_orders_query = "SELECT order_id, total_amount, order_date, status FROM orders ORDER BY order_date DESC LIMIT 5";
$recent_orders_result = mysqli_query($connection, $recent_orders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/admin-panel.css">
</head>
<body>
        <?php $IPATH = "global-assets/"; include($IPATH."admin-sidebar.html"); ?>

        <div class="dashboard-container">
        <!-- User Stats Section -->
        <section class="user-stats">
            <h2>User Statistics</h2>
            <div class="stats">
                <div class="total-users">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="total-managers">
                    <h3>Total Shop Managers</h3>
                    <p><?php echo $total_managers; ?></p>
                </div>
            </div>
            <!-- Buttons to Add/Manage Users -->
            <div class="user-buttons">
                <a href="account/admin/add-user.php" class="btn">Add New User</a>
                <a href="account/admin/manage-users.php" class="btn">Manage Users</a>
            </div>
        </section>

        <!-- Recent Orders Section -->
        <section class="recent-orders">
            <h2>Recent Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($recent_orders_result)): ?>
                        <tr>
                            <td><?php echo 'BB_' . $order['order_id']; ?></td>
                            <td><?php echo 'LKR ' . number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo date('F j, Y', strtotime($order['order_date'])); ?></td>
                            <td><?php echo $order['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
  <!-- Add this before the closing </body> tag -->
<script>
    // Function to show the popup
    function showPopup() {
        document.getElementById('userPopup').style.display = 'block';
    }

    // Function to hide the popup
    function hidePopup() {
        document.getElementById('userPopup').style.display = 'none';
    }
</script>

<!-- Popup Structure -->
<div id="userPopup" class="popup" style="display: none;">
    <div class="popup-content">
        <span class="close" onclick="hidePopup()">&times;</span>
        <h3>Manage Users</h3>
        <button onclick="window.location.href='account/admin/manage-shop-managers.php'" class="popup-button">Shop Managers</button>
        <button onclick="window.location.href='account/admin/manage-regular-users.php'" class="popup-button">Customers</button>
    </div>
</div>

<script>
    // Add event listener to the Manage Users button
    document.querySelector('.user-buttons .btn:nth-child(2)').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        showPopup(); // Show the popup
    });
</script>
</body>
</html>