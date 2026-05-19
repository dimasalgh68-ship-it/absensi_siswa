@php
    $routePrefix = Auth::check() && Auth::user()->isTeacher ? 'teacher' : 'admin';
@endphp
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-1 pt-3" href="{{ route($routePrefix . '.dashboard') }}">
        <div class="sidebar-brand-icon">
            @if(Auth::check() && Auth::user()->isTeacher)
                <div class="p-2 rounded-lg text-white shadow-sm" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; background: #059669;">
                    <i class="fas fa-chalkboard-teacher fa-lg"></i>
                </div>
            @else
                @if (App\Models\Setting::get('app_logo'))
                    <img src="{{ App\Models\Setting::logo() }}" alt="Logo" class="rounded-lg shadow-sm" style="width: 40px; height: 40px; object-fit: contain; background: white; padding: 2px;">
                @else
                    <div class="p-2 bg-primary rounded-lg text-white shadow-sm" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-fingerprint fa-lg"></i>
                    </div>
                @endif
            @endif
        </div>
        <div class="sidebar-brand-text ml-2 text-left">
            @if(Auth::check() && Auth::user()->isTeacher)
                <div class="font-weight-bold" style="line-height: 1.2;">Portal Guru</div> 
            @else
                <div class="font-weight-bold" style="line-height: 1.2;">{{ App\Models\Setting::appName() }}</div>
            @endif
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.dashboard') }}">
            <i class="fas fa-fw fa-th-large"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen
    </div>

    <!-- Nav Item - Attendance -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.attendances*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.attendances') }}">
            <i class="fas fa-fw fa-clock"></i>
            <span>Presensi</span>
        </a>
    </li>

    <!-- Nav Item - Employees -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.employees*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.employees') }}">
            <i class="fas fa-fw fa-user-graduate"></i>
            <span>Siswa</span>
        </a>
    </li>

    @if(Auth::user()->isAdmin)
    <!-- Nav Item - Teachers (Admin Only) -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.teachers*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.teachers') }}">
            <i class="fas fa-fw fa-chalkboard-teacher"></i>
            <span>Guru</span>
        </a>
    </li>

    <!-- Nav Item - Teacher Subjects (Admin Only) -->
    <li class="nav-item {{ request()->routeIs('admin.teacher-subjects') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.teacher-subjects') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Mata Pelajaran Guru</span>
        </a>
    </li>
    @endif

    @if(Auth::user()->isAdmin)
    <!-- Nav Item - Face Registration -->
    <li class="nav-item {{ request()->routeIs('admin.face-registrations') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.face-registrations') }}">
            <i class="fas fa-fw fa-smile"></i>
            <span>Face Registration</span>
        </a>
    </li>

    <!-- Nav Item - Office Locations -->
    <li class="nav-item {{ request()->routeIs('admin.office-locations') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.office-locations') }}">
            <i class="fas fa-fw fa-map-location-dot"></i>
            <span>Lokasi Sekolah</span>
        </a>
    </li>

    <!-- Nav Item - Academic Events -->
    <li class="nav-item {{ request()->routeIs('admin.academic-events') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.academic-events') }}">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Academic Events</span>
        </a>
    </li>
    @endif

    <!-- Nav Item - Academic Calendar -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.academic-calendar') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.academic-calendar') }}">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Kalender Akademik</span>
        </a>
    </li>

    @if(Auth::user()->isTeacher || Auth::user()->isAdmin)
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Portal Guru
    </div>

    <!-- Nav Item - Materi -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.materials*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.materials') }}">
            <i class="fas fa-fw fa-book-open"></i>
            <span>Materi</span>
        </a>
    </li>

    <!-- Nav Item - Tugas -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.tasks*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.tasks') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Tugas</span>
        </a>
    </li>

    <!-- Nav Item - Jadwal -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.schedules*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.schedules') }}">
            <i class="fas fa-fw fa-calendar-week"></i>
            <span>Jadwal Mengajar</span>
        </a>
    </li>

    <!-- Nav Item - Ujian CBT -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.exams*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.exams') }}">
            <i class="fas fa-fw fa-laptop-code"></i>
            <span>Ujian CBT</span>
        </a>
    </li>

    <!-- Nav Item - Nilai -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.grades*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.grades') }}">
            <i class="fas fa-fw fa-star"></i>
            <span>Input Nilai</span>
        </a>
    </li>

    <!-- Nav Item - Raport -->
    <li class="nav-item {{ request()->routeIs($routePrefix . '.report-cards*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($routePrefix . '.report-cards') }}">
            <i class="fas fa-fw fa-file-contract"></i>
            <span>Raport Semesteran</span>
        </a>
    </li>
    @endif

    @if(Auth::user()->isAdmin)
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data Lanjutan
    </div>

    <!-- Nav Item - Master Data Collapse Menu -->
    <li class="nav-item {{ request()->routeIs('admin.masters.*') ? 'active' : '' }}" 
        x-data="{ open: {{ request()->routeIs('admin.masters.*') ? 'true' : 'false' }} }">
        <a class="nav-link d-flex justify-content-between align-items-center" 
           href="javascript:void(0)" 
           @click="open = !open">
            <div class="d-flex align-items-center">
                <i class="fas fa-fw fa-layer-group"></i>
                <span>Master Data</span>
            </div>
            <i class="fas fa-chevron-down extra-small transition-all" :class="{ 'rotate-180 opacity-100': open, 'opacity-40': !open }"></i>
        </a>
        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="px-3 pb-2">
            <div class="rounded-xl py-2" style="background: rgba(255,255,255,0.03);">
                <div class="px-3 py-1 mb-1">
                    <span class="extra-small text-white-50 font-weight-bold tracking-widest uppercase">Data Master</span>
                </div>
                <a class="sub-nav-link {{ request()->routeIs('admin.masters.division') ? 'active' : '' }}" href="{{ route('admin.masters.division') }}">Angkatan</a>
                <a class="sub-nav-link {{ request()->routeIs('admin.masters.job-title') ? 'active' : '' }}" href="{{ route('admin.masters.job-title') }}">Jurusan</a>
                <a class="sub-nav-link {{ request()->routeIs('admin.masters.education') ? 'active' : '' }}" href="{{ route('admin.masters.education') }}">Kelas</a>
                <a class="sub-nav-link {{ request()->routeIs('admin.masters.shift') ? 'active' : '' }}" href="{{ route('admin.masters.shift') }}">Jadwal</a>
                <a class="sub-nav-link {{ request()->routeIs('admin.masters.subject') ? 'active' : '' }}" href="{{ route('admin.masters.subject') }}">Mata Pelajaran</a>
                
                <div class="px-3 py-1 mt-2 mb-1 border-top border-white-10 opacity-20"></div>
                <div class="px-3 py-1 mb-1">
                    <span class="extra-small text-white-50 font-weight-bold tracking-widest uppercase">Pengguna</span>
                </div>
                <a class="sub-nav-link {{ request()->routeIs('admin.masters.admin') ? 'active' : '' }}" href="{{ route('admin.masters.admin') }}">Administrator</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Import Export Collapse Menu -->
    <li class="nav-item {{ request()->routeIs('admin.import-export.*') ? 'active' : '' }}" 
        x-data="{ open: {{ request()->routeIs('admin.import-export.*') ? 'true' : 'false' }} }">
        <a class="nav-link d-flex justify-content-between align-items-center" 
           href="javascript:void(0)" 
           @click="open = !open">
            <div class="d-flex align-items-center">
                <i class="fas fa-fw fa-file-export"></i>
                <span>Import & Export</span>
            </div>
            <i class="fas fa-chevron-down extra-small transition-all" :class="{ 'rotate-180 opacity-100': open, 'opacity-40': !open }"></i>
        </a>
        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="px-3 pb-2">
            <div class="rounded-xl py-2" style="background: rgba(255,255,255,0.03);">
                <div class="px-3 py-1 mb-1">
                    <span class="extra-small text-white-50 font-weight-bold tracking-widest uppercase">Utilitas Data</span>
                </div>
                <a class="sub-nav-link {{ request()->routeIs('admin.import-export.users') ? 'active' : '' }}" href="{{ route('admin.import-export.users') }}">Siswa / Admin</a>
                <a class="sub-nav-link {{ request()->routeIs('admin.import-export.attendances') ? 'active' : '' }}" href="{{ route('admin.import-export.attendances') }}">Presensi Siswa</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <!-- Nav Item - Settings -->
    <li class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.settings') }}">
            <i class="fas fa-fw fa-sliders"></i>
            <span>Pengaturan</span>
        </a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">


</ul>
