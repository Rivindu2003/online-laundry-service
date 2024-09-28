<?php
$servername = "localhost";
$username = "rivindu"; // Replace with your database username
$password = "Rivindu@1234"; // Replace with your database password
$dbname = "shop_management";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from POST request
$serviceName = $_POST['serviceName'];
$description = $_POST['description'];
$servicePrice = $_POST['servicePrice'];

// Validate inputs
if (empty($serviceName) || empty($servicePrice)) {
    echo json_encode(["success" => false, "message" => "Service name and price are required"]);
    exit;
}

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO services (service_name, description, price) VALUES (?, ?, ?)");
$stmt->bind_param("ssd", $serviceName, $description, $servicePrice); // "ssd" - s for string, d for double (price)

// Execute the query and handle success/failure
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Service created successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
