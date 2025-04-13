<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <style>
        .profile-image-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 1rem;
        }
        .profile-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .image-upload-label {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #000;
            color: #fff;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .image-upload-input {
            display: none;
        }
    </style>
</head>
<body>
    <?php 
    $role = 'student';
    include('../../includes/header.php'); 
    ?>

    <div class="main-content">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title text-center mb-4">Edit Profile</h1>
                            
                            <form id="editProfileForm">
                                <!-- Profile Picture -->
                                <div class="text-center mb-4">
                                    <div class="profile-image-container">
                                        <img src="../../assets/images/avatar.png" alt="Profile Picture" class="profile-image">
                                        <label for="profileImage" class="image-upload-label">
                                            <i class="bi bi-camera"></i>
                                        </label>
                                        <input type="file" id="profileImage" class="image-upload-input" accept="image/*">
                                    </div>
                                </div>

                                <!-- Personal Information -->
                                <div class="mb-4">
                                    <h5 class="card-subtitle mb-3">Personal Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="firstName" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="firstName" value="John">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="lastName" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="lastName" value="Doe">
                                        </div>
                                        <div class="col-12">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" value="john.doe@example.com">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control" id="phone" value="+1 (555) 123-4567">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="location" class="form-label">Location</label>
                                            <input type="text" class="form-control" id="location" value="New York, USA">
                                        </div>
                                    </div>
                                </div>

                                <!-- About Me -->
                                <div class="mb-4">
                                    <h5 class="card-subtitle mb-3">About Me</h5>
                                    <textarea class="form-control" id="about" rows="4">Computer Science student passionate about learning and helping others understand complex concepts. Interested in web development, artificial intelligence, and data science.</textarea>
                                </div>

                                <!-- Subjects -->
                                <div class="mb-4">
                                    <h5 class="card-subtitle mb-3">Subjects</h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <select class="form-select" id="subjects" multiple>
                                                <option value="mathematics" selected>Mathematics</option>
                                                <option value="physics" selected>Physics</option>
                                                <option value="computer-science" selected>Computer Science</option>
                                                <option value="chemistry">Chemistry</option>
                                                <option value="biology">Biology</option>
                                            </select>
                                            <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple subjects</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Social Links -->
                                <div class="mb-4">
                                    <h5 class="card-subtitle mb-3">Social Links</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="website" class="form-label">Website</label>
                                            <input type="url" class="form-control" id="website" value="https://portfolio-website.com">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="linkedin" class="form-label">LinkedIn</label>
                                            <input type="url" class="form-control" id="linkedin" placeholder="LinkedIn profile URL">
                                        </div>
                                    </div>
                                </div>

                                <!-- Privacy Settings -->
                                <div class="mb-4">
                                    <h5 class="card-subtitle mb-3">Privacy Settings</h5>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="publicProfile" checked>
                                        <label class="form-check-label" for="publicProfile">
                                            Make my profile public
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showEmail" checked>
                                        <label class="form-check-label" for="showEmail">
                                            Show my email to other users
                                        </label>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between">
                                    <a href="view-profile.php" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle profile image upload
        document.getElementById('profileImage').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.profile-image').src = e.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Handle form submission
        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add AJAX call to update profile
            alert('Profile updated successfully!');
            window.location.href = 'view-profile.php';
        });
    </script>
</body>
</html>