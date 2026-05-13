<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar minuta</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem auto; max-width: 900px; line-height: 1.5; }
        input, textarea { box-sizing: border-box; margin-bottom: 1rem; padding: .75rem; width: 100%; }
        textarea { min-height: 160px; }
        button { background: #111827; border: 0; border-radius: .5rem; color: #fff; cursor: pointer; padding: .75rem 1rem; }
        .error { background: #fee2e2; color: #991b1b; padding: .75rem; }
    </style>
</head>
<body>
    <p><a href="{{ route('minutes-generator.show', $minute) }}">← Volver</a></p>
    <h1>Editar minuta</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('minutes-generator.update', $minute) }}">
        @csrf
        @method('PUT')

        <label for="title">Título</label>
        <input id="title" name="title" value="{{ old('title', $minute->title) }}" required>

        <label for="meeting_date">Fecha</label>
        <input id="meeting_date" name="meeting_date" value="{{ old('meeting_date', $minute->meeting_date) }}">

        <label for="executive_summary">Resumen ejecutivo</label>
        <textarea id="executive_summary" name="executive_summary" required>{{ old('executive_summary', $minute->executive_summary) }}</textarea>

        <label for="editable_content">Contenido editable</label>
        <textarea id="editable_content" name="editable_content">{{ old('editable_content', $minute->editable_content) }}</textarea>

        <button type="submit">Guardar</button>
    </form>
</body>
</html>
