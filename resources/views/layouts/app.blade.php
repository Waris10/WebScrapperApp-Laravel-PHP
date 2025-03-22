<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('download.png') }}">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark')
            }
    </script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Change the icons inside the button based on previous settings
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) &&
    window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    themeToggleLightIcon.classList.remove('hidden');
    } else {
    themeToggleDarkIcon.classList.remove('hidden');
    }

    var themeToggleBtn = document.getElementById('theme-toggle');

    themeToggleBtn.addEventListener('click', function() {

    // toggle icons inside button
    themeToggleDarkIcon.classList.toggle('hidden');
    themeToggleLightIcon.classList.toggle('hidden');

    // if set via local storage previously
    if (localStorage.getItem('color-theme')) {
    if (localStorage.getItem('color-theme') === 'light') {
    document.documentElement.classList.add('dark');
    localStorage.setItem('color-theme', 'dark');
    } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('color-theme', 'light');
    }

    // if NOT set via local storage previously
    } else {
    if (document.documentElement.classList.contains('dark')) {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('color-theme', 'light');
    } else {
    document.documentElement.classList.add('dark');
    localStorage.setItem('color-theme', 'dark');
    }
    }
    });



    // Toggle dropdown on ellipsis click (Using event delegation)
    $(document).on('click', '.ellipsis-btn', function(e) {
    e.preventDefault();
    e.stopPropagation(); // Prevent event bubbling

    const dropdown = $(this).next('.dropdown-menu');
    $('.dropdown-menu').not(dropdown).addClass('hidden'); // Close other dropdowns
    dropdown.toggleClass('hidden');
    });

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
    if (!$(e.target).closest('.ellipsis-btn').length && !$(e.target).closest('.dropdown-menu').length) {
    $('.dropdown-menu').addClass('hidden');
    }
    });

</script>

</html>