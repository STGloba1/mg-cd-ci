<?php

use App\Http\Controllers\MinutesGeneratorAuthController;
use App\Http\Controllers\MinutesGeneratorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MinutesGeneratorAuthController::class, 'showLogin'])->name('minutes-generator.login');
Route::post('/login', [MinutesGeneratorAuthController::class, 'login'])->name('minutes-generator.login.store');

Route::middleware('minutes-generator.auth')->group(function (): void {
    Route::post('/logout', [MinutesGeneratorAuthController::class, 'logout'])->name('minutes-generator.logout');
    Route::get('/app', [MinutesGeneratorController::class, 'index'])->name('minutes-generator.index');
    Route::post('/analyze', [MinutesGeneratorController::class, 'analyze'])->name('minutes-generator.analyze');
    Route::get('/analyses/{analysis}/status', [MinutesGeneratorController::class, 'status'])->name('minutes-generator.status');
    Route::get('/minutes/{minute}', [MinutesGeneratorController::class, 'show'])->name('minutes-generator.show');
    Route::get('/minutes/{minute}/edit', [MinutesGeneratorController::class, 'edit'])->name('minutes-generator.edit');
    Route::put('/minutes/{minute}', [MinutesGeneratorController::class, 'update'])->name('minutes-generator.update');
    Route::post('/minutes/{minute}/approve', [MinutesGeneratorController::class, 'approve'])->name('minutes-generator.approve');
    Route::post('/minutes/{minute}/regenerate', [MinutesGeneratorController::class, 'regenerate'])->name('minutes-generator.regenerate');
});
