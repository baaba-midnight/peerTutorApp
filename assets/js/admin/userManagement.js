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
      `${API_BASE_URL}?action=getFilteredUsers&start=${start}&length=${pageSize}`
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

function generatePageNumbers(current, total) {
  // If 7 or fewer pages, show all page numbers
  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1)
      .map((p) => `<button class="page-btn ${p === current ? "active" : ""}" data-page="${p}">${p}</button>`)
      .join("");
  }
  
  // For more than 7 pages, show a compact version with ellipses
  let pages = [];
  
  // Always show first page
  pages.push(1);
  
  // Logic for showing pages around current page and ellipses
  if (current <= 3) {
    // Near the beginning: show first 5 pages, then ellipsis, then last page
    pages.push(2, 3, 4, 5, '...', total);
  } else if (current >= total - 2) {
    // Near the end: show first page, ellipsis, and last 5 pages
    pages.push('...', total - 4, total - 3, total - 2, total - 1, total);
  } else {
    // In the middle: show first page, ellipsis, current page and neighbors, ellipsis, last page
    pages.push('...', current - 1, current, current + 1, '...', total);
  }
  
  // Generate HTML for page buttons
  return pages.map(p => {
    if (p === '...') {
      return `<span class="page-ellipsis">...</span>`;
    }
    return `<button class="page-btn ${p === current ? "active" : ""}" data-page="${p}">${p}</button>`;
  }).join("");
}

function updatePaginationControls() {
  const paginationEl = document.getElementById("pagination");
  const totalPages = Math.ceil(totalFilteredRecords / pageSize);

  paginationEl.innerHTML = ""; // Clear existing pagination

  // Calculate showing range
  const startRecord = Math.min(((currentPage - 1) * pageSize) + 1, totalFilteredRecords);
  const endRecord = Math.min(currentPage * pageSize, totalFilteredRecords);

  paginationEl.innerHTML = `
    <div class="page-info">Showing ${startRecord}-${endRecord} of ${totalFilteredRecords}</div>
    <div class="page-buttons">
      <button class="page-btn" ${currentPage === 1 ? "disabled" : ""} id="prevBtn"><</button>
      ${generatePageNumbers(currentPage, totalPages)}
      <button class="page-btn" ${currentPage === totalPages ? "disabled" : ""} id="nextBtn">></button>
    </div>
  `;

  // Add event listener for previous button
  document.getElementById("prevBtn").addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage--;
      loadUsers();
    }
  });

  // Add event listener for next button
  document.getElementById("nextBtn").addEventListener("click", () => {
    if (currentPage < totalPages) {
      currentPage++;
      loadUsers();
    }
  });

  // Add event listeners for page number buttons
  document.querySelectorAll('.page-btn[data-page]').forEach(button => {
    button.addEventListener('click', () => {
      currentPage = parseInt(button.getAttribute('data-page'));
      loadUsers();
    });
  });
}

document.addEventListener("DOMContentLoaded", loadUsers);
