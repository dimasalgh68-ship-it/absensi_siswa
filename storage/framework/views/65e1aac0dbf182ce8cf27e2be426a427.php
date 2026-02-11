<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta name="theme-color" content="#1BA1E2">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <title><?php echo e(config('app.name', 'Laravel')); ?></title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,900&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

  <!-- Styles -->
  <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

  
  <style>
    /* Exact background from image */
    body {
      background: #f0f4f8;
      min-height: 100vh;
      min-height: -webkit-fill-available;
      font-family: 'Outfit', sans-serif;
    }
    
    /* Prevent zoom on input focus (iOS) */
    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="tel"] {
      font-size: 16px !important;
    }
  </style>
</head>

<body class="antialiased">
  <div class="min-h-screen flex items-center justify-center p-6 md:p-12">
    <?php echo e($slot); ?>

  </div>

  <?php if (isset($component)) { $__componentOriginal232a39a7644340ed20810a0183d55909 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal232a39a7644340ed20810a0183d55909 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sigsegv-core-dumped','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sigsegv-core-dumped'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal232a39a7644340ed20810a0183d55909)): ?>
<?php $attributes = $__attributesOriginal232a39a7644340ed20810a0183d55909; ?>
<?php unset($__attributesOriginal232a39a7644340ed20810a0183d55909); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal232a39a7644340ed20810a0183d55909)): ?>
<?php $component = $__componentOriginal232a39a7644340ed20810a0183d55909; ?>
<?php unset($__componentOriginal232a39a7644340ed20810a0183d55909); ?>
<?php endif; ?>

  <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

  <script>
    document.addEventListener('livewire:initialized', () => {
      Livewire.onPageExpired((response, message) => {
        window.location.reload();
      });
    });
  </script>
</body>

</html>
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/layouts/guest.blade.php ENDPATH**/ ?>