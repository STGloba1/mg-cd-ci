<?php

use App\Http\Controllers\TeamsTranscriptImportController;
use Illuminate\Support\Facades\Route;

Route::middleware('teams-import.token')
    ->post('/teams/transcripts', [TeamsTranscriptImportController::class, 'store'])
    ->name('api.teams.transcripts.store');
