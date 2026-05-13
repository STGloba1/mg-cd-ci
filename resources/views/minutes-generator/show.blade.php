<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $minute->title }}</title>
    <style>
        :root {
            color-scheme: light;
            --border: #d1d5db;
            --muted: #6b7280;
            --text: #111827;
            --soft: #f9fafb;
            --brand: #1f2937;
        }

        * { box-sizing: border-box; }

        body {
            background: #e5e7eb;
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.45;
            margin: 0;
            padding: 24px;
        }

        .toolbar {
            align-items: center;
            display: flex;
            gap: 8px;
            justify-content: space-between;
            margin: 0 auto 16px;
            max-width: 210mm;
        }

        .toolbar-actions { display: flex; flex-wrap: wrap; gap: 8px; }
        form { display: inline; }
        a { color: #1d4ed8; }
        button, .button {
            background: var(--brand);
            border: 0;
            border-radius: 6px;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            padding: 9px 12px;
            text-decoration: none;
        }
        .button.secondary { background: #4b5563; }
        .button.print { background: #047857; }
        .error, .success {
            border-radius: 6px;
            margin: 0 auto 12px;
            max-width: 210mm;
            padding: 12px;
        }
        .error { background: #fee2e2; color: #991b1b; }
        .success { background: #dcfce7; color: #166534; }

        .sheet {
            background: #fff;
            box-shadow: 0 20px 45px rgba(15, 23, 42, .12);
            margin: 0 auto;
            max-width: 210mm;
            min-height: 297mm;
            padding: 18mm;
        }

        .document-header {
            border-bottom: 2px solid var(--brand);
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr auto;
            padding-bottom: 14px;
        }

        .eyebrow {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            margin: 0 0 6px;
            text-transform: uppercase;
        }

        h1 {
            font-size: 26px;
            line-height: 1.15;
            margin: 0;
        }

        h2 {
            border-bottom: 1px solid var(--border);
            font-size: 16px;
            margin: 22px 0 10px;
            padding-bottom: 5px;
            text-transform: uppercase;
        }

        h3 { font-size: 14px; margin: 0 0 6px; }
        p { margin: 0 0 8px; }
        ul { margin: 0; padding-left: 18px; }
        li { margin-bottom: 4px; }

        .meta-box {
            border: 1px solid var(--border);
            border-radius: 8px;
            min-width: 210px;
            overflow: hidden;
        }

        .meta-row {
            display: grid;
            gap: 8px;
            grid-template-columns: 84px 1fr;
            padding: 7px 9px;
        }
        .meta-row + .meta-row { border-top: 1px solid var(--border); }
        .meta-label { color: var(--muted); font-size: 12px; font-weight: 700; }
        .meta-value { font-size: 12px; }

        .summary {
            background: var(--soft);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px;
        }

        .grid-2 {
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr 1fr;
        }

        .empty { color: var(--muted); font-style: italic; }

        .tasks-table {
            border-collapse: collapse;
            font-size: 13px;
            width: 100%;
        }
        .tasks-table th,
        .tasks-table td {
            border: 1px solid var(--border);
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        .tasks-table th {
            background: var(--soft);
            font-size: 12px;
            text-transform: uppercase;
        }

        .approval {
            display: grid;
            gap: 20px;
            grid-template-columns: 1fr 1fr;
            margin-top: 34px;
        }
        .signature-line {
            border-top: 1px solid var(--text);
            padding-top: 6px;
            text-align: center;
        }

        .footer {
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 11px;
            margin-top: 24px;
            padding-top: 8px;
        }

        @media print {
            @page { margin: 14mm; size: A4; }
            body { background: #fff; padding: 0; }
            .toolbar, .error, .success { display: none !important; }
            .sheet {
                box-shadow: none;
                margin: 0;
                max-width: none;
                min-height: auto;
                padding: 0;
                width: auto;
            }
            a { color: inherit; text-decoration: none; }
            h2 { break-after: avoid; }
            .tasks-table, .summary, .meta-box { break-inside: avoid; }
            tr { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('minutes-generator.index') }}">← Nueva transcripción</a>

        <div class="toolbar-actions">
            <button class="button print" type="button" onclick="window.print()">Exportar PDF</button>

            @if ($minute->status !== 'approved')
                <a class="button secondary" href="{{ route('minutes-generator.edit', $minute) }}">Editar</a>

                <form method="POST" action="{{ route('minutes-generator.approve', $minute) }}">
                    @csrf
                    <button type="submit">Aprobar</button>
                </form>
            @endif

            <form method="POST" action="{{ route('minutes-generator.regenerate', $minute) }}">
                @csrf
                <button type="submit">Regenerar</button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <main class="sheet">
        <header class="document-header">
            <div>
                <p class="eyebrow">{{ config('services.minutes_generator.organization_name') }} · Minuta ejecutiva</p>
                <h1>{{ $minute->title }}</h1>
            </div>

            <div class="meta-box" aria-label="Datos de la minuta">
                <div class="meta-row">
                    <span class="meta-label">Fecha</span>
                    <span class="meta-value">{{ $minute->meeting_date }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Estado</span>
                    <span class="meta-value">{{ ucfirst($minute->status) }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Versión</span>
                    <span class="meta-value">{{ $minute->version }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Confianza</span>
                    <span class="meta-value">{{ $minute->confidence_score }}%</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Aprobación</span>
                    <span class="meta-value">{{ $minute->approved_at?->format('Y-m-d H:i') ?? 'Pendiente' }}</span>
                </div>
            </div>
        </header>

        <section>
            <h2>1. Datos de reunión</h2>
            <div class="grid-2">
                <div>
                    <h3>Participantes</h3>
                    @include('minutes-generator.partials.list', ['items' => $minute->participants])
                </div>
                <div>
                    <h3>Registro</h3>
                    <p><strong>Generada:</strong> {{ $minute->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Última actualización:</strong> {{ $minute->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </section>

        <section>
            <h2>2. Resumen ejecutivo</h2>
            <div class="summary">
                <p>{{ $minute->executive_summary }}</p>
            </div>
        </section>

        @if (filled($minute->editable_content))
            <section>
                <h2>3. Contenido aprobado/editable</h2>
                <p style="white-space: pre-wrap;">{{ $minute->editable_content }}</p>
            </section>
        @endif

        <section>
            <h2>4. Temas tratados</h2>
            @include('minutes-generator.partials.list', ['items' => $minute->topics])
        </section>

        <section>
            <h2>5. Problemas y soluciones</h2>
            <div class="grid-2">
                <div>
                    <h3>Problemas detectados</h3>
                    @include('minutes-generator.partials.list', ['items' => $minute->detected_problems])
                </div>
                <div>
                    <h3>Soluciones propuestas</h3>
                    @include('minutes-generator.partials.list', ['items' => $minute->proposed_solutions])
                </div>
            </div>
        </section>

        <section>
            <h2>6. Acuerdos y decisiones</h2>
            @include('minutes-generator.partials.list', ['items' => $minute->agreements])
        </section>

        <section>
            <h2>7. Tareas pendientes</h2>
            @if (count($minute->pending_tasks ?? []) > 0)
                <table class="tasks-table">
                    <thead>
                        <tr>
                            <th>Tarea</th>
                            <th>Responsable</th>
                            <th>Fecha compromiso</th>
                            <th>Evidencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($minute->pending_tasks as $task)
                            <tr>
                                <td>{{ $task['task'] ?? 'No identificado' }}</td>
                                <td>{{ $task['responsible'] ?? 'No identificado' }}</td>
                                <td>{{ $task['due_date'] ?? 'No identificado' }}</td>
                                <td>{{ $task['evidence'] ?? 'No identificado' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="empty">No identificado</p>
            @endif
        </section>

        <section>
            <h2>8. Riesgos</h2>
            @include('minutes-generator.partials.list', ['items' => $minute->risks])
        </section>

        <section>
            <h2>9. Próximos pasos</h2>
            @include('minutes-generator.partials.list', ['items' => $minute->next_steps])
        </section>

        <section>
            <h2>10. Aprobación</h2>
            <div class="approval">
                <div class="signature-line">Responsable de revisión</div>
                <div class="signature-line">Aprobado por</div>
            </div>
        </section>

        <footer class="footer">
            Documento generado desde transcripción mediante IA. La información debe validarse contra la reunión original antes de su aprobación formal.
        </footer>
    </main>
</body>
</html>
