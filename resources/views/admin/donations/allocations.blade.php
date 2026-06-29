@extends('admin.layouts.adminLayouts')

@section('title', 'Donation Allocations')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Donation Allocations</h1>
    </div>

    <!-- Allocation Statistics Cards - Responsive Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Total Donations</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800 monthly-donations">RM {{ number_format($monthlyTotal, 2) }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Total Allocation % (Avg)</p>
                    <p class="text-xl md:text-2xl font-bold text-primary total-percent">{{ number_format($totalPercent, 2) }}%</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-pie text-blue-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Total Allocated Amount</p>
                    @php
                        $totalAllocatedAmount = $allocations->sum('allocation_amount');
                    @endphp
                    <p class="text-xl md:text-2xl font-bold text-green-600 total-amount">RM {{ number_format($totalAllocatedAmount, 2) }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-coins text-purple-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Remaining % (Avg)</p>
                    <p class="text-xl md:text-2xl font-bold text-orange-600 remaining-percent">{{ number_format(100 - $totalPercent, 2) }}%</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-percent text-orange-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar - Responsive -->
    <div class="bg-bg-light p-4 rounded-lg shadow-soft border mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Filter by Month/Year -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Month & Year</label>
                <input type="month" id="filterMonth" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                <select id="filterCategory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->alc_cat_id }}">{{ $category->alc_cat_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search by Notes -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="filterSearch" placeholder="Search by notes..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
            </div>

            <!-- Reset Filters Button -->
            <div class="flex items-end">
                <button id="resetFilters" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                    <i class="fas fa-undo-alt mr-2"></i> Reset
                </button>
            </div>
        </div>

        <!-- Active Filters Display -->
        <div id="activeFilters" class="flex flex-wrap gap-2 mt-3"></div>
    </div>

    <div class="bg-bg-light p-4 md:p-6 rounded-lg shadow-soft border">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Allocation Records</h2>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2">
                <button onclick="generateDonationReport()" 
                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition">
                    <i class="fas fa-chart-line mr-1"></i> Report
                </button>
                <a href="{{ route('admin.donations') }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition">
                    <i class="fas fa-heart mr-1"></i> Donations
                </a>
                <button type="button" id="open-allocation-modal-btn"
                        class="inline-flex items-center justify-center px-3 py-1.5 bg-primary hover:bg-primary/90 text-white text-xs font-medium rounded-lg transition">
                    <i class="fas fa-plus mr-1"></i> Add Allocation
                </button>
                <button type="button" id="open-categories-modal-btn"
                        class="inline-flex items-center justify-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition">
                    <i class="fas fa-tags mr-1"></i> Manage Categories
                </button>
            </div>
        </div>

        {{-- Loading Spinner --}}
        <div id="tableLoading" class="hidden text-center py-8">
            <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
            <p class="mt-2 text-gray-500">Loading...</p>
        </div>

        {{-- Allocation Table with Sortable Headers - Responsive Overflow --}}
        <div id="allocations-table-container" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="allocationsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="id">
                            No. <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="category">
                            Category <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="month">
                            Month <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="percent">
                            Percent <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="amount">
                            Amount <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Notes
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Changed By
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="allocationsTableBody">
                    @include('admin.donations.partials.allocation-rows', ['allocations' => $allocations])
                </tbody>
            </table>
        </div>

        {{-- Pagination - AJAX enabled --}}
        <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
                Showing <span id="showingStart">{{ $allocations->firstItem() ?? 0 }}</span> to <span id="showingEnd">{{ $allocations->lastItem() ?? 0 }}</span> of <span id="totalResults">{{ $allocations->total() ?? 0 }}</span> results
            </div>
            <div class="flex justify-center space-x-2" id="paginationLinks">
                @include('admin.donations.partials.allocation-pagination', ['allocations' => $allocations])
            </div>
        </div>
    </div>

    <!-- Add/Edit Allocation Modal - Mobile Responsive -->
    <div id="allocationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-xl relative transform transition-all duration-300 scale-95 max-h-[90vh] overflow-y-auto">
            <div class="p-4 md:p-6">

                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary/10 rounded-lg">
                            <i class="fas fa-layer-group text-primary text-lg"></i>
                        </div>

                        <div>
                            <h3 class="text-lg md:text-xl font-semibold text-gray-800" id="modalTitle">
                                Add Allocation
                            </h3>

                            <p class="text-xs md:text-sm text-gray-500 mt-1" id="modalSubtitle">
                                Create a new donation allocation
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        onclick="closeAllocationModal()"
                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                    >
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- FORM -->
                <form
                    method="POST"
                    action="{{ route('admin.donations.allocations.store') }}"
                    id="allocationForm"
                >
                    @csrf

                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <input
                        type="hidden"
                        name="allocation_id"
                        id="allocation_id"
                        value=""
                    >

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                        <!-- MONTH -->
                        <div>
                            <label
                                for="allocation_month"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Month
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="month"
                                name="allocation_month"
                                id="allocation_month"
                                required
                                value="{{ now()->format('Y-m') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                            >
                        </div>

                        <!-- CATEGORY -->
                        <div>
                            <label
                                for="allocation_category"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Category
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="allocation_category_id"
                                id="allocation_category"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                            >
                                <option value="">Select Category</option>

                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->alc_cat_id }}"
                                        data-icon="{{ $category->alc_cat_icon }}"
                                        data-color="{{ $category->alc_cat_color }}"
                                    >
                                        {{ $category->alc_cat_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- PERCENT -->
                        <div>
                            <label
                                for="allocation_percent"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Allocation Percent %
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="number"
                                name="allocation_percent"
                                id="allocation_percent"
                                required
                                min="0.01"
                                max="100"
                                step="0.01"
                                placeholder="25.00"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                            >
                        </div>

                        <!-- MONTHLY TOTAL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Monthly Total
                            </label>

                            <div class="px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 font-medium">
                                RM <span id="monthlyTotal">0.00</span>
                            </div>
                        </div>

                    </div>

                    <!-- NOTES -->
                    <div class="mb-6">
                        <label
                            for="allocation_notes"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Notes (Optional)
                        </label>

                        <textarea
                            name="allocation_notes"
                            id="allocation_notes"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                            placeholder="Add any additional notes..."
                        ></textarea>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex flex-col-reverse sm:flex-row justify-end space-y-2 space-y-reverse sm:space-y-0 sm:space-x-3">

                        <button
                            type="button"
                            onclick="closeAllocationModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            id="submitBtn"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors"
                        >
                            <i class="fas fa-save mr-2"></i>
                            Save Allocation
                        </button>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Create Category Modal - FIXED -->
    <div id="createCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[100] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="p-4 md:p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary/10 rounded-lg">
                            <i class="fas fa-plus text-primary text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-xl font-semibold text-gray-800">Create New Category</h3>
                            <p class="text-xs md:text-sm text-gray-500 mt-1">Add a new allocation category</p>
                        </div>
                    </div>
                    <button onclick="closeCreateCategoryModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form id="createCategoryForm" method="POST" action="{{ route('admin.donations.categories.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <!-- Category Name -->
                        <div>
                            <label for="alc_cat_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="alc_cat_name" id="alc_cat_name" required placeholder="e.g., Food Distribution"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                        </div>

                        <!-- Icon Picker -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Icon <span class="text-red-500">*</span>
                            </label>
                            <input type="hidden" name="icon" id="category_icon" value="fas fa-hand-heart">
                            <div class="grid grid-cols-4 md:grid-cols-6 gap-2 mb-4 max-h-48 overflow-y-auto p-2 border border-gray-300 rounded-lg bg-gray-50">
                                @php
                                $icons = [
                                    'fas fa-heart',
                                    'fas fa-utensils',
                                    'fas fa-book',
                                    'fas fa-graduation-cap',
                                    'fas fa-hospital',
                                    'fas fa-home',
                                    'fas fa-child',
                                    'fas fa-wheelchair',
                                    'fas fa-eye',      
                                    'fas fa-teeth',
                                    'fas fa-briefcase',
                                    'fas fa-tools',
                                    'fas fa-car',
                                    'fas fa-leaf',
                                    'fas fa-water',
                                    'fas fa-sun',
                                    'fas fa-lightbulb',
                                    'fas fa-laptop',
                                    'fas fa-phone',
                                    'fas fa-shoe-prints',
                                    'fas fa-dumbbell',
                                ];
                                @endphp
                                @foreach($icons as $icon)
                                    <button type="button" onclick="selectIcon('{{ $icon }}')" 
                                        class="icon-btn p-3 border-2 border-gray-300 rounded-lg hover:border-primary hover:bg-primary/10 transition-colors" 
                                        data-icon="{{ $icon }}">
                                        <i class="{{ $icon }} text-xl"></i>
                                    </button>
                                @endforeach
                            </div>

                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-600">Preview:</span>
                                <div id="iconPreviewWrapper" class="w-14 h-14 rounded-full flex items-center justify-center shadow-md" style="background-color: #554994;">
                                    <i id="selectedIconPreview" class="fas fa-hand-heart text-white text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Color Picker -->
                        <div>
                            <label for="category_color" class="block text-sm font-medium text-gray-700 mb-2">
                                Color
                            </label>
                            <div class="flex items-center gap-2 mb-4">
                                <input type="color" name="color" id="category_color" value="#554994"
                                    class="h-10 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                <span id="colorValue" class="text-sm text-gray-600">#554994</span>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end space-y-2 space-y-reverse sm:space-y-0 sm:space-x-3">
                            <button type="button" onclick="closeCreateCategoryModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Create Category
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Manage Categories Modal -->
    <div id="categoriesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-5xl w-full mx-auto max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="p-4 md:p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="fas fa-tags text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-xl font-semibold text-gray-800">Manage Categories</h3>
                            <p class="text-xs md:text-sm text-gray-500 mt-1">View, search, and manage categories</p>
                        </div>
                    </div>
                    <button onclick="closeCategoriesModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Categories Search & Filter -->
                <div class="flex flex-col sm:flex-row gap-3 mb-4">
                    <div class="flex-1">
                        <input type="text" id="categorySearch" placeholder="Search categories..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                    </div>
                    <div class="flex gap-2">
                        <select id="categoryStatusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <button onclick="loadCategories()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                            <i class="fas fa-search mr-1"></i> Search
                        </button>
                        <button onclick="openCreateCategoryModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-1"></i> New Category
                        </button>
                    </div>
                </div>

                <!-- Categories Table -->
                <div id="categoriesTableContainer" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="categoriesTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Categories will be loaded here via AJAX -->
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-spinner fa-spin text-2xl mr-2"></i> Loading categories...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Categories -->
                <div id="categoriesPagination" class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-700" id="categoriesPaginationInfo">
                        Showing 0 to 0 of 0 results
                    </div>
                    <div class="flex justify-center space-x-2" id="categoriesPaginationLinks">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[100] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="p-4 md:p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <i class="fas fa-edit text-yellow-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-xl font-semibold text-gray-800">Edit Category</h3>
                            <p class="text-xs md:text-sm text-gray-500 mt-1">Update category details</p>
                        </div>
                    </div>
                    <button onclick="closeEditCategoryModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <input type="hidden" name="category_id" id="edit_category_id">

                        <!-- Category Name -->
                        <div>
                            <label for="edit_alc_cat_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="alc_cat_name" id="edit_alc_cat_name" required placeholder="e.g., Food Distribution"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                        </div>

                        <!-- Icon Picker -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Icon <span class="text-red-500">*</span>
                            </label>
                            <input type="hidden" name="icon" id="edit_category_icon" value="fas fa-hand-heart">
                            <div class="grid grid-cols-4 md:grid-cols-6 gap-2 mb-4 max-h-48 overflow-y-auto p-2 border border-gray-300 rounded-lg bg-gray-50">
                                @php
                                $icons = [
                                    'fas fa-heart',
                                    'fas fa-utensils',
                                    'fas fa-book',
                                    'fas fa-graduation-cap',
                                    'fas fa-hospital',
                                    'fas fa-home',
                                    'fas fa-child',
                                    'fas fa-wheelchair',
                                    'fas fa-eye',      
                                    'fas fa-teeth',
                                    'fas fa-briefcase',
                                    'fas fa-tools',
                                    'fas fa-car',
                                    'fas fa-leaf',
                                    'fas fa-water',
                                    'fas fa-sun',
                                    'fas fa-lightbulb',
                                    'fas fa-laptop',
                                    'fas fa-phone',
                                    'fas fa-shoe-prints',
                                    'fas fa-dumbbell',
                                ];
                                @endphp
                                @foreach($icons as $icon)
                                    <button type="button" onclick="selectEditIcon('{{ $icon }}')" 
                                        class="edit-icon-btn p-3 border-2 border-gray-300 rounded-lg hover:border-primary hover:bg-primary/10 transition-colors" 
                                        data-icon="{{ $icon }}">
                                        <i class="{{ $icon }} text-xl"></i>
                                    </button>
                                @endforeach
                            </div>

                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-600">Preview:</span>
                                <div id="editIconPreviewWrapper" class="w-14 h-14 rounded-full flex items-center justify-center shadow-md" style="background-color: #554994;">
                                    <i id="editSelectedIconPreview" class="fas fa-hand-heart text-white text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Color Picker -->
                        <div>
                            <label for="edit_category_color" class="block text-sm font-medium text-gray-700 mb-2">
                                Color
                            </label>
                            <div class="flex items-center gap-2 mb-4">
                                <input type="color" name="color" id="edit_category_color" value="#554994"
                                    class="h-10 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                <span id="editColorValue" class="text-sm text-gray-600">#554994</span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="edit_category_status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select name="alc_cat_is_active" id="edit_category_status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end space-y-2 space-y-reverse sm:space-y-0 sm:space-x-3">
                            <button type="button" onclick="closeEditCategoryModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                <i class="fas fa-save mr-2"></i> Update Category
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let currentSortColumn = 'month';
    let currentSortDirection = 'desc';
    let currentPage = 1;
    let currentFilters = {
        month: '',
        category: '',
        search: ''
    };

    // Category modal pagination variables
    let categoryCurrentPage = 1;
    let categorySearch = '';
    let categoryStatus = '';

    // AJAX function to fetch data
    async function fetchAllocations() {
        const loading = document.getElementById('tableLoading');
        const tbody = document.getElementById('allocationsTableBody');

        if (loading) loading.classList.remove('hidden');
        if (tbody) {
            tbody.style.opacity = '0.5';
            tbody.style.pointerEvents = 'none';
        }

        const params = new URLSearchParams();
        params.append('page', currentPage);
        if (currentFilters.month) params.append('month', currentFilters.month);
        if (currentFilters.category) params.append('category', currentFilters.category);
        if (currentFilters.search) params.append('search', currentFilters.search);
        if (currentSortColumn) {
            params.append('sort', currentSortColumn);
            params.append('direction', currentSortDirection);
        }

        try {
            const response = await fetch(`/admin/donations/allocations/data?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Unexpected response ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                if (tbody) {
                    tbody.innerHTML = data.html;
                    tbody.style.opacity = '1';
                    tbody.style.pointerEvents = 'auto';
                }

                const showingStart = document.getElementById('showingStart');
                const showingEnd = document.getElementById('showingEnd');
                const totalResults = document.getElementById('totalResults');

                if (showingStart) showingStart.textContent = data.from || 0;
                if (showingEnd) showingEnd.textContent = data.to || 0;
                if (totalResults) totalResults.textContent = data.total || 0;

                const paginationLinks = document.getElementById('paginationLinks');
                if (paginationLinks && data.pagination) {
                    paginationLinks.innerHTML = data.pagination;
                    attachPaginationHandlers();
                }

                if (data.stats) {
                    updateStatisticsCards(data.stats);
                }
            } else {
                console.error('Error in response:', data);
                if (tbody) {
                    tbody.innerHTML = `<tr><td colspan="8" class="px-4 py-8 text-center text-red-500">Failed to load data. Please refresh the page.</td></tr>`;
                    tbody.style.opacity = '1';
                    tbody.style.pointerEvents = 'auto';
                }
            }
        } catch (error) {
            console.error('Error fetching allocations:', error);
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="8" class="px-4 py-8 text-center text-red-500">Failed to load data. Please refresh the page.</td></tr>`;
                tbody.style.opacity = '1';
                tbody.style.pointerEvents = 'auto';
            }
        } finally {
            if (loading) loading.classList.add('hidden');
        }
    }

    // Load categories for the manage categories modal
    async function loadCategories(page = 1) {
        const tbody = document.getElementById('categoriesTableBody');
        if (!tbody) return;

        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin text-2xl mr-2"></i> Loading categories...
                </td>
            </tr>
        `;

        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

        const params = new URLSearchParams();
        params.append('page', page);
        if (categorySearch) params.append('search', categorySearch);
        if (categoryStatus !== '') params.append('is_active', categoryStatus);

        try {
            const response = await fetch(`/admin/donations/categories?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.data) {
                const categories = data.data.data || [];
                const total = data.data.total || 0;
                const from = data.data.from || 0;
                const to = data.data.to || 0;
                const currentPage = data.data.current_page || 1;
                const lastPage = data.data.last_page || 1;

                if (categories.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                No categories found.
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = categories.map((category, index) => `
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-600">${((currentPage - 1) * 15) + index + 1}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-sm" 
                                     style="background-color: ${category.alc_cat_color || '#554994'};">
                                    <i class="${category.alc_cat_icon || 'fas fa-tag'} text-white text-lg"></i>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">${category.alc_cat_name}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full border border-gray-200" 
                                         style="background-color: ${category.alc_cat_color || '#554994'};"></div>
                                    <span class="text-xs text-gray-500">${category.alc_cat_color || '#554994'}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full ${category.alc_cat_is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${category.alc_cat_is_active ? 'Active' : 'Inactive'}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex gap-2">
                                    <button onclick="openEditCategoryModal(
                                        '${category.alc_cat_id}',
                                        '${category.alc_cat_name.replace(/'/g, "\\'")}',
                                        '${category.alc_cat_icon || 'fas fa-hand-heart'}',
                                        '${category.alc_cat_color || '#554994'}',
                                        ${category.alc_cat_is_active}
                                    )" 
                                            class="px-3 py-1 text-xs rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                    <button onclick="toggleCategory('${category.alc_cat_id}', ${category.alc_cat_is_active})" 
                                            class="px-3 py-1 text-xs rounded-lg transition ${category.alc_cat_is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200'}">
                                        <i class="fas ${category.alc_cat_is_active ? 'fa-ban' : 'fa-check-circle'} mr-1"></i>
                                        ${category.alc_cat_is_active ? 'Deactivate' : 'Activate'}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }

                // Update pagination info
                const infoEl = document.getElementById('categoriesPaginationInfo');
                if (infoEl) {
                    infoEl.textContent = `Showing ${from} to ${to} of ${total} results`;
                }

                // Generate pagination links
                const linksEl = document.getElementById('categoriesPaginationLinks');
                if (linksEl) {
                    let linksHtml = '';
                    if (currentPage > 1) {
                        linksHtml += `<button onclick="loadCategories(${currentPage - 1})" class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Previous</button>`;
                    } else {
                        linksHtml += `<span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>`;
                    }

                    let start = Math.max(1, currentPage - 2);
                    let end = Math.min(lastPage, currentPage + 2);

                    if (start > 1) {
                        linksHtml += `<button onclick="loadCategories(1)" class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">1</button>`;
                        if (start > 2) linksHtml += `<span class="px-3 py-1 text-gray-400">...</span>`;
                    }

                    for (let i = start; i <= end; i++) {
                        if (i === currentPage) {
                            linksHtml += `<span class="px-3 py-1 bg-primary text-white rounded-md">${i}</span>`;
                        } else {
                            linksHtml += `<button onclick="loadCategories(${i})" class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">${i}</button>`;
                        }
                    }

                    if (end < lastPage) {
                        if (end < lastPage - 1) linksHtml += `<span class="px-3 py-1 text-gray-400">...</span>`;
                        linksHtml += `<button onclick="loadCategories(${lastPage})" class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">${lastPage}</button>`;
                    }

                    if (currentPage < lastPage) {
                        linksHtml += `<button onclick="loadCategories(${currentPage + 1})" class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Next</button>`;
                    } else {
                        linksHtml += `<span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>`;
                    }

                    linksEl.innerHTML = linksHtml;
                }
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-red-500">
                            Failed to load categories. Please try again.
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-red-500">
                        Failed to load categories. Please try again.
                        <br><small class="text-gray-400">${error.message}</small>
                    </td>
                </tr>
            `;
        }
    }

    // Toggle category active status
    async function toggleCategory(categoryId, currentStatus) {
        const newStatus = currentStatus ? 'inactive' : 'active';
        const confirmed = await Swal.fire({
            title: `Are you sure?`,
            text: `Do you want to ${newStatus} this category?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: currentStatus ? '#d33' : '#28a745',
            confirmButtonText: `Yes, ${newStatus} it!`,
            cancelButtonText: 'Cancel'
        });

        if (confirmed.isConfirmed) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const response = await fetch(`/admin/donations/categories/${categoryId}/toggle-active`, {
                    method: 'PATCH',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || `Category ${newStatus}d successfully!`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    // Reload categories
                    loadCategories(categoryCurrentPage);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Failed to update category status.'
                    });
                }
            } catch (error) {
                console.error('Error toggling category:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred. Please try again.'
                });
            }
        }
    }

    // Open Edit Category Modal
    window.openEditCategoryModal = function(categoryId, categoryName, icon, color, isActive) {
        const modal = document.getElementById('editCategoryModal');
        if (!modal) return;

        // Set form values
        document.getElementById('edit_category_id').value = categoryId;
        document.getElementById('edit_alc_cat_name').value = categoryName;
        document.getElementById('edit_category_icon').value = icon || 'fas fa-hand-heart';
        
        // Set color
        const colorInput = document.getElementById('edit_category_color');
        if (colorInput) {
            colorInput.value = color || '#554994';
            document.getElementById('editColorValue').textContent = color || '#554994';
            document.getElementById('editIconPreviewWrapper').style.backgroundColor = color || '#554994';
        }
        
        // Set status
        const statusSelect = document.getElementById('edit_category_status');
        if (statusSelect) {
            statusSelect.value = isActive ? '1' : '0';
        }
        
        // Set icon preview
        const previewIcon = document.getElementById('editSelectedIconPreview');
        if (previewIcon) {
            previewIcon.className = (icon || 'fas fa-hand-heart') + ' text-white text-2xl';
        }
        
        // Highlight selected icon
        document.querySelectorAll('.edit-icon-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'bg-primary/10');
            btn.classList.add('border-gray-300');
            if (btn.dataset.icon === (icon || 'fas fa-hand-heart')) {
                btn.classList.add('border-primary', 'bg-primary/10');
            }
        });
        
        // Set form action
        const form = document.getElementById('editCategoryForm');
        if (form) {
            form.action = `/admin/donations/categories/${categoryId}`;
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    // Close Edit Category Modal
    window.closeEditCategoryModal = function() {
        const modal = document.getElementById('editCategoryModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            document.getElementById('editCategoryForm').reset();
        }
    }

    // Select Icon for Edit Modal
    window.selectEditIcon = function(iconClass) {
        document.getElementById('edit_category_icon').value = iconClass;
        const preview = document.getElementById('editSelectedIconPreview');
        if (preview) {
            preview.className = iconClass + ' text-white text-2xl';
        }

        document.querySelectorAll('.edit-icon-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'bg-primary/10');
            btn.classList.add('border-gray-300');
        });

        const selectedBtn = document.querySelector(`.edit-icon-btn[data-icon="${iconClass}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('border-primary', 'bg-primary/10');
        }
    }

    function updateStatisticsCards(stats) {
        const monthlyDonationsEl = document.querySelector('.monthly-donations');
        const totalPercentEl = document.querySelector('.total-percent');
        const totalAmountEl = document.querySelector('.total-amount');
        const remainingPercentEl = document.querySelector('.remaining-percent');

        if (monthlyDonationsEl) monthlyDonationsEl.textContent = `RM ${formatNumber(stats.monthlyDonations || 0)}`;
        if (totalPercentEl) totalPercentEl.textContent = `${formatNumber(stats.totalPercent || 0)}%`;
        if (totalAmountEl) totalAmountEl.textContent = `RM ${formatNumber(stats.totalAmount || 0)}`;
        if (remainingPercentEl) remainingPercentEl.textContent = `${formatNumber(stats.remainingPercent || 0)}%`;
    }

    function formatNumber(num) {
        return parseFloat(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function attachPaginationHandlers() {
        document.querySelectorAll('.pagination-link').forEach(link => {
            link.removeEventListener('click', paginationClickHandler);
            link.addEventListener('click', paginationClickHandler);
        });
    }

    function paginationClickHandler(e) {
        e.preventDefault();
        e.stopPropagation();
        const page = this.getAttribute('data-page');
        if (page && !isNaN(parseInt(page))) {
            currentPage = parseInt(page);
            fetchAllocations();
            document.getElementById('allocations-table-container')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        return false;
    }

    function applyFilters() {
        currentPage = 1;
        fetchAllocations();
        updateActiveFiltersDisplay();
    }

    function updateActiveFiltersDisplay() {
        const container = document.getElementById('activeFilters');
        if (!container) return;

        const activeFiltersList = [];

        if (currentFilters.month) {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            const [year, month] = currentFilters.month.split('-');
            activeFiltersList.push({ key: 'month', label: `${monthNames[parseInt(month)-1]} ${year}`, value: currentFilters.month });
        }
        if (currentFilters.category) {
            const categorySelect = document.getElementById('filterCategory');
            const categoryLabel = categorySelect.options[categorySelect.selectedIndex].text;
            activeFiltersList.push({ key: 'category', label: `Category: ${categoryLabel}`, value: currentFilters.category });
        }
        if (currentFilters.search) {
            activeFiltersList.push({ key: 'search', label: `Search: "${currentFilters.search}"`, value: currentFilters.search });
        }

        if (activeFiltersList.length === 0) {
            container.innerHTML = '';
            return;
        }

        container.innerHTML = activeFiltersList.map(filter => `
            <span class="inline-flex items-center px-2 py-1 bg-primary/10 text-primary text-xs rounded-full">
                ${filter.label}
                <button onclick="removeFilter('${filter.key}')" class="ml-1 hover:text-primary/70">
                    <i class="fas fa-times-circle text-xs"></i>
                </button>
            </span>
        `).join('');
    }

    window.removeFilter = function(key) {
        switch(key) {
            case 'month':
                currentFilters.month = '';
                const monthInput = document.getElementById('filterMonth');
                if (monthInput) monthInput.value = '';
                break;
            case 'category':
                currentFilters.category = '';
                const categorySelect = document.getElementById('filterCategory');
                if (categorySelect) categorySelect.value = '';
                break;
            case 'search':
                currentFilters.search = '';
                const searchInput = document.getElementById('filterSearch');
                if (searchInput) searchInput.value = '';
                break;
        }
        applyFilters();
    };

    function sortTable(column) {
        if (currentSortColumn === column) {
            currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            currentSortColumn = column;
            currentSortDirection = 'asc';
        }

        updateSortIcons(column);
        fetchAllocations();
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

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', () => {
                const sortColumn = header.getAttribute('data-sort');
                sortTable(sortColumn);
            });
        });

        const filterMonth = document.getElementById('filterMonth');
        if (filterMonth) {
            filterMonth.addEventListener('change', function() {
                currentFilters.month = this.value;
                applyFilters();
            });
        }

        const filterCategory = document.getElementById('filterCategory');
        if (filterCategory) {
            filterCategory.addEventListener('change', function() {
                currentFilters.category = this.value;
                applyFilters();
            });
        }

        const searchInput = document.getElementById('filterSearch');
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentFilters.search = this.value;
                    applyFilters();
                }, 500);
            });
        }

        const resetBtn = document.getElementById('resetFilters');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                currentFilters = { month: '', category: '', search: '' };
                currentSortColumn = null;
                currentSortDirection = 'asc';
                if (filterMonth) filterMonth.value = '';
                if (filterCategory) filterCategory.value = '';
                if (searchInput) searchInput.value = '';
                updateSortIcons(null);
                applyFilters();
            });
        }

        // Fetch monthly total when month changes
        const allocationMonth = document.getElementById('allocation_month');
        if (allocationMonth) {
            allocationMonth.addEventListener('change', updateMonthlyTotal);
            updateMonthlyTotal();
        }

        // Edit category color picker update
        const editColorInput = document.getElementById('edit_category_color');
        if (editColorInput) {
            function updateEditPreviewColor(color) {
                document.getElementById('editColorValue').textContent = color;
                document.getElementById('editIconPreviewWrapper').style.backgroundColor = color;
            }

            editColorInput.addEventListener('input', function() {
                updateEditPreviewColor(this.value);
            });

            editColorInput.addEventListener('change', function() {
                updateEditPreviewColor(this.value);
            });
        }

        // Edit category form submission
        const editCategoryForm = document.getElementById('editCategoryForm');
        if (editCategoryForm) {
            editCategoryForm.addEventListener('submit', function(e) {
                const name = document.getElementById('edit_alc_cat_name').value;
                const icon = document.getElementById('edit_category_icon').value;

                if (!name) {
                    e.preventDefault();
                    Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please enter a category name.', confirmButtonColor: '#d33' });
                    return false;
                }
                if (!icon) {
                    e.preventDefault();
                    Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please select an icon.', confirmButtonColor: '#d33' });
                    return false;
                }

                Swal.fire({ 
                    title: 'Updating...', 
                    text: 'Please wait while we update the category.', 
                    allowOutsideClick: false, 
                    didOpen: () => { Swal.showLoading(); } 
                });
            });
        }

        // Close edit modal on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditCategoryModal();
            }
        });

        // Close edit modal on backdrop click
        const editModal = document.getElementById('editCategoryModal');
        if (editModal) {
            editModal.addEventListener('click', function(e) {
                if (e.target === editModal) {
                    closeEditCategoryModal();
                }
            });
        }

        setTimeout(() => {
            attachPaginationHandlers();
            fetchAllocations();
        }, 100);
    });

    function updateMonthlyTotal() {
        const month = document.getElementById('allocation_month').value;
        if (!month) {
            document.getElementById('monthlyTotal').textContent = '0.00';
            return;
        }

        fetch(`/admin/donations/allocations/summary/${month}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.summary) {
                document.getElementById('monthlyTotal').textContent = formatNumber(data.summary.total_donations);
            }
        })
        .catch(error => console.error('Error fetching monthly total:', error));
    }

    // Allocation Modal
    const modal = document.getElementById('allocationModal');
    const openModalBtn = document.getElementById('open-allocation-modal-btn');

    if (openModalBtn) {
        openModalBtn.addEventListener('click', function() {
            resetAllocationForm();
            document.getElementById('modalTitle').textContent = 'Add Allocation';
            document.getElementById('modalSubtitle').textContent = 'Create a new donation allocation';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save mr-2"></i> Save Allocation';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('allocation_id').value = '';
            document.getElementById('allocationForm').action = '{{ route('admin.donations.allocations.store') }}';
            document.getElementById('allocation_month').value = new Date().toISOString().slice(0, 7);
            updateMonthlyTotal();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });
    }

    window.openEditAllocationModal = function(allocationId, month, categoryId, percent, notes) {
        resetAllocationForm();
        document.getElementById('modalTitle').textContent = 'Edit Allocation';
        document.getElementById('modalSubtitle').textContent = 'Update allocation details';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-edit mr-2"></i> Update Allocation';
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('allocation_id').value = allocationId;
        document.getElementById('allocationForm').action = `/admin/donations/allocations/${allocationId}`;
        document.getElementById('allocation_month').value = month;
        document.getElementById('allocation_category').value = categoryId;
        document.getElementById('allocation_percent').value = percent;
        document.getElementById('allocation_notes').value = notes;
        updateMonthlyTotal();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function resetAllocationForm() {
        document.getElementById('allocationForm').reset();
        document.getElementById('allocation_month').value = new Date().toISOString().slice(0, 7);
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('allocation_id').value = '';
    }

    window.closeAllocationModal = function() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        const form = document.getElementById('allocationForm');
        if (form) form.reset();
    }

    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAllocationModal();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllocationModal();
        }
    });

    const allocationForm = document.getElementById('allocationForm');
    if (allocationForm) {
        allocationForm.addEventListener('submit', function(e) {
            const month = document.getElementById('allocation_month').value;
            const category = document.getElementById('allocation_category').value;
            const percent = document.getElementById('allocation_percent').value;

            if (!month) {
                e.preventDefault();
                Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please select a month.', confirmButtonColor: '#d33' });
                return false;
            }
            if (!category) {
                e.preventDefault();
                Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please select a category.', confirmButtonColor: '#d33' });
                return false;
            }
            if (!percent || percent < 0.01 || percent > 100) {
                e.preventDefault();
                Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please enter a valid allocation percentage (0.01 - 100).', confirmButtonColor: '#d33' });
                return false;
            }

            Swal.fire({ title: 'Processing...', text: 'Please wait while we save the allocation.', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        });
    }

    // Delete Allocation with SweetAlert
    window.deleteAllocation = function(allocationId, categoryName, month) {
        const monthFormatted = month ? new Date(month + '-01').toLocaleDateString('en-US', { year: 'numeric', month: 'long' }) : '';

        Swal.fire({
            title: 'Delete Allocation?',
            html: `
                <div class="text-left">
                    <p class="mb-2">You are about to delete this allocation:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>Category:</strong> ${categoryName}</p>
                        <p><strong>Month:</strong> ${monthFormatted}</p>
                    </div>
                    <p class="mt-3 text-red-500 text-sm">⚠️ This action cannot be undone!</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i> Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the allocation.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

                // Make delete request
                fetch(`/admin/donations/allocations/${allocationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message || 'Allocation deleted successfully!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        // Reload the page or refresh the table
                        fetchAllocations();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to delete allocation.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error deleting allocation:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.'
                    });
                });
            }
        });
    }

    // Categories Modal
    const categoriesModal = document.getElementById('categoriesModal');
    const openCategoriesBtn = document.getElementById('open-categories-modal-btn');

    if (openCategoriesBtn) {
        openCategoriesBtn.addEventListener('click', function() {
            categoriesModal.classList.remove('hidden');
            categoriesModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            // Reset search and filter
            document.getElementById('categorySearch').value = '';
            document.getElementById('categoryStatusFilter').value = '';
            categorySearch = '';
            categoryStatus = '';
            categoryCurrentPage = 1;
            loadCategories(1);
        });
    }

    window.closeCategoriesModal = function() {
        categoriesModal.classList.add('hidden');
        categoriesModal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    if (categoriesModal) {
        categoriesModal.addEventListener('click', function(e) {
            if (e.target === categoriesModal) {
                closeCategoriesModal();
            }
        });
    }

    // Category search and filter
    document.addEventListener('DOMContentLoaded', function() {
        const categorySearchInput = document.getElementById('categorySearch');
        const categoryStatusFilter = document.getElementById('categoryStatusFilter');

        if (categorySearchInput) {
            categorySearchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    categorySearch = this.value;
                    categoryCurrentPage = 1;
                    loadCategories(1);
                }
            });
        }

        if (categoryStatusFilter) {
            categoryStatusFilter.addEventListener('change', function() {
                categoryStatus = this.value;
                categoryCurrentPage = 1;
                loadCategories(1);
            });
        }
    });

    // Create Category Modal Functions
    window.openCreateCategoryModal = function() {
        const createCategoryModal = document.getElementById('createCategoryModal');
        createCategoryModal.classList.remove('hidden');
        createCategoryModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    window.closeCreateCategoryModal = function() {
        const createCategoryModal = document.getElementById('createCategoryModal');
        createCategoryModal.classList.add('hidden');
        createCategoryModal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        document.getElementById('createCategoryForm').reset();
        // Reset icon preview
        document.getElementById('selectedIconPreview').className = 'fas fa-hand-heart text-white text-2xl';
        document.getElementById('iconPreviewWrapper').style.backgroundColor = '#554994';
    }

    window.selectIcon = function(iconClass) {
        document.getElementById('category_icon').value = iconClass;
        const preview = document.getElementById('selectedIconPreview');
        preview.className = iconClass + ' text-white text-2xl';

        document.querySelectorAll('.icon-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'bg-primary/10');
            btn.classList.add('border-gray-300');
        });

        document.querySelector(`[data-icon="${iconClass}"]`).classList.add('border-primary', 'bg-primary/10');
    }

    // Color picker update
    const colorInput = document.getElementById('category_color');
    if (colorInput) {
        function updatePreviewColor(color) {
            document.getElementById('colorValue').textContent = color;
            document.getElementById('iconPreviewWrapper').style.backgroundColor = color;
        }

        colorInput.addEventListener('input', function() {
            updatePreviewColor(this.value);
        });

        colorInput.addEventListener('change', function() {
            updatePreviewColor(this.value);
        });

        // initial load
        updatePreviewColor(colorInput.value);
    }

    // Create category modal event listeners
    const createCategoryModal = document.getElementById('createCategoryModal');
    if (createCategoryModal) {
        createCategoryModal.addEventListener('click', function(e) {
            if (e.target === createCategoryModal) {
                closeCreateCategoryModal();
            }
        });
    }

    // Handle create category form submission
    const createCategoryForm = document.getElementById('createCategoryForm');
    if (createCategoryForm) {
        createCategoryForm.addEventListener('submit', function(e) {
            const name = document.getElementById('alc_cat_name').value;
            const icon = document.getElementById('category_icon').value;

            if (!name) {
                e.preventDefault();
                Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please enter a category name.', confirmButtonColor: '#d33' });
                return false;
            }
            if (!icon) {
                e.preventDefault();
                Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please select an icon.', confirmButtonColor: '#d33' });
                return false;
            }

            Swal.fire({ title: 'Creating...', text: 'Please wait while we create the category.', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        });
    }

     // ============================================
    // GENERATE REPORT - UPDATED WITH MONTH PICKER
    // ============================================
    window.generateDonationReport = function() {
        Swal.fire({
            title: 'Generate Report',
            html: `
                <div class="text-left">
                    <p class="mb-3 text-gray-700">Select report period:</p>
                    <select id="reportPeriod" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-3">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="this_week">This Week</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="this_year">This Year</option>
                        <option value="custom">Custom Month</option>
                    </select>
                    <div id="reportCustomMonth" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Month</label>
                        <input type="month" id="reportMonth" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                        <select id="reportFormat" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="html">HTML Report</option>
                            <option value="pdf">PDF Download</option>
                        </select>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#554994',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Generate Report',
            preConfirm: () => {
                const period = document.getElementById('reportPeriod').value;
                let startDate = '';
                let endDate = '';
                
                if (period === 'custom') {
                    const month = document.getElementById('reportMonth').value;
                    if (!month) {
                        Swal.showValidationMessage('Please select a month');
                        return false;
                    }
                    // Set start and end date to the selected month
                    startDate = month + '-01';
                    endDate = month + '-' + new Date(month + '-01').getDaysInMonth();
                }
                
                const format = document.getElementById('reportFormat').value;
                window.open(`/admin/donations/report?period=${period}&start=${startDate}&end=${endDate}&format=${format}`, '_blank');
                return true;
            }
        });
        
        document.getElementById('reportPeriod')?.addEventListener('change', function() {
            const customDiv = document.getElementById('reportCustomMonth');
            if (this.value === 'custom') {
                customDiv.classList.remove('hidden');
            } else {
                customDiv.classList.add('hidden');
            }
        });
    };

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}', confirmButtonColor: '#554994', timer: 3000 });
    @endif

    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error!', text: '{{ session('error') }}', confirmButtonColor: '#d33', timer: 3000 });
    @endif
</script>
@endpush

@push('styles')
<style>
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
    .sortable {
        user-select: none;
    }
    .sortable:hover {
        background-color: #f3f4f6;
    }
    #categoriesTableBody tr:hover {
        background-color: #f9fafb;
    }
</style>
@endpush