<?php

include '../../global-assets/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $serviceName = $_POST['serviceName'];
    $description = $_POST['description'];
    $servicePrice = $_POST['servicePrice'];

        if (!empty($serviceName) && !empty($servicePrice)) {
                $stmt = $connection->prepare("INSERT INTO services (service_name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $serviceName, $description, $servicePrice); 
                if ($stmt->execute()) {
            $message = "Service created successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

                $stmt->close();
    } else {
        $message = "Please fill in all required fields.";
    }

        $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Service</title>
    <link rel="stylesheet" href="../../css/admin-sidebar.css">
    <link rel="stylesheet" href="../../css/create-service.css">
    <script src="../../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
</head>
<body>
<?php $IPATH = "../../global-assets/"; include($IPATH."admin-sidebar.html"); ?>

            <div id="create-service">
                <h2>Create New Service</h2>
                <form id="serviceForm" action="" method="POST">
                    <label for="servicename">New Service Name:</label>
                    <input type="text" id="serviceName" name="serviceName" required><br>
                    
                    <label for="description">Description About the Service:</label>
                    <textarea id="description" name="description" rows="4" cols="50"></textarea><br>
            
                    <label for="serviceprice">Service Price:</label>
                    <input type="number" id="servicePrice" name="servicePrice" step="0.01" min="0.01" required><br>
            
                    <button type="submit">Create New Service</button>
                    <button id="reset-button" type="button">Reset & Return</button>
                </form>
            </div>
<?php
        if ($message) {
                        echo "<script>
                    window.onload = function() {
                        swal({
                            title: 'Success!',
                            text: '$message',
                            icon: 'success',
                            button: 'OK',
                        }).then(() => {
                            window.location.href = '../../shop-manager.php';                         });
                    };
                </script>";
        }
?>
        <script src="../../js/create-service.js"></script>
</body>
</html>