<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    @stack('styles')
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
            max-width: 100vw;
        }
        html {
            overflow-x: hidden;
            max-width: 100vw;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
        .app-gradient {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            min-height: 100vh;
        }
        .header-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }
        .content-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }
        .page-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-900 overflow-x-hidden">
    <x-banner />

    <div class="app-gradient relative overflow-x-hidden">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-blue-400 opacity-10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-indigo-400 opacity-10 rounded-full blur-3xl pointer-events-none"></div>

        <nav class="sticky top-0 z-50">
            @livewire('navigation-menu')
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="header-glass mb-6">
                <div class="mx-auto max-w-7xl px-6 py-8">
                    <div class="flex items-center space-x-4">
                        <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                                {{ $header }}
                            </h2>
                            <p class="text-sm text-slate-500 font-medium">Layanan Absensi Siswa Online</p>
                        </div>
                    </div>
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 pb-24">
            <div class="page-fade-in">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('modals')
    @livewireScripts
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.onPageExpired((response, message) => {
                window.location.reload();
            });
        });
    </script>
    @stack('scripts')
    
    @auth
        @if(!Auth::user()->isAdmin)
            <x-mobile-bottom-nav />
        @endif
    @endauth
</body>

</html>
