<?php
session_start();
include('../../global-assets/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$servicesQuery = $connection->query("SELECT service_id, service_name, price FROM services");
$services = $servicesQuery->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Order</title>
    <link rel="stylesheet" href="../../css/header-footer-sidebar.css">
    <link rel="stylesheet" href="../../css/create_order_customer.css">
    <link rel="stylesheet" href="../../css/position.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php $IPATH = "../../global-assets/"; include($IPATH."header.html"); ?>

    <?php $IPATH = "../../global-assets/"; include($IPATH."sidebar.html"); ?>

    <div class="order-section">
        <h1>Create New Order</h1>
        
        <div class="service-list">
            <h3>Available Services</h3>
            <?php foreach ($services as $service): ?>
            <div class="service-item" data-service-id="<?= $service['service_id']; ?>" data-price="<?= $service['price']; ?>">
                <span><?= $service['service_name']; ?> (LKR <?= $service['price']; ?>)</span>
                <button class="add-btn">+</button>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="selected-services">
            <h3>Selected Services</h3>
            <div id="selected-services-list"></div>
            <div class="total-section">
                Total: LKR <span id="total-amount">0.00</span>
            </div>
        </div>

        <div class="form-inputs">
            <input type="text" id="address" placeholder="Enter Delivery Address" required>
            <input type="datetime-local" id="delivery-date" required>
            <input type="submit" id="submit-order" value="Place Order">
        </div>
    </div>

    <?php $IPATH = "../../global-assets/"; include($IPATH."footer.html"); ?>

    <script>

        let selectedServices = [];
        let totalAmount = 0;

        document.querySelectorAll('.add-btn').forEach(button => {
            button.addEventListener('click', function() {
                const serviceItem = this.closest('.service-item');
                const serviceId = serviceItem.getAttribute('data-service-id');
                const serviceName = serviceItem.querySelector('span').innerText;
                const price = parseFloat(serviceItem.getAttribute('data-price'));

                const existingService = selectedServices.find(service => service.service_id === serviceId);
                if (!existingService) {
                    selectedServices.push({ service_id: serviceId, service_name: serviceName, quantity: 1, price: price });
                    updateSelectedServices();
                }
            });
        });

        function updateSelectedServices() {
            const selectedList = document.getElementById('selected-services-list');
            selectedList.innerHTML = '';
            totalAmount = 0;

            selectedServices.forEach((service, index) => {
                const serviceTotal = service.quantity * service.price;
                totalAmount += serviceTotal;

                selectedList.innerHTML += `
                    <div class="selected-item">
                        <span>${service.service_name}</span>
                        <input type="number" value="${service.quantity}" min="1" class="quantity-input" data-index="${index}">
                        <span>LKR ${serviceTotal.toFixed(2)}</span>
                        <button class="remove-btn" data-index="${index}">Remove</button>
                    </div>
                `;
            });

            document.getElementById('total-amount').innerText = totalAmount.toFixed(2);

          
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('input', function() {
                    const index = this.getAttribute('data-index');
                    selectedServices[index].quantity = parseInt(this.value);
                    updateSelectedServices();
                });
            });

            document.querySelectorAll('.remove-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    selectedServices.splice(index, 1);
                    updateSelectedServices();
                });
            });
        }

        document.getElementById('submit-order').addEventListener('click', function() {
            const address = document.getElementById('address').value;
            const deliveryDate = document.getElementById('delivery-date').value;

            if (!address || !deliveryDate || selectedServices.length === 0) {
                alert('Please complete all fields and select at least one service.');
                return;
            }

            const orderData = {
                selectedServices: selectedServices,
                address: address,
                deliveryDate: deliveryDate,
                total: totalAmount
            };

            fetch('create-order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "Order Created!",
                        text: "Your order is created and we're processing it",
                        icon: "success",
                        showConfirmButton: false,  
                        timer: 3500, 
                        willClose: () => {
                    window.location.href = 'user-panel.php';
                }
            });

                } else {
                    alert('Error placing order. Delivery cannot be in past');
                }
            });
        });
    </script>

        
</body>
</html>