<?php 
    define('BASE_URL', 'http://localhost/peerTutorApp'); 
    define('ROOT_PATH', 'C:/xampp/htdocs/peerTutorApp');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs | Peer Tutoring Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL ?>/assets/css/main.css">
    <!-- <link rel="stylesheet" href="../../assets/css/dashboard.css"> -->
    <link rel="stylesheet" href="<?php echo BASE_URL ?>/assets/css/header.css">

    <script src="<?php echo BASE_URL ?>/assets/js/activePage.js"></script>

    <style>
        .log-critical {
            background-color: #ffdddd;
        }

        .log-error {
            background-color: #ffe6e6;
        }

        .log-warning {
            background-color: #fff3cd;
        }

        .log-info {
            background-color: #e7f5ff;
        }

        .log-debug {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #fff;
        }

        .severity-info {
            background-color: #2E4F2A !important;
        }

        .severity-debug {
            background-color: #6D6D6D !important;
            color: #ffffff !important;
        }
        
        .severity-warning {
            background-color: #6A2E2E !important;
        }

        .severity-error {
            background-color: #C72E2E !important;
        }

        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php
    $role = 'admin';
    include(ROOT_PATH . '/includes/header.php');
    ?>

    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">System Logs</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button class="btn btn-sm btn-outline-secondary" id="exportLogs">
                    <i class="bi bi-download"></i> Export Logs
                </button>
                <button class="btn btn-sm btn-outline-danger ms-2" id="archiveLogs">
                    <i class="bi bi-archive"></i> Archive Old Logs
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="dateFrom" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="dateFrom">
                </div>
                <div class="col-md-3">
                    <label for="dateTo" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="dateTo">
                </div>
                <div class="col-md-3">
                    <label for="severityFilter" class="form-label">Severity Level</label>
                    <select class="form-select" id="severityFilter">
                        <option value="">All Levels</option>
                        <option value="critical">Critical</option>
                        <option value="error">Error</option>
                        <option value="warning">Warning</option>
                        <option value="info">Info</option>
                        <option value="debug">Debug</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="moduleFilter" class="form-label">Module</label>
                    <select class="form-select" id="moduleFilter">
                        <option value="">All Modules</option>
                        <option value="auth">Authentication</option>
                        <option value="tutoring">Tutoring</option>
                        <option value="scheduling">Scheduling</option>
                        <option value="database">Database</option>
                        <option value="backup">Backup</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn w-100" id="applyFilters">Apply Filters</button>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn w-100" id="resetFilters">Reset Filters</button>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="table-responsive">
            <table id="logsTable" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Severity</th>
                        <th>Module</th>
                        <th>Message</th>
                        <th>User</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Will be inserted with JS dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-controls" id="pagination">
            <!-- Pagination buttons will be inserted here by JS -->
        </div>
    </div>
    </div>

    <!-- Log Details Modal -->
    <div class="modal fade" id="logDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Timestamp:</strong> <span id="detailTimestamp">2023-10-15 14:32:45</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Severity:</strong> <span id="detailSeverity" class="badge bg-danger">Critical</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Module:</strong> <span id="detailModule">Database</span>
                        </div>
                        <div class="col-md-6">
                            <strong>User:</strong> <span id="detailUser">admin1</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>IP Address:</strong> <span id="detailIp">192.168.1.10</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Request ID:</strong> <span id="detailRequestId">req_abc123</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Message:</strong>
                        <div class="p-2 bg-light rounded mt-1" id="detailMessage">
                            Failed to connect to database server: Connection refused. Check if MySQL service is running.
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Stack Trace:</strong>
                        <pre class="p-2 bg-light rounded mt-1" id="detailStackTrace" style="max-height: 200px; overflow: auto;">
                            at Database.connect() in /app/database.php:45
                            at System.initialize() in /app/system.php:12
                            at main() in /app/index.php:5
                        </pre>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="<?php echo BASE_URL ?>/assets/js/admin/SystemLogs.js"></script>
</body>

</html>