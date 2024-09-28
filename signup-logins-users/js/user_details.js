document.getElementById('edit-btn').addEventListener('click', function() {
    document.querySelectorAll('.editable-text').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.editable-input').forEach(el => {
        el.style.display = 'block';
        el.value = document.getElementById(el.id.replace('-input', '')).textContent.trim();
    });

    document.getElementById('edit-btn').style.display = 'none';
    document.getElementById('save-btn').style.display = 'inline-block';
});

document.getElementById('user-form').addEventListener('submit', function(e) {
    e.preventDefault();

    // Gather data from input fields
    const userData = {
        name: document.getElementById('name-input').value,
        email: document.getElementById('email-input').value,
        phone: document.getElementById('phone-input').value,
        address: document.getElementById('address-input').value // Include address
    };

    // Send AJAX request to the backend
    fetch('update_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(userData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the text fields with the new values
            document.getElementById('name').textContent = userData.name;
            document.getElementById('email').textContent = userData.email;
            document.getElementById('phone').textContent = userData.phone;
            document.getElementById('address').textContent = userData.address; // Update address

            // Hide input fields and show the updated text
            document.querySelectorAll('.editable-input').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.editable-text').forEach(el => el.style.display = 'block');

            document.getElementById('edit-btn').style.display = 'inline-block';
            document.getElementById('save-btn').style.display = 'none';
        } else {
            alert('Failed to update user details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating');
    });
});

document.getElementById('delete-btn').addEventListener('click', function() {
    // Show confirmation dialog
    if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        // Send AJAX request to delete user account
        fetch('delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Your account has been deleted successfully.');
                window.location.href = 'login.php'; // Redirect to login page after deletion
            } else {
                alert('Failed to delete account: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the account.');
        });
    }
});

document.getElementById('edit-btn').addEventListener('click', function() {
    // Hide the Delete button when editing
    document.getElementById('delete-btn').style.display = 'none';
    
    document.querySelectorAll('.editable-text').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.editable-input').forEach(el => {
        el.style.display = 'block';
        el.value = document.getElementById(el.id.replace('-input', '')).textContent.trim();
    });

    document.getElementById('edit-btn').style.display = 'none';
    document.getElementById('save-btn').style.display = 'inline-block';
});


