<?php
// Database connection (update the credentials as needed)
$host = 'localhost';
$dbname = 'shop_management';
$username = 'rivindu';
$password = 'Rivindu@1234';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch services from the table
    $stmt = $conn->prepare("SELECT service_id, service_name, price FROM services");
    $stmt->execute();

    // Fetch all services as an associative array
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return services as a JSON response
    echo json_encode($services);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>
