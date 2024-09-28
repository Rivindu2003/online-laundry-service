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
                
                let buttonClass;
                switch (order.status) {
                    case 'Pending':
                        buttonClass = 'btn-primary';
                        break;
                    case 'processing':
                        buttonClass = 'btn-info';
                        break;
                    case 'completed':
                        buttonClass = 'btn-success';
                        break;
                    default:
                        buttonClass = 'btn-secondary'; // Fallback class for any unexpected status
                }
                
                output += `
                    <tr>
                        <td>${order.id}</td>
                        <td>${order.customer_name}</td>
                        <td>${order.product_name}</td>
                        <td><button type="button" class="btn ${buttonClass}">${order.status}</button></td>
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
