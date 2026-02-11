 <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top sticky-top header-glass border-0" style="z-index: 1030;">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-2 hover-scale">
        <i class="fa fa-bars text-primary"></i>
    </button>

    <!-- Mobile Branding (Logo & Name) -->
    <div class="d-flex d-md-none align-items-center mr-auto">
        @if (App\Models\Setting::get('app_logo'))
            <img src="{{ App\Models\Setting::logo() }}" alt="Logo" class="rounded shadow-sm" style="height: 32px; width: 32px; object-fit: contain; background: white; padding: 2px;">
        @endif
        <div class="ml-2 flex flex-col">
            <span class="text-slate-800 font-weight-bold small line-height-1" style="font-size: 0.85rem;">{{ App\Models\Setting::appName() }}</span>
            <span class="text-slate-400 extra-small font-weight-600" style="font-size: 0.6rem;">Mobile Dashboard</span>
        </div>
    </div>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group search-container">
            <div class="input-group-prepend">
                <span class="bg-transparent border-0 pl-3 pr-2">
                    <i class="fas fa-search fa-sm text-blue-400"></i>
                </span>
            </div>
            <input type="text" class="form-control bg-transparent border-0 small shadow-none text-slate-600 font-weight-500" 
                   placeholder="Cari data atau siswa..." aria-label="Search" id="topbarSearch">
            <div class="search-shortcut mr-2 d-none d-lg-flex">
                <kbd>/</kbd>
            </div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-2" x-data="{ open: false }" @click.away="open = false">
            <button type="button" 
                    class="nav-link nav-icon-btn bg-transparent border-0 position-relative" 
                    @click="open = !open">
                <i class="fas fa-bell fa-fw"></i>
                <span class="status-indicator-pulse"></span>
                <span class="badge badge-pill badge-danger position-absolute" 
                      style="font-size: 0.55rem; top: 0px; right: 0px; padding: 0.2rem 0.35rem; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    3
                </span>
            </button>
            
            <!-- Dropdown - Alerts -->
            <div x-show="open" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                 class="dropdown-menu-custom shadow-premium border-0 rounded-2xl mt-3" 
                 style="position: absolute; right: 0; min-width: 320px; z-index: 1060; background: white; overflow: hidden;">
                <div class="dropdown-header-premium d-flex justify-content-between align-items-center px-4 py-3">
                    <h6 class="m-0 font-weight-bold font-outfit">Notifikasi</h6>
                    <span class="badge badge-primary-light px-2 py-1" style="background: rgba(79, 70, 229, 0.1); color: var(--primary-color);">3 Baru</span>
                </div>
                <div class="notification-scroll" style="max-height: 320px; overflow-y: auto;">
                    <a class="dropdown-item d-flex align-items-center py-3 px-4 border-bottom-light" href="#">
                        <div class="mr-3">
                            <div class="icon-circle bg-blue-50 text-blue-500 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <div class="small text-muted mb-1">{{ date('H:i') }} • Hari Ini</div>
                            <span class="font-weight-600 text-slate-700 d-block text-truncate">Laporan absensi bulanan tersedia</span>
                        </div>
                    </a>
                    <a class="dropdown-item d-flex align-items-center py-3 px-4 border-bottom-light" href="#">
                        <div class="mr-3">
                            <div class="icon-circle bg-emerald-50 text-emerald-500 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <div class="small text-muted mb-1">Kemarin</div>
                            <span class="text-slate-600 d-block text-truncate font-weight-600">Siswa baru terdaftar</span>
                        </div>
                    </a>
                </div>
                <a class="dropdown-item text-center small text-indigo-600 font-weight-bold py-3 bg-light" href="#">Tandai Semua Sudah Dibaca</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block mx-2"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow" x-data="{ open: false }" @click.away="open = false">
            <button type="button" 
                    class="nav-link d-flex align-items-center px-2 py-1 user-nav-link rounded-pill bg-transparent border-0" 
                    @click="open = !open">
                <div class="text-right mr-3 d-none d-lg-inline">
                    <span class="d-block text-slate-800 font-weight-bold small line-height-1">{{ Auth::user()->name }}</span>
                    <span class="text-slate-400 extra-small font-weight-600">Administrator</span>
                </div>
                <div class="profile-avatar shadow-sm">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img class="img-profile rounded-circle" 
                             src="{{ Auth::user()->profile_photo_url }}" 
                             alt="{{ Auth::user()->name }}" 
                             style="width: 34px; height: 34px; object-fit: cover; border: 2px solid white;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold shadow-sm" 
                             style="width: 34px; height: 34px; background: var(--primary-color); font-size: 0.9rem; border: 2px solid white;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="status-dot shadow-sm"></div>
                </div>
                <i class="fas fa-chevron-down ml-2 extra-small text-slate-400 transition-all" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <!-- Dropdown - User Information -->
            <div x-show="open" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                 class="dropdown-menu-custom shadow-premium border-0 rounded-2xl mt-3 p-3" 
                 style="position: absolute; right: 0; min-width: 280px; z-index: 1060; background: white;">
                
                <!-- User Info Card -->
                <div class="px-3 py-3 mb-2 bg-slate-50 rounded-xl d-flex align-items-center">
                    <div class="mr-3">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <img class="rounded-circle shadow-sm border border-white" src="{{ Auth::user()->profile_photo_url }}" style="width: 48px; height: 48px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center text-primary font-weight-bold shadow-sm" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="overflow-hidden text-left flex-grow-1">
                        <h6 class="p-0 text-slate-800 font-weight-bold mb-1 d-block text-truncate" style="font-size: 0.9rem;">{{ Auth::user()->name }}</h6>
                        <p class="mb-0 text-slate-500 extra-small tracking-wide text-truncate" style="font-size: 0.7rem;">{{ Auth::user()->email }}</p>
                        <span class="badge badge-primary mt-1 px-2 py-1" style="font-size: 0.65rem; background: rgba(79, 70, 229, 0.1); color: var(--primary-color); border: 1px solid rgba(79, 70, 229, 0.2);">
                            <i class="fas fa-shield-alt mr-1"></i>Administrator
                        </span>
                    </div>
                </div>
                
                <!-- Menu Items -->
                <div class="dropdown-items">
                    <a class="dropdown-item rounded-xl py-2 px-3 d-flex align-items-center transition-all hover-translate-x" href="{{ route('profile.show') }}">
                        <div class="item-icon-container mr-3 bg-blue-50 text-blue-600 rounded-lg">
                            <i class="fas fa-user-circle fa-sm"></i>
                        </div>
                        <div class="flex-grow-1">
                            <span class="font-weight-600 text-slate-700 small d-block">Profil Saya</span>
                            <span class="text-slate-400 extra-small">Kelola akun Anda</span>
                        </div>
                    </a>
                    
                    <a class="dropdown-item rounded-xl py-2 px-3 d-flex align-items-center transition-all hover-translate-x" href="{{ route('admin.settings') }}">
                        <div class="item-icon-container mr-3 bg-indigo-50 text-indigo-600 rounded-lg">
                            <i class="fas fa-sliders-h fa-sm"></i>
                        </div>
                        <div class="flex-grow-1">
                            <span class="font-weight-600 text-slate-700 small d-block">Pengaturan</span>
                            <span class="text-slate-400 extra-small">Konfigurasi sistem</span>
                        </div>
                    </a>
                    
                    <a class="dropdown-item rounded-xl py-2 px-3 d-flex align-items-center transition-all hover-translate-x" href="{{ route('admin.dashboard') }}">
                        <div class="item-icon-container mr-3 bg-emerald-50 text-emerald-600 rounded-lg">
                            <i class="fas fa-th-large fa-sm"></i>
                        </div>
                        <div class="flex-grow-1">
                            <span class="font-weight-600 text-slate-700 small d-block">Dashboard</span>
                            <span class="text-slate-400 extra-small">Kembali ke beranda</span>
                        </div>
                    </a>
                </div>
                
                <div class="dropdown-divider my-2 border-slate-100 opacity-50"></div>
                
                <!-- Logout Button -->
                <a class="dropdown-item rounded-xl py-2 px-3 d-flex align-items-center text-danger transition-all hover-translate-x" href="#" data-toggle="modal" data-target="#logoutModal">
                    <div class="item-icon-container mr-3 bg-red-50 text-red-600 rounded-lg">
                        <i class="fas fa-sign-out-alt fa-sm"></i>
                    </div>
                    <div class="flex-grow-1">
                        <span class="font-weight-700 small d-block">Keluar Sistem</span>
                        <span class="extra-small" style="color: #f87171;">Logout dari akun</span>
                    </div>
                </a>
            </div>
        </li>

    </ul>

</nav>

<style>
    /* Stability Fixes for Dropdowns */
    .dropdown-menu-custom {
        display: none;
    }
    
    [x-cloak] { 
        display: none !important; 
    }
    
    .user-nav-link:focus, .nav-icon-btn:focus {
        outline: none;
        box-shadow: none;
    }
    
    .hover-translate-x {
        transition: all 0.2s ease;
    }
    
    .hover-translate-x:hover {
        transform: translateX(4px);
        background-color: #f8fafc !important;
    }
    
    /* Ensure no conflicts with SB Admin 2 */
    .topbar .nav-item.dropdown .dropdown-menu {
        position: absolute !important;
    }
    
    /* Profile dropdown improvements */
    .dropdown-item {
        border-radius: 0.75rem;
        margin-bottom: 0.25rem;
        transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
        background-color: #f8fafc;
    }
    
    .dropdown-item:active {
        background-color: #f1f5f9;
    }
    
    /* Icon container */
    .item-icon-container {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .dropdown-item:hover .item-icon-container {
        transform: scale(1.05);
    }
    
    /* Badge styling */
    .badge-primary {
        font-weight: 600;
        letter-spacing: 0.025em;
    }
    
    /* Smooth animations */
    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Profile avatar hover effect */
    .profile-avatar {
        transition: transform 0.2s ease;
    }
    
    .user-nav-link:hover .profile-avatar {
        transform: scale(1.05);
    }
    
    /* Notification scroll */
    .notification-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .notification-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    .notification-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .notification-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
    // Add shortcut listener for search
    if (!window.searchShortcutInit) {
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                const searchInput = document.getElementById('topbarSearch');
                if (searchInput) searchInput.focus();
            }
        });
        window.searchShortcutInit = true;
    }
</script>
