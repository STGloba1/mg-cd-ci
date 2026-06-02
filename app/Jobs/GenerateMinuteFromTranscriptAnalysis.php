<?php

namespace App\Jobs;

use App\Models\TranscriptAnalysis;
use App\Services\TranscriptAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateMinuteFromTranscriptAnalysis implements ShouldQueue
{
    use Queueable;

    public int $timeout = 120;

    public function __construct(
        public readonly int $analysisId,
        public readonly ?int $version = null,
    ) {}

    public function handle(TranscriptAnalysisService $transcriptAnalysisService): void
    {
        $analysis = TranscriptAnalysis::findOrFail($this->analysisId);

        $transcriptAnalysisService->processAnalysis($analysis, $this->version);
    }
}
