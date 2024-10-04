<?php
session_start();
include('../../global-assets/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login/login.php');
    exit;
}

$userId = $_SESSION['user_id']; 

$query = "SELECT orders.*, users.username, users.email 
          FROM orders 
          JOIN users ON orders.customer_id = users.id 
          WHERE orders.customer_id = ?"; 
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
    <link rel="stylesheet" href="../../css/header-footer-sidebar.css">
    <link rel="stylesheet" href="../../css/user-panel.css">
    <link rel="stylesheet" href="../../css/position.css">
</head>
<body>

    <?php $IPATH = "../../global-assets/"; include($IPATH."header.html"); ?>

    <?php $IPATH = "../../global-assets/"; include($IPATH."sidebar.html"); ?>

    <div class="container">

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
                    <td><?php echo date('Y-m-d', strtotime($row['order_date'])); ?></td> 
                    <td><?php echo number_format($row['total_amount'], 2); ?> LKR</td> 
                    <td><?php echo $row['status']; ?></td> 
                    <td>
                        <a href="view-order.php?order_id=<?php echo $row['order_id']; ?>"><button type="button">VIEW</button></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="createOrder">
        <a href="create_order_customer.php"><button type="button">Create Order</button></a>
    </div>
</div>
    <?php $IPATH = "../../global-assets/"; include($IPATH."footer.html"); ?>

</body>
</html>