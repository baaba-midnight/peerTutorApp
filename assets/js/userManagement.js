// user sample data
const users = [
  {
    id: 1,
    name: "John Smith",
    email: "john.smith@example.com",
    role: "student",
    status: "active",
    lastLogin: "2 hours ago",
    joinedDate: "15 Mar, 2025",
  },
  {
    id: 2,
    name: "Sarah Johnson",
    email: "sarah.j@example.com",
    role: "tutor",
    status: "active",
    lastLogin: "5 hours ago",
    joinedDate: "10 Feb, 2025",
  },
  {
    id: 3,
    name: "Michael Brown",
    email: "m.brown@example.com",
    role: "admin",
    status: "active",
    lastLogin: "1 day ago",
    joinedDate: "05 Jan, 2025",
  },
  {
    id: 4,
    name: "Emma Wilson",
    email: "emma.w@example.com",
    role: "applicant",
    status: "pending",
    lastLogin: "Never",
    joinedDate: "28 Mar, 2025",
  },
  {
    id: 5,
    name: "David Lee",
    email: "david.l@example.com",
    role: "applicant",
    status: "pending",
    lastLogin: "Never",
    joinedDate: "15 Jan, 2025",
  },
];

let currentUserId = null;

// populate the user table with sample data
function populateUserTable() {
  const tableBody = document.querySelector("#userTable tbody");
  tableBody.innerHTML = ""; // Clear existing rows

  users.forEach((user) => {
    const row = document.createElement("tr");

    // generate initials from name
    const nameParts = user.name.split(" ");
    const initials = nameParts
      .map((part) => part.charAt(0))
      .join("")
      .toUpperCase();

    // Basic user info column
    const userInfoColumn = document.createElement("td");
    userInfoColumn.innerHTML = `
        <div class="user-info">
            <div class="avatar">${initials}</div>
            <div class="user-details">
                <span class="user-name">${user.name}</span>
                <span class="user-email">${user.email}</span>
            </div>
        </div>
        `;
    row.appendChild(userInfoColumn);

    // Role column
    const roleCell = document.createElement("td");
    const roleName = user.role.charAt(0).toUpperCase() + user.role.slice(1);
    roleCell.innerHTML = `<span class="role-badge ${user.role}">${roleName}</span>`;
    row.appendChild(roleCell);

    // Status column
    const statusCell = document.createElement("td");
    statusCell.innerHTML = `
          <div class="status ${user.status}">
            <span class="status-dot"></span>
            ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
          </div>
        `;
    row.appendChild(statusCell);

    // Last login column
    const lastLoginCell = document.createElement("td");
    lastLoginCell.textContent = user.lastLogin;
    row.appendChild(lastLoginCell);

    // Joined date column
    const joinedDateCell = document.createElement("td");
    joinedDateCell.textContent = user.joinedDate;
    row.appendChild(joinedDateCell);

    // Actions column - different actions based on role
    const actionsCell = document.createElement("td");
    actionsCell.className = "actions";

    if (user.role === "applicant") {
      // Applicant actions
      actionsCell.innerHTML = `
            <button class="action-btn" title="View Details">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z" fill="white"/>
              </svg>
            </button>
            <button class="action-btn approve-btn" title="Approve" onclick="approveTutor(${user.id})">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 16.17L4.83 12L3.41 13.41L9 19L21 7L19.59 5.59L9 16.17Z" fill="white"/>
              </svg>
            </button>
            <button class="action-btn reject-btn" title="Reject" onclick="rejectModal(${user.id})" data-bs-toggle="modal" data-bs-target="#rejectModal">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z" fill="white"/>
              </svg>
            </button>
          `;
    } else {
      // Regular user actions
      actionsCell.innerHTML = `
            <button class="action-btn" title="View Details">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z" fill="white"/>
              </svg>
            </button>
            <button class="action-btn" title="Edit">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 17.25V21H6.75L17.81 9.94L14.06 6.19L3 17.25ZM20.71 7.04C21.1 6.65 21.1 6.02 20.71 5.63L18.37 3.29C17.98 2.9 17.35 2.9 16.96 3.29L15.13 5.12L18.88 8.87L20.71 7.04Z" fill="white"/>
              </svg>
            </button>
            <div class="dropdown">
              <button class="action-btn" title="More">⋯</button>
              <div class="dropdown-content">
                <button onclick="resetPassword(${
                  user.id
                })">Reset Password</button>
                ${
                  user.status === "active"
                    ? `<button onclick="disableUser(${user.id})">Disable Account</button>`
                    : `<button onclick="enableUser(${user.id})">Enable Account</button>`
                }
                ${
                  user.role === "tutor"
                    ? `<button onclick="viewStudents(${user.id})">View Students</button>`
                    : ""
                }
                ${
                  user.role === "tutor"
                    ? `<button onclick="viewCredentials(${user.id})">View Credentials</button>`
                    : ""
                }
                <button class="danger" onclick="deleteUser(${
                  user.id
                })">Delete</button>
              </div>
            </div>
          `;
    }

    row.appendChild(actionsCell);
    tableBody.appendChild(row);
  });
}

let selectedUserId = null;

// User actions functions
function deleteUser(userId) {
  selectedUserId = userId; // set user ID to delete

  console.log(selectedUserId)

  // Show the modal
  const deleteModal = new bootstrap.Modal(
    document.getElementById("deleteModal")
  );
  deleteModal.show();
}

function confirmDelete() {
  const input = document.getElementById("confirmDeleteInput").value.trim();

  if (input !== "DELETE") {
    alert("You must type DELETE to confirm.");
    return;
  }

  if (!selectedUserId) return;

  // send API request to delete user

  // For demo purposes, remove from the array
  const index = users.findIndex((user) => user.id === parseInt(selectedUserId));
  if (index !== -1) {
    users.splice(index, 1);
    populateUserTable();
  }

  selectedUserId = null; // reset user ID

  // Hide the modal
  const modalEl = document.getElementById("deleteModal");
  const modalInstance = bootstrap.Modal.getInstance(modalEl);
  modalInstance.hide();
}

function approveTutor(userId) {
  console.log(`Approving tutor application for user ID: ${userId}`);
  // In a real application, you would send an API request here

  // For demo purposes, update the user's role and status
  const user = users.find((user) => user.id === userId);
  if (user) {
    user.role = "tutor";
    user.status = "active";
    populateUserTable();
  }
}

function rejectModal(userId) {
  selectedUserId = userId; // set user ID to reject

  const rejectModal = new bootstrap.Modal(
    document.getElementById("rejectModal")
  );

  rejectModal.show();
}

function rejectTutor() {
  const reason = document.getElementById("rejection-reason").value;
  console.log(
    `Rejecting tutor application for user ID: ${selectedUserId} with reason: ${reason}`
  );
  // In a real application, you would send an API request here

  // For demo purposes, remove from the array or update status
  const user = users.find((user) => user.id === selectedUserId);
  if (user) {
    user.status = "inactive";
    populateUserTable();
  }

  // Clear the textarea
  document.getElementById("rejection-reason").value = "";

  selectedUserId = null;

  // Hide the modal
  const modalEl = document.getElementById("rejectModal");
  const modalInstance = bootstrap.Modal.getInstance(modalEl);
  modalInstance.hide();
}

function resetPassword(userId) {
  console.log(`Resetting password for user ID: ${userId}`);
  // In a real application, you would send an API request here
  alert(`Password reset email sent to user ID: ${userId}`);
}

function disableUser(userId) {
  console.log(`Disabling user ID: ${userId}`);
  // In a real application, you would send an API request here

  // For demo purposes, update the user's status
  const user = users.find((user) => user.id === userId);
  if (user) {
    user.status = "inactive";
    populateUserTable();
  }
}

function enableUser(userId) {
  console.log(`Enabling user ID: ${userId}`);
  // In a real application, you would send an API request here

  // For demo purposes, update the user's status
  const user = users.find((user) => user.id === userId);
  if (user) {
    user.status = "active";
    populateUserTable();
  }
}

function viewStudents(userId) {
  console.log(`Viewing students for tutor ID: ${userId}`);
  // In a real application, you would navigate to a new page or open a modal
  alert(`Viewing students for tutor ID: ${userId}`);
}

function viewCredentials(userId) {
  console.log(`Viewing credentials for tutor ID: ${userId}`);
  // In a real application, you would navigate to a new page or open a modal
  alert(`Viewing credentials for tutor ID: ${userId}`);
}

document.addEventListener("DOMContentLoaded", populateUserTable);
