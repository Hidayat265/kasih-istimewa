@extends('admin.layouts.adminLayouts')
@section('title', 'Admin - Dashboard')
@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
        <div class="bg-white p-6 rounded-xl shadow-xs border">
            <h3 class="text-sm font-medium text-gray-500">Donations (This Month)</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">RM 12,800</p>
            <div class="w-12 h-1 bg-blue-500 rounded-full mt-3"></div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-xs border">
            <h3 class="text-sm font-medium text-gray-500">Total Event Requests</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">8</p>
            <div class="w-12 h-1 bg-teal-500 rounded-full mt-3"></div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-xs border">
            <h3 class="text-sm font-medium text-gray-500">Events (This Month)</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">5</p>
            <div class="w-12 h-1 bg-green-500 rounded-full mt-3"></div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-xs border">
            <h3 class="text-sm font-medium text-gray-500">New Users (Month)</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $newUsersCount }}</p>
            <div class="w-12 h-1 bg-amber-500 rounded-full mt-3"></div>
        </div>
    </div>

    <!-- Placeholder Table -->
    <div class="bg-light p-1 rounded-lg shadow-soft">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 px-6">Recent Event Signups</h2>
         <div id="events-table-container">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Organizer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Dummy Row 1 --}}
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">1.</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#EVT-2001</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">Art Therapy Workshop</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">Sarah Johnson</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">10 Jan 2026</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">11 Jan 2026</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button type="button" title="View Details" class="text-secondary hover:text-primary transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" title="Update Status" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        {{-- Dummy Row 2 --}}
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">2.</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#EVT-2002</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">Community Fun Run</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">David Smith</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">15 Feb 2026</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">15 Feb 2026</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Reviewing</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button type="button" title="View Details" class="text-secondary hover:text-primary transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" title="Update Status" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        {{-- Dummy Row 3 --}}
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">3.</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#EVT-2003</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">Music for Soul Fest</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">Michael Ross</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">20 Mar 2026</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">22 Mar 2026</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button type="button" title="View Details" class="text-secondary hover:text-primary transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" title="Update Status" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            {{-- Dummy Pagination UI --}}
            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of <span class="font-medium">3</span> results
                </div>
                <div class="flex-1 flex justify-end space-x-2">
                    <button class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                        Previous
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-white bg-primary border border-transparent rounded-lg">
                        1
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- <script></script> --}}