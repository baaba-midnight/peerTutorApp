const API_BASE_URL = '../../api/tutor-appointments.php';

// main data loader
async function loadStats(tutorId) {
    try {
        const response = await fetch(`${API_BASE_URL}?action=getDashboardData&user_id=${tutorId}&limit=5`);
        const data = await response.json();

        const sessions = data.sessions;
        const messages = data.messages;
        const reviews = data.reviews;

        updateDashboardCards({ sessions, messages, reviews });
    } catch (error) {
        console.error('Error', error);
        updateDashboardCards({ data: [] });
    }
}

function formatDate(dateString) {
    ;
    const dateObj = new Date(dateString);

    // Extract date in YYYY-MM-DD format
    const date = dateObj.toISOString().split('T')[0];

    // Extract time in HH:MM format
    const time = dateObj.toTimeString().split(' ')[0].slice(0, 5);  // "14:30"

    return `${date} - ${time}`;
}

function renderStars(rating) {
    let html = '';
    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);

    for (let i = 0; i < fullStars; i++) {
        html += '<i class="fas fa-star rating-star"></i>'; // full star
    }
    if (halfStar) {
        html += '<i class="fas fa-star-half-alt rating-star"></i>'; // half star
    }
    for (let i = 0; i < emptyStars; i++) {
        html += '<i class="far fa-star rating-star"></i>'; // empty star
    }

    return html;
}


function updateDashboardCards(data) {

    // add upcoming appointments to card
    const upcomingAppointments = document.querySelector('#upcomingAppointments');
    upcomingAppointments.innerHTML = '';
    const upcomingSessions = data.sessions.upcoming;

    if (upcomingSessions.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 6;
        cell.className = 'text-center text-muted';
        cell.textContent = 'No upcoming appointments';
        row.appendChild(cell);
        upcomingAppointments.appendChild(row);
    } else if (upcomingSessions.length > 0) {
        upcomingAppointments.innerHTML = upcomingSessions.map(
            appointment => `
            <div class="card-section">
                <div class="card-info">
                    <p class="title">Session with ${appointment.student_name}</p>
                    <div class="title-meta">
                        <p>${appointment.course_code} - ${appointment.course_name}</p>
                        <p>${formatDate(appointment.start_datetime)}</p>
                    </div>
                </div>
                <div class="card-action">
                    <button class="btn appointment-status">${appointment.status}</button>
                    <button class="btn appointment-join">Start Session</button>
                </div>
            </div>`).join('');
    }

    // update recent messages card
    const recentMessages = document.querySelector('#recentMessages');
    recentMessages.innerHTML = '';

    if (data.messages.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 6;
        cell.className = 'text-center text-muted';
        cell.textContent = 'No recent messages';
        row.appendChild(cell);
        recentMessages.appendChild(row);
    } else if (data.messages.length > 0) {
        recentMessages.innerHTML = data.messages.map(
            message => `
            <div class="card-section">
                <div class="card-info">
                    <p class="title">Message from ${message.student_name}</p>
                    <div class="title-meta">
                        <p>${message.content}</p>
                        <p>@ ${message.created_at}</p>
                    </div>
                </div>
                <div class="card-action">
                    <button class="btn appointment-join">Reply</button>
                </div>
            </div>`).join('');
    }

    // update recent reviews card
    const recentReviews = document.querySelector('#recentReviews');
    recentReviews.innerHTML = '';

    if (data.reviews.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 6;
        cell.className = 'text-center text-muted';
        cell.textContent = 'No recent reviews';
        row.appendChild(cell);
        recentReviews.appendChild(row);
    } else if (data.reviews.length > 0) {
        recentReviews.innerHTML = data.reviews.map(
            review => `
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-info">
                            <div class="avatar">${review.student_name
                                .split(' ')
                                .map(word => word.charAt(0).toUpperCase())
                                .join('')
                              }</div>
                            <div class="user-details">
                                <span class="user-name">${review.student_name}</span>
                                <span class="user-email">${review.student_email}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="rating-stars">
                        ${renderStars(review.rating)}
                    </div>
                </td>
                <td>
                    <div class="review-content">${review.comment}</div>
                </td>
                <td>${formatDate(review.created_at)}</td>
            </tr>
        `).join('');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const tutorId = document.querySelector('#userId').value; // Get the tutor ID from a hidden input field or similar
    loadStats(tutorId);
});