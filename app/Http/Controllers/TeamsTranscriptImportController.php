<?php

namespace App\Http\Controllers;

use App\Services\TranscriptAnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TeamsTranscriptImportController extends Controller
{
    public function __construct(
        private readonly TranscriptAnalysisService $transcriptAnalysisService,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'source' => ['nullable', 'string', 'max:100'],
            'meeting_title' => ['nullable', 'string', 'max:255'],
            'meeting_date' => ['nullable', 'date_format:Y-m-d'],
            'transcript_text' => [
                'required',
                'string',
                'min:100',
                'max:'.config('services.ai.max_transcript_length', 20000),
            ],
        ]);

        try {
            $minute = $this->transcriptAnalysisService->analyze($data['transcript_text']);

            return response()->json([
                'status' => 'completed',
                'source' => $data['source'] ?? 'microsoft_teams',
                'minute_id' => $minute->id,
                'analysis_id' => $minute->transcript_analysis_id,
                'title' => $minute->title,
                'url' => route('minutes-generator.show', $minute),
            ], 201);
        } catch (Throwable) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No se pudo generar la minuta desde la transcripción importada.',
            ], 422);
        }
    }
}
