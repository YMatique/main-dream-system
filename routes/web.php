<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\System\CompanyController;
use App\Livewire\System\CompanyManagement;
use App\Livewire\System\PlanManagement;
use App\Livewire\System\SubscriptionManagement;
use App\Livewire\System\SystemDashboard;
use App\Livewire\System\UserManagement;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

/*
Route::prefix('system')
    ->middleware(['auth', 'verified', 'role:super_admin'])
    ->name('system.')
    ->group(function () {
        
        // Dashboard do Sistema
        Route::get('/dashboard', function () {
            return view('system.dashboard');
        })->name('dashboard');
        
        // Gestão de Empresas
        Route::get('/companies', CompanyManagement::class)->name('companies');
        // Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('companies.show');
        
        // Outras rotas do sistema serão adicionadas aqui...
        Route::get('/plans', PlanManagement::class)->name('plans');
        Route::get('/subscriptions', SubscriptionManagement::class)->name('subscriptions');
        Route::get('/users',UserManagement::class)->name('users');
    });
    */

    // System Administration routes (Super Admin only)
Route::middleware(['auth', 'user.type:super_admin'])->prefix('system')->name('system.')->group(function () {
    
     Route::get('/dashboard', SystemDashboard::class)->name('dashboard');

    // Company Management
    Route::get('/companies', CompanyManagement::class)->name('companies');
    
    // User Management  
    Route::get('/users', UserManagement::class)->name('users');
    
    // Subscription Management
    Route::get('/subscriptions', SubscriptionManagement::class)->name('subscriptions');
    
    // Plans Management (will be implemented later)
    Route::get('/plans', PlanManagement::class)->name('plans');
    
    // System Settings
    Route::get('/settings', function() {
        return view('system.settings');
    })->name('settings');
    
    // System Reports
    Route::get('/reports', function() {
        return view('system.reports');
    })->name('reports');
    
});

//Rotas para Admin de Empresa 

// Administração da Empresa (Company Admin + Super Admin)
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'user.type:super_admin,company_admin'])
    ->name('admin.')
    ->group(function () {
        
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        // Gestão de Funcionários
        Route::get('/employees', function () {
            return view('admin.employees.index');
        })->name('employees');
        
        // Gestão de Clientes
        Route::get('/clients', function () {
            return view('admin.clients.index');
        })->name('clients');
        
        // Outros módulos da empresa...
    });//Rotas para (Todos os usuários da empresa)
Route::prefix('app')
    ->middleware(['auth', 'verified', 'user.type:super_admin,company_admin,company_user'])
    ->name('app.')
    ->group(function () {
        
        // Formulários de Ordens de Reparação
        Route::get('/repair-orders/form1', function () {
            return view('app.repair-orders.form1');
        })->name('repair-orders.form1')->middleware('permission:repair_orders.create');
        
        Route::get('/repair-orders/form2', function () {
            return view('app.repair-orders.form2');
        })->name('repair-orders.form2')->middleware('permission:repair_orders.create');
        
        // Listagens
        Route::get('/repair-orders/list', function () {
            return view('app.repair-orders.list');
        })->name('repair-orders.list')->middleware('permission:repair_orders.view');
        
        // Faturação
        Route::get('/billing', function () {
            return view('app.billing.index');
        })->name('billing')->middleware('permission:billing.view');
        
    });
require __DIR__.'/auth.php';
