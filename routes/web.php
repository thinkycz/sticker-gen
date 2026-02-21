<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StickerSheetController;
use App\Http\Controllers\SheetConfigController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return Inertia::render('TermsOfService');
})->name('terms-of-service');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [StickerSheetController::class, 'index'])->name('dashboard');

    Route::get('/setup', [StickerSheetController::class, 'create'])->name('setup');
    Route::post('/setup', [StickerSheetController::class, 'store'])->name('setup.store');

    Route::get('/designer/{sheet}', [StickerSheetController::class, 'show'])->name('designer');
    Route::put('/designer/{sheet}', [StickerSheetController::class, 'update'])->name('designer.update');
    Route::delete('/designer/{sheet}', [StickerSheetController::class, 'destroy'])->name('designer.destroy');
    Route::post('/designer/{sheet}/duplicate', [StickerSheetController::class, 'duplicate'])->name('designer.duplicate');
    Route::get('/designer/{sheet}/preview', [StickerSheetController::class, 'preview'])->name('designer.preview');

    Route::post('/configs', [SheetConfigController::class, 'store'])->name('configs.store');
    Route::delete('/configs/{config}', [SheetConfigController::class, 'destroy'])->name('configs.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
