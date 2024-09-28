<?php
// Database configuration
$host = 'localhost'; // Change if needed
$db = 'shop_management'; // Replace with your database name
$user = 'rivindu'; // Replace with your database username
$pass = 'Rivindu@1234'; // Replace with your database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

$total = 0;

if ($result) {
    while ($order = mysqli_fetch_assoc($result)) {
        // Determine the button class based on the status
        $buttonClass = '';
        switch ($order['status']) {
            case 'Completed':
                $buttonClass = 'btn-success';
                break;
            case 'Pending':
                $buttonClass = 'btn-warning';
                break;
            case 'Canceled':
                $buttonClass = 'btn-danger';
                break;
            case 'Shipped':
                $buttonClass = 'btn-info';
                break;
            default:
                $buttonClass = 'btn-secondary'; // Fallback for unknown status
        }

        $total  = $order['quantity'] * $order['price'];  // Add to the overall total

        // Output the table row with the button
        echo '<tr>
                <td>#000' . htmlspecialchars($order['id']) . '</td>
                <td>' . htmlspecialchars($order['customer_name']) . '</td>
                <td><button class="btn ' . $buttonClass . '">' . htmlspecialchars($order['status']) . '</button></td>
                <td>' . htmlspecialchars($order['order_date']) . '</td>
                <td>' . htmlspecialchars(number_format($total, 2)) . '</td>
                <td>
                    <a href="#" class="btn btn-light eye-icon" data-id="' . $order['id'] . '">
                <i class="fas fa-eye"></i>
            </a>
                </td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="4">No orders found.</td></tr>';
}

// Close the database connection if applicable
$conn->close();
?>
