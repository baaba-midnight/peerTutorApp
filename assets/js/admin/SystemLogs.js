const API_BASE_URL = '../../api/SystemLogsController.php';

// Configuration
let currentPage = 1;
const pageSize = 5;
let totalRecords = 0;
let totalFilteredRecords = 0;

// Main data loader
async function loadLogs() {
    try {
        const start = (currentPage - 1) * pageSize;
        const filters = getCurrentFilters();
        
        const response = await fetch(`${API_BASE_URL}?action=getLogs&start=${start}&length=${pageSize}&${new URLSearchParams(filters)}`);
        const data = await response.json();
        
        updateLogsTable(data.data);
        totalRecords = data.recordsTotal;
        totalFilteredRecords = data.recordsFiltered;
        updatePaginationControls();
    } catch (error) {
        console.error('Error:', error);
        updateLogsTable({ data: [], recordsTotal: 0, recordsFiltered: 0 });
    }
}

// UI Updaters
function updateLogsTable({ data = [] }) {
    const tbody = document.querySelector('#logsTable tbody');

    tbody.innerHTML = ''; // Clear existing rows

    if (data.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 6;
        cell.className = 'text-center text-muted';
        cell.textContent = 'No data available';
        row.appendChild(cell);
        tbody.appendChild(row);
        return;
    }

    tbody.innerHTML = data.map(log => `
        <tr class="log-${log.severity.toLowerCase()}">
            <td>${log.timestamp}</td>
            <td><span class="badge severity-${log.severity.toLowerCase()}">${log.severity}</span></td>
            <td>${log.module}</td>
            <td>${log.message}</td>
            <td>${log.user_email || 'N/A'}</td>
            <td>${log.ip_address}</td>
        </tr>
    `).join('');
}

function updatePaginationControls() {
    const paginationEl = document.getElementById('pagination');
    const totalPages = Math.ceil(totalFilteredRecords / pageSize);
    
    paginationEl.innerHTML = `
        <button ${currentPage === 1 ? 'disabled' : ''} id="prevBtn">Previous</button>
        ${generatePageNumbers(currentPage, totalPages)}
        <button ${currentPage === totalPages ? 'disabled' : ''} id="nextBtn">Next</button>
        <span>Showing ${((currentPage-1)*pageSize)+1}-${Math.min(currentPage*pageSize, totalFilteredRecords)} of ${totalFilteredRecords}</span>
    `;
    
    document.getElementById('prevBtn').addEventListener('click', () => {
        currentPage--;
        loadLogs();
    });
    
    document.getElementById('nextBtn').addEventListener('click', () => {
        currentPage++;
        loadLogs();
    });
}

// Helper functions
function generatePageNumbers(current, total) {
    if (total <= 7) {
        return Array.from({length: total}, (_, i) => i+1)
            .map(p => `<button class="${p === current ? 'active' : ''}">${p}</button>`)
            .join('');
    }
    // Logic for ellipsis cases would go here
}

function getCurrentFilters() {
    return {
        severity: document.getElementById('severityFilter').value,
        module: document.getElementById('moduleFilter').value,
        dateFrom: document.getElementById('dateFrom').value,
        dateTo: document.getElementById('dateTo').value
    };
}

// Initialize
document.addEventListener('DOMContentLoaded', loadLogs);

document.getElementById('applyFilters').addEventListener('click', () => {
    currentPage = 1; // Reset to first page on filter change
    loadLogs();
});

document.getElementById('resetFilters').addEventListener('click', () => {
    document.getElementById('severityFilter').value = '';
    document.getElementById('moduleFilter').value = '';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    currentPage = 1; // Reset to first page on filter reset
    loadLogs();
});

document.getElementById('exportLogs').addEventListener('click', async () => {
    const response = await fetch(`${API_BASE_URL}?action=exportLogs`);
    const data = await response.json();
});