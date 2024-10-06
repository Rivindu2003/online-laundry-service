document.getElementById('reset-button').addEventListener('click', function() {
    
    const form = document.getElementById('serviceForm');
    
    
    form.reset();

    window.location.href = "../../shop-manager.php";
});
