<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMinuteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'meeting_date' => ['nullable', 'string', 'max:50'],
            'executive_summary' => ['required', 'string', 'max:5000'],
            'editable_content' => ['nullable', 'string', 'max:20000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
