<?php
include '../global-assets/db.php';

// Get the service_id from the URL
$serviceId = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;

// Handle delete request
if (isset($_POST['delete_service_id'])) {
    $deleteId = $_POST['delete_service_id'];
    $deleteStmt = $connection->prepare("DELETE FROM services WHERE service_id = ?");
    $deleteStmt->bind_param("i", $deleteId);
    $deleteStmt->execute();
    $deleteStmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_service_id'])) {
    $updateId = $_POST['update_service_id'];
    $serviceName = $_POST['serviceName'];
    $description = $_POST['description'];
    $price = $_POST['servicePrice'];

    // Prepare and bind the update SQL statement
    $updateStmt = $connection->prepare("UPDATE services SET service_name = ?, description = ?, price = ? WHERE service_id = ?");
    $updateStmt->bind_param("ssdi", $serviceName, $description, $price, $updateId);

    // Execute the query and handle success/failure
    if ($updateStmt->execute()) {
        echo "<script>alert('Service updated successfully.');</script>";
    } else {
        echo "<script>alert('Error: " . $updateStmt->error . "');</script>";
    }

    $updateStmt->close();
}

// Fetch specific service if service_id is provided
$stmt = $connection->prepare("SELECT service_id, service_name, description, price FROM services WHERE service_id = ?");
$stmt->bind_param("i", $serviceId);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc(); // Fetch only one service
$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link rel="stylesheet" href="styles/edit-service.css">
    <link rel="stylesheet" href="../global-assets/admin-sidebar.css">
</head>
<body>
    <?php $IPATH = "../global-assets/"; include($IPATH."admin-sidebar.html"); ?>
    <div id="edit-service">
        <h2>Edit Service</h2>
        <?php if ($service): ?>
            <div class="service-item" id="service-<?php echo $service['service_id']; ?>">
                <p>
                    <strong>Service Name:</strong> <span class="service-name"><?php echo htmlspecialchars($service['service_name']); ?></span>
                </p>
                <p>
                    <strong>Description:</strong> <span class="service-description"><?php echo htmlspecialchars($service['description']); ?></span>
                </p>
                <p>
                    <strong>Price: LKR </strong> <span class="service-price"><?php echo htmlspecialchars($service['price']); ?></span>
                </p>
                <button class="edit-btn" data-id="<?php echo $service['service_id']; ?>">Edit</button>
                <form method="POST" action="" class="delete-form" style="display:inline;">
                    <input type="hidden" name="delete_service_id" value="<?php echo $service['service_id']; ?>">
                    <button type="submit" class="delete-btn">Delete Service</button>
                </form>
            </div>
        <?php else: ?>
            <p>No service found with the specified ID.</p>
        <?php endif; ?>
    </div>

    <script src="js/edit-service.js"></script>
</body>
</html>
