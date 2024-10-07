<?php
session_start();
include '../../global-assets/db.php';

if (!isset($_SESSION['shop_manager_id'])) {
    echo '<h1>Unauthorized Access</h1>';
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}

$message_done = '';
$message_failed = '';

$serviceId = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;

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

        $updateStmt = $connection->prepare("UPDATE services SET service_name = ?, description = ?, price = ? WHERE service_id = ?");
    $updateStmt->bind_param("ssdi", $serviceName, $description, $price, $updateId);

        if ($updateStmt->execute()) {
        $message_done = "Service edit successful";
    } else {
        $message_failed = "Service edit failed";
    }

    $updateStmt->close();
}

$stmt = $connection->prepare("SELECT service_id, service_name, description, price FROM services WHERE service_id = ?");
$stmt->bind_param("i", $serviceId);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc(); $stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link rel="stylesheet" href="../../css/edit-service.css">
    <link rel="stylesheet" href="../../css/admin-sidebar.css">
    <script src="../../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
</head>
<body>
    <?php $IPATH = "../../global-assets/"; include($IPATH."admin-sidebar.html"); ?>
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
                <form method="POST" action="" class="delete-form" style="display:inline;" id="delete-form-<?php echo $service['service_id']; ?>">
                    <input type="hidden" name="delete_service_id" value="<?php echo $service['service_id']; ?>">
                    <button type="button" class="delete-btn" onclick="confirmDeletion(<?php echo $service['service_id']; ?>)">Delete Service</button>
                </form>
            </div>
        <?php else: ?>
            <p>No service found with the specified ID.</p>
        <?php endif; ?>
    </div>

    <?php
    if($message_done){
        echo "<script>
            swal({
                position: \"top-end\",
                icon: \"success\",
                title: \"Your work has been saved\",
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    } elseif ($message_failed){
        echo "<script>
            swal({
                position: \"top-end\",
                icon: \"error\",
                title: \"Your work has been saved\",
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    }
    ?>

    <script>
        function confirmDeletion(serviceId) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this service!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                                        document.getElementById('delete-form-' + serviceId).submit();
                } else {
                    swal("Your service is safe!");
                }
            });
        }
    </script>

    <script src="../../js/edit-service.js"></script>
</body>
</html>
