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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
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
                    </button>
                    <button class="filter-btn">Filter</button>
                    <button class="add-user-btn">Add User</button>
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

            <div class="pagination">
                <div class="page-info">Showing 1-4 of 25 users</div>
                <div class="page-buttons">
                    <button class="page-btn" disabled>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.41 7.41L14 6L8 12L14 18L15.41 16.59L10.83 12L15.41 7.41Z" fill="white" />
                        </svg>
                    </button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.59 7.41L9.17 6L3.17 12L9.17 18L10.59 16.59L5.99 12L10.59 7.41Z" fill="white" />
                            <path d="M18.59 7.41L17.17 6L11.17 12L17.17 18L18.59 16.59L13.99 12L18.59 7.41Z" fill="white" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- View Tutor profile -->
    <div id="tutorDetails" class="modal fade" tabindex="-1" aria-labelledby="tutorDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div id="tutorProfile">

            </div>
            <h2>Reviews</h2>
            <div id="reviewsContainer">

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

    <!-- Approve Tutor Modal
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
    </div> -->

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
    <script src="../../assets/js/userManagement.js"></script>
    <script src="../../assets/js/viewTutors.js"></script>
</body>

</html>