function viewDetails(userId) {
    // Find the user by ID
    fetch(`${API_BASE_URL}?action=getUserById&user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.user) {
          const user = data.user;

          console.log(user);
          
          // Get modal element
          const modal = document.getElementById("userDetails");
          
          // Update modal title based on user role
          const modalTitle = modal.querySelector(".modal-title");
          
          // Update modal content based on user role
          const modalBody = modal.querySelector(".modal-body");
          
          // Clear previous content
          modalBody.innerHTML = "";
          
          // Common user info section
          const userInfoSection = document.createElement("div");
          userInfoSection.className = "user-info-section";
          
          // Create content based on user role
          if (user.role === "tutor") {
            modalTitle.textContent = "Tutor Details";
            
            // Tutor specific information
            userInfoSection.innerHTML = `
              <div class="user-profile-header">
                <div class="avatar-large">${user.first_name.charAt(0)}${user.last_name.charAt(0)}</div>
                <div class="user-info-main">
                  <h3>${user.first_name} ${user.last_name}</h3>
                  <span class="role-badge tutor">Tutor</span>
                  <p>${user.email}</p>
                </div>
              </div>
              
              <div class="detail-section">
                <h4>Personal Information</h4>
                <div class="detail-row">
                  <span class="detail-label">Phone:</span>
                  <span class="detail-value">${user.phone_number || "Not provided"}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Department:</span>
                  <span class="detail-value">${user.department || "Not provided"}</span>
                </div>
              </div>
              
              <div class="detail-section">
                <h4>Tutor Information</h4>
                <div class="detail-row">
                  <span class="detail-label">Subjects:</span>
                  <span class="detail-value">${user.courses || "Not specified"}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Year of Study:</span>
                  <span class="detail-value">${user.year_of_study || "Not specified"}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Experience:</span>
                  <span class="detail-value">${user.experience || "Not specified"}</span>
                </div>
              </div>
              
              <div class="detail-section">
                <h4>Statistics</h4>
                <div class="detail-row">
                  <span class="detail-label">Rating:</span>
                  <span class="detail-value">${user.overall_rating || "No ratings yet"}</span>
                </div>
              </div>
            `;
            
            // // Add a button to view credentials
            // const credentialsBtn = document.createElement("button");
            // credentialsBtn.className = "btn btn-primary mt-3";
            // credentialsBtn.textContent = "View Credentials";
            // credentialsBtn.onclick = () => viewCredentials(userId);
            // userInfoSection.appendChild(credentialsBtn);
            
          } else if (user.role === "student") {
            modalTitle.textContent = "Student Details";
            
            // Student specific information
            userInfoSection.innerHTML = `
              <div class="user-profile-header">
                <div class="avatar-large">${user.first_name.charAt(0)}${user.last_name.charAt(0)}</div>
                <div class="user-info-main">
                  <h3>${user.first_name} ${user.last_name}</h3>
                  <span class="role-badge student">Student</span>
                  <p>${user.email}</p>
                </div>
              </div>
              
              <div class="detail-section">
                <h4>Personal Information</h4>
                <div class="detail-row">
                  <span class="detail-label">Phone:</span>
                  <span class="detail-value">${user.phone_number || "Not provided"}</span>
                </div>
              </div>
              
              <div class="detail-section">
                <h4>Learning Information</h4>
                <div class="detail-row">
                  <span class="detail-label">Education Level:</span>
                  <span class="detail-value">${user.education_level || "Not specified"}</span>
                </div>
              </div>
            `;
            
          } else if (user.role === "admin") {
            modalTitle.textContent = "Admin Details";
            
            // Admin specific information
            userInfoSection.innerHTML = `
              <div class="user-profile-header">
                <div class="avatar-large">${user.first_name.charAt(0)}${user.last_name.charAt(0)}</div>
                <div class="user-info-main">
                  <h3>${user.first_name} ${user.last_name}</h3>
                  <span class="role-badge admin">Admin</span>
                  <p>${user.email}</p>
                </div>
              </div>
              
              <div class="detail-section">
                <h4>Personal Information</h4>
                <div class="detail-row">
                  <span class="detail-label">Phone:</span>
                  <span class="detail-value">${user.phone_number || "Not provided"}</span>
                </div>

              </div>
            `;
            
          } else if (user.role === "applicant") {
            modalTitle.textContent = "Applicant Details";
            let is_verified = "rejected";

            if (user.is_verified == 1) {
                is_verified = "completed";
            }
            
            // Applicant specific information
            userInfoSection.innerHTML = `
              <div class="user-profile-header">
                <div class="avatar-large">${user.first_name.charAt(0)}${user.last_name.charAt(0)}</div>
                <div class="user-info-main">
                  <h3>${user.first_name} ${user.last_name}</h3>
                  <span class="role-badge applicant">Applicant</span>
                  <p>${user.email}</p>
                </div>
              </div>
              
              <div class="detail-section">
                <h4>Personal Information</h4>
                <div class="detail-row">
                  <span class="detail-label">Phone:</span>
                  <span class="detail-value">${user.phone_number || "Not provided"}</span>
                </div>
              </div>
              
              <div class="detail-section">
                <h4>Application Information</h4>
                <div class="detail-row">
                  <span class="detail-label">Education:</span>
                  <span class="detail-value">${user.education || "Not specified"}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Experience:</span>
                  <span class="detail-value">${user.experience || "Not specified"}</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Application Status:</span>
                  <span class="detail-value application-${user.is_verified || 'pending'}">${user.application_status || "Pending"}</span>
                </div>
              </div>
            `;
            
            // Add action buttons for applicants
            const actionButtonsDiv = document.createElement("div");
            actionButtonsDiv.className = "application-actions mt-3";
            
            // Approve button
            const approveBtn = document.createElement("button");
            approveBtn.className = "btn btn-success";
            approveBtn.textContent = "Approve Application";
            approveBtn.onclick = () => {
              // Hide this modal
              const modalInstance = bootstrap.Modal.getInstance(modal);
              modalInstance.hide();
              
              // Show approve modal
              approveTutor(userId);
            };
            
            // Reject button
            const rejectBtn = document.createElement("button");
            rejectBtn.className = "btn btn-danger ms-2";
            rejectBtn.textContent = "Reject Application";
            rejectBtn.onclick = () => {
              // Hide this modal
              const modalInstance = bootstrap.Modal.getInstance(modal);
              modalInstance.hide();
              
              // Show reject modal
              rejectModal(userId);
            };
            
            // View credentials button
            const viewCredsBtn = document.createElement("button");
            viewCredsBtn.className = "btn btn-primary ms-2";
            viewCredsBtn.textContent = "View Credentials";
            viewCredsBtn.onclick = () => viewCredentials(userId);
            
            actionButtonsDiv.appendChild(approveBtn);
            actionButtonsDiv.appendChild(rejectBtn);
            actionButtonsDiv.appendChild(viewCredsBtn);
            userInfoSection.appendChild(actionButtonsDiv);
          }
          
          // Add the user info section to the modal body
          modalBody.appendChild(userInfoSection);
          
          // Show the modal
          const modalInstance = new bootstrap.Modal(modal);
          modalInstance.show();
        } else {
          // Handle error
          console.error("Could not fetch user details");
          alert("Could not load user details. Please try again.");
        }
      })
      .catch(error => {
        console.error("Error fetching user details:", error);
        alert("An error occurred while fetching user details.");
      });
  }