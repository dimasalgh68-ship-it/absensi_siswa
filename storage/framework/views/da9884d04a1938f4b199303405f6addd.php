<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Styles -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
            max-width: 100vw;
        }
        html {
            overflow-x: hidden;
            max-width: 100vw;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
        .app-gradient {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            min-height: 100vh;
        }
        .header-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }
        .content-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }
        .page-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-900 overflow-x-hidden">
    <?php if (isset($component)) { $__componentOriginalff9615640ecc9fe720b9f7641382872b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalff9615640ecc9fe720b9f7641382872b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.banner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalff9615640ecc9fe720b9f7641382872b)): ?>
<?php $attributes = $__attributesOriginalff9615640ecc9fe720b9f7641382872b; ?>
<?php unset($__attributesOriginalff9615640ecc9fe720b9f7641382872b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalff9615640ecc9fe720b9f7641382872b)): ?>
<?php $component = $__componentOriginalff9615640ecc9fe720b9f7641382872b; ?>
<?php unset($__componentOriginalff9615640ecc9fe720b9f7641382872b); ?>
<?php endif; ?>

    <div class="app-gradient relative overflow-x-hidden">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-blue-400 opacity-10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-indigo-400 opacity-10 rounded-full blur-3xl pointer-events-none"></div>

        <nav class="sticky top-0 z-50">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('navigation-menu');

$__html = app('livewire')->mount($__name, $__params, 'lw-4262692733-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </nav>

        <!-- Page Heading -->
        <?php if(isset($header)): ?>
            <header class="header-glass mb-6">
                <div class="mx-auto max-w-7xl px-6 py-8">
                    <div class="flex items-center space-x-4">
                        <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                                <?php echo e($header); ?>

                            </h2>
                            <p class="text-sm text-slate-500 font-medium">Layanan Absensi Siswa Online</p>
                        </div>
                    </div>
                </div>
            </header>
        <?php endif; ?>

        <!-- Page Content -->
        <main class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 pb-24">
            <div class="page-fade-in">
                <?php echo e($slot); ?>

            </div>
        </main>
    </div>

    <?php echo $__env->yieldPushContent('modals'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.onPageExpired((response, message) => {
                window.location.reload();
            });
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    <?php if(auth()->guard()->check()): ?>
        <?php if(!Auth::user()->isAdmin): ?>
            <?php if (isset($component)) { $__componentOriginal6adbe3b652407eed1f1583bc7f2e3b54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6adbe3b652407eed1f1583bc7f2e3b54 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.mobile-bottom-nav','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mobile-bottom-nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6adbe3b652407eed1f1583bc7f2e3b54)): ?>
<?php $attributes = $__attributesOriginal6adbe3b652407eed1f1583bc7f2e3b54; ?>
<?php unset($__attributesOriginal6adbe3b652407eed1f1583bc7f2e3b54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6adbe3b652407eed1f1583bc7f2e3b54)): ?>
<?php $component = $__componentOriginal6adbe3b652407eed1f1583bc7f2e3b54; ?>
<?php unset($__componentOriginal6adbe3b652407eed1f1583bc7f2e3b54); ?>
<?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/layouts/app.blade.php ENDPATH**/ ?>