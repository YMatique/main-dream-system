<?php

use App\Http\Controllers\Portal\PortalAuthController;
use App\Livewire\Portal\EmployeeDashboard;
use App\Livewire\Portal\EmployeeEvaluations;
use App\Livewire\Portal\EmployeePerformanceHistory;
use App\Livewire\Portal\EmployeeProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =============================================
// ROTAS DE AUTENTICAÇÃO
// =============================================

Route::name('portal.')->group(function () {
    
    Route::get('/login', [PortalAuthController::class, 'showLoginForm'])
        ->name('login')
        ->middleware('guest:employee_portal');
    
    Route::post('/login', [PortalAuthController::class, 'login'])
        ->name('login.post')
        ->middleware('guest:employee_portal');
    
    Route::post('/logout', [PortalAuthController::class, 'logout'])
        ->name('logout')
        ->middleware('auth:employee_portal');
    
    Route::get('/access-denied', [PortalAuthController::class, 'accessDenied'])
        ->name('access-denied');
});

Route::prefix('employee')
    ->name('employee.')
    ->middleware(['auth:employee_portal'])
    ->group(function () {
        
        Route::get('/portal', EmployeeDashboard::class)->name('portal');
        Route::get('/evaluations', EmployeeEvaluations::class)->name('evaluations');
        Route::get('/evaluations/{year}/{month}', EmployeeEvaluations::class)->name('evaluations.monthly');
        Route::get('/performance-history', EmployeePerformanceHistory::class)->name('performance-history');
        Route::get('/profile', EmployeeProfile::class)->name('profile');
        
        // Impressão e download
        Route::get('/evaluations/{evaluation}/print', function ($evaluationId) {
            $portalUser = Auth::guard('employee_portal')->user();
            $evaluation = \App\Models\Company\Evaluation\PerformanceEvaluation::with([
                'employee.company', 'employee.department', 'evaluator', 'approvedBy', 'responses.metric'
            ])->findOrFail($evaluationId);
            
            if ($evaluation->employee_id !== $portalUser->employee_id) {
                abort(403, 'Acesso negado a esta avaliação.');
            }
            
            return view('portal.evaluations.print', compact('evaluation'));
        })->name('evaluations.print');
        
        Route::get('/evaluations/{evaluation}/download', function ($evaluationId) {
            $portalUser = Auth::guard('employee_portal')->user();
            $evaluation = \App\Models\Company\Evaluation\PerformanceEvaluation::findOrFail($evaluationId);
            
            if ($evaluation->employee_id !== $portalUser->employee_id) {
                abort(403, 'Acesso negado a esta avaliação.');
            }
            
            return response()->json(['message' => 'Download de PDF será implementado']);
        })->name('evaluations.download');
    });

// Redirecionamento
Route::get('/portal', function () {
    if (Auth::guard('employee_portal')->check()) {
        return redirect()->route('employee.portal');
    }
    return redirect()->route('portal.login');
});