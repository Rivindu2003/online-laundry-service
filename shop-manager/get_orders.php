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

// Fetch order details from the orders table
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

$total = 0; // Initialize total

// Check if there are results
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $orderTotal = $row['quantity'] * $row['price']; // Calculate order total
        $total += $orderTotal; // Add to the overall total

        echo "<tr>
                <td>" . htmlspecialchars($row['customer_name']) . "</td>
                <td>" . htmlspecialchars($row['status']) . "</td>
                <td>" . htmlspecialchars($row['order_date']) . "</td>
                <td>" . htmlspecialchars(number_format($orderTotal, 2)) . "</td> <!-- Display individual order total -->
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No orders found.</td></tr>";
}

// Close the connection
$conn->close();
?>
