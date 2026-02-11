<div class="w-full bg-white dark:bg-gray-900 rounded-[1.25rem] p-4 md:p-6">
  @php
    use Illuminate\Support\Carbon;
  @endphp

  <div class="flex flex-col lg:flex-row gap-8">
    {{-- Left Column: Face Scanner --}}
    @if (!$isAbsence)
      <div class="w-full lg:w-1/2 flex flex-col gap-4">
        {{-- Schedule Selection --}}
        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
          <label for="schedule" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Jadwal</label>
          <x-select id="schedule" class="block w-full rounded-lg" wire:model="schedule_id" disabled="{{ !is_null($attendance) }}">
            <option value="">{{ __('Pilih Jadwal') }}</option>
            @foreach ($schedules as $schedule)
              <option value="{{ $schedule->id }}" {{ $schedule->id == $schedule_id ? 'selected' : '' }}>
                {{ $schedule->name . ' | ' . $schedule->start_time . ' - ' . $schedule->end_time }}
              </option>
            @endforeach
          </x-select>
        </div>

        {{-- Face Registration Warning --}}
        @if (!$hasFaceRegistration)
          <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
              </svg>
              <div>
                <p class="font-semibold text-yellow-800 dark:text-yellow-200">Wajah Belum Terdaftar</p>
                <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                  Anda harus mendaftarkan wajah terlebih dahulu.
                </p>
                <a href="{{ route('face-registration.index') }}" 
                   class="inline-block mt-2 text-sm font-medium text-yellow-800 dark:text-yellow-200 underline hover:no-underline">
                  Daftar Sekarang →
                </a>
              </div>
            </div>
          </div>
        @endif

        {{-- Camera Preview --}}
        <div class="relative bg-gray-900 rounded-2xl overflow-hidden" style="aspect-ratio: 4/3;">
          <video id="camera" autoplay playsinline muted class="w-full h-full object-cover"></video>
          <canvas id="canvas" class="hidden"></canvas>
          
          {{-- Camera Loading --}}
          <div id="cameraLoading" class="absolute inset-0 bg-gray-800 flex flex-col items-center justify-center">
            <svg class="animate-spin h-12 w-12 text-blue-500 mb-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-white text-sm">Memuat kamera...</p>
          </div>
          
          {{-- Camera Error Fallback --}}
          <div id="cameraError" class="hidden absolute inset-0 bg-gray-800 flex flex-col items-center justify-center p-6 text-center">
            <svg class="w-16 h-16 text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="text-white font-bold text-lg mb-2">Kamera Tidak Dapat Diakses</h3>
            <p class="text-gray-300 text-sm mb-4">
              Kamera tidak tersedia atau izin ditolak.
            </p>
            <div class="bg-blue-900/50 rounded-lg p-4 mb-4 text-left text-sm text-gray-200">
              <p class="font-semibold mb-2">Solusi:</p>
              <ol class="list-decimal list-inside space-y-1">
                <li>Pastikan browser memiliki izin akses kamera</li>
                <li>Gunakan HTTPS untuk keamanan</li>
                <li>Atau upload foto manual di bawah</li>
              </ol>
            </div>
            <label for="manualPhoto" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg inline-block transition">
              📷 Upload Foto Manual
            </label>
            <input type="file" id="manualPhoto" accept="image/*" capture="user" class="hidden">
          </div>
          
          {{-- GPS Status Indicator --}}
          <div id="gpsStatus" class="absolute top-4 right-4 bg-black/50 text-white px-3 py-2 rounded-lg text-sm flex items-center gap-2 z-20">
            <span id="gpsIcon">📍</span>
            <span id="gpsText">Mencari lokasi...</span>
          </div>

          {{-- Liveness Detection Challenge --}}
          <div id="livenessChallenge" class="hidden absolute inset-0 bg-black/80 flex flex-col items-center justify-center z-30 p-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full text-center">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                Verifikasi Liveness
              </h3>
              
              <div class="mb-6">
                <div class="relative pt-1">
                  <div class="flex mb-2 items-center justify-between">
                    <div>
                      <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                        Progress
                      </span>
                    </div>
                    <div class="text-right">
                      <span id="livenessProgress" class="text-xs font-semibold inline-block text-blue-600">
                        0%
                      </span>
                    </div>
                  </div>
                  <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                    <div id="livenessProgressBar" style="width:0%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 transition-all duration-300"></div>
                  </div>
                </div>
              </div>
              
              <div id="livenessInstructions" class="space-y-3 mb-4">
                <div id="blinkInstruction" class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                  <span class="text-sm text-gray-700 dark:text-gray-300">Kedipkan mata 2x</span>
                  <span id="blinkStatus" class="text-2xl">⏳</span>
                </div>
                <div id="headMoveInstruction" class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                  <span class="text-sm text-gray-700 dark:text-gray-300">Gerakkan kepala</span>
                  <span id="headMoveStatus" class="text-2xl">⏳</span>
                </div>
              </div>
              
              <div id="livenessTimer" class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Waktu tersisa: <span id="timeRemaining" class="font-bold">10</span> detik
              </div>
              
              <button id="cancelLiveness" class="text-sm text-red-600 hover:text-red-700 underline">
                Batal
              </button>
            </div>
          </div>

          {{-- Face Detection Overlay --}}
          <div id="faceOverlay" class="absolute inset-0 pointer-events-none border-[3px] border-white/30 rounded-2xl m-4"></div>
        </div>

        {{-- Scan Button --}}
        @if ($hasFaceRegistration)
          <button 
            id="scanBtn"
            type="button"
            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-4 px-6 rounded-xl transition shadow-lg hover:shadow-xl"
            @if($attendance && $attendance->time_out) disabled @endif
            @if($attendance && $attendance->time_in && !$attendance->time_out && !$canClockOut) disabled @endif
          >
            <span class="flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              @if(!$attendance)
                Scan Wajah untuk Absen Masuk
              @elseif(!$attendance->time_out)
                @if($canClockOut)
                  Scan Wajah untuk Absen Keluar
                @else
                  Belum Waktunya Absen Keluar
                @endif
              @else
                Absensi Selesai
              @endif
            </span>
          </button>
          
          {{-- Countdown Info --}}
          @if($attendance && $attendance->time_in && !$attendance->time_out && !$canClockOut && $clockOutAvailableAt)
            <div class="mt-3 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl">
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                  <p class="font-semibold text-yellow-800 dark:text-yellow-200 mb-1">Waktu Absen Keluar</p>
                  <p class="text-sm text-yellow-700 dark:text-yellow-300">
                    Anda dapat absen keluar mulai pukul <strong>{{ $clockOutAvailableAt }}</strong>
                  </p>
                  <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                    <span id="countdown-text">Tersisa: <strong id="countdown-timer">{{ floor($minutesUntilClockOut / 60) }}j {{ $minutesUntilClockOut % 60 }}m</strong></span>
                  </p>
                </div>
              </div>
            </div>
          @endif
        @endif
      </div>
    @endif

    {{-- Right Column: Info & Status --}}
    <div class="w-full {{ !$isAbsence ? 'lg:w-1/2' : 'lg:w-full' }} space-y-6">
      
      {{-- Status Messages --}}
      <div id="messageContainer">
        {{-- Error Message --}}
        <div id="errorMessage" class="hidden bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
          <p class="text-red-800 dark:text-red-200 font-semibold" id="errorText"></p>
        </div>

        {{-- Success Message --}}
        <div id="successMessage" class="hidden bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
          <p class="text-green-800 dark:text-green-200 font-semibold" id="successText"></p>
          <div id="successDetails" class="mt-2 text-sm text-green-700 dark:text-green-300"></div>
        </div>

        {{-- Existing Success --}}
        @if($successMsg)
          <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
            <p class="text-green-800 dark:text-green-200 font-semibold">{{ $successMsg }}</p>
          </div>
        @endif
      </div>

      {{-- Attendance Status --}}
      @if ($attendance)
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-100 dark:border-blue-800">
          <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Status Absensi Hari Ini
          </h3>
          
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4">
              <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Absen Masuk</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i') : '-' }}
              </p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4">
              <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Absen Keluar</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $attendance->time_out ? Carbon::parse($attendance->time_out)->format('H:i') : '-' }}
              </p>
            </div>
          </div>

          @if($attendance->face_similarity_score)
            <div class="mt-4 pt-4 border-t border-blue-200 dark:border-blue-700">
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Similarity Score: <span class="font-bold text-blue-600 dark:text-blue-400">{{ $attendance->face_similarity_score }}%</span>
              </p>
            </div>
          @endif
        </div>
      @endif

      {{-- Instructions --}}
      <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700">
        <h4 class="font-bold text-gray-900 dark:text-white mb-3">Panduan Absensi:</h4>
        <ol class="list-decimal list-inside text-sm text-gray-700 dark:text-gray-300 space-y-2">
          <li>Pastikan GPS aktif dan lokasi terdeteksi</li>
          <li>Posisikan wajah di tengah kamera</li>
          <li>Pastikan pencahayaan cukup terang</li>
          <li><strong>Gunakan kamera langsung, bukan foto dari layar/cetakan</strong></li>
          <li>Tekan tombol "Scan Wajah"</li>
          <li><strong>Ikuti instruksi liveness: kedipkan mata 2x dan gerakkan kepala</strong></li>
          <li>Tunggu proses verifikasi selesai</li>
        </ol>
        
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
          <p class="text-xs text-gray-600 dark:text-gray-400 flex items-start gap-2">
            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Sistem dilengkapi dengan deteksi anti-spoofing dan liveness detection (kedipan mata & gerakan kepala) untuk mencegah penggunaan foto palsu.</span>
          </p>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <!-- Face API Library -->
  <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.12/dist/face-api.min.js"></script>
  <script src="{{ asset('assets/js/liveness-detection.js') }}"></script>
  <script>
    const video = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    const scanBtn = document.getElementById('scanBtn');
    const gpsStatus = document.getElementById('gpsStatus');
    const gpsIcon = document.getElementById('gpsIcon');
    const gpsText = document.getElementById('gpsText');
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    const successMessage = document.getElementById('successMessage');
    const successText = document.getElementById('successText');
    const successDetails = document.getElementById('successDetails');
    const cameraError = document.getElementById('cameraError');
    const cameraLoading = document.getElementById('cameraLoading');
    const manualPhoto = document.getElementById('manualPhoto');
    const faceOverlay = document.getElementById('faceOverlay');

    let currentPosition = null;
    let stream = null;
    let isProcessing = false;
    let cameraAvailable = false;
    let manualPhotoData = null;
    let livenessDetector = null;
    let faceApiLoaded = false;
    let detectionInterval = null;

    // Start camera
    async function startCamera() {
      try {
        console.log('Starting camera...');
        
        // Request camera permission
        const constraints = { 
          video: { 
            facingMode: 'user',
            width: { ideal: 1280 },
            height: { ideal: 720 }
          } 
        };
        
        stream = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject = stream;
        
        // Wait for video to be ready
        video.onloadedmetadata = () => {
          video.play();
          cameraAvailable = true;
          
          // Hide loading, show video
          if (cameraLoading) cameraLoading.classList.add('hidden');
          video.style.display = 'block';
          
          console.log('Camera started successfully');
          
          // Load face-api models
          loadFaceApi();
        };
        
      } catch (err) {
        console.error('Camera error:', err);
        cameraAvailable = false;
        
        // Hide loading
        if (cameraLoading) cameraLoading.classList.add('hidden');
        
        // Show error fallback
        if (cameraError) {
          video.style.display = 'none';
          cameraError.classList.remove('hidden');
        }
        
        let errorMsg = 'Kamera tidak dapat diakses. ';
        
        if (err.name === 'NotAllowedError') {
          errorMsg += 'Izin kamera ditolak. Silakan izinkan akses kamera di browser.';
        } else if (err.name === 'NotFoundError') {
          errorMsg += 'Kamera tidak ditemukan.';
        } else if (err.name === 'NotReadableError') {
          errorMsg += 'Kamera sedang digunakan aplikasi lain.';
        } else if (err.name === 'NotSupportedError') {
          errorMsg += 'Browser tidak mendukung akses kamera melalui HTTP. Gunakan HTTPS.';
        } else {
          errorMsg += err.message;
        }
        
        showError(errorMsg);
      }
    }

    // Load face-api models
    async function loadFaceApi() {
      try {
        console.log('Loading face-api models...');
        
        const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.12/model';
        
        await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
        await faceapi.nets.faceLandmark68TinyNet.loadFromUri(MODEL_URL);
        
        faceApiLoaded = true;
        console.log('Face-api models loaded successfully!');
        
        // Initialize liveness detector
        livenessDetector = new LivenessDetector();
        setupLivenessCallbacks();
        
      } catch (err) {
        console.error('Failed to load face-api:', err);
        showError('Gagal memuat model AI untuk deteksi wajah.');
      }
    }

    // Setup liveness detector callbacks
    function setupLivenessCallbacks() {
      livenessDetector.on('blinkDetected', (count) => {
        const blinkStatus = document.getElementById('blinkStatus');
        if (blinkStatus) {
          blinkStatus.textContent = count >= 2 ? '✅' : `${count}/2`;
        }
      });

      livenessDetector.on('headMovementDetected', (movement) => {
        const headMoveStatus = document.getElementById('headMoveStatus');
        if (headMoveStatus) {
          headMoveStatus.textContent = '✅';
        }
      });

      livenessDetector.on('progress', (progress) => {
        const progressBar = document.getElementById('livenessProgressBar');
        const progressText = document.getElementById('livenessProgress');
        const timeRemaining = document.getElementById('timeRemaining');
        
        if (progressBar) progressBar.style.width = progress.percentage + '%';
        if (progressText) progressText.textContent = progress.percentage + '%';
        if (timeRemaining) timeRemaining.textContent = Math.ceil(progress.timeRemaining / 1000);
      });

      livenessDetector.on('livenessConfirmed', (result) => {
        console.log('Liveness confirmed!', result);
        hideLivenessChallenge();
        capturePhotoAfterLiveness();
      });

      livenessDetector.on('livenessFailed', (reason) => {
        console.log('Liveness failed:', reason);
        hideLivenessChallenge();
        showError(reason);
        isProcessing = false;
        scanBtn.disabled = false;
        scanBtn.innerHTML = originalBtnText;
      });
    }

    // Start face detection for liveness
    async function startLivenessDetection() {
      if (detectionInterval) clearInterval(detectionInterval);
      
      detectionInterval = setInterval(async () => {
        if (!faceApiLoaded || !video.videoWidth || !livenessDetector.isActive) return;

        try {
          const detection = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks(true);

          if (detection) {
            await livenessDetector.process(detection);
          }
        } catch (err) {
          console.error('Detection error:', err);
        }
      }, 100); // Check every 100ms
    }

    // Stop face detection
    function stopLivenessDetection() {
      if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
      }
    }

    // Show liveness challenge
    function showLivenessChallenge() {
      const challenge = document.getElementById('livenessChallenge');
      if (challenge) {
        challenge.classList.remove('hidden');
        
        // Reset UI
        document.getElementById('blinkStatus').textContent = '⏳';
        document.getElementById('headMoveStatus').textContent = '⏳';
        document.getElementById('livenessProgressBar').style.width = '0%';
        document.getElementById('livenessProgress').textContent = '0%';
        document.getElementById('timeRemaining').textContent = '10';
      }
    }

    // Hide liveness challenge
    function hideLivenessChallenge() {
      const challenge = document.getElementById('livenessChallenge');
      if (challenge) {
        challenge.classList.add('hidden');
      }
      stopLivenessDetection();
    }

    // Capture photo after liveness confirmed
    async function capturePhotoAfterLiveness() {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0);
      const photoData = canvas.toDataURL('image/jpeg', 0.95);

      // Process attendance
      await processAttendance(photoData);
    }

    // Process attendance with photo
    async function processAttendance(photoData) {
      try {
        const result = await @this.call('scanFace', photoData, currentPosition.latitude, currentPosition.longitude);

        if (result.success) {
          showSuccess(result.message, result);
          
          // Reload after 3 seconds
          setTimeout(() => {
            window.location.reload();
          }, 3000);
        } else {
          // Show detailed error if liveness check failed
          let errorMsg = result.message;
          
          if (result.liveness_score !== undefined) {
            errorMsg += `\n\nLiveness Score: ${result.liveness_score}/100`;
            
            if (result.liveness_checks) {
              errorMsg += '\n\nDetail Pemeriksaan:';
              for (const [key, check] of Object.entries(result.liveness_checks)) {
                const checkName = {
                  'blur': 'Ketajaman',
                  'texture': 'Tekstur',
                  'brightness': 'Pencahayaan',
                  'color_variance': 'Variasi Warna'
                }[key] || key;
                
                errorMsg += `\n- ${checkName}: ${check.score.toFixed(1)}/100 (${check.status})`;
              }
            }
          }
          
          showError(errorMsg);
          isProcessing = false;
          scanBtn.disabled = false;
          scanBtn.innerHTML = originalBtnText;
        }
      } catch (error) {
        console.error('Scan error:', error);
        showError('Terjadi kesalahan: ' + error.message);
        isProcessing = false;
        scanBtn.disabled = false;
        scanBtn.innerHTML = originalBtnText;
      }
    }

    // Cancel liveness detection
    const cancelLiveness = document.getElementById('cancelLiveness');
    if (cancelLiveness) {
      cancelLiveness.addEventListener('click', () => {
        if (livenessDetector) {
          livenessDetector.stop();
        }
        hideLivenessChallenge();
        isProcessing = false;
        scanBtn.disabled = false;
        scanBtn.innerHTML = originalBtnText;
      });
    }

    // Handle manual photo upload
    if (manualPhoto) {
      manualPhoto.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = (event) => {
            manualPhotoData = event.target.result;
            
            // Show preview in error container
            if (cameraError) {
              cameraError.innerHTML = `
                <div class="w-full h-full flex flex-col items-center justify-center p-4">
                  <img src="${manualPhotoData}" class="max-w-full max-h-64 object-contain rounded-lg mb-4 border-2 border-green-500">
                  <p class="text-white font-semibold mb-2">✅ Foto siap digunakan</p>
                  <button onclick="resetManualPhoto()" 
                          class="text-sm text-blue-400 hover:text-blue-300 underline">
                    Ganti Foto
                  </button>
                </div>
              `;
            }
            
            // Enable scan button
            if (scanBtn) {
              scanBtn.disabled = false;
            }
          };
          reader.readAsDataURL(file);
        }
      });
    }

    // Reset manual photo
    window.resetManualPhoto = function() {
      if (manualPhoto) manualPhoto.value = '';
      manualPhotoData = null;
      startCamera(); // Try camera again
    };

    // Get GPS location
    function getLocation() {
      if (!navigator.geolocation) {
        showError('Browser Anda tidak mendukung GPS');
        gpsIcon.textContent = '❌';
        gpsText.textContent = 'GPS tidak didukung';
        gpsStatus.classList.add('bg-red-600');
        return;
      }

      gpsIcon.textContent = '🔄';
      gpsText.textContent = 'Mencari lokasi...';

      navigator.geolocation.getCurrentPosition(
        (position) => {
          currentPosition = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude
          };
          
          gpsIcon.textContent = '✅';
          gpsText.textContent = 'Lokasi terdeteksi';
          gpsStatus.classList.remove('bg-black/50', 'bg-red-600');
          gpsStatus.classList.add('bg-green-600');
          
          console.log('GPS location:', currentPosition);
        },
        (error) => {
          console.error('GPS error:', error);
          gpsIcon.textContent = '❌';
          gpsText.textContent = 'GPS tidak tersedia';
          gpsStatus.classList.remove('bg-black/50');
          gpsStatus.classList.add('bg-red-600');
          
          let errorMsg = 'Tidak dapat mengakses GPS. ';
          if (error.code === 1) {
            errorMsg += 'Izin lokasi ditolak.';
          } else if (error.code === 2) {
            errorMsg += 'Lokasi tidak tersedia.';
          } else if (error.code === 3) {
            errorMsg += 'Timeout.';
          }
          
          showError(errorMsg + ' Pastikan GPS aktif dan izin lokasi diberikan.');
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0
        }
      );
    }

    // Scan face
    let originalBtnText = '';
    if (scanBtn) {
      originalBtnText = scanBtn.innerHTML;
      
      scanBtn.addEventListener('click', async () => {
        if (isProcessing) return;
        
        if (!currentPosition) {
          showError('Lokasi belum terdeteksi. Pastikan GPS aktif dan izin lokasi diberikan.');
          return;
        }

        // Check if face-api is loaded
        if (!faceApiLoaded) {
          showError('Model AI belum siap. Silakan tunggu beberapa saat.');
          return;
        }

        // Use manual photo if camera not available
        if (!cameraAvailable && manualPhotoData) {
          isProcessing = true;
          scanBtn.disabled = true;
          scanBtn.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...</span>';
          
          await processAttendance(manualPhotoData);
          return;
        }

        if (!cameraAvailable) {
          showError('Silakan upload foto terlebih dahulu atau izinkan akses kamera.');
          return;
        }

        // Start liveness detection challenge
        isProcessing = true;
        scanBtn.disabled = true;
        scanBtn.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memulai Verifikasi...</span>';

        // Show liveness challenge
        showLivenessChallenge();

        // Start liveness detector
        livenessDetector.start({
          requiredBlinks: 2,
          requiredHeadMovement: true,
          timeLimit: 10000
        });

        // Start detection loop
        startLivenessDetection();
      });
    }

    // Show error message
    function showError(message) {
      if (errorMessage && errorText) {
        errorMessage.classList.remove('hidden');
        errorText.textContent = message;
      }
      
      if (successMessage) {
        successMessage.classList.add('hidden');
      }
      
      // Auto hide after 8 seconds
      setTimeout(() => {
        if (errorMessage) errorMessage.classList.add('hidden');
      }, 8000);
    }

    // Show success message
    function showSuccess(message, data) {
      if (successMessage && successText) {
        successMessage.classList.remove('hidden');
        successText.textContent = message;
        
        if (data && successDetails) {
          let details = '';
          if (data.similarity) details += `<div>Similarity Score: <strong>${data.similarity}%</strong></div>`;
          if (data.office) details += `<div>Lokasi: <strong>${data.office}</strong></div>`;
          if (data.distance) details += `<div>Jarak: <strong>${data.distance}m</strong></div>`;
          successDetails.innerHTML = details;
        }
      }
      
      if (errorMessage) {
        errorMessage.classList.add('hidden');
      }
    }

    // Initialize
    @if ($hasFaceRegistration && !$isAbsence)
      console.log('Initializing camera and GPS...');
      startCamera();
      getLocation();
    @endif

    // Countdown timer for clock out
    @if($attendance && $attendance->time_in && !$attendance->time_out && !$canClockOut && $minutesUntilClockOut)
      let countdownMinutes = {{ $minutesUntilClockOut }};
      const countdownTimer = document.getElementById('countdown-timer');
      const scanBtn = document.getElementById('scanBtn');
      
      function updateCountdown() {
        if (countdownMinutes <= 0) {
          // Time's up! Reload page to enable button
          window.location.reload();
          return;
        }
        
        countdownMinutes--;
        const hours = Math.floor(countdownMinutes / 60);
        const mins = countdownMinutes % 60;
        
        if (countdownTimer) {
          if (hours > 0) {
            countdownTimer.textContent = `${hours}j ${mins}m`;
          } else {
            countdownTimer.textContent = `${mins}m`;
          }
        }
      }
      
      // Update every minute
      const countdownInterval = setInterval(updateCountdown, 60000);
      
      // Cleanup countdown on page unload
      window.addEventListener('beforeunload', () => {
        if (countdownInterval) {
          clearInterval(countdownInterval);
        }
      });
    @endif

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }
    });
  </script>
  @endpush
</div>
