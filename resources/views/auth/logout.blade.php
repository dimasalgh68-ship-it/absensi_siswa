<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <div class="text-center mb-6">
      <!-- Icon -->
      <div class="mx-auto w-20 h-20 bg-gradient-to-br from-red-500 to-pink-500 rounded-full flex items-center justify-center mb-4 shadow-lg">
        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
      </div>

      <!-- Title -->
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
        {{ __('Konfirmasi Keluar') }}
      </h2>

      <!-- Message -->
      <p class="text-gray-600 dark:text-gray-400">
        {{ __('Apakah Anda yakin ingin keluar dari aplikasi?') }}
      </p>
      <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
        {{ __('Anda perlu login kembali untuk mengakses aplikasi.') }}
      </p>
    </div>

    <form method="POST" action="{{ route('logout') }}">
      @csrf

      <div class="flex flex-col sm:flex-row gap-3 mt-6">
        <!-- Cancel Button -->
        <a href="{{ url()->previous() }}" class="flex-1">
          <button type="button" class="w-full px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            {{ __('Batal') }}
          </button>
        </a>

        <!-- Logout Button -->
        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg font-semibold hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          {{ __('Ya, Keluar') }}
        </button>
      </div>
    </form>

    <!-- Additional Info -->
    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
      <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="text-sm text-blue-700 dark:text-blue-300">
          <p class="font-semibold mb-1">{{ __('Tips Keamanan') }}</p>
          <p>{{ __('Pastikan untuk logout jika menggunakan komputer umum atau perangkat bersama.') }}</p>
        </div>
      </div>
    </div>
  </x-authentication-card>
</x-guest-layout>
