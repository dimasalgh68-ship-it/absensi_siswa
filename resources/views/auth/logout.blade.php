<x-guest-layout>
  <div class="w-full min-h-screen flex items-center justify-center p-0 sm:p-6 lg:p-12">
    <!-- Main Container -->
    <div class="relative w-full sm:max-w-[480px]">
      <!-- Background Decorative Elements (Desktop Only) -->
      <div class="hidden sm:block absolute -top-20 -left-20 w-64 h-64 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
      <div class="hidden sm:block absolute -bottom-20 -right-20 w-64 h-64 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

      <!-- Card Core -->
      <div class="relative min-h-screen sm:min-h-[auto] w-full px-6 py-12 sm:px-10 sm:py-16 bg-white dark:bg-gray-900 sm:rounded-[40px] shadow-none sm:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.08)] border-0 sm:border border-gray-100 dark:border-gray-800 flex flex-col justify-center">

        <!-- Header Section -->
        <div class="flex flex-col items-center justify-center mb-10 text-center select-none">
          <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-red-500 to-pink-400 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
            @if(\App\Models\Setting::logo())
              <div class="relative bg-white dark:bg-gray-800 p-2 rounded-2xl shadow-sm">
                <img src="{{ \App\Models\Setting::logo() }}" alt="Logo" class="w-16 h-16 sm:w-20 sm:h-20 object-contain">
              </div>
            @else
              <div class="relative w-16 h-16 sm:w-20 sm:h-20 bg-red-500 rounded-2xl flex items-center justify-center shadow-lg shadow-red-200 dark:shadow-none transform rotate-3 hover:rotate-0 transition-transform duration-300">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
              </div>
            @endif
          </div>

          <h1 class="mt-8 text-2xl sm:text-3xl font-black tracking-tight text-gray-900 dark:text-white leading-tight">
            Keluar dari Akun?
          </h1>
          <p class="mt-2 text-sm sm:text-base text-gray-500 dark:text-gray-400 font-medium max-w-[280px]">
            Sesi Anda akan diakhiri dari perangkat ini
          </p>
        </div>

        <!-- User Info Box -->
        @auth
        <div class="mb-6 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl px-5 py-4 flex items-center gap-4">
          <div class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-base flex-shrink-0" style="font-family: inherit;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
          </div>
          <div class="overflow-hidden flex-1">
            <p class="font-bold text-gray-800 dark:text-white text-sm truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
          </div>
          <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-lg
            {{ Auth::user()->isTeacher
              ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400'
              : 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' }}">
            {{ Auth::user()->isTeacher ? 'Guru' : 'Admin' }}
          </span>
        </div>
        @endauth

        <!-- Confirmation Message -->
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-8 leading-relaxed">
          Apakah Anda yakin ingin keluar? Anda perlu login kembali untuk mengakses sistem.
        </p>

        <!-- Action Buttons -->
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
          @csrf
          <div class="flex flex-col gap-3">
            <!-- Logout Button -->
            <button
              type="submit"
              id="btn-confirm-logout"
              class="w-full py-5 px-8 bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-500/20 text-white transition-all duration-300 text-center text-lg font-bold shadow-xl shadow-red-500/25 rounded-2xl active:scale-[0.98]"
            >
              Ya, Keluar Sekarang
            </button>

            <!-- Cancel Button -->
            <a href="{{ url()->previous() }}"
              class="w-full py-5 px-8 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-all duration-300 text-center text-base font-bold rounded-2xl active:scale-[0.98] block"
            >
              Batal, Kembali
            </a>
          </div>
        </form>

        <!-- Security Tip -->
        <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-800">
          <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-blue-700 dark:text-blue-300">
              <p class="font-bold mb-1 text-[11px] uppercase tracking-[1.5px] text-blue-500 dark:text-blue-400">Tips Keamanan</p>
              <p class="text-gray-500 dark:text-gray-400 text-xs leading-relaxed">Selalu logout jika menggunakan komputer umum atau perangkat bersama untuk menjaga keamanan akun Anda.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    document.getElementById('logout-form').addEventListener('submit', function() {
      const btn = document.getElementById('btn-confirm-logout');
      btn.innerHTML = `
        <svg class="w-5 h-5 inline-block animate-spin mr-2 -mt-0.5" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Keluar...
      `;
      btn.disabled = true;
      btn.classList.add('opacity-75', 'cursor-not-allowed');
    });
  </script>
</x-guest-layout>
