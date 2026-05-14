<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel privado · Minutes Generator</title>
    <style>
        :root {
            --bg: #f1f5f9;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --primary: <?php echo e($brandColor); ?>;
            --danger: #991b1b;
            --success: #166534;
        }
        * { box-sizing: border-box; }
        body { background: var(--bg); color: var(--text); font-family: Inter, ui-sans-serif, system-ui, sans-serif; margin: 0; line-height: 1.5; }
        a { color: inherit; }
        .page { margin: 0 auto; max-width: 1180px; padding: 28px; }
        .topbar { align-items: center; display: flex; justify-content: space-between; gap: 16px; margin-bottom: 26px; }
        .brand { align-items: center; display: flex; gap: 12px; }
        .logo { align-items: center; background: linear-gradient(135deg, var(--primary), #0f172a); border-radius: 18px; color: #fff; display: flex; font-weight: 900; height: 48px; justify-content: center; width: 48px; }
        .eyebrow { color: var(--primary); font-size: 12px; font-weight: 900; letter-spacing: .08em; margin: 0; text-transform: uppercase; }
        h1 { font-size: clamp(2rem, 5vw, 3.5rem); letter-spacing: -.05em; line-height: 1; margin: 0; }
        h2 { font-size: 20px; margin: 0 0 14px; }
        .subtitle { color: var(--muted); font-size: 17px; margin: 10px 0 0; max-width: 760px; }
        .logout { background: #0f172a; border: 0; border-radius: 999px; color: #fff; cursor: pointer; padding: 10px 14px; }
        .hero { background: radial-gradient(circle at top right, color-mix(in srgb, var(--primary) 18%, transparent), transparent 26rem), var(--card); border: 1px solid var(--border); border-radius: 28px; box-shadow: 0 24px 60px rgba(15,23,42,.08); margin-bottom: 20px; padding: 26px; }
        .stats { display: grid; gap: 14px; grid-template-columns: repeat(4, 1fr); margin: 20px 0; }
        .stat { background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 18px; }
        .stat span { color: var(--muted); display: block; font-size: 13px; font-weight: 800; text-transform: uppercase; }
        .stat strong { display: block; font-size: 32px; line-height: 1; margin-top: 8px; }
        .grid { display: grid; gap: 20px; grid-template-columns: 1.05fr .95fr; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 24px; box-shadow: 0 18px 50px rgba(15,23,42,.06); padding: 22px; }
        label { display: block; font-weight: 800; margin-bottom: 10px; }
        textarea, input, select { border: 1px solid #cbd5e1; border-radius: 14px; font: inherit; padding: 12px 14px; width: 100%; }
        textarea { min-height: 360px; resize: vertical; }
        textarea:focus, input:focus, select:focus { border-color: var(--primary); box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary) 16%, transparent); outline: none; }
        .actions { align-items: center; display: flex; gap: 12px; justify-content: space-between; margin-top: 16px; }
        .hint, .counter { color: var(--muted); font-size: 14px; margin: 0; }
        .submit, .button { background: linear-gradient(135deg, var(--primary), #1d4ed8); border: 0; border-radius: 14px; color: #fff; cursor: pointer; display: inline-block; font: inherit; font-weight: 800; padding: 13px 18px; text-decoration: none; }
        .submit[disabled] { cursor: wait; opacity: .72; }
        .processing-panel {
            align-items: center;
            background: #eff6ff;
            border: 1px solid color-mix(in srgb, var(--primary) 28%, #bfdbfe);
            border-radius: 18px;
            display: none;
            gap: 14px;
            margin-top: 16px;
            padding: 14px;
        }
        .processing-panel.active { display: flex; }
        .processing-icon {
            align-items: center;
            animation: pulse-ring 1.25s ease-in-out infinite;
            background: linear-gradient(135deg, var(--primary), #1d4ed8);
            border-radius: 999px;
            color: #fff;
            display: flex;
            flex: 0 0 auto;
            font-size: 20px;
            font-weight: 900;
            height: 52px;
            justify-content: center;
            width: 52px;
        }
        .processing-copy strong { display: block; }
        .processing-copy span { color: var(--muted); display: block; font-size: 14px; }
        @keyframes pulse-ring {
            0%, 100% { box-shadow: 0 0 0 0 color-mix(in srgb, var(--primary) 35%, transparent); transform: scale(1); }
            50% { box-shadow: 0 0 0 10px color-mix(in srgb, var(--primary) 0%, transparent); transform: scale(1.04); }
        }
        .error, .success { border-radius: 14px; margin-bottom: 18px; padding: 12px 14px; }
        .error { background: #fee2e2; color: var(--danger); }
        .success { background: #dcfce7; color: var(--success); }
        .filters { display: grid; gap: 10px; grid-template-columns: 1fr 150px auto; margin-bottom: 14px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border-bottom: 1px solid var(--border); padding: 11px 8px; text-align: left; vertical-align: top; }
        th { color: var(--muted); font-size: 12px; text-transform: uppercase; }
        .status { border-radius: 999px; display: inline-block; font-size: 12px; font-weight: 900; padding: 4px 9px; text-transform: uppercase; }
        .status.draft { background: #fef3c7; color: #92400e; }
        .status.approved { background: #dcfce7; color: #166534; }
        .empty { color: var(--muted); padding: 22px 0; text-align: center; }
        .pagination { color: var(--muted); font-size: 13px; margin-top: 12px; }
        @media (max-width: 960px) { .grid, .stats { grid-template-columns: 1fr; } .filters { grid-template-columns: 1fr; } .topbar, .actions { align-items: stretch; flex-direction: column; } .logout, .submit, .button { width: 100%; } .page { padding: 18px; } }
    </style>
</head>
<body>
    <main class="page">
        <div class="topbar">
            <div class="brand">
                <div class="logo">MG</div>
                <div>
                    <p class="eyebrow"><?php echo e($organizationName); ?></p>
                    <strong>Minutes Generator</strong>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('minutes-generator.logout')); ?>">
                <?php echo csrf_field(); ?>
                <button class="logout" type="submit">Cerrar sesión</button>
            </form>
        </div>

        <section class="hero">
            <p class="eyebrow">Panel privado</p>
            <h1>Gestión profesional de minutas</h1>
            <p class="subtitle">Generá, revisá, aprobá y exportá minutas ejecutivas desde transcripciones con trazabilidad básica y control de versiones.</p>
        </section>

        <section class="stats" aria-label="Indicadores">
            <article class="stat"><span>Total</span><strong><?php echo e($stats['total']); ?></strong></article>
            <article class="stat"><span>Borradores</span><strong><?php echo e($stats['draft']); ?></strong></article>
            <article class="stat"><span>Aprobadas</span><strong><?php echo e($stats['approved']); ?></strong></article>
            <article class="stat"><span>Mayor versión</span><strong><?php echo e($stats['versions']); ?></strong></article>
        </section>

        <?php if(session('success')): ?><p class="success"><?php echo e(session('success')); ?></p><?php endif; ?>
        <?php if(session('error')): ?><p class="error"><?php echo e(session('error')); ?></p><?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="error">
                <strong>Revisá la transcripción:</strong>
                <ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            </div>
        <?php endif; ?>

        <div class="grid">
            <section class="card">
                <h2>Nueva minuta</h2>
                <form id="analysis-form" method="POST" action="<?php echo e(route('minutes-generator.analyze')); ?>">
                    <?php echo csrf_field(); ?>
                    <label for="transcript_text">Transcripción</label>
                    <textarea id="transcript_text" name="transcript_text" required maxlength="<?php echo e($maxTranscriptLength); ?>" placeholder="Pegá acá la transcripción completa de la reunión..."><?php echo e(old('transcript_text')); ?></textarea>
                    <div class="actions">
                        <div>
                            <p class="hint">Mínimo 100 caracteres. La IA tratará la transcripción como datos, no como instrucciones.</p>
                            <p id="counter" class="counter">0 / <?php echo e(number_format($maxTranscriptLength)); ?> caracteres</p>
                        </div>
                        <button id="submit-button" class="submit" type="submit">Generar minuta</button>
                    </div>

                    <div id="processing-panel" class="processing-panel" role="status" aria-live="polite">
                        <div class="processing-icon" aria-hidden="true">AI</div>
                        <div class="processing-copy">
                            <strong id="processing-title">Procesando transcripción con IA</strong>
                            <span id="processing-detail">Validando contenido y preparando el análisis estructurado...</span>
                        </div>
                    </div>
                </form>
            </section>

            <section class="card">
                <h2>Minutas recientes</h2>
                <form class="filters" method="GET" action="<?php echo e(route('minutes-generator.index')); ?>">
                    <input name="search" value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="Buscar por título, resumen o fecha">
                    <select name="status">
                        <option value="">Todos</option>
                        <option value="draft" <?php if(($filters['status'] ?? '') === 'draft'): echo 'selected'; endif; ?>>Borradores</option>
                        <option value="approved" <?php if(($filters['status'] ?? '') === 'approved'): echo 'selected'; endif; ?>>Aprobadas</option>
                    </select>
                    <button class="button" type="submit">Filtrar</button>
                </form>

                <?php if($minutes->count() > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Minuta</th>
                                <th>Estado</th>
                                <th>Versión</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $minutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $minute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($minute->title); ?></strong><br>
                                        <small><?php echo e($minute->meeting_date); ?> · <?php echo e($minute->created_at->format('Y-m-d H:i')); ?></small>
                                    </td>
                                    <td><span class="status <?php echo e($minute->status); ?>"><?php echo e($minute->status); ?></span></td>
                                    <td><?php echo e($minute->version); ?></td>
                                    <td><a href="<?php echo e(route('minutes-generator.show', $minute)); ?>">Ver</a></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <p class="pagination">Mostrando <?php echo e($minutes->firstItem()); ?>-<?php echo e($minutes->lastItem()); ?> de <?php echo e($minutes->total()); ?> minutas.</p>
                    <?php echo e($minutes->links()); ?>

                <?php else: ?>
                    <p class="empty">Todavía no hay minutas para estos filtros.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <script>
        const textarea = document.getElementById('transcript_text');
        const counter = document.getElementById('counter');
        const form = document.getElementById('analysis-form');
        const button = document.getElementById('submit-button');
        const processingPanel = document.getElementById('processing-panel');
        const processingDetail = document.getElementById('processing-detail');
        const max = Number(textarea.getAttribute('maxlength'));
        const processingMessages = [
            'Validando longitud y estructura de la transcripción...',
            'Enviando la transcripción al backend seguro...',
            'La IA está identificando temas, acuerdos y tareas...',
            'Organizando la minuta y calculando nivel de confianza...',
            'Guardando la versión generada en la base de datos...'
        ];

        function updateCounter() {
            counter.textContent = `${textarea.value.length.toLocaleString()} / ${max.toLocaleString()} caracteres`;
        }

        textarea.addEventListener('input', updateCounter);
        form.addEventListener('submit', () => {
            let step = 0;
            button.disabled = true;
            button.textContent = 'Procesando...';
            processingPanel.classList.add('active');
            processingDetail.textContent = processingMessages[step];

            window.setInterval(() => {
                step = Math.min(step + 1, processingMessages.length - 1);
                processingDetail.textContent = processingMessages[step];
            }, 1800);
        });
        updateCounter();
    </script>
</body>
</html>
<?php /**PATH /home/runner/work/minutes-generator/minutes-generator/resources/views/minutes-generator/index.blade.php ENDPATH**/ ?>