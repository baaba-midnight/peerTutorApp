<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup & Restore | Peer Tutoring Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../assets/css/main.css" rel="stylesheet">
    <link href="../../assets/css/header.css" rel="stylesheet">

    <script src="../../assets/js/activePage.js"></script>


    <style>
        .badge {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            text-align: center !important;
            justify-content: center !important;
            font-weight: var(--poppins-regular);
        }

        .badge.bg-success {
            background-color: #2E4F2A !important;
        }

        .badge.bg-warning {
            background-color: #6D6D6D !important;
            color: #ffffff !important;
        }
        
        .badge.bg-danger {
            background-color: #6A2E2E !important;
        }

        .backup-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .backup-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .backup-success {
            border-left: 5px solid #2E4F2A;
        }

        .backup-warning {
            border-left: 5px solid #6D6D6D;
        }

        .backup-danger {
            border-left: 5px solid #6A2E2E;
        }

        .progress-thin {
            height: 5px;
        }

        .backup-size {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <?php
    $role ='admin';
    include_once '../../includes/header.php';
    ?>

    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Backup & Restore</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button class="btn btn-sm btn-primary" id="createBackup">
                    <i class="bi bi-database-add"></i> Create New Backup
                </button>
            </div>
        </div>

        <!-- Backup Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-black text-white">
                        <h5 class="mb-0">Backup Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card backup-card backup-success mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title">Full System Backup</h5>
                                            <span class="badge bg-success">Complete</span>
                                        </div>
                                        <p class="card-text backup-size">2.4 GB - 2023-10-15 02:00:00</p>
                                        <p class="card-text">Includes all database tables and system files.</p>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-sm btn-outline-primary download-backup" data-id="backup1">
                                                <i class="bi bi-download"></i> Download
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-backup" data-id="backup1">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card backup-card backup-warning mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title">Database Only Backup</h5>
                                            <span class="badge bg-warning text-dark">Partial</span>
                                        </div>
                                        <p class="card-text backup-size">156 MB - 2023-10-14 02:00:00</p>
                                        <p class="card-text">Database tables only, excludes system files.</p>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-sm btn-outline-primary download-backup" data-id="backup2">
                                                <i class="bi bi-download"></i> Download
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-backup" data-id="backup2">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card backup-card backup-success mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title">Full System Backup</h5>
                                            <span class="badge bg-success">Complete</span>
                                        </div>
                                        <p class="card-text backup-size">2.3 GB - 2023-10-13 02:00:00</p>
                                        <p class="card-text">Includes all database tables and system files.</p>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-sm btn-outline-primary download-backup" data-id="backup3">
                                                <i class="bi bi-download"></i> Download
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-backup" data-id="backup3">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card backup-card backup-danger mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title">Incremental Backup</h5>
                                            <span class="badge bg-danger">Failed</span>
                                        </div>
                                        <p class="card-text backup-size">0 MB - 2023-10-12 02:00:00</p>
                                        <p class="card-text">Failed due to disk space issues.</p>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                                <i class="bi bi-download"></i> Download
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-backup" data-id="backup4">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Refresh Backup List
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Restore Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-black text-white">
                        <h5 class="mb-0">Restore Options</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <strong>Warning:</strong> Restoring from a backup will overwrite existing data.
                            Always verify your backup before proceeding with a restore.
                        </div>

                        <div class="mb-4">
                            <h5>Restore from Existing Backup</h5>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <label for="selectBackup" class="form-label">Select Backup</label>
                                    <select class="form-select" id="selectBackup">
                                        <option selected disabled>Choose a backup...</option>
                                        <option value="backup1">Full System Backup - 2023-10-15 02:00:00</option>
                                        <option value="backup2">Database Only Backup - 2023-10-14 02:00:00</option>
                                        <option value="backup3">Full System Backup - 2023-10-13 02:00:00</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button class="btn" id="startRestore">
                                        <i class="bi bi-arrow-counterclockwise"></i> Begin Restore Process
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Upload Backup File</h5>
                            <div class="mb-3">
                                <label for="backupFile" class="form-label">Select backup file to upload</label>
                                <input class="form-control" type="file" id="backupFile" accept=".sql,.gz,.zip">
                            </div>
                            <button class="btn">
                                <i class="bi bi-upload"></i> Upload and Verify Backup
                            </button>
                        </div>

                        <div class="mb-3">
                            <h5>Restore Options</h5>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="preRestoreCheck" checked>
                                <label class="form-check-label" for="preRestoreCheck">
                                    Create automatic backup before restoring (recommended)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notifyUsers">
                                <label class="form-check-label" for="notifyUsers">
                                    Notify users about system maintenance
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="verifyOnly">
                                <label class="form-check-label" for="verifyOnly">
                                    Verify backup only (no actual restore)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup Progress Modal -->
    <div class="modal fade" id="backupProgressModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Creating System Backup</h5>
                </div>
                <div class="modal-body">
                    <p>Please wait while the system backup is being created...</p>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 45%"></div>
                    </div>
                    <div class="alert alert-info">
                        <small>Do not close this window or navigate away during the backup process.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" disabled>Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div class="modal fade" id="restoreConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Confirm Restore Operation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to restore the system from a backup. This operation will:</p>
                    <ul>
                        <li>Overwrite all current data with the backup data</li>
                        <li>Potentially cause data loss if the backup is incomplete</li>
                        <li>Require the system to be temporarily unavailable</li>
                    </ul>
                    <p class="fw-bold">Are you sure you want to proceed?</p>
                    <div class="mb-3">
                        <label for="confirmRestoreText" class="form-label">Type "CONFIRM" to proceed:</label>
                        <input type="text" class="form-control" id="confirmRestoreText" placeholder="CONFIRM">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="confirmRestoreBtn" disabled>Proceed with Restore</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Create backup button handler
            $('#createBackup').click(function() {
                $('#backupProgressModal').modal('show');

                // Simulate backup progress (in real app, this would be server-side events)
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 5;
                    $('.progress-bar').css('width', progress + '%');

                    if (progress >= 100) {
                        clearInterval(interval);
                        setTimeout(() => {
                            $('#backupProgressModal').modal('hide');
                            alert('Backup completed successfully!');
                        }, 500);
                    }
                }, 300);
            });

            // Start restore button handler
            $('#startRestore').click(function() {
                if ($('#selectBackup').val()) {
                    $('#restoreConfirmModal').modal('show');
                } else {
                    alert('Please select a backup to restore from');
                }
            });

            // Confirm restore text validation
            $('#confirmRestoreText').on('input', function() {
                $('#confirmRestoreBtn').prop('disabled', $(this).val().toUpperCase() !== 'CONFIRM');
            });

            // Confirm restore button
            $('#confirmRestoreBtn').click(function() {
                alert('Restore process would begin here');
                $('#restoreConfirmModal').modal('hide');
            });

            // Download backup button handlers
            $('.download-backup').click(function() {
                const backupId = $(this).data('id');
                alert('Download for backup ' + backupId + ' would start here');
            });

            // Delete backup button handlers
            $('.delete-backup').click(function() {
                const backupId = $(this).data('id');
                if (confirm('Are you sure you want to delete this backup? This cannot be undone.')) {
                    alert('Delete for backup ' + backupId + ' would proceed here');
                }
            });
        });
    </script>
</body>

</html>