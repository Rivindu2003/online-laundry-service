function editFeedback(id) {
    // Redirect to the edit feedback page with the feedback ID
    window.location.href = `edit_feedback.php?id=${id}`;
}

function confirmDeletion() {
    return confirm("Are you sure you want to delete this feedback?");
}