<?php
$servername = "localhost"; // Your database server
$username = "rivindu"; // Your database username
$password = "Rivindu@1234"; // Your database password
$dbname = "shop_management"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO orders (customer_name, product_name, quantity, price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssd", $customer_name, $product_name, $quantity, $price);

// Get data from POST request
$customer_name = $_POST['customerName'];
$product_name = $_POST['productName'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["message" => "Order created successfully."]);
} else {
    echo json_encode(["message" => "Error creating order: " . $stmt->error]);
}

// Close connections
$stmt->close();
$conn->close();
?>
