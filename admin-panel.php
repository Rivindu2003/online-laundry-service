<?php 

session_start();

if (!isset($_SESSION['ses_admin_id'])) {
    header("Location: login-admin/login-admin.php");
    exit();
}

include 'global-assets/db.php';

$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = $connection->query($user_query);
$user_data = $user_result->fetch_assoc();
$total_users = $user_data['total_users'];

$manager_query = "SELECT COUNT(*) AS total_managers FROM shop_managers";
$manager_result = $connection->query($manager_query);
$manager_data = $manager_result->fetch_assoc();
$total_managers = $manager_data['total_managers'];

$recent_orders_query = "SELECT order_id, total_amount, order_date, status FROM orders ORDER BY order_date DESC LIMIT 5";
$recent_orders_result = mysqli_query($connection, $recent_orders_query);

$sql = "SELECT ticket_id, customer_name, subject, submission_date, customer_phone_number, status, message FROM support_admin";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($ticket_id, $customer_name, $subject, $submission_date,$customer_phone, $status, $message, );

    $support_requests = [];
    while ($stmt->fetch()) {
        $support_requests[] = [
            'ticket_id' => $ticket_id,
            'customer_name' => $customer_name,
            'subject' => $subject,
            'submission_date' => $submission_date,
            'customer_phone_number' => $customer_phone,
            'status' => $status,
            'message' => $message
        ];
    }

$stmt->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $ticket_id = $_POST['ticket_id'];
    $new_status = $_POST['status'];

    try {
        
        $sql = "UPDATE support_admin SET status = ? WHERE ticket_id = ?";
        $stmt = $connection->prepare($sql);
        
        
        $stmt->bind_param("si", $new_status, $ticket_id);
        
        
        if ($stmt->execute()) {
            
            header('Location: admin-panel.php');
            exit;
        } else {
            
            echo json_encode(['success' => false, 'error' => 'Failed to update status.']);
        }

        
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/admin-panel.css">
</head>
<body>
        <?php $IPATH = "global-assets/"; include($IPATH."administrator-sidebar.html"); ?>

        <div class="dashboard-container">
      
        <section class="user-stats">
            <h2>User Statistics</h2>
            <div class="stats">
                <div class="total-users">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="total-managers">
                    <h3>Total Shop Managers</h3>
                    <p><?php echo $total_managers; ?></p>
                </div>
            </div>
            
            <div class="user-buttons">
                <a href="account/admin/add-user.php" class="btn">Add New User</a>
                <a href="#" class="btn">Manage Users</a>
            </div>
        </section>

        <section class="recent-orders">
            <h2>Recent Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($recent_orders_result)): ?>
                        <tr>
                            <td><?php echo 'BB_' . $order['order_id']; ?></td>
                            <td><?php echo 'LKR ' . number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo date('F j, Y', strtotime($order['order_date'])); ?></td>
                            <td><?php echo $order['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <div class="support-request">
            <h2 style="margin-left: 300px;">Active Support Requests</h2>
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
                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h2>Case Details</h2>
                        <div id="modal-body">
                            <p><strong>Request ID:</strong> <?php echo htmlspecialchars($request['ticket_id']); ?></p>
                            <p><strong>Name:</strong> <span id="modal-customer-name"></span></p>
                            <p><strong>Subject:</strong> <span id="modal-subject"></span></p>
                            <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($request['customer_phone_number']); ?></p>
                            <p><strong>Message:</strong> <span id="modal-message"></span></p>
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

            </div>
            
    </div>

<script>

function openModal(request) {
            document.getElementById("modal-ticket-id").value = request.ticket_id;
            document.getElementById("modal-customer-name").innerText = request.customer_name;
            document.getElementById("modal-subject").innerText = request.subject;
            document.getElementById("modal-submission-date").innerText = request.submission_date;
            document.getElementById("modal-message").innerText = request.message;
            document.getElementById("myModal").style.display = "block";
}

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

            // Outside window close 
        window.onclick = function(event) {
            const modal = document.getElementById("myModal");
            if (event.target === modal) {
                closeModal();
            }
        }

    function showPopup() {
        document.getElementById('userPopup').style.display = 'block';
    }

    
    function hidePopup() {
        document.getElementById('userPopup').style.display = 'none';
    }
</script>

<div id="userPopup" class="popup" style="display: none;">
    <div class="popup-content">
        <span class="close" onclick="hidePopup()">&times;</span>
        <h3>Manage Users</h3>
        <button onclick="window.location.href='account/admin/manage-shop-managers.php'" class="popup-button">Shop Managers</button>
        <button onclick="window.location.href='account/admin/manage-regular-users.php'" class="popup-button">Customers</button>
    </div>
</div>

<script>
    
    document.querySelector('.user-buttons .btn:nth-child(2)').addEventListener('click', function(event) {
        event.preventDefault(); 
        showPopup(); 
    });

</script>
</body>
</html>