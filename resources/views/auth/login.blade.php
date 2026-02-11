<x-guest-layout>
  <div class="w-full min-h-screen flex items-center justify-center p-0 sm:p-6 lg:p-12">
    <!-- Main Container -->
    <div class="relative w-full sm:max-w-[480px]">
      <!-- Background Decorative Elements (Desktop Only) -->
      <div class="hidden sm:block absolute -top-20 -left-20 w-64 h-64 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
      <div class="hidden sm:block absolute -bottom-20 -right-20 w-64 h-64 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

      <!-- Card Core -->
      <div class="relative min-h-screen sm:min-h-[auto] w-full px-6 py-12 sm:px-10 sm:py-16 bg-white dark:bg-gray-900 sm:rounded-[40px] shadow-none sm:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.08)] border-0 sm:border border-gray-100 dark:border-gray-800 flex flex-col justify-center">
        
        <!-- Header Section -->
        <div class="flex flex-col items-center justify-center mb-10 text-center select-none">
          <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-400 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
            @if(\App\Models\Setting::logo())
              <div class="relative bg-white dark:bg-gray-800 p-2 rounded-2xl shadow-sm">
                <img src="{{ \App\Models\Setting::logo() }}" alt="Logo" class="w-16 h-16 sm:w-20 sm:h-20 object-contain">
              </div>
            @else
              <div class="relative w-16 h-16 sm:w-20 sm:h-20 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none transform rotate-3 hover:rotate-0 transition-transform duration-300">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002-2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
            @endif
          </div>

          <h1 class="mt-8 text-2xl sm:text-3xl font-black tracking-tight text-gray-900 dark:text-white leading-tight">
            {{ \App\Models\Setting::appName() }}
          </h1>
          <p class="mt-2 text-sm sm:text-base text-gray-500 dark:text-gray-400 font-medium max-w-[280px]">
            Silahkan login untuk mengakses sistem absensi
          </p>
        </div>

        <x-validation-errors class="mb-6" />
        @session('status')
          <div class="mb-6 text-sm font-medium text-green-600 text-center bg-green-50 dark:bg-green-900/20 py-3 rounded-xl border border-green-100 dark:border-green-800">
            {{ $value }}
          </div>
        @endsession

        <!-- Form Section -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
          @csrf

          <div class="group">
            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">
              E-mail / NISN / No. HP
            </label>
            <div class="relative">
              <input
                type="text"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="Email / NISN / No. HP"
                class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-4 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white placeholder:text-gray-300 shadow-sm"
              />
            </div>
          </div>

          <div class="group">
            <div class="flex justify-between items-center ml-1 mb-2">
              <label class="text-[11px] font-bold text-gray-400 uppercase tracking-[2px] group-focus-within:text-blue-500 transition-colors">
                Password
              </label>
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-blue-500 hover:text-blue-700 transition-colors">
                  LUPA PASSWORD?
                </a>
              @endif
            </div>
            <input
              type="password"
              name="password"
              required
              placeholder="••••••••"
              class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-4 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white placeholder:text-gray-300 shadow-sm"
            />
          </div>

          <div class="flex items-center justify-between ml-1">
            <label class="flex items-center cursor-pointer group/rem">
              <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500/20 dark:bg-gray-800 dark:border-gray-700 transition-all cursor-pointer">
              <span class="ml-2 text-xs font-bold text-gray-400 uppercase tracking-[1px] group-hover/rem:text-gray-600 dark:group-hover/rem:text-gray-300 transition-colors">
                Ingat Saya
              </span>
            </label>
          </div>

          <div class="pt-4">
            <button
              type="submit"
              class="w-full py-5 px-8 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 text-white transition-all duration-300 text-center text-lg font-bold shadow-xl shadow-blue-500/25 rounded-2xl active:scale-[0.98]"
            >
              Masuk Sekarang
            </button>
          </div>
        </form>

        <!-- Footer -->
        @if (Route::has('register'))
          <div class="mt-12 text-center pb-6 sm:pb-0">
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">
              Belum punya akun? 
              <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 ml-1 font-bold">Daftar di sini</a>
            </p>
          </div>
        @endif
      </div>
    </div>
  </div>
</x-guest-layout>
