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
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
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
                    <img src="/logo-pin.svg" alt="E-ABSENSI" style="width: 80%; height: 80%; object-fit: contain;" />
                @endif
            </div>
        </div>

        <!-- App Name -->
        <h1 class="app-name">E-ABSENSI</h1>
        <p class="app-tagline">Sistem Absensi Digital Modern</p>

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
