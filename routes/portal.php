<?php

use App\Http\Controllers\Portal\PortalAuthController;
use App\Livewire\Portal\EmployeeDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =============================================
// ROTAS DE AUTENTICAÇÃO DO PORTAL
// =============================================// Rotas públicas do portal
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('login', [PortalAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [PortalAuthController::class, 'login'])->name('login.post');
});


// =============================================
// ROTAS PROTEGIDAS DO PORTAL DO FUNCIONÁRIO
// =============================================
Route::prefix('employee')
    ->name('employee.')
    ->middleware('portal.auth')  // <-- AQUI ESTÁ A CORREÇÃO PRINCIPAL
    ->group(function () {
        
        // Dashboard principal
        Route::get('/portal', EmployeeDashboard::class)->name('portal');
        
        // Outras rotas do portal...
        Route::get('/evaluations', function () {
            return view('portal.employee.evaluations');
        })->name('evaluations');
        
        Route::get('/performance-history', function () {
            return view('portal.employee.performance');
        })->name('performance-history');
        
        Route::get('/profile', function () {
            return view('portal.employee.profile');
        })->name('profile');
    });

// =============================================
// REDIRECIONAMENTO INTELIGENTE
// =============================================
Route::get('/portal', function () {
    if (Auth::guard('employee_portal')->check()) {
        return redirect()->route('employee.portal');
    }
    return redirect()->route('portal.login');
});

Route::get('/test-portal', function () {
    return 'Portal middleware funcionando!';
})->middleware('portal.auth');