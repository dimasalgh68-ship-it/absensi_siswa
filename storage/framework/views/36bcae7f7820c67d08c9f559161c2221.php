<?php if (isset($component)) { $__componentOriginal91fdd17964e43374ae18c674f95cdaa3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91fdd17964e43374ae18c674f95cdaa3 = $attributes; } ?>
<?php $component = App\View\Components\AdminLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AdminLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('Pengaturan Aplikasi')); ?></h1>
     <?php $__env->endSlot(); ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Nama Sekolah/Aplikasi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-school mr-2"></i>Nama Sekolah / Aplikasi
            </h6>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.settings.update-app-name')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="app_name">Nama Sekolah / Aplikasi <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="app_name" 
                           id="app_name"
                           value="<?php echo e(old('app_name', $appName)); ?>"
                           class="form-control"
                           placeholder="Contoh: SMA Negeri 1 Jakarta"
                           required>
                    <?php $__errorArgs = ['app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Nama ini akan ditampilkan di sidebar, topbar, dan halaman login
                    </small>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <!-- Logo Aplikasi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-image mr-2"></i>Logo Sekolah / Aplikasi
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Current Logo -->
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Logo Saat Ini:</label>
                    <div class="border rounded p-4 text-center bg-light">
                        <?php if($logo): ?>
                            <img src="<?php echo e(asset('storage/' . $logo)); ?>" 
                                 alt="App Logo" 
                                 class="img-fluid mb-3"
                                 style="max-height: 150px;">
                            <br>
                            <form action="<?php echo e(route('admin.settings.reset-logo')); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin mereset logo ke default?')"
                                        class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash mr-1"></i> Reset ke Default
                                </button>
                            </form>
                        <?php else: ?>
                            <i class="fas fa-image fa-4x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada logo</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Upload New Logo -->
                <div class="col-md-6">
                    <label class="font-weight-bold">Upload Logo Baru:</label>
                    <form action="<?php echo e(route('admin.settings.update-logo')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <input type="file" 
                                   name="logo" 
                                   id="logo"
                                   accept="image/png,image/jpeg,image/jpg,image/svg+xml"
                                   class="form-control-file"
                                   required>
                            <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <strong><i class="fas fa-info-circle"></i> Ketentuan:</strong><br>
                                • Format: PNG, JPG, JPEG, atau SVG<br>
                                • Ukuran maksimal: 2MB<br>
                                • Rekomendasi: Rasio 1:1 atau 16:9<br>
                                • Resolusi minimal: 200x200px
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload mr-1"></i> Upload Logo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengaturan Waktu Absensi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-clock mr-2"></i>Pengaturan Waktu Absensi
            </h6>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.settings.update-attendance')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i> <strong>Informasi:</strong><br>
                    Pengaturan ini mengontrol kapan siswa dapat melakukan absensi masuk dan keluar berdasarkan jadwal shift mereka.
                </div>

                <div class="row">
                    <!-- Clock In Early -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="clock_in_early_minutes">
                                <i class="fas fa-sign-in-alt text-success"></i> Absen Masuk Lebih Awal (Menit)
                            </label>
                            <input type="number" 
                                   name="clock_in_early_minutes" 
                                   id="clock_in_early_minutes"
                                   value="<?php echo e(old('clock_in_early_minutes', $clockInEarlyMinutes ?? 60)); ?>"
                                   min="0"
                                   class="form-control"
                                   required>
                            <?php $__errorArgs = ['clock_in_early_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Berapa menit sebelum jadwal masuk siswa bisa clock in
                            </small>
                        </div>
                    </div>

                    <!-- Clock In Late -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="clock_in_late_minutes">
                                <i class="fas fa-hourglass-end text-danger"></i> Batas Waktu Absen Masuk (Menit)
                            </label>
                            <input type="number" 
                                   name="clock_in_late_minutes" 
                                   id="clock_in_late_minutes"
                                   value="<?php echo e(old('clock_in_late_minutes', $clockInLateMinutes ?? 120)); ?>"
                                   min="0"
                                   class="form-control"
                                   required>
                            <?php $__errorArgs = ['clock_in_late_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Berapa menit setelah jadwal masuk siswa masih bisa clock in
                            </small>
                        </div>
                    </div>

                    <!-- Late Tolerance -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="late_tolerance_minutes">
                                <i class="fas fa-user-clock text-warning"></i> Toleransi Keterlambatan (Menit)
                            </label>
                            <input type="number" 
                                   name="late_tolerance_minutes" 
                                   id="late_tolerance_minutes"
                                   value="<?php echo e(old('late_tolerance_minutes', $lateToleranceMinutes)); ?>"
                                   min="0"
                                   class="form-control"
                                   required>
                            <?php $__errorArgs = ['late_tolerance_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Waktu toleransi sebelum dianggap terlambat
                            </small>
                        </div>
                    </div>

                    <!-- Clock Out Early -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="clock_out_early_minutes">
                                <i class="fas fa-door-open text-info"></i> Waktu Pulang Lebih Awal (Menit)
                            </label>
                            <input type="number" 
                                   name="clock_out_early_minutes" 
                                   id="clock_out_early_minutes"
                                   value="<?php echo e(old('clock_out_early_minutes', $clockOutEarlyMinutes)); ?>"
                                   min="0"
                                   class="form-control"
                                   required>
                            <?php $__errorArgs = ['clock_out_early_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Berapa menit sebelum jadwal pulang siswa bisa clock out
                            </small>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <strong><i class="fas fa-exclamation-triangle"></i> Contoh Penggunaan:</strong><br>
                    Jika shift dimulai jam 07:00 dan berakhir jam 15:00:<br>
                    • Dengan "Absen Masuk Lebih Awal" = 60 menit, siswa bisa clock in mulai jam 06:00<br>
                    • Dengan "Batas Waktu Absen Masuk" = 120 menit, siswa masih bisa clock in sampai jam 09:00<br>
                    • Dengan "Toleransi Keterlambatan" = 15 menit, siswa dianggap terlambat jika clock in setelah jam 07:15<br>
                    • Dengan "Waktu Pulang Lebih Awal" = 30 menit, siswa bisa clock out mulai jam 14:30
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>

    <!-- Pengaturan Face Recognition -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-check mr-2"></i>Pengaturan Face Recognition
            </h6>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.settings.update-face-recognition')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i> <strong>Informasi:</strong><br>
                    Pengaturan ini mengontrol tingkat akurasi pengenalan wajah. Semakin tinggi persentase, semakin ketat validasi wajah.
                </div>

                <div class="row">
                    <!-- Face Similarity Threshold -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="face_similarity_threshold">
                                <i class="fas fa-percentage text-primary"></i> Minimum Persentase Kemiripan Wajah
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       name="face_similarity_threshold" 
                                       id="face_similarity_threshold"
                                       value="<?php echo e(old('face_similarity_threshold', $faceSimilarityThreshold ?? 70)); ?>"
                                       min="50"
                                       max="95"
                                       step="1"
                                       class="form-control"
                                       oninput="updateThresholdDisplay(this.value)"
                                       required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <?php $__errorArgs = ['face_similarity_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Persentase minimum kemiripan wajah yang diperlukan untuk absensi berhasil (50% - 95%)
                            </small>
                        </div>

                        <!-- Visual Indicator -->
                        <div class="mb-4">
                            <label class="font-weight-bold">Tingkat Keamanan:</label>
                            <div class="progress" style="height: 30px;">
                                <div id="threshold-bar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: <?php echo e($faceSimilarityThreshold ?? 70); ?>%"
                                     aria-valuenow="<?php echo e($faceSimilarityThreshold ?? 70); ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <span id="threshold-text" class="font-weight-bold"><?php echo e($faceSimilarityThreshold ?? 70); ?>%</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted">Rendah (50%)</small>
                                <small id="threshold-label" class="font-weight-bold text-primary">
                                    <?php if(($faceSimilarityThreshold ?? 70) < 65): ?>
                                        Rendah
                                    <?php elseif(($faceSimilarityThreshold ?? 70) < 80): ?>
                                        Sedang
                                    <?php else: ?>
                                        Tinggi
                                    <?php endif; ?>
                                </small>
                                <small class="text-muted">Tinggi (95%)</small>
                            </div>
                        </div>

                        <!-- Recommendations -->
                        <div class="alert alert-warning">
                            <strong><i class="fas fa-lightbulb"></i> Rekomendasi:</strong><br>
                            • <strong>60-70%</strong>: Cocok untuk lingkungan dengan pencahayaan bervariasi<br>
                            • <strong>70-80%</strong>: <span class="badge badge-success">Rekomendasi</span> Keseimbangan antara akurasi dan kemudahan<br>
                            • <strong>80-90%</strong>: Keamanan tinggi, memerlukan kondisi pencahayaan baik<br>
                            • <strong>90-95%</strong>: Sangat ketat, hanya untuk keamanan maksimal
                        </div>

                        <!-- Warning -->
                        <div class="alert alert-danger">
                            <strong><i class="fas fa-exclamation-triangle"></i> Perhatian:</strong><br>
                            • Persentase terlalu rendah (<60%) dapat menyebabkan orang lain bisa absen menggunakan wajah yang mirip<br>
                            • Persentase terlalu tinggi (>85%) dapat menyebabkan siswa kesulitan absen meskipun wajah mereka benar<br>
                            • Disarankan menggunakan 70-80% untuk hasil optimal
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>

    <script>
        function updateThresholdDisplay(value) {
            const bar = document.getElementById('threshold-bar');
            const text = document.getElementById('threshold-text');
            const label = document.getElementById('threshold-label');
            
            // Update progress bar
            bar.style.width = value + '%';
            bar.setAttribute('aria-valuenow', value);
            text.textContent = value + '%';
            
            // Update color based on value
            bar.className = 'progress-bar progress-bar-striped progress-bar-animated';
            if (value < 65) {
                bar.classList.add('bg-warning');
                label.textContent = 'Rendah';
                label.className = 'font-weight-bold text-warning';
            } else if (value < 80) {
                bar.classList.add('bg-success');
                label.textContent = 'Sedang';
                label.className = 'font-weight-bold text-success';
            } else {
                bar.classList.add('bg-info');
                label.textContent = 'Tinggi';
                label.className = 'font-weight-bold text-info';
            }
        }
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91fdd17964e43374ae18c674f95cdaa3)): ?>
<?php $attributes = $__attributesOriginal91fdd17964e43374ae18c674f95cdaa3; ?>
<?php unset($__attributesOriginal91fdd17964e43374ae18c674f95cdaa3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91fdd17964e43374ae18c674f95cdaa3)): ?>
<?php $component = $__componentOriginal91fdd17964e43374ae18c674f95cdaa3; ?>
<?php unset($__componentOriginal91fdd17964e43374ae18c674f95cdaa3); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/admin/settings.blade.php ENDPATH**/ ?>