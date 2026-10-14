<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

// Halaman utama — tampilkan demo widget
Route::get('/', [ChatController::class, 'index']);

// Legacy route (tetap aktif agar tidak breaking jika ada yang sudah pakai)
Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');

// API routes — dipakai oleh widget.js
Route::prefix('api')->group(function () {

    // Chat — endpoint utama widget
    Route::post('/chat', [ChatController::class, 'send'])->name('api.chat.send');

    // Feedback jempol (PRD §5.2, ERD: FEEDBACK)
    Route::post('/chat/feedback', [ChatController::class, 'feedback'])->name('api.chat.feedback');

    // Visitor event tracking (ERD: VISITOR_EVENT)
    Route::post('/chat/event', [ChatController::class, 'event'])->name('api.chat.event');

    // Contact form (PRD §5.2, ERD: CONTACT_MESSAGE)
    Route::post('/contact', [ContactController::class, 'store'])->name('api.contact.store');
});

// ── ADMIN ROUTES ────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    Route::post('/logout',[AdminController::class, 'logout'])->name('logout');

    // Protected: harus login dulu
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    });
});
