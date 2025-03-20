<?php
// Get User Role
$role = $_SESSION['role'] ?? null;
?>

<header>
    <img src="../../assets/images/Logo.png" alt="PeerEd Logo">
    <nav>
        <a href="../../index.php">Home</a> | 
        <?php if ($role == "2"): ?>
            <a href="../search/search-tutors.php">View Tutors</a> | 
        <?php endif; ?>
        <a href="../appointments/view-appointments.php">Appointments</a> | 
        <a href="../messaging/chat.php">Messages</a> | 
        <a href="../settings/settings.php">Settings</a> |
    </nav>
</header>
