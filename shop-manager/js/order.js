document.addEventListener('DOMContentLoaded', function() {
  // Show the Create Order modal
  document.querySelector('.quick-actions button:nth-child(1)').addEventListener('click', function() {
      document.getElementById('create-order-modal').style.display = 'block';
  });

  // Handle form submission
  document.getElementById('orderForm').addEventListener('submit', function(e) {
      e.preventDefault(); // Prevent default form submission

      const formData = new FormData(this);

      fetch('http://localhost/iwt-project/online-laundry-service/shop-manager/create_order.php', {
          method: 'POST',
          body: formData
      })
      
      .then(response => response.json())
      .then(data => {
          alert(data.message); // Show success or error message
          document.getElementById('create-order-modal').style.display = 'none'; // Hide the modal
          this.reset(); // Reset the form
      })
      .catch(error => console.error('Error:', error));
  });
});