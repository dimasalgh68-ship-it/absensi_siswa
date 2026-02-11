<x-app-layout>
    {{-- Reuse styles from home or global --}}
    @push('styles')
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    </style>
    @endpush

    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 pb-20 font-sans">
        
        {{-- Hero Background --}}
        <div class="absolute top-0 left-0 right-0 h-64 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-b-[2.5rem] shadow-2xl shadow-blue-500/20 overflow-hidden">
             <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-soft-light"></div>
            <div class="absolute top-[-20%] right-[-10%] w-80 h-80 bg-purple-500/30 rounded-full blur-3xl mix-blend-overlay"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-60 h-60 bg-cyan-400/20 rounded-full blur-3xl mix-blend-overlay"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            {{-- Header --}}
            <div class="mb-8 text-center relative z-10 animate-slide-up">
                <span class="inline-block p-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-lg mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
                <h2 class="text-3xl font-bold text-white mb-1">Scan Wajah</h2>
                <p class="text-blue-100 text-sm">Posisikan wajah Anda di depan kamera untuk absen</p>
            </div>

            {{-- Scanner Section --}}
            <div class="animate-slide-up" style="animation-delay: 0.1s;">
                <div class="glass-card rounded-3xl overflow-hidden p-1 max-w-5xl mx-auto ring-1 ring-white/40 dark:ring-gray-700 shadow-2xl">
                    <div class="rounded-[1.25rem] overflow-hidden bg-gray-100 dark:bg-gray-900 relative">
                        @livewire('scan-component')
                        
                        {{-- Overlay corners for visual guide --}}
                        <div class="absolute inset-0 pointer-events-none border-[3px] border-white/20 dark:border-white/10 rounded-[1.25rem] m-4 z-10"></div>
                    </div>
                </div>

                {{-- Instructions or Helpers --}}
                <div class="mt-8 max-w-sm mx-auto text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 dark:bg-gray-800/60 rounded-full backdrop-blur-sm border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-4 h-4 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                            Pastikan izin kamera aktif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
