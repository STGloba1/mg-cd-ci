<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($minute->title); ?></title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem auto; max-width: 900px; line-height: 1.5; }
        form { display: inline; }
        button, .button { background: #111827; border: 0; border-radius: .5rem; color: #fff; cursor: pointer; display: inline-block; margin-right: .5rem; padding: .6rem .9rem; text-decoration: none; }
        section { border-top: 1px solid #e5e7eb; margin-top: 1.25rem; padding-top: 1rem; }
        .error { background: #fee2e2; color: #991b1b; padding: .75rem; }
        .success { background: #dcfce7; color: #166534; padding: .75rem; }
    </style>
</head>
<body>
    <p><a href="<?php echo e(route('minutes-generator.index')); ?>">← Nueva transcripción</a></p>

    <?php if(session('success')): ?>
        <p class="success"><?php echo e(session('success')); ?></p>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <p class="error"><?php echo e(session('error')); ?></p>
    <?php endif; ?>

    <h1><?php echo e($minute->title); ?></h1>
    <p>
        Estado: <strong><?php echo e($minute->status); ?></strong> ·
        Versión: <strong><?php echo e($minute->version); ?></strong> ·
        Confianza: <strong><?php echo e($minute->confidence_score); ?>%</strong>
    </p>
    <p>Fecha: <?php echo e($minute->meeting_date); ?></p>

    <p>
        <?php if($minute->status !== 'approved'): ?>
            <a class="button" href="<?php echo e(route('minutes-generator.edit', $minute)); ?>">Editar</a>

            <form method="POST" action="<?php echo e(route('minutes-generator.approve', $minute)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit">Aprobar</button>
            </form>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('minutes-generator.regenerate', $minute)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit">Regenerar</button>
        </form>
    </p>

    <section>
        <h2>Participantes</h2>
        <?php echo $__env->make('minutes-generator.partials.list', ['items' => $minute->participants], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>

    <section>
        <h2>Resumen ejecutivo</h2>
        <p><?php echo e($minute->executive_summary); ?></p>
    </section>

    <?php if(filled($minute->editable_content)): ?>
        <section>
            <h2>Contenido editable</h2>
            <p style="white-space: pre-wrap;"><?php echo e($minute->editable_content); ?></p>
        </section>
    <?php endif; ?>

    <section>
        <h2>Temas</h2>
        <?php echo $__env->make('minutes-generator.partials.list', ['items' => $minute->topics], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>

    <section>
        <h2>Problemas detectados</h2>
        <?php echo $__env->make('minutes-generator.partials.list', ['items' => $minute->detected_problems], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>

    <section>
        <h2>Soluciones propuestas</h2>
        <?php echo $__env->make('minutes-generator.partials.list', ['items' => $minute->proposed_solutions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>

    <section>
        <h2>Acuerdos</h2>
        <?php echo $__env->make('minutes-generator.partials.list', ['items' => $minute->agreements], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>

    <section>
        <h2>Tareas pendientes</h2>
        <?php $__empty_1 = true; $__currentLoopData = $minute->pending_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article>
                <strong><?php echo e($task['task']); ?></strong><br>
                Responsable: <?php echo e($task['responsible']); ?><br>
                Fecha: <?php echo e($task['due_date']); ?><br>
                Evidencia: <?php echo e($task['evidence']); ?>

            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No identificado</p>
        <?php endif; ?>
    </section>

    <section>
        <h2>Riesgos</h2>
        <?php echo $__env->make('minutes-generator.partials.list', ['items' => $minute->risks], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>

    <section>
        <h2>Próximos pasos</h2>
        <?php echo $__env->make('minutes-generator.partials.list', ['items' => $minute->next_steps], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
</body>
</html>
<?php /**PATH /home/runner/work/minutes-generator/minutes-generator/resources/views/minutes-generator/show.blade.php ENDPATH**/ ?>