<div class="fixed bottom-0 left-0 right-0 glass-nav z-50 rounded-t-3xl pb-safe md:hidden">
    {{-- Custom Styles for Nav --}}
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
        }
        .dark .glass-nav {
            background: rgba(15, 23, 42, 0.95);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
        }
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 20px);
        }
        .nav-item-active {
            transform: translateY(-4px);
        }
    </style>

    <div class="flex justify-around items-center px-2 py-3">
        {{-- Home --}}
        <a href="{{ route('home') }}" class="relative flex flex-col items-center p-2 rounded-xl transition-all duration-300 {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300' }}">
            <div class="relative">
                <svg class="w-6 h-6 mb-1 transition-transform duration-300 {{ request()->routeIs('home') ? 'nav-item-active' : '' }}" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                @if(request()->routeIs('home'))
                    <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-medium {{ request()->routeIs('home') ? 'opacity-100' : 'opacity-70' }} transition-all">Home</span>
        </a>

        {{-- History --}}
        <a href="{{ route('attendance-history') }}" class="relative flex flex-col items-center p-2 rounded-xl transition-all duration-300 {{ request()->routeIs('attendance-history') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300' }}">
            <div class="relative">
                <svg class="w-6 h-6 mb-1 transition-transform duration-300 {{ request()->routeIs('attendance-history') ? 'nav-item-active' : '' }}" fill="{{ request()->routeIs('attendance-history') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                @if(request()->routeIs('attendance-history'))
                    <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-medium {{ request()->routeIs('attendance-history') ? 'opacity-100' : 'opacity-70' }} transition-all">Riwayat</span>
        </a>

        {{-- Center Action Button (Scan Face) --}}
        <a href="{{ route('face-attendance.index') }}" class="relative group -mt-8">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl text-white flex items-center justify-center shadow-xl shadow-blue-500/40 border-4 border-white dark:border-gray-900 group-active:scale-95 transition-all {{ request()->routeIs('face-attendance.index') ? 'ring-4 ring-blue-300 dark:ring-blue-800' : '' }}">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{-- Attendance Indicator Badge --}}
                @php
                    $hasAttendanceToday = \App\Models\Attendance::where('user_id', auth()->id())
                        ->whereDate('date', today())
                        ->exists();
                @endphp
                @if(!$hasAttendanceToday)
                    <span class="absolute -top-1 -right-1 flex h-5 w-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-red-500 border-2 border-white dark:border-gray-900 items-center justify-center">
                            <span class="text-white text-[8px] font-bold">!</span>
                        </span>
                    </span>
                @endif
            </div>
            <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 whitespace-nowrap">
                <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400">Absen</span>
            </div>
        </a>

        {{-- Face Registration --}}
        <a href="{{ route('face-registration.index') }}" class="relative flex flex-col items-center p-2 rounded-xl transition-all duration-300 {{ request()->routeIs('face-registration.index') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300' }}">
            <div class="relative">
                <svg class="w-6 h-6 mb-1 transition-transform duration-300 {{ request()->routeIs('face-registration.index') ? 'nav-item-active' : '' }}" fill="{{ request()->routeIs('face-registration.index') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @if(request()->routeIs('face-registration.index'))
                    <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-medium {{ request()->routeIs('face-registration.index') ? 'opacity-100' : 'opacity-70' }} transition-all">Wajah</span>
        </a>

        {{-- Profile --}}
        <a href="{{ route('profile.show') }}" class="relative flex flex-col items-center p-2 rounded-xl transition-all duration-300 {{ request()->routeIs('profile.show') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300' }}">
            <div class="relative">
                <svg class="w-6 h-6 mb-1 transition-transform duration-300 {{ request()->routeIs('profile.show') ? 'nav-item-active' : '' }}" fill="{{ request()->routeIs('profile.show') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                @if(request()->routeIs('profile.show'))
                    <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-medium {{ request()->routeIs('profile.show') ? 'opacity-100' : 'opacity-70' }} transition-all">Profil</span>
        </a>
    </div>
</div>
