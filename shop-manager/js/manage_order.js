document.addEventListener("DOMContentLoaded", function() {
    fetchOrders();
});

function fetchOrders() {
    fetch('get_orders.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('order-body').innerHTML = data;
        })
        .catch(error => console.error('Error fetching orders:', error));
}

document.addEventListener("DOMContentLoaded", function () {
    fetchOrders();

    // Modal Elements
    const modal = document.getElementById("orderModal");
    const closeModal = document.querySelector(".close");

    // Close modal when the user clicks on <span> (x)
    closeModal.onclick = function () {
        modal.style.display = "none";
    };

    // Close modal when clicking outside of the modal
    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
});

// Function to fetch orders
function fetchOrders() {
    fetch('get_orders.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('order-body').innerHTML = data;
            attachEyeIcons();
        })
        .catch(error => console.error('Error fetching orders:', error));
}

// Function to attach click event to eye icons
function attachEyeIcons() {
    const eyeIcons = document.querySelectorAll('.eye-icon');
    eyeIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const orderId = this.dataset.id; // Get order ID from data attribute
            fetchOrderDetails(orderId);
        });
    });
}

// Function to fetch order details
function fetchOrderDetails(orderId) {
    fetch(`get_order_details.php?id=${orderId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('orderId').value = data.id;
            document.getElementById('customerName').value = data.customer_name;
            document.getElementById('orderStatus').value = data.status;
            document.getElementById("orderModal").style.display = "block"; // Show modal
        })
        .catch(error => console.error('Error fetching order details:', error));
}

// Handle form submission
document.getElementById('orderForm').onsubmit = function (event) {
    event.preventDefault(); // Prevent default form submission
    const formData = new FormData(this);

    fetch('update_order.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.text())
        .then(data => {
            alert(data); // Show success message
            fetchOrders(); // Refresh order list
            document.getElementById("orderModal").style.display = "none"; // Close modal
        })
        .catch(error => console.error('Error updating order:', error));
};
