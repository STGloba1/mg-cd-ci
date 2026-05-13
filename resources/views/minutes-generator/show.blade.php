<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $minute->title }}</title>
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
    <p><a href="{{ route('minutes-generator.index') }}">← Nueva transcripción</a></p>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <h1>{{ $minute->title }}</h1>
    <p>
        Estado: <strong>{{ $minute->status }}</strong> ·
        Versión: <strong>{{ $minute->version }}</strong> ·
        Confianza: <strong>{{ $minute->confidence_score }}%</strong>
    </p>
    <p>Fecha: {{ $minute->meeting_date }}</p>

    <p>
        @if ($minute->status !== 'approved')
            <a class="button" href="{{ route('minutes-generator.edit', $minute) }}">Editar</a>

            <form method="POST" action="{{ route('minutes-generator.approve', $minute) }}">
                @csrf
                <button type="submit">Aprobar</button>
            </form>
        @endif

        <form method="POST" action="{{ route('minutes-generator.regenerate', $minute) }}">
            @csrf
            <button type="submit">Regenerar</button>
        </form>
    </p>

    <section>
        <h2>Participantes</h2>
        @include('minutes-generator.partials.list', ['items' => $minute->participants])
    </section>

    <section>
        <h2>Resumen ejecutivo</h2>
        <p>{{ $minute->executive_summary }}</p>
    </section>

    @if (filled($minute->editable_content))
        <section>
            <h2>Contenido editable</h2>
            <p style="white-space: pre-wrap;">{{ $minute->editable_content }}</p>
        </section>
    @endif

    <section>
        <h2>Temas</h2>
        @include('minutes-generator.partials.list', ['items' => $minute->topics])
    </section>

    <section>
        <h2>Problemas detectados</h2>
        @include('minutes-generator.partials.list', ['items' => $minute->detected_problems])
    </section>

    <section>
        <h2>Soluciones propuestas</h2>
        @include('minutes-generator.partials.list', ['items' => $minute->proposed_solutions])
    </section>

    <section>
        <h2>Acuerdos</h2>
        @include('minutes-generator.partials.list', ['items' => $minute->agreements])
    </section>

    <section>
        <h2>Tareas pendientes</h2>
        @forelse ($minute->pending_tasks as $task)
            <article>
                <strong>{{ $task['task'] }}</strong><br>
                Responsable: {{ $task['responsible'] }}<br>
                Fecha: {{ $task['due_date'] }}<br>
                Evidencia: {{ $task['evidence'] }}
            </article>
        @empty
            <p>No identificado</p>
        @endforelse
    </section>

    <section>
        <h2>Riesgos</h2>
        @include('minutes-generator.partials.list', ['items' => $minute->risks])
    </section>

    <section>
        <h2>Próximos pasos</h2>
        @include('minutes-generator.partials.list', ['items' => $minute->next_steps])
    </section>
</body>
</html>
