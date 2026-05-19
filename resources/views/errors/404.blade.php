@props([
    'title' => 'Not found Page',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <script>
        const tz = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
        Livewire.emit('setTimezone', tz);
    </script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="relative bg-zinc-900">

    <x-widget.toast-message-frontend />

    <main
        class="flex flex-col gap-10 items-center justify-center max-w-md mx-auto min-h-screen bg-lemon text-slate-900 px-4 overflow-hidden">

        @if (env('UNDER_DEVELOPMENT'))
            <div class="w-full max-w-xl mx-auto">
                <x-widget.marquee />
            </div>
        @endif

        <div
            class="w-full max-w-xl mx-auto bg-white backdrop-blur-sm rounded-2xl shadow-lg border border-amber-200 p-8 flex flex-col items-center text-center">

            <!-- Icon / Badge -->
            <div
                class="flex items-center justify-center w-24 h-24 rounded-full bg-rose-50 text-rose-600 text-2xl font-extrabold mb-4">
                404
            </div>

            <!-- Title -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-rose-600 mb-2">Page not found.</h1>

            <!-- Description -->
            <p class="text-slate-700 mb-6 px-2">
                Sorry, the page you are looking for could not be found. Maybe the link is broken, or the page has been
                moved.
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <a href="{{ route('home') }}" wire:navigate
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium shadow transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10.5L12 4l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10.5z" />
                    </svg>
                    Go back home.
                </a>

                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" target="_blank"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-amber-500 text-white font-medium hover:bg-amber-600 transition">
                    Contact us
                </a>
            </div>

            <!-- Tip and meta -->
            <p class="mt-6 text-sm text-slate-500 px-2">
                Tips: Check that the URL is correct; if it is case sensitive, check the upper/lower case.
            </p>

            <div id="error-id" class="mt-4 text-xs text-slate-400">Error ID: <span id="ts"
                    class="font-mono text-slate-600"></span></div>
        </div>
    </main>

    <footer
        class="fixed bottom-0 left-0 right-0 mx-auto max-w-md bg-emerald-500/80 backdrop-blur-xs text-white py-2 shadow-inner flex justify-around space-x-10 rounded-ss-xl rounded-se-xl z-50">


        <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" target="_blank"
            class="flex flex-col items-center gap-1 cursor-pointer py-1 px-3 rounded-md hover:text-amber-400 hover:bg-emerald-600/50"
            data-discover="true">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" class="text-2xl"
                height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z">
                </path>
            </svg>
            <p class="text-xs">Chat</p>
        </a>


        <a aria-current="page"
            class="flex flex-col items-center gap-1 cursor-pointer py-1 px-3 rounded-md hover:text-amber-400 hover:bg-emerald-600"
            href="{{ route('home') }}" wire:navigate data-discover="true"><svg stroke="currentColor" fill="currentColor"
                stroke-width="0" viewBox="0 0 24 24" class="text-2xl" height="1em" width="1em"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M13 19H19V9.97815L12 4.53371L5 9.97815V19H11V13H13V19ZM21 20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V9.48907C3 9.18048 3.14247 8.88917 3.38606 8.69972L11.3861 2.47749C11.7472 2.19663 12.2528 2.19663 12.6139 2.47749L20.6139 8.69972C20.8575 8.88917 21 9.18048 21 9.48907V20Z">
                </path>
            </svg>
            <p class="text-xs">Home</p>
        </a>


        <a href="{{ route('profile.edit') }}" wire:navigate
            class="flex flex-col items-center gap-1 cursor-pointer py-1 px-3 rounded-md hover:text-amber-400 hover:bg-emerald-600 active:text-amber-400"
            data-discover="true"><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 1024 1024"
                class="text-2xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M858.5 763.6a374 374 0 0 0-80.6-119.5 375.63 375.63 0 0 0-119.5-80.6c-.4-.2-.8-.3-1.2-.5C719.5 518 760 444.7 760 362c0-137-111-248-248-248S264 225 264 362c0 82.7 40.5 156 102.8 201.1-.4.2-.8.3-1.2.5-44.8 18.9-85 46-119.5 80.6a375.63 375.63 0 0 0-80.6 119.5A371.7 371.7 0 0 0 136 901.8a8 8 0 0 0 8 8.2h60c4.4 0 7.9-3.5 8-7.8 2-77.2 33-149.5 87.8-204.3 56.7-56.7 132-87.9 212.2-87.9s155.5 31.2 212.2 87.9C779 752.7 810 825 812 902.2c.1 4.4 3.6 7.8 8 7.8h60a8 8 0 0 0 8-8.2c-1-47.8-10.9-94.3-29.5-138.2zM512 534c-45.9 0-89.1-17.9-121.6-50.4S340 407.9 340 362c0-45.9 17.9-89.1 50.4-121.6S466.1 190 512 190s89.1 17.9 121.6 50.4S684 316.1 684 362c0 45.9-17.9 89.1-50.4 121.6S557.9 534 512 534z">
                </path>
            </svg>
            <p class="text-xs">Profile</p>
        </a>

    </footer>



    <!-- JS -->
    <script>
        // ISO string: 2026-04-08T14:35:00.000Z
        const iso = new Date().toISOString();

        // Unix epoch milliseconds: 1712606100000
        const epochMs = Date.now();

        document.getElementById('ts').textContent = iso;
        // অথবা document.getElementById('ts').textContent = epochMs;
    </script>

    @livewireScripts
</body>

</html>
