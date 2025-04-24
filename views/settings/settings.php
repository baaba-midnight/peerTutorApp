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
                                    <h1 class="profile-title">My Profile</h1>
                                    <button class="edit-button" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                                </div>
                                <div class="profile-content">
                                    <div class="profile-image">
                                        <img id="profileImage" src="../../assets/images/man-1.jpg" alt="Profile Picture">
                                    </div>
                                    <div class="profile-info">
                                        <h2 class="profile-name" id="profileName">Malcom Price</h2>
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
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h1 class="profile-title">Tutor Profile</h1>
                        <button class="edit-button" data-bs-toggle="modal" data-bs-target="#editTutorModal">Edit Tutor Profile</button>
                    </div>

                    <div class="card-content">
                        <div class="form-group">
                            <label for="courses-offered" class="form-label">Courses Offered</label>
                            <input type="dropdown" id="courses-offered" class="form-control" value="OOP, DSA" readonly>
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
                        <h1 class="profile-title">Availability & Scheduling</h1>
                        <button class="edit-button" data-bs-toggle="modal" data-bs-target="#editAvailabilityModal">Set Availability</button>
                    </div>

                    <div class="card-content">
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
        <?php endif; ?>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" aria-labelledby="editProfileModalLabel" aria-hidden="true">
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
                                <img id="uploadPreview" src="/api/placeholder/100/100" alt="Profile Picture">
                            </div>
                            <button type="button" class="btn btn-outline-secondary">Change Photo</button>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" value="Malcom Price">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" id="role" value="Student">
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" rows="4">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-save" id="saveChangesBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>


                        <!-- Account Settings Tab -->
                        <div class="tab-pane fade show active" id="account">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Account Settings</h3>
                                    <form id="accountSettingsForm">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" value="user@example.com">
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" value="username">
                                        </div>
                                        <div class="mb-3">
                                            <label for="currentPassword" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="currentPassword">
                                        </div>
                                        <div class="mb-3">
                                            <label for="newPassword" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="newPassword">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="notifications">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Notification Settings</h3>
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
                                <div class="card-body">
                                    <h3 class="card-title">Privacy Settings</h3>
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
                                <div class="card-body">
                                    <h3 class="card-title">Preferences</h3>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
