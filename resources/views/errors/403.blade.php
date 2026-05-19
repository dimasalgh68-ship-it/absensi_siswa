<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden - Akses Ditolak</title>
    <!-- Use Tailwind from Vite or fallback to CDN for standalone error page -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .progress-bar {
            height: 4px;
            background-color: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }
        .progress-bar-fill {
            height: 100%;
            background-color: #4f46e5;
            width: 0%;
            animation: fillProgress 3s linear forwards;
        }
        @keyframes fillProgress {
            0% { width: 0%; }
            100% { width: 100%; }
        }
        .pulse-icon {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden text-center p-8 border border-slate-100">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 text-red-500 mb-6 pulse-icon">
            <i class="fas fa-lock fa-2x"></i>
        </div>
        
        <h1 class="text-4xl font-bold text-slate-800 mb-2">403</h1>
        <h2 class="text-xl font-semibold text-slate-700 mb-4">Akses Ditolak</h2>
        
        <p class="text-slate-500 mb-8">
            Maaf, Anda tidak memiliki izin (hak akses) untuk membuka halaman ini.
        </p>
        
        <div class="mb-6">
            <p class="text-sm text-slate-400 mb-3 font-medium">Mengarahkan kembali dalam <span id="countdown" class="text-indigo-600 font-bold">3</span> detik...</p>
            <div class="progress-bar">
                <div class="progress-bar-fill"></div>
            </div>
        </div>
        
        <button onclick="goBack()" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors focus:ring-4 focus:ring-indigo-100 outline-none">
            <i class="fas fa-arrow-left mr-2"></i> Kembali Sekarang
        </button>
    </div>

    <script>
        let seconds = 3;
        const countdownEl = document.getElementById('countdown');
        
        const interval = setInterval(() => {
            seconds--;
            if (seconds > 0) {
                countdownEl.textContent = seconds;
            } else {
                clearInterval(interval);
            }
        }, 1000);

        function goBack() {
            // Check if there is a referrer to go back to
            if (document.referrer && document.referrer !== window.location.href) {
                window.history.back();
            } else {
                // Fallback to home/dashboard
                window.location.href = "{{ url('/') }}";
            }
        }

        // Auto redirect after 3 seconds
        setTimeout(() => {
            goBack();
        }, 3000);
    </script>
</body>
</html>
