<x-app-layout>
    <style>
        /* Smooth scrolling optimization */
        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
            max-width: 100vw;
        }
        
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        
        /* Reduce repaints during scroll */
        video, canvas {
            will-change: transform;
        }

        /* Loading Screen Styles */
        #loadingScreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }

        #loadingScreen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-container {
            text-align: center;
        }

        .face-loader {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            position: relative;
        }

        .face-circle {
            width: 100%;
            height: 100%;
            border: 4px solid #e5e7eb;
            border-top-color: #009ee0;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .face-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.8; }
        }

        .loading-text {
            color: #1f2937;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 12px;
            animation: fadeInOut 2s ease-in-out infinite;
        }

        .loading-subtext {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 30px;
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .loading-dots {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .loading-dot {
            width: 12px;
            height: 12px;
            background: #009ee0;
            border-radius: 50%;
            animation: bounce 1.4s ease-in-out infinite;
        }

        .loading-dot:nth-child(1) { animation-delay: 0s; }
        .loading-dot:nth-child(2) { animation-delay: 0.2s; }
        .loading-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1.2); opacity: 1; }
        }

        .progress-bar-container {
            width: 300px;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 20px;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #009ee0, #48cae4);
            border-radius: 2px;
            width: 0%;
            animation: progress 3s ease-in-out forwards;
        }

        @keyframes progress {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }
    </style>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Absensi Face Recognition') }}
        </h2>
    </x-slot>

    <!-- Loading Screen -->
    <div id="loadingScreen">
        <div class="loader-container">
            <div class="face-loader">
                <div class="face-circle"></div>
                <div class="face-icon">👤</div>
            </div>
            <div class="loading-text" id="loadingText">Memuat Sistem Face Recognition</div>
            <div class="loading-subtext" id="loadingSubtext">Mohon tunggu sebentar...</div>
            <div class="loading-dots">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar" id="progressBar"></div>
            </div>
        </div>
    </div>

    @php
        $faceThreshold = \App\Models\Setting::get('face_similarity_threshold', 70);
        $distanceThreshold = (100 - $faceThreshold) / 100; // Convert percentage to distance
    @endphp

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (!$hasFaceRegistration)
                <!-- Belum registrasi wajah -->
                <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-400 text-yellow-800 dark:text-yellow-200 px-6 py-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="font-bold">Wajah Belum Terdaftar</p>
                            <p class="text-sm">Anda harus mendaftarkan wajah terlebih dahulu sebelum dapat melakukan absensi.</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('face-registration.index') }}" 
                           class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Status Absensi Hari Ini -->
                    @if ($todayAttendance)
                        <div class="mb-6 bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                            <h3 class="font-bold text-green-900 dark:text-green-100 mb-2">Status Absensi Hari Ini</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-green-700 dark:text-green-300">Absen Masuk:</span>
                                    <span class="font-bold text-green-900 dark:text-green-100">
                                        {{ $todayAttendance->time_in ?? '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-green-700 dark:text-green-300">Absen Keluar:</span>
                                    <span class="font-bold text-green-900 dark:text-green-100">
                                        {{ $todayAttendance->time_out ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Countdown Widget -->
                    @php
                        $shift = \App\Models\Shift::first();
                        $clockInDeadline = null;
                        $clockOutTime = null;
                        $clockInOpenTime = null;
                        $now = \Carbon\Carbon::now();
                        $deadlineType = 'waiting';
                        
                        if ($shift) {
                            $clockInEarlyMinutes = (int) \App\Models\Setting::get('clock_in_early_minutes', 60);
                            $clockInLateMinutes = (int) \App\Models\Setting::get('clock_in_late_minutes', 120);
                            $scheduleStartTime = \Carbon\Carbon::today()->setTimeFromTimeString($shift->start_time);
                            
                            $clockInOpenTime = $scheduleStartTime->copy()->subMinutes($clockInEarlyMinutes);
                            $clockInDeadline = $scheduleStartTime->copy()->addMinutes($clockInLateMinutes);
                            $clockOutTime = \Carbon\Carbon::today()->setTimeFromTimeString($shift->end_time);
                            
                            // Determine deadline type
                            if (!$todayAttendance) {
                                if ($now->lt($clockInOpenTime)) {
                                    $deadlineType = 'waiting';
                                } elseif ($now->gte($clockInOpenTime) && $now->lt($clockInDeadline)) {
                                    $deadlineType = 'clock_in';
                                } else {
                                    $deadlineType = 'next_day';
                                }
                            } elseif ($todayAttendance && !$todayAttendance->time_out) {
                                if ($now->lt($clockOutTime)) {
                                    $deadlineType = 'clock_out';
                                } else {
                                    $deadlineType = 'next_day';
                                }
                            } else {
                                $deadlineType = 'next_day';
                            }
                        }
                    @endphp

                    @if($shift)
                    <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-5 rounded-xl border border-blue-200 dark:border-blue-800 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br 
                                    @if($deadlineType === 'waiting') from-gray-500 to-gray-600
                                    @elseif($deadlineType === 'clock_in') from-red-500 to-orange-500
                                    @elseif($deadlineType === 'clock_out') from-blue-500 to-indigo-500
                                    @else from-purple-500 to-indigo-500
                                    @endif
                                    flex items-center justify-center text-white shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">
                                        @if($deadlineType === 'waiting') Menunggu Waktu Absensi
                                        @elseif($deadlineType === 'clock_in') Batas Waktu Absensi Masuk
                                        @elseif($deadlineType === 'clock_out') Waktu Absensi Keluar
                                        @else Absensi Besok
                                        @endif
                                    </h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        Shift: {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div id="scan-countdown-display" class="text-2xl font-bold 
                                    @if($deadlineType === 'waiting') text-gray-600 dark:text-gray-400
                                    @elseif($deadlineType === 'clock_in') text-red-600 dark:text-red-400
                                    @elseif($deadlineType === 'clock_out') text-blue-600 dark:text-blue-400
                                    @else text-purple-600 dark:text-purple-400
                                    @endif
                                    tabular-nums font-mono">--:--:--</div>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">
                                    @if($deadlineType === 'waiting') Dibuka Dalam
                                    @else Sisa Waktu
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                            <div id="scan-countdown-progress" class="h-full 
                                @if($deadlineType === 'waiting') bg-gradient-to-r from-gray-500 to-gray-600
                                @elseif($deadlineType === 'clock_in') bg-gradient-to-r from-red-500 to-orange-500
                                @elseif($deadlineType === 'clock_out') bg-gradient-to-r from-blue-500 to-indigo-500
                                @else bg-gradient-to-r from-purple-500 to-indigo-500
                                @endif
                                transition-all duration-1000" style="width: 100%"></div>
                        </div>
                        <div class="mt-3 flex justify-between text-xs text-gray-600 dark:text-gray-400">
                            <span>Buka: <strong class="text-green-600 dark:text-green-400">{{ $clockInOpenTime->format('H:i') }}</strong></span>
                            <span>Deadline: <strong class="text-red-600 dark:text-red-400">{{ $clockInDeadline->format('H:i') }}</strong></span>
                            <span>Keluar: <strong class="text-blue-600 dark:text-blue-400">{{ $clockOutTime->format('H:i') }}</strong></span>
                        </div>
                    </div>

                    @push('scripts')
                    <script>
                        // Scan Page Countdown
                        @php
                            $targetTime = null;
                            if ($deadlineType === 'waiting') {
                                $targetTime = $clockInOpenTime;
                            } elseif ($deadlineType === 'clock_in') {
                                $targetTime = $clockInDeadline;
                            } elseif ($deadlineType === 'clock_out') {
                                $targetTime = $clockOutTime;
                            } else {
                                $targetTime = $clockInOpenTime->copy()->addDay();
                            }
                        @endphp
                        
                        const scanDeadlineTime = new Date('{{ $targetTime->format('Y-m-d H:i:s') }}').getTime();
                        const scanDeadlineType = '{{ $deadlineType }}';
                        const scanStartOfDay = new Date('{{ $targetTime->copy()->startOfDay()->format('Y-m-d H:i:s') }}').getTime();
                        const scanTotalDuration = scanDeadlineTime - scanStartOfDay;
                        
                        function updateScanCountdown() {
                            const now = new Date().getTime();
                            const distance = scanDeadlineTime - now;
                            
                            const display = document.getElementById('scan-countdown-display');
                            const progress = document.getElementById('scan-countdown-progress');
                            
                            if (!display || !progress) return;
                            
                            if (distance < 0) {
                                display.textContent = 'MEMUAT...';
                                setTimeout(() => window.location.reload(), 2000);
                                return;
                            }
                            
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            
                            display.textContent = 
                                String(hours).padStart(2, '0') + ':' + 
                                String(minutes).padStart(2, '0') + ':' + 
                                String(seconds).padStart(2, '0');
                            
                            const percentage = Math.min(Math.max((distance / scanTotalDuration) * 100, 0), 100);
                            progress.style.width = percentage + '%';
                            
                            // Update color based on percentage
                            if (scanDeadlineType === 'clock_in') {
                                if (percentage < 25) {
                                    progress.className = 'h-full bg-gradient-to-r from-red-600 to-red-500 transition-all duration-1000';
                                } else if (percentage < 50) {
                                    progress.className = 'h-full bg-gradient-to-r from-orange-500 to-red-500 transition-all duration-1000';
                                } else {
                                    progress.className = 'h-full bg-gradient-to-r from-yellow-500 to-orange-500 transition-all duration-1000';
                                }
                            }
                        }
                        
                        updateScanCountdown();
                        setInterval(updateScanCountdown, 1000);
                        
                        // Auto-enable/disable buttons based on time
                        function updateButtonStates() {
                            const now = new Date().getTime();
                            const clockInBtn = document.getElementById('clockInBtn');
                            const clockOutBtn = document.getElementById('clockOutBtn');
                            
                            if (!clockInBtn || !clockOutBtn) return;
                            
                            // Check if buttons are disabled due to time (not due to other reasons)
                            const clockInDisabledByTime = clockInBtn.hasAttribute('data-reason') && clockInBtn.getAttribute('data-reason') === 'time';
                            const clockOutDisabledByTime = clockOutBtn.hasAttribute('data-reason') && clockOutBtn.getAttribute('data-reason') === 'time';
                            
                            // Clock In button logic
                            if (clockInDisabledByTime) {
                                const clockInOpenTime = new Date('{{ $clockInOpenTime->format('Y-m-d H:i:s') }}').getTime();
                                const clockInDeadlineTime = new Date('{{ $clockInDeadline->format('Y-m-d H:i:s') }}').getTime();
                                
                                if (now >= clockInOpenTime && now < clockInDeadlineTime) {
                                    // Time to enable clock in
                                    clockInBtn.disabled = false;
                                    clockInBtn.removeAttribute('data-reason');
                                    clockInBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                                    clockInBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                                    
                                    // Show notification
                                    if (window.scanNotificationShown !== true) {
                                        speak('Waktu absensi masuk telah dibuka. Silakan lakukan absensi.');
                                        window.scanNotificationShown = true;
                                    }
                                }
                            }
                            
                            // Clock Out button logic
                            if (clockOutDisabledByTime) {
                                const clockOutTime = new Date('{{ $clockOutTime->format('Y-m-d H:i:s') }}').getTime();
                                
                                @if($todayAttendance && $todayAttendance->time_in && !$todayAttendance->time_out)
                                if (now < clockOutTime) {
                                    // Time to enable clock out
                                    clockOutBtn.disabled = false;
                                    clockOutBtn.removeAttribute('data-reason');
                                    clockOutBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                                    clockOutBtn.classList.add('bg-red-600', 'hover:bg-red-700');
                                }
                                @endif
                            }
                        }
                        
                        // Check button states every second
                        setInterval(updateButtonStates, 1000);
                        updateButtonStates(); // Initial check
                    </script>
                    @endpush
                    @endif

                    <div class="max-w-3xl mx-auto">
                        <!-- Camera Preview -->
                        <div class="mb-6">
                            <div class="relative bg-gray-900 rounded-lg overflow-hidden" style="aspect-ratio: 1/1;">
                                <video id="camera" autoplay playsinline class="w-full h-full object-cover" style="transform: scaleX(-1);"></video>
                                <canvas id="canvas" class="hidden"></canvas>
                                <canvas id="overlay" class="absolute top-0 left-0 w-full h-full pointer-events-none" style="transform: scaleX(-1);"></canvas>
                                
                                <!-- Face Detection Status -->
                                <div id="faceStatus" class="absolute top-4 left-4 bg-black/70 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    <span id="faceStatusIcon">�</span>
                                    <span id="faceStatusText">Memuat model...</span>
                                </div>
                                
                                <!-- GPS Status Indicator -->
                                <div id="gpsStatus" class="absolute top-4 right-4 bg-black bg-opacity-50 text-white px-3 py-2 rounded-lg text-sm">
                                    <span id="gpsIcon">📍</span>
                                    <span id="gpsText">Mencari lokasi...</span>
                                </div>
                                
                                <!-- Sound Toggle Button -->
                                <button id="soundToggle" 
                                        class="absolute bottom-4 right-4 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition z-10"
                                        title="Toggle Suara">
                                    <span id="soundIcon">🔊</span>
                                </button>
                            </div>
                        </div>

                        
                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-4">
                            <button id="clockInBtn" 
                                    @if (!$hasFaceRegistration || ($todayAttendance && $todayAttendance->time_in)) disabled 
                                    @elseif($deadlineType === 'waiting' || $deadlineType === 'next_day') disabled data-reason="time"
                                    @endif
                                    class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-4 px-6 rounded-lg transition relative group">
                                <span class="flex items-center justify-center gap-2">
                                    🕐 Absen Masuk
                                </span>
                                @if($deadlineType === 'waiting')
                                <span class="absolute -top-2 -right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">
                                    Belum Waktunya
                                </span>
                                @endif
                            </button>
                            
                            <button id="clockOutBtn" 
                                    @if (!$hasFaceRegistration || !$todayAttendance || !$todayAttendance->time_in || ($todayAttendance && $todayAttendance->time_out)) disabled 
                                    @elseif($deadlineType !== 'clock_out') disabled data-reason="time"
                                    @endif
                                    class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-4 px-6 rounded-lg transition relative group">
                                <span class="flex items-center justify-center gap-2">
                                    🕐 Absen Keluar
                                </span>
                                @if($todayAttendance && $todayAttendance->time_in && !$todayAttendance->time_out && $deadlineType === 'clock_out')
                                <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">
                                    Aktif
                                </span>
                                @endif
                            </button>
                        </div>

                        <!-- Time Status Info -->
                        @if($deadlineType === 'waiting')
                        <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <div>
                                    <p class="font-bold text-yellow-800 dark:text-yellow-200 text-sm">Belum Waktunya Absen</p>
                                    <p class="text-yellow-700 dark:text-yellow-300 text-xs mt-1">
                                        Absensi akan dibuka pada <strong>{{ $clockInOpenTime->format('H:i') }} WIB</strong>. 
                                        Silakan tunggu countdown di atas.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @elseif($deadlineType === 'clock_in' && !$todayAttendance)
                        <div class="mt-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-bold text-green-800 dark:text-green-200 text-sm">Siap Absen Masuk</p>
                                    <p class="text-green-700 dark:text-green-300 text-xs mt-1">
                                        Anda dapat melakukan absensi masuk sekarang. Deadline: <strong>{{ $clockInDeadline->format('H:i') }} WIB</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @elseif($deadlineType === 'clock_out' && $todayAttendance && !$todayAttendance->time_out)
                        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <div>
                                    <p class="font-bold text-blue-800 dark:text-blue-200 text-sm">Siap Absen Keluar</p>
                                    <p class="text-blue-700 dark:text-blue-300 text-xs mt-1">
                                        Anda dapat melakukan absensi keluar sekarang. Jam keluar: <strong>{{ $clockOutTime->format('H:i') }} WIB</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Location Info -->
                        <div id="locationInfo" class="hidden mb-6 bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                            <h4 class="font-bold text-blue-900 dark:text-blue-100 mb-2">Informasi Lokasi:</h4>
                            <p class="text-blue-800 dark:text-blue-200 text-sm">
                                <span id="locationText"></span>
                            </p>
                        </div>

                        <!-- Error Message -->
                        <div id="errorMessage" class="hidden mb-6 bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                            <p class="text-red-800 dark:text-red-200 font-bold" id="errorText"></p>
                        </div>

                        <!-- Success Message -->
                        <div id="successMessage" class="hidden mb-6 bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                            <p class="text-green-800 dark:text-green-200 font-bold" id="successText"></p>
                            <div id="successDetails" class="mt-2 text-sm text-green-700 dark:text-green-300"></div>
                        </div>

                        <!-- Instructions -->
                        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-bold text-gray-900 dark:text-gray-100 mb-2">Cara Absensi:</h4>
                            <ol class="list-decimal list-inside text-gray-700 dark:text-gray-300 space-y-1 text-sm">
                                <li>Pastikan GPS aktif dan lokasi terdeteksi</li>
                                <li>Posisikan wajah di tengah kamera</li>
                                <li>Tekan tombol Absen Masuk atau Absen Keluar</li>
                                <li>Tunggu proses verifikasi selesai</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Face Recognition Library -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.11.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface@0.0.7"></script>
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.12/dist/face-api.min.js"></script>
    
    <script>
        const video = document.getElementById('camera');
        const canvas = document.getElementById('canvas');
        const overlay = document.getElementById('overlay');
        const clockInBtn = document.getElementById('clockInBtn');
        const clockOutBtn = document.getElementById('clockOutBtn');
        const gpsStatus = document.getElementById('gpsStatus');
        const gpsIcon = document.getElementById('gpsIcon');
        const gpsText = document.getElementById('gpsText');
        const locationInfo = document.getElementById('locationInfo');
        const locationText = document.getElementById('locationText');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const successMessage = document.getElementById('successMessage');
        const successText = document.getElementById('successText');
        const successDetails = document.getElementById('successDetails');
        const faceStatus = document.getElementById('faceStatus');
        const faceStatusIcon = document.getElementById('faceStatusIcon');
        const faceStatusText = document.getElementById('faceStatusText');
        const soundToggle = document.getElementById('soundToggle');
        const soundIcon = document.getElementById('soundIcon');

        let currentPosition = null;
        let stream = null;
        let faceApiLoaded = false;
        let registeredDescriptor = null;
        let detectionInterval = null;
        let faceDetected = false;
        let hasSpokenFaceDetected = false; // Track if we've spoken about face detection
        let soundEnabled = true; // Sound is enabled by default

        // Loading Screen Management
        const loadingScreen = document.getElementById('loadingScreen');
        const loadingText = document.getElementById('loadingText');
        const loadingSubtext = document.getElementById('loadingSubtext');
        const progressBar = document.getElementById('progressBar');
        
        let loadingProgress = 0;
        const loadingSteps = [
            { progress: 20, text: 'Mengakses Kamera', subtext: 'Meminta izin akses kamera...' },
            { progress: 40, text: 'Memuat Model AI', subtext: 'Mengunduh model face recognition...' },
            { progress: 60, text: 'Mendeteksi Lokasi GPS', subtext: 'Mendapatkan koordinat lokasi...' },
            { progress: 80, text: 'Memuat Data Wajah', subtext: 'Mengambil data registrasi wajah...' },
            { progress: 100, text: 'Siap!', subtext: 'Sistem face recognition siap digunakan' }
        ];
        
        let currentStepIndex = 0;

        function updateLoadingProgress(step) {
            if (step < loadingSteps.length) {
                const stepData = loadingSteps[step];
                loadingText.textContent = stepData.text;
                loadingSubtext.textContent = stepData.subtext;
                progressBar.style.width = stepData.progress + '%';
                currentStepIndex = step;
            }
        }

        function hideLoadingScreen() {
            // Ensure progress is at 100%
            if (currentStepIndex < 4) {
                updateLoadingProgress(4);
            }
            
            setTimeout(() => {
                loadingScreen.classList.add('hidden');
            }, 500);
        }

        // Load face-api models
        async function loadFaceApi() {
            try {
                console.log('Loading face-api models...');
                updateLoadingProgress(1); // Step 2: Loading AI Models
                
                const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.12/model';
                
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68TinyNet.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                
                faceApiLoaded = true;
                console.log('Face-api models loaded!');
                
                updateLoadingProgress(2); // Step 3: GPS (skip for now)
                
                // Load registered face descriptor
                await loadRegisteredFace();
                
                // Start detection only if descriptor loaded
                if (registeredDescriptor) {
                    startFaceDetection();
                }
            } catch (err) {
                console.error('Failed to load face-api:', err);
                updateLoadingProgress(4); // Complete progress
                setTimeout(() => {
                    hideLoadingScreen();
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Model AI',
                            text: 'Terjadi kesalahan saat memuat model face recognition: ' + err.message,
                            confirmButtonText: 'Kembali',
                            confirmButtonColor: '#6b7280',
                            allowOutsideClick: false
                        }).then(() => {
                            window.history.back();
                        });
                    }, 300);
                }, 800);
            }
        }

        // Load registered face from server
        async function loadRegisteredFace() {
            try {
                updateLoadingProgress(3); // Step 4: Loading Face Data
                
                const response = await fetch('/api/face-registration/descriptor');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                console.log('Loading face descriptor...'); // Debug log
                
                if (data.success && data.descriptor) {
                    registeredDescriptor = new Float32Array(data.descriptor);
                    console.log('Registered face loaded successfully');
                    updateLoadingProgress(4); // Step 5: Ready!
                    setTimeout(hideLoadingScreen, 800);
                } else {
                    // This shouldn't happen since we checked in initialize()
                    throw new Error('Face descriptor not found');
                }
            } catch (err) {
                console.error('Failed to load registered face:', err);
                updateLoadingProgress(4);
                
                setTimeout(() => {
                    hideLoadingScreen();
                    
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Data',
                            text: 'Terjadi kesalahan saat memuat data wajah: ' + err.message,
                            confirmButtonText: 'Kembali',
                            confirmButtonColor: '#6b7280',
                            allowOutsideClick: false
                        }).then(() => {
                            window.history.back();
                        });
                    }, 300);
                }, 800);
            }
        }

        // Detect faces in video
        async function detectFaces() {
            if (!faceApiLoaded || !video.videoWidth) return;

            try {
                const detection = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks(true);

                // Use requestAnimationFrame for smoother rendering
                requestAnimationFrame(() => {
                    // Draw on overlay canvas
                    const ctx = overlay.getContext('2d');
                    
                    // Only resize canvas if dimensions changed
                    if (overlay.width !== video.videoWidth || overlay.height !== video.videoHeight) {
                        overlay.width = video.videoWidth;
                        overlay.height = video.videoHeight;
                    }
                    
                    ctx.clearRect(0, 0, overlay.width, overlay.height);
                
                if (!detection) {
                    // No face detected
                    if (faceDetected) {
                        // Face was detected before, now lost
                        hasSpokenFaceDetected = false;
                        faceDetected = false;
                        faceStatusIcon.textContent = '❌';
                        faceStatusText.textContent = 'Wajah tidak terdeteksi';
                        faceStatus.className = 'absolute top-4 left-4 bg-red-600/90 text-white px-4 py-2 rounded-lg text-sm font-semibold';
                    }
                } else {
                    // Face detected - no audio notification
                    if (!faceDetected) {
                        faceDetected = true;
                        faceStatusIcon.textContent = '✅';
                        faceStatusText.textContent = ' terdeteksi - Siap!';
                        faceStatus.className = 'absolute top-4 left-4 bg-green-600/90 text-white px-4 py-2 rounded-lg text-sm font-semibold';
                    }
                    
                        // Draw face box
                        drawFaceBox(ctx, detection.detection.box);
                    }
                });
            } catch (err) {
                console.error('Face detection error:', err);
            }
        }

        // Draw face bounding box (optimized)
        function drawFaceBox(ctx, box) {
            const { x, y, width, height } = box;
            
            // Increase box size by 20% for better visibility
            const padding = Math.min(width, height) * 0.1;
            const newX = x - padding;
            const newY = y - padding;
            const newWidth = width + (padding * 2);
            const newHeight = height + (padding * 2);
            
            // Set styles once
            ctx.strokeStyle = '#10b981'; // Green color
            ctx.lineWidth = 4;
            
            // Draw main box
            ctx.strokeRect(newX, newY, newWidth, newHeight);
            
            // Draw corners in one path for better performance
            const cornerLength = 30;
            ctx.lineWidth = 5;
            ctx.beginPath();
            
            // Top-left corner
            ctx.moveTo(newX, newY + cornerLength);
            ctx.lineTo(newX, newY);
            ctx.lineTo(newX + cornerLength, newY);
            
            // Top-right corner
            ctx.moveTo(newX + newWidth - cornerLength, newY);
            ctx.lineTo(newX + newWidth, newY);
            ctx.lineTo(newX + newWidth, newY + cornerLength);
            
            // Bottom-left corner
            ctx.moveTo(newX, newY + newHeight - cornerLength);
            ctx.lineTo(newX, newY + newHeight);
            ctx.lineTo(newX + cornerLength, newY + newHeight);
            
            // Bottom-right corner
            ctx.moveTo(newX + newWidth - cornerLength, newY + newHeight);
            ctx.lineTo(newX + newWidth, newY + newHeight);
            ctx.lineTo(newX + newWidth, newY + newHeight - cornerLength);
            
            ctx.stroke();
        }

        // Start continuous face detection
        function startFaceDetection() {
            if (detectionInterval) clearInterval(detectionInterval);
            detectionInterval = setInterval(detectFaces, 1000); // Increased to 1000ms for smoother scrolling
        }

        // Stop face detection
        function stopFaceDetection() {
            if (detectionInterval) {
                clearInterval(detectionInterval);
                detectionInterval = null;
            }
        }

        // Detect and verify face
        async function detectAndVerifyFace() {
            if (!faceApiLoaded) {
                showError('Face recognition belum siap');
                return null;
            }

            if (!registeredDescriptor) {
                showError('Wajah belum terdaftar. Silakan daftarkan wajah Anda terlebih dahulu.');
                
                // Show redirect dialog
                setTimeout(() => {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Wajah Belum Terdaftar',
                        text: 'Silakan daftarkan wajah Anda terlebih dahulu untuk dapat melakukan absensi.',
                        showCancelButton: true,
                        confirmButtonText: 'Daftar Wajah',
                        cancelButtonText: 'Kembali',
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/face-registration';
                        } else {
                            window.history.back();
                        }
                    });
                }, 500);
                
                return null;
            }

            try {
                // Detect face and get descriptor
                const detection = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks(true)
                    .withFaceDescriptor();

                if (!detection) {
                    showError(' tidak terdeteksi. Pastikan wajah Anda terlihat jelas.');
                    return null;
                }

                // Calculate distance (similarity)
                const distance = faceapi.euclideanDistance(
                    registeredDescriptor,
                    detection.descriptor
                );

                // Convert distance to similarity percentage
                const similarity = Math.max(0, (1 - distance) * 100);

                console.log('Face verification:', { distance, similarity: similarity.toFixed(2) });

                // Get threshold from server
                const thresholdPercentage = {{ $faceThreshold }};
                const distanceThreshold = {{ $distanceThreshold }};

                // Check if match
                if (distance > distanceThreshold) {
                    showError(`Wajah tidak cocok (similarity: ${similarity.toFixed(1)}%). Minimum ${thresholdPercentage}% diperlukan.`);
                    return null;
                }

                return {
                    matched: true,
                    similarity: similarity.toFixed(2),
                    distance: distance.toFixed(4)
                };

            } catch (err) {
                console.error('Face verification error:', err);
                showError('Gagal memverifikasi wajah: ' + err.message);
                return null;
            }
        }

        // Start camera
        async function startCamera() {
            try {
                updateLoadingProgress(0); // Step 1: Accessing Camera
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'user',
                        width: { ideal: 480 },  // Reduced resolution for better performance
                        height: { ideal: 360 },
                        frameRate: { ideal: 15, max: 20 }  // Lower frame rate for smoother scrolling
                    } 
                });
                video.srcObject = stream;
                
                // Wait for video to be ready
                video.onloadedmetadata = () => {
                    loadFaceApi();
                };
            } catch (err) {
                showError('Tidak dapat mengakses kamera: ' + err.message);
                hideLoadingScreen();
            }
        }

        // Get GPS location
        function getLocation() {
            if (!navigator.geolocation) {
                showError('Browser Anda tidak mendukung GPS');
                return;
            }

            updateLoadingProgress(2); // Step 3: Detecting GPS Location
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    currentPosition = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    };
                    
                    gpsIcon.textContent = '✅';
                    gpsText.textContent = 'Lokasi terdeteksi';
                    gpsStatus.classList.remove('bg-opacity-50');
                    gpsStatus.classList.add('bg-green-600');
                    
                    if (locationInfo) {
                        locationInfo.classList.remove('hidden');
                        locationText.textContent = `Lat: ${currentPosition.latitude.toFixed(6)}, Long: ${currentPosition.longitude.toFixed(6)}`;
                    }
                },
                (error) => {
                    gpsIcon.textContent = '❌';
                    gpsText.textContent = 'GPS tidak tersedia';
                    gpsStatus.classList.add('bg-red-600');
                    showError('Tidak dapat mengakses GPS. Pastikan GPS aktif dan izin lokasi diberikan.');
                }
            );
        }

        // Capture and submit attendance
        async function submitAttendance(type) {
            if (!currentPosition) {
                showError('Lokasi belum terdeteksi. Pastikan GPS aktif.');
                return;
            }

            if (!faceApiLoaded) {
                showError('Face recognition belum siap. Tunggu sebentar...');
                return;
            }

            if (!faceDetected) {
                showError('Pastikan wajah Anda terdeteksi sebelum melakukan absensi.');
                return;
            }

            // Disable buttons
            clockInBtn.disabled = true;
            clockOutBtn.disabled = true;
            clockInBtn.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memverifikasi...</span>';
            clockOutBtn.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memverifikasi...</span>';

            // Verify face first
            const verification = await detectAndVerifyFace();
            
            if (!verification) {
                // Reset buttons
                clockInBtn.disabled = false;
                clockOutBtn.disabled = false;
                clockInBtn.innerHTML = '🕐 Absen Masuk';
                clockOutBtn.innerHTML = '🕐 Absen Keluar';
                return;
            }

            // Capture photo
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            canvas.toBlob(async (blob) => {
                const formData = new FormData();
                formData.append('photo', blob, 'face.jpg');
                formData.append('latitude', currentPosition.latitude);
                formData.append('longitude', currentPosition.longitude);
                formData.append('type', type);
                formData.append('similarity', verification.similarity);
                formData.append('verified_in_browser', 'true');
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    const response = await fetch('{{ route("face-attendance.store") }}', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        showSuccess(data.message, {
                            similarity: verification.similarity,
                            location: data.data?.location || {}
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    } else {
                        showError(data.message);
                        clockInBtn.disabled = false;
                        clockOutBtn.disabled = false;
                        clockInBtn.innerHTML = '🕐 Absen Masuk';
                        clockOutBtn.innerHTML = '🕐 Absen Keluar';
                    }
                } catch (error) {
                    showError('Terjadi kesalahan: ' + error.message);
                    clockInBtn.disabled = false;
                    clockOutBtn.disabled = false;
                    clockInBtn.innerHTML = '🕐 Absen Masuk';
                    clockOutBtn.innerHTML = '🕐 Absen Keluar';
                }
            }, 'image/jpeg', 0.95);
        }

        // Text-to-Speech function using Google TTS
        let currentUtterance = null;
        let speechTimeout = null;
        
        function speak(text, lang = 'id-ID') {
            // Check if sound is enabled
            if (!soundEnabled) return;
            
            // Check if browser supports Speech Synthesis
            if (!('speechSynthesis' in window)) return;
            
            try {
                // Cancel any ongoing speech
                window.speechSynthesis.cancel();
                
                // Clear timeout if exists
                if (speechTimeout) {
                    clearTimeout(speechTimeout);
                    speechTimeout = null;
                }
                
                // Clear current utterance reference
                if (currentUtterance) {
                    currentUtterance = null;
                }
                
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = lang;
                utterance.rate = 1.0; // Speed
                utterance.pitch = 1.0; // Pitch
                utterance.volume = 1.0; // Volume
                
                // Try to use Google voice if available
                const voices = window.speechSynthesis.getVoices();
                const googleVoice = voices.find(voice => 
                    voice.lang.startsWith('id') && voice.name.includes('Google')
                );
                
                if (googleVoice) {
                    utterance.voice = googleVoice;
                }
                
                // Event handlers
                utterance.onend = () => {
                    currentUtterance = null;
                    if (speechTimeout) {
                        clearTimeout(speechTimeout);
                        speechTimeout = null;
                    }
                };
                
                utterance.onerror = (event) => {
                    console.error('Speech synthesis error:', event);
                    currentUtterance = null;
                    if (speechTimeout) {
                        clearTimeout(speechTimeout);
                        speechTimeout = null;
                    }
                };
                
                // Store reference and speak
                currentUtterance = utterance;
                window.speechSynthesis.speak(utterance);
                
                // Safety timeout: force stop after 10 seconds
                speechTimeout = setTimeout(() => {
                    stopSpeech();
                }, 10000);
                
            } catch (error) {
                console.error('Error in speak function:', error);
                currentUtterance = null;
                if (speechTimeout) {
                    clearTimeout(speechTimeout);
                    speechTimeout = null;
                }
            }
        }
        
        // Function to stop all speech
        function stopSpeech() {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }
            currentUtterance = null;
            if (speechTimeout) {
                clearTimeout(speechTimeout);
                speechTimeout = null;
            }
        }

        // Load voices (needed for some browsers)
        if ('speechSynthesis' in window) {
            window.speechSynthesis.onvoiceschanged = () => {
                window.speechSynthesis.getVoices();
            };
        }

        // Toggle sound on/off
        soundToggle.addEventListener('click', () => {
            soundEnabled = !soundEnabled;
            soundIcon.textContent = soundEnabled ? '🔊' : '🔇';
            soundToggle.title = soundEnabled ? 'Matikan Suara' : 'Nyalakan Suara';
            
            // Cancel any ongoing speech when muting
            if (!soundEnabled) {
                stopSpeech();
            }
            
            // Give feedback
            if (soundEnabled) {
                speak('Suara diaktifkan');
            }
        });

        // Show error message
        function showError(message) {
            errorMessage.classList.remove('hidden');
            errorText.textContent = message;
            successMessage.classList.add('hidden');
            
            // Speak error message
            speak(message);
            
            setTimeout(() => {
                errorMessage.classList.add('hidden');
            }, 5000);
        }

        // Show success message
        function showSuccess(message, data) {
            successMessage.classList.remove('hidden');
            successText.textContent = message;
            
            if (data) {
                let details = `<div>Similarity Score: ${data.similarity}%</div>`;
                if (data.location && data.location.office) {
                    details += `<div>Lokasi: ${data.location.office} (${data.location.distance}m)</div>`;
                }
                successDetails.innerHTML = details;
            }
            
            errorMessage.classList.add('hidden');
            
            // Speak success message
            speak(message);
        }

        // Event listeners
        clockInBtn.addEventListener('click', () => submitAttendance('clock_in'));
        clockOutBtn.addEventListener('click', () => submitAttendance('clock_out'));

        // Initialize - Always start, check registration inside
        async function initialize() {
            try {
                // Step 1: Check face registration first
                updateLoadingProgress(0);
                
                const checkResponse = await fetch('/api/face-registration/descriptor');
                const checkData = await checkResponse.json();
                
                if (!checkData.success || !checkData.descriptor) {
                    // No face registration - show alert and stop
                    updateLoadingProgress(4); // Complete progress
                    
                    setTimeout(() => {
                        hideLoadingScreen();
                        
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Wajah Belum Terdaftar',
                                html: `
                                    <p class="text-gray-600 mb-4">
                                        Anda belum mendaftarkan wajah untuk sistem absensi face recognition.
                                    </p>
                                    <p class="text-gray-600 mb-4">
                                        Silakan daftarkan wajah Anda terlebih dahulu untuk dapat melakukan absensi dengan face recognition.
                                    </p>
                                `,
                                showCancelButton: true,
                                confirmButtonText: '<i class="fas fa-user-plus mr-2"></i>Daftar Wajah Sekarang',
                                cancelButtonText: '<i class="fas fa-arrow-left mr-2"></i>Kembali',
                                confirmButtonColor: '#3b82f6',
                                cancelButtonColor: '#6b7280',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/face-registration';
                                } else {
                                    window.history.back();
                                }
                            });
                        }, 300);
                    }, 800);
                    
                    return; // Stop initialization
                }
                
                // Has registration - continue with normal flow
                console.log('Face registration found, continuing...');
                startCamera();
                getLocation();
                
            } catch (error) {
                console.error('Initialization error:', error);
                updateLoadingProgress(4);
                
                setTimeout(() => {
                    hideLoadingScreen();
                    
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat',
                            text: 'Terjadi kesalahan: ' + error.message,
                            confirmButtonText: 'Kembali',
                            confirmButtonColor: '#6b7280',
                            allowOutsideClick: false
                        }).then(() => {
                            window.history.back();
                        });
                    }, 300);
                }, 800);
            }
        }
        
        // Start initialization
        initialize();

        // Pause detection during scroll for better performance
        let scrollTimeout;
        let isScrolling = false;
        
        window.addEventListener('scroll', () => {
            isScrolling = true;
            
            // Temporarily stop detection during scroll
            if (detectionInterval) {
                clearInterval(detectionInterval);
                detectionInterval = null;
            }
            
            // Clear previous timeout
            clearTimeout(scrollTimeout);
            
            // Resume detection after scroll stops
            scrollTimeout = setTimeout(() => {
                isScrolling = false;
                if (faceApiLoaded && !detectionInterval) {
                    startFaceDetection();
                }
            }, 150);
        }, { passive: true });

        // Cleanup on page unload - IMPORTANT: Stop all audio/speech
        window.addEventListener('beforeunload', () => {
            // Stop camera
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            
            // Stop all speech synthesis
            stopSpeech();
            
            // Stop face detection interval
            if (detectionInterval) {
                clearInterval(detectionInterval);
            }
        });

        // Also cleanup on page hide (for mobile browsers)
        window.addEventListener('pagehide', () => {
            stopSpeech();
        });

        // Cleanup on visibility change (when tab is hidden)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopSpeech();
            }
        });
    </script>
    @endpush
</x-app-layout>
