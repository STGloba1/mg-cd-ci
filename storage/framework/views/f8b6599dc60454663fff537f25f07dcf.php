<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingresar - Minutes Generator</title>
    <style>
        :root { --bg: #0f172a; --card: rgba(255,255,255,.94); --muted: #64748b; --text: #0f172a; --primary: #2563eb; --primary-dark: #1d4ed8; --danger-bg: #fee2e2; --danger: #991b1b; --success-bg: #dcfce7; --success: #166534; }
        * { box-sizing: border-box; }
        body { align-items: center; background: radial-gradient(circle at top left, rgba(37,99,235,.45), transparent 32rem), radial-gradient(circle at bottom right, rgba(20,184,166,.35), transparent 30rem), linear-gradient(135deg, #020617 0%, var(--bg) 55%, #111827 100%); color: var(--text); display: flex; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; justify-content: center; line-height: 1.5; margin: 0; min-height: 100vh; padding: 24px; }
        .shell { display: grid; gap: 28px; grid-template-columns: 1.05fr .95fr; max-width: 1040px; width: 100%; }
        .hero { color: white; padding: 36px 10px; }
        .badge { background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2); border-radius: 999px; display: inline-block; font-size: 13px; font-weight: 700; letter-spacing: .04em; margin-bottom: 24px; padding: 8px 12px; text-transform: uppercase; }
        h1 { font-size: clamp(2.4rem, 6vw, 4.5rem); letter-spacing: -.06em; line-height: .95; margin: 0 0 20px; }
        .hero p { color: #cbd5e1; font-size: 18px; max-width: 560px; margin: 0; }
        .card { backdrop-filter: blur(18px); background: var(--card); border: 1px solid rgba(255,255,255,.55); border-radius: 28px; box-shadow: 0 30px 90px rgba(2,6,23,.35); padding: 34px; }
        .card h2 { font-size: 28px; margin: 0 0 8px; }
        .card .subtitle { color: var(--muted); margin: 0 0 26px; }
        label { display: block; font-size: 14px; font-weight: 700; margin-bottom: 8px; }
        input { background: #fff; border: 1px solid #cbd5e1; border-radius: 14px; font: inherit; margin-bottom: 18px; padding: 13px 14px; transition: border-color .2s, box-shadow .2s; width: 100%; }
        input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37,99,235,.14); outline: none; }
        button { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: 0; border-radius: 14px; color: white; cursor: pointer; font: inherit; font-weight: 800; padding: 14px 16px; width: 100%; }
        .error, .success { border-radius: 14px; margin-bottom: 18px; padding: 12px 14px; }
        .error { background: var(--danger-bg); color: var(--danger); }
        .success { background: var(--success-bg); color: var(--success); }
        .footnote { color: var(--muted); font-size: 13px; margin: 18px 0 0; text-align: center; }
        @media (max-width: 820px) { .shell { grid-template-columns: 1fr; } .hero { padding: 8px 4px 0; } .card { padding: 24px; } }
    </style>
</head>
<body>
    <main class="shell">
        <section class="hero" aria-label="Presentación">
            <span class="badge"><?php echo e(config('services.minutes_generator.organization_name')); ?> · Acceso privado</span>
            <h1>Minutes Generator</h1>
            <p>Generá minutas ejecutivas, acuerdos y tareas accionables desde transcripciones, con control de versiones y aprobación.</p>
        </section>
        <section class="card" aria-label="Inicio de sesión">
            <h2>Iniciar sesión</h2>
            <p class="subtitle">Ingresá con el usuario configurado para este entorno.</p>
            <?php if(session('success')): ?><div class="success"><?php echo e(session('success')); ?></div><?php endif; ?>
            <?php if($errors->any()): ?><div class="error"><?php echo e($errors->first()); ?></div><?php endif; ?>
            <form method="POST" action="<?php echo e(route('minutes-generator.login.store')); ?>">
                <?php echo csrf_field(); ?>
                <label for="username">Usuario</label>
                <input id="username" name="username" value="<?php echo e(old('username')); ?>" autocomplete="username" required autofocus>
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" autocomplete="current-password" required>
                <button type="submit">Entrar</button>
            </form>
            <p class="footnote">Acceso Limitado a usuarios autorizados.</p>
        </section>
    </main>
</body>
</html>
<?php /**PATH /home/runner/work/minutes-generator/minutes-generator/resources/views/minutes-generator/login.blade.php ENDPATH**/ ?>