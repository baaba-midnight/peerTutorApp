<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>

    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/manage-users.css">
    <link rel="stylesheet" href="../../assets/css/view-tutor.css">
</head>

<body>
    <?php
    $role = 'admin';
    include('../../includes/header.php');
    ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <div class="content-header">
                <h1>User Management</h1>
                <div class="header-actions">
                    <button class="search-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.5 14H14.71L14.43 13.73C15.41 12.59 16 11.11 16 9.5C16 5.91 13.09 3 9.5 3C5.91 3 3 5.91 3 9.5C3 13.09 5.91 16 9.5 16C11.11 16 12.59 15.41 13.73 14.43L14 14.71V15.5L19 20.49L20.49 19L15.5 14ZM9.5 14C7.01 14 5 11.99 5 9.5C5 7.01 7.01 5 9.5 5C11.99 5 14 7.01 14 9.5C14 11.99 11.99 14 9.5 14Z" fill="black" />
                        </svg>
                        Search
                    </button>
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Search users...">
                    </div>
                    <button class="filter-btn">Filter</button>
                    <div class="filter-container">
                        <select id="userFilter">
                            <option value="">All Users</option>
                            <option value="admin">Admin</option>
                            <option value="tutor">Tutor</option>
                            <option value="student">Student</option>
                            <option value=1>Active</option>
                            <option value=0>Inactive</option>
                        </select>
                    </div>
                    <button class="add-user-btn open-user-modal" data-bs-toggle="modal" data-bs-target="#userModalForm" data-mode="add">Add User</button>
                </div>
            </div>

            <table id="userTable">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <!-- User rows will be dynamically inserted here -->
                </tbody>
            </table>

            <div id="pagination" class="pagination">
                <!-- Will be generated with JS -->
            </div>
        </div>

    </div>

    <!-- User Details Modal -->
    <div class="modal fade" id="userDetails" tabindex="-1" aria-labelledby="userDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailsLabel">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content will be dynamically inserted here based on user role -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add and Edit User Modal -->
    <div class="modal fade" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true" id="userModalForm" data-mode="add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="logo">
                        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M37.5 70C37.5 70 62.5 70 62.5 60C62.5 50 37.5 50 37.5 40C37.5 30 62.5 30 62.5 30" stroke="black" stroke-width="10" stroke-linecap="round" />
                            <path d="M75 20L90 35L75 50" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M25 50L10 65L25 80" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>

                    <h1 class="heading" id="formTitle">Add User</h1>
                </div>
                <div class="modal-body">
                    <form id="registrationForm" novalidate>

                        <!-- Personal Information -->
                        <div class="section">
                            <h2 class="section-title">User Information</h2>
                            <div class="mb-3 text-center">
                                <img id="profilePreview" src="../../assets/images/woman-1.jpg" alt="Profile Picture" class="rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ccc;" />
                                <div>
                                    <label for="profilePicture" class="btn btn-sm btn-outline-primary mt-2">Change Picture</label>
                                    <input type="file" id="profilePicture" name="profilePicture" accept="image/*" style="display: none;">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">First Name *</label>
                                    <input type="text" id="firstName" class="form-control" name="firstName" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Last Name *</label>
                                    <input type="text" id="lastName" class="form-control" name="lastName" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" class="form-control" name="phone">
                            </div>
                        </div>

                        <!-- Account Details -->
                        <div class="section">
                            <h2 class="section-title">Account Details</h2>
                            <div class="form-group bio">
                                <label for="bio">Bio *</label>
                                <input type="textarea" id="bio" class="form-control" name="bio" required>
                            </div>
                            <div class="form-group password-group">
                                <label for="password">Password <span class="password-hint">(Leave blank to keep unchanged)</span></label>
                                <input type="password" id="password" class="form-control" name="password">
                            </div>
                            <div class="form-group password-group">
                                <label for="confirmPassword">Confirm Password</label>
                                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Account Type *</label>
                                <div class="mb-3">
                                    <label for="roleSelect" class="form-label">Role</label>
                                    <select id="roleSelect" class="form-select" name="role">
                                        <option value="">Select a role</option>
                                        <option value="admin">Admin</option>
                                        <option value="student">Student</option>
                                        <option value="tutor">Tutor</option>
                                    </select>
                                </div>
                                <input type="hidden" id="selectedRole" name="selectedRole" required>
                            </div>
                        </div>


                        <!-- Preferences -->
                        <div class="section">
                            <h2 class="section-title">Preferences</h2>
                            <div class="form-group">
                                <label for="subjects">Subjects of Interest *</label>
                                <select id="subjects" class="form-control" name="subjects" multiple required>
                                    <!-- options unchanged -->
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn" id="submitBtn" type="submit" data-bs-dismiss="modal"></button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" tabindex="-1" id="deleteModal" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Are you sure you want to delete your account?</h2>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Type 'DELETE' to confirm</p>
                    <input type="text" id="confirmDeleteInput" placeholder="Type 'DELETE' here" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" id="deleteBtn" onclick="confirmDelete()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Tutor Modal -->
    <div id="approveModal" class="modal fade" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Approve Tutor Application</h2>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this tutor application?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" id="approveBtn">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Tutor Modal -->
    <div id="rejectModal" class="modal fade" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Reject Tutor Application</h2>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection-reason">Reason for Rejection</label>
                        <textarea id="rejection-reason" class="form-control" rows="4" placeholder="Provide feedback to the applicant..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" id="rejectBtn" onclick="rejectTutor()">Reject</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/activePage.js"></script>
    <script src="../../assets/js/admin/userDetails.js"></script>
    <script src="../../assets/js/admin/userManagement.js"></script>
    <script src="../../assets/js/viewTutors.js"></script>
    <script src="../../assets/js/admin/searchFilter.js"></script>
    <script src="../../assets/js/admin/addEditUsers.js"></script>
</body>

</html>