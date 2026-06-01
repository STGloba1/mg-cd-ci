<?php

namespace App\Services;

class TranscriptOptimizationService
{
    public function optimize(string $transcript): string
    {
        $lines = preg_split('/\R/u', str_replace(["\r\n", "\r"], "\n", $transcript)) ?: [];
        $optimizedLines = [];
        $previousLineKey = null;
        $previousSpeaker = null;
        $previousSpeakerUtteranceKey = null;

        foreach ($lines as $line) {
            $line = $this->normalizeWhitespace($line);

            if ($line === '') {
                continue;
            }

            $line = $this->collapseRepeatedSentences($line);
            $lineKey = $this->comparisonKey($line);

            if ($lineKey === $previousLineKey) {
                continue;
            }

            [$speaker, $utterance] = $this->speakerAndUtterance($line);
            $utteranceKey = $utterance === null ? null : $this->comparisonKey($utterance);

            if ($speaker !== null
                && $speaker === $previousSpeaker
                && $utteranceKey !== null
                && $utteranceKey === $previousSpeakerUtteranceKey) {
                continue;
            }

            $optimizedLines[] = $line;
            $previousLineKey = $lineKey;
            $previousSpeaker = $speaker;
            $previousSpeakerUtteranceKey = $utteranceKey;
        }

        return implode("\n", $optimizedLines);
    }

    private function normalizeWhitespace(string $text): string
    {
        return trim((string) preg_replace('/[\t ]+/u', ' ', $text));
    }

    private function collapseRepeatedSentences(string $line): string
    {
        [$speaker, $utterance] = $this->speakerAndUtterance($line);
        $text = $utterance ?? $line;
        $sentences = preg_split('/(?<=[.!?])\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if (count($sentences) < 2) {
            return $line;
        }

        $collapsed = [];
        $previousSentenceKey = null;

        foreach ($sentences as $sentence) {
            $sentence = $this->normalizeWhitespace($sentence);
            $sentenceKey = $this->comparisonKey($sentence);

            if ($sentenceKey === $previousSentenceKey) {
                continue;
            }

            $collapsed[] = $sentence;
            $previousSentenceKey = $sentenceKey;
        }

        $collapsedText = implode(' ', $collapsed);

        return $speaker === null ? $collapsedText : $this->speakerLabel($line).': '.$collapsedText;
    }

    /**
     * @return array{0: string|null, 1: string|null}
     */
    private function speakerAndUtterance(string $line): array
    {
        if (preg_match('/^([^:\n]{1,80}):\s*(.+)$/u', $line, $matches) !== 1) {
            return [null, null];
        }

        return [$this->comparisonKey($matches[1]), $matches[2]];
    }

    private function speakerLabel(string $line): string
    {
        preg_match('/^([^:\n]{1,80}):\s*(.+)$/u', $line, $matches);

        return $matches[1];
    }

    private function comparisonKey(string $text): string
    {
        return mb_strtolower($this->normalizeWhitespace($text));
    }
}
