<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (in_array($analysis->status, ['pending', 'processing'], true))
        <meta http-equiv="refresh" content="5">
    @endif
    <title>Estado de generación · Minutes Generator</title>
    <style>
        :root { --bg: #f1f5f9; --card: #ffffff; --text: #0f172a; --muted: #64748b; --border: #e2e8f0; --primary: {{ $brandColor }}; --danger: #991b1b; --success: #166534; }
        * { box-sizing: border-box; }
        body { background: var(--bg); color: var(--text); font-family: Inter, ui-sans-serif, system-ui, sans-serif; margin: 0; line-height: 1.5; }
        .page { margin: 0 auto; max-width: 860px; padding: 28px; }
        .topbar { align-items: center; display: flex; justify-content: space-between; gap: 16px; margin-bottom: 26px; }
        .brand { align-items: center; display: flex; gap: 12px; }
        .logo { align-items: center; background: linear-gradient(135deg, var(--primary), #0f172a); border-radius: 18px; color: #fff; display: flex; font-weight: 900; height: 48px; justify-content: center; width: 48px; }
        .eyebrow { color: var(--primary); font-size: 12px; font-weight: 900; letter-spacing: .08em; margin: 0; text-transform: uppercase; }
        h1 { font-size: clamp(2rem, 5vw, 3.2rem); letter-spacing: -.05em; line-height: 1; margin: 0; }
        .card { background: radial-gradient(circle at top right, color-mix(in srgb, var(--primary) 16%, transparent), transparent 22rem), var(--card); border: 1px solid var(--border); border-radius: 28px; box-shadow: 0 24px 60px rgba(15,23,42,.08); padding: 26px; }
        .status { border-radius: 999px; display: inline-block; font-size: 12px; font-weight: 900; margin: 20px 0; padding: 6px 12px; text-transform: uppercase; }
        .pending, .processing { background: #dbeafe; color: #1d4ed8; }
        .completed { background: #dcfce7; color: var(--success); }
        .failed { background: #fee2e2; color: var(--danger); }
        .muted { color: var(--muted); }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 22px; }
        .button { background: linear-gradient(135deg, var(--primary), #1d4ed8); border: 0; border-radius: 14px; color: #fff; display: inline-block; font-weight: 800; padding: 13px 18px; text-decoration: none; }
        .button.secondary { background: #0f172a; }
        .success, .error { border-radius: 14px; margin-bottom: 18px; padding: 12px 14px; }
        .success { background: #dcfce7; color: var(--success); }
        .error { background: #fee2e2; color: var(--danger); }
        @media (max-width: 720px) { .page { padding: 18px; } .topbar { align-items: stretch; flex-direction: column; } .button { text-align: center; width: 100%; } }
    </style>
</head>
<body>
    <main class="page">
        <div class="topbar">
            <div class="brand">
                <div class="logo">MG</div>
                <div>
                    <p class="eyebrow">{{ $organizationName }}</p>
                    <strong>Minutes Generator</strong>
                </div>
            </div>
            <a class="button secondary" href="{{ route('minutes-generator.index') }}">Volver al panel</a>
        </div>

        @if (session('success'))<p class="success">{{ session('success') }}</p>@endif

        <section class="card">
            <p class="eyebrow">Estado de generación</p>
            <h1>
                @if ($analysis->status === 'completed')
                    Tu minuta está lista
                @elseif ($analysis->status === 'failed')
                    La generación falló
                @else
                    Tu minuta está en proceso
                @endif
            </h1>
            <span class="status {{ $analysis->status }}">{{ $analysis->status }}</span>

            @if (in_array($analysis->status, ['pending', 'processing'], true))
                <p>Estamos generando la minuta en segundo plano. Esta página se actualiza automáticamente cada 5 segundos.</p>
                <p class="muted">Podés dejar esta pantalla abierta o volver más tarde desde el enlace de estado.</p>
            @elseif ($analysis->status === 'completed' && $minute)
                <p>La IA terminó el análisis y guardó la minuta generada.</p>
                <div class="actions">
                    <a class="button" href="{{ route('minutes-generator.show', $minute) }}">Ver minuta</a>
                    <a class="button secondary" href="{{ route('minutes-generator.index') }}">Generar otra minuta</a>
                </div>
            @elseif ($analysis->status === 'failed')
                <p>No se pudo generar la minuta.</p>
                <p class="error">{{ $analysis->error_message ?: 'Revisá la configuración de IA o intentá nuevamente.' }}</p>
                <div class="actions">
                    <a class="button" href="{{ route('minutes-generator.index') }}">Intentar nuevamente</a>
                </div>
            @else
                <p class="muted">El análisis terminó, pero no encontramos una minuta asociada todavía.</p>
            @endif
        </section>
    </main>
</body>
</html>
