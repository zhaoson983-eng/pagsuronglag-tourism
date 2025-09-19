@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Category Cards Section -->
    <div class="px-4 py-6 bg-white">
        <!-- Mobile: 4 columns, Desktop: 4 columns centered -->
        <div class="grid grid-cols-4 gap-4 md:max-w-2xl md:mx-auto">
            <!-- Products & Shops -->
            <a href="{{ route('customer.products') }}" class="flex flex-col items-center p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-500 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-shopping-basket text-white text-lg md:text-xl"></i>
                </div>
                <span class="text-xs md:text-sm font-medium text-gray-700 text-center leading-tight">Products & Shops</span>
            </a>

            <!-- Hotels -->
            <a href="{{ route('customer.hotels') }}" class="flex flex-col items-center p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-500 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-hotel text-white text-lg md:text-xl"></i>
                </div>
                <span class="text-xs md:text-sm font-medium text-gray-700 text-center leading-tight">Hotels</span>
            </a>

            <!-- Resorts -->
            <a href="{{ route('customer.resorts') }}" class="flex flex-col items-center p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-500 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-umbrella-beach text-white text-lg md:text-xl"></i>
                </div>
                <span class="text-xs md:text-sm font-medium text-gray-700 text-center leading-tight">Resorts</span>
            </a>

            <!-- Attractions -->
            <a href="{{ route('customer.attractions') }}" class="flex flex-col items-center p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-500 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-map-marked-alt text-white text-lg md:text-xl"></i>
                </div>
                <span class="text-xs md:text-sm font-medium text-gray-700 text-center leading-tight">Attractions</span>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mt-6 max-w-2xl mx-auto">
            <form action="{{ route('customer.search') }}" method="GET" class="flex gap-2">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search for products, businesses, or tourist spots..."
                       class="flex-1 px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
    </div>

    <!-- Feed Section -->
    <div class="px-4 py-2 max-w-2xl mx-auto">
        <div id="feed-container" class="space-y-4">
            <!-- Feed items will be loaded here -->
        </div>
        
        <!-- Loading indicator -->
        <div id="loading" class="text-center py-8 hidden">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <p class="mt-2 text-gray-500">Loading more...</p>
        </div>
        
        <!-- No content message -->
        <div id="no-content" class="text-center py-12 hidden">
            <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-500 text-lg">No content available at the moment</p>
            <p class="text-gray-400 text-sm">Check back later for new businesses and attractions!</p>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap');

    .font-serif {
        font-family: 'Playfair Display', serif;
    }

    /* Ensure smooth transitions */
    .transition-all {
        transition: all 0.3s ease;
    }
    
    /* Feed item animations */
    .feed-item {
        animation: fadeInUp 0.5s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@push('scripts')
<script>
let currentPage = 1;
let loading = false;
let hasMore = true;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard loaded, starting feed load...');
    loadFeedData();
    
    // Infinite scroll
    window.addEventListener('scroll', function() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
            if (!loading && hasMore) {
                loadMoreFeed();
            }
        }
    });
    
    // Pull to refresh simulation
    let startY = 0;
    let isRefreshing = false;
    
    document.addEventListener('touchstart', function(e) {
        startY = e.touches[0].clientY;
    });
    
    document.addEventListener('touchmove', function(e) {
        if (window.scrollY === 0 && e.touches[0].clientY > startY + 100 && !isRefreshing) {
            isRefreshing = true;
            refreshFeed();
        }
    });
});

function loadFeedData() {
    if (loading) return;
    
    loading = true;
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('no-content').classList.add('hidden');
    
    fetch(`/customer/feed?page=${currentPage}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Feed data received:', data);
            const container = document.getElementById('feed-container');
            
            if (currentPage === 1) {
                container.innerHTML = '';
            }
            
            if (data.items && data.items.length > 0) {
                data.items.forEach(item => {
                    container.appendChild(createFeedItem(item));
                });
                hasMore = data.hasMore;
            } else if (currentPage === 1) {
                // Show no content message only on first page
                document.getElementById('no-content').classList.remove('hidden');
                hasMore = false;
            }
            
            loading = false;
            document.getElementById('loading').classList.add('hidden');
        })
        .catch(error => {
            console.error('Error loading feed:', error);
            loading = false;
            document.getElementById('loading').classList.add('hidden');
            
            if (currentPage === 1) {
                const noContentDiv = document.getElementById('no-content');
                noContentDiv.classList.remove('hidden');
                // Update the message to show the error
                noContentDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                    <p class="text-red-500 text-lg">Error loading content</p>
                    <p class="text-gray-400 text-sm">Please refresh the page or try again later</p>
                `;
            }
        });
}

function loadMoreFeed() {
    currentPage++;
    loadFeedData();
}

function refreshFeed() {
    currentPage = 1;
    hasMore = true;
    loadFeedData();
}

function createFeedItem(item) {
    const feedItem = document.createElement('div');
    feedItem.className = 'feed-item bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden';
    
    const typeColors = {
        'business': 'bg-blue-500',
        'hotel': 'bg-green-500',
        'resort': 'bg-purple-500',
        'attraction': 'bg-orange-500',
        'product': 'bg-indigo-500'
    };
    
    const typeLabels = {
        'business': 'Shop',
        'hotel': 'Hotel',
        'resort': 'Resort',
        'attraction': 'Attraction',
        'product': 'Product'
    };
    
    const imageSection = item.image ? 
        `<div class="aspect-w-16 aspect-h-9">
            <img src="${item.image}" alt="${item.title}" class="w-full h-64 object-cover">
        </div>` : 
        `<div class="w-full h-64 bg-gray-200 flex items-center justify-center">
            <i class="fas fa-image text-gray-400 text-4xl"></i>
        </div>`;
    
    feedItem.innerHTML = `
        <div class="p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden">
                        ${item.profile_avatar ? 
                            `<img src="${item.profile_avatar}" alt="${item.title}" class="w-full h-full object-cover">` :
                            `<div class="w-full h-full ${typeColors[item.type]} rounded-full flex items-center justify-center">
                                <i class="fas fa-${getTypeIcon(item.type)} text-white text-sm"></i>
                            </div>`
                        }
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">${item.title}</h3>
                        <p class="text-sm text-gray-500">${item.location}</p>
                    </div>
                </div>
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                    ${item.status}
                </span>
            </div>
        </div>
        
        ${imageSection}
        
        <div class="p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button class="flex items-center space-x-1 transition-colors like-btn" 
                            onclick="toggleLike('${item.type}', ${item.id})" 
                            data-liked="${item.user_liked || false}">
                        <i class="${item.user_liked ? 'fas' : 'far'} fa-heart ${item.user_liked ? 'text-red-500' : 'text-gray-400 hover:text-red-500'}"></i>
                        <span class="text-sm like-count">${item.like_count || 0}</span>
                    </button>
                    <button class="flex items-center space-x-1 transition-colors" onclick="showRating('${item.type}', ${item.id})">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="text-sm text-gray-600">${(item.rating || 0).toFixed(1)} (${item.rating_count || 0})</span>
                    </button>
                    <button class="flex items-center space-x-1 text-gray-600 hover:text-blue-600 transition-colors" onclick="showComments('${item.type}', ${item.id})">
                        <i class="fas fa-comment"></i>
                        <span class="text-sm">${item.comment_count || 0}</span>
                    </button>
                </div>
                <a href="${item.url}" class="text-blue-500 hover:text-blue-600 font-medium text-sm">
                    View ${typeLabels[item.type]} →
                </a>
            </div>
        </div>
    `;
    
    return feedItem;
}

function getTypeIcon(type) {
    const icons = {
        'business': 'shopping-basket',
        'hotel': 'hotel',
        'resort': 'umbrella-beach',
        'attraction': 'map-marked-alt',
        'product': 'box'
    };
    return icons[type] || 'store';
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star text-yellow-400"></i>';
        } else if (i - 0.5 <= rating) {
            stars += '<i class="fas fa-star-half-alt text-yellow-400"></i>';
        } else {
            stars += '<i class="far fa-star text-yellow-400"></i>';
        }
    }
    return stars;
}

function toggleLike(type, id) {
 
    let route = '';
    switch(type) {
        case 'business':
            route = `/businesses/${id}/like`;
            break;
        case 'hotel':
            route = `/hotels/${id}/like`;
            break;
        case 'resort':
            route = `/resorts/${id}/like`;
            break;
        case 'attraction':
            route = `/tourist-spots/${id}/like`;
            break;
        case 'product':
            route = `/products/${id}/like`;
            break;
        default:
            console.error('Unknown type:', type);
            return;
    }
    
    fetch(route, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Find the button that was clicked
            const button = document.querySelector(`[onclick*="toggleLike('${type}', ${id})"]`);
            if (button) {
                const countSpan = button.querySelector('.like-count');
                const heartIcon = button.querySelector('i');
                
                // Update like count
                if (countSpan) {
                    countSpan.textContent = data.like_count || data.likes_count || 0;
                }
                
                // Update heart icon and colors based on like status
                if (heartIcon) {
                    if (data.liked) {
                        heartIcon.classList.remove('far', 'text-gray-400');
                        heartIcon.classList.add('fas', 'text-red-500');
                        button.setAttribute('data-liked', 'true');
                    } else {
                        heartIcon.classList.remove('fas', 'text-red-500');
                        heartIcon.classList.add('far', 'text-gray-400');
                        button.setAttribute('data-liked', 'false');
                    }
                }
            }
        }
    })
    .catch(error => {
        console.error('Error toggling like:', error);
    });
}

function showRating(type, id) {
    // Create and show rating modal
    const modal = createRatingModal(type, id);
    document.body.appendChild(modal);
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function createRatingModal(type, id) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.id = `rating-modal-${type}-${id}`;
    
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Rate this ${type}</h3>
                <button onclick="closeRatingModal('${type}', ${id})" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="rating-form-${type}-${id}" onsubmit="submitRating(event, '${type}', ${id})">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                    <div class="flex space-x-1" id="star-rating-${type}-${id}">
                        ${[1,2,3,4,5].map(star => `
                            <button type="button" class="rating-star text-2xl text-gray-300 hover:text-yellow-400 transition-colors" 
                                    data-rating="${star}" onclick="setRating('${type}', ${id}, ${star})">
                                <i class="fas fa-star"></i>
                            </button>
                        `).join('')}
                    </div>
                    <input type="hidden" name="rating" id="rating-input-${type}-${id}" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment (Optional)</label>
                    <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              placeholder="Share your experience..."></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors">
                        Submit Rating
                    </button>
                    <button type="button" onclick="closeRatingModal('${type}', ${id})" 
                            class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    `;
    
    return modal;
}

function setRating(type, id, rating) {
    // Update visual stars
    const stars = document.querySelectorAll(`#star-rating-${type}-${id} .rating-star`);
    stars.forEach((star, index) => {
        const starIcon = star.querySelector('i');
        if (index < rating) {
            starIcon.classList.remove('text-gray-300');
            starIcon.classList.add('text-yellow-400');
        } else {
            starIcon.classList.remove('text-yellow-400');
            starIcon.classList.add('text-gray-300');
        }
    });
    
    // Set hidden input value
    document.getElementById(`rating-input-${type}-${id}`).value = rating;
}

function closeRatingModal(type, id) {
    const modal = document.getElementById(`rating-modal-${type}-${id}`);
    if (modal) {
        modal.remove();
        document.body.classList.remove('overflow-hidden');
    }
}

function submitRating(event, type, id) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Determine the correct route based on type
    let route = '';
    switch(type) {
        case 'business':
            route = `/businesses/${id}/rate`;
            break;
        case 'hotel':
            route = `/hotels/${id}/rate`;
            break;
        case 'resort':
            route = `/resorts/${id}/rate`;
            break;
        case 'attraction':
            route = `/tourist-spots/${id}/rate`;
            break;
        case 'product':
            route = `/products/${id}/rate`;
            break;
        default:
            console.error('Unknown type:', type);
            return;
    }
    
    fetch(route, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeRatingModal(type, id);
            // Update the rating display in the feed item without refreshing entire feed
            updateFeedItemRating(type, id, data.average_rating, data.total_ratings);
        } else {
            alert(data.error || 'Error submitting rating');
        }
    })
    .catch(error => {
        console.error('Error submitting rating:', error);
        alert('Error submitting rating');
    });
}

function showComments(type, id) {
    // Create and show comments modal
    const modal = createCommentsModal(type, id);
    document.body.appendChild(modal);
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Load existing comments
    loadComments(type, id);
}

function createCommentsModal(type, id) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.id = `comments-modal-${type}-${id}`;
    
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4 max-h-[80vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Comments</h3>
                <button onclick="closeCommentsModal('${type}', ${id})" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Comments List -->
            <div class="flex-1 overflow-y-auto mb-4" id="comments-list-${type}-${id}">
                <div class="text-center py-4 text-gray-500">Loading comments...</div>
            </div>
            
            <!-- Add Comment Form -->
            <form id="comment-form-${type}-${id}" onsubmit="submitComment(event, '${type}', ${id})" class="border-t pt-4">
                <div class="flex space-x-3">
                    <textarea name="comment" rows="2" class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" 
                              placeholder="Write a comment..." required></textarea>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    `;
    
    return modal;
}

function closeCommentsModal(type, id) {
    const modal = document.getElementById(`comments-modal-${type}-${id}`);
    if (modal) {
        modal.remove();
        document.body.classList.remove('overflow-hidden');
    }
}

function loadComments(type, id) {
    // Determine the correct route based on type
    let route = '';
    switch(type) {
        case 'business':
            route = `/businesses/${id}/comments`;
            break;
        case 'hotel':
            route = `/hotels/${id}/comments`;
            break;
        case 'resort':
            route = `/resorts/${id}/comments`;
            break;
        case 'attraction':
            route = `/tourist-spots/${id}/comments`;
            break;
        case 'product':
            route = `/products/${id}/comments`;
            break;
        default:
            console.error('Unknown type:', type);
            return;
    }
    
    fetch(route, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const commentsList = document.getElementById(`comments-list-${type}-${id}`);
        if (data.comments && data.comments.length > 0) {
            commentsList.innerHTML = data.comments.map(comment => `
                <div class="mb-4 p-3 bg-gray-50 rounded-lg" id="comment-${comment.id}">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                            ${comment.user && comment.user.profile_picture ? 
                                `<img src="${comment.user.profile_picture}" alt="${comment.user.name}" class="w-full h-full object-cover">` :
                                `<div class="w-full h-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                                    ${comment.user && comment.user.name ? comment.user.name.charAt(0).toUpperCase() : (comment.user_name ? comment.user_name.charAt(0).toUpperCase() : 'U')}
                                </div>`
                            }
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-sm">${comment.user ? comment.user.name : (comment.user_name || 'User')}</span>
                                    <span class="text-xs text-gray-500">${comment.created_at_human || comment.created_at}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button onclick="likeComment(${comment.id})" class="text-gray-400 hover:text-red-500 text-xs">
                                        <i class="far fa-heart"></i> <span class="like-count-${comment.id}">0</span>
                                    </button>
                                    ${comment.can_delete ? `<button onclick="deleteComment(${comment.id}, '${type}', ${id})" class="text-gray-400 hover:text-red-500 text-xs">
                                        <i class="fas fa-trash"></i>
                                    </button>` : ''}
                                </div>
                            </div>
                            <p class="text-sm text-gray-700">${comment.comment}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            commentsList.innerHTML = '<div class="text-center py-4 text-gray-500">No comments yet. Be the first to comment!</div>';
        }
    })
    .catch(error => {
        console.error('Error loading comments:', error);
        const commentsList = document.getElementById(`comments-list-${type}-${id}`);
        commentsList.innerHTML = '<div class="text-center py-4 text-red-500">Error loading comments</div>';
    });
}

function submitComment(event, type, id) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Determine the correct route based on type
    let route = '';
    switch(type) {
        case 'business':
            route = `/businesses/${id}/comment`;
            break;
        case 'hotel':
            route = `/hotels/${id}/comment`;
            break;
        case 'resort':
            route = `/resorts/${id}/comment`;
            break;
        case 'attraction':
            route = `/tourist-spots/${id}/comment`;
            break;
        case 'product':
            route = `/products/${id}/comment`;
            break;
        default:
            console.error('Unknown type:', type);
            return;
    }
    
    fetch(route, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear the form
            form.reset();
            // Reload comments
            loadComments(type, id);
            // Update comment count in feed without refreshing entire feed
            updateFeedItemCommentCount(type, id);
        } else {
            alert(data.error || 'Error submitting comment');
        }
    })
    .catch(error => {
        console.error('Error submitting comment:', error);
        alert('Error submitting comment');
    });
}

function likeComment(commentId) {
    fetch(`/comments/${commentId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeCountSpan = document.querySelector(`.like-count-${commentId}`);
            const heartIcon = document.querySelector(`[onclick="likeComment(${commentId})"] i`);
            
            if (likeCountSpan) {
                likeCountSpan.textContent = data.like_count || 0;
            }
            
            if (heartIcon) {
                if (data.liked) {
                    heartIcon.classList.remove('far');
                    heartIcon.classList.add('fas', 'text-red-500');
                } else {
                    heartIcon.classList.remove('fas', 'text-red-500');
                    heartIcon.classList.add('far');
                }
            }
        }
    })
    .catch(error => {
        console.error('Error liking comment:', error);
    });
}

function deleteComment(commentId, type, contentId) {
    if (confirm('Are you sure you want to delete this comment?')) {
        fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const commentElement = document.getElementById(`comment-${commentId}`);
                if (commentElement) {
                    commentElement.remove();
                }
                // Update comment count in feed without refreshing entire feed
                updateFeedItemCommentCount(type, contentId);
            } else {
                alert(data.error || 'Error deleting comment');
            }
        })
        .catch(error => {
            console.error('Error deleting comment:', error);
            alert('Error deleting comment');
        });
    }
}

// Helper functions to update feed items without full refresh
function updateFeedItemRating(type, id, averageRating, totalRatings) {
    const feedItem = document.querySelector(`[onclick*="toggleLike('${type}', ${id})"]`).closest('.bg-white');
    if (feedItem) {
        const ratingButton = feedItem.querySelector(`[onclick*="showRating('${type}', ${id})"] span`);
        if (ratingButton) {
            ratingButton.textContent = `${averageRating.toFixed(1)} (${totalRatings})`;
        }
    }
}

function updateFeedItemCommentCount(type, id) {
    // Get current comment count from the modal or make a quick API call
    const feedItem = document.querySelector(`[onclick*="toggleLike('${type}', ${id})"]`).closest('.bg-white');
    if (feedItem) {
        const commentButton = feedItem.querySelector(`[onclick*="showComments('${type}', ${id})"] span`);
        if (commentButton) {
            // Get count from the currently open modal if available
            const modal = document.getElementById(`comments-modal-${type}-${id}`);
            if (modal) {
                const comments = modal.querySelectorAll('[id^="comment-"]');
                commentButton.textContent = comments.length;
            }
        }
    }
}

// Add keyboard support for closing modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Close any open modals
        const modals = document.querySelectorAll('[id^="rating-modal-"], [id^="comments-modal-"]');
        modals.forEach(modal => {
            modal.remove();
        });
        document.body.classList.remove('overflow-hidden');
    }
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        e.target.remove();
        document.body.classList.remove('overflow-hidden');
    }
});
</script>
@endpush