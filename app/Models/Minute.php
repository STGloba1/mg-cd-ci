<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Minute extends Model
{
    protected $fillable = [
        'transcript_analysis_id',
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
        'editable_content',
        'status',
        'version',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'participants' => 'array',
            'topics' => 'array',
            'detected_problems' => 'array',
            'proposed_solutions' => 'array',
            'agreements' => 'array',
            'pending_tasks' => 'array',
            'risks' => 'array',
            'next_steps' => 'array',
            'approved_at' => 'datetime',
        ];
    }

    public function transcriptAnalysis(): BelongsTo
    {
        return $this->belongsTo(TranscriptAnalysis::class);
    }
}
