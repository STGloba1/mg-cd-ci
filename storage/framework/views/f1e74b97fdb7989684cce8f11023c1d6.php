<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minutes Generator</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem auto; max-width: 900px; line-height: 1.5; }
        textarea, input { box-sizing: border-box; width: 100%; }
        textarea { min-height: 320px; padding: 1rem; }
        button, .button { background: #111827; border: 0; border-radius: .5rem; color: #fff; cursor: pointer; display: inline-block; padding: .75rem 1rem; text-decoration: none; }
        .error { background: #fee2e2; color: #991b1b; padding: .75rem; }
        .success { background: #dcfce7; color: #166534; padding: .75rem; }
    </style>
</head>
<body>
    <h1>Minutes Generator</h1>

    <?php if(session('success')): ?>
        <p class="success"><?php echo e(session('success')); ?></p>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <p class="error"><?php echo e(session('error')); ?></p>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="error">
            <strong>Revisá la transcripción:</strong>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('minutes-generator.analyze')); ?>">
        <?php echo csrf_field(); ?>

        <label for="transcript_text">Transcripción</label>
        <textarea id="transcript_text" name="transcript_text" required><?php echo e(old('transcript_text')); ?></textarea>

        <p>
            <button type="submit">Generate Minutes</button>
        </p>
    </form>
</body>
</html>
<?php /**PATH /home/runner/work/minutes-generator/minutes-generator/resources/views/minutes-generator/index.blade.php ENDPATH**/ ?>