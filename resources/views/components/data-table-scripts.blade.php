@push('scripts')
<script>
// Build placeholder skeleton rows shown while a table's data is loading
function buildSkeletonRows(tableId, rows = 5) {
    const columnCount = document.querySelectorAll(`#${tableId} thead th`).length || 1;
    let html = '';

    for (let r = 0; r < rows; r++) {
        html += '<tr class="animate-pulse">';
        for (let c = 0; c < columnCount; c++) {
            html += '<td class="px-6 py-3"><div class="h-4 bg-gray-200 dark:bg-gray-600 rounded"></div></td>';
        }
        html += '</tr>';
    }

    return html;
}

// Load data for table
function loadTableData(tableId, listUrl, renderCallback) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    if (!tbody) {
        console.error(`Table with id ${tableId} not found`);
        return;
    }

    tbody.innerHTML = buildSkeletonRows(tableId);

    fetch(listUrl, { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
            tbody.innerHTML = '';
            if (data.data && data.data.length > 0) {
                data.data.forEach(item => {
                    const row = renderCallback(item);
                    tbody.appendChild(row);
                });
            } else {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `<td colspan="100%" class="px-6 py-4 text-center text-gray-500">{{ __('No data available') }}</td>`;
                tbody.appendChild(emptyRow);
            }

            renderPagination(tableId, data.meta);
        })
        .catch(e => {
            console.error(`Failed to load data for ${tableId}:`, e);
            const errorRow = document.createElement('tr');
            errorRow.innerHTML = `<td colspan="100%" class="px-6 py-4 text-center text-red-500">{{ __('Failed to load data') }}</td>`;
            tbody.appendChild(errorRow);
        });
}

// Page state per table: { [tableId]: pageNumber }
window.__tablePage = window.__tablePage || {};

// Per-page state per table: { [tableId]: perPage }
window.__tablePerPage = window.__tablePerPage || {};

// Render pagination controls (page info + prev/next/page-number buttons) into
// both the top and bottom pagination containers of a table.
function renderPagination(tableId, meta) {
    const containers = [`${tableId}-pagination-top`, `${tableId}-pagination`]
        .map(id => document.getElementById(id))
        .filter(Boolean);

    if (containers.length === 0) {
        return;
    }

    if (!meta || meta.last_page <= 1) {
        containers.forEach(container => container.innerHTML = '');
        return;
    }

    const { current_page, last_page, total, per_page } = meta;
    const from = total === 0 ? 0 : (current_page - 1) * per_page + 1;
    const to = Math.min(current_page * per_page, total);

    const btnClass = (disabled) => `px-3 py-1.5 text-sm rounded-md border border-gray-300 dark:border-gray-600 ${disabled ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600'}`;

    let html = `<p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Showing') }} ${from}-${to} {{ __('of') }} ${total}</p>`;
    html += '<div class="flex gap-2">';
    html += `<button type="button" ${current_page <= 1 ? 'disabled' : ''} onclick="goToPage('${tableId}', ${current_page - 1})" class="${btnClass(current_page <= 1)}">{{ __('Previous') }}</button>`;
    html += `<span class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-200">${current_page} / ${last_page}</span>`;
    html += `<button type="button" ${current_page >= last_page ? 'disabled' : ''} onclick="goToPage('${tableId}', ${current_page + 1})" class="${btnClass(current_page >= last_page)}">{{ __('Next') }}</button>`;
    html += '</div>';

    containers.forEach(container => container.innerHTML = html);
}

// Navigate to a specific page without resetting filters/sort
function goToPage(tableId, page) {
    window.__tablePage[tableId] = page;
    const functionName = 'load' + tableId.charAt(0).toUpperCase() + tableId.slice(1);
    if (typeof window[functionName] === 'function') {
        window[functionName]();
    }
}

// Change how many rows per page a table shows, resetting to page 1
function changePerPage(tableId, perPage) {
    window.__tablePerPage[tableId] = perPage;
    window.__tablePage[tableId] = 1;
    const functionName = 'load' + tableId.charAt(0).toUpperCase() + tableId.slice(1);
    if (typeof window[functionName] === 'function') {
        window[functionName]();
    }
}

// Build URL with filter query params collected from data-filter-table inputs
function buildFilterUrl(tableId) {
    const table = document.getElementById(tableId);
    const baseUrl = table ? table.dataset.listUrl : '#';
    const params = new URLSearchParams();

    document.querySelectorAll(`[data-filter-table="${tableId}"]`).forEach(input => {
        const value = input.value.trim();
        if (value) {
            params.set(input.dataset.filterKey, value);
        }
    });

    const sortState = window.__tableSort && window.__tableSort[tableId];
    if (sortState) {
        params.set('sort', sortState.key);
        params.set('direction', sortState.direction);
    }

    const page = window.__tablePage && window.__tablePage[tableId];
    if (page) {
        params.set('page', page);
    }

    const perPage = window.__tablePerPage && window.__tablePerPage[tableId];
    if (perPage) {
        params.set('per_page', perPage);
    }

    const qs = params.toString();
    return baseUrl + (qs ? '?' + qs : '');
}

// Sort state per table: { [tableId]: { key, direction } }
window.__tableSort = window.__tableSort || {};

function toggleSort(th) {
    const table = th.closest('table');
    if (!table) {
        return;
    }

    const tableId = table.id;
    const key = th.dataset.sortKey;
    const current = window.__tableSort[tableId];
    const direction = current && current.key === key && current.direction === 'asc' ? 'desc' : 'asc';

    window.__tableSort[tableId] = { key, direction };

    table.querySelectorAll('[data-sort-key] .sort-indicator').forEach(indicator => {
        indicator.textContent = '↕';
    });

    const indicator = th.querySelector('.sort-indicator');
    if (indicator) {
        indicator.textContent = direction === 'asc' ? '↑' : '↓';
    }

    applyFilters(tableId);
}

function applyFilters(tableId) {
    window.__tablePage = window.__tablePage || {};
    window.__tablePage[tableId] = 1;

    const functionName = 'load' + tableId.charAt(0).toUpperCase() + tableId.slice(1);
    if (typeof window[functionName] === 'function') {
        window[functionName]();
    }
}

function resetFilters(tableId) {
    document.querySelectorAll(`[data-filter-table="${tableId}"]`).forEach(input => {
        input.value = '';
    });
    applyFilters(tableId);
}

// Generic delete function
window.deleteItem = function(url) {
    showConfirmModal(url);
};

// Initialize table on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Wire up per-page selects
    document.querySelectorAll('[data-per-page-select]').forEach(select => {
        const tableId = select.dataset.perPageSelect;
        select.addEventListener('change', () => changePerPage(tableId, select.value));
    });

    // Find all tables with data-list-url and load their data
    const tables = document.querySelectorAll('[data-list-url]');
    tables.forEach(table => {
        const tableId = table.id;
        const listUrl = table.dataset.listUrl;

        if (tableId && listUrl) {
            // Call page-specific function if it exists
            const functionName = 'load' + tableId.charAt(0).toUpperCase() + tableId.slice(1);
            if (typeof window[functionName] === 'function') {
                window[functionName]();
            } else {
                console.warn('[DataTable] Function not found:', functionName);
            }
        }
    });
});
</script>
@endpush
