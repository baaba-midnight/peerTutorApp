<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    header('Location: ../../views/auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/settings.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <?php 
    $role = 'student'; // This should come from session
    include('../../includes/header.php'); 
    ?>

    <div class="main-content">
        <div class="container py-4">
            <h1 class="mb-4">Settings</h1>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="#profile" class="list-group-item list-group-item-action" data-bs-toggle="list">Profile</a>
                        <a href="#account" class="list-group-item list-group-item-action active" data-bs-toggle="list">Account Settings</a>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="tab-content">
                        <!-- Profile Tab -->
                        <div class="tab-pane fade" id="profile">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="profile-title">My Profile</h3>
                                    <button class="edit-button" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                                </div>
                                <div class="card-body">
                                    <div class="profile-content">
                                        <div class="profile-image">
                                            <img id="profileImage" src="../../assets/images/man-1.jpg" alt="Profile Picture">
                                        </div>
                                        <div class="profile-info">
                                            <h4 class="profile-name" id="profileName">Malcom Price</h4>
                                            <p class="profile-role" id="profileRole">Student</p>
                                        </div>
                                    </div>
                                    <p class="profile-bio" id="profileBio">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                    </p>
                                </div>
                            </div>

                            <!-- Tutor Additional Functionality -->
                            <?php if ($role === 'tutor'): ?>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h3 class="profile-title">Tutor Profile</h3>
                                            <button class="edit-button" data-bs-toggle="modal" data-bs-target="#editTutorModal">Edit Tutor Profile</button>
                                        </div>

                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="courses-offered" class="form-label">Courses Offered</label>
                                                <input type="text" id="courses-offered" class="form-control" value="OOP, DSA" readonly>
                                            </div>

                                            <div class="form-group">
                                                <label for="experience" class="form-label">Tutoring Experience</label>
                                                <input type="text" id="experience" class="form-control" value="5 years" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h3 class="profile-title">Availability & Scheduling</h3>
                                            <button class="edit-button" data-bs-toggle="modal" data-bs-target="#editAvailabilityModal">Set Availability</button>
                                        </div>

                                        <div class="card-body">
                                            <!-- Display Current Schedule -->
                                            <div id="current-schedule">
                                                <p><strong>Monday:</strong> 9:00 AM - 12:00 PM, 1:00 PM - 4:00 PM</p>
                                                <p><strong>Tuesday:</strong> 10:00 AM - 2:00 PM</p>
                                                <p><strong>Wednesday:</strong> Not Available</p>
                                                <p><strong>Thursday:</strong> 9:00 AM - 12:00 PM</p>
                                                <p><strong>Friday:</strong> 2:00 PM - 5:00 PM</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Account Settings Tab -->
                        <div class="tab-pane fade show active" id="account">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="profile-title">Account Settings</h3>
                                    <div class="action-btn">
                                        <button class="edit-button" data-bs-toggle="modal" data-bs-target="#editAccountModal">Edit Account</button>
                                        <button class="delete-button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" class="form-control" value="malcom.price@peerTutor.com" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" id="password" class="form-control" value="wow@19202ik" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm">
                        <div class="text-center mb-4">
                            <div class="upload-preview mx-auto mb-3">
                                <img id="uploadPreview" src="../../assets/images/avatar.png" alt="Profile Picture">
                            </div>
                            <input type="file" id="profileImageInput" accept="image/*" style="display:none;">
                            <button type="button" class="btn btn-outline-secondary" id="changePhotoBtn">Change Photo</button>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" value="Malcom Price">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" id="role" value="Student" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" rows="4">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveProfileBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAccountModalLabel">Edit Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAccountForm">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" placeholder="Enter current password">
                        </div>

                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
                        </div>

                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveAccountBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="delete-question">Are you sure you want to delete your account?</p>
                    <div class="alert alert-danger">This action cannot be undone. All your data will be permanently deleted.</div>
                    <form id="deleteAccountForm">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="deleteConfirmation" placeholder="Type 'DELETE' to confirm" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteBtn">Delete Account</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tutor Additional Modals -->
    <?php if ($role === 'tutor'): ?>
    <!-- Edit Tutor Profile Modal -->
    <div class="modal fade" id="editTutorModal" tabindex="-1" aria-labelledby="editTutorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTutorModalLabel">Edit Tutor Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTutorForm">
                        <div class="mb-3">
                            <label for="tutorCourses" class="form-label">Courses Offered</label>
                            <select class="form-select" id="tutorCourses" multiple>
                                <option value="OOP" selected>Object-Oriented Programming</option>
                                <option value="DSA" selected>Data Structures & Algorithms</option>
                                <option value="DB">Database Systems</option>
                                <option value="WebDev">Web Development</option>
                                <option value="ML">Machine Learning</option>
                            </select>
                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple courses</small>
                        </div>

                        <div class="mb-3">
                            <label for="tutorExperience" class="form-label">Tutoring Experience (years)</label>
                            <input type="number" class="form-control" id="tutorExperience" value="5" min="0" max="50">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveTutorBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Availability Schedule Modal -->
    <div class="modal fade" id="editAvailabilityModal" tabindex="-1" aria-labelledby="editAvailabilityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAvailabilityModalLabel">Edit Your Availability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="availability-form">
                        <!-- Example of time slots for each day -->
                        <div class="mb-3">
                            <label for="monday" class="form-label">Monday</label>
                            <input type="text" class="form-control" id="monday" placeholder="e.g. 9:00 AM - 12:00 PM" value="9:00 AM - 12:00 PM, 1:00 PM - 4:00 PM">
                        </div>
                        <div class="mb-3">
                            <label for="tuesday" class="form-label">Tuesday</label>
                            <input type="text" class="form-control" id="tuesday" placeholder="e.g. 10:00 AM - 2:00 PM" value="10:00 AM - 2:00 PM">
                        </div>
                        <div class="mb-3">
                            <label for="wednesday" class="form-label">Wednesday</label>
                            <input type="text" class="form-control" id="wednesday" placeholder="e.g. Not Available" value="Not Available">
                        </div>
                        <div class="mb-3">
                            <label for="thursday" class="form-label">Thursday</label>
                            <input type="text" class="form-control" id="thursday" placeholder="e.g. 9:00 AM - 12:00 PM" value="9:00 AM - 12:00 PM">
                        </div>
                        <div class="mb-3">
                            <label for="friday" class="form-label">Friday</label>
                            <input type="text" class="form-control" id="friday" placeholder="e.g. 2:00 PM - 5:00 PM" value="2:00 PM - 5:00 PM">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveAvailabilityBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/activePage.js"></script>
    <script src="../../assets/js/settings.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch user details via AJAX
            $.ajax({
                url: '../../api/get_user.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data && !data.error) {
                        // Profile Tab
                        var fullName = (data.first_name ? data.first_name : '') + (data.last_name ? ' ' + data.last_name : '');
                        $('#profileName').text(fullName);
                        $('#profileRole').text(data.role ? data.role.charAt(0).toUpperCase() + data.role.slice(1) : '');
                        $('#profileBio').text(data.bio || '');
                        $('#name').val(fullName);
                        $('#role').val(data.role ? data.role.charAt(0).toUpperCase() + data.role.slice(1) : '');
                        $('#bio').val(data.bio || '');
                        if (data.profile_picture_url) {
                            $('#profileImage').attr('src', '../../' + data.profile_picture_url);
                            $('#uploadPreview').attr('src', '../../' + data.profile_picture_url);
                        }
                        // Account Tab
                        $('#email').val(data.email || '');
                        $('#password').val('********'); // Never show real password
                        // The following fields are not present in the HTML, so we skip them to avoid errors.
                        // $('#phone').val(data.phone_number || '');
                        // $('#department').val(data.department || '');
                        // $('#year_of_study').val(data.year_of_study || '');
                        // if (data.role === 'tutor') {
                        //     if (data.hourly_rate) $('#hourlyRate').val(data.hourly_rate);
                        //     if (data.overall_rating) $('#overallRating').val(data.overall_rating);
                        //     if (data.is_verified !== undefined) $('#isVerified').val(data.is_verified ? 'Yes' : 'No');
                        // }
                    }
                },
                error: function(xhr) {
                    // Optionally handle error
                }
            });

            $('#saveProfileBtn').on('click', function(e) {
                e.preventDefault();
                var name = $('#name').val().trim();
                var role = $('#role').val();
                var bio = $('#bio').val();
                var first_name = '', last_name = '';
                if (name) {
                    var parts = name.split(' ');
                    first_name = parts[0];
                    last_name = parts.slice(1).join(' ');
                }
                var formData = new FormData();
                formData.append('first_name', first_name);
                formData.append('last_name', last_name);
                formData.append('role', role);
                formData.append('bio', bio);
                var fileInput = $('#profileImageInput')[0];
                if (fileInput.files && fileInput.files[0]) {
                    formData.append('profile_image', fileInput.files[0]);
                }
                $.ajax({
                    url: '../../api/save_profile.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Profile updated successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (response.message || 'Could not update profile.'));
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while saving the profile.');
                    }
                });
            });

            // Change Photo functionality
            $('#changePhotoBtn').on('click', function() {
                $('#profileImageInput').click();
            });
            $('#profileImageInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#uploadPreview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            $('#saveAccountBtn').on('click', function(e) {
                e.preventDefault();
                var currentPassword = $('#currentPassword').val();
                var newPassword = $('#newPassword').val();
                var confirmPassword = $('#confirmPassword').val();

                if (!currentPassword || !newPassword || !confirmPassword) {
                    alert('Please fill in all password fields.');
                    return;
                }
                if (newPassword !== confirmPassword) {
                    alert('New password and confirm password do not match.');
                    return;
                }
                $.ajax({
                    url: '../../api/change_password.php',
                    method: 'POST',
                    data: {
                        current_password: currentPassword,
                        new_password: newPassword
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Password updated successfully!');
                            $('#editAccountModal').modal('hide');
                        } else {
                            alert('Error: ' + (response.message || 'Could not update password.'));
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while updating the password.');
                    }
                });
            });
        });
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                alert('Settings saved successfully!');
            });
        });
    </script>
</body>
</html>
