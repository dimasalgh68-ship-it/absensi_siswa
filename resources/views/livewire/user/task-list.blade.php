<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 relative overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-400/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
        <!-- Header Section -->
        <div class="mb-12 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="relative">
                <h2 class="text-4xl font-black text-gray-800 tracking-tight mb-2">
                    <span class="bg-clip-text text-transparent bg-blue-600">
                        Daftar Tugas
                    </span>
                </h2>
                <p class="text-gray-600 text-lg font-medium max-w-2xl">
                    Kelola dan selesaikan tugas akademik Anda tepat waktu.
                </p>
                <div class="absolute -bottom-4 left-0 w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
            </div>
            
            <a href="{{ route('home') }}" 
               class="group relative inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-200 rounded-2xl text-gray-700 font-medium overflow-hidden transition-all duration-300 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-100">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="relative">Kembali ke Dashboard</span>
            </a>
        </div>

        <!-- Task List -->
        @if($tasks->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($tasks as $task)
                    @php
                        $submission = $task->submissions->where('user_id', auth()->id())->first();
                        $isPastDeadline = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->isPast() : false;
                        $isUrgent = false;
                        $hoursRemaining = 0;
                        
                        if ($task->due_date && !$isPastDeadline) {
                            $hoursRemaining = \Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse($task->due_date), false);
                            $isUrgent = $hoursRemaining < 24 && $hoursRemaining > 0;
                        }
                        
                        $wasLateSubmission = false;
                        if ($submission && $task->due_date) {
                            $wasLateSubmission = \Carbon\Carbon::parse($submission->submitted_at)->isAfter(\Carbon\Carbon::parse($task->due_date));
                        }
                    @endphp
                    
                    <div class="group relative bg-white backdrop-blur-xl border-2 @if($isPastDeadline && !$submission) border-red-300 @elseif($isUrgent) border-orange-300 @else border-gray-200 @endif rounded-3xl overflow-hidden hover:border-blue-400 transition-all duration-500 @if($isPastDeadline && !$submission) hover:shadow-[0_8px_30px_rgba(239,68,68,0.25)] @elseif($isUrgent) hover:shadow-[0_8px_30px_rgba(249,115,22,0.25)] @else hover:shadow-[0_8px_30px_rgba(59,130,246,0.2)] @endif hover:-translate-y-2 shadow-lg">
                        <!-- Image Section -->
                        <div class="relative h-56 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                            <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent z-10"></div>
                            @if($task->image_path)
                                <img src="{{ Storage::url($task->image_path) }}" 
                                     alt="{{ $task->title }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                                    <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Deadline Status Badge -->
                            <div class="absolute top-4 right-4 z-20">
                                @if($isPastDeadline)
                                    <span class="px-3 py-1.5 bg-red-500 backdrop-blur-md border border-red-600 rounded-full text-xs font-bold text-white shadow-lg flex items-center animate-pulse">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Expired
                                    </span>
                                @elseif($isUrgent)
                                    <span class="px-3 py-1.5 bg-orange-500 backdrop-blur-md border border-orange-600 rounded-full text-xs font-bold text-white shadow-lg flex items-center animate-pulse">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Urgent
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 bg-blue-500 backdrop-blur-md border border-blue-600 rounded-full text-xs font-bold text-white shadow-lg">
                                        Tugas
                                    </span>
                                @endif
                            </div>

                            <div class="absolute bottom-4 left-4 right-4 z-20">
                                <h3 class="text-xl font-bold text-gray-800 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors duration-300 drop-shadow-sm">
                                    {{ $task->title }}
                                </h3>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Description -->
                            <p class="text-gray-600 text-sm line-clamp-3 mb-6 leading-relaxed">
                                {{ Str::limit($task->description, 120) }}
                            </p>

                            <!-- Meta Info -->
                            <div class="space-y-4">
                                @if($task->due_date)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 @if($isPastDeadline) bg-red-100 text-red-600 @elseif($isUrgent) bg-orange-100 text-orange-600 @else bg-blue-100 text-blue-600 @endif rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Deadline</p>
                                                <p class="text-sm font-semibold @if($isPastDeadline) text-red-600 @elseif($isUrgent) text-orange-600 @else text-gray-800 @endif">
                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y, H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if(!$isPastDeadline)
                                        <div class="relative overflow-hidden rounded-xl @if($isUrgent) bg-gradient-to-r from-orange-100 to-red-100 border-orange-300 @else bg-gradient-to-r from-blue-50 to-purple-50 border-blue-200 @endif border-2 p-3 @if($hoursRemaining < 1) animate-pulse @endif" 
                                             x-data="countdownTimer({{ $task->due_date->timestamp * 1000 }})" 
                                             x-init="init()">
                                            <div class="flex items-center justify-between relative z-10">
                                                <span class="text-xs font-bold @if($isUrgent) text-orange-700 @else text-blue-700 @endif uppercase tracking-wider flex items-center">
                                                    <svg class="w-3 h-3 mr-1.5 @if($hoursRemaining < 1) animate-ping @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Sisa Waktu
                                                </span>
                                                <span class="text-sm font-bold font-mono" 
                                                      x-text="timeString" 
                                                      :class="isOverdue ? 'text-red-600' : '@if($isUrgent) text-orange-700 @else text-blue-700 @endif'">
                                                    Menghitung...
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-red-100 to-pink-100 border-2 border-red-300 p-3">
                                            <div class="flex items-center justify-center relative z-10">
                                                <span class="text-sm font-bold text-red-700 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Waktu Telah Habis
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <!-- Action Button -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                @if($submission)
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-2xl border-2 border-green-200 group/status">
                                            <div class="flex items-center text-green-700">
                                                <div class="p-1 bg-green-200 rounded-full mr-3">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="font-bold text-sm block">Terkirim</span>
                                                    @if($wasLateSubmission)
                                                        <span class="text-xs text-orange-600 flex items-center mt-1 font-semibold">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            Terlambat
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-1 rounded-full text-xs font-bold
                                                    @if($submission->status === 'approved') bg-green-200 text-green-800 border-2 border-green-300
                                                    @elseif($submission->status === 'rejected') bg-red-200 text-red-800 border-2 border-red-300
                                                    @else bg-yellow-200 text-yellow-800 border-2 border-yellow-300
                                                    @endif">
                                                    {{ ucfirst($submission->status) }}
                                                </span>
                                                <a href="{{ route('user.tasks.detail', $task->id) }}" class="text-sm font-bold text-green-700 hover:text-green-800 flex items-center transition-colors">
                                                    <svg class="w-4 h-4 transform group-hover/status:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @if($isPastDeadline)
                                        <div class="relative block w-full py-4 px-6 bg-red-50 border-2 border-red-300 rounded-2xl text-center">
                                            <span class="text-red-700 font-bold text-sm flex items-center justify-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                Pengumpulan Ditutup
                                            </span>
                                        </div>
                                    @else
                                        <a href="{{ route('user.tasks.detail', $task->id) }}" 
                                           class="group/btn relative block w-full py-4 px-6 @if($isUrgent) bg-gradient-to-r from-orange-500 to-red-500 shadow-lg shadow-orange-200 hover:shadow-xl hover:shadow-orange-300 @else bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg shadow-blue-200 hover:shadow-xl hover:shadow-blue-300 @endif rounded-2xl overflow-hidden transition-all duration-300 hover:scale-[1.02]">
                                            <div class="absolute inset-0 bg-white/30 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                                            <div class="relative flex items-center justify-center text-white font-bold tracking-wide">
                                                <span>Kerjakan Sekarang</span>
                                                <svg class="w-5 h-5 ml-2 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                            </div>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-24 px-4">
                <div class="relative w-32 h-32 mb-8 group">
                    <div class="absolute inset-0 bg-blue-200 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500"></div>
                    <div class="relative w-full h-full bg-white border-2 border-gray-200 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-500 shadow-lg">
                        <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4 text-center">Belum Ada Tugas</h3>
                <p class="text-gray-600 text-center max-w-md text-lg leading-relaxed">
                    Saat ini belum ada tugas yang perlu dikerjakan. Nikmati waktu luang Anda atau pelajari materi sebelumnya.
                </p>
            </div>
        @endif
    </div>

    <script>
        function countdownTimer(deadline) {
            return {
                deadline: new Date(deadline).getTime(),
                timeString: 'Menghitung...',
                isOverdue: false,
                init() {
                    this.updateTimer();
                    setInterval(() => {
                        this.updateTimer();
                    }, 1000);
                },
                updateTimer() {
                    const now = new Date().getTime();
                    const distance = this.deadline - now;

                    if (distance < 0) {
                        this.timeString = 'Waktu Habis';
                        this.isOverdue = true;
                        return;
                    }

                    this.isOverdue = false;
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    if (days > 0) {
                        this.timeString = `${days}h ${hours}j ${minutes}m`;
                    } else {
                        this.timeString = `${hours}j ${minutes}m ${seconds}d`;
                    }
                }
            }
        }
    </script>
</div>
