@extends('user.layouts.userLayouts')

@section('title', 'My Donations | Kasih Istimewa')

@section('content')

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary/90 to-secondary/80 py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">My Donations</h1>
        <p class="text-base md:text-lg lg:text-xl opacity-90 max-w-2xl mx-auto px-4">
            Track your donation history and view receipts
        </p>
    </div>
</section>

<!-- Donations List Section -->
<section class="py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-soft p-6 text-center">
                <div class="text-3xl text-primary mb-2">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <div class="text-2xl font-bold text-gray-800" id="totalDonationsStat">{{ $totalDonations ?? 0 }}</div>
                <p class="text-gray-600 text-sm">Total Donations</p>
            </div>
            <div class="bg-white rounded-xl shadow-soft p-6 text-center">
                <div class="text-3xl text-primary mb-2">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="text-2xl font-bold text-gray-800" id="totalAmountStat">RM {{ number_format($totalAmount ?? 0, 2) }}</div>
                <p class="text-gray-600 text-sm">Total Donated</p>
            </div>
            <div class="bg-white rounded-xl shadow-soft p-6 text-center">
                <div class="text-3xl text-primary mb-2">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="text-2xl font-bold text-gray-800" id="monthlyCountStat">{{ $monthlyCount ?? 0 }}</div>
                <p class="text-gray-600 text-sm">This Month</p>
            </div>
        </div>
        
        <!-- Donations Table -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Donation History</h2>
                    <p class="text-sm text-gray-500 mt-1">View all your past donations and receipts</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:flex-none">
                        <input type="text" id="searchInput" placeholder="Search donations..." 
                               class="w-full sm:w-48 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select id="sortSelect" class="w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm bg-white">
                        <option value="created_at_desc">Sort by Date (Newest)</option>
                        <option value="created_at_asc">Sort by Date (Oldest)</option>
                        <option value="donation_amount_desc">Sort by Amount (Highest)</option>
                        <option value="donation_amount_asc">Sort by Amount (Lowest)</option>
                        <option value="donation_status_asc">Sort by Status (A-Z)</option>
                        <option value="donation_status_desc">Sort by Status (Z-A)</option>
                    </select>
                </div>
            </div>
            
            <div id="tableLoading" class="hidden text-center py-8">
                <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
                <p class="mt-2 text-gray-500">Loading...</p>
            </div>
            
            <div class="overflow-x-auto" id="donationsTableContainer">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_id">Donation ID <i class="fas fa-sort ml-1 text-gray-400"></i></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_amount">Amount <i class="fas fa-sort ml-1 text-gray-400"></i></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="created_at">Date <i class="fas fa-sort ml-1 text-gray-400"></i></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_payment_method">Payment Method <i class="fas fa-sort ml-1 text-gray-400"></i></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_received_by">Received By <i class="fas fa-sort ml-1 text-gray-400"></i></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_status">Status <i class="fas fa-sort ml-1 text-gray-400"></i></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="donationsTableBody">
                        @include('user.donation.partials.table-rows', ['donations' => $donations])
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                @include('user.donation.partials.pagination', ['donations' => $donations])
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    let currentSortColumn = 'created_at';
    let currentSortDirection = 'desc';
    let currentPage = 1;
    let currentFilters = {
        search: '',
        sort: 'created_at_desc'
    };

    function fetchDonations(page = 1) {
        const tableLoading = document.getElementById('tableLoading');
        const tableContainer = document.getElementById('donationsTableContainer');
        
        if (tableLoading) tableLoading.classList.remove('hidden');
        if (tableContainer) {
            tableContainer.style.opacity = '0.5';
        }

        const params = new URLSearchParams();
        params.append('page', page);
        if (currentFilters.search) params.append('search', currentFilters.search);
        if (currentFilters.sort) params.append('sort', currentFilters.sort);
        if (currentSortColumn) {
            params.append('sort_column', currentSortColumn);
            params.append('sort_direction', currentSortDirection);
        }

        const fetchUrl = `{{ route('user.donations.data') }}?${params.toString()}`;

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update table body
                const tbody = document.querySelector('#donationsTableBody');
                if (tbody) {
                    tbody.innerHTML = data.html;
                }
                
                // Update pagination links
                const paginationContainer = document.getElementById('paginationContainer');
                if (paginationContainer) {
                    paginationContainer.innerHTML = data.pagination;
                    attachPaginationHandlers();
                }
                
                // Update showing info
                document.getElementById('usersShowingStart').textContent = data.from || 0;
                document.getElementById('usersShowingEnd').textContent = data.to || 0;
                document.getElementById('usersTotalResults').textContent = data.total || 0;
                
                // Update statistics cards
                if (data.stats) {
                    document.getElementById('totalDonationsStat').textContent = data.stats.total_donations || 0;
                    document.getElementById('totalAmountStat').textContent = 'RM ' + Number(data.stats.total_amount || 0).toFixed(2);
                    document.getElementById('monthlyCountStat').textContent = data.stats.monthly_count || 0;
                }
                
                // Update sort icons
                updateSortIcons(currentSortColumn);
                
                if (tableLoading) tableLoading.classList.add('hidden');
                if (tableContainer) {
                    tableContainer.style.opacity = '1';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (tableLoading) tableLoading.classList.add('hidden');
            if (tableContainer) {
                tableContainer.style.opacity = '1';
            }
        });
    }

    function updateSortIcons(activeColumn) {
        document.querySelectorAll('.sortable i').forEach(icon => {
            icon.className = 'fas fa-sort ml-1 text-gray-400';
        });
        
        const activeHeader = document.querySelector(`.sortable[data-sort="${activeColumn}"] i`);
        if (activeHeader) {
            activeHeader.className = currentSortDirection === 'asc' 
                ? 'fas fa-sort-up ml-1 text-primary' 
                : 'fas fa-sort-down ml-1 text-primary';
        }
    }

    function sortTable(column) {
        if (currentSortColumn === column) {
            currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            currentSortColumn = column;
            currentSortDirection = 'asc';
        }
        
        // Update sort dropdown to match
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect) {
            sortSelect.value = column + '_' + currentSortDirection;
        }
        
        fetchDonations(1);
    }

    function attachPaginationHandlers() {
        document.querySelectorAll('#paginationContainer .pagination-link').forEach(link => {
            link.removeEventListener('click', paginationClickHandler);
            link.addEventListener('click', paginationClickHandler);
        });
    }

    function paginationClickHandler(e) {
        e.preventDefault();
        const page = this.getAttribute('data-page');
        if (page) {
            fetchDonations(parseInt(page));
        }
        return false;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Sortable headers
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', () => {
                const sortColumn = header.getAttribute('data-sort');
                if (sortColumn) {
                    sortTable(sortColumn);
                }
            });
        });

        // Search
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.trim();
                currentFilters.search = searchTerm;

                searchTimeout = setTimeout(() => {
                    fetchDonations(1);
                }, 500);
            });
        }

        // Sort dropdown
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const value = this.value;
                const parts = value.split('_');
                currentSortColumn = parts[0] || 'created_at';
                currentSortDirection = parts[1] || 'desc';
                currentFilters.sort = value;
                fetchDonations(1);
            });
        }

        // Initial load
        setTimeout(() => {
            attachPaginationHandlers();
            updateSortIcons('created_at');
        }, 100);
    });

    function viewReceipt(donationId) {
        if (!donationId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Donation ID not found.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        window.open(`/receipt/${donationId}/view`, '_blank');
    }   
</script>
@endpush

@push('styles')
<style>
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
    .sortable {
        user-select: none;
        cursor: pointer;
    }
    .sortable:hover {
        background-color: #f3f4f6;
    }
    #donationsTableBody tr:hover {
        background-color: #f9fafb;
    }
</style>
@endpush
@endsection