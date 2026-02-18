<x-admin-layout>
    <x-slot name="title">Profil Admin</x-slot>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800 font-weight-bold">
                    <i class="fas fa-user-circle text-primary mr-2"></i>Profil Saya
                </h1>
                <p class="text-muted mb-0">Kelola informasi profil dan keamanan akun Anda</p>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Profile Card -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-2xl">
                    <div class="card-body text-center p-4">
                        <!-- Profile Photo -->
                        <div class="mb-4 position-relative d-inline-block">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img src="{{ Auth::user()->profile_photo_url }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="rounded-circle shadow-lg border border-4 border-white"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="rounded-circle shadow-lg d-flex align-items-center justify-content-center text-white font-weight-bold mx-auto"
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #4f46e5 0%, #818cf8 100%); font-size: 3rem;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="position-absolute bg-success rounded-circle border border-3 border-white"
                                 style="width: 24px; height: 24px; bottom: 8px; right: 8px;"></div>
                        </div>

                        <!-- User Info -->
                        <h4 class="font-weight-bold text-gray-800 mb-1">{{ Auth::user()->name }}</h4>
                        <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
                        <span class="badge badge-primary px-3 py-2 rounded-pill" style="font-size: 0.75rem;">
                            <i class="fas fa-shield-alt mr-1"></i>
                            @if(Auth::user()->role === 'superadmin')
                                Super Administrator
                            @else
                                Administrator
                            @endif
                        </span>

                        <hr class="my-4">

                        <!-- Quick Stats -->
                        <div class="row text-center">
                            <div class="col-6 border-right">
                                <div class="text-primary font-weight-bold h4 mb-1">{{ Auth::user()->created_at->diffInDays(now()) }}</div>
                                <div class="text-muted small">Hari Bergabung</div>
                            </div>
                            <div class="col-6">
                                <div class="text-success font-weight-bold h4 mb-1">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="text-muted small">Akun Aktif</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Info Card -->
                <div class="card shadow-sm border-0 rounded-2xl mt-4">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-gray-800 mb-3">
                            <i class="fas fa-info-circle text-primary mr-2"></i>Informasi Akun
                        </h6>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Terdaftar Sejak</small>
                            <div class="font-weight-600 text-gray-700">
                                {{ Auth::user()->created_at->format('d F Y') }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Terakhir Update</small>
                            <div class="font-weight-600 text-gray-700">
                                {{ Auth::user()->updated_at->format('d F Y H:i') }}
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block mb-1">ID Pengguna</small>
                            <div class="font-weight-600 text-gray-700">
                                #{{ str_pad(Auth::user()->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Forms -->
            <div class="col-lg-8">
                <!-- Update Profile Information -->
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <div class="card shadow-sm border-0 rounded-2xl mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="font-weight-bold text-gray-800 mb-0">
                                <i class="fas fa-user-edit text-primary mr-2"></i>Informasi Profil
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            @livewire('profile.update-profile-information-form')
                        </div>
                    </div>
                @endif

                <!-- Update Password -->
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="card shadow-sm border-0 rounded-2xl mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="font-weight-bold text-gray-800 mb-0">
                                <i class="fas fa-lock text-warning mr-2"></i>Ubah Password
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            @livewire('profile.update-password-form')
                        </div>
                    </div>
                @endif

                <!-- Two Factor Authentication -->
                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <div class="card shadow-sm border-0 rounded-2xl mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="font-weight-bold text-gray-800 mb-0">
                                <i class="fas fa-shield-alt text-success mr-2"></i>Autentikasi Dua Faktor
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            @livewire('profile.two-factor-authentication-form')
                        </div>
                    </div>
                @endif

                <!-- Browser Sessions -->
                <div class="card shadow-sm border-0 rounded-2xl mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="font-weight-bold text-gray-800 mb-0">
                            <i class="fas fa-desktop text-info mr-2"></i>Sesi Browser
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>
                </div>

                <!-- Delete Account -->
                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="card shadow-sm border-0 rounded-2xl border-danger mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="font-weight-bold text-danger mb-0">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Hapus Akun
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            @livewire('profile.delete-user-form')
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .rounded-2xl {
            border-radius: 1rem !important;
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .border-4 {
            border-width: 4px !important;
        }
        
        .font-weight-600 {
            font-weight: 600;
        }
    </style>
    @endpush
</x-admin-layout>
