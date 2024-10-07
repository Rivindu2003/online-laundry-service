<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/about-us.css">
        <link rel="stylesheet" href="../css/home-footer-header.css">
    <title>Our Services</title>
</head>
<body>

    <?php $IPATH = "../global-assets/"; include($IPATH."header-home.html"); ?>


    <main>
        <section class="service">
            <div class="service-image">
                <img src="../images/stack-t-shirt-polo.jpg" alt="Washing Service">
            </div>
            <div class="service-info">
                <h2>WASHING</h2>
                <p>Our Washing Service is a convenient and affordable method of outsourcing your laundry to professionals. Each garment that you bring undergoes a thorough cleaning process, with an expert evaluation of the material followed by professional cleaning using advanced cleaning techniques.</p>
            </div>
        </section>

        <section class="service">
            <div class="service-image">
                <img src="../images/man-holding-pile-clean-clothes.jpg" alt="Pressing Service">
            </div>
            <div class="service-info">
                <h2>PRESSING</h2>
                <p>Our steam Pressing Service will give the perfect finished look to your clothes. Not only are your clothes pressed well, using some of the best equipment, but they are also carefully packed using supporting add-ons so that their finished shape and form are retained.</p>
            </div>
        </section>

        <section class="service">
            <div class="service-image">
                <img src="../images/closeup-photo-fashionable-clothes-hangers-shop.jpg" alt="Dry Cleaning Service">
            </div>
            <div class="service-info">
                <h2>DRY CLEANING</h2>
                <p>Our Dry Cleaning Service is designed to take care of delicate fabrics. Using specialized techniques, we ensure that your clothes are cleaned without any damage, providing you with the best care for your precious garments.</p>
            </div>
        </section>

        <div class="main">
            <a href="/login/login.php"><button>Book Your Service</button></a>
        </div>
    </main>

    

    <?php $IPATH = "../global-assets/"; include($IPATH."footer-home.html"); ?>

</body>
</html>
