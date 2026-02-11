<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#1BA1E2">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,900&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Styles -->
  @livewireStyles
  
  <style>
    /* Exact background from image */
    body {
      background: #f0f4f8;
      min-height: 100vh;
      min-height: -webkit-fill-available;
      font-family: 'Outfit', sans-serif;
    }
    
    /* Prevent zoom on input focus (iOS) */
    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="tel"] {
      font-size: 16px !important;
    }
  </style>
</head>

<body class="antialiased">
  <div class="min-h-screen flex items-center justify-center p-6 md:p-12">
    {{ $slot }}
  </div>

  <x-sigsegv-core-dumped />

  @livewireScripts
  <script>
    document.addEventListener('livewire:initialized', () => {
      Livewire.onPageExpired((response, message) => {
        window.location.reload();
      });
    });
  </script>
</body>

</html>
