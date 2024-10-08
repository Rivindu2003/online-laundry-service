<?php 
    session_start();

    
    include '../global-assets/db.php';

    $sql = "SELECT review_id, user_name, review, review_date FROM reviews ORDER BY review_date DESC";
    $result = $connection->query($sql);

    $reviews = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    }
    
    $connection->close();

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonial</title>
    <link rel="stylesheet" href="styles/body.css"/>
    <script src="../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
    
    <script>
        
        const loggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
        const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
    </script>
    <link rel="stylesheet" href="../css/home-footer-header.css">
</head>
<body>
    <?php $IPATH = "../global-assets/"; include($IPATH."header-home.html"); ?>

    <section id="testimonials">
        <div class="testimonial-heading">
            <h1>What Our Clients Say</h1>
        </div>
        <div class="testimonial-box-container" id="review-container">
        <?php 
        
        if (!empty($reviews)) {
            foreach ($reviews as $review) { 
        ?>
                <div class="testimonial-box">
                    <div class="box-top">
                        <div class="profile">
                            <div class="profile-img">
                                <img src="images/profile-user.png" alt="Profile Image"/>
                            </div>
                            <div class="name-user">
                                <strong><?php echo htmlspecialchars($review['user_name']); ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="client-comment">
                        <p><?php echo htmlspecialchars($review['review']); ?></p>
                    </div>
                    <div class="review-date"><?php echo htmlspecialchars($review['review_date']); ?></div>
                </div>
        <?php 
            } 
        } else {
            echo "<p>No reviews available.</p>"; 
        }
        ?>
        </div>
        <button id="writeReviewBtn">Write Your Review</button>
    </section>

    <?php $IPATH = "../global-assets/"; include($IPATH."footer-home.html"); ?>

    <script>
        
        document.getElementById('writeReviewBtn').addEventListener('click', () => {
    if (loggedIn) {
        window.location.href = 'write_review.php'; 
    } else {
        swal({
            title: "Error!",
            text: "Please log in to write a review.",
            icon: "error",
            button: "Login"
        }).then((result) => {
            if (result) {
                window.location.href = '../login/login.php'; 
            }
        });
    }
});
        
        document.addEventListener('DOMContentLoaded', fetchReviews);
    </script>
</body>
</html>
