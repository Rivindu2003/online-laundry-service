<?php
session_start();
include('../../global-assets/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];


$userQuery = $connection->prepare("SELECT first_name, last_name, phone_number FROM users WHERE id = ?");
if ($userQuery === false) {
    die("Error preparing statement: " . $connection->error); 
}

$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();


$order_id = $_GET['order_id']; 
$orderQuery = $connection->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
$orderQuery->bind_param("ii", $order_id, $user_id);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$order = $orderResult->fetch_assoc();


$serviceQuery = $connection->prepare("SELECT so.service_id, s.service_name, so.status, so.service_date, s.price
                                      FROM service_orders so 
                                      JOIN services s ON so.service_id = s.service_id
                                      WHERE so.order_id = ?");
$serviceQuery->bind_param("i", $order_id);
$serviceQuery->execute();
$serviceResult = $serviceQuery->get_result();


$totalPrice = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="../../css/view-order.css">
    <link rel="stylesheet" href="../../css/header-footer-sidebar.css">
    <link rel="stylesheet" href="../../css/position.css">
    <script src="../../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
    <script>

        
        
            function enableEdit() {
                document.getElementById('address').disabled = false;
                document.getElementById('save-btn').style.display = 'inline-block';
            }


        function saveDetails() {
            let address = document.getElementById('address').value;

            
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
                    location.reload(); 
                } else {
                    alert('Error updating details: ' + data.message); 
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function cancelOrder() {
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this! This will delete the order.",
        icon: "warning",
        buttons: {
            cancel: {
                text: "No, keep it",
                value: null,
                visible: true,
                className: "btn-danger",
                closeModal: true,
            },
            confirm: {
                text: "Yes, cancel it!",
                value: true,
                visible: true,
                className: "btn-primary",
                closeModal: false
            }
        }
    }).then((willDelete) => {
        if (willDelete) {
            
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
                    swal("Canceled!", "Your order has been canceled.", "success")
                    .then(() => {
                        
                        window.location.href = 'user-panel.php';
                    });
                } else {
                    swal("Error!", "There was a problem canceling your order.", "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal("Error!", "An unexpected error occurred.", "error");
            });
        }
    });
}

    </script>

</head>
<body>

        <?php $IPATH = "../../global-assets/"; include($IPATH."header.html"); ?>

        <?php $IPATH = "../../global-assets/"; include($IPATH."sidebar.html"); ?>

    <div class="order-container">
        <!-- Display Order ID and User Information -->
        <h1>Order #BB_0<?php echo $order['order_id']; ?></h1>
        <p><strong>Full Name:</strong> <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
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

    <?php $IPATH = "../../global-assets/"; include($IPATH."footer.html"); ?>
</body>
</html>

<?php
$userQuery->close();
$orderQuery->close();
$serviceQuery->close();
$connection->close();
?>
