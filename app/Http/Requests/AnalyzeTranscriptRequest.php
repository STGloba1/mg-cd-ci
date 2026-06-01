<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyzeTranscriptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'transcript_text' => [
                'required',
                'string',
                'min:100',
                'max:'.config('services.ai.max_transcript_length', 100000),
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
