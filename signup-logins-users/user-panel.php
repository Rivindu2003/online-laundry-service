<?php
// Include your database connection file
session_start();
include('db.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the logged-in user's ID (assuming you store it in session)
$userId = $_SESSION['user_id']; // Adjust this based on your session variable

// Fetch orders for the logged-in user
$query = "SELECT orders.*, users.username, users.email 
          FROM orders 
          JOIN users ON orders.customer_id = users.id 
          WHERE orders.customer_id = ?"; // Make sure to replace 'user_id' with your actual column name
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userInfo = $result->fetch_assoc()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles/header-user.css">
    <link rel="stylesheet" href="styles/body.css">
</head>
<body>
<header class="header">
        <img src="images/logo-white.png" alt="logo">
        <nav class="navbar">
            <a href="#">Home</a>
            <a href="#">Our Services</a>
            <a href="#">About us</a>
            <a href="#">Contact us</a>
            <a id="login" href="logout.php">
                <img id="login-image" src="images/logout.png">
                Logout
            </a>
        </nav>
    </header>

    <div class="container">

    <aside class="sidebar">
            <div class="user-info">
                <p id="userName_sidebar"><?php echo htmlspecialchars('username : ' . $userInfo['username']); ?></p>
                <p id="email_sidebar"><?php echo htmlspecialchars($userInfo['email']); ?></p>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Orders</a></li>
                    <li><a href="#">Downloads</a></li>
                    <li><a href="#">Addresses</a></li>
                    <li><a href="#">Account Details</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

    <h1>Overview</h1>
    
    <h2>Order ID:</h2>

    <table>
        <thead>
            <tr>
                <th>OrderID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php $result->data_seek(0); ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo '#0000' .$row['order_id']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['order_date'])); ?></td> <!-- Adjust column name if necessary -->
                    <td><?php echo number_format($row['total_amount'], 2); ?> LKR</td> <!-- Assuming 'total' is a decimal value -->
                    <td><?php echo $row['status']; ?></td> <!-- Adjust column name if necessary -->
                    <td>
                        <button onclick="trackOrder(<?php echo $row['order_id']; ?>)">Track</button>
                        <button onclick="reorder(<?php echo $row['order_id']; ?>)">Reorder</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="options">
        <h2>Schedule Your Delivery</h2>
        <form action="schedule_delivery.php" method="POST">
            <label for="delivery_date">Choose Delivery Date:</label>
            <input type="date" name="delivery_date" id="delivery_date" required>
            <input type="submit" value="Schedule">
        </form>
        
        <h2>Create New Service</h2>
        <form action="create_service.php" method="POST">
            <label for="service_name">Service Name:</label>
            <input type="text" name="service_name" id="service_name" required>
            <input type="submit" value="Create">
        </form>
    </div>
</div>

    <footer>
    <div class="footerContainer">

        <center><img src="images/logo-white.png"></center>

        <div class="footerNav">
            <ul><li><a href="">Home</a></li>
                <li><a href="">Our Services</a></li>
                <li><a href="">About US</a></li>
                <li><a href="">Contact Us</a></li>
                <li><a href="">Login / Signup</a></li>
            </ul>
        </div>
        
    </div>
    <div class="footerBottom">
        <p>Copyright &copy;2024; Designed by <span class="designer">Group 1.1 Team</span></p>
    </div>
    </footer>
</body>
</html>