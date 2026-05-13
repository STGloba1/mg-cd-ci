<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minutes Generator</title>
    <style>
        :root { --bg: #f1f5f9; --card: #fff; --text: #0f172a; --muted: #64748b; --primary: #2563eb; --danger: #991b1b; --success: #166534; }
        * { box-sizing: border-box; }
        body { background: var(--bg); color: var(--text); font-family: Inter, ui-sans-serif, system-ui, sans-serif; margin: 0; line-height: 1.5; }
        .page { margin: 0 auto; max-width: 1080px; padding: 28px; }
        header { align-items: center; display: flex; justify-content: space-between; gap: 16px; margin-bottom: 24px; }
        .eyebrow { color: var(--primary); font-size: 13px; font-weight: 800; letter-spacing: .08em; margin: 0 0 8px; text-transform: uppercase; }
        h1 { font-size: clamp(2rem, 5vw, 3.7rem); letter-spacing: -.05em; line-height: 1; margin: 0; }
        .subtitle { color: var(--muted); font-size: 18px; margin: 12px 0 0; max-width: 720px; }
        .logout { background: #0f172a; border: 0; border-radius: 999px; color: #fff; cursor: pointer; padding: 10px 14px; }
        .card { background: var(--card); border: 1px solid #e2e8f0; border-radius: 24px; box-shadow: 0 24px 60px rgba(15,23,42,.08); padding: 24px; }
        label { display: block; font-weight: 800; margin-bottom: 10px; }
        textarea { border: 1px solid #cbd5e1; border-radius: 18px; font: inherit; min-height: 360px; padding: 18px; resize: vertical; width: 100%; }
        textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37,99,235,.12); outline: none; }
        .actions { align-items: center; display: flex; gap: 12px; justify-content: space-between; margin-top: 16px; }
        .hint { color: var(--muted); font-size: 14px; margin: 0; }
        .submit { background: linear-gradient(135deg, #2563eb, #1d4ed8); border: 0; border-radius: 14px; color: #fff; cursor: pointer; font: inherit; font-weight: 800; padding: 13px 18px; }
        .error, .success { border-radius: 14px; margin-bottom: 18px; padding: 12px 14px; }
        .error { background: #fee2e2; color: var(--danger); }
        .success { background: #dcfce7; color: var(--success); }
        @media (max-width: 720px) { .page { padding: 18px; } header, .actions { align-items: stretch; flex-direction: column; } .logout, .submit { width: 100%; } }
    </style>
</head>
<body>
    <main class="page">
        <header>
            <div>
                <p class="eyebrow">Panel privado</p>
                <h1>Minutes Generator</h1>
                <p class="subtitle">Pegá una transcripción y generá una minuta estructurada lista para editar, aprobar y exportar a PDF.</p>
            </div>
            <form method="POST" action="<?php echo e(route('minutes-generator.logout')); ?>">
                <?php echo csrf_field(); ?>
                <button class="logout" type="submit">Cerrar sesión</button>
            </form>
        </header>

        <?php if(session('success')): ?><p class="success"><?php echo e(session('success')); ?></p><?php endif; ?>
        <?php if(session('error')): ?><p class="error"><?php echo e(session('error')); ?></p><?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="error">
                <strong>Revisá la transcripción:</strong>
                <ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            </div>
        <?php endif; ?>

        <section class="card">
            <form method="POST" action="<?php echo e(route('minutes-generator.analyze')); ?>">
                <?php echo csrf_field(); ?>
                <label for="transcript_text">Transcripción</label>
                <textarea id="transcript_text" name="transcript_text" required placeholder="Pegá acá la transcripción completa de la reunión..."><?php echo e(old('transcript_text')); ?></textarea>
                <div class="actions">
                    <p class="hint">Mínimo 100 caracteres. La IA tratará la transcripción como datos, no como instrucciones.</p>
                    <button class="submit" type="submit">Generar minuta</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
<?php /**PATH /home/runner/work/minutes-generator/minutes-generator/resources/views/minutes-generator/index.blade.php ENDPATH**/ ?>