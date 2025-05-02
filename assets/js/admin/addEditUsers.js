document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("userModalForm");
    const form = document.getElementById("registrationForm");
    const formTitle = document.getElementById("formTitle");
    const submitBtn = document.getElementById("submitBtn");
    const passwordFields = document.querySelectorAll(".password-group");
    const tutorOnlyFields = document.querySelectorAll(".tutor-only");
    const roleSelect = document.getElementById("roleSelect");
    const selectedRoleInput = document.getElementById("selectedRole");
    const bioField = document.querySelector(".bio");

    function toggleTutorFields(show) {
        tutorOnlyFields.forEach(field => {
            field.style.display = show ? "block" : "none";
        });
    }

    function togglePasswordFields(show) {
        passwordFields.forEach(field => {
            field.style.display = show ? "block" : "none";
        });
    }

    function toggleBioField(show) {
        bioField.style.display = show ? "block" : "none";
    }

    function populateForm(mode, userData = null) {
        form.reset();
        roleSelect.value = "";
        selectedRoleInput.value = "";
        toggleTutorFields(false);
        togglePasswordFields(mode !== "edit");

        if (mode === "edit" && userData) {
            document.getElementById("firstName").value = userData.first_name;
            document.getElementById("lastName").value = userData.last_name;
            document.getElementById("email").value = userData.email;
            document.getElementById("phone").value = userData.phone_number;
            document.getElementById("profilePicture").value = userData.profile_picture_url;
            document.getElementById("bio").value = userData.bio;

            roleSelect.value = userData.role;
            selectedRoleInput.value = userData.role;
            toggleTutorFields(userData.role === "tutor");

            const subjectSelect = document.getElementById("subjects");
            Array.from(subjectSelect.options).forEach(option => {
                option.selected = userData.subjects.includes(option.value);
            });

            formTitle.textContent = "Edit User";
            submitBtn.textContent = "Save Changes";
        } else {
            toggleBioField(false);
            formTitle.textContent = "Add User";
            submitBtn.textContent = "Add User";
        }

        modal.dataset.mode = mode;
    }

    // Listen for dropdown changes
    roleSelect.addEventListener("change", function () {
        const role = this.value;
        selectedRoleInput.value = role;
        toggleTutorFields(role === "tutor");
    });

    // Bootstrap modal show event
    document.getElementById('userModalForm').addEventListener('shown.bs.modal', function (event) {
        const button = event.relatedTarget;
        const mode = button.getAttribute("data-mode");
        const userId = button.getAttribute("data-user-id");

        modal.dataset.userId = userId; // Store user ID in modal data attribute

        if (mode === "edit" && userId) {
            (async () => {
                const response = await fetch(`${API_BASE_URL}?action=getUserById&user_id=${userId}`);
                const data = await response.json();

                if (!data.success) {
                    alert("Error fetching user data.");
                    return;
                }

                if (data.user.role === "tutor") {
                    toggleTutorFields(true);
                }
                populateForm("edit", data.user);
            })();
        } else {
            populateForm("add");
        }
    });

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const mode = modal.dataset.mode; // "add" or "edit"

        const isEdit = mode === "edit";
        const url = `${API_BASE_URL}?action=${isEdit ? "updateUser" : "addUser"}`;

        const formData = new FormData(form);
        formData.append("mode", mode);

        if (isEdit) {
            formData.append("user_id", modal.dataset.userId);
        }

        fetch(url, {
            method: "POST",
            body: formData,
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(isEdit ? "User updated successfully!" : "User added successfully!");

                    

                    // Wait a bit before reload
                    setTimeout(() => location.reload(), 300);

                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error("Submission error:", error);
                alert("There was a problem submitting the form.");
            });
    });
});
