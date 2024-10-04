<?php
session_start();
include '../global-assets/db.php';

try {
    // Fetch services from the database
    $sql = "SELECT service_id, service_name, price FROM services";
    $stmt = $connection->prepare($sql);

    // Execute the statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($service_id, $service_name, $price);

    // Fetch the results
    $services = [];
    while ($stmt->fetch()) {
        $services[] = [
            'service_id' => $service_id,
            'service_name' => $service_name,
            'price' => $price
        ];
    }

    // Close the statement
    $stmt->close();

} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Manager</title>
    <link rel="stylesheet" href="../global-assets/admin-sidebar.css">
    <link rel="stylesheet" href="styles/shop-manager.css">
</head>
<body>
        <?php $IPATH = "../global-assets/"; include($IPATH."admin-sidebar.html"); ?>

            <div class="container">
            <h1>Listed Services</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($services) > 0): ?>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($service['price']); ?> LKR</td>
                                    <td><a href="edit-service.php?service_id=<?php echo $service['service_id']; ?>" class="btn">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No services found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="create-service">
                    <a href="create-service.php" class="create-btn">Create New Service</a>
                </div>
            </div>

</body>
</html>