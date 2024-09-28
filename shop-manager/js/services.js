document.addEventListener('DOMContentLoaded', function () {
    // Fetch services and populate the dropdown
    fetchServices();

    // Get references to the elements
    const productName = document.getElementById('productName');
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price');
    const totalPriceElement = document.getElementById('totalPrice');

    let services = {};  // Store service details here

    // Function to fetch services from the database
    function fetchServices() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_services.php', true);

        xhr.onload = function () {
            if (this.status === 200) {
                services = JSON.parse(this.responseText);

                let options = '<option value="" disabled selected>Select a service</option>';
                services.forEach(service => {
                    options += `<option value="${service.service_id}" data-price="${service.price}">${service.service_name}</option>`;
                });
                productName.innerHTML = options;
            }
        };

        xhr.send();
    }

    // Event listener to update price when a service is selected
    productName.addEventListener('change', function () {
        const selectedServiceId = this.value;
        const selectedService = services.find(service => service.service_id == selectedServiceId);

        if (selectedService) {
            priceInput.value = selectedService.price;
            updateTotalPrice();
        }
    });

    // Event listener to update total price when quantity is entered
    quantityInput.addEventListener('input', updateTotalPrice);

    // Function to update total price
    function updateTotalPrice() {
        const quantity = parseInt(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = quantity * price;
        totalPriceElement.textContent = `Total Price: LKR ${total.toFixed(2)}`;
    }
});