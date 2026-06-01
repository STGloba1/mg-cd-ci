<?php

namespace App\Services;

use App\Models\Minute;
use App\Models\TranscriptAnalysis;
use Throwable;

class TranscriptAnalysisService
{
    public function __construct(
        private readonly AIProviderService $aiProvider,
        private readonly MinutesGeneratorService $minutesGenerator,
        private readonly TranscriptOptimizationService $transcriptOptimization,
    ) {}

    public function analyze(string $transcript): Minute
    {
        $analysis = $this->createPending($transcript);

        return $this->processAnalysis($analysis);
    }

    public function createPending(string $transcript): TranscriptAnalysis
    {
        return TranscriptAnalysis::create([
            'transcript_text' => $transcript,
            'status' => 'pending',
            'provider' => $this->aiProvider->provider(),
            'model' => $this->aiProvider->model(),
        ]);
    }

    public function regenerate(Minute $minute): Minute
    {
        $analysis = $minute->transcriptAnalysis;

        $nextVersion = ((int) $analysis->minutes()->max('version')) + 1;

        return $this->processAnalysis($analysis, $nextVersion);
    }

    public function processAnalysis(TranscriptAnalysis $analysis, ?int $version = null): Minute
    {
        $analysis->update([
            'status' => 'processing',
            'error_message' => null,
        ]);

        try {
            $optimizedTranscript = $this->transcriptOptimization->optimize($analysis->transcript_text);

            $result = $this->aiProvider->analyzeTranscript(
                $this->minutesGenerator->buildPrompt($optimizedTranscript),
            );

            $minute = $this->minutesGenerator->createMinute(
                $analysis,
                $result['content'],
                $version,
            );

            $analysis->update([
                'status' => 'completed',
                'provider' => $this->aiProvider->provider(),
                'model' => $this->aiProvider->model(),
                'input_tokens' => $result['input_tokens'],
                'output_tokens' => $result['output_tokens'],
            ]);

            return $minute;
        } catch (Throwable $exception) {
            $analysis->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
