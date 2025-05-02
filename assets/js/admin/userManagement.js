const API_BASE_URL = "../../api/UserManagementController.php"; // Replace with your actual API URL

let currentUserId = null;
let currentPage = 1; // Current page number
let pageSize = 5; // Number of records per page
let totalRecords = 0; // Total number of records
let totalFilteredRecords = 0; // Total number of filtered records

async function loadUsers() {
  try {
    const start = (currentPage - 1) * pageSize;
    const response = await fetch(
      `${API_BASE_URL}?action=getFilteredUsers&start${start}&length=${pageSize}`
    );
    const data = await response.json();

    populateUserTable(data.data);
    totalRecords = data.total;
    totalFilteredRecords = data.filteredTotal;
    updatePaginationControls();
  } catch (error) {
    console.error("Error:", error);
    populateUserTable({ data: [], recordsTotal: 0, recordsFiltered: 0 });
  }
}

// populate the user table with sample data
function populateUserTable(data) {
  const tableBody = document.querySelector("#userTable tbody");
  tableBody.innerHTML = ""; // Clear existing rows

  if (data.length === 0) {
    const row = document.createElement("tr");
    const cell = document.createElement("td");
    cell.colSpan = 6;
    cell.className = "text-center text-muted";
    cell.textContent = "No data available";
    row.appendChild(cell);
    tableBody.appendChild(row);
    return;
  }

  data.forEach((user) => {
    const row = document.createElement("tr");
	let name = user.first_name + " " + user.last_name

    // Generate initials from name
    const nameParts = [user.first_name, user.last_name];
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
              <span class="user-name">${name}</span>
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
    let is_active = "active";

    if (user.is_active === 0) {
      is_active = "inactive";
    }

    statusCell.innerHTML = `
      <div class="status ${is_active.toLowerCase()}">
        <span class="status-dot"></span>
        ${is_active.charAt(0).toUpperCase() + is_active.slice(1)}
      </div>
    `;
    row.appendChild(statusCell);

    // Last login column
    const lastLoginCell = document.createElement("td");
    lastLoginCell.textContent = user.last_login || "N/A";
    row.appendChild(lastLoginCell);

    // Joined date column
    const joinedDateCell = document.createElement("td");
    joinedDateCell.textContent = user.created_at || "N/A";
    row.appendChild(joinedDateCell);

    // Actions column
    const actionsCell = document.createElement("td");
    actionsCell.className = "actions";

    if (user.role === "applicant") {
      // Applicant actions
      actionsCell.innerHTML = `
        <button class="action-btn" title="View Details" onclick="viewDetails(${user.user_id})">
          <i class="bi bi-eye-fill"></i>
        </button>
        <button class="action-btn approve-btn" title="Approve" onclick="approveTutor(${user.user_id})" data-bs-toggle="modal" data-bs-target="#approveModal">
          <i class="bi bi-check2"></i>
        </button>
        <button class="action-btn reject-btn" title="Reject" onclick="rejectModal(${user.user_id})" data-bs-toggle="modal" data-bs-target="#rejectModal">
          <i class="bi bi-x-lg"></i>
        </button>
      `;
    } else {
      // Regular user actions
      actionsCell.innerHTML = `
        <button class="action-btn" title="View Details" onclick="viewDetails(${user.user_id})">
          <i class="bi bi-eye-fill"></i>
        </button>
        <button class="action-btn open-user-modal" title="Edit" data-mode="edit" data-bs-toggle="modal" data-bs-target="#userModalForm" data-user-id="${user.user_id})">
          <i class="bi bi-pencil-square"></i>
        </button>
        <div class="dropdown">
          <button class="action-btn" title="More">⋯</button>
          <div class="dropdown-content">
            <button onclick="resetPassword(${user.user_id})">Reset Password</button>
            ${
              user.status === "active"
                ? `<button onclick="disableUser(${user.user_id})">Disable Account</button>`
                : `<button onclick="enableUser(${user.user_id})">Enable Account</button>`
            }
            ${
              user.role === "tutor"
                ? `<button onclick="viewCredentials(${user.user_id})">View Credentials</button>`
                : ""
            }
            <button class="danger" onclick="deleteUser(${user.user_id})">Delete</button>
          </div>
        </div>
      `;
    }

    row.appendChild(actionsCell);
    tableBody.appendChild(row);
  });
}

function updatePaginationControls() {
  const paginationEl = document.getElementById("pagination");
  const totalPages = Math.ceil(totalRecords / pageSize);

  paginationEl.innerHTML = ""; // Clear existing pagination

  paginationEl.innerHTML = `
	<div class="page-info">Showing ${((currentPage - 1) * pageSize) + 1}-${currentPage * pageSize > totalRecords ? totalRecords : currentPage * pageSize} of ${totalRecords}</div>
	<div class="page-buttons">
		<button class="page-btn" ${currentPage === 1 ? "disabled" : ""} id="prevBtn"><</button>
		${generatePageNumbers(currentPage, totalPages)}
		<button class="page-btn" ${currentPage === totalPages ? "disabled" : ""} id="nextBtn">></button>
	</div>
  `;

  document.getElementById("prevBtn").addEventListener("click", () => {
	currentPage--;
	loadUsers();
  });

  document.getElementById("nextBtn").addEventListener("click", () => {
	currentPage++;
	loadUsers();
  });
}

function generatePageNumbers(current, total) {
	  if (total <= 7) {
		return Array.from({ length: total }, (_, i) => i + 1)
		  .map((p) => `<button class="page-btn ${p === current ? "active" : ""}">${p}</button>`)
		  .join("");
	}
}

// // add User Modal
// function addUser() {

// }

// let selectedUserId = null;

// // User actions functions
// function deleteUser(userId) {
//   selectedUserId = userId; // set user ID to delete

//   console.log(selectedUserId)

//   // Show the modal
//   const deleteModal = new bootstrap.Modal(
//     document.getElementById("deleteModal")
//   );
//   deleteModal.show();
// }

// function confirmDelete() {
//   const input = document.getElementById("confirmDeleteInput").value.trim();

//   if (input !== "DELETE") {
//     alert("You must type DELETE to confirm.");
//     return;
//   }

//   if (!selectedUserId) return;

//   // send API request to delete user

//   // For demo purposes, remove from the array
//   const index = users.findIndex((user) => user.id === parseInt(selectedUserId));
//   if (index !== -1) {
//     users.splice(index, 1);
//     populateUserTable();
//   }

//   selectedUserId = null; // reset user ID

//   // Hide the modal
//   const modalEl = document.getElementById("deleteModal");
//   const modalInstance = bootstrap.Modal.getInstance(modalEl);
//   modalInstance.hide();
// }

// function approveTutor(userId) {
//   // get approve button element from modal
//   document.getElementById("approveBtn").addEventListener("click", function () {
//     console.log(`Approving tutor application for user ID: ${userId}`);
//     // In a real application, you would send an API request here
//   });

//   // For demo purposes, update the user's role and status
//   const user = users.find((user) => user.id === userId);
//   if (user) {
//     user.role = "tutor";
//     user.status = "active";
//     populateUserTable();
//   }
// }

// function rejectModal(userId) {
//   selectedUserId = userId; // set user ID to reject

//   const rejectModal = new bootstrap.Modal(
//     document.getElementById("rejectModal")
//   );

//   rejectModal.show();
// }

// function rejectTutor() {
//   const reason = document.getElementById("rejection-reason").value;
//   console.log(
//     `Rejecting tutor application for user ID: ${selectedUserId} with reason: ${reason}`
//   );
//   // In a real application, you would send an API request here

//   // For demo purposes, remove from the array or update status
//   const user = users.find((user) => user.id === selectedUserId);
//   if (user) {
//     user.status = "inactive";
//     populateUserTable();
//   }

//   // Clear the textarea
//   document.getElementById("rejection-reason").value = "";

//   selectedUserId = null;

//   // Hide the modal
//   const modalEl = document.getElementById("rejectModal");
//   const modalInstance = bootstrap.Modal.getInstance(modalEl);
//   modalInstance.hide();
// }

// function resetPassword(userId) {
//   console.log(`Resetting password for user ID: ${userId}`);
//   // In a real application, you would send an API request here
//   alert(`Password reset email sent to user ID: ${userId}`);
// }

// function disableUser(userId) {
//   console.log(`Disabling user ID: ${userId}`);
//   // In a real application, you would send an API request here

//   // For demo purposes, update the user's status
//   const user = users.find((user) => user.id === userId);
//   if (user) {
//     user.status = "inactive";
//     populateUserTable();
//   }
// }

// function enableUser(userId) {
//   console.log(`Enabling user ID: ${userId}`);
//   // In a real application, you would send an API request here

//   // For demo purposes, update the user's status
//   const user = users.find((user) => user.id === userId);
//   if (user) {
//     user.status = "active";
//     populateUserTable();
//   }
// }

document.addEventListener("DOMContentLoaded", loadUsers);
