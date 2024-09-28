document.addEventListener('DOMContentLoaded', function() {
    // Show the Create service modal when clicking the button
    document.querySelector('.quick-actions button:nth-child(2)').addEventListener('click', function() {
        document.getElementById('create-service-modal').style.display = 'block';
    })})

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('serviceForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission
    
            // Create FormData object to capture form inputs
            const formData = new FormData(this);
    
            // Send the data using Fetch API
            fetch('create_service.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Expecting JSON response from server
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "Service Created!",
                        text: data.message,
                        icon: "success"
                    });
                    document.getElementById('create-service-modal').style.display = 'none'; // Hide the modal
                    this.reset(); // Reset the form fields
                } else {
                    // Display error message
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    