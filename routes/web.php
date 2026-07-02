<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
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

require __DIR__.'/auth.php';