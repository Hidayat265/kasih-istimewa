@php
    $cloudName = 'dvwfqplh3';
    $baseImage = 'v1762011946/blank.png';
    $user = $user ?? Auth::user();
    $firstLetter = strtoupper(substr($user->user_name ?? 'A', 0, 1));
    $size = $size ?? 100;

    $fontSize = max(20, round($size * 0.55));

    // Try a font that typically centers better
    $fallbackAvatar = "https://res.cloudinary.com/{$cloudName}/image/upload/"
        . "w_{$size},h_{$size},c_fill,r_max,b_rgb:999999/"
        . "l_text:Verdana_{$fontSize}_bold:{$firstLetter},co_rgb:ffffff,g_center/fl_layer_apply/"
        . "f_auto,q_auto/{$baseImage}";

    $avatarUrl = $user->user_profile_picture ?? $fallbackAvatar;
@endphp

<div 
    class="rounded-full overflow-hidden border border-gray-300 shadow-sm"
    style="width: {{ $size }}px; height: {{ $size }}px;"
>
    <img 
        src="{{ $avatarUrl }}"
        alt="{{ $user->user_name }}'s profile photo"
        class="w-full h-full object-cover"
        onerror="this.onerror=null; this.src='{{ $fallbackAvatar }}';"
    >
</div>

{{-- Font Asal --}}
{{-- @php
    $cloudName = 'dvwfqplh3'; // your Cloudinary cloud name
    $baseImage = 'v1762011946/blank.png'; // your transparent background image
    $user = $user ?? Auth::user();
    $firstLetter = strtoupper(substr($user->user_name ?? 'A', 0, 1));
    $size = $size ?? 100; // default size

    // Dynamically adjust font size based on image size
    $fontSize = max(20, round($size * 0.6)); // 60% of image size, minimum 20px

    // Cloudinary placeholder avatar
    $fallbackAvatar = "https://res.cloudinary.com/{$cloudName}/image/upload/"
        . "w_{$size},h_{$size},c_fill,r_max,b_rgb:999999/"
        . "l_text:Arial_{$fontSize}_bold:{$firstLetter},co_rgb:ffffff,g_center/fl_layer_apply/"
        . "f_auto,q_auto/{$baseImage}";

    $avatarUrl = $user->user_profile_picture ?? $fallbackAvatar;
@endphp --}}
