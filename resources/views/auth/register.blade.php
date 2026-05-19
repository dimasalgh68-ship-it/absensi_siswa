<x-guest-layout>
  <div class="w-full min-h-screen flex items-center justify-center p-0 sm:p-6 lg:p-12">
    <!-- Main Container -->
    <div class="relative w-full sm:max-w-[550px] my-auto">
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
            Buat akun untuk mulai menggunakan sistem absensi
          </p>
        </div>

        <x-validation-errors class="mb-6" />

        <!-- Form Section -->
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
          @csrf

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="group">
              <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">Nama Lengkap</label>
              <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="Nama Anda"
                class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-3.5 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white placeholder:text-gray-300 shadow-sm" />
            </div>

            <!-- NISN -->
            <div class="group">
              <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">NISN</label>
              <input id="nisn" type="text" name="nisn" value="{{ old('nisn') }}" autocomplete="nisn" required
                placeholder="Nomor Induk Siswa"
                class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-3.5 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white placeholder:text-gray-300 shadow-sm" />
            </div>

            <!-- Email -->
            <div class="group">
              <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">Email</label>
              <input id="email" type="email" name="email" value="{{ old('email') }}" required
                placeholder="email@sekolah.com"
                class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-3.5 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white placeholder:text-gray-300 shadow-sm" />
            </div>

            <!-- Phone -->
            <div class="group">
              <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">No. Telepon</label>
              <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                placeholder="08xxxxxxxxxx"
                class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-3.5 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white placeholder:text-gray-300 shadow-sm" />
            </div>

            <!-- Gender -->
            <div class="group">
              <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">Jenis Kelamin</label>
              <select id="gender" name="gender" required
                class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-3.5 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white shadow-sm appearance-none">
                <option value="" disabled selected>Pilih Gender</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
              </select>
            </div>

            <!-- Kelas -->
            <div class="group">
              <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">Kelas</label>
              <select id="education_id" name="education_id" required
                class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-3.5 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white shadow-sm appearance-none">
                <option value="" disabled selected>Pilih Kelas</option>
                @foreach(\App\Models\Education::all() as $education)
                  <option value="{{ $education->id }}" {{ old('education_id') == $education->id ? 'selected' : '' }}>
                    {{ $education->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- City -->
            <div class="group">
              <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">Kota</label>
              <input id="city" type="text" name="city" value="{{ old('city') }}" required
                placeholder="Contoh: Jakarta"
                class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-3.5 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white placeholder:text-gray-300 shadow-sm" />
            </div>
          </div>

          <!-- Address -->
          <div class="group">
            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">Alamat Lengkap</label>
            <textarea id="address" name="address" required rows="2"
              placeholder="Masukkan alamat lengkap anda"
              class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-3.5 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white placeholder:text-gray-300 shadow-sm resize-none">{{ old('address') }}</textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Password -->
            <div class="group">
              <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">Password</label>
              <input id="password" type="password" name="password" required autocomplete="new-password"
                placeholder="••••••••"
                class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-3.5 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white placeholder:text-gray-300 shadow-sm" />
            </div>

            <!-- Confirm Password -->
            <div class="group">
              <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[2px] ml-1 mb-2 group-focus-within:text-blue-500 transition-colors">Konfirmasi Password</label>
              <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                placeholder="••••••••"
                class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-800 px-5 py-3.5 rounded-2xl text-base outline-none focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-900 transition-all duration-300 text-gray-700 dark:text-white placeholder:text-gray-300 shadow-sm" />
            </div>
          </div>

          @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mt-4">
              <label for="terms" class="flex items-start cursor-pointer group">
                <div class="mt-1">
                  <x-checkbox name="terms" id="terms" required class="w-5 h-5 rounded-md border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                </div>
                <div class="ms-3 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                  {!! __('Saya setuju dengan :terms_of_service dan :privacy_policy', [
                      'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline font-bold text-blue-600 hover:text-blue-800">Syarat Layanan</a>',
                      'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline font-bold text-blue-600 hover:text-blue-800">Kebijakan Privasi</a>',
                  ]) !!}
                </div>
              </label>
            </div>
          @endif

          <div class="pt-6">
            <button type="submit"
              class="w-full py-5 px-8 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 text-white transition-all duration-300 text-center text-lg font-bold shadow-xl shadow-blue-500/25 rounded-2xl active:scale-[0.98]">
              Daftar Sekarang
            </button>
          </div>
        </form>

        <!-- Footer -->
        <div class="mt-12 text-center pb-6 sm:pb-0">
          <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 ml-1 font-bold">Masuk di sini</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</x-guest-layout>
