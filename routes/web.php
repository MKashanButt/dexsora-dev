<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SheetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/company-setup', [AuthController::class, 'showCompanySetup'])->name('company.setup');
    Route::post('/company-setup', [AuthController::class, 'companySetup'])->name('company.setup.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Main app routes (require both auth and company)
    Route::middleware('has.company')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Sheets
        Route::get('/sheets', [SheetController::class, 'index'])->name('sheets.index');
        Route::post('/sheets', [SheetController::class, 'store'])->name('sheets.store');
        Route::get('/sheets/{sheet}', [SheetController::class, 'show'])->name('sheets.show');
        Route::put('/sheets/{sheet}/data', [SheetController::class, 'updateData'])->name('sheets.updateData');
        Route::delete('/sheets/{sheet}', [SheetController::class, 'destroy'])->name('sheets.destroy');
        
        // HTMX routes for clear functionality
        Route::get('/sheets/{sheet}/clear-confirmation', [SheetController::class, 'showClearConfirmation'])->name('sheets.clear-confirmation');
        Route::post('/sheets/{sheet}/clear', [SheetController::class, 'clear'])->name('sheets.clear');
        
        // Placeholder routes for other pages
        Route::get('/notifications', function () {
            return view('notifications');
        })->name('notifications');
        
        Route::get('/chats', function () {
            return view('chats');
        })->name('chats');
        
        Route::get('/settings', function () {
            return view('settings');
        })->name('settings');
    });
});
