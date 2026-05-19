<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <?php $__env->startPush('styles'); ?>
    <style>
        .mobile-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
        .dark .mobile-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-nav {
            background: rgba(15, 23, 42, 0.85);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Analog Clock Styles */
        .clock-container {
            position: relative;
            width: 80px;
            height: 80px;
            background: #2d3748;
            border-radius: 50%;
            border: 4px solid #1a202c;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.5), 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .clock-face {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: repeating-radial-gradient(
                circle at center,
                #f7fafc 0,
                #edf2f7 2px,
                #e2e8f0 3px
            );
        }

        .clock-marker {
            position: absolute;
            background: #2d3748;
            width: 3px; /* Slightly thinner */
            height: 8px; /* Slightly shorter */
            left: 50%;
            transform-origin: 50% 36px; /* Adjusted based on new size (80px / 2 - 4px padding approx) */
            transform: translateX(-50%);
            top: 4px; /* Padding from edge */
        }

        .clock-marker.main {
            width: 4px;
            height: 10px;
            background: #1a202c;
        }

        .clock-marker.twelve { transform: translateX(-50%) rotate(0deg); }
        .clock-marker.three { transform: translateX(-50%) rotate(90deg); top: 35px; left: 66px; transform-origin: center; height: 4px; width: 10px; } /* Manual positioning might be tricky with rotate, let's use standard rotation */
        
        /* Better marker positioning strategy */
        .marker-container {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
        }
        .marker-12, .marker-6 {
            position: absolute; left: 50%; width: 4px; height: 10px; background: #1a202c; transform: translateX(-50%);
        }
        .marker-12 { top: 4px; }
        .marker-6 { bottom: 4px; }
        .marker-3, .marker-9 {
            position: absolute; top: 50%; height: 4px; width: 10px; background: #1a202c; transform: translateY(-50%);
        }
        .marker-3 { right: 4px; }
        .marker-9 { left: 4px; }

        .clock-hand {
            position: absolute;
            bottom: 50%;
            left: 50%;
            transform-origin: bottom center;
            transform: translateX(-50%);
            border-radius: 4px;
            z-index: 10;
        }

        .hand-hour {
            width: 4px;
            height: 20px;
            background: #1a202c;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .hand-minute {
            width: 3px;
            height: 28px;
            background: #2d3748;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .hand-second {
            width: 1.5px;
            height: 32px;
            background: #e53e3e;
            z-index: 11;
        }

        .clock-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 6px;
            height: 6px;
            background: #e53e3e;
            border: 1px solid #c53030;
            border-radius: 50%;
            z-index: 12;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        /* Animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 pb-24 md:pb-12 font-sans selection:bg-blue-500 selection:text-white overflow-x-hidden">
        
        
        <div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-b-[2.5rem] md:rounded-b-none md:h-64 overflow-hidden shadow-2xl shadow-blue-500/20">
            
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-soft-light"></div>
            <div class="absolute top-[-20%] right-[-10%] w-80 h-80 bg-purple-500/30 rounded-full blur-3xl mix-blend-overlay"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-60 h-60 bg-cyan-400/20 rounded-full blur-3xl mix-blend-overlay"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-12 pb-24 md:pb-12 h-full flex flex-col md:flex-row md:items-center md:justify-between overflow-hidden">
                <div class="flex items-center gap-4 animate-slide-up">
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-pink-500 to-cyan-500 rounded-full opacity-75 group-hover:opacity-100 blur transition duration-200"></div>
                        <img class="relative h-14 w-14 md:h-20 md:w-20 rounded-full object-cover border-2 border-white/50 shadow-lg" 
                                src="<?php echo e(Auth::user()->profile_photo_url); ?>" 
                                alt="<?php echo e(Auth::user()->name); ?>" />
                        <div class="absolute bottom-0 right-0 w-3.5 h-3.5 md:w-5 md:h-5 bg-emerald-400 border-2 border-blue-800 rounded-full"></div>
                    </div>
                    <div>
                        <h2 class="text-white text-xl md:text-3xl font-bold max-w-[200px] md:max-w-md leading-tight">
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200"><?php echo e(Auth::user()->name); ?></span>
                        </h2>
                         <h4 class="text-blue-200 text-sm md:text-lg font-semibold max-w-[200px] md:max-w-md leading-tight mt-1">
                            <span class="bg-clip-text text-white bg-gradient-to-r from-blue-100 to-blue-300"><?php echo e(Auth::user()->education?->name ?? 'Siswa'); ?></span>
                        </h4>
                    </div>
                </div>
                
                
                


               
            </div>
        </div>

        
        <div class="max-w-[1400px] mx-auto px-2 sm:px-4 -mt-12 md:mt-8 md:grid md:grid-cols-12 md:gap-8 relative z-10">
            
            
            <div class="md:col-span-8 space-y-6">
                
                
                <div class="mobile-card rounded-2xl p-4 sm:p-5 animate-slide-up">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="clock-container hover:scale-105 transition-transform duration-300">
                                <div class="clock-face">
                                    <div class="marker-container">
                                        <div class="marker-12"></div>
                                        <div class="marker-6"></div>
                                        <div class="marker-3"></div>
                                        <div class="marker-9"></div>
                                    </div>
                                    <div class="clock-hand hand-hour"></div>
                                    <div class="clock-hand hand-minute"></div>
                                    <div class="clock-hand hand-second"></div>
                                    <div class="clock-center"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-baseline gap-1">
                                    <span id="clock-time" class="text-3xl font-bold text-gray-800 dark:text-white tabular-nums">00:00</span>
                                    <span id="clock-seconds" class="text-lg font-semibold text-gray-500 dark:text-gray-400 tabular-nums">00</span>
                                </div>
                                <p id="clock-date" class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-0.5">Loading...</p>
                            </div>
                        </div>
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">Live</span>
                        </div>
                    </div>
                </div>

                
                <?php if($nextDeadline): ?>
                    <div class="mobile-card rounded-2xl p-4 sm:p-5 animate-slide-up delay-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div id="countdown-icon" class="w-12 h-12 rounded-xl 
                                    <?php if($deadlineType === 'waiting'): ?> bg-gradient-to-br from-gray-500 to-gray-600
                                    <?php elseif($deadlineType === 'clock_in'): ?> bg-gradient-to-br from-red-500 to-orange-500
                                    <?php elseif($deadlineType === 'clock_out'): ?> bg-gradient-to-br from-blue-500 to-indigo-500
                                    <?php else: ?> bg-gradient-to-br from-purple-500 to-indigo-500
                                    <?php endif; ?>
                                    flex items-center justify-center text-white shadow-lg transition-all duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 id="countdown-title" class="text-sm font-bold text-gray-800 dark:text-white">
                                        <?php if($deadlineType === 'waiting'): ?> Menunggu Waktu Absensi
                                        <?php elseif($deadlineType === 'clock_in'): ?> Batas Waktu Absensi Masuk
                                        <?php elseif($deadlineType === 'clock_out'): ?> Waktu Absensi Keluar
                                        <?php else: ?> Absensi Besok
                                        <?php endif; ?>
                                    </h3>
                                    <p id="countdown-subtitle" class="text-xs text-gray-500 dark:text-gray-400">
                                        <?php if($deadlineType === 'waiting'): ?> Buka: <?php echo e($nextDeadline->format('H:i')); ?> WIB
                                        <?php elseif($deadlineType === 'clock_in'): ?> Deadline: <?php echo e($nextDeadline->format('H:i')); ?> WIB
                                        <?php elseif($deadlineType === 'clock_out'): ?> Waktu Keluar: <?php echo e($nextDeadline->format('H:i')); ?> WIB
                                        <?php else: ?> Buka Besok: <?php echo e($nextDeadline->format('H:i')); ?> WIB
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div id="countdown-display" class="text-2xl font-bold 
                                    <?php if($deadlineType === 'waiting'): ?> text-gray-600 dark:text-gray-400
                                    <?php elseif($deadlineType === 'clock_in'): ?> text-red-600 dark:text-red-400
                                    <?php elseif($deadlineType === 'clock_out'): ?> text-blue-600 dark:text-blue-400
                                    <?php else: ?> text-purple-600 dark:text-purple-400
                                    <?php endif; ?>
                                    tabular-nums">--:--:--</div>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">
                                    <?php if($deadlineType === 'waiting'): ?> Dibuka Dalam
                                    <?php else: ?> Sisa Waktu
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                <div id="countdown-progress" class="h-full 
                                    <?php if($deadlineType === 'waiting'): ?> bg-gradient-to-r from-gray-500 to-gray-600
                                    <?php elseif($deadlineType === 'clock_in'): ?> bg-gradient-to-r from-red-500 to-orange-500
                                    <?php elseif($deadlineType === 'clock_out'): ?> bg-gradient-to-r from-blue-500 to-indigo-500
                                    <?php else: ?> bg-gradient-to-r from-purple-500 to-indigo-500
                                    <?php endif; ?>
                                    transition-all duration-1000" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <?php if(!$attendanceToday && $canClockIn): ?>
                            <a href="<?php echo e(route('face-attendance.index')); ?>" class="mt-3 w-full flex items-center justify-center gap-2 bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white font-bold py-2.5 px-4 rounded-xl shadow-lg transition-all group">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span>Absen Sekarang</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        <?php elseif(!$attendanceToday && !$canClockIn): ?>
                            <div class="mt-3 w-full flex items-center justify-center gap-2 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 font-bold py-2.5 px-4 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <span>Belum Waktunya Absen</span>
                            </div>
                        <?php elseif($attendanceToday && !$attendanceToday->time_out && $deadlineType === 'clock_out'): ?>
                            <a href="<?php echo e(route('face-attendance.index')); ?>" class="mt-3 w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-bold py-2.5 px-4 rounded-xl shadow-lg transition-all group">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>Absen Keluar</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        <?php else: ?>
                            <div class="mt-3 w-full flex items-center justify-center gap-2 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400 font-bold py-2.5 px-4 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Absensi Hari Ini Selesai - Sampai Jumpa Besok!</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                
                <?php if($attendanceToday): ?>
                    <div class="mobile-card rounded-2xl p-4 sm:p-5 animate-slide-up delay-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Status Absensi Hari Ini</h3>
                                <div class="flex gap-4 mt-1">
                                    <div>
                                        <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold">Masuk</span>
                                        <p class="text-sm font-bold text-gray-800 dark:text-white"><?php echo e($attendanceToday->time_in ? \Carbon\Carbon::parse($attendanceToday->time_in)->format('H:i') : '-'); ?></p>
                                    </div>
                                    <div class="w-px bg-gray-200 dark:bg-gray-700"></div>
                                    <div>
                                        <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold">Keluar</span>
                                        <p class="text-sm font-bold text-gray-800 dark:text-white"><?php echo e($attendanceToday->time_out ? \Carbon\Carbon::parse($attendanceToday->time_out)->format('H:i') : '-'); ?></p>
                                    </div>
                                    <?php if($attendanceToday->time_out): ?>
                                        <div class="w-px bg-gray-200 dark:bg-gray-700"></div>
                                        <div>
                                            <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold">Durasi</span>
                                            <p class="text-sm font-bold text-gray-800 dark:text-white"><?php echo e($attendanceToday->duration ?? '-'); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

             
            </div>

            
            <div class="md:col-span-4 space-y-6 mt-6 md:mt-0">
                
                
                <div class="animate-slide-up delay-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">Menu & Akses</h3>
                    </div>
                    <div class="grid grid-cols-4 md:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3">
                        <a href="<?php echo e(route('home')); ?>" class="flex flex-col items-center gap-2 group">
                            <div class="w-14 h-14 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center text-blue-600 group-active:scale-95 transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:shadow-md hover:-translate-y-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-600 dark:text-gray-400">Home</span>
                        </a>
                        <a href="<?php echo e(route('apply-leave')); ?>" class="flex flex-col items-center gap-2 group">
                            <div class="w-14 h-14 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center text-emerald-600 group-active:scale-95 transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:shadow-md hover:-translate-y-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-600 dark:text-gray-400">Izin</span>
                        </a>
                        <a href="<?php echo e(route('attendance-history')); ?>" class="flex flex-col items-center gap-2 group">
                            <div class="w-14 h-14 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center text-indigo-600 group-active:scale-95 transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:shadow-md hover:-translate-y-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-600 dark:text-gray-400">Riw.Izin</span>
                        </a>
                        <a href="<?php echo e(route('academic-calendar')); ?>" class="flex flex-col items-center gap-2 group">
                            <div class="w-14 h-14 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center text-purple-600 group-active:scale-95 transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:shadow-md hover:-translate-y-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-600 dark:text-gray-400">Kalender</span>
                        </a>
                    </div>
                </div>

                
                <div class="animate-slide-up delay-400">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">Riwayat Terbaru</h3>
                        <a href="<?php echo e(route('attendance-history')); ?>" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Lihat Semua</a>
                    </div>
                    <div class="space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="group relative bg-white dark:bg-gray-800 p-3 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between hover:shadow-md transition-all duration-300">
                                
                                <?php if(!$loop->last): ?>
                                    <div class="absolute left-[1.65rem] top-10 bottom-[-0.75rem] w-0.5 bg-gray-100 dark:bg-gray-700 -z-10 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors"></div>
                                <?php endif; ?>

                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 dark:text-white text-xs">Presensi <?php echo e($record->time_out ? 'Selesai' : 'Harian'); ?></h4>
                                        <p class="text-[10px] text-gray-500 mt-0.5"><?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('l, d F Y')); ?></p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <div class="flex items-center gap-2">
                                        <div class="flex flex-col items-end">
                                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter leading-none">In</span>
                                            <span class="text-[11px] font-bold text-gray-800 dark:text-white"><?php echo e($record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('H:i') : '-'); ?></span>
                                        </div>
                                        <?php if($record->time_out): ?>
                                            <div class="w-[1px] h-4 bg-gray-200 dark:bg-gray-700 mx-1"></div>
                                            <div class="flex flex-col items-end">
                                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter leading-none">Out</span>
                                                <span class="text-[11px] font-bold text-gray-800 dark:text-white"><?php echo e(\Carbon\Carbon::parse($record->time_out)->format('H:i')); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-[9px] uppercase font-bold <?php echo e($record->status == 'present' ? 'text-green-500 bg-green-50' : 'text-amber-500 bg-amber-50'); ?> dark:bg-opacity-10 px-1.5 py-0.5 rounded-full">
                                        <?php echo e($record->status == 'present' ? 'Hadir' : 'Terlambat'); ?>

                                    </span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-8 bg-white/50 border-2 border-dashed border-gray-200 rounded-2xl">
                                <p class="text-gray-400 text-sm font-medium">Belum ada aktivitas</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


            </div>
        </div>
    </div>

    
    <?php $__env->startPush('scripts'); ?>
    <script>
        function updateClock() {
            const now = new Date();
            
            // Format time (HH:MM)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            // Format date
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const date = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            // Update Digital DOM
            const timeElement = document.getElementById('clock-time');
            const secondsElement = document.getElementById('clock-seconds');
            const dateElement = document.getElementById('clock-date');
            
            if (timeElement) timeElement.textContent = `${hours}:${minutes}`;
            if (secondsElement) secondsElement.textContent = seconds;
            if (dateElement) dateElement.textContent = `${dayName}, ${date} ${monthName} ${year}`;
            
            // Update Analog Clock
            const hourHand = document.querySelector('.hand-hour');
            const minuteHand = document.querySelector('.hand-minute');
            const secondHand = document.querySelector('.hand-second');
            
            if (hourHand && minuteHand && secondHand) {
                const hourDeg = (now.getHours() % 12) * 30 + now.getMinutes() * 0.5;
                const minuteDeg = now.getMinutes() * 6;
                const secondDeg = now.getSeconds() * 6;
                
                hourHand.style.transform = `translateX(-50%) rotate(${hourDeg}deg)`;
                minuteHand.style.transform = `translateX(-50%) rotate(${minuteDeg}deg)`;
                secondHand.style.transform = `translateX(-50%) rotate(${secondDeg}deg)`;
            }
        }
        
        // Update immediately
        updateClock();
        
        // Update every second
        setInterval(updateClock, 1000);

        <?php if($nextDeadline): ?>
        // Countdown Timer - Based on server-calculated deadline
        const deadlineTime = new Date('<?php echo e($nextDeadline->format('Y-m-d H:i:s')); ?>').getTime();
        const deadlineType = '<?php echo e($deadlineType); ?>'; // clock_in, clock_out, next_day, waiting
        const startOfDay = new Date('<?php echo e($nextDeadline->copy()->startOfDay()->format('Y-m-d H:i:s')); ?>').getTime();
        const totalDuration = deadlineTime - startOfDay;
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = deadlineTime - now;
            
            const countdownDisplay = document.getElementById('countdown-display');
            const countdownProgress = document.getElementById('countdown-progress');
            
            if (!countdownDisplay || !countdownProgress) return;
            
            // If expired, reload page to recalculate
            if (distance < 0) {
                countdownDisplay.textContent = 'MEMUAT...';
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            countdownDisplay.textContent = 
                String(hours).padStart(2, '0') + ':' + 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
            
            // Update progress bar
            const percentage = (distance / totalDuration) * 100;
            countdownProgress.style.width = Math.min(Math.max(percentage, 0), 100) + '%';
            
            // Change color based on countdown type and time remaining
            if (deadlineType === 'waiting') {
                // Gray gradient for waiting
                countdownProgress.className = 'h-full bg-gradient-to-r from-gray-500 to-gray-600 transition-all duration-1000';
                countdownDisplay.classList.remove('animate-pulse');
            } else if (deadlineType === 'clock_out') {
                // Blue gradient for clock out
                if (percentage < 25) {
                    countdownProgress.className = 'h-full bg-gradient-to-r from-blue-600 to-indigo-600 transition-all duration-1000';
                    countdownDisplay.classList.add('animate-pulse');
                } else {
                    countdownProgress.className = 'h-full bg-gradient-to-r from-blue-500 to-indigo-500 transition-all duration-1000';
                    countdownDisplay.classList.remove('animate-pulse');
                }
            } else if (deadlineType === 'next_day') {
                // Purple gradient for next day
                countdownProgress.className = 'h-full bg-gradient-to-r from-purple-500 to-indigo-500 transition-all duration-1000';
                countdownDisplay.classList.remove('animate-pulse');
            } else {
                // Red/orange gradient for clock in (urgent)
                if (percentage < 25) {
                    countdownProgress.className = 'h-full bg-gradient-to-r from-red-600 to-red-500 transition-all duration-1000';
                    countdownDisplay.classList.add('animate-pulse');
                } else if (percentage < 50) {
                    countdownProgress.className = 'h-full bg-gradient-to-r from-orange-500 to-red-500 transition-all duration-1000';
                    countdownDisplay.classList.remove('animate-pulse');
                } else {
                    countdownProgress.className = 'h-full bg-gradient-to-r from-yellow-500 to-orange-500 transition-all duration-1000';
                    countdownDisplay.classList.remove('animate-pulse');
                }
            }
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
        <?php endif; ?>
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/home.blade.php ENDPATH**/ ?>