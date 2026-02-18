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
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --sidebar-bg: #0f172a;
            --sidebar-item-active: rgba(255, 255, 255, 0.1);
            --content-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --dropdown-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
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
            transition: all 0.3s ease;
            z-index: 1025 !important;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
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
            left: 224px !important; /* Sidebar width */
            transition: left 0.3s ease;
        }
        
        /* Content wrapper - don't overlap sidebar */
        #content-wrapper {
            margin-left: 224px; /* Sidebar width */
            transition: margin-left 0.3s ease;
        }
        
        /* When sidebar is toggled (collapsed) */
        .sidebar-toggled .topbar {
            left: 0 !important;
        }
        
        .sidebar-toggled #content-wrapper {
            margin-left: 0;
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
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body id="page-top" x-data>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.partials.admin-sidebar')
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Log Out?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-muted">Apakah Anda yakin ingin keluar dari sistem?</div>
                <div class="modal-footer border-0">
                    <button class="btn btn-light" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary px-4" href="{{ route('logout.confirm') }}">Log Out</a>
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
    </script>

</body>
</html>
