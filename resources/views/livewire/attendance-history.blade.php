<div>
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .custom-scrollbar::-webkit-scrollbar {
            height: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }
        @keyframes bounce-horizontal {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(5px); }
        }
        .animate-bounce-horizontal {
            animation: bounce-horizontal 2s infinite;
        }
    </style>
  @endpushOnce

  {{-- Header & Navigation --}}
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('home') }}" class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Riwayat Absensi</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Pantau kehadiran anda setiap hari</p>
        </div>
    </div>

    <div class="flex items-center gap-3 bg-white dark:bg-gray-800 p-1.5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="pl-3 text-sm font-medium text-gray-500 dark:text-gray-400">Periode:</div>
        <input type="month" wire:model.live="month" class="border-0 bg-transparent text-sm font-semibold text-gray-800 dark:text-white focus:ring-0 p-1 cursor-pointer">
    </div>
  </div>
  
  @php
    $presentCount = 0;
    $lateCount = 0;
    $excusedCount = 0;
    $sickCount = 0;
    $absentCount = 0;

    // Calculate counts first
    foreach ($dates as $date) {
        $isWeekend = $date->isWeekend();
        $attendance = $attendances->firstWhere(fn($v, $k) => $v['date'] === $date->format('Y-m-d'));
        $status = ($attendance ?? ['status' => $isWeekend || !$date->isPast() ? '-' : 'absent'])['status'];
        
        switch ($status) {
            case 'present': $presentCount++; break;
            case 'late': $lateCount++; break;
            case 'excused': $excusedCount++; break;
            case 'sick': $sickCount++; break;
            case 'absent': $absentCount++; break;
        }
    }
  @endphp

  {{-- Stats Cards --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 mb-8">
      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 p-4 text-white shadow-lg shadow-green-500/20">
          <div class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white/20 blur-2xl"></div>
          <div class="relative z-10">
              <p class="text-xs font-medium text-green-100 uppercase tracking-wider">Hadir</p>
              <div class="flex items-end gap-2 mt-1">
                  <h4 class="text-3xl font-black">{{ $presentCount + $lateCount }}</h4>
                  <span class="text-xs bg-white/20 px-1.5 py-0.5 rounded text-white mb-1.5">{{ $lateCount }} Telat</span>
              </div>
          </div>
      </div>

      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-4 text-white shadow-lg shadow-blue-500/20">
          <div class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white/20 blur-2xl"></div>
          <div class="relative z-10">
              <p class="text-xs font-medium text-blue-100 uppercase tracking-wider">Izin</p>
              <h4 class="text-3xl font-black mt-1">{{ $excusedCount }}</h4>
          </div>
      </div>

      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-fuchsia-600 p-4 text-white shadow-lg shadow-purple-500/20">
          <div class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white/20 blur-2xl"></div>
          <div class="relative z-10">
              <p class="text-xs font-medium text-purple-100 uppercase tracking-wider">Sakit</p>
              <h4 class="text-3xl font-black mt-1">{{ $sickCount }}</h4>
          </div>
      </div>

      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 p-4 text-white shadow-lg shadow-red-500/20">
          <div class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white/20 blur-2xl"></div>
          <div class="relative z-10">
              <p class="text-xs font-medium text-red-100 uppercase tracking-wider">Alpha</p>
              <h4 class="text-3xl font-black mt-1">{{ $absentCount }}</h4>
          </div>
      </div>
  </div>

  {{-- Calendar Grid --}}
  <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
      <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
          <h4 class="font-bold text-gray-800 dark:text-white">Kalender Kehadiran</h4>
          <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
              <div class="flex gap-2 text-[10px] sm:text-xs">
                  <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Hadir</div>
                  <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Telat</div>
                  <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Alpha</div>
              </div>
              <div class="sm:hidden flex items-center gap-1 text-[10px] text-gray-400 animate-pulse">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                  <span>Geser</span>
              </div>
          </div>
      </div>
      
      <div class="p-3 sm:p-6 overflow-x-auto custom-scrollbar relative">
        {{-- Mobile Swipe Hint --}}
        <div class="md:hidden absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none opacity-50">
            <div class="flex flex-col items-center gap-1">
                <div class="w-8 h-8 rounded-full bg-white/80 dark:bg-gray-800/80 shadow-sm flex items-center justify-center animate-bounce-horizontal">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>
            </div>
        </div>

        <div class="min-w-[600px]">
            <div class="grid grid-cols-7 gap-2 mb-2">
                @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                    <div class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wider py-2 {{ $day === 'Min' ? 'text-red-400' : '' }} {{ $day === 'Jum' ? 'text-green-600 dark:text-green-400' : '' }}">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 gap-2">
                @if ($start->dayOfWeek !== 0)
                    @foreach (range(1, $start->dayOfWeek) as $i)
                        <div class="aspect-square rounded-xl bg-gray-50 dark:bg-gray-800/50"></div>
                    @endforeach
                @endif

                @foreach ($dates as $date)
                    @php
                        $isWeekend = $date->isWeekend();
                        $attendance = $attendances->firstWhere(fn($v, $k) => $v['date'] === $date->format('Y-m-d'));
                        $status = ($attendance ?? ['status' => $isWeekend || !$date->isPast() ? '-' : 'absent'])['status'];
                        
                        $baseClasses = "aspect-square rounded-xl flex flex-col items-center justify-center text-sm font-medium transition-all duration-200 relative group";
                        $statusClasses = "";
                        $dotClass = "";

                        switch ($status) {
                            case 'present':
                                $statusClasses = "bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/30 border border-green-100 dark:border-green-800 cursor-pointer";
                                $dotClass = "bg-green-500";
                                break;
                            case 'late':
                                $statusClasses = "bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/30 border border-amber-100 dark:border-amber-800 cursor-pointer";
                                $dotClass = "bg-amber-500";
                                break;
                            case 'excused':
                                $statusClasses = "bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 border border-blue-100 dark:border-blue-800 cursor-pointer";
                                $dotClass = "bg-blue-500";
                                break;
                            case 'sick':
                                $statusClasses = "bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-900/30 border border-purple-100 dark:border-purple-800 cursor-pointer";
                                $dotClass = "bg-purple-500";
                                break;
                            case 'absent':
                                $statusClasses = "bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800";
                                $dotClass = "bg-red-500";
                                break;
                            default:
                                $statusClasses = "bg-gray-50 dark:bg-gray-800 text-gray-400 dark:text-gray-500 border border-transparent";
                                break;
                        }
                    @endphp

                    @if ($attendance && ($attendance['attachment'] || $attendance['note'] || $attendance['coordinates']))
                        <button wire:click="show({{ $attendance['id'] }})" onclick="setLocation({{ $attendance['lat'] ?? 0 }}, {{ $attendance['lng'] ?? 0 }})" class="{{ $baseClasses }} {{ $statusClasses }}">
                            <span class="{{ $date->isSunday() ? 'text-red-500' : '' }}">{{ $date->format('d') }}</span>
                            @if($dotClass)
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }} mt-1"></span>
                            @endif
                        </button>
                    @else
                        <div class="{{ $baseClasses }} {{ $statusClasses }}">
                            <span class="{{ $date->isSunday() ? 'text-red-500' : '' }}">{{ $date->format('d') }}</span>
                            @if($dotClass)
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }} mt-1"></span>
                            @endif
                        </div>
                    @endif
                @endforeach

                @if ($end->dayOfWeek !== 6)
                    @foreach (range(5, $end->dayOfWeek) as $i)
                        <div class="aspect-square rounded-xl bg-gray-50 dark:bg-gray-800/50"></div>
                    @endforeach
                @endif
            </div>
        </div>
      </div>
  </div>

  <x-attendance-detail-modal :current-attendance="$currentAttendance" />
  @stack('attendance-detail-scripts')
</div>
