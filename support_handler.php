<?php

include 'global-assets/db.php';

$message_success = '';
$message_failed = '';

if (isset($_POST['submit'])) {
    
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone_number = $_POST['customer_phone_number'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $request_type = $_POST['request_type']; 
    

    
    if ($request_type == 'shop') {
        
        $sql = "INSERT INTO support_shop (customer_name, customer_email, customer_phone_number, subject, message, submission_date, status) 
                VALUES (?, ?, ?, ?, ?, NOW(), 'Open')";
    } else {
        
        $sql = "INSERT INTO support_admin (customer_name, customer_email, customer_phone_number, subject, message, submission_date, status) 
                VALUES (?, ?, ?, ?, ?, NOW(), 'Open')";
    }

    
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssss", $customer_name, $customer_email, $customer_phone_number, $subject, $message);


   

    
    if ($stmt->execute()) {
        $message_success = "Support request submitted successfully!";

        header("Location: contact-us.php?success_message=" . urlencode($success_message));
        exit();
    } 

    
    $stmt->close();
    $connection->close();
}
?>
