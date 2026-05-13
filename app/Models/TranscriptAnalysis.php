<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TranscriptAnalysis extends Model
{
    protected $fillable = [
        'transcript_text',
        'status',
        'provider',
        'model',
        'input_tokens',
        'output_tokens',
        'error_message',
    ];

    public function minutes(): HasMany
    {
        return $this->hasMany(Minute::class);
    }
}
