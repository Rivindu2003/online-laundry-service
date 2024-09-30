<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the users table
$userQuery = $connection->prepare("SELECT full_name, phone_number FROM users WHERE id = ?");
if ($userQuery === false) {
    die("Error preparing statement: " . $connection->error); // This will print the MySQL error
}
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();

// Fetch the order details from the orders table
$order_id = $_GET['order_id']; // Assume you are passing order ID in the URL
$orderQuery = $connection->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
$orderQuery->bind_param("ii", $order_id, $user_id);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$order = $orderResult->fetch_assoc();

// Fetch service details from service_orders and services tables
$serviceQuery = $connection->prepare("SELECT so.service_id, s.service_name, so.status, so.service_date, s.price
                                      FROM service_orders so 
                                      JOIN services s ON so.service_id = s.service_id
                                      WHERE so.order_id = ?");
$serviceQuery->bind_param("i", $order_id);
$serviceQuery->execute();
$serviceResult = $serviceQuery->get_result();

// Calculate total price
$totalPrice = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="styles/view-order.css">
    <link rel="stylesheet" href="styles/header-footer-sidebar.css">
    <link rel="stylesheet" href="styles/position.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        // Function to toggle edit fields for delivery date and address
        // Function to enable editing of the delivery address
            function enableEdit() {
                document.getElementById('address').disabled = false;
                document.getElementById('save-btn').style.display = 'inline-block';
            }

// Function to handle save and AJAX submission
        function saveDetails() {
            let address = document.getElementById('address').value;

            // Perform an AJAX call to save the updated delivery details
            fetch('update-user-from-view.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: <?php echo $order_id; ?>,
                    address: address
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Details updated successfully!');
                    location.reload(); // Reload to reflect changes
                } else {
                    alert('Error updating details: ' + data.message); // Show error message
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function cancelOrder() {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this! this will delete order",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, cancel & Cancel it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // Make an AJAX call to cancel the order
            fetch('delete_order_customer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: <?php echo $order_id; ?>
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "Canceled!",
                        text: "Your order has been canceled.",
                        icon: "success"
                    }).then(() => {
                        // Redirect to the user panel after cancellation
                        window.location.href = 'user-panel.php';
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: "There was a problem canceling your order.",
                        icon: "error"
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
}

    </script>

</head>
<body>

        <?php $IPATH = "assets/"; include($IPATH."header.html"); ?>

        <?php $IPATH = "assets/"; include($IPATH."sidebar.html"); ?>

    <div class="order-container">
        <!-- Display Order ID and User Information -->
        <h1>Order #BB_0<?php echo $order['order_id']; ?></h1>
        <p><strong>Full Name:</strong> <?php echo $user['full_name']; ?></p>
        <p><strong>Phone Number:</strong> <?php echo $user['phone_number']; ?></p>

        <!-- Display Service Orders in a Table -->
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Status</th>
                    <th>Delivery Date</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($service = $serviceResult->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $service['service_name']; ?></td>
                        <td><?php echo $service['status']; ?></td>
                        <td><?php echo date('Y-m-d', strtotime($service['service_date'])); ?></td>
                        <td>LKR <?php echo number_format($service['price'], 2); ?></td>
                    </tr>
                    <?php $totalPrice += $service['price']; ?>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Total Price -->
        <p><strong>Total Price:</strong> LKR <?php echo number_format($totalPrice, 2); ?></p>

        <!-- Display Address and Delivery Date -->
     <!-- Display Address and Delivery Date -->
        <p><strong>Delivery Address:</strong>
            <textarea id="address" disabled><?php echo $order['delivery_address']; ?></textarea>
        </p>

        <?php if ($order['status'] != 'Shipped') : ?>
            <!-- Edit Button and Save Button (Initially Hidden) -->
            <button onclick="enableEdit()">Edit Delivery Details</button>
            <button id="save-btn" onclick="saveDetails()" style="display: none;">Save</button>
        <?php endif; ?>
        
        <?php if ($order['status'] == 'Pending') : ?>
    <button id="cancel-btn" onclick="cancelOrder()">Cancel Order</button>
    <?php endif; ?>
    </div>

    <?php $IPATH = "assets/"; include($IPATH."footer.html"); ?>
</body>
</html>

<?php
$userQuery->close();
$orderQuery->close();
$serviceQuery->close();
$connection->close();
?>
