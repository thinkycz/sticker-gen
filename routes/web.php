<?php

use App\Http\Controllers\StickerSheetController;
use App\Http\Controllers\SheetConfigController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StickerSheetController::class, 'index'])->name('dashboard');

Route::get('/setup', [StickerSheetController::class, 'create'])->name('setup');
Route::post('/setup', [StickerSheetController::class, 'store'])->name('setup.store');

Route::post('/configs', [SheetConfigController::class, 'store'])->name('configs.store');
Route::delete('/configs/{config}', [SheetConfigController::class, 'destroy'])->name('configs.destroy');

Route::get('/designer/{sheet}', [StickerSheetController::class, 'show'])->name('designer');
Route::put('/designer/{sheet}', [StickerSheetController::class, 'update'])->name('designer.update');
Route::delete('/designer/{sheet}', [StickerSheetController::class, 'destroy'])->name('designer.destroy');
Route::post('/designer/{sheet}/duplicate', [StickerSheetController::class, 'duplicate'])->name('designer.duplicate');

Route::get('/preview/{sheet}', [StickerSheetController::class, 'preview'])->name('preview');
