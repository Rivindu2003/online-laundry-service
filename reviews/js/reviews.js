document.addEventListener("DOMContentLoaded", function() {
    let reviewLimit = 4;

    document.getElementById('loadMoreBtn').addEventListener('click', function() {
        fetch(`load_reviews.php?limit=${reviewLimit}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('reviews').innerHTML += data;
                reviewLimit += 4;
            })
            .catch(error => console.log('Error:', error));
    });
});
