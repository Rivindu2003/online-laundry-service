document.addEventListener('DOMContentLoaded', function() {
    // Show the Create Order modal when clicking the button
    document.querySelector('.quick-actions button:nth-child(1)').addEventListener('click', function() {
        document.getElementById('create-order-modal').style.display = 'block';
    });

    // Update selected service text when a service is chosen
    document.getElementById('productName').addEventListener('change', function() {
        let selectedService = this.options[this.selectedIndex].text;
        document.getElementById('selectedServiceText').textContent = `Selected Service: ${selectedService}`;
    });

    // Handle form submission
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);

        // Get the selected service name from the dropdown
        const productNameElement = document.getElementById('productName');
        const selectedServiceName = productNameElement.options[productNameElement.selectedIndex].text; // Get the service name (visible text)
        
        // Add the selected service name to FormData (so it's submitted to the backend)
        formData.set('productName', selectedServiceName); // Replace 'productName' value with the service name

        // Submit the form data using Fetch API
        fetch('create_order.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message); // Show success or error message
            document.getElementById('create-order-modal').style.display = 'none'; // Hide the modal
            document.getElementById('orderForm').reset(); // Reset the form
            document.getElementById('selectedServiceText').textContent = 'Selected Service: None'; // Reset selected service text
        })
        .catch(error => console.error('Error:', error));
    });
});
