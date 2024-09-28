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