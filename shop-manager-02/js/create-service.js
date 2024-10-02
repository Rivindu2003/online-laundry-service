document.getElementById('reset-button').addEventListener('click', function() {
    // Get the form element
    const form = document.getElementById('serviceForm');
    
    // Reset the form fields
    form.reset();

    window.location.href = "shop-manager.php";
});
