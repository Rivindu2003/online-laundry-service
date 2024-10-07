<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home - Bubble Shine</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/home-footer-header.css">
</head>

<body>
    <?php $IPATH = "global-assets/"; include($IPATH."header-home.html"); ?>

    <section class="home">
        <div class="home-content">
            <h1>Welcome to Bubble Shine</h1>
            <h3>Your Premier Laundry Service</h3>
            <p>Experience the magic of effortless laundry with Bubble Shine! We take the hassle out of washing, drying, and folding, delivering pristine clothes right to your doorstep. Enjoy our express services, eco-friendly practices, and exceptional customer care—all designed to make your life easier and your clothes shine brighter.</p>
            <a href="/home/about-us.php" class="button">
                <button type="button">Book Now!</button>
            </a>
        </div>

        <div class="home-image">
            <img src="images/content-image.png" alt="home-image">
        </div>

    </section>

    <div class="feature-heading">
        <h1>WHY CHOOSE US!</h1>
        <p>At Bubble Shine, we understand that your time is valuable. That's why we offer convenient, high-quality laundry services tailored to fit your busy lifestyle.</p>
    </div>

        <div class="features-section">
            <div class="feature">
                <div class="icon">
                    <img src="https://img.icons8.com/ios-filled/50/delivery.png" alt="Express Shipping">
                </div>
                <h3>Express Shipping</h3>
                <p>We wash your clothes just within 2 days!</p>
            </div>
        
            <div class="feature">
                <div class="icon">
                    <img src="https://img.icons8.com/ios/50/online-support.png" alt="icon">
                </div>
                <h3>We're Here for You!</h3>
                <p>Our dedicated customer support team is ready to assit you with any questions</p>
            </div>
        
            <div class="feature">
                <div class="icon">
                    <img src="https://img.icons8.com/ios-filled/50/cardboard-box.png" alt="icon">
                </div>
                <h3>Secure Packaging</h3>
                <p>We will take care your clothes. That's our promise</p>
            </div>
        
            <div class="feature">
                <div class="icon">
                    <img src="https://img.icons8.com/ios-filled/50/card-in-use.png" alt="Payment Gateway Icon">
                </div>
                <h3>Secure Payments</h3>
                <p>Experience Peace of Mind with Our Secure Online Payment Gateway</p>
            </div>
        </div>
        
        <?php $IPATH = "global-assets/"; include($IPATH."footer-home.html"); ?>
</body>
</html>