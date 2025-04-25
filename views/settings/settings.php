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
                        <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="list">Notifications</a>
                        <a href="#privacy" class="list-group-item list-group-item-action" data-bs-toggle="list">Privacy</a>
                        <a href="#preferences" class="list-group-item list-group-item-action" data-bs-toggle="list">Preferences</a>
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

                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="notifications">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="profile-title">Notification Settings</h3>
                                </div>
                                <div class="card-body">
                                    <form id="notificationSettingsForm">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                                <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="appointmentReminders" checked>
                                                <label class="form-check-label" for="appointmentReminders">Appointment Reminders</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="messageNotifications" checked>
                                                <label class="form-check-label" for="messageNotifications">Message Notifications</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Tab -->
                        <div class="tab-pane fade" id="privacy">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="profile-title">Privacy Settings</h3>
                                </div>
                                <div class="card-body">
                                    <form id="privacySettingsForm">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="profileVisibility" checked>
                                                <label class="form-check-label" for="profileVisibility">Public Profile</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="showOnlineStatus" checked>
                                                <label class="form-check-label" for="showOnlineStatus">Show Online Status</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Preferences Tab -->
                        <div class="tab-pane fade" id="preferences">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="profile-title">Preferences</h3>
                                </div>
                                <div class="card-body">
                                    <form id="preferencesForm">
                                        <div class="mb-3">
                                            <label for="timezone" class="form-label">Timezone</label>
                                            <select class="form-select" id="timezone">
                                                <option value="UTC">UTC</option>
                                                <option value="EST">Eastern Time</option>
                                                <option value="CST">Central Time</option>
                                                <option value="PST">Pacific Time</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="language" class="form-label">Language</label>
                                            <select class="form-select" id="language">
                                                <option value="en">English</option>
                                                <option value="es">Spanish</option>
                                                <option value="fr">French</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn delete-button" id="deleteBtn">Delete</button>
                    </div>
                    </div>
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
                            <button type="button" class="btn btn-outline-secondary">Change Photo</button>
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
    <script src="../../assets/js/activePage.js"></script>
    <script src="../../assets/js/settings.js"></script>
    <script>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                alert('Settings saved successfully!');
            });
        });
    </script>
</body>
</html>
