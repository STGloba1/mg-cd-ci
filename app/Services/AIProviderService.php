<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class AIProviderService
{
    /**
     * @return array{content: array<string, mixed>, input_tokens: int|null, output_tokens: int|null}
     */
    public function analyzeTranscript(string $prompt): array
    {
        if (! in_array($this->provider(), ['openai', 'nvidia'], true)) {
            throw new RuntimeException('Proveedor de IA no soportado.');
        }

        $apiKey = config('services.ai.api_key');

        if (blank($apiKey)) {
            throw new RuntimeException('La clave del proveedor de IA no está configurada.');
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(60)
            ->post($this->baseUrl().'/chat/completions', [
                'model' => $this->model(),
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => [
                        'name' => 'minutes_generation',
                        'strict' => true,
                        'schema' => $this->schema(),
                    ],
                ],
            ]);

        $response->throw();

        $body = $response->json();
        $text = $this->extractText($body);
        $content = json_decode($text, true);

        if (! is_array($content)) {
            throw new RuntimeException('La respuesta de IA no es JSON válido.');
        }

        return [
            'content' => $content,
            'input_tokens' => data_get($body, 'usage.prompt_tokens'),
            'output_tokens' => data_get($body, 'usage.completion_tokens'),
        ];
    }

    public function provider(): string
    {
        return (string) config('services.ai.provider', 'nvidia');
    }

    public function model(): string
    {
        return (string) config('services.ai.model', 'meta/llama-3.1-70b-instruct');
    }

    private function baseUrl(): string
    {
        return rtrim((string) config('services.ai.base_url', 'https://integrate.api.nvidia.com/v1'), '/');
    }

    /**
     * @param  array<string, mixed>  $body
     */
    private function extractText(array $body): string
    {
        $chatText = data_get($body, 'choices.0.message.content');

        if (is_string($chatText) && $chatText !== '') {
            return $chatText;
        }

        $outputText = data_get($body, 'output_text');

        if (is_string($outputText) && $outputText !== '') {
            return $outputText;
        }

        foreach ((array) data_get($body, 'output', []) as $output) {
            foreach ((array) data_get($output, 'content', []) as $content) {
                $text = data_get($content, 'text');

                if (is_string($text) && $text !== '') {
                    return $text;
                }
            }
        }

        throw new RuntimeException('La respuesta de IA no contiene texto.');
    }

    /**
     * @return array<string, mixed>
     */
    private function schema(): array
    {
        return [
            'type' => 'object',
            'additionalProperties' => false,
            'required' => [
                'title',
                'meeting_date',
                'participants',
                'executive_summary',
                'topics',
                'detected_problems',
                'proposed_solutions',
                'agreements',
                'pending_tasks',
                'risks',
                'next_steps',
                'confidence_score',
            ],
            'properties' => [
                'title' => ['type' => 'string'],
                'meeting_date' => ['type' => 'string'],
                'participants' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'executive_summary' => ['type' => 'string'],
                'topics' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'detected_problems' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'proposed_solutions' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'agreements' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'pending_tasks' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'additionalProperties' => false,
                        'required' => ['task', 'responsible', 'due_date', 'evidence'],
                        'properties' => [
                            'task' => ['type' => 'string'],
                            'responsible' => ['type' => 'string'],
                            'due_date' => ['type' => 'string'],
                            'evidence' => ['type' => 'string'],
                        ],
                    ],
                ],
                'risks' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'next_steps' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'confidence_score' => [
                    'type' => 'integer',
                    'minimum' => 0,
                    'maximum' => 100,
                ],
            ],
        ];
    }
}
