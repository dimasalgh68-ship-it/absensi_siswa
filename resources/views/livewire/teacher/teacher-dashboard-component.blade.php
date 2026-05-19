@php
  $date = Carbon\Carbon::now();
@endphp
<div>
@pushOnce('styles')
  <style>
    .shadow-premium {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04) !important;
    }
    .hover-translate-y:hover {
        transform: translateY(-4px);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }
  </style>
@endpushOnce

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden bg-gradient-success text-white p-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="font-weight-bold mb-1">Selamat Datang, {{ Auth::user()->name }}! 🏫</h2>
                    <div class="mb-0 opacity-90 font-weight-500 d-flex align-items-center flex-wrap mt-2">
                        <span class="mr-2">Guru Mapel:</span>
                        @forelse($mySubjects as $subj)
                            <span class="badge badge-pill bg-white text-primary px-2 py-1 font-weight-bold mr-1 mb-1">{{ $subj }}</span>
                        @empty
                            <span class="badge badge-pill bg-white text-primary px-2 py-1 font-weight-bold mr-1 mb-1">-</span>
                        @endforelse
                        
                        <span class="ml-2 mr-2">| Mengajar Kelas:</span> 
                        @forelse($myClasses as $cls)
                            <span class="badge badge-pill bg-white text-success px-2 py-1 font-weight-bold mr-1 mb-1">{{ $cls->name }}</span>
                        @empty
                            <span class="badge badge-pill bg-white text-success px-2 py-1 font-weight-bold mr-1 mb-1">-</span>
                        @endforelse
                        
                        @if(Auth::user()->education_id)
                        <span class="ml-2 mr-2">| Wali Kelas:</span> <span class="badge badge-pill bg-warning text-white px-2 py-1 font-weight-bold mr-1 mb-1">{{ Auth::user()->education->name ?? '' }}</span>
                        @endif
                    </div>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="badge badge-pill bg-white text-success font-weight-bold px-3 py-2">
                        <i class="fas fa-calendar-day mr-1"></i> {{ $date->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Classroom Attendance Metric Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden hover-translate-y transition-all glass-card">
            <div class="card-body p-4" style="border-left: 5px solid #10b981;">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Siswa Hadir</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $presentCount }}</div>
                        <div class="text-xs text-muted mt-1 font-weight-500">Terlambat: {{ $lateCount }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-xl" style="background: rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-user-check fa-lg text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden hover-translate-y transition-all glass-card">
            <div class="card-body p-4" style="border-left: 5px solid #3b82f6;">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sakit / Izin</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $excusedCount + $sickCount }}</div>
                        <div class="text-xs text-muted mt-1 font-weight-500">Izin: {{ $excusedCount }} | Sakit: {{ $sickCount }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-xl" style="background: rgba(59, 130, 246, 0.1);">
                            <i class="fas fa-envelope-open-text fa-lg text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden hover-translate-y transition-all glass-card">
            <div class="card-body p-4" style="border-left: 5px solid #ef4444;">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Alpa / Belum Absen</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $absentCount }}</div>
                        <div class="text-xs text-muted mt-1 font-weight-500">Siswa belum melakukan presensi</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-xl" style="background: rgba(239, 68, 68, 0.1);">
                            <i class="fas fa-user-times fa-lg text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden hover-translate-y transition-all glass-card">
            <div class="card-body p-4" style="border-left: 5px solid #f59e0b;">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Siswa Kelas</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $employeesCount }}</div>
                        <div class="text-xs text-muted mt-1 font-weight-500">Jumlah siswa terdaftar</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-xl" style="background: rgba(245, 158, 11, 0.1);">
                            <i class="fas fa-users fa-lg text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Teacher Quick Stats & Quick Actions -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('admin.materials') }}" class="text-decoration-none hover-translate-y transition-all d-block">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-slate-600 font-weight-bold mb-1">Materi Ajar Anda</h6>
                            <h3 class="font-weight-bold text-slate-800 mb-0">{{ $materiCount }}</h3>
                            <small class="text-success font-weight-600">Upload Materi Baru &rarr;</small>
                        </div>
                        <div class="p-3 rounded-xl bg-emerald-50 text-success">
                            <i class="fas fa-book-open fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('admin.tasks') }}" class="text-decoration-none hover-translate-y transition-all d-block">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-slate-600 font-weight-bold mb-1">Tugas Yang Dibuat</h6>
                            <h3 class="font-weight-bold text-slate-800 mb-0">{{ $tasksCount }}</h3>
                            <small class="text-success font-weight-600">Lihat Pengumpulan &rarr;</small>
                        </div>
                        <div class="p-3 rounded-xl bg-purple-50 text-purple">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-12 mb-4">
        <a href="{{ route('admin.exams') }}" class="text-decoration-none hover-translate-y transition-all d-block">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-slate-600 font-weight-bold mb-1">Ujian CBT Anda</h6>
                            <h3 class="font-weight-bold text-slate-800 mb-0">{{ $examsCount }}</h3>
                            <small class="text-success font-weight-600">Buat Ujian / Bank Soal &rarr;</small>
                        </div>
                        <div class="p-3 rounded-xl bg-pink-50 text-pink">
                            <i class="fas fa-laptop-code fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row mb-4">
    <!-- Today's Schedule & Tasks -->
    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom-light d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-calendar-alt mr-2"></i>Jadwal Mengajar Anda Hari Ini ({{ $todayDayName }})</h6>
                <a href="{{ route('admin.schedules') }}" class="small font-weight-bold text-success">Jadwal Mingguan &rarr;</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                        <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                            <tr>
                                <th class="border-0 px-4 py-3">Waktu</th>
                                <th class="border-0 px-4 py-3">Mapel & Kelas</th>
                                <th class="border-0 px-4 py-3">Ruangan</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-800 font-weight-500">
                            @forelse($todaySchedules as $sch)
                                <tr>
                                    <td class="px-4 py-3 text-nowrap">
                                        <span class="badge badge-pill bg-emerald-50 text-success font-weight-bold px-3 py-2">
                                            {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-weight-600 text-slate-800">{{ $sch->subject->name ?? 'N/A' }}</div>
                                        <small class="text-slate-600">Kelas: {{ $sch->education->name ?? 'N/A' }}</small>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-weight-bold text-slate-700">{{ $sch->room ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <small class="font-weight-500">Tidak ada jadwal mengajar untuk Anda hari ini</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom-light d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-tasks mr-2"></i>Tugas Aktif yang Anda Buat</h6>
                <a href="{{ route('admin.tasks') }}" class="small font-weight-bold text-success">Kelola Tugas &rarr;</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                        <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                            <tr>
                                <th class="border-0 px-4 py-3">Judul Tugas</th>
                                <th class="border-0 px-4 py-3">Tenggat Waktu</th>
                                <th class="border-0 px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-800 font-weight-500">
                            @forelse($recentTasks as $task)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-weight-600 text-slate-800">{{ $task->title }}</div>
                                        <small class="text-slate-600">{{ Str::limit($task->description, 50) }}</small>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="small text-danger font-weight-600">{{ \Carbon\Carbon::parse($task->due_date)->translatedFormat('d M, H:i') }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-light btn-sm rounded-xl px-3 hover-translate-y transition-all">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <small class="font-weight-500">Anda belum membuat tugas pembelajaran</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Shift / Countdown -->
    <div class="col-lg-5 mb-4">
        @if($shift && $clockInDeadline)
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden h-100 bg-white">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-clock mr-2"></i>Countdown Jadwal Shift Sekolah</h6>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-2xl bg-success text-white mr-3 shadow-premium" style="width: 60px; height: 60px;">
                                <i class="fas fa-business-time fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-slate-800 mb-1">Shift Utama: {{ $shift->name }}</h6>
                                <p class="text-muted extra-small mb-0">Jam Belajar: {{ $shift->start_time }} - {{ $shift->end_time }}</p>
                            </div>
                        </div>

                        <div class="border rounded-2xl p-3 mb-4 bg-slate-50">
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block mb-1">Buka Absen</small>
                                    <span class="font-weight-bold text-success">{{ \Carbon\Carbon::parse($shift->start_time)->subMinutes((int) \App\Models\Setting::get('clock_in_early_minutes', 60))->format('H:i') }}</span>
                                </div>
                                <div class="col-4 border-left border-right">
                                    <small class="text-muted d-block mb-1">Batas Telat</small>
                                    <span class="font-weight-bold text-danger">{{ \Carbon\Carbon::parse($shift->start_time)->addMinutes((int) \App\Models\Setting::get('clock_in_late_minutes', 120))->format('H:i') }}</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block mb-1">Jam Keluar</small>
                                    <span class="font-weight-bold text-primary">{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <div id="teacher-countdown-display" class="display-4 font-weight-bold text-success mb-2" style="font-family: 'Courier New', monospace; letter-spacing: 2px;">--:--:--</div>
                        <p id="teacher-countdown-label" class="text-muted mb-0 font-weight-bold small">Sisa Waktu</p>
                        <div class="progress mt-3 rounded-pill" style="height: 8px;">
                            <div id="teacher-countdown-progress" class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            @push('scripts')
            <script>
                (function() {
                    const now = new Date().getTime();
                    const clockInOpenTime = new Date('{{ $clockInOpenTime->format('Y-m-d H:i:s') }}').getTime();
                    const clockInDeadline = new Date('{{ $clockInDeadline->format('Y-m-d H:i:s') }}').getTime();
                    const clockOutTime = new Date('{{ $clockOutTime->format('Y-m-d H:i:s') }}').getTime();
                    
                    let targetTime;
                    let labelText;
                    let progressColor;
                    
                    if (now < clockInOpenTime) {
                        targetTime = clockInOpenTime;
                        labelText = 'Mulai Absensi Dalam';
                        progressColor = 'bg-secondary';
                    } else if (now < clockInDeadline) {
                        targetTime = clockInDeadline;
                        labelText = 'Batas Waktu Hadir';
                        progressColor = 'bg-danger';
                    } else if (now < clockOutTime) {
                        targetTime = clockOutTime;
                        labelText = 'Batas Waktu Pulang';
                        progressColor = 'bg-success';
                    } else {
                        targetTime = clockInOpenTime + (24 * 60 * 60 * 1000); // Tomorrow
                        labelText = 'Buka Absen Esok Hari';
                        progressColor = 'bg-info';
                    }
                    
                    const startOfDay = new Date('{{ \Carbon\Carbon::today()->format('Y-m-d H:i:s') }}').getTime();
                    const totalDuration = targetTime - startOfDay;
                    
                    function updateTeacherCountdown() {
                        const now = new Date().getTime();
                        const distance = targetTime - now;
                        
                        const display = document.getElementById('teacher-countdown-display');
                        const label = document.getElementById('teacher-countdown-label');
                        const progress = document.getElementById('teacher-countdown-progress');
                        
                        if (!display || !label || !progress) return;
                        
                        if (distance < 0) {
                            display.textContent = 'RELOAD...';
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
                        
                        label.textContent = labelText;
                        
                        const percentage = Math.min(Math.max((distance / totalDuration) * 100, 0), 100);
                        progress.style.width = percentage + '%';
                        progress.className = 'progress-bar ' + progressColor;
                    }
                    
                    updateTeacherCountdown();
                    setInterval(updateTeacherCountdown, 1000);
                })();
            </script>
            @endpush
        @endif
    </div>
</div>

<!-- Student Classroom Attendance -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-user-clock mr-2"></i>Kehadiran Siswa Kelas {{ $selectedClassName }} Hari Ini</h6>
                @if(count($myClasses) > 1)
                <div>
                    <select wire:model.live="selectedClassId" class="form-control form-control-sm border-success text-success rounded-lg font-weight-bold" style="background-color: #f0fdf4;">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($myClasses as $cls)
                            <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                        <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                            <tr>
                                <th class="border-0 px-4 py-3">Nama Siswa</th>
                                <th class="border-0 px-4 py-3">NISN</th>
                                <th class="border-0 px-4 py-3">Status Kehadiran</th>
                                <th class="border-0 px-4 py-3">Jam Masuk</th>
                                <th class="border-0 px-4 py-3">Jam Keluar</th>
                                <th class="border-0 px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-800 font-weight-500">
                            @foreach ($employees as $employee)
                                @php
                                    $attendance = $employee->attendance;
                                    $timeIn = $attendance ? $attendance?->time_in?->format('H:i:s') : null;
                                    $timeOut = $attendance ? $attendance?->time_out?->format('H:i:s') : null;
                                    $isWeekend = $date->isWeekend();
                                    $status = ($attendance ?? [
                                        'status' => $isWeekend || !$date->isPast() ? '-' : 'absent',
                                    ])['status'];
                                    
                                    switch ($status) {
                                        case 'present':
                                            $badgeClass = 'bg-emerald-50 text-success';
                                            $statusText = 'Hadir';
                                            break;
                                        case 'late':
                                            $badgeClass = 'bg-warning-50 text-warning';
                                            $statusText = 'Terlambat';
                                            break;
                                        case 'excused':
                                            $badgeClass = 'bg-blue-50 text-blue';
                                            $statusText = 'Izin';
                                            break;
                                        case 'sick':
                                            $badgeClass = 'bg-slate-50 text-slate-700';
                                            $statusText = 'Sakit';
                                            break;
                                        case 'absent':
                                            $badgeClass = 'bg-red-50 text-danger';
                                            $statusText = 'Alpa';
                                            break;
                                        default:
                                            $badgeClass = 'bg-slate-50 text-slate-500';
                                            $statusText = 'Belum Absen';
                                            break;
                                    }
                                @endphp
                                <tr wire:key="teacher-stud-{{ $employee->id }}">
                                    <td class="px-4 py-3">
                                        <div class="font-weight-600 text-slate-800">{{ $employee->name }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-slate-600">{{ $employee->nisn }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge badge-pill font-weight-bold px-3 py-2 {{ $badgeClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-slate-700">{{ $timeIn ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-slate-700">{{ $timeOut ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if ($attendance && ($attendance->attachment || $attendance->note || $attendance->lat_lng))
                                            <button type="button" class="btn btn-light btn-sm rounded-xl px-3 hover-translate-y transition-all" 
                                                    wire:click="show({{ $attendance->id }})"
                                                    onclick="setLocation({{ $attendance->latitude ?? 0 }}, {{ $attendance->longitude ?? 0 }})">
                                                <i class="fas fa-eye text-success mr-1"></i> Detail
                                            </button>
                                        @else
                                            <span class="text-muted extra-small font-weight-600">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-top-light bg-slate-50">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-attendance-detail-modal :current-attendance="$currentAttendance" />
@stack('attendance-detail-scripts')
</div>
