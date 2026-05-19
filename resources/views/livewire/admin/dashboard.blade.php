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
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden bg-gradient-primary text-white p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="font-weight-bold mb-1">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h2>
                    <p class="mb-0 opacity-80 font-weight-500">Pantau kehadiran, kelola materi, dan pantau aktivitas belajar mengajar di sekolah dalam satu panel terintegrasi.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="badge badge-pill bg-white text-primary font-weight-bold px-3 py-2">
                        <i class="fas fa-calendar-day mr-1"></i> {{ $date->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Primary Attendance Metric Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden hover-translate-y transition-all glass-card">
            <div class="card-body p-4" style="border-left: 5px solid #10b981;">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir (Hari Ini)</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $presentCount }}</div>
                        <div class="text-xs text-muted mt-1 font-weight-500">Terlambat: {{ $lateCount }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-xl bg-success-light" style="background: rgba(16, 185, 129, 0.1);">
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
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Izin / Sakit</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $excusedCount + $sickCount }}</div>
                        <div class="text-xs text-muted mt-1 font-weight-500">Izin: {{ $excusedCount }} | Sakit: {{ $sickCount }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-xl bg-primary-light" style="background: rgba(59, 130, 246, 0.1);">
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
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tanpa Keterangan</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $absentCount }}</div>
                        <div class="text-xs text-muted mt-1 font-weight-500">Belum melakukan absensi</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-xl bg-danger-light" style="background: rgba(239, 68, 68, 0.1);">
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Siswa</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $employeesCount }}</div>
                        <div class="text-xs text-muted mt-1 font-weight-500">Terdaftar di sistem</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-xl bg-warning-light" style="background: rgba(245, 158, 11, 0.1);">
                            <i class="fas fa-users fa-lg text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Portal Quick Stats -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('admin.masters.admin') }}" class="text-decoration-none hover-translate-y transition-all d-block">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-slate-600 font-weight-bold mb-1">Guru Pengajar</h6>
                            <h3 class="font-weight-bold text-slate-800 mb-0">{{ $teachersCount }}</h3>
                            <small class="text-primary font-weight-600">Kelola Pengguna Guru &rarr;</small>
                        </div>
                        <div class="p-3 rounded-xl bg-indigo-50 text-indigo">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('admin.face-registrations') }}" class="text-decoration-none hover-translate-y transition-all d-block">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-slate-600 font-weight-bold mb-1">Face Registration</h6>
                            <h3 class="font-weight-bold text-slate-800 mb-0">{{ $faceRegCount }}</h3>
                            <small class="text-primary font-weight-600">Verifikasi Wajah Siswa &rarr;</small>
                        </div>
                        <div class="p-3 rounded-xl bg-purple-50 text-purple">
                            <i class="fas fa-smile fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-12 mb-4">
        <a href="{{ route('admin.office-locations') }}" class="text-decoration-none hover-translate-y transition-all d-block">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-slate-600 font-weight-bold mb-1">Titik GPS Lokasi</h6>
                            <h3 class="font-weight-bold text-slate-800 mb-0">{{ $officeLocCount }}</h3>
                            <small class="text-primary font-weight-600">Kelola Geofence Sekolah &rarr;</small>
                        </div>
                        <div class="p-3 rounded-xl bg-pink-50 text-pink">
                            <i class="fas fa-map-location-dot fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row mb-4">
    <!-- Today's Teaching Schedules & Recent Tasks -->
    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom-light d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-alt mr-2"></i>Jadwal Hari Ini ({{ $todayDayName }})</h6>
                <a href="{{ route('admin.schedules') }}" class="small font-weight-bold">Semua Jadwal &rarr;</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                        <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                            <tr>
                                <th class="border-0 px-4 py-3">Waktu</th>
                                <th class="border-0 px-4 py-3">Mapel & Kelas</th>
                                <th class="border-0 px-4 py-3">Ruangan</th>
                                <th class="border-0 px-4 py-3">Pengampu</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-800 font-weight-500">
                            @forelse($todaySchedules as $sch)
                                <tr>
                                    <td class="px-4 py-3 text-nowrap">
                                        <span class="badge badge-pill bg-blue-50 text-blue font-weight-bold px-3 py-2">
                                            {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-weight-600 text-slate-800">{{ $sch->subject->name ?? 'N/A' }}</div>
                                        <small class="text-slate-600">{{ $sch->education->name ?? 'N/A' }}</small>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-weight-600 text-slate-700">{{ $sch->room ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="small text-slate-600">{{ $sch->teacher->name ?? 'N/A' }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <small class="font-weight-500">Tidak ada jadwal mengajar hari ini</small>
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
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tasks mr-2"></i>Tugas Siswa Terbaru</h6>
                <a href="{{ route('admin.tasks') }}" class="small font-weight-bold">Semua Tugas &rarr;</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                        <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                            <tr>
                                <th class="border-0 px-4 py-3">Tugas</th>
                                <th class="border-0 px-4 py-3">Batas Waktu</th>
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
                                        <small class="font-weight-500">Belum ada tugas yang dibuat</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Countdown / Active Shift -->
    <div class="col-lg-5 mb-4">
        @if($shift && $clockInDeadline)
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden h-100 bg-white">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clock mr-2"></i>Status Shift & Absensi Hari Ini</h6>
                </div>
                <div class="card-body p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-2xl bg-primary text-white mr-3 shadow-premium" style="width: 60px; height: 60px;">
                                <i class="fas fa-business-time fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-slate-800 mb-1">Shift Aktif: {{ $shift->name }}</h6>
                                <p class="text-muted extra-small mb-0">Waktu Kerja: {{ $shift->start_time }} - {{ $shift->end_time }}</p>
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
                        <div id="admin-countdown-display" class="display-4 font-weight-bold text-primary mb-2" style="font-family: 'Courier New', monospace; letter-spacing: 2px;">--:--:--</div>
                        <p id="admin-countdown-label" class="text-muted mb-0 font-weight-bold small">Sisa Waktu</p>
                        <div class="progress mt-3 rounded-pill" style="height: 8px;">
                            <div id="admin-countdown-progress" class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            @push('scripts')
            <script>
                // Countdown Timer
                const now = new Date().getTime();
                const clockInOpenTime = new Date('{{ $clockInOpenTime->format('Y-m-d H:i:s') }}').getTime();
                const clockInDeadline = new Date('{{ $clockInDeadline->format('Y-m-d H:i:s') }}').getTime();
                const clockOutTime = new Date('{{ $clockOutTime->format('Y-m-d H:i:s') }}').getTime();
                
                let targetTime;
                let labelText;
                let progressColor;
                
                if (now < clockInOpenTime) {
                    targetTime = clockInOpenTime;
                    labelText = 'Dibuka Dalam';
                    progressColor = 'bg-secondary';
                } else if (now < clockInDeadline) {
                    targetTime = clockInDeadline;
                    labelText = 'Batas Waktu Hadir';
                    progressColor = 'bg-danger';
                } else if (now < clockOutTime) {
                    targetTime = clockOutTime;
                    labelText = 'Jam Pulang Kerja';
                    progressColor = 'bg-primary';
                } else {
                    targetTime = clockInOpenTime + (24 * 60 * 60 * 1000); // Tomorrow
                    labelText = 'Buka Absen Esok Hari';
                    progressColor = 'bg-info';
                }
                
                const startOfDay = new Date('{{ \Carbon\Carbon::today()->format('Y-m-d H:i:s') }}').getTime();
                const totalDuration = targetTime - startOfDay;
                
                function updateAdminCountdown() {
                    const now = new Date().getTime();
                    const distance = targetTime - now;
                    
                    const display = document.getElementById('admin-countdown-display');
                    const label = document.getElementById('admin-countdown-label');
                    const progress = document.getElementById('admin-countdown-progress');
                    
                    if (!display || !label || !progress) return;
                    
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
                    
                    label.textContent = labelText;
                    
                    const percentage = Math.min(Math.max((distance / totalDuration) * 100, 0), 100);
                    progress.style.width = percentage + '%';
                    progress.className = 'progress-bar ' + progressColor;
                }
                
                updateAdminCountdown();
                setInterval(updateAdminCountdown, 1000);
            </script>
            @endpush
        @endif
    </div>
</div>

<!-- Student Live Attendance Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom-light d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-clock mr-2"></i>Kehadiran Live Siswa Hari Ini</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                        <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                            <tr>
                                <th class="border-0 px-4 py-3">Nama Siswa</th>
                                <th class="border-0 px-4 py-3">NISN</th>
                                <th class="border-0 px-4 py-3">Kelas & Jurusan</th>
                                <th class="border-0 px-4 py-3">Status Kehadiran</th>
                                <th class="border-0 px-4 py-3">Jam Masuk</th>
                                <th class="border-0 px-4 py-3">Jam Keluar</th>
                                <th class="border-0 px-4 py-3 text-right">Aksi / Detail</th>
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
                                <tr wire:key="{{ $employee->id }}">
                                    <td class="px-4 py-3">
                                        <div class="font-weight-600 text-slate-800">{{ $employee->name }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-slate-600">{{ $employee->nisn }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-weight-600 text-slate-700">{{ $employee->education?->name ?? '-' }}</div>
                                        <small class="text-slate-600">{{ $employee->jobTitle?->name ?? '-' }}</small>
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
                                                <i class="fas fa-eye text-primary mr-1"></i> Detail
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
