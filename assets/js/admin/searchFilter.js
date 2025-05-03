// Toggle search functionality

const searchBtn = document.querySelector('.search-btn');
const searchContainer = document.querySelector('.search-container');
const searchInput = document.getElementById('searchInput');

searchBtn.addEventListener('click', () => {
    searchContainer.classList.toggle('active');
    if (searchContainer.classList.contains('active')) {
        searchInput.focus();
        // Close filter if it's open
        filterContainer.classList.remove('active');
    }
});

// Toggle filter functionality
const filterBtn = document.querySelector('.filter-btn');
const filterContainer = document.querySelector('.filter-container');
const userFilter = document.getElementById('userFilter');

filterBtn.addEventListener('click', () => {
    filterContainer.classList.toggle('active');
    if (filterContainer.classList.contains('active')) {
        // Close search if it's open
        searchContainer.classList.remove('active');
    }
});

// Close popups when clicking outside
document.addEventListener('click', (event) => {
    if (!searchBtn.contains(event.target) && !searchContainer.contains(event.target)) {
        searchContainer.classList.remove('active');
    }
    
    if (!filterBtn.contains(event.target) && !filterContainer.contains(event.target)) {
        filterContainer.classList.remove('active');
    }
});

// Search functionality
searchInput.addEventListener('input', () => {
    filterUsers();
});

// Filter functionality
userFilter.addEventListener('change', () => {
    filterUsers();
});

// Function to filter users based on search text and filter value
async function filterUsers() {
    try {
        const start = (currentPage - 1) * pageSize;
        const response = await fetch(
          `${API_BASE_URL}?action=getFilteredUsers&start${start}&length=${pageSize}&${new URLSearchParams(getCurrentFilters())}`,
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

function getCurrentFilters() {
    return {
        search: searchInput.value,
        filter: userFilter.value,
    }
}

// Add User functionality (placeholder)
const addUserBtn = document.querySelector('.add-user-btn');
addUserBtn.addEventListener('click', () => {
    alert('Add user functionality would open a form here');
});