<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('KasihIstimewa-KI-icon.ico') }}">

    <title>@yield('title', 'Kasih Istimewa | Making a Difference in Special Needs Lives')</title>

    <meta name="description"
        content="@yield('description', 'Kasih Istimewa supports individuals with special needs through donations, volunteering, community events and meaningful support programs.')">

    <meta name="robots" content="index, follow">

    <meta name="author" content="Kasih Istimewa">

    <link rel="canonical"
        href="@yield('canonical', url()->current())">

    <!-- Open Graph -->
    <meta property="og:type"
        content="website">

    <meta property="og:site_name"
        content="Kasih Istimewa">

    <meta property="og:title"
        content="@yield('title', 'Kasih Istimewa | Making a Difference in Special Needs Lives')">

    <meta property="og:description"
        content="@yield('description', 'Kasih Istimewa supports individuals with special needs through donations, volunteering, community events and meaningful support programs.')">

    <meta property="og:url"
        content="@yield('canonical', url()->current())">

    <meta property="og:image"
        content="@yield('og_image', asset('images/kasih-istimewa-og.jpg'))">

    <meta property="og:image:alt"
        content="@yield('og_image_alt', 'Kasih Istimewa')">

    <!-- Twitter / X -->
    <meta name="twitter:card"
        content="summary_large_image">

    <meta name="twitter:title"
        content="@yield('title', 'Kasih Istimewa | Making a Difference in Special Needs Lives')">

    <meta name="twitter:description"
        content="@yield('description', 'Kasih Istimewa supports individuals with special needs through donations, volunteering, community events and meaningful support programs.')">

    <meta name="twitter:image"
        content="@yield('og_image', asset('images/kasih-istimewa-og.jpg'))">

    
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- SweetAlert2 CSS (optional but recommended) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            height: 350px;
            width: 100%;
            border-radius: 12px;
            z-index: 1;
        }
        .leaflet-control-attribution {
            font-size: 9px;
        }
    </style>

    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fb;
            color: #1f2937;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .shadow-soft {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .event-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
        .filter-btn.active {
            background-color: #554994;
            color: white;
        }
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
                    }
                }
            }
        }
    </script>
    @stack('styles')
    @stack('structured-data')
</head>
<body class="antialiased bg-gray-50">

    @include('user.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>