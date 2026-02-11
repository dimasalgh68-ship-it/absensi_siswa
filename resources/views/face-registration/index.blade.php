<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrasi Wajah') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($registration)
                        <!-- Wajah sudah terdaftar -->
                        <div class="text-center">
                            <div class="mb-6">
                                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                ✅ Wajah Sudah Terdaftar
                            </h3>
                            
                            <p class="text-gray-600 dark:text-gray-400 mb-2">
                                Terdaftar pada: {{ $registration->registered_at->format('d M Y H:i') }}
                            </p>
                            
                            

                            @if ($registration->photo_path)
                                <div class="mb-6">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Foto Wajah Terdaftar:</p>
                                    <img src="{{ Storage::url($registration->photo_path) }}" 
                                         alt="Foto Wajah" 
                                         class="mx-auto rounded-lg shadow-lg max-w-xs border-4 border-green-500">
                                </div>
                            @endif

                            <div class="space-y-3">
                                <div>
                                    <a href="{{ route('face-attendance.index') }}" 
                                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                         Mulai Absensi dengan Face Recognition
                                    </a>
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Perlu bantuan? Hubungi administrator untuk menghapus atau mengubah registrasi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Form registrasi wajah -->
                        <div class="max-w-2xl mx-auto">
                            <div class="text-center mb-8">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                                    Daftarkan Wajah Anda
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Ambil foto selfie untuk mendaftarkan wajah Anda. Pastikan wajah terlihat jelas dan pencahayaan cukup.
                                </p>
                            </div>
                    
                            <!-- Camera Preview -->
                            <div class="mb-6">
                                <div class="relative bg-gray-900 rounded-lg overflow-hidden" style="aspect-ratio: 4/3;">
                                    <video id="camera" autoplay playsinline class="w-full h-full object-cover"></video>
                                    <canvas id="canvas" class="hidden"></canvas>
                                    <canvas id="overlay" class="absolute top-0 left-0 w-full h-full pointer-events-none"></canvas>
                                    <img id="preview" class="hidden w-full h-full object-cover" />
                                    
                                    {{-- Face Detection Status --}}
                                    <div id="faceStatus" class="absolute top-4 left-4 bg-black/70 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                        <span id="faceStatusIcon">🔍</span>
                                        <span id="faceStatusText">Mencari wajah...</span>
                                    </div>
                                    
                                    {{-- Camera Error Fallback --}}
                                    <div id="cameraError" class="hidden absolute inset-0 bg-gray-800 flex flex-col items-center justify-center p-6 text-center">
                                        <svg class="w-16 h-16 text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <h3 class="text-white font-bold text-lg mb-2">Kamera Tidak Dapat Diakses</h3>
                                        <p class="text-gray-300 text-sm mb-4">
                                            Untuk menggunakan kamera, aplikasi harus diakses melalui HTTPS.<br>
                                            Saat ini Anda mengakses melalui HTTP.
                                        </p>
                                        <div class="bg-blue-900/50 rounded-lg p-4 mb-4 text-left text-sm text-gray-200">
                                            <p class="font-semibold mb-2">Solusi:</p>
                                            <ol class="list-decimal list-inside space-y-1">
                                                <li>Gunakan HTTPS (Recommended)</li>
                                                <li>Atau upload foto manual di bawah</li>
                                            </ol>
                                        </div>
                                        <label for="manualPhotoReg" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg inline-block">
                                            📷 Upload Foto Manual
                                        </label>
                                        <input type="file" id="manualPhotoReg" accept="image/*" capture="user" class="hidden">
                                    </div>
                                </div>
                            </div>

                            <!-- Instructions -->
                            <div class="mb-6 bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                                <h4 class="font-bold text-blue-900 dark:text-blue-100 mb-2">Panduan:</h4>
                                <ul class="list-disc list-inside text-blue-800 dark:text-blue-200 space-y-1">
                                    <li>Pastikan wajah Anda berada di tengah frame</li>
                                    <li>Hindari menggunakan kacamata hitam atau masker</li>
                                    <li>Pastikan pencahayaan cukup terang</li>
                                    <li><strong>Jangan menggunakan foto dari layar atau cetakan</strong></li>
                                </ul>
                                
                                <div class="mt-3 pt-3 border-t border-blue-200 dark:border-blue-700">
                                    <p class="text-xs text-blue-700 dark:text-blue-300 flex items-start gap-2">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <span><strong>Keamanan:</strong> Sistem dilengkapi dengan deteksi anti-spoofing untuk mencegah penggunaan foto palsu. Foto akan dianalisis untuk memastikan keasliannya.</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Form -->
                            <form id="registrationForm" action="{{ route('face-registration.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                                <input type="hidden" name="descriptor" id="descriptorInput">
                                
                                <div class="flex gap-3">
                                    <button type="button" 
                                            id="captureBtn"
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                        📸 Ambil Foto
                                    </button>
                                    
                                    <button type="button" 
                                            id="retakeBtn"
                                            class="hidden flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg">
                                        🔄 Ambil Ulang
                                    </button>
                                    
                                    <button type="submit" 
                                            id="submitBtn"
                                            class="hidden flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                                        ✓ Daftarkan Wajah
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Face API Library (ONLY) -->
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.12/dist/face-api.min.js"></script>
    
    <script>
        // DOM Elements
        const video = document.getElementById('camera');
        const canvas = document.getElementById('canvas');
        const overlay = document.getElementById('overlay');
        const preview = document.getElementById('preview');
        const captureBtn = document.getElementById('captureBtn');
        const retakeBtn = document.getElementById('retakeBtn');
        const submitBtn = document.getElementById('submitBtn');
        const photoInput = document.getElementById('photoInput');
        const descriptorInput = document.getElementById('descriptorInput');
        const cameraError = document.getElementById('cameraError');
        const manualPhotoReg = document.getElementById('manualPhotoReg');
        const faceStatus = document.getElementById('faceStatus');
        const faceStatusIcon = document.getElementById('faceStatusIcon');
        const faceStatusText = document.getElementById('faceStatusText');
        
        // State
        let stream = null;
        let cameraAvailable = false;
        let faceApiLoaded = false;
        let detectionInterval = null;
        let faceDetected = false;

        // Load face-api models
        async function loadFaceApi() {
            try {
                console.log('Loading face-api models...');
                faceStatusText.textContent = 'Memuat model AI...';
                
                const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.12/model';
                
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68TinyNet.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                
                faceApiLoaded = true;
                console.log('Face-api models loaded successfully!');
                faceStatusText.textContent = 'Mencari wajah...';
                startFaceDetection();
            } catch (err) {
                console.error('Failed to load face-api:', err);
                faceStatusText.textContent = 'Gagal memuat model AI';
                faceStatus.className = 'absolute top-4 left-4 bg-red-600/90 text-white px-4 py-2 rounded-lg text-sm font-semibold';
            }
        }

        // Detect faces in video
        async function detectFaces() {
            if (!faceApiLoaded || !video.videoWidth) return;

            try {
                const detection = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks(true);

                // Set canvas size FIRST, then clear
                overlay.width = video.videoWidth;
                overlay.height = video.videoHeight;
                
                const ctx = overlay.getContext('2d');
                ctx.clearRect(0, 0, overlay.width, overlay.height);
                
                if (!detection) {
                    // No face detected
                    faceDetected = false;
                    faceStatusIcon.textContent = '❌';
                    faceStatusText.textContent = 'Wajah tidak terdeteksi';
                    faceStatus.className = 'absolute top-4 left-4 bg-red-600/90 text-white px-4 py-2 rounded-lg text-sm font-semibold';
                    captureBtn.disabled = true;
                    captureBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    // Face detected
                    faceDetected = true;
                    faceStatusIcon.textContent = '✅';
                    faceStatusText.textContent = 'Wajah terdeteksi - Siap!';
                    faceStatus.className = 'absolute top-4 left-4 bg-green-600/90 text-white px-4 py-2 rounded-lg text-sm font-semibold';
                    captureBtn.disabled = false;
                    captureBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    
                    // Draw face box
                    drawFaceBox(ctx, detection.detection.box);
                }
            } catch (err) {
                console.error('Face detection error:', err);
            }
        }

        // Draw face bounding box
        function drawFaceBox(ctx, box) {
            const { x, y, width, height } = box;
            
            // Main box
            ctx.strokeStyle = '#10b981';
            ctx.lineWidth = 3;
            ctx.strokeRect(x, y, width, height);
            
            // Corner decorations
            const cornerLength = 20;
            ctx.lineWidth = 4;
            
            // Top-left
            ctx.beginPath();
            ctx.moveTo(x, y + cornerLength);
            ctx.lineTo(x, y);
            ctx.lineTo(x + cornerLength, y);
            ctx.stroke();
            
            // Top-right
            ctx.beginPath();
            ctx.moveTo(x + width - cornerLength, y);
            ctx.lineTo(x + width, y);
            ctx.lineTo(x + width, y + cornerLength);
            ctx.stroke();
            
            // Bottom-left
            ctx.beginPath();
            ctx.moveTo(x, y + height - cornerLength);
            ctx.lineTo(x, y + height);
            ctx.lineTo(x + cornerLength, y + height);
            ctx.stroke();
            
            // Bottom-right
            ctx.beginPath();
            ctx.moveTo(x + width - cornerLength, y + height);
            ctx.lineTo(x + width, y + height);
            ctx.lineTo(x + width, y + height - cornerLength);
            ctx.stroke();
        }

        // Start continuous face detection
        function startFaceDetection() {
            if (detectionInterval) clearInterval(detectionInterval);
            detectionInterval = setInterval(detectFaces, 200);
        }

        // Stop face detection
        function stopFaceDetection() {
            if (detectionInterval) {
                clearInterval(detectionInterval);
                detectionInterval = null;
            }
        }

        // Extract face descriptor
        async function extractDescriptor(imageElement) {
            try {
                const detection = await faceapi
                    .detectSingleFace(imageElement, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks(true)
                    .withFaceDescriptor();

                if (!detection) {
                    throw new Error('Wajah tidak terdeteksi di foto');
                }

                return Array.from(detection.descriptor);
            } catch (err) {
                console.error('Failed to extract descriptor:', err);
                throw err;
            }
        }

        // Start camera
        async function startCamera() {
            try {
                const isSecure = window.location.protocol === 'https:' || 
                                window.location.hostname === 'localhost' || 
                                window.location.hostname === '127.0.0.1';
                
                if (!isSecure) {
                    throw new Error('Camera requires HTTPS');
                }

                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'user',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    } 
                });
                video.srcObject = stream;
                cameraAvailable = true;
                
                video.onloadedmetadata = () => {
                    loadFaceApi();
                };
            } catch (err) {
                console.error('Camera error:', err);
                cameraAvailable = false;
                
                if (cameraError) {
                    video.style.display = 'none';
                    cameraError.classList.remove('hidden');
                    faceStatus.classList.add('hidden');
                }
            }
        }

        // Handle manual photo upload
        if (manualPhotoReg) {
            manualPhotoReg.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    photoInput.files = dataTransfer.files;
                    
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        if (cameraError) {
                            cameraError.innerHTML = `
                                <img src="${event.target.result}" class="w-full h-full object-cover rounded-lg mb-4">
                                <p class="text-white font-semibold mb-2">Foto siap digunakan</p>
                                <button type="button" onclick="location.reload();" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                                    Ganti Foto
                                </button>
                            `;
                        }
                        
                        captureBtn.classList.add('hidden');
                        submitBtn.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Capture photo from camera
        captureBtn.addEventListener('click', async () => {
            if (!cameraAvailable) {
                alert('Kamera tidak tersedia. Silakan upload foto manual.');
                return;
            }

            if (!faceDetected) {
                alert('Pastikan wajah Anda terdeteksi dengan baik sebelum mengambil foto.');
                return;
            }

            // Stop face detection
            stopFaceDetection();

            // Show loading
            captureBtn.disabled = true;
            captureBtn.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...</span>';

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            
            try {
                // Extract descriptor from captured image
                const descriptor = await extractDescriptor(canvas);
                
                // Store descriptor in hidden input
                descriptorInput.value = JSON.stringify(descriptor);
                
                canvas.toBlob((blob) => {
                    const file = new File([blob], 'face.jpg', { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    photoInput.files = dataTransfer.files;
                    
                    preview.src = URL.createObjectURL(blob);
                    preview.classList.remove('hidden');
                    video.classList.add('hidden');
                    overlay.classList.add('hidden');
                    faceStatus.classList.add('hidden');
                    
                    captureBtn.classList.add('hidden');
                    retakeBtn.classList.remove('hidden');
                    submitBtn.classList.remove('hidden');
                    
                    // Stop camera
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                    }
                }, 'image/jpeg', 0.95);
            } catch (err) {
                alert('Gagal memproses wajah: ' + err.message);
                captureBtn.disabled = false;
                captureBtn.innerHTML = '📸 Ambil Foto';
                startFaceDetection();
            }
        });

        // Retake photo
        retakeBtn.addEventListener('click', () => {
            preview.classList.add('hidden');
            video.classList.remove('hidden');
            overlay.classList.remove('hidden');
            faceStatus.classList.remove('hidden');
            
            captureBtn.classList.remove('hidden');
            captureBtn.innerHTML = '📸 Ambil Foto';
            retakeBtn.classList.add('hidden');
            submitBtn.classList.add('hidden');
            
            descriptorInput.value = '';
            
            startCamera();
        });

        // Start camera on page load
        @if (!$registration)
            startCamera();
        @endif
    </script>
    @endpush
</x-app-layout>
