<?php if(Auth::check() && Auth::user()->isTeacher): ?>
<?php if (isset($component)) { $__componentOriginal0476d53c7b600aa8e73b9066f8c326d4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0476d53c7b600aa8e73b9066f8c326d4 = $attributes; } ?>
<?php $component = App\View\Components\TeacherLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('teacher-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\TeacherLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php if(isset($title)): ?>
     <?php $__env->slot('title', null, []); ?> <?php echo e($title); ?> <?php $__env->endSlot(); ?>
    <?php endif; ?>
    <?php if(isset($header)): ?>
     <?php $__env->slot('header', null, []); ?> <?php echo e($header); ?> <?php $__env->endSlot(); ?>
    <?php endif; ?>
    <?php echo e($slot); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0476d53c7b600aa8e73b9066f8c326d4)): ?>
<?php $attributes = $__attributesOriginal0476d53c7b600aa8e73b9066f8c326d4; ?>
<?php unset($__attributesOriginal0476d53c7b600aa8e73b9066f8c326d4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0476d53c7b600aa8e73b9066f8c326d4)): ?>
<?php $component = $__componentOriginal0476d53c7b600aa8e73b9066f8c326d4; ?>
<?php unset($__componentOriginal0476d53c7b600aa8e73b9066f8c326d4); ?>
<?php endif; ?>
<?php else: ?>
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
    <?php if(isset($title)): ?>
     <?php $__env->slot('title', null, []); ?> <?php echo e($title); ?> <?php $__env->endSlot(); ?>
    <?php endif; ?>
    <?php if(isset($header)): ?>
     <?php $__env->slot('header', null, []); ?> <?php echo e($header); ?> <?php $__env->endSlot(); ?>
    <?php endif; ?>
    <?php echo e($slot); ?>

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
<?php endif; ?>
<?php /**PATH D:\PROJECT-LARAVEL\absensi-siswa\absensi-siswa\resources\views/components/dynamic-layout.blade.php ENDPATH**/ ?>