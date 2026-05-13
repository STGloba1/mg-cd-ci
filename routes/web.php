<?php

use App\Http\Controllers\MinutesGeneratorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('minutes-generator.index');
});

Route::get('/minutes-generator', [MinutesGeneratorController::class, 'index'])->name('minutes-generator.index');
Route::post('/minutes-generator/analyze', [MinutesGeneratorController::class, 'analyze'])->name('minutes-generator.analyze');
Route::get('/minutes-generator/{minute}', [MinutesGeneratorController::class, 'show'])->name('minutes-generator.show');
Route::get('/minutes-generator/{minute}/edit', [MinutesGeneratorController::class, 'edit'])->name('minutes-generator.edit');
Route::put('/minutes-generator/{minute}', [MinutesGeneratorController::class, 'update'])->name('minutes-generator.update');
Route::post('/minutes-generator/{minute}/approve', [MinutesGeneratorController::class, 'approve'])->name('minutes-generator.approve');
Route::post('/minutes-generator/{minute}/regenerate', [MinutesGeneratorController::class, 'regenerate'])->name('minutes-generator.regenerate');
