<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ __('Profile') }}
      </h2>
      <div class="text-sm text-gray-500 dark:text-gray-400">
        <svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
        </svg>
        Kelola informasi profil Anda
      </div>
    </div>
  </x-slot>

  <div>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
      
      <!-- Profile Information -->
      @if (Laravel\Fortify\Features::canUpdateProfileInformation())
        <div class="mb-6">
          @livewire('profile.update-profile-information-form')
        </div>
      @endif

      <!-- Update Password -->
      @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
        <div class="mb-6">
          @livewire('profile.update-password-form')
        </div>
      @endif

      <!-- Two Factor Authentication -->
      @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        <div class="mb-6">
          @livewire('profile.two-factor-authentication-form')
        </div>
      @endif

      <!-- Browser Sessions -->
      <div class="mb-6">
        @livewire('profile.logout-other-browser-sessions-form')
      </div>

      <!-- Delete Account -->
      @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
        <div class="mb-6">
          @livewire('profile.delete-user-form')
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
