document.addEventListener("DOMContentLoaded", function() {
    fetchRecentOrders();
});

function fetchRecentOrders() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_orders.php', true);

    xhr.onload = function () {
        if (this.status === 200) {
            const orders = JSON.parse(this.responseText);
            let output = '';

            // Loop through the orders and create table rows
            orders.forEach(function(order) {
                let orderTotal = order.quantity * order.price;
                output += `
                    <tr>
                        <td>${order.id}</td>
                        <td>${order.customer_name}</td>
                        <td>${order.product_name}</td>
                        <td>${order.status}</td>
                        <td>${order.order_date}</td>
                        <td>LKR ${orderTotal}</td>
                    </tr>
                `;
            });

            // Ensure the target element exists before setting innerHTML
            const tableBody = document.getElementById('order-table-body');
            if (tableBody) {
                tableBody.innerHTML = output;
            } else {
                console.error('Element with id "order-table-body" not found.');
            }
        }
    }

    xhr.send();
}
