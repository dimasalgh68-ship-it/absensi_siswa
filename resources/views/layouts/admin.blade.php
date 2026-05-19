<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
    @stack('styles')
    
    <style>
        :root {
            @if(Auth::check() && Auth::user()->isTeacher)
                /* Teacher Premium Theme (Teal/Emerald) */
                --primary-color: #059669;
                --primary-hover: #047857;
                --sidebar-bg: #064e3b;
                --sidebar-item-active: rgba(255, 255, 255, 0.12);
            @else
                /* Admin Premium Theme (Indigo/Slate) */
                --primary-color: #4f46e5;
                --primary-hover: #4338ca;
                --sidebar-bg: #0f172a;
                --sidebar-item-active: rgba(255, 255, 255, 0.1);
            @endif
            --content-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --dropdown-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
            --sidebar-width: 250px; /* Default sidebar width */
            --transition-speed: 0.2s; /* Adjustable transition speed */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--content-bg);
            color: #1e293b;
        }

        h1, h2, h3, h4, h5, h6, .sidebar-brand-text {
            font-family: 'Outfit', sans-serif;
        }

        /* Modern Sidebar */
        #accordionSidebar {
            background: var(--sidebar-bg) !important;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
<<<<<<< HEAD
            transition: width var(--transition-speed) ease, left var(--transition-speed) ease;
=======
            transition: all 0.3s ease;
>>>>>>> b7451b4dfb32aa6d059cb1d176141e6ab49a7ffd
            z-index: 1025 !important;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
<<<<<<< HEAD
            width: var(--sidebar-width) !important;
=======
>>>>>>> b7451b4dfb32aa6d059cb1d176141e6ab49a7ffd
        }
        
        /* Custom scrollbar for sidebar */
        #accordionSidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        #accordionSidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        #accordionSidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
        
        #accordionSidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar-dark .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 0.75rem;
            border-radius: 0.6rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .sidebar-dark .nav-item .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-dark .nav-item.active .nav-link {
            background-color: var(--primary-color) !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .sidebar-dark .nav-item .nav-link i {
            color: inherit;
            margin-right: 0.75rem;
            font-size: 1rem;
            width: 1.5rem;
            text-align: center;
            opacity: 0.8;
        }

        .sidebar-brand {
            height: 5rem !important;
            margin-bottom: 0.5rem;
        }

        .sidebar-brand-text {
            letter-spacing: 0.5px;
            font-weight: 700;
            text-transform: none;
            font-size: 1.1rem;
            margin-left: 0.25rem;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
            margin: 1rem 0.75rem !important;
        }

        .sidebar-heading {
            padding: 0 1.75rem !important;
            color: rgba(255, 255, 255, 0.4) !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 1.2px;
            font-size: 0.65rem !important;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }

        /* Premium UI Components */
        .header-glass {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
            border-bottom: 1px solid rgba(241, 245, 249, 0.7) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02) !important;
        }
        
        /* Topbar positioning - don't overlap sidebar */
        .topbar {
<<<<<<< HEAD
            left: var(--sidebar-width) !important;
            transition: left var(--transition-speed) ease;
=======
            left: 224px !important; /* Sidebar width */
            transition: left 0.3s ease;
>>>>>>> b7451b4dfb32aa6d059cb1d176141e6ab49a7ffd
        }
        
        /* Content wrapper - don't overlap sidebar */
        #content-wrapper {
<<<<<<< HEAD
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) ease;
            
=======
            margin-left: 224px; /* Sidebar width */
            transition: margin-left 0.3s ease;
>>>>>>> b7451b4dfb32aa6d059cb1d176141e6ab49a7ffd
        }
        
        /* When sidebar is toggled (collapsed) */
        .sidebar-toggled .topbar {
<<<<<<< HEAD
            left: 104px !important;
        }
        
        .sidebar-toggled #content-wrapper {
            margin-left: 104px;
        }
        
        /* Resizer handle */
        .sidebar-resizer {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            width: 8px;
            height: 100vh;
            cursor: col-resize;
            z-index: 1030;
            background: transparent;
            transform: translateX(-50%);
            transition: background 0.2s;
        }
        .sidebar-resizer:hover, .sidebar-resizer.is-resizing {
            background: rgba(79, 70, 229, 0.5); /* Highlight color when hovered or dragged */
        }
        .sidebar-toggled .sidebar-resizer {
            display: none; /* Hide resizer when collapsed */
=======
            left: 0 !important;
        }
        
        .sidebar-toggled #content-wrapper {
            margin-left: 0;
>>>>>>> b7451b4dfb32aa6d059cb1d176141e6ab49a7ffd
        }
        
        /* Mobile - topbar full width, content no margin */
        @media (max-width: 768px) {
            .topbar {
                left: 0 !important;
            }
            
            #content-wrapper {
                margin-left: 0 !important;
            }
        }

        .search-container {
            background: #f1f5f9;
            border-radius: 12px;
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 300px;
        }

        .search-container:focus-within {
            background: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            width: 350px;
        }

        .search-shortcut kbd {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .nav-icon-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
            color: #64748b !important;
        }

        .nav-icon-btn:hover {
            background: #f1f5f9;
            color: var(--primary-color) !important;
            transform: translateY(-1px);
        }

        .status-indicator-pulse {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .shadow-premium {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        .dropdown-header-premium {
            background: #f8fafc;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
        }

        .border-bottom-light {
            border-bottom: 1px solid #f8fafc;
        }

        .user-nav-link {
            transition: all 0.2s;
            padding: 0.4rem 0.75rem !important;
        }

        .user-nav-link:hover {
            background: #f1f5f9;
        }

        .profile-avatar {
            position: relative;
            padding: 2px;
            background: linear-gradient(135deg, #4f46e5 0%, #818cf8 100%);
            border-radius: 50%;
        }

        .status-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            background: #10b981;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .extra-small {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .line-height-1 {
            line-height: 1.1;
        }

        .rounded-xl { border-radius: 0.75rem !important; }
        .rounded-2xl { border-radius: 1rem !important; }

        .hover-translate-x:hover {
            transform: translateX(4px);
        }

        .item-icon-container {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-slate-50 { background-color: #f8fafc !important; }
        .bg-blue-50 { background-color: #eff6ff !important; }
        .bg-indigo-50 { background-color: #eef2ff !important; }
        .bg-emerald-50 { background-color: #ecfdf5 !important; }
        .bg-red-50 { background-color: #fef2f2 !important; }
        
        .text-slate-400 { color: #94a3b8 !important; }
        .text-slate-600 { color: #475569 !important; }
        .text-slate-700 { color: #334155 !important; }
        .text-slate-800 { color: #1e293b !important; }

        .font-weight-600 { font-weight: 600 !important; }

        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Sidebar Sub-menu Styling */
        .sub-nav-link {
            display: block;
            color: rgba(255, 255, 255, 0.6) !important;
            padding: 0.5rem 1rem;
            margin: 0.1rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none !important;
            transition: all 0.2s;
        }

        .sub-nav-link:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }

        .sub-nav-link.active {
            color: #fff !important;
            background: rgba(79, 70, 229, 0.12) !important;
            font-weight: 700;
            box-shadow: inset 2px 0 0 var(--primary-color);
        }

        .border-white-10 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        /* Animation utilities */
        .animated--fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        [x-cloak] { display: none !important; }

        /* =============================================
           FIX: Jetstream dialog-modal — z-index + scroll
           Sidebar z-index: 1025, so modal must be higher
        ============================================= */

        /* Outer wrapper: positioned within content area (right of sidebar) */
        .jetstream-modal {
            z-index: 1060 !important;
            position: fixed !important;
            top: 0 !important;
            left: var(--sidebar-width) !important; /* match sidebar width */
            width: calc(100% - var(--sidebar-width)) !important;
            height: 100% !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch;
            padding: 5rem 1rem 2rem !important;
            box-sizing: border-box !important;
        }

        /* When sidebar is collapsed, modal takes full width */
        .sidebar-toggled .jetstream-modal {
            left: 104px !important;
            width: calc(100% - 104px) !important;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .jetstream-modal {
                left: 0 !important;
                width: 100% !important;
            }
        }

        /* Backdrop: fixed over full screen */
        .jetstream-modal > div:first-child {
            position: fixed !important;
            inset: 0 !important;
            z-index: 0 !important;
        }

        /* Content panel: 80% of the content area, centered, scrollable */
        .jetstream-modal > div:last-child {
            position: relative !important;
            z-index: 1 !important;
            margin: 0 auto !important;
            width: 80% !important;
            max-width: 80% !important;
            max-height: 80vh !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body id="page-top" x-data>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.partials.admin-sidebar')
        <div class="sidebar-resizer" id="sidebarResizer"></div>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" style="padding-top: 70px;">

                <!-- Topbar -->
                @include('layouts.partials.admin-topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid py-4"> 

                    <!-- Page Heading -->
                    @if (isset($header))
                        <div class="mb-4">
                            {{ $header }}
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="fade-in">
                        {{ $slot }}
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="bg-white py-4 border-top">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto text-muted small">
                        <span>&copy; {{ date('Y') }} {{ config('app.name') }} • Developed with <i class="fas fa-heart text-danger"></i></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded-circle shadow-lg" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Premium Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 420px;">
            <div class="modal-content border-0 overflow-hidden" style="border-radius: 1.25rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                
                <!-- Modal Top Danger Banner -->
                <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%); padding: 2rem 2rem 3.5rem; position: relative; overflow: hidden;">
                    <!-- Decorative circles -->
                    <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -30px; left: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.06); border-radius: 50%;"></div>
                    
                    <!-- Close button -->
                    <button type="button" data-dismiss="modal" aria-label="Close"
                        style="position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.15); border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; transition: background 0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                        <i class="fas fa-times" style="font-size: 0.85rem;"></i>
                    </button>

                    <!-- Icon -->
                    <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 1rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-sign-out-alt" style="font-size: 1.6rem; color: white;"></i>
                    </div>
                    <h5 class="text-white font-weight-bold mb-1" style="font-size: 1.2rem; font-family: 'Outfit', sans-serif;" id="logoutModalLabel">Keluar dari Sistem?</h5>
                    <p class="mb-0" style="color: rgba(255,255,255,0.8); font-size: 0.85rem;">Anda akan mengakhiri sesi aktif Anda saat ini.</p>
                </div>

                <!-- User Info Card (floating over banner) -->
                <div style="margin: -1.5rem 1.5rem 0; position: relative; z-index: 1;">
                    <div style="background: white; border-radius: 1rem; padding: 0.85rem 1rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 8px 24px rgba(0,0,0,0.08); border: 1px solid #f1f5f9;">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <img src="{{ Auth::user()->profile_photo_url }}" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;"
                                 alt="{{ Auth::user()->name }}">
                        @else
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-color), #818cf8); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1rem; flex-shrink: 0;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                        <div style="overflow: hidden; flex: 1;">
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Auth::user()->name }}</div>
                            <div style="color: #94a3b8; font-size: 0.72rem;">{{ Auth::user()->email }}</div>
                        </div>
                        <span style="background: {{ Auth::user()->isTeacher ? 'rgba(5,150,105,0.1)' : 'rgba(79,70,229,0.1)' }}; color: {{ Auth::user()->isTeacher ? '#059669' : '#4f46e5' }}; border-radius: 20px; padding: 0.2rem 0.6rem; font-size: 0.65rem; font-weight: 700; white-space: nowrap;">
                            {{ Auth::user()->isTeacher ? 'Guru' : 'Admin' }}
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div style="padding: 1.25rem 1.5rem 0.5rem;">
                    <p style="color: #64748b; font-size: 0.85rem; margin: 0; line-height: 1.6;">
                        Sesi login Anda akan dihapus dari perangkat ini. Data yang belum tersimpan mungkin akan hilang.
                    </p>
                </div>

                <!-- Footer Actions -->
                <div style="padding: 1rem 1.5rem 1.5rem; display: flex; gap: 0.75rem;">
                    <button type="button" data-dismiss="modal"
                        style="flex: 1; padding: 0.75rem 1rem; background: #f1f5f9; border: none; border-radius: 0.75rem; font-weight: 600; color: #475569; font-size: 0.875rem; cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif;"
                        onmouseover="this.style.background='#e2e8f0'; this.style.color='#1e293b';" 
                        onmouseout="this.style.background='#f1f5f9'; this.style.color='#475569';">
                        <i class="fas fa-arrow-left mr-2" style="font-size: 0.75rem;"></i>Tetap Disini
                    </button>
                    <a href="{{ route('logout.confirm') }}"
                        style="flex: 1; padding: 0.75rem 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); border: none; border-radius: 0.75rem; font-weight: 700; color: white; font-size: 0.875rem; cursor: pointer; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s; box-shadow: 0 4px 12px rgba(239,68,68,0.4); font-family: 'Inter', sans-serif;"
                        onmouseover="this.style.background='linear-gradient(135deg, #dc2626, #b91c1c)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 16px rgba(239,68,68,0.5)';"
                        onmouseout="this.style.background='linear-gradient(135deg, #ef4444, #dc2626)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(239,68,68,0.4)';">
                        <i class="fas fa-sign-out-alt" style="font-size: 0.8rem;"></i>Ya, Keluar
                    </a>
                </div>

            </div>
        </div>
    </div>

    @stack('modals')

    <!-- Core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    @livewireScripts
    
    <!-- CSRF Token Auto-Refresh -->
    <script>
        // Refresh CSRF token every 30 minutes to prevent page expiration
        function refreshCSRFToken() {
            fetch('/refresh-csrf', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    // Update meta tag
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                    
                    // Update all CSRF input fields
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.token;
                    });
                    
                    // Update jQuery AJAX setup if exists
                    if (typeof $ !== 'undefined') {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': data.token
                            }
                        });
                    }
                    
                    console.log('✅ CSRF token refreshed');
                }
            })
            .catch(error => {
                console.warn('⚠️ Failed to refresh CSRF token:', error);
            });
        }
        
        // Refresh token every 30 minutes (1800000 ms)
        setInterval(refreshCSRFToken, 1800000);
        
        // Also refresh on page visibility change (when user returns to tab)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                refreshCSRFToken();
            }
        });
        
        // Refresh before form submission if token is old
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.method.toLowerCase() === 'post') {
                const tokenInput = form.querySelector('input[name="_token"]');
                if (tokenInput) {
                    const metaToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    tokenInput.value = metaToken;
                }
            }
        });
    </script>
    
    @stack('scripts')
    
    <script>
    $(document).ready(function() {
        // Toggle the side navigation
        $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
            e.preventDefault();
            $("body").toggleClass("sidebar-toggled");
            $(".sidebar").toggleClass("toggled");
            if ($(".sidebar").hasClass("toggled")) {
                $('.sidebar .collapse').collapse('hide');
            };
        });

        // Close any open menu accordions when window is resized below 768px
        $(window).resize(function() {
            if ($(window).width() < 768) {
                $('.sidebar .collapse').collapse('hide');
            }
        });

        // Scroll to top button appear
        $(document).on('scroll', function() {
            var scrollDistance = $(this).scrollTop();
            if (scrollDistance > 100) {
                $('.scroll-to-top').fadeIn();
            } else {
                $('.scroll-to-top').fadeOut();
            }
        });

        // Smooth scrolling using jQuery easing
        $(document).on('click', 'a.scroll-to-top', function(e) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: ($($anchor.attr('href')).offset().top)
            }, 1000, 'easeInOutExpo');
            e.preventDefault();
        });
    });

    // Sidebar Resizer Logic
    document.addEventListener('DOMContentLoaded', function() {
        const resizer = document.getElementById('sidebarResizer');
        if (!resizer) return;
        
        const root = document.documentElement;
        let isResizing = false;

        // Load saved width from localStorage
        const savedWidth = localStorage.getItem('sidebarWidth');
        if (savedWidth) {
            root.style.setProperty('--sidebar-width', savedWidth + 'px');
        }

        resizer.addEventListener('mousedown', (e) => {
            isResizing = true;
            resizer.classList.add('is-resizing');
            document.body.style.cursor = 'col-resize';
            document.body.style.userSelect = 'none'; // Prevent text selection
            
            // Disable transitions during drag for smooth performance
            root.style.setProperty('--transition-speed', '0s');
        });

        document.addEventListener('mousemove', (e) => {
            if (!isResizing) return;
            
            // Constrain width between 200px and 500px
            let newWidth = e.clientX;
            if (newWidth < 200) newWidth = 200;
            if (newWidth > 500) newWidth = 500;
            
            root.style.setProperty('--sidebar-width', newWidth + 'px');
        });

        document.addEventListener('mouseup', () => {
            if (isResizing) {
                isResizing = false;
                resizer.classList.remove('is-resizing');
                document.body.style.cursor = '';
                document.body.style.userSelect = '';
                
                // Re-enable transition speed
                root.style.setProperty('--transition-speed', '0.2s');
                
                // Save width
                const currentWidth = getComputedStyle(root).getPropertyValue('--sidebar-width').replace('px', '').trim();
                localStorage.setItem('sidebarWidth', currentWidth);
            }
        });
    });
    </script>

</body>
</html>
