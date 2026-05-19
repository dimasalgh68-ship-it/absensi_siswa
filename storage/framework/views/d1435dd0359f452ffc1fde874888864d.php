<?php if (isset($component)) { $__componentOriginal3346dab4185290893d36302633774906 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3346dab4185290893d36302633774906 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dynamic-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
   <?php $__env->slot('header', null, []); ?> 
    <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('Data Guru')); ?></h1>
   <?php $__env->endSlot(); ?>

  <div class="card shadow mb-4">
    <div class="card-body">
      <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.teacher-component');

$__html = app('livewire')->mount($__name, $__params, 'lw-2383671183-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>
  </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3346dab4185290893d36302633774906)): ?>
<?php $attributes = $__attributesOriginal3346dab4185290893d36302633774906; ?>
<?php unset($__attributesOriginal3346dab4185290893d36302633774906); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3346dab4185290893d36302633774906)): ?>
<?php $component = $__componentOriginal3346dab4185290893d36302633774906; ?>
<?php unset($__componentOriginal3346dab4185290893d36302633774906); ?>
<?php endif; ?>
<?php /**PATH D:\PROJECT-LARAVEL\absensi-siswa\absensi-siswa\resources\views/admin/teachers/index.blade.php ENDPATH**/ ?>