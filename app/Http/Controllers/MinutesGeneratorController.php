<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnalyzeTranscriptRequest;
use App\Http\Requests\UpdateMinuteRequest;
use App\Models\Minute;
use App\Services\TranscriptAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class MinutesGeneratorController extends Controller
{
    public function __construct(
        private readonly TranscriptAnalysisService $transcriptAnalysisService,
    ) {}

    public function index(Request $request): View
    {
        $minutesQuery = Minute::query()
            ->latest();

        if ($request->filled('status')) {
            $minutesQuery->where('status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $minutesQuery->where(function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('executive_summary', 'like', "%{$search}%")
                    ->orWhere('meeting_date', 'like', "%{$search}%");
            });
        }

        return view('minutes-generator.index', [
            'minutes' => $minutesQuery->paginate(8)->withQueryString(),
            'stats' => [
                'total' => Minute::count(),
                'draft' => Minute::where('status', 'draft')->count(),
                'approved' => Minute::where('status', 'approved')->count(),
                'versions' => Minute::max('version') ?? 0,
            ],
            'organizationName' => config('services.minutes_generator.organization_name'),
            'brandColor' => config('services.minutes_generator.brand_color'),
            'maxTranscriptLength' => config('services.ai.max_transcript_length'),
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function analyze(AnalyzeTranscriptRequest $request): RedirectResponse
    {
        try {
            $minute = $this->transcriptAnalysisService->analyze($request->string('transcript_text')->toString());

            return redirect()
                ->route('minutes-generator.show', $minute)
                ->with('success', 'Minuta generada correctamente.');
        } catch (Throwable) {
            return redirect()
                ->route('minutes-generator.index')
                ->withInput()
                ->with('error', 'No se pudo generar la minuta. Revisá la configuración de IA o intentá nuevamente.');
        }
    }

    public function show(Minute $minute): View
    {
        return view('minutes-generator.show', [
            'minute' => $minute,
        ]);
    }

    public function edit(Minute $minute): RedirectResponse|View
    {
        if ($minute->status === 'approved') {
            return redirect()
                ->route('minutes-generator.show', $minute)
                ->with('error', 'No se puede editar una minuta aprobada.');
        }

        return view('minutes-generator.edit', [
            'minute' => $minute,
        ]);
    }

    public function update(UpdateMinuteRequest $request, Minute $minute): RedirectResponse
    {
        if ($minute->status === 'approved') {
            return redirect()
                ->route('minutes-generator.show', $minute)
                ->with('error', 'No se puede editar una minuta aprobada.');
        }

        $minute->update($request->validated());

        return redirect()
            ->route('minutes-generator.show', $minute)
            ->with('success', 'Minuta actualizada correctamente.');
    }

    public function approve(Minute $minute): RedirectResponse
    {
        if ($minute->status === 'approved') {
            return redirect()
                ->route('minutes-generator.show', $minute)
                ->with('error', 'La minuta ya estaba aprobada.');
        }

        $minute->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('minutes-generator.show', $minute)
            ->with('success', 'Minuta aprobada correctamente.');
    }

    public function regenerate(Minute $minute): RedirectResponse
    {
        try {
            $newMinute = $this->transcriptAnalysisService->regenerate($minute);

            return redirect()
                ->route('minutes-generator.show', $newMinute)
                ->with('success', 'Minuta regenerada como nueva versión.');
        } catch (Throwable) {
            return redirect()
                ->route('minutes-generator.show', $minute)
                ->with('error', 'No se pudo regenerar la minuta.');
        }
    }
}
