<?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <ul>
        <li><?php echo e($item); ?></li>
    </ul>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <p>No identificado</p>
<?php endif; ?>
<?php /**PATH /home/runner/work/minutes-generator/minutes-generator/resources/views/minutes-generator/partials/list.blade.php ENDPATH**/ ?>