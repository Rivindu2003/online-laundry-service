document.addEventListener('DOMContentLoaded', function () {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const reviewsDiv = document.getElementById('reviews');
    
    loadMoreBtn.addEventListener('click', function () {
        
        const lastReviewId = this.getAttribute('data-last-id');
        
        
        fetch('reviews.php?last_review_id=' + lastReviewId)
            .then(response => response.text())
            .then(data => {
                
                reviewsDiv.insertAdjacentHTML('beforeend', data);

                
                const newLastReviewId = document.querySelector('#loadMoreBtn').getAttribute('data-last-id');
                loadMoreBtn.setAttribute('data-last-id', newLastReviewId);
            })
            .catch(error => console.log('Error fetching reviews:', error));
    });
});
