<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Tools\ToolController;
use App\Http\Controllers\Loans\ToolLoanController;
use App\Http\Controllers\Warehouses\WarehouseController;
use App\Http\Controllers\Programs\TechnicalProgramController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\InvitationController;
use App\Http\Controllers\Auth\ProfileCompletionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Invitation Routes (public)
Route::get('/invitation/{token}', [InvitationController::class, 'accept'])->name('invitation.accept');
Route::post('/invitation/{token}/complete', [InvitationController::class, 'complete'])->name('invitation.complete');

// Profile Completion Routes (authenticated but incomplete profile)
Route::middleware(['auth'])->group(function () {
    Route::get('/complete-profile', [ProfileCompletionController::class, 'show'])->name('profile.complete');
    Route::post('/complete-profile', [ProfileCompletionController::class, 'update'])->name('profile.complete');
});

// User Invitation Management (Admin only)
Route::middleware(['auth', 'permission:manage users'])->group(function () {
    Route::get('/invitations', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::post('/invitations/{invitation}/resend', [InvitationController::class, 'resend'])->name('invitations.resend');
    Route::delete('/invitations/{invitation}', [InvitationController::class, 'cancel'])->name('invitations.cancel');
});

// Registration (Admin only - legacy, now replaced by invitations)
Route::middleware(['auth', 'permission:manage users'])->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated Routes (with profile completion check)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tools Management
    Route::resource('tools', ToolController::class);
    Route::post('/tools/bulk-action', [ToolController::class, 'bulkAction'])
        ->name('tools.bulk-action')
        ->middleware('permission:manage tools');

    // Tool Loans Management
    Route::resource('loans', ToolLoanController::class);
    Route::post('/loans/{loan}/approve', [ToolLoanController::class, 'approve'])
        ->name('loans.approve')
        ->middleware('logistics');
    Route::post('/loans/{loan}/deliver', [ToolLoanController::class, 'deliver'])
        ->name('loans.deliver')
        ->middleware('logistics');
    Route::get('/loans/{loan}/return', [ToolLoanController::class, 'showReturnForm'])
        ->name('loans.return.form')
        ->middleware('logistics');
    Route::post('/loans/{loan}/return', [ToolLoanController::class, 'processReturn'])
        ->name('loans.return')
        ->middleware('logistics');
    Route::post('/loans/{loan}/cancel', [ToolLoanController::class, 'cancel'])->name('loans.cancel');

    // API Routes for AJAX
    Route::get('/api/classrooms', [ToolLoanController::class, 'getClassrooms']);
    Route::get('/api/tools-by-warehouse', [ToolLoanController::class, 'getToolsByWarehouse']);

    // Reports
    Route::prefix('reports')->name('reports.')->middleware('permission:view reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/tools', [ReportController::class, 'toolsReport'])->name('tools');
        Route::get('/loans', [ReportController::class, 'loansReport'])->name('loans');
        Route::get('/usage', [ReportController::class, 'usageReport'])->name('usage');
        Route::get('/export/{type}', [ReportController::class, 'export'])
            ->name('export')
            ->middleware('permission:export data');
    });

    // Admin Routes
    Route::middleware(['permission:manage system'])->group(function () {
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('programs', TechnicalProgramController::class);
        Route::resource('users', UserController::class);
    });
});
