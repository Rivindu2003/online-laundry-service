
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const serviceId = this.getAttribute('data-id');
        const serviceItem = document.getElementById(`service-${serviceId}`);

        
        const serviceName = serviceItem.querySelector('.service-name').innerText;
        const serviceDescription = serviceItem.querySelector('.service-description').innerText;
        const servicePrice = serviceItem.querySelector('.service-price').innerText.replace(' USD', '');

        
        serviceItem.innerHTML = `
            <form method="POST" action="">
                <input type="hidden" name="update_service_id" value="${serviceId}">
                <p>
                    <strong>Service Name:</strong>
                    <input type="text" name="serviceName" value="${serviceName}" required>
                </p>
                <p>
                    <strong>Description:</strong>
                    <textarea name="description" required>${serviceDescription}</textarea>
                </p>
                <p>
                    <strong>Price:</strong>
                    LKR <input type="number" name="servicePrice" value="${servicePrice}" step="0.01" required>
                </p>
                <button type="submit" class="save-btn">Save</button>
                <button type="button" class="cancel-btn">Cancel</button>
            </form>
        `;

        
        serviceItem.querySelector('.cancel-btn').addEventListener('click', function() {
            location.reload(); 
        });
    });
});
