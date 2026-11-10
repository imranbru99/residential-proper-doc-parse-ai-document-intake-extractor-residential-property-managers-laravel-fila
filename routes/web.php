<?php

use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/features', [PublicSiteController::class, 'features'])->name('features');
Route::get('/workflow', [PublicSiteController::class, 'workflow'])->name('workflow');
Route::get('/pricing', [PublicSiteController::class, 'pricing'])->name('pricing');
Route::get('/demo', [PublicSiteController::class, 'demo'])->name('demo');
