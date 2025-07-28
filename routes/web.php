<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatBotController;

// Public Routes
Route::get('/', [ApplicationController::class, 'index'])->name('home');
Route::get('/apply', [ApplicationController::class, 'create'])->name('application.create');
Route::post('/apply', [ApplicationController::class, 'store'])->name('application.store');
Route::get('/application/success/{applicationId}', [ApplicationController::class, 'success'])->name('application.success');
Route::get('/admit-card/{applicationId}', [ApplicationController::class, 'downloadAdmitCard'])->name('application.admit-card');
Route::post('/check-status', [ApplicationController::class, 'checkStatus'])->name('application.check-status');

// ChatBot Routes
Route::post('/chat', [ChatBotController::class, 'chat'])->name('chat');

// Admin Routes (Protected by Authentication)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/applications', [AdminController::class, 'applications'])->name('applications');
    Route::get('/applications/{application}', [AdminController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{application}/status', [AdminController::class, 'updateStatus'])->name('applications.update-status');
    Route::get('/applications/export', [AdminController::class, 'exportApplications'])->name('applications.export');
    Route::get('/admit-card/{application}', [AdminController::class, 'generateAdmitCard'])->name('admit-card');
    Route::post('/bulk-admit-cards', [AdminController::class, 'bulkAdmitCards'])->name('bulk-admit-cards');
    Route::post('/applications/bulk-status-update', [AdminController::class, 'bulkStatusUpdate'])->name('applications.bulk-status-update');
});

// Authentication Routes
Auth::routes();

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
