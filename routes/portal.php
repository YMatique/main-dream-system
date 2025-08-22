<?php

use App\Http\Controllers\Portal\PortalAuthController;
use App\Livewire\Portal\EmployeeDashboard;
use App\Livewire\Portal\EmployeeEvaluations;
use App\Livewire\Portal\EmployeePerformanceHistory;
use App\Livewire\Portal\EmployeeProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =============================================
// ROTAS DE AUTENTICAÇÃO DO PORTAL
// =============================================
Route::name('portal.')->group(function () {
    
    // Login form
    Route::get('portal/login', [PortalAuthController::class, 'showLoginForm'])
        ->name('login')
        ->middleware('guest:portal');
       
    // Process login
    Route::post('portal/login', [PortalAuthController::class, 'login'])
        ->name('login.post')
        ->middleware('guest:portal');
   
    // Logout
    Route::post('portal/logout', [PortalAuthController::class, 'logout'])
        ->name('logout')
        ->middleware('portal.auth');
   
    // Access denied page
    Route::get('portal/access-denied', [PortalAuthController::class, 'accessDenied'])
        ->name('access-denied');
});

// =============================================
// ROTAS PROTEGIDAS DO PORTAL DO FUNCIONÁRIO
// =============================================
Route::prefix('employee')
    ->name('employee.')
    ->middleware(['portal.auth'])
    ->group(function () {
       
        Route::get('/portal', EmployeeDashboard::class)->name('portal');
        Route::get('/evaluations', EmployeeEvaluations::class)->name('evaluations');
        Route::get('/evaluations/{year}/{month}', EmployeeEvaluations::class)->name('evaluations.monthly');
        Route::get('/performance-history', EmployeePerformanceHistory::class)->name('performance-history');
        Route::get('/profile', EmployeeProfile::class)->name('profile');
       
        // Impressão e download de avaliações
        Route::get('/evaluations/{evaluation}/print', function ($evaluationId) {
            $portalUser = Auth::guard('portal')->user();
            $evaluation = \App\Models\Company\Evaluation\PerformanceEvaluation::with([
                'employee.company', 'employee.department', 'evaluator', 'approvedBy', 'responses.metric'
            ])->findOrFail($evaluationId);
           
            if ($evaluation->employee_id !== $portalUser->employee_id) {
                abort(403, 'Acesso negado a esta avaliação.');
            }
           
            return view('portal.evaluations.print', compact('evaluation'));
        })->name('evaluations.print');
       
        Route::get('/evaluations/{evaluation}/download', function ($evaluationId) {
            $portalUser = Auth::guard('portal')->user();
            $evaluation = \App\Models\Company\Evaluation\PerformanceEvaluation::findOrFail($evaluationId);
           
            if ($evaluation->employee_id !== $portalUser->employee_id) {
                abort(403, 'Acesso negado a esta avaliação.');
            }
           
            return response()->json(['message' => 'Download de PDF será implementado']);
        })->name('evaluations.download');
    });

// =============================================
// REDIRECIONAMENTOS INTELIGENTES
// =============================================

// Redirecionar /portal para o dashboard se logado, senão para login
Route::get('/portal', function () {
    if (Auth::guard('portal')->check()) {
        return redirect()->route('employee.portal');
    }
    return redirect()->route('portal.login');
});

// Redirecionar /employee/portal para /portal (compatibilidade)
// Route::get('/employee/portal', function () {
//     return redirect('/portal');
// });