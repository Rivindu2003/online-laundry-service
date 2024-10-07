<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact US</title>
    <link rel="stylesheet" href="css/home-footer-header.css">
    <link rel="stylesheet" href="/css/contact-us.css">
    <script src="sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
</head>
<body>

    <?php $IPATH = "global-assets/"; include($IPATH."header-home.html"); ?>

    <div class="container">
        <h2>Submit a Support Request</h2>
        <form action="support_handler.php" method="POST">
            <input type="text" name="customer_name" placeholder="Your Name" required>
            <input type="email" name="customer_email" placeholder="Your Email" required>
            <input type="text" name="customer_phone_number" placeholder="Your Phone Number" required>
            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="message" placeholder="Describe your issue" rows="6" required></textarea>
            
            <label for="request_type">Request Type:</label>
            <select name="request_type" id="request_type" required>
                <option value="shop">Order Related</option>
                <option value="admin">Account Related</option>
            </select>

            <input type="submit" name="submit" value="Submit">
        </form>
    </div>

    <?php
    if (isset($_GET['success_message'])) {
        $success_message = $_GET['success_message'];
        echo "
        <script>
            swal({
                title: 'Success!',
                text: '" . $success_message . "',
                icon: 'success',
                button: 'OK',
            });
        </script>
        ";
    }
    ?>

    <?php $IPATH = "global-assets/"; include($IPATH."footer-home.html"); ?>

</body>
</html>
