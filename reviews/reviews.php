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

<!--doctype html-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonial HTML</title>
    <!--Stylesheet-->
    <link rel="stylesheet" href="styles/body.css"/>
    <!--Fav-icon-->
    <link rel="shortcut icon" href="images/fav-icon.png"/>
    <!--Poppins Font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;700&display=swap" rel="stylesheet">
    <!--Font Awesome-->
    <script src="https://kit.fontawesome.com/c8e4d183c2.js" crossorigin="anonymous"></script>
    <!-- SweetAlert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    
    <script>
        
        const loggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
        const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
    </script>
    <link rel="stylesheet" href="../css/home-footer-header.css">
</head>
<body>
    <!--Testimonials Section-->
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
                    type: "error",
                    confirmButtonText: "Login",
                    closeOnConfirm: false
                }, function() {
                    window.location.href = '../login/login.php'; 
                });
            }
        });

        
        document.addEventListener('DOMContentLoaded', fetchReviews);
    </script>
</body>
</html>
