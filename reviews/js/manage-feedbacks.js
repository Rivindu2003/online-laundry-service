function editFeedback(id) {
    // Redirect to the edit feedback page with the feedback ID
    window.location.href = `edit_feedback.php?id=${id}`;
}


function deleteFeedback(id) {
    if (confirm('Are you sure you want to delete this feedback?')) {
        // Perform an AJAX request to delete the feedback
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_feedback.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert('Feedback deleted successfully');
                window.location.reload(); // Reload the page to see the changes
            } else {
                alert('Error deleting feedback');
            }
        };
        xhr.send(`id=${id}`);
    }
}