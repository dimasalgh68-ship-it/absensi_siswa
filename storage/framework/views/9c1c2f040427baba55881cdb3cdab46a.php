<?php
  $date = Carbon\Carbon::now();
?>
<div>
<?php if (! $__env->hasRenderedOnce('9c441bd8-4d00-459b-86e2-5de155e5eebf')): $__env->markAsRenderedOnce('9c441bd8-4d00-459b-86e2-5de155e5eebf');
$__env->startPush('styles'); ?>
  <style>
    #attendanceChart {
      max-height: 350px;
    }
  </style>
<?php $__env->stopPush(); endif; ?>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 border-0" style="border-left: 4px solid #10b981 !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir (Hari Ini)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($presentCount); ?></div>
                        <div class="text-xs text-muted mt-1">Terlambat: <?php echo e($lateCount); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 bg-success-light rounded-circle" style="background: rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-user-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 border-0" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Izin / Sakit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($excusedCount + $sickCount); ?></div>
                        <div class="text-xs text-muted mt-1">Izin: <?php echo e($excusedCount); ?> | Sakit: <?php echo e($sickCount); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 bg-primary-light rounded-circle" style="background: rgba(59, 130, 246, 0.1);">
                            <i class="fas fa-envelope-open-text fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2 border-0" style="border-left: 4px solid #ef4444 !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tanpa Keterangan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($absentCount); ?></div>
                        <div class="text-xs text-muted mt-1">Belum melakukan absensi</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 bg-danger-light rounded-circle" style="background: rgba(239, 68, 68, 0.1);">
                            <i class="fas fa-user-times fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 border-0" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Siswa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($employeesCount); ?></div>
                        <div class="text-xs text-muted mt-1">Terdaftar di sistem</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 bg-warning-light rounded-circle" style="background: rgba(245, 158, 11, 0.1);">
                            <i class="fas fa-users fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--[if BLOCK]><![endif]--><?php if($shift && $clockInDeadline): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary" style="width: 80px; height: 80px;">
                            <i class="fas fa-clock fa-2x text-white"></i>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h5 class="font-weight-bold mb-2">Jadwal Absensi Hari Ini</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Shift: <strong><?php echo e($shift->name); ?></strong> (<?php echo e($shift->start_time); ?> - <?php echo e($shift->end_time); ?>)
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="mr-4">
                                <small class="text-muted d-block">Buka Absen</small>
                                <strong class="text-success"><?php echo e($clockInOpenTime->format('H:i')); ?> WIB</strong>
                            </div>
                            <div class="mr-4">
                                <small class="text-muted d-block">Deadline</small>
                                <strong class="text-danger"><?php echo e($clockInDeadline->format('H:i')); ?> WIB</strong>
                            </div>
                            <div>
                                <small class="text-muted d-block">Jam Keluar</small>
                                <strong class="text-primary"><?php echo e($clockOutTime->format('H:i')); ?> WIB</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div id="admin-countdown-display" class="display-4 font-weight-bold text-primary mb-2" style="font-family: 'Courier New', monospace;">--:--:--</div>
                        <p id="admin-countdown-label" class="text-muted mb-0 font-weight-bold">Sisa Waktu</p>
                        <div class="progress mt-2" style="height: 6px;">
                            <div id="admin-countdown-progress" class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Admin Countdown Timer
    const now = new Date().getTime();
    const clockInOpenTime = new Date('<?php echo e($clockInOpenTime->format('Y-m-d H:i:s')); ?>').getTime();
    const clockInDeadline = new Date('<?php echo e($clockInDeadline->format('Y-m-d H:i:s')); ?>').getTime();
    const clockOutTime = new Date('<?php echo e($clockOutTime->format('Y-m-d H:i:s')); ?>').getTime();
    
    let targetTime;
    let labelText;
    let progressColor;
    
    // Determine which countdown to show
    if (now < clockInOpenTime) {
        targetTime = clockInOpenTime;
        labelText = 'Dibuka Dalam';
        progressColor = 'bg-secondary';
    } else if (now < clockInDeadline) {
        targetTime = clockInDeadline;
        labelText = 'Deadline Dalam';
        progressColor = 'bg-danger';
    } else if (now < clockOutTime) {
        targetTime = clockOutTime;
        labelText = 'Jam Keluar Dalam';
        progressColor = 'bg-primary';
    } else {
        targetTime = clockInOpenTime + (24 * 60 * 60 * 1000); // Tomorrow
        labelText = 'Besok Dibuka Dalam';
        progressColor = 'bg-info';
    }
    
    const startOfDay = new Date('<?php echo e(\Carbon\Carbon::today()->format('Y-m-d H:i:s')); ?>').getTime();
    const totalDuration = targetTime - startOfDay;
    
    function updateAdminCountdown() {
        const now = new Date().getTime();
        const distance = targetTime - now;
        
        const display = document.getElementById('admin-countdown-display');
        const label = document.getElementById('admin-countdown-label');
        const progress = document.getElementById('admin-countdown-progress');
        
        if (!display || !label || !progress) return;
        
        if (distance < 0) {
            display.textContent = 'MEMUAT...';
            setTimeout(() => window.location.reload(), 2000);
            return;
        }
        
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        display.textContent = 
            String(hours).padStart(2, '0') + ':' + 
            String(minutes).padStart(2, '0') + ':' + 
            String(seconds).padStart(2, '0');
        
        label.textContent = labelText;
        
        const percentage = Math.min(Math.max((distance / totalDuration) * 100, 0), 100);
        progress.style.width = percentage + '%';
        progress.className = 'progress-bar ' + progressColor;
    }
    
    updateAdminCountdown();
    setInterval(updateAdminCountdown, 1000);
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->


  <div class="mb-4 overflow-x-scroll rounded">
    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 ">
      <thead class="bg-gray-50 dark:bg-gray-900">
        <tr>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            <?php echo e(__('Name')); ?>

          </th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            <?php echo e(__('NISN')); ?>

          </th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            <?php echo e(__('Division')); ?>

          </th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            <?php echo e(__('Job Title')); ?>

          </th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            <?php echo e(__('Shift')); ?>

          </th>
          <th scope="col"
            class="text-nowrap border border-gray-300 px-1 py-3 text-center text-xs font-medium text-gray-500 dark:border-gray-600 dark:text-gray-300">
            Status
          </th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            <?php echo e(__('Time In')); ?>

          </th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            <?php echo e(__('Time Out')); ?>

          </th>
          <th scope="col" class="relative">
            <span class="sr-only">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        <?php
          $class = 'px-4 py-3 text-sm font-medium text-gray-900 dark:text-white';
        ?>
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $attendance = $employee->attendance;
            $timeIn = $attendance ? $attendance?->time_in?->format('H:i:s') : null;
            $timeOut = $attendance ? $attendance?->time_out?->format('H:i:s') : null;
            $isWeekend = $date->isWeekend();
            $status = ($attendance ?? [
                'status' => $isWeekend || !$date->isPast() ? '-' : 'absent',
            ])['status'];
            switch ($status) {
                case 'present':
                    $shortStatus = 'H';
                    $bgColor =
                        'bg-green-200 dark:bg-green-800 hover:bg-green-300 dark:hover:bg-green-700 border border-green-300 dark:border-green-600';
                    break;
                case 'late':
                    $shortStatus = 'T';
                    $bgColor =
                        'bg-amber-200 dark:bg-amber-800 hover:bg-amber-300 dark:hover:bg-amber-700 border border-amber-300 dark:border-amber-600';
                    break;
                case 'excused':
                    $shortStatus = 'I';
                    $bgColor =
                        'bg-blue-200 dark:bg-blue-800 hover:bg-blue-300 dark:hover:bg-blue-700 border border-blue-300 dark:border-blue-600';
                    break;
                case 'sick':
                    $shortStatus = 'S';
                    $bgColor = 'hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600';
                    break;
                case 'absent':
                    $shortStatus = 'A';
                    $bgColor =
                        'bg-red-200 dark:bg-red-800 hover:bg-red-300 dark:hover:bg-red-700 border border-red-300 dark:border-red-600';
                    break;
                default:
                    $shortStatus = '-';
                    $bgColor = 'hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600';
                    break;
            }
          ?>
          <tr wire:key="<?php echo e($employee->id); ?>" class="group">
            
            <td class="<?php echo e($class); ?> text-nowrap group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
              <?php echo e($employee->name); ?>

            </td>
            <td class="<?php echo e($class); ?> group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
              <?php echo e($employee->nisn); ?>

            </td>
            <td class="<?php echo e($class); ?> text-nowrap group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
              <?php echo e($employee->division?->name ?? '-'); ?>

            </td>
            <td class="<?php echo e($class); ?> text-nowrap group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
              <?php echo e($employee->jobTitle?->name ?? '-'); ?>

            </td>
            <td class="<?php echo e($class); ?> text-nowrap group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
              <?php echo e($attendance->shift?->name ?? '-'); ?>

            </td>

            
            <td
              class="<?php echo e($bgColor); ?> text-nowrap px-1 py-3 text-center text-sm font-medium text-gray-900 dark:text-white">
              <?php echo e(__($status)); ?>

            </td>

            
            <td class="<?php echo e($class); ?> group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
              <?php echo e($timeIn ?? '-'); ?>

            </td>
            <td class="<?php echo e($class); ?> group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
              <?php echo e($timeOut ?? '-'); ?>

            </td>

            
            <td
              class="cursor-pointer text-center text-sm font-medium text-gray-900 group-hover:bg-gray-100 dark:text-white dark:group-hover:bg-gray-700">
              <div class="flex items-center justify-center gap-3">
                <!--[if BLOCK]><![endif]--><?php if($attendance && ($attendance->attachment || $attendance->note || $attendance->lat_lng)): ?>
                  <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','wire:click' => 'show('.e($attendance->id).')','onclick' => 'setLocation('.e($attendance->latitude ?? 0).', '.e($attendance->longitude ?? 0).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'show('.e($attendance->id).')','onclick' => 'setLocation('.e($attendance->latitude ?? 0).', '.e($attendance->longitude ?? 0).')']); ?>
                    <?php echo e(__('Detail')); ?>

                   <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                <?php else: ?>
                  -
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
              </div>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
      </tbody>
    </table>
  </div>
  <?php echo e($employees->links()); ?>


  <?php if (isset($component)) { $__componentOriginal323973f2b7c9b279426a00e14a9be4bd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal323973f2b7c9b279426a00e14a9be4bd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.attendance-detail-modal','data' => ['currentAttendance' => $currentAttendance]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('attendance-detail-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current-attendance' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentAttendance)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal323973f2b7c9b279426a00e14a9be4bd)): ?>
<?php $attributes = $__attributesOriginal323973f2b7c9b279426a00e14a9be4bd; ?>
<?php unset($__attributesOriginal323973f2b7c9b279426a00e14a9be4bd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal323973f2b7c9b279426a00e14a9be4bd)): ?>
<?php $component = $__componentOriginal323973f2b7c9b279426a00e14a9be4bd; ?>
<?php unset($__componentOriginal323973f2b7c9b279426a00e14a9be4bd); ?>
<?php endif; ?>
  <?php echo $__env->yieldPushContent('attendance-detail-scripts'); ?>
</div>
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/livewire/admin/dashboard.blade.php ENDPATH**/ ?>