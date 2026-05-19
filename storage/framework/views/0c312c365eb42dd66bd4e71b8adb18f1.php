<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <style>
        /* Smooth scrolling optimization */
        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
            max-width: 100vw;
        }
        
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        
        /* Reduce repaints during scroll */
        video, canvas {
            will-change: transform;
        }

        /* Loading Screen Styles */
        #loadingScreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }

        #loadingScreen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-container {
            text-align: center;
        }

        .face-loader {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            position: relative;
        }

        .face-circle {
            width: 100%;
            height: 100%;
            border: 4px solid #e5e7eb;
            border-top-color: #009ee0;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .face-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.8; }
        }

        .loading-text {
            color: #1f2937;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 12px;
            animation: fadeInOut 2s ease-in-out infinite;
        }

        .loading-subtext {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 30px;
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .loading-dots {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .loading-dot {
            width: 12px;
            height: 12px;
            background: #009ee0;
            border-radius: 50%;
            animation: bounce 1.4s ease-in-out infinite;
        }

        .loading-dot:nth-child(1) { animation-delay: 0s; }
        .loading-dot:nth-child(2) { animation-delay: 0.2s; }
        .loading-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1.2); opacity: 1; }
        }

        .progress-bar-container {
            width: 300px;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 20px;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #009ee0, #48cae4);
            border-radius: 2px;
            width: 0%;
            transition: width 0.3s ease;
        }
    </style>
    
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Registrasi Wajah')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <!-- Loading Screen -->
    <?php if(!$registration): ?>
    <div id="loadingScreen">
        <div class="loader-container">
            <div class="face-loader">
                <div class="face-circle"></div>
                <div class="face-icon">📸</div>
            </div>
            <div class="loading-text" id="loadingText">Mempersiapkan Registrasi Wajah</div>
            <div class="loading-subtext" id="loadingSubtext">Mohon tunggu sebentar...</div>
            <div class="loading-dots">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar" id="progressBar"></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <?php if(session('success')): ?>
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($registration): ?>
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
                                Terdaftar pada: <?php echo e($registration->registered_at->format('d M Y H:i')); ?>

                            </p>
                            
                            

                            <?php if($registration->photo_path): ?>
                                <div class="mb-6">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Foto Wajah Terdaftar:</p>
                                    <img src="<?php echo e(Storage::url($registration->photo_path)); ?>" 
                                         alt="Foto Wajah" 
                                         class="mx-auto rounded-lg shadow-lg max-w-xs border-4 border-green-500">
                                </div>
                            <?php endif; ?>

                            <div class="space-y-3">
                                <div>
                                    <a href="<?php echo e(route('face-attendance.index')); ?>" 
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
                    <?php else: ?>
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
                    
                            <!-- Camera Preview - Square 1:1 -->
                            <div class="mb-6">
                                <div class="relative bg-gray-900 rounded-lg overflow-hidden mx-auto" style="max-width: 500px; aspect-ratio: 1/1;">
                                    <video id="camera" autoplay playsinline class="w-full h-full object-cover"></video>
                                    <canvas id="canvas" class="hidden"></canvas>
                                    <canvas id="overlay" class="absolute top-0 left-0 w-full h-full pointer-events-none"></canvas>
                                    <img id="preview" class="hidden w-full h-full object-cover" />
                                    
                                    
                                    <div id="faceStatus" class="absolute top-4 left-4 bg-black/70 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                        <span id="faceStatusIcon">🔍</span>
                                        <span id="faceStatusText">Mencari wajah...</span>
                                    </div>
                                    
                                    
                                    <div id="cameraError" class="hidden absolute inset-0 bg-gray-800 flex flex-col items-center justify-center p-6 text-center">
                                        <svg class="w-16 h-16 text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <h3 class="text-white font-bold text-lg mb-2">Kamera Tidak Dapat Diakses</h3>
                                        <p class="text-gray-300 text-sm mb-4">
                                            Untuk menggunakan kamera,  aplikasi harus diakses melalui HTTPS.<br>
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

                            <!-- Form -->
                            <form id="registrationForm" action="<?php echo e(route('face-registration.store')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                                <input type="hidden" name="descriptor" id="descriptorInput">
                                
                                <!-- Buttons Below Canvas -->
                                <div class="flex gap-3 max-w-md mx-auto mb-6">
                                    <button type="button" 
                                            id="captureBtn"
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg hover:shadow-xl">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Ambil Foto
                                        </span>
                                    </button>
                                    
                                    <button type="button" 
                                            id="retakeBtn"
                                            class="hidden flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-6 rounded-lg transition-all shadow-lg hover:shadow-xl">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Ambil Ulang
                                        </span>
                                    </button>
                                    
                                    <button type="submit" 
                                            id="submitBtn"
                                            class="hidden flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition-all shadow-lg hover:shadow-xl">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Daftarkan Wajah
                                        </span>
                                    </button>
                                </div>
                            </form>

                            <!-- Instructions -->
                            <div class="mt-6 bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                                <h4 class="font-bold text-blue-900 dark:text-blue-100 mb-2">Panduan:</h4>
                                <ul class="list-disc list-inside text-blue-800 dark:text-blue-200 space-y-1 text-sm">
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
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
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

        // Loading Screen Management
        const loadingScreen = document.getElementById('loadingScreen');
        const loadingText = document.getElementById('loadingText');
        const loadingSubtext = document.getElementById('loadingSubtext');
        const progressBar = document.getElementById('progressBar');
        
        const loadingSteps = [
            { progress: 25, text: 'Mengakses Kamera', subtext: 'Meminta izin akses kamera...' },
            { progress: 50, text: 'Memuat Model AI', subtext: 'Mengunduh model face recognition...' },
            { progress: 75, text: 'Mempersiapkan Deteksi', subtext: 'Mengaktifkan sistem deteksi wajah...' },
            { progress: 100, text: 'Siap!', subtext: 'Sistem registrasi wajah siap digunakan' }
        ];

        function updateLoadingProgress(step) {
            if (loadingScreen && step < loadingSteps.length) {
                const stepData = loadingSteps[step];
                if (loadingText) loadingText.textContent = stepData.text;
                if (loadingSubtext) loadingSubtext.textContent = stepData.subtext;
                if (progressBar) progressBar.style.width = stepData.progress + '%';
            }
        }

        function hideLoadingScreen() {
            if (loadingScreen) {
                setTimeout(() => {
                    loadingScreen.classList.add('hidden');
                }, 500);
            }
        }

        // Load face-api models
        async function loadFaceApi() {
            try {
                console.log('Loading face-api models...');
                faceStatusText.textContent = 'Memuat model AI...';
                updateLoadingProgress(1); // Step 2: Loading AI Models
                
                const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.12/model';
                
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68TinyNet.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                
                faceApiLoaded = true;
                console.log('Face-api models loaded successfully!');
                faceStatusText.textContent = 'Mencari wajah...';
                updateLoadingProgress(2); // Step 3: Preparing Detection
                startFaceDetection();
                
                setTimeout(() => {
                    updateLoadingProgress(3); // Step 4: Ready!
                    setTimeout(hideLoadingScreen, 800);
                }, 500);
            } catch (err) {
                console.error('Failed to load face-api:', err);
                faceStatusText.textContent = 'Gagal memuat model AI';
                faceStatus.className = 'absolute top-4 left-4 bg-red-600/90 text-white px-4 py-2 rounded-lg text-sm font-semibold';
                hideLoadingScreen();
            }
        }

        // Detect faces in video
        async function detectFaces() {
            if (!faceApiLoaded || !video.videoWidth) return;

            try {
                const detection = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks(true);

                // Use requestAnimationFrame for smoother rendering
                requestAnimationFrame(() => {
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
                });
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
            detectionInterval = setInterval(detectFaces, 800); // Increased to 800ms for smoother scrolling
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
                updateLoadingProgress(0); // Step 1: Accessing Camera
                const isSecure = window.location.protocol === 'https:' || 
                                window.location.hostname === 'localhost' || 
                                window.location.hostname === '127.0.0.1';
                
                if (!isSecure) {
                    throw new Error('Camera requires HTTPS');
                }

                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'user',
                        width: { ideal: 640 },  // Reduced for better performance
                        height: { ideal: 480 },
                        frameRate: { ideal: 15, max: 20 }  // Lower frame rate
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
                hideLoadingScreen();
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
        <?php if(!$registration): ?>
            startCamera();
        <?php endif; ?>

        // Pause detection during scroll for better performance
        let scrollTimeout;
        let isScrolling = false;
        
        window.addEventListener('scroll', () => {
            isScrolling = true;
            
            // Temporarily stop detection during scroll
            if (detectionInterval) {
                clearInterval(detectionInterval);
                detectionInterval = null;
            }
            
            // Clear previous timeout
            clearTimeout(scrollTimeout);
            
            // Resume detection after scroll stops
            scrollTimeout = setTimeout(() => {
                isScrolling = false;
                if (faceApiLoaded && !detectionInterval && cameraAvailable) {
                    startFaceDetection();
                }
            }, 150);
        }, { passive: true });

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            if (detectionInterval) {
                clearInterval(detectionInterval);
            }
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/face-registration/index.blade.php ENDPATH**/ ?>