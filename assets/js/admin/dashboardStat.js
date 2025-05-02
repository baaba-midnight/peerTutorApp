document.addEventListener("DOMContentLoaded", function () {
    fetch('../../api/dashboardStats.php')
        .then(response => response.json())
        .then(data => {
            // Update Stat Cards
            document.querySelectorAll('.stat-card-body .stat-number')[0].textContent = data.activeTutors;
            document.querySelectorAll('.stat-card-body .stat-number')[1].textContent = data.activeStudents;
            document.querySelectorAll('.stat-card-body .stat-number')[2].textContent = data.completedSessions;
            document.querySelectorAll('.stat-card-body .stat-number')[3].textContent = parseFloat(data.avgRating).toFixed(1);

            // Update Top Tutors
            const topTutorsContainer = document.querySelector('.card-body.p-0');
            topTutorsContainer.innerHTML = ''; // Clear existing entries

            data.topTutors.forEach(tutor => {
                const initials = `${tutor.first_name[0]}${tutor.last_name[0]}`;
                const tutorDiv = `
                    <div class="top-tutor p-3">
                        <div class="user-avatar">${initials}</div>
                        <div class="tutor-info">
                            <p class="tutor-name">${tutor.first_name} ${tutor.last_name}</p>
                            <p class="tutor-subject">N/A</p>
                        </div>
                        <div class="tutor-rating">
                            <i class="fas fa-star"></i> ${tutor.overall_rating}
                        </div>
                    </div>
                `;
                topTutorsContainer.innerHTML += tutorDiv;
            });
        })
        .catch(error => console.error('Error loading dashboard stats:', error));
});
