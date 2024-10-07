document.addEventListener("DOMContentLoaded", function() {
        const accountTypeSelect = document.getElementById('account_type');
        const addressField = document.getElementById('address-field');
        const addressLabel = addressField.previousElementSibling; 
        
        
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
        
        
        toggleAddressField();
        
        
        accountTypeSelect.addEventListener('change', toggleAddressField);
});

