// DOM Elements
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');
const cancelBtn = document.getElementById('cancelBtn');
const editModal = document.getElementById('editModal');
const eidtAccountModal = document.getElementById('editAccountModal');
const editProfileForm = document.getElementById('editProfileForm');

// Profile Elements
const profileName = document.getElementById('profileName');
const profileRole = document.getElementById('profileRole');
const profileBio = document.getElementById('profileBio');
const profileImage = document.getElementById('profileImage');
const uploadPreview = document.getElementById('uploadPreview');

// Form Elements
const nameInput = document.getElementById('name');
const roleInput = document.getElementById('role');
const bioInput = document.getElementById('bio');

// Open Modal
openModalBtn.addEventListener('click', () => {
    editModal.classList.add('active');
    // Set current values to form inputs
    nameInput.value = profileName.textContent;
    roleInput.value = profileRole.textContent;
    bioInput.value = profileBio.textContent;
    uploadPreview.src = profileImage.src;
});

// Close Modal
function closeModal() {
    editModal.classList.remove('active');
}

closeModalBtn.addEventListener('click', closeModal);
cancelBtn.addEventListener('click', closeModal);

// Close modal when clicking outside
editModal.addEventListener('click', (e) => {
    if (e.target === editModal) {
        closeModal();
    }
});

// Form submission
editProfileForm.addEventListener('submit', (e) => {
    e.preventDefault();

    // Update profile with form values
    profileName.textContent = nameInput.value;
    profileRole.textContent = roleInput.value;
    profileBio.textContent = bioInput.value;
    profileImage.src = uploadPreview.src;

    // Close modal after saving
    closeModal();
});