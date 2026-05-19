<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="<?php echo e(route('admin.dashboard')); ?>">
        <div class="sidebar-brand-icon">
            <?php if(App\Models\Setting::get('app_logo')): ?>
                <img src="<?php echo e(App\Models\Setting::logo()); ?>" alt="Logo" class="rounded-lg shadow-sm" style="width: 40px; height: 40px; object-fit: contain; background: white; padding: 2px;">
            <?php else: ?>
                <div class="p-2 bg-primary rounded-lg text-white shadow-sm" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-fingerprint fa-lg"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="sidebar-brand-text ml-2">
            <div class="font-weight-bold" style="line-height: 1.2;"><?php echo e(App\Models\Setting::appName()); ?></div>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">
            <i class="fas fa-fw fa-th-large"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen
    </div>

    <!-- Nav Item - Attendance -->
    <li class="nav-item <?php echo e(request()->routeIs('admin.attendances*') ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo e(route('admin.attendances')); ?>">
            <i class="fas fa-fw fa-clock"></i>
            <span>Presensi</span>
        </a>
    </li>

    <!-- Nav Item - Employees -->
    <li class="nav-item <?php echo e(request()->routeIs('admin.employees*') ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo e(route('admin.employees')); ?>">
            <i class="fas fa-fw fa-user-graduate"></i>
            <span>Siswa</span>
        </a>
    </li>

    <!-- Nav Item - Face Registration -->
    <li class="nav-item <?php echo e(request()->routeIs('admin.face-registrations') ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo e(route('admin.face-registrations')); ?>">
            <i class="fas fa-fw fa-smile"></i>
            <span>Face Registration</span>
        </a>
    </li>

    <!-- Nav Item - Office Locations -->
    <li class="nav-item <?php echo e(request()->routeIs('admin.office-locations') ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo e(route('admin.office-locations')); ?>">
            <i class="fas fa-fw fa-map-location-dot"></i>
            <span>Lokasi Sekolah</span>
        </a>
    </li>

    <!-- Nav Item - Academic Events -->
    <li class="nav-item <?php echo e(request()->routeIs('admin.academic-events') ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo e(route('admin.academic-events')); ?>">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Academic Events</span>
        </a>
    </li>

    <!-- Nav Item - Academic Calendar -->
    <li class="nav-item <?php echo e(request()->routeIs('admin.academic-calendar') ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo e(route('admin.academic-calendar')); ?>">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Kalender Akademik</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data Lanjutan
    </div>

    <!-- Nav Item - Master Data Collapse Menu -->
    <li class="nav-item <?php echo e(request()->routeIs('admin.masters.*') ? 'active' : ''); ?>" 
        x-data="{ open: <?php echo e(request()->routeIs('admin.masters.*') ? 'true' : 'false'); ?> }">
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
                <a class="sub-nav-link <?php echo e(request()->routeIs('admin.masters.division') ? 'active' : ''); ?>" href="<?php echo e(route('admin.masters.division')); ?>">Angkatan</a>
                <a class="sub-nav-link <?php echo e(request()->routeIs('admin.masters.job-title') ? 'active' : ''); ?>" href="<?php echo e(route('admin.masters.job-title')); ?>">Jurusan</a>
                <a class="sub-nav-link <?php echo e(request()->routeIs('admin.masters.education') ? 'active' : ''); ?>" href="<?php echo e(route('admin.masters.education')); ?>">Kelas</a>
                <a class="sub-nav-link <?php echo e(request()->routeIs('admin.masters.shift') ? 'active' : ''); ?>" href="<?php echo e(route('admin.masters.shift')); ?>">Jadwal</a>
                
                <div class="px-3 py-1 mt-2 mb-1 border-top border-white-10 opacity-20"></div>
                <div class="px-3 py-1 mb-1">
                    <span class="extra-small text-white-50 font-weight-bold tracking-widest uppercase">Pengguna</span>
                </div>
                <a class="sub-nav-link <?php echo e(request()->routeIs('admin.masters.admin') ? 'active' : ''); ?>" href="<?php echo e(route('admin.masters.admin')); ?>">Administrator</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Import Export Collapse Menu -->
    <li class="nav-item <?php echo e(request()->routeIs('admin.import-export.*') ? 'active' : ''); ?>" 
        x-data="{ open: <?php echo e(request()->routeIs('admin.import-export.*') ? 'true' : 'false'); ?> }">
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
                <a class="sub-nav-link <?php echo e(request()->routeIs('admin.import-export.users') ? 'active' : ''); ?>" href="<?php echo e(route('admin.import-export.users')); ?>">Siswa / Admin</a>
                <a class="sub-nav-link <?php echo e(request()->routeIs('admin.import-export.attendances') ? 'active' : ''); ?>" href="<?php echo e(route('admin.import-export.attendances')); ?>">Presensi Siswa</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <!-- Nav Item - Settings -->
    <li class="nav-item <?php echo e(request()->routeIs('admin.settings') ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo e(route('admin.settings')); ?>">
            <i class="fas fa-fw fa-sliders"></i>
            <span>Pengaturan</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" style="background: rgba(255,255,255,0.05); color: #fff;"></button>
    </div>

</ul>
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/layouts/partials/admin-sidebar.blade.php ENDPATH**/ ?>