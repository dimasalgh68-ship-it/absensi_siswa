<div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 dark:bg-gray-900 sm:justify-center sm:pt-0" style="background-image: url('/assets/login-bg.jpg'); background-size: cover; background-position: center;">
  <div>
    {{ $logo }}
  </div>

  <div class="mt-6 w-full overflow-hidden bg-transparent px-5 py-4 shadow-2xl dark:bg-gray-800 sm:max-w-xl sm:rounded-lg">
    {{ $slot }}
  </div>
</div>
