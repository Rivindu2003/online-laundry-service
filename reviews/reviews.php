<?php 
    session_start();
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
        // Check if the user is logged in via PHP session
        const loggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
        const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
    </script>
    <link rel="stylesheet" href="../signup-logins-users/styles/header-footer-sidebar.css">
</head>
<body>
    <!--Testimonials Section-->
    <?php $IPATH = "../signup-logins-users/assets/"; include($IPATH."header.html"); ?>

    <section id="testimonials">
        <div class="testimonial-heading">
            <h1>What Our Clients Say</h1>
        </div>
        <div class="testimonial-box-container" id="review-container">
            <!-- Reviews will be injected here dynamically -->
        </div>
        <button id="writeReviewBtn">Write Your Review</button>
    </section>

    <?php $IPATH = "../signup-logins-users/assets/"; include($IPATH."footer.html"); ?>

    <script>
        // Fetch reviews from the database using AJAX
        function fetchReviews() {
            fetch('fetch_reviews.php') // Replace with your PHP script
                .then(response => response.json())
                .then(data => {
                    const reviewContainer = document.getElementById('review-container');
                    reviewContainer.innerHTML = ''; // Clear previous reviews

                    data.forEach(review => {
                        reviewContainer.innerHTML += `
                            <div class="testimonial-box">
                                <div class="box-top">
                                    <div class="profile">
                                        <div class="profile-img">
                                            <img src="images/profile-user.png" alt="Profile Image"/>
                                        </div>
                                        <div class="name-user">
                                            <strong>${review.user_name}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="client-comment">
                                    <p>${review.review}</p>
                                </div>
                                <div class="review-date">${review.review_date}</div>
                            </div>
                        `;
                    });
                })
                .catch(error => console.error('Error fetching reviews:', error));
        }

        // Event listener for the Write Your Review button
        document.getElementById('writeReviewBtn').addEventListener('click', () => {
            if (loggedIn) {
                window.location.href = 'write_review.php'; // Redirect to the review writing page
            } else {
                swal({
                    title: "Error!",
                    text: "Please log in to write a review.",
                    type: "error",
                    confirmButtonText: "Login",
                    closeOnConfirm: false
                }, function() {
                    window.location.href = '../signup-logins-users/login.php'; // Redirect to login page
                });
            }
        });

        // Fetch reviews on page load
        document.addEventListener('DOMContentLoaded', fetchReviews);
    </script>
</body>
</html>
