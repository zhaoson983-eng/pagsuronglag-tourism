document.addEventListener('DOMContentLoaded', function() {
    // Initialize business rating
    const businessRatingContainers = document.querySelectorAll('.business-rating');
    businessRatingContainers.forEach(container => {
        const businessId = container.dataset.businessId;
        const stars = container.querySelectorAll('.star');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                rateBusiness(businessId, rating);
            });
        });
    });

    // Initialize product ratings
    const productRatingContainers = document.querySelectorAll('.product-rating');
    productRatingContainers.forEach(container => {
        const productId = container.dataset.productId;
        const stars = container.querySelectorAll('.star');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                rateProduct(productId, rating);
            });
        });
    });

    // Function to handle business rating
    async function rateBusiness(businessId, rating) {
        try {
            const response = await fetch(`/businesses/${businessId}/rate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ rating })
            });

            const data = await response.json();
            
            if (data.success) {
                // Update the UI with new rating
                const ratingContainer = document.querySelector(`.business-rating[data-business-id="${businessId}"]`);
                if (ratingContainer) {
                    updateRatingDisplay(ratingContainer, data.average_rating, data.total_ratings);
                }
                
                // Show success message
                showNotification('Rating submitted successfully!', 'success');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Failed to submit rating. Please try again.', 'error');
        }
    }

    // Function to handle product rating
    async function rateProduct(productId, rating) {
        try {
            const response = await fetch(`/products/${productId}/rate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ rating })
            });

            const data = await response.json();
            
            if (data.success) {
                // Update the UI with new rating
                const ratingContainer = document.querySelector(`.product-rating[data-product-id="${productId}"]`);
                if (ratingContainer) {
                    updateRatingDisplay(ratingContainer, data.average_rating, data.total_ratings);
                }
                
                // Show success message
                showNotification('Rating submitted successfully!', 'success');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Failed to submit rating. Please try again.', 'error');
        }
    }

    // Update the star display based on the average rating
    function updateRatingDisplay(container, averageRating, totalRatings) {
        const stars = container.querySelectorAll('.star');
        const ratingText = container.querySelector('.rating-text');
        
        // Update active state of stars
        stars.forEach(star => {
            const starRating = parseFloat(star.dataset.rating);
            
            if (starRating <= averageRating) {
                star.classList.remove('far');
                star.classList.add('fas');
            } else if (starRating - 0.5 <= averageRating) {
                star.classList.remove('far');
                star.classList.add('fas', 'fa-star-half-alt');
            } else {
                star.classList.remove('fas', 'fa-star-half-alt');
                star.classList.add('far');
            }
        });
        
        // Update rating text
        if (ratingText) {
            if (totalRatings > 0) {
                ratingText.textContent = `(${parseFloat(averageRating).toFixed(1)})`;
            } else {
                ratingText.textContent = 'No ratings';
            }
        }
    }

    // Show notification to user
    function showNotification(message, type = 'success') {
        // You can implement a toast notification system here
        alert(message);
    }
});
