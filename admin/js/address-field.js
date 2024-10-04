document.addEventListener("DOMContentLoaded", function() {
        const accountTypeSelect = document.getElementById('account_type');
        const addressField = document.getElementById('address-field');
        const addressLabel = addressField.previousElementSibling; // To hide the label as well
        
        // Function to toggle the visibility of the address field
        function toggleAddressField() {
            if (accountTypeSelect.value === 'shop_manager') {
                addressField.style.display = 'none';
                addressLabel.style.display = 'none';
                addressField.removeAttribute('required');
            } else {
                addressField.style.display = 'block';
                addressLabel.style.display = 'block';
                addressField.setAttribute('required', 'required');
            }
        }
        
        // Initially call the function to set the correct visibility
        toggleAddressField();
        
        // Add event listener to the dropdown
        accountTypeSelect.addEventListener('change', toggleAddressField);
});

