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
        first_name: document.getElementById('first-name-input').value,
        last_name: document.getElementById('last-name-input').value,
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
        console.log(data);
        if (data.success) {
            
            document.getElementById('first-name').textContent = userData.first_name;
            document.getElementById('last-name').textContent = userData.last_name;
            document.getElementById('email').textContent = userData.email;
            document.getElementById('phone').textContent = userData.phone;
            document.getElementById('address').textContent = userData.address; 

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

document.getElementById('delete-btn').addEventListener('click', function() {
    // SweetAlert confirmation dialog
    swal({
        title: 'Are you sure?',
        text: 'Once deleted, you will not be able to recover this account!',
        icon: 'warning',
        buttons: ['Cancel', 'Yes, delete it!'],
        dangerMode: true, // Makes the confirm button red
    }).then((willDelete) => {
        if (willDelete) {
            // Redirect to delete.php if confirmed
            window.location.href = 'delete_user.php';
        }
    });
});


