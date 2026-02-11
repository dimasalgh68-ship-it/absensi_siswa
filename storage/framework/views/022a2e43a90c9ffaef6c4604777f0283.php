<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Absensi Siswa')); ?></title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .splash-container {
            text-align: center;
            color: white;
        }

        .logo-container {
            margin-bottom: 2rem;
            animation: zoomIn 0.6s ease-out;
        }

        .logo {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .logo svg {
            width: 70px;
            height: 70px;
            fill: #667eea;
        }

        .app-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            animation: fadeIn 0.8s ease-out 0.3s both;
        }

        .loading-container {
            margin-top: 3rem;
            animation: fadeIn 0.8s ease-out 0.6s both;
        }

        /* UIverse.io Loader */
        .loader {
            width: 60px;
            height: 60px;
            margin: 0 auto 1.5rem;
            position: relative;
        }

        .loader:before,
        .loader:after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 4px solid transparent;
            border-top-color: white;
            animation: spin 1.5s linear infinite;
        }

        .loader:after {
            border-top-color: rgba(255, 255, 255, 0.3);
            animation-duration: 2s;
            animation-direction: reverse;
        }

        .loader span {
            position: absolute;
            inset: 10px;
            border-radius: 50%;
            border: 4px solid transparent;
            border-top-color: rgba(255, 255, 255, 0.5);
            animation: spin 2.5s linear infinite;
        }

        .loading-text {
            font-size: 1rem;
            opacity: 0.9;
            animation: pulse 1.5s ease-in-out infinite;
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

        @keyframes zoomIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 0.9;
            }
            50% {
                opacity: 0.6;
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

        .fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: scale(0.95);
            }
        }

        /* Progress bar */
        .progress-container {
            width: 200px;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            margin: 2rem auto 0;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: white;
            border-radius: 2px;
            animation: progress 2s ease-out forwards;
        }

        @keyframes progress {
            from {
                width: 0%;
            }
            to {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .app-name {
                font-size: 1.5rem;
            }
            
            .logo {
                width: 100px;
                height: 100px;
            }
            
            .logo svg {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="splash-container" id="splash">
        <!-- App Name -->
        <h1 class="app-name"><?php echo e(\App\Models\Setting::get('app_name', config('app.name'))); ?></h1>

        <!-- Loading -->
        <div class="loading-container">
            <!-- UIverse.io Loader -->
            <div class="loader">
                <span></span>
            </div>
            
            <p class="loading-text">
                Memuat aplikasi<span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>
            </p>
            
            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>
        </div>
    </div>

    <script>
        // Auto redirect after 2 seconds
        setTimeout(function() {
            document.getElementById('splash').classList.add('fade-out');
            
            setTimeout(function() {
                window.location.href = '<?php echo e(route("login")); ?>';
            }, 500);
        }, 2000);
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/splash.blade.php ENDPATH**/ ?>