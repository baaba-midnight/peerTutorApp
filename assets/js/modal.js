// Open modal and set current user
function openModal(modalId, userId) {
  currentUserId = userId;
  document.getElementById(modalId).style.display = "block";
}

// Close modal
function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

// Close modals when clicking on close button or outside the modal
document.querySelectorAll(".close-modal").forEach((button) => {
  button.addEventListener("click", function () {
    const modal = this.closest(".modal");
    modal.style.display = "none";
  });
});

window.addEventListener("click", function (event) {
  document.querySelectorAll(".modal").forEach((modal) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
});
