<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <style>
        .review-card {
            transition: transform 0.2s;
        }
        .review-card:hover {
            transform: translateY(-5px);
        }
        .rating {
            color:rgb(200, 173, 23);
        }
        .review-stats {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 600;
            color: #000;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .review-filter {
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <?php 
    $role = 'tutor';
    include('../../includes/header.php'); 
    ?>

    <input type="hidden" id="userId" value="5">

    <div class="main-content">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>My Reviews</h1>
            </div>

            <!-- Review Statistics -->
            <div class="review-stats">
                <div class="row text-center">
                    <div class="col-md-6">
                        <div class="stat-number" id="avgRating">0</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-number" id="totalReviews">0</div>
                        <div class="stat-label">Total Reviews</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="review-filter">
                <div class="row g-3">
                    <div class="col-md">
                        <select class="form-select" id="ratingFilter">
                            <option value="all">All Ratings</option>
                            <option value=5>5 Stars</option>
                            <option value=4>4 Stars</option>
                            <option value=3>3 Stars</option>
                            <option value=2>2 Stars</option>
                            <option value=1>1 Star</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div id="reviewsContainer" class="row g-4">
                <!-- Will dynamically be inserted with JS -->
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/activePage.js"></script>
    <script src="../../assets/js/viewReviews.js"></script>
</body>
</html>