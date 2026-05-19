<?php
  $class =
      'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150';
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
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/components/secondary-button.blade.php ENDPATH**/ ?>