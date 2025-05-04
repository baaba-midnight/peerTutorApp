<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Review - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <style>
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        .rating-input input {
            display: none;
        }
        .rating-input label {
            cursor: pointer;
            font-size: 2rem;
            color: #ddd;
            transition: color 0.2s;
        }
        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #ffd700;
        }
        .session-card {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .session-card:hover {
            transform: translateY(-5px);
        }
        .session-card.selected {
            border-color: #000;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <?php 
    $role = $_SESSION['role'];
    include('../../includes/header.php'); 
    ?>

    <div class="main-content">
        <div class="container py-4">
            <h1 class="mb-4">Give Review</h1>

            <!-- Session Selection -->
            <div class="mb-4">
                <h4>Select Session to Review</h4>
                <div class="row g-4">
                    <!-- Sample Session Cards -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card session-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="card-title mb-1">Mathematics Session</h5>
                                        <p class="text-muted mb-0">with John Smith</p>
                                    </div>
                                    <span class="badge bg-success">Completed</span>
                                </div>
                                <p class="card-text">
                                    <i class="bi bi-calendar"></i> April 10, 2025<br>
                                    <i class="bi bi-clock"></i> 2:00 PM - 3:00 PM
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card session-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="card-title mb-1">Physics Session</h5>
                                        <p class="text-muted mb-0">with Sarah Johnson</p>
                                    </div>
                                    <span class="badge bg-success">Completed</span>
                                </div>
                                <p class="card-text">
                                    <i class="bi bi-calendar"></i> April 8, 2025<br>
                                    <i class="bi bi-clock"></i> 4:00 PM - 5:00 PM
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Write Your Review</h4>
                    <form id="reviewForm">
                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label">Rating</label>
                            <div class="rating-input">
                                <input type="radio" id="star5" name="rating" value="5">
                                <label for="star5" class="bi bi-star-fill"></label>
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4" class="bi bi-star-fill"></label>
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3" class="bi bi-star-fill"></label>
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2" class="bi bi-star-fill"></label>
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1" class="bi bi-star-fill"></label>
                            </div>
                        </div>

                        <!-- Review Title -->
                        <div class="mb-3">
                            <label for="reviewTitle" class="form-label">Review Title</label>
                            <input type="text" class="form-control" id="reviewTitle" placeholder="Summarize your experience">
                        </div>

                        <!-- Detailed Review -->
                        <div class="mb-3">
                            <label for="reviewText" class="form-label">Detailed Review</label>
                            <textarea class="form-control" id="reviewText" rows="4" placeholder="Share your experience with the tutor"></textarea>
                        </div>

                        <!-- Categories -->
                        <div class="mb-4">
                            <label class="form-label d-block">What went well?</label>
                            <div class="btn-group-sm flex-wrap">
                                <input type="checkbox" class="btn-check" id="knowledge" autocomplete="off">
                                <label class="btn btn-outline-primary me-2 mb-2" for="knowledge">Knowledge</label>

                                <input type="checkbox" class="btn-check" id="communication" autocomplete="off">
                                <label class="btn btn-outline-primary me-2 mb-2" for="communication">Communication</label>

                                <input type="checkbox" class="btn-check" id="punctuality" autocomplete="off">
                                <label class="btn btn-outline-primary me-2 mb-2" for="punctuality">Punctuality</label>

                                <input type="checkbox" class="btn-check" id="teaching" autocomplete="off">
                                <label class="btn btn-outline-primary me-2 mb-2" for="teaching">Teaching Style</label>

                                <input type="checkbox" class="btn-check" id="preparation" autocomplete="off">
                                <label class="btn btn-outline-primary me-2 mb-2" for="preparation">Preparation</label>
                            </div>
                        </div>

                        <!-- Private Feedback -->
                        <div class="mb-4">
                            <label for="privateFeedback" class="form-label">Private Feedback (Optional)</label>
                            <textarea class="form-control" id="privateFeedback" rows="3" placeholder="This feedback will only be shared with the platform administrators"></textarea>
                        </div>

                        <!-- Anonymous Option -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="anonymous">
                                <label class="form-check-label" for="anonymous">
                                    Submit review anonymously
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle session card selection
        document.querySelectorAll('.session-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.session-card').forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
            });
        });

        // Handle form submission
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const rating = document.querySelector('input[name="rating"]:checked')?.value;
            const title = document.getElementById('reviewTitle').value;
            const text = document.getElementById('reviewText').value;
            const isAnonymous = document.getElementById('anonymous').checked;
            
            if (!rating) {
                alert('Please select a rating');
                return;
            }
            
            if (!title || !text) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Add AJAX call to submit review
            alert('Review submitted successfully!');
            window.location.href = 'view-reviews.php';
        });
    </script>
</body>
</html>