<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kalender Akademik') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                
                {{-- Calendar Header --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('academic-calendar', ['year' => $currentDate->copy()->subMonth()->year, 'month' => $currentDate->copy()->subMonth()->month]) }}" 
                               class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                                {{ $currentDate->translatedFormat('F Y') }}
                            </h3>
                            <a href="{{ route('academic-calendar', ['year' => $currentDate->copy()->addMonth()->year, 'month' => $currentDate->copy()->addMonth()->month]) }}" 
                               class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('academic-calendar.sync-holidays') }}" method="POST" class="inline" id="syncForm">
                                @csrf
                                <input type="hidden" name="year" value="{{ $year }}">
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" id="syncButton">
                                    <svg class="w-4 h-4" id="syncIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span id="syncText">Sync Libur Nasional</span>
                                </button>
                            </form>
                            <a href="{{ route('academic-calendar') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Hari Ini
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mt-4 p-4 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mt-4 p-4 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mt-4 p-4 bg-blue-100 dark:bg-blue-900/30 border-l-4 border-blue-500 text-blue-700 dark:text-blue-300 rounded">
                            {{ session('info') }}
                        </div>
                    @endif
                </div>

                <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Calendar Grid --}}
                    <div class="lg:col-span-2">
                        {{-- Info Box --}}
                        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-1">Sinkronisasi Hari Libur</h4>
                                    <p class="text-sm text-blue-700 dark:text-blue-400">
                                        Klik tombol "Sync Libur Nasional" untuk mengambil data hari libur nasional dari API resmi. 
                                        Data akan diperbarui secara otomatis untuk tahun {{ $year }}.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Day Headers --}}
                        <div class="grid grid-cols-7 gap-2 mb-2">
                            @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                                <div class="text-center font-semibold text-sm text-gray-600 dark:text-gray-400 py-2">
                                    {{ $day }}
                                </div>
                            @endforeach
                        </div>

                        {{-- Calendar Days --}}
                        <div class="grid grid-cols-7 gap-2">
                            @foreach($calendarDays as $day)
                                @php
                                    // Get first event color for the date number
                                    $firstEvent = $day['events']->first();
                                    $dateColor = $firstEvent ? $firstEvent->color : null;
                                @endphp
                                <div class="min-h-[100px] p-2 rounded-lg border {{ $day['isCurrentMonth'] ? 'bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600' : 'bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700' }} {{ $day['isToday'] ? 'ring-2 ring-blue-500' : '' }}">
                                    <div class="text-sm font-bold {{ $day['isCurrentMonth'] ? 'text-gray-800 dark:text-white' : 'text-gray-400 dark:text-gray-500' }} {{ $day['isToday'] ? 'text-blue-600 dark:text-blue-400' : '' }}"
                                         @if($dateColor && !$day['isToday']) style="color: {{ $dateColor }} !important;" @endif>
                                        {{ $day['date']->format('d') }}
                                    </div>
                                    <div class="mt-1 space-y-1">
                                        @foreach($day['events'] as $event)
                                            <div class="text-xs px-1 py-0.5 rounded truncate" style="background-color: {{ $event->color }}20; color: {{ $event->color }}; border-left: 2px solid {{ $event->color }};">
                                                {{ $event->title }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Upcoming Events Sidebar --}}
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Event Mendatang</h4>
                            <div class="space-y-3">
                                @forelse($upcomingEvents as $event)
                                    <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition">
                                        <div class="flex items-start gap-3">
                                            <div class="w-3 h-3 rounded-full mt-1" style="background-color: {{ $event->color }};"></div>
                                            <div class="flex-1">
                                                <h5 class="font-semibold text-gray-800 dark:text-white">{{ $event->title }}</h5>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $event->start_date->translatedFormat('d M Y') }}
                                                    @if($event->start_date != $event->end_date)
                                                        - {{ $event->end_date->translatedFormat('d M Y') }}
                                                    @endif
                                                </p>
                                                @if($event->description)
                                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">{{ $event->description }}</p>
                                                @endif
                                                <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                    {{ ucfirst($event->type) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">Tidak ada event mendatang</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Legend --}}
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Keterangan</h4>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded bg-red-500"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Libur</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded bg-blue-500"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Event</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded bg-yellow-500"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Ujian</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded bg-green-500"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Rapat</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add loading state to sync button
        document.getElementById('syncForm').addEventListener('submit', function() {
            const button = document.getElementById('syncButton');
            const icon = document.getElementById('syncIcon');
            const text = document.getElementById('syncText');
            
            button.disabled = true;
            icon.classList.add('animate-spin');
            text.textContent = 'Sedang Sync...';
        });
    </script>
    @endpush
</x-app-layout>
