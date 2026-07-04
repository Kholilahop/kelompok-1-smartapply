<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\GeminiController;  // 🔴 TAMBAHKAN INI!
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Job Routes
Route::middleware('auth')->group(function () {
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/data', [JobController::class, 'getData'])->name('jobs.data');
});

// Application Routes
Route::middleware('auth')->group(function () {
    Route::get('/applications/create/{job}', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications/generate', [ApplicationController::class, 'generateCoverLetter'])->name('applications.generate');
    Route::post('/applications/store', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/history', [ApplicationController::class, 'history'])->name('applications.history');
});

// 🔴 TAMBAHKAN ROUTE GEMINI DI SINI
// Gemini Routes (AI Cover Letter Generator)
Route::middleware('auth')->group(function () {
    Route::get('/cover-letter', [GeminiController::class, 'index'])->name('cover.letter.form');
    Route::post('/generate-cover-letter', [GeminiController::class, 'generate'])->name('generate.cover.letter');
});

// Route test (opsional) - bisa di luar middleware auth
Route::get('/test-gemini', function (App\Services\GeminiService $geminiService) {
    $result = $geminiService->generateCoverLetter([
        'nama' => 'Test User',
        'posisi' => 'Software Engineer',
        'skill' => 'PHP, Laravel, JavaScript'
    ]);
    
    return $result;
});

require __DIR__.'/auth.php';