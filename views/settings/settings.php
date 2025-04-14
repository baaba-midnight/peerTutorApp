<?php $role = 'admin' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/settings.css">
</head>

<body>
    <?php include('../../includes/header.php'); ?>
    <!-- Profile Card -->
    <div class="main-content ">

        <div class="row row-cols-1 row-cols-md-2 g-4"">

            <!-- Profile Section -->
            <div class=" col">
            <div class="card h-100">
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
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>
        </div>

        <!-- Account Settings Section -->
        <div class="col">
            <div class="card h-100">
                <div class="card-header">
                    <h1 class="profile-title">Account Settings</h1>
                    <div class="action-btn">
                        <button class="edit-button" data-bs-toggle="modal" data-bs-target="#editAccountModal">Edit Account</button>
                        <button class="delete-button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>
                    </div>
                </div>

                <div class="card-content">
                    <div class="form-group">
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

    <!-- Edit Account Modal -->
    <div class="modal fade" tabindex="-1" id="editAccountModal" aria-labelledby="editAccountModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-save" id="saveChangesBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" tabindex="-1" id="deleteAccountModal" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <p class="delete-question">Are you sure you want to delete your account?</p>
                    <form id="deleteAccountForm">
                        <div class="mb-3">
                            <input type="password" class="form-control" id="currentPassword" placeholder="Type 'DELETE' to confirm" required>
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

    <!-- Tutor Additional Functionality -->
    <?php if ($role === 'tutor'): ?>
        <!-- Edit Availability Schedule -->
        <div class="modal fade" tabindex="-1" id="editAvailabilityModal" aria-labelledby="editAvailabilityModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Your Availability</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="availability-form">
                            <!-- Example of time slots for each day -->
                            <div class="mb-3">
                                <label for="monday" class="form-label">Monday</label>
                                <input type="text" class="form-control" id="monday" placeholder="e.g. 9:00 AM - 12:00 PM">
                            </div>
                            <div class="mb-3">
                                <label for="tuesday" class="form-label">Tuesday</label>
                                <input type="text" class="form-control" id="tuesday" placeholder="e.g. 10:00 AM - 2:00 PM">
                            </div>
                            <div class="mb-3">
                                <label for="wednesday" class="form-label">Wednesday</label>
                                <input type="text" class="form-control" id="wednesday" placeholder="e.g. Not Available">
                            </div>
                            <div class="mb-3">
                                <label for="thursday" class="form-label">Thursday</label>
                                <input type="text" class="form-control" id="thursday" placeholder="e.g. 9:00 AM - 12:00 PM">
                            </div>
                            <div class="mb-3">
                                <label for="friday" class="form-label">Friday</label>
                                <input type="text" class="form-control" id="friday" placeholder="e.g. 2:00 PM - 5:00 PM">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-save" id="save-availability">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>

    <script src="../../assets/js/activePage.js"></script>
    <script src="../../assets/js/settings.js"></script>
</body>

</html>