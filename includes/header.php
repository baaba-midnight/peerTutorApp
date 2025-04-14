<header class="header">
    <div class="nav-container">
        <a href="../../index.php" class="logo">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M37.5 70C37.5 70 62.5 70 62.5 60C62.5 50 37.5 50 37.5 40C37.5 30 62.5 30 62.5 30" stroke="black" stroke-width="10" stroke-linecap="round" />
                <path d="M75 20L90 35L75 50" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25 50L10 65L25 80" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>

        <?php if ($role === 'student') : ?>
            <nav class="main-nav">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'student-dashboard.php' ? 'active' : ''; ?>" 
                   href="../../views/dashboard/student-dashboard.php">Home</a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'schedule.php' ? 'active' : ''; ?>"
                   href="../../views/appointments/schedule.php">Find Tutors</a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view-appointments.php' ? 'active' : ''; ?>"
                   href="../../views/appointments/view-appointments.php">Appointments</a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'chat.php' ? 'active' : ''; ?>"
                   href="../../views/messaging/chat.php">Messages</a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>"
                   href="../../views/settings/settings.php">Settings</a>
            </nav>
        <?php elseif ($role === 'tutor') : ?>
            <nav class="main-nav">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'tutor-dashboard.php' ? 'active' : ''; ?>"
                   href="../../views/dashboard/tutor-dashboard.php">Home</a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view-appointments.php' ? 'active' : ''; ?>"
                   href="../../views/appointments/view-appointments.php">Appointments</a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'chat.php' ? 'active' : ''; ?>"
                   href="../../views/messaging/chat.php">Messages</a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>"
                   href="../../views/settings/settings.php">Settings</a>
            </nav>
        <?php elseif ($role === 'admin') : ?>
            <nav class="main-nav">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin-dashboard.php' ? 'active' : ''; ?>"
                   href="../../views/dashboard/admin-dashboard.php">Home</a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-users.php' ? 'active' : ''; ?>"
                   href="../../views/admin/manage-users.php">User Management</a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>"
                   href="../../views/admin/reports.php">Reports</a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>"
                   href="../../views/settings/settings.php">Settings</a>
            </nav>
        <?php endif; ?>

        <div class="user-actions">
            <div class="notification-bell">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="notification-badge">3</span>
            </div>
            <a href="../../api/auth.php?action=logout" class="logout-btn">Logout</a>
        </div>
    </div>
</header>

<script>
document.querySelector('.logout-btn').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = this.href;
    }
});
</script>