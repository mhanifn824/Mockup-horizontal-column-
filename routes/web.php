<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/smart-search', [DashboardController::class, 'smartSearch'])->name('smart.search');
Route::get('/chat-ai', [DashboardController::class, 'chatAi'])->name('chat.ai');
Route::get('/preview', [DashboardController::class, 'previewDocument'])->name('document.preview');