<!-- Add so it changes depending on role = student, tutor and admin -->
<header class="header">
    <div class="logo">
        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M37.5 70C37.5 70 62.5 70 62.5 60C62.5 50 37.5 50 37.5 40C37.5 30 62.5 30 62.5 30" stroke="black" stroke-width="10" stroke-linecap="round" />
            <path d="M75 20L90 35L75 50" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M25 50L10 65L25 80" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    <div class="nav-container">

        <?php if ($role === 'student') : ?>
            <nav class="main-nav" id="mainNav">
                <a href="../dashboard/student-dashboard.php" class="active">Home</a>
                <a href="../appointments/schedule.php">Find Tutors</a>
                <a href="../appointments/view-appointments.php">Appointments</a>
                <a href="../messaging/chat.php">Messages</a>
                <a href="../settings/settings.php">Settings</a>
            </nav>
        <?php elseif ($role === 'tutor') : ?>
            <nav class="main-nav" id="mainNav">
                <a href="#" class="active">Home</a>
                <a href="#">View Students</a>
                <a href="#">Appointments</a>
                <a href="#">Messages</a>
                <a href="#">Settings</a>
            </nav>
        <?php elseif ($role === 'admin') : ?>
            <nav class="main-nav" id="mainNav">
                <a href="#" class="active">Home</a>
                <a href="#">View Tutors</a>
                <a href="#">Appointment Management</a>
                <a href="#">Messages</a>
                <a href="#">Settings</a>
            </nav>
        <?php endif; ?>

    </div>

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
</header>
<script>
document.querySelector('.logout-btn').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = this.href;
    }
});
</script>