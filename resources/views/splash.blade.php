<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Absensi Siswa') }}</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            overflow: hidden;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Animated background particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: #009ee0;
            border-radius: 50%;
            animation: float 15s infinite;
            opacity: 0.15;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 12s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; animation-duration: 15s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; animation-duration: 18s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 1s; animation-duration: 14s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 3s; animation-duration: 16s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; animation-duration: 13s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 2s; animation-duration: 17s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 4s; animation-duration: 15s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 1s; animation-duration: 19s; }

        @keyframes float {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }

        .splash-container {
            text-align: center;
            color: #1f2937;
            position: relative;
            z-index: 10;
        }

        .logo-container {
            margin-bottom: 3rem;
            animation: zoomIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .logo-wrapper {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #009ee0 0%, #0077b6 100%);
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 20px 60px rgba(0, 158, 224, 0.3);
            position: relative;
            overflow: hidden;
        }

        .logo-wrapper::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo-wrapper svg {
            width: 160px;
            height: auto;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1)) brightness(0) invert(1);
        }

        .app-name {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            animation: fadeInUp 0.8s ease-out 0.3s both;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #009ee0 0%, #0077b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .app-tagline {
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 3rem;
            color: #6b7280;
            animation: fadeInUp 0.8s ease-out 0.5s both;
        }

        .loading-container {
            animation: fadeInUp 0.8s ease-out 0.7s both;
        }

        /* Modern loader */
        .loader {
            width: 60px;
            height: 60px;
            margin: 0 auto 2rem;
            position: relative;
        }

        .loader-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 3px solid transparent;
            border-top-color: #009ee0;
            border-radius: 50%;
            animation: spin 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        }

        .loader-ring:nth-child(1) {
            animation-delay: -0.45s;
        }

        .loader-ring:nth-child(2) {
            animation-delay: -0.3s;
            border-top-color: #0077b6;
        }

        .loader-ring:nth-child(3) {
            animation-delay: -0.15s;
            border-top-color: #48cae4;
        }

        .loading-text {
            font-size: 1rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 1.5rem;
        }

        .loading-dots {
            display: inline-block;
        }

        .loading-dots span {
            animation: blink 1.4s infinite;
            animation-fill-mode: both;
        }

        .loading-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .loading-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        /* Progress bar */
        .progress-container {
            width: 280px;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            margin: 0 auto;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #009ee0, #48cae4);
            border-radius: 3px;
            animation: progress 2.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            box-shadow: 0 0 10px rgba(0, 158, 224, 0.4);
        }

        .version-info {
            margin-top: 3rem;
            font-size: 0.85rem;
            color: #9ca3af;
            animation: fadeIn 1s ease-out 1s both;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0) rotate(-180deg);
                opacity: 0;
            }
            to {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes blink {
            0%, 80%, 100% {
                opacity: 0;
            }
            40% {
                opacity: 1;
            }
        }

        @keyframes progress {
            0% {
                width: 0%;
                opacity: 0.5;
            }
            50% {
                opacity: 1;
            }
            100% {
                width: 100%;
                opacity: 0.9;
            }
        }

        .fade-out {
            animation: fadeOut 0.6s ease-out forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
        }

        @media (max-width: 768px) {
            .app-name {
                font-size: 2rem;
            }
            
            .app-tagline {
                font-size: 0.95rem;
            }
            
            .logo-wrapper {
                width: 160px;
                height: 160px;
                border-radius: 32px;
            }
            
            .logo-wrapper svg {
                width: 130px;
            }

            .progress-container {
                width: 220px;
            }
        }

        @media (max-width: 480px) {
            .app-name {
                font-size: 1.75rem;
            }
            
            .logo-wrapper {
                width: 140px;
                height: 140px;
                border-radius: 28px;
            }
            
            .logo-wrapper svg {
                width: 110px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated particles background -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="splash-container" id="splash">
        <!-- Logo -->
        <div class="logo-container">
            <div class="logo-wrapper">
                @php
                    $customLogo = \App\Models\Setting::logo();
                @endphp

                @if($customLogo)
                    <img src="{{ $customLogo }}" alt="{{ \App\Models\Setting::appName() }}" style="width: 80%; height: 80%; object-fit: contain;" />
                @else
                    <svg viewBox="0 0 1024 204" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                      <path d="M58.967,103.477c-7.745,-7.458 -7.979,-19.775 -0.529,-27.521c7.454,-7.75 19.775,-7.983 27.525,-0.529l21.129,20.304l51.934,-53.404c7.491,-7.687 19.816,-7.867 27.5,-0.371c7.708,7.492 7.883,19.817 0.391,27.525l-65.295,67.113l-0.042,0.058c-7.45,7.746 -19.775,7.979 -27.521,0.529l-35.092,-33.704Z" style="fill:#009ee0;"/>
                      <path d="M103.214,0.019c11.562,0.175 20.796,9.679 20.616,21.246c-0.175,11.562 -9.7,20.791 -21.262,20.616c-3.479,-0.058 -7.063,0.196 -10.717,0.8c-3.383,0.55 -6.866,1.446 -10.391,2.7c-11.946,4.267 -21.921,12.129 -28.867,22.109c-6.988,10.033 -10.9,22.158 -10.725,34.87c0.175,12.7 4.425,24.709 11.679,34.55c7.183,9.759 17.371,17.334 29.5,21.284c12.108,3.929 24.846,3.796 36.463,0.112c11.637,-3.691 22.145,-10.896 29.737,-21.046c6.938,-9.254 20.063,-11.129 29.317,-4.183c9.237,6.925 11.112,20.05 4.166,29.304c-13.045,17.425 -30.887,29.734 -50.583,35.971c-19.717,6.242 -41.371,6.45 -62.067,-0.271c-20.658,-6.708 -38.05,-19.679 -50.354,-36.387c-12.246,-16.621 -19.425,-37.046 -19.716,-58.838c-0.296,-21.754 6.32,-42.371 18.112,-59.337c11.813,-16.996 28.854,-30.392 49.392,-37.709c5.696,-2.016 11.604,-3.52 17.608,-4.495c5.942,-0.984 12.008,-1.413 18.092,-1.296" style="fill:#009ee0;"/>
                      <path d="M339.623,85.59c0,-10.442 -8.312,-18.754 -18.75,-18.754l-23.004,-0l0,37.7l23.004,-0c10.438,-0 18.75,-8.509 18.75,-18.946m30.546,-1.158c0,29.383 -19.717,47.945 -47.171,47.945l-25.129,0l0,34.796c0,4.063 -2.517,6.571 -6.383,6.571l-18.171,0c-4.058,0 -6.571,-2.508 -6.571,-6.571l0,-113.287c0,-8.504 6.958,-15.463 15.467,-15.463l40.787,0c27.454,0 47.171,19.325 47.171,46.009" style="fill:#009ee0;fill-rule:nonzero;"/>
                      <path d="M435.143,96.804c-13.341,0 -25.521,10.829 -25.521,26.483l0,45.046c0,3.484 -1.933,5.413 -5.412,5.413l-16.817,-0c-3.483,-0 -5.416,-1.929 -5.416,-5.413l-0,-44.854c-0,-19.717 10.633,-36.921 25.904,-46.204c10.246,-6.379 18.946,-7.538 30.158,-7.538c4.642,0 8.313,0 11.6,1.355c5.221,2.125 8.508,6.958 8.508,12.758c0,6.958 -5.412,13.342 -13.341,13.342c-3.288,-0 -5.992,-0.388 -9.663,-0.388" style="fill:#009ee0;fill-rule:nonzero;"/>
                      <path d="M533.74,114.203c-2.321,-10.25 -12.371,-18.946 -25.13,-18.946c-12.762,0 -23.008,8.696 -25.325,18.946l50.455,0Zm16.237,19.909l-66.112,-0c3.479,10.441 15.27,17.208 29.579,17.208c9.087,-0 16.429,-2.704 21.071,-5.413c2.316,-1.354 4.833,-2.316 7.729,-2.316c6.575,-0 12.375,5.216 12.375,12.562c-0,5.221 -2.904,9.284 -8.504,12.371c-8.892,5.029 -23.205,7.154 -34.025,7.154c-33.059,0 -56.259,-21.646 -56.259,-52.579c0,-29.392 22.617,-53.362 53.167,-53.362c28.608,-0 52.192,24.75 52.192,48.908c-0,11.025 -5.413,15.467 -11.213,15.467" style="fill:#009ee0;fill-rule:nonzero;"/>
                      <path d="M612.239,152.672c8.312,-0 11.025,-3.288 11.025,-7.542c-0,-3.667 -2.325,-6.179 -6.963,-8.117l-15.662,-6.183c-18.175,-7.154 -27.063,-18.363 -27.063,-30.742c0,-15.271 13.338,-30.35 39.438,-30.35c13.916,0 25.9,2.509 33.441,9.275c2.325,2.129 3.675,5.417 3.675,8.317c0,6.187 -4.645,10.437 -10.633,10.437c-5.804,0 -9.283,-1.741 -13.925,-3.091c-4.633,-1.354 -9.079,-1.934 -12.175,-1.934c-6.767,0 -10.05,2.709 -10.05,7.346c0,3.288 1.738,5.996 6.958,7.925l18.367,7.542c18.942,7.542 24.163,18.367 24.163,28.617c-0,16.62 -13.338,31.504 -41.367,31.504c-14.113,-0 -26.683,-3.475 -35.383,-8.692c-4.834,-2.904 -7.342,-6.767 -7.342,-10.637c-0,-6.188 4.637,-11.017 10.629,-11.017c5.804,-0 8.892,2.512 15.467,4.446c5.025,1.546 12.179,2.896 17.4,2.896" style="fill:#009ee0;fill-rule:nonzero;"/>
                      <path d="M740.616,114.203c-2.321,-10.25 -12.375,-18.946 -25.133,-18.946c-12.758,0 -23.008,8.696 -25.325,18.946l50.458,0Zm16.234,19.909l-66.113,-0c3.479,10.441 15.271,17.208 29.579,17.208c9.088,-0 16.429,-2.704 21.075,-5.413c2.317,-1.354 4.829,-2.316 7.729,-2.316c6.575,-0 12.371,5.216 12.371,12.562c0,5.221 -2.9,9.284 -8.5,12.371c-8.896,5.029 -23.2,7.154 -34.029,7.154c-33.058,0 -56.258,-21.646 -56.258,-52.579c-0,-29.392 22.621,-53.362 53.171,-53.362c28.604,-0 52.195,24.75 52.195,48.908c0,11.025 -5.416,15.467 -11.22,15.467" style="fill:#009ee0;fill-rule:nonzero;"/>
                      <path d="M877.884,108.214l0,60.121c0,3.483 -1.933,5.412 -5.412,5.412l-16.821,0c-3.479,0 -5.413,-1.929 -5.413,-5.412l0,-52.009c0,-12.754 -7.929,-20.104 -19.141,-20.104c-11.013,0 -19.325,7.35 -19.325,20.104l-0,52.009c-0,3.483 -1.938,5.412 -5.421,5.412l-16.813,0c-3.483,0 -5.412,-1.929 -5.412,-5.412l-0,-84.68c-0,-7.733 6.179,-13.916 13.725,-13.916c6.958,-0 12.95,5.221 13.721,12.175c6.187,-6.954 15.854,-12.175 29.195,-12.175c23.775,-0 37.117,16.433 37.117,38.475" style="fill:#009ee0;fill-rule:nonzero;"/>
                      <path d="M936.087,152.672c8.313,-0 11.021,-3.288 11.021,-7.542c0,-3.667 -2.321,-6.179 -6.958,-8.117l-15.663,-6.183c-18.17,-7.154 -27.066,-18.363 -27.066,-30.742c-0,-15.271 13.341,-30.35 39.437,-30.35c13.921,0 25.904,2.509 33.446,9.275c2.321,2.129 3.671,5.417 3.671,8.317c-0,6.187 -4.638,10.437 -10.633,10.437c-5.8,0 -9.275,-1.741 -13.917,-3.091c-4.638,-1.354 -9.083,-1.934 -12.179,-1.934c-6.763,0 -10.054,2.709 -10.054,7.346c-0,3.288 1.741,5.996 6.962,7.925l18.363,7.542c18.945,7.542 24.166,18.367 24.166,28.617c0,16.62 -13.341,31.504 -41.371,31.504c-14.108,-0 -26.679,-3.475 -35.375,-8.692c-4.833,-2.904 -7.345,-6.767 -7.345,-10.637c-0,-6.188 4.637,-11.017 10.629,-11.017c5.8,-0 8.891,2.512 15.471,4.446c5.02,1.546 12.175,2.896 17.395,2.896" style="fill:#009ee0;fill-rule:nonzero;"/>
                      <path d="M1021.35,166.205c0,5.029 -2.517,7.542 -7.346,7.542l-12.758,-0c-5.025,-0 -7.542,-2.513 -7.542,-7.542l0,-82.55c0,-7.733 6.192,-13.917 13.729,-13.917c7.734,0 13.917,6.184 13.917,13.917l0,82.55Zm-29.967,-122.954c0,-8.696 7.155,-15.85 16.05,-15.85c8.696,-0 15.855,7.154 15.855,15.85c-0,8.896 -7.159,16.05 -15.855,16.05c-8.895,-0 -16.05,-7.154 -16.05,-16.05" style="fill:#009ee0;fill-rule:nonzero;"/>
                    </svg>
                @endif
            </div>
        </div>

        <!-- App Name -->
        <h1 class="app-name">{{ \App\Models\Setting::get('app_name', config('app.name')) }}</h1>
        <p class="app-tagline">Sistem Absensi Modern & Terpercaya</p>

        <!-- Loading -->
        <div class="loading-container">
            <!-- Modern Loader -->
            <div class="loader">
                <div class="loader-ring"></div>
                <div class="loader-ring"></div>
                <div class="loader-ring"></div>
            </div>
            
            <p class="loading-text">
                Memuat aplikasi<span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>
            </p>
            
            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>
        </div>

        <!-- Version Info -->
        <div class="version-info">
            Version 2.0 • © {{ date('Y') }}
        </div>
    </div>

    <script>
        // Auto redirect after animation completes
        setTimeout(function() {
            document.getElementById('splash').classList.add('fade-out');
            
            setTimeout(function() {
                window.location.href = '{{ route("login") }}';
            }, 600);
        }, 2500);
    </script>
</body>
</html>
