<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/logo.jpg') }}" type="image/x-icon">
    <title>{{ $title ? $title . ' | ' . config('app.name') : config('app.name') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />

    {{-- Google Fonts for Digital Clock --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Orbitron:wght@400..900&display=swap"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="relative bg-zinc-900">

    <x-widget.toast-message-frontend />

    {{ $slot }} --}}

    <x-footer />

    <script>
        // রাইট-ক্লিক বন্ধ করার জন্য
        // document.addEventListener('contextmenu', event => event.preventDefault());

        // কপি (Ctrl+C) বন্ধ করার জন্য
        // document.addEventListener('copy', event => event.preventDefault());

        // সিলেক্ট করা বন্ধ করার জন্য
        document.addEventListener('selectstart', event => event.preventDefault());
    </script>

    @livewireScripts
</body>

</html>
