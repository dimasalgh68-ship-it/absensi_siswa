<?php
  $class =
      'inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150';
?>

<!--[if BLOCK]><![endif]--><?php if(!isset($attributes['href'])): ?>
  <button <?php echo e($attributes->merge(['type' => 'submit', 'class' => $class])); ?>>
    <?php echo e($slot); ?>

  </button>
<?php else: ?>
  <a <?php echo e($attributes->merge(['class' => $class])); ?>>
    <?php echo e($slot); ?>

  </a>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/components/danger-button.blade.php ENDPATH**/ ?>