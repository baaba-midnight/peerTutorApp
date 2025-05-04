const API_BASE_URL = '../../api/tutor-appointments.php';

async function loadAppointments(tutorId) {
    try {
        const response = await fetch(`${API_BASE_URL}?action=getAppointments&tutor_id=${parseInt(tutorId)}`);
        const data = await response.json();

        console.log(data);

        if (data.status !== 'success') {
            console.error('Error fetching appointments');
            return;
        }
        

        updateAppointmentsCards(data.appointments);
    } catch (error) {
        console.error('Error', error);
        updateAppointmentsCards({ data: [] });
    }
}

function updateAppointmentsCards(data) {
    const upcomingAppointments = document.querySelector('#upcomingAppointments');
    const pastAppointments = document.querySelector('#pastAppointments');
    const pendingAppointments = document.querySelector('#pendingAppointments');

    // Clear existing cards
    upcomingAppointments.innerHTML = '';
    pastAppointments.innerHTML = '';
    if (pendingAppointments) pendingAppointments.innerHTML = '';

    // Helper function to create a card
    const createCard = (appointment, statusClass, statusLabel, actions = '') => {
        const card = document.createElement('div');
        card.className = 'col-md-6';
        card.innerHTML = `
            <div class="card appointment-card h-100" data-appointment-id="${appointment.appointment_id}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">${appointment.course_name} Session</h5>
                            <p class="text-muted mb-0">with ${appointment.student_name}</p>
                        </div>
                        <span class="badge ${statusClass} status-badge">${statusLabel}</span>
                    </div>
                    <div class="mb-3">
                        <p class="mb-1">
                            <i class="bi bi-calendar"></i>
                            ${new Date(appointment.start_datetime).toLocaleDateString()}
                        </p>
                        <p class="mb-1">
                            <i class="bi bi-clock"></i>
                            ${new Date(appointment.start_datetime).toLocaleTimeString()} - ${new Date(appointment.end_datetime).toLocaleTimeString()}
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-tag"></i>
                            ${appointment.notes || 'No additional notes'}
                        </p>
                    </div>
                    <div class="d-flex appointment-actions">
                        ${actions}
                    </div>
                </div>
            </div>
        `;
        return card;
    };

    // Populate Pending Appointments
    if (data.pending && data.pending.length > 0 && pendingAppointments) {
        data.pending.forEach((appointment) => {
            const actions = `
                <button class="btn btn-success flex-grow-1" onclick="markAsUpcoming(${appointment.appointment_id})">Mark as Upcoming</button>
                <button class="btn btn-outline-danger" onclick="cancelSession(${appointment.appointment_id})">Cancel</button>
            `;
            const card = createCard(appointment, 'bg-warning', 'Pending', actions);
            pendingAppointments.appendChild(card);
        });
    } else if (pendingAppointments) {
        const noDataMessage = document.createElement('div');
        noDataMessage.className = 'text-center text-muted';
        noDataMessage.textContent = 'No pending appointments.';
        pendingAppointments.appendChild(noDataMessage);
    }

    // Populate Upcoming Appointments
    if (data.upcoming && data.upcoming.length > 0) {
        data.upcoming.forEach((appointment) => {
            const actions = `
                <button class="btn btn-primary flex-grow-1" onclick="markAsCompleted(${appointment.appointment_id})">Mark as Completed</button>
                <button class="btn btn-outline-danger" onclick="cancelSession(${appointment.appointment_id})">Cancel</button>
            `;
            const card = createCard(appointment, 'bg-primary', 'Upcoming', actions);
            upcomingAppointments.appendChild(card);
        });
    } else {
        const noDataMessage = document.createElement('div');
        noDataMessage.className = 'text-center text-muted';
        noDataMessage.textContent = 'No upcoming appointments.';
        upcomingAppointments.appendChild(noDataMessage);
    }

    // Populate Past Appointments
    if (data.past && data.past.length > 0) {
        data.past.forEach((appointment) => {
            const card = createCard(appointment, 'bg-success', 'Completed');
            pastAppointments.appendChild(card);
        });
    } else {
        const noDataMessage = document.createElement('div');
        noDataMessage.className = 'text-center text-muted';
        noDataMessage.textContent = 'No past appointments.';
        pastAppointments.appendChild(noDataMessage);
    }
}

async function markAsUpcoming(appointmentId) {
    const formData = new FormData();
    formData.append('action', 'updateAppointmentStatus');
    formData.append('appointment_id', appointmentId);
    formData.append('status', 'confirmed');

    try {
        const response = await fetch(API_BASE_URL, {
            method: 'POST',
            body: formData,
        });
        const result = await response.json();
        if (result.status === 'success') {
            console.log(`Appointment ${appointmentId} marked as upcoming.`);
            loadAppointments(document.querySelector('#tutorId').value); // Reload appointments
        } else {
            console.error('Failed to mark as upcoming:', result.message);
        }
    } catch (error) {
        console.error('Error marking as upcoming:', error);
    }
}

async function markAsCompleted(appointmentId) {
    const formData = new FormData();
    formData.append('action', 'updateAppointmentStatus');
    formData.append('appointment_id', appointmentId);
    formData.append('status', 'completed'); // Assuming 'completed' is the status for completed appointments

    try {
        const response = await fetch(API_BASE_URL, {
            method: 'POST',
            body: formData,
        });
        const result = await response.json();
        if (result.status === 'success') {
            console.log(`Appointment ${appointmentId} marked as completed.`);
            loadAppointments(document.querySelector('#tutorId').value); // Reload appointments
        } else {
            console.error('Failed to mark as completed:', result.message);
        }
    } catch (error) {
        console.error('Error marking as completed:', error);
    }
}

async function cancelSession(appointmentId) {
    if (!confirm('Are you sure you want to cancel this session? This action cannot be undone.')) {
        return; // Exit if the user cancels
    }

    const formData = new FormData();
    formData.append('action', 'updateAppointmentStatus');
    formData.append('appointment_id', appointmentId);
    formData.append('status', 'cancelled');

    try {
        const response = await fetch(API_BASE_URL, {
            method: 'POST',
            body: formData,
        });
        const result = await response.json();
        if (result.status === 'success') {
            console.log(`Appointment ${appointmentId} canceled.`);
            loadAppointments(document.querySelector('#tutorId').value); // Reload appointments
        } else {
            console.error('Failed to cancel session:', result.message);
        }
    } catch (error) {
        console.error('Error canceling session:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const tutorId = document.querySelector('#tutorId').value; 
    if (tutorId) {
        console.log('Tutor ID:', tutorId);
        loadAppointments(tutorId);
    } else {
        console.error('Tutor ID not found in URL.');
    }
});
