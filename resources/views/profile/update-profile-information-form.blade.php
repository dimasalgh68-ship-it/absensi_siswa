<x-form-section submit="updateProfileInformation">
  <x-slot name="title">
    Informasi Profil
  </x-slot>

  <x-slot name="description">
    Perbarui informasi profil dan data pribadi Anda.
  </x-slot>

  <x-slot name="form">
    <!-- Profile Photo -->
    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
      <div x-data="{ photoName: null, photoPreview: null, photoError: null }" class="col-span-6 sm:col-span-4">
        <!-- Profile Photo File Input -->
        <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo" accept="image/jpeg,image/png,image/jpg"
          x-on:change="
                                    const file = $refs.photo.files[0];
                                    photoError = null;
                                    
                                    // Validate file size (2MB = 2097152 bytes)
                                    if (file && file.size > 2097152) {
                                        photoError = 'Ukuran foto maksimal 2MB. Ukuran file Anda: ' + (file.size / 1048576).toFixed(2) + 'MB';
                                        $refs.photo.value = '';
                                        photoPreview = null;
                                        photoName = null;
                                        return;
                                    }
                                    
                                    // Validate file type
                                    if (file && !['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                                        photoError = 'Format file harus JPG atau PNG';
                                        $refs.photo.value = '';
                                        photoPreview = null;
                                        photoName = null;
                                        return;
                                    }
                                    
                                    if (file) {
                                        photoName = file.name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL(file);
                                    }
                            " />

        <x-label for="photo" value="Foto Profil" />

        <!-- Current Profile Photo -->
        <div class="mt-2" x-show="! photoPreview">
          <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}"
            class="h-24 w-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700 shadow-lg">
        </div>

        <!-- New Profile Photo Preview -->
        <div class="mt-2" x-show="photoPreview" style="display: none;">
          <span class="block h-24 w-24 rounded-full bg-cover bg-center bg-no-repeat border-4 border-blue-500 shadow-lg"
            x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
          </span>
        </div>

        <!-- Client-side Error Message -->
        <div x-show="photoError" class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
          <p class="text-sm text-red-800 dark:text-red-200" x-text="photoError"></p>
        </div>

        <div class="mt-3 flex gap-2">
          <x-secondary-button type="button" x-on:click.prevent="$refs.photo.click()">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Pilih Foto Baru
          </x-secondary-button>

          @if ($this->user->profile_photo_path)
            <x-secondary-button type="button" wire:click="deleteProfilePhoto">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              Hapus Foto
            </x-secondary-button>
          @endif
        </div>

        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
          <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
          Format: JPG, PNG. Maksimal 2MB. Rekomendasi: 400x400px
        </p>

        <x-input-error for="photo" class="mt-2" />
      </div>
    @endif

    <!-- Divider -->
    <div class="col-span-6 sm:col-span-4">
      <hr class="border-gray-200 dark:border-gray-700">
      <h3 class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Data Pribadi</h3>
    </div>

    <!-- Name -->
    <div class="col-span-6 sm:col-span-4">
      <x-label for="name" value="Nama Lengkap" />
      <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required
        autocomplete="name" placeholder="Masukkan nama lengkap" />
      <x-input-error for="name" class="mt-2" />
    </div>

    <!-- NISN -->
    <div class="col-span-6 sm:col-span-4">
      <x-label for="nisn" value="NISN" />
      <x-input id="nisn" type="text" class="mt-1 block w-full" wire:model="state.nisn" required
        autocomplete="nisn" placeholder="Nomor Induk Siswa Nasional" />
      <x-input-error for="nisn" class="mt-2" />
    </div>

    <div class="col-span-6 flex flex-col sm:flex-row gap-3 sm:col-span-4">
      <!-- Birth Place -->
      <div class="w-full">
        <x-label for="birth_place" value="Tempat Lahir" />
        <x-input id="birth_place" type="text" class="mt-1 block w-full" wire:model="state.birth_place" placeholder="Kota kelahiran" />
        <x-input-error for="birth_place" class="mt-2" />
      </div>

      <!-- Birth Date -->
      <div class="w-full">
        <x-label for="birth_date" value="Tanggal Lahir" />
        <x-input id="birth_date" type="date" class="mt-1 block w-full" value="{{ $state['birth_date'] }}"
          wire:model="state.birth_date" />
        <x-input-error for="birth_date" class="mt-2" />
      </div>
    </div>

    <div class="col-span-6 flex flex-col sm:flex-row gap-3 sm:col-span-4">
      <!-- Phone Number -->
      <div class="w-full">
        <x-label for="phone" value="Nomor Telepon" />
        <x-input id="phone" type="text" class="mt-1 block w-full" wire:model="state.phone" required placeholder="08xxxxxxxxxx" />
        <x-input-error for="phone" class="mt-2" />
      </div>

      <!-- Gender -->
      <div class="w-full">
        <x-label for="gender" value="Jenis Kelamin" />
        <x-select id="gender" class="mt-1 block w-full" wire:model="state.gender" required>
          <option value="">Pilih</option>
          <option value="male" {{ ($state['gender'] ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
          <option value="female" {{ ($state['gender'] ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
        </x-select>
        <x-input-error for="gender" class="mt-2" />
      </div>
    </div>

    <!-- Address -->
    <div class="col-span-6 sm:col-span-4">
      <x-label for="address" value="Alamat Lengkap" />
      <x-textarea id="address" class="mt-1 block w-full" wire:model="state.address" required rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan" />
      <x-input-error for="address" class="mt-2" />
    </div>

    <!-- City -->
    <div class="col-span-6 sm:col-span-4">
      <x-label for="city" value="Kota/Kabupaten" />
      <x-input id="city" type="text" class="mt-1 block w-full" wire:model="state.city" required placeholder="Nama kota/kabupaten" />
      <x-input-error for="city" class="mt-2" />
    </div>

    <!-- Divider -->
    <div class="col-span-6 sm:col-span-4">
      <hr class="border-gray-200 dark:border-gray-700">
      <h3 class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Informasi Akun</h3>
    </div>

    <!-- Email -->
    <div class="col-span-6 sm:col-span-4">
      <x-label for="email" value="Email" />
      <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required
        autocomplete="username" placeholder="email@example.com" />
      <x-input-error for="email" class="mt-2" />

      @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) &&
              !$this->user->hasVerifiedEmail())
        <div class="mt-2 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
          <p class="text-sm text-yellow-800 dark:text-yellow-200">
            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            Email Anda belum diverifikasi.
          </p>
          <button type="button"
            class="mt-2 text-sm text-yellow-700 dark:text-yellow-300 underline hover:text-yellow-900 dark:hover:text-yellow-100 font-semibold"
            wire:click.prevent="sendEmailVerification">
            Kirim ulang email verifikasi
          </button>
        </div>

        @if ($this->verificationLinkSent)
          <div class="mt-2 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-sm text-green-800 dark:text-green-200">
              <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
              Link verifikasi baru telah dikirim ke email Anda.
            </p>
          </div>
        @endif
      @endif
    </div>

    <!-- Divider -->
    <div class="col-span-6 sm:col-span-4">
      <hr class="border-gray-200 dark:border-gray-700">
      <h3 class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Informasi Akademik</h3>
    </div>

    <!-- Division -->
    <div class="col-span-6 sm:col-span-4">
      <x-label for="division" value="Jurusan" />
      <x-select id="division" class="mt-1 block w-full" wire:model="state.division_id">
        <option value="">Pilih Jurusan</option>
        @foreach (App\Models\Division::all() as $division)
          <option value="{{ $division->id }}" {{ $division->id == ($state['division_id'] ?? null) ? 'selected' : '' }}>
            {{ $division->name }}
          </option>
        @endforeach
      </x-select>
      <x-input-error for="division_id" class="mt-2" />
    </div>

    <!-- Education / Kelas -->
    <div class="col-span-6 sm:col-span-4">
      <x-label for="education" value="Kelas" />
      <x-select id="education" class="mt-1 block w-full" wire:model="state.education_id">
        <option value="">Pilih Kelas</option>
        @foreach (App\Models\Education::all() as $education)
          <option value="{{ $education->id }}" {{ $education->id == ($state['education_id'] ?? null) ? 'selected' : '' }}>
            {{ $education->name }}
          </option>
        @endforeach
      </x-select>
      <x-input-error for="education_id" class="mt-2" />
    </div>

    <!-- Job title / Status -->
    <div class="col-span-6 sm:col-span-4">
      <x-label for="job_title" value="Status" />
      <x-select id="job_title" class="mt-1 block w-full" wire:model="state.job_title_id">
        <option value="">Pilih Status</option>
        @foreach (App\Models\JobTitle::all() as $job_title)
          <option value="{{ $job_title->id }}" {{ $job_title->id == ($state['job_title_id'] ?? null) ? 'selected' : '' }}>
            {{ $job_title->name }}
          </option>
        @endforeach
      </x-select>
      <x-input-error for="job_title_id" class="mt-2" />
      <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Status kepegawaian atau kesiswaan</p>
    </div>

    <!-- Shift -->
    <div class="col-span-6 sm:col-span-4">
      <x-label for="shift" value="Jadwal Shift" />
      <x-select id="shift" class="mt-1 block w-full" wire:model="state.shift_id">
        <option value="">Pilih Shift</option>
        @foreach (App\Models\Shift::all() as $shift)
          <option value="{{ $shift->id }}" {{ $shift->id == ($state['shift_id'] ?? null) ? 'selected' : '' }}>
            {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
          </option>
        @endforeach
      </x-select>
      <x-input-error for="shift_id" class="mt-2" />
      <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
        <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
        Jadwal ini menentukan waktu absensi Anda
      </p>
    </div>
  </x-slot>

  <x-slot name="actions">
    <x-action-message class="me-3" on="saved">
      <svg class="w-4 h-4 inline mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
      Tersimpan
    </x-action-message>

    <x-button wire:loading.attr="disabled" wire:target="photo">
      <svg wire:loading wire:target="updateProfileInformation" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <span wire:loading.remove wire:target="updateProfileInformation">Simpan Perubahan</span>
      <span wire:loading wire:target="updateProfileInformation">Menyimpan...</span>
    </x-button>
  </x-slot>
</x-form-section>
