<?php

use App\Http\Controllers\MinutesGeneratorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MinutesGeneratorController::class, 'index'])->name('minutes-generator.index');
Route::post('/analyze', [MinutesGeneratorController::class, 'analyze'])->name('minutes-generator.analyze');
Route::get('/minutes/{minute}', [MinutesGeneratorController::class, 'show'])->name('minutes-generator.show');
Route::get('/minutes/{minute}/edit', [MinutesGeneratorController::class, 'edit'])->name('minutes-generator.edit');
Route::put('/minutes/{minute}', [MinutesGeneratorController::class, 'update'])->name('minutes-generator.update');
Route::post('/minutes/{minute}/approve', [MinutesGeneratorController::class, 'approve'])->name('minutes-generator.approve');
Route::post('/minutes/{minute}/regenerate', [MinutesGeneratorController::class, 'regenerate'])->name('minutes-generator.regenerate');
