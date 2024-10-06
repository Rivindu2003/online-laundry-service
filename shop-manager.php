<?php
session_start();
include 'global-assets/db.php';

if (!isset($_SESSION['shop_manager_id'])) {
    header('Location: login-shop/login.php');
    exit;
}

try {
    
    $sql = "SELECT service_id, service_name, price FROM services";
    $stmt = $connection->prepare($sql);

    
    $stmt->execute();

    
    $stmt->bind_result($service_id, $service_name, $price);

    
    $services = [];
    while ($stmt->fetch()) {
        $services[] = [
            'service_id' => $service_id,
            'service_name' => $service_name,
            'price' => $price
        ];
    }

    
    $stmt->close();

    $sql = "SELECT ticket_id, customer_name, subject, submission_date, status FROM support_shop";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($ticket_id, $customer_name, $subject, $submission_date, $status);

    
    $support_requests = [];
    while ($stmt->fetch()) {
        $support_requests[] = [
            'ticket_id' => $ticket_id,
            'customer_name' => $customer_name,
            'subject' => $subject,
            'submission_date' => $submission_date,
            'status' => $status
        ];
    }
    $stmt->close();

} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $ticket_id = $_POST['ticket_id'];
    $new_status = $_POST['status'];

    try {
        
        $sql = "UPDATE support_shop SET status = ? WHERE ticket_id = ?";
        $stmt = $connection->prepare($sql);
        
        
        $stmt->bind_param("si", $new_status, $ticket_id);
        
        
        if ($stmt->execute()) {
            
            header('Location: shop-manager.php');
            exit;
        } else {
            
            echo json_encode(['success' => false, 'error' => 'Failed to update status.']);
        }

        
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Manager</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/shop-manager.css">
</head>
<body>
        <?php $IPATH = "global-assets/"; include($IPATH."admin-sidebar.html"); ?>

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
                                    <td><a href="account/shop-manager/edit-service.php?service_id=<?php echo $service['service_id']; ?>" class="btn">Edit</a></td>
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
                    <a href="account/shop-manager/create-service.php" class="create-btn">Create New Service</a>
                </div>
            </div>

            <div class="support-request">
            <h1>Active Support Requests</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($support_requests) > 0): ?>
                            <?php foreach ($support_requests as $request): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($request['ticket_id']); ?></td>
                                    <td><?php echo htmlspecialchars($request['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($request['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($request['submission_date']); ?></td>
                                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                                    <td><span id="support-action" class="request-btn" onclick="openModal(<?php echo htmlspecialchars(json_encode($request)); ?>)">Attend</span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No support requests found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Case Details</h2>
                <div id="modal-body">
                    <p><strong>Request ID:</strong> <?php echo htmlspecialchars($request['ticket_id']); ?></p>
                    <p><strong>Name:</strong> <span id="modal-customer-name"></span></p>
                    <p><strong>Subject:</strong> <span id="modal-subject"></span></p>
                    <p><strong>Submission Date:</strong> <span id="modal-submission-date"></span></p>
                    <form id="status-form" action="" method="POST">
                    <input type="hidden" id="modal-ticket-id" name="ticket_id">
                        <div class="form-group">
                            <label for="status">Change Status:</label>
                            <select id="status" name="status">
                                <option value="Open">Open</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
        
        function openModal(request) {
            document.getElementById("modal-ticket-id").value = request.ticket_id;
            document.getElementById("modal-customer-name").innerText = request.customer_name;
            document.getElementById("modal-subject").innerText = request.subject;
            document.getElementById("modal-submission-date").innerText = request.submission_date;
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        
        window.onclick = function(event) {
            const modal = document.getElementById("myModal");
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>