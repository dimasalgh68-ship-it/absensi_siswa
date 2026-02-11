<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang - {{ config('app.name', 'Absensi Siswa') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .gradient-text {
            background: linear-gradient(135deg, #60A5FA 0%, #A78BFA 50%, #F472B6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float 10s infinite ease-in-out;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0, 0) scale(1); }
        }
    </style>
</head>
<body class="bg-slate-900 text-white antialiased overflow-x-hidden selection:bg-indigo-500 selection:text-white">
    <!-- Background Effects -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="blob bg-blue-600 w-96 h-96 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="blob bg-purple-600 w-[30rem] h-[30rem] rounded-full bottom-0 right-0 translate-x-1/3 translate-y-1/3 animation-delay-2000"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 glass" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="text-2xl font-bold tracking-tighter">
                        <span class="text-blue-400">Absensi</span><span class="text-white">Siswa</span>
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="{{ url('/') }}" class="hover:text-blue-400 transition-colors px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="{{ route('about') }}" class="text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Tentang</a>
                        @auth
                            <a href="{{ url('/home') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-6 py-2 rounded-full text-sm font-medium transition-all hover:scale-105 backdrop-blur-sm">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-6 py-2 rounded-full text-sm font-medium transition-all hover:scale-105 backdrop-blur-sm">Login</a>
                        @endauth
                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    @auth
                        <a href="{{ url('/home') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="relative min-h-screen pt-32 pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 tracking-tight">
                    Tentang <span class="gradient-text">Kami</span>
                </h1>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Membangun masa depan pendidikan dengan teknologi presensi yang cerdas dan terintegrasi.
                </p>
            </div>

            <div class="glass p-8 md:p-12 rounded-3xl mb-12">
                <h2 class="text-2xl font-bold mb-6">Misi Kami</h2>
                <p class="text-gray-300 leading-relaxed mb-6">
                    Absensi Siswa hadir untuk menjawab tantangan administrasi akademik di era digital. Kami berkomitmen untuk menyediakan platform pencatatan kehadiran yang tidak hanya akurat, tetapi juga mudah digunakan oleh guru dan siswa.
                </p>
                <p class="text-gray-300 leading-relaxed">
                    Dengan memanfaatkan teknologi terkini, kami menghilangkan kerumitan presensi manual, mengurangi penggunaan kertas, dan memberikan transparansi data secara real-time.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="glass p-8 rounded-2xl">
                    <h3 class="text-xl font-bold mb-4 text-blue-400">Visi</h3>
                    <p class="text-gray-400">Menjadi standar baru dalam sistem manajemen kehadiran akademik yang efisien dan terpercaya.</p>
                </div>
                <div class="glass p-8 rounded-2xl">
                    <h3 class="text-xl font-bold mb-4 text-purple-400">Nilai</h3>
                    <p class="text-gray-400">Inovasi, Integritas, dan Kemudahan Pengguna adalah inti dari setiap fitur yang kami kembangkan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-white/10 bg-black/20 backdrop-blur-lg relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <p class="text-gray-500 text-sm">© {{ date('Y') }} Absensi Siswa. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
