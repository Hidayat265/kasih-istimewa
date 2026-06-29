<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('KasihIstimewa-KI-icon.ico') }}">
    <title>@yield('title', 'Kasih Istimewa')</title>
    
    <script src="https://cdn-tailwindcss.vercel.app/"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 CSS (optional but recommended) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .sidebar-transition { 
            transition: width 0.3s ease, transform 0.3s ease; 
        }
        
        .main-content-transition {
            transition: margin-left 0.3s ease;
        }

        .sidebar-collapsed .nav-link-text { 
            display: none; 
        }  
        
        .sidebar-collapsed .logo-text-full { 
            display: none; 
        }
        
        .sidebar-collapsed .logo-icon-small { 
            display: block; 
            margin: 0 auto; 
        }

        .nav-link-icon { 
            width: 1.5rem; 
            height: 1.5rem; 
            text-align: center; 
        }
        
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#554994',
                        'secondary': '#CB80AB',
                        'third': '#34495e',
                        'bg-light': '#fefefe',
                        'bg-dark': '#f3f4f6',
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
</head>

<body class="bg-bg-dark min-h-screen font-family-inter">
   
    {{-- Add Admin Sidebar --}}
    @include('admin.layouts.sidebar')

    <!-- Main content -->
    <div id="mainContent" class="main-content-transition flex-1 flex flex-col min-h-screen md:ml-64">
        {{-- Add Admin Header --}}
        @include('admin.layouts.header')
        
        <main class="p-6 md:p-10 flex-1">
            @yield('content')
        </main>
    </div>

    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('sidebar-toggle');
            const closeBtn = document.getElementById('close-sidebar-btn');
            const hamburger = document.getElementById('hamburger');
            const dropdownBtn = document.getElementById('events-dropdown-btn');
            const dropdownMenu = document.getElementById('events-dropdown-menu');
            const dropdownIcon = document.getElementById('events-dropdown-icon');
            
            function updateMainContentMargin() {
                if (window.innerWidth >= 768) { // md breakpoint
                    if (sidebar && mainContent) {
                        if (sidebar.classList.contains('w-20')) {
                            mainContent.style.marginLeft = '5rem'; // w-20 = 5rem
                        } else {
                            mainContent.style.marginLeft = '16rem'; // w-64 = 16rem
                        }
                    }
                } else {
                    if (mainContent) mainContent.style.marginLeft = '0';
                }
            }
            
            if (toggleBtn && sidebar && mainContent) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('w-64');
                    sidebar.classList.toggle('w-20');
                    sidebar.classList.toggle('sidebar-collapsed');
                    updateMainContentMargin();
                    
                    // Store state in localStorage
                    if (sidebar.classList.contains('w-20')) {
                        localStorage.setItem('sidebarCollapsed', 'true');
                    } else {
                        localStorage.setItem('sidebarCollapsed', 'false');
                    }
                });
            }
            
            // Restore sidebar state from localStorage
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true' && sidebar && window.innerWidth >= 768) {
                sidebar.classList.add('w-20', 'sidebar-collapsed');
                sidebar.classList.remove('w-64');
                updateMainContentMargin();
            }
            
            // Mobile sidebar handling
            if (hamburger && sidebar) {
                hamburger.addEventListener('click', function() {
                    sidebar.classList.remove('-translate-x-full');
                });
            }
            
            if (closeBtn && sidebar) {
                closeBtn.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                });
            }
            
            // Dropdown functionality
            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    if (sidebar && sidebar.classList.contains('w-20') && window.innerWidth >= 768) {
                        sidebar.classList.remove('w-20', 'sidebar-collapsed');
                        sidebar.classList.add('w-64');
                        if (dropdownIcon) dropdownIcon.classList.remove('hidden');
                        updateMainContentMargin();
                    }
                    
                    dropdownMenu.classList.toggle('hidden');
                    if (dropdownIcon) dropdownIcon.classList.toggle('rotate-180');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        if (!dropdownMenu.classList.contains('hidden')) {
                            dropdownMenu.classList.add('hidden');
                            if (dropdownIcon) dropdownIcon.classList.remove('rotate-180');
                        }
                    }
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                updateMainContentMargin();
                if (window.innerWidth < 768 && sidebar) {
                    sidebar.classList.add('-translate-x-full');
                } else if (window.innerWidth >= 768 && sidebar) {
                    sidebar.classList.remove('-translate-x-full');
                    updateMainContentMargin();
                }
            });
            
            // Initial margin setup
            updateMainContentMargin();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swal === 'undefined') {
                return;
            }

            const statusMessage = @json(session('status'));
            const successMessage = @json(session('success'));
            const warningMessage = @json(session('warning'));
            const errorMessage = @json(session('error'));

            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: successMessage,
                    confirmButtonColor: '#554994'
                });
            } else if (statusMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: statusMessage,
                    confirmButtonColor: '#554994'
                });
            } else if (warningMessage) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Notice',
                    text: warningMessage,
                    confirmButtonColor: '#ff9800'
                });
            } else if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#d33'
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>

</html>