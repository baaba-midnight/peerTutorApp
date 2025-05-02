// Change appointment status (used for Accept, Decline)
function updateStatus(appointmentId, newStatus) {
    fetch('../appointments/update_appointment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update_status&appointment_id=${appointmentId}&new_status=${newStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Appointment ${newStatus === 'confirmed' ? 'confirmed' : 'cancelled'} successfully.`);
            location.reload();
        } else {
            alert('Failed to update appointment status.');
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
    });
}

// Mark appointment as completed (used for "Completed" button)
function completedappointment(appointmentId) {
    if (confirm("Mark this session as completed?")) {
        fetch('../../models/update_appointment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=mark_completed&appointment_id=${appointmentId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Appointment marked as completed.");
                location.reload();
            } else {
                alert("Failed to complete the appointment.");
            }
        })
        .catch(error => {
            console.error("Error marking completed:", error);
        });
    }
}

// Redirect to rescheduling page
function rescheduleAppointment(appointmentId) {
    window.location.href = `reschedule.php?appointment_id=${appointmentId}`;
}

// Cancel appointment
function cancelAppointment(appointmentId) {
    if (confirm("Are you sure you want to cancel this appointment?")) {
        fetch('../../views/appointments/update_appointment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=cancel&appointment_id=${appointmentId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Appointment cancelled.");
                location.reload();
            } else {
                alert("Failed to cancel appointment.");
            }
        })
        .catch(error => {
            console.error('Error cancelling appointment:', error);
        });
    }
}


