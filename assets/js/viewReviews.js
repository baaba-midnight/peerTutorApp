// const API_BASE_URL = '../../tutor-reviews.php';

// Configuration
let currentPage = 1;
const pageSize = 5;
let totalRecords = 0;
let totalFilteredRecords = 0;

async function loadTutorStats(tutorId) {
    const res = await fetch(`../../api/tutor-review.php?action=getReviewStats&tutor_id=${tutorId}`);
    const data = await res.json();

    document.getElementById("avgRating").textContent = isNaN(parseFloat(data.data.avg_rating)) ? "0.0" : parseFloat(data.data.avg_rating).toFixed(1);
    document.getElementById("totalReviews").textContent = data.data.total_reviews;
}

async function loadReviews(tutorId, sortBy = "all", pageSize = 10) { // Added pageSize parameter
    try {
        const res = await fetch(`../../api/tutor-review.php?action=getFilteredReviews&tutor_id=${tutorId}&rating=${sortBy}&limit=${pageSize}`);
        
        if (!res.ok) { // Check for HTTP errors
            throw new Error(`Failed to fetch: ${res.success} ${res.message}`);
        }

        const reviews = await res.json();

        const container = document.getElementById("reviewsContainer");
        container.innerHTML = ""; // Clear container

        // Safely get data (handle non-array or missing 'data')
        const data = reviews?.data.data && Array.isArray([reviews.data.data]) ? reviews.data.data : [];

        if (data.length === 0) {
            const noDataMessage = document.createElement("div");
            noDataMessage.className = "text-center text-muted";
            noDataMessage.textContent = "No reviews available";
            container.appendChild(noDataMessage);
            return;
        }

        // Generate review cards
        data.forEach(review => {
            const stars = "★".repeat(review.rating) + "☆".repeat(5 - review.rating);
            const card = `
                <div class="col-md-6">
                    <div class="card review-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div class="rating mb-2">${stars}</div>
                                </div>
                            </div>
                            <p class="card-text">${review.comment}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">${new Date(review.created_at).toDateString()}</small>
                            </div>
                        </div>
                    </div>
                </div>`;
            container.innerHTML += card;
        });

    } catch (error) {
        console.error("Error loading reviews:", error);
        // Display error message to user
        const container = document.getElementById("reviewsContainer");
        container.innerHTML = `<div class="text-center text-danger">Error loading reviews. Please try again.</div>`;
    }
}

document.getElementById("ratingFilter").addEventListener("change", function () {
    const sortBy = this.value;
    const tutorId = document.getElementById("userId").value;

    console.log(tutorId);
    loadReviews(tutorId, sortBy);
});

window.onload = function () {
    const tutorId = parseInt(document.getElementById("userId").value);
    loadTutorStats(tutorId);
    loadReviews(tutorId);
};