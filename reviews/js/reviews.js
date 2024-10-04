document.addEventListener('DOMContentLoaded', function () {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const reviewsDiv = document.getElementById('reviews');
    
    loadMoreBtn.addEventListener('click', function () {
        // Get the last review id from the data attribute
        const lastReviewId = this.getAttribute('data-last-id');
        
        // Send an AJAX request to fetch more reviews
        fetch('reviews.php?last_review_id=' + lastReviewId)
            .then(response => response.text())
            .then(data => {
                // Append the new reviews to the review container
                reviewsDiv.insertAdjacentHTML('beforeend', data);

                // Update the last review id based on the new data
                const newLastReviewId = document.querySelector('#loadMoreBtn').getAttribute('data-last-id');
                loadMoreBtn.setAttribute('data-last-id', newLastReviewId);
            })
            .catch(error => console.log('Error fetching reviews:', error));
    });
});
