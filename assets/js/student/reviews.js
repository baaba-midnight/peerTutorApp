// Open the review modal and set the appointment ID
function openReviewModal(appointmentId) {
    const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
    document.getElementById('reviewForm').dataset.appointmentId = appointmentId; // Store appointment ID in the form
    reviewModal.show();
}

// Handle review form submission
document.getElementById('reviewForm').addEventListener('submit', async (event) => {
    event.preventDefault(); // Prevent default form submission

    const form = event.target;
    const appointmentId = form.dataset.appointmentId; // Retrieve appointment ID
    const rating = form.querySelector('input[name="rating"]:checked'); // Get the selected rating

    if (!rating) {
        alert('Please select a rating before submitting.');
        return;
    }

    const formData = new FormData(form);
    formData.append("appointment_id", appointmentId); // Append appointment ID to form data

    try {
        const response = await fetch(`../../api/leave_review.php`, {
            method: 'POST',
            body: formData,
        });
        const result = await response.json();

        console.log('Review submission response:', result); // Log the response for debugging

        if (result.success === true) {
            console.log(`Review for appointment ${appointmentId} submitted successfully.`);
            const reviewModal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
            reviewModal.hide(); // Close the modal
            loadAppointments(document.querySelector('#userId').value, document.querySelector('userRole')); // Reload appointments
        } else {
            console.error('Failed to submit review:', result.message);
            alert('Failed to submit review. Please try again.');
        }
    } catch (error) {
        console.error('Error submitting review:', error);
        alert('An error occurred while submitting the review.');
    }
});