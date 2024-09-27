<?php
// Database connection (update the credentials as needed)
$host = 'localhost';
$dbname = 'shop_management';
$username = 'rivindu';
$password = 'Rivindu@1234';

error_reporting(0); 
ini_set('display_errors', 0);

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch only the necessary fields
    $stmt = $conn->query("SELECT service_id, service_name, price FROM services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return valid JSON response
    header('Content-Type: application/json');
    echo json_encode($services);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>



