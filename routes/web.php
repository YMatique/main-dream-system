<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\System\CompanyController;
use App\Livewire\Auth\System\ForgotPassword;
use App\Livewire\Auth\System\ResetPassword;
use App\Livewire\Company\Billing\BillingEstimatedManagement;
use App\Livewire\Company\Billing\BillingHHManagement;
use App\Livewire\Company\Billing\BillingRealManagement;
use App\Livewire\Company\ClientCostManagement;
use App\Livewire\Company\ClientManagement;
use App\Livewire\Company\CompanyLogin;
use App\Livewire\Company\Dashboard;
use App\Livewire\Company\DepartmentManagement;
use App\Livewire\Company\EmployeeManagement;
use App\Livewire\Company\Forms\RepairOrderForm1;
use App\Livewire\Company\Forms\RepairOrderForm2;
use App\Livewire\Company\Forms\RepairOrderForm3;
use App\Livewire\Company\Forms\RepairOrderForm4;
use App\Livewire\Company\Forms\RepairOrderForm5;
use App\Livewire\Company\Listings\AdvancedListing;
use App\Livewire\Company\Listings\RepairOrdersForm1List;
use App\Livewire\Company\Listings\RepairOrdersForm2List;
use App\Livewire\Company\Listings\RepairOrdersForm3List;
use App\Livewire\Company\Listings\RepairOrdersForm4List;
use App\Livewire\Company\Listings\RepairOrdersForm5List;
use App\Livewire\Company\Listings\RepairOrdersList;
use App\Livewire\Company\MachineNumberManagement;
use App\Livewire\Company\MaintenanceTypeManagement;
use App\Livewire\Company\MaterialManagement;
use App\Livewire\Company\Perfomance\ApprovalStageManagement;
use App\Livewire\Company\Perfomance\EvaluationApprovals;
use App\Livewire\Company\Perfomance\EvaluationManagement;
use App\Livewire\Company\Perfomance\EvaluationReports;
use App\Livewire\Company\Perfomance\MetricsManagement;
use App\Livewire\Company\RequesterManagement;
use App\Livewire\Company\StatusLocationManagement;
use App\Livewire\Company\UserPermissionManagement;
use App\Livewire\Portal\EmployeeDashboard;
use App\Livewire\Portal\EmployeeEvaluations;
use App\Livewire\Portal\EmployeePerformanceHistory;
use App\Livewire\Portal\EmployeeProfile;
use App\Livewire\System\ActivityLogsManagement;
use App\Livewire\System\CompanyManagement;
use App\Livewire\System\PlanManagement;
use App\Livewire\System\SubscriptionManagement;
use App\Livewire\System\SystemDashboard;
use App\Livewire\System\UserManagement;
use App\Livewire\Website\About;
use App\Livewire\Website\CheckOut;
use App\Livewire\Website\Contact;
use App\Livewire\Website\Home;
use App\Livewire\Website\Project;
use App\Livewire\Website\Service;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

// WEBSITE ROUTES
// Route::get('lang/{locale}', function ($locale) {
//     if (in_array($locale, config('app.available_locales'))) {
//         session(['locale' => $locale]);
//     }
//     return redirect()->back();
// })->name('lang.switch');

Route::get('/', function () {
    $locale = session('locale', config('app.locale'));
    return redirect("/{$locale}");
})->name('root');
// Rotas com prefixo de idioma
Route::group([
    'prefix' => '{locale}',
    'middleware' => 'setlocale',
    'where' => ['locale' => '[a-zA-Z]{2}']
], function () {

    // Home
    Route::get('/', Home::class)->name('home');

    // Sobre3
    Route::get('/sobre', About::class)->name('about');

    // Serviços
    Route::get('/servicos', Service::class)->name('services');

    Route::get('/servicos/engenharia', function () {
        return view('website.services.engineering');
    })->name('services.engineering');

    Route::get('/servicos/manutencao', function () {
        return view('website.services.maintenance');
    })->name('services.maintenance');

    Route::get('/servicos/tecnologia', function () {
        return view('website.services.technology');
    })->name('services.technology');

    Route::get('/servicos/pecas', function () {
        return view('website.services.spare-parts');
    })->name('services.spare_parts');

    // Projetos
    Route::get('/projetos', Project::class)->name('projects');

    // Contato
    Route::get('/contacto', Contact::class)->name('contact');

    Route::get('/check-out',CheckOut::class)->name('check-out');

    Route::post('/contacto', function () {
        // Lógica de envio do formulário
        return back()->with('success', __('messages.contact.success'));
    })->name('contact.send');
});

// Rota para troca de idioma
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, config('app.available_locales'))) {
        session(['locale' => $locale]);
    }

    // Pegar a URL anterior e adaptar o prefixo
    $previousUrl = url()->previous();
    $previousPath = parse_url($previousUrl, PHP_URL_PATH);

    // Remover o prefixo de idioma anterior se existir
    $availableLocales = config('app.available_locales');
    foreach ($availableLocales as $lang) {
        if (str_starts_with($previousPath, "/{$lang}")) {
            $previousPath = substr($previousPath, 3); // Remove /xx
            break;
        }
    }

    // Redirecionar com novo prefixo
    return redirect("/{$locale}{$previousPath}");
})->name('lang.switch');


// 
Route::middleware('guest')->group(function () {
    Route::get('system-auth/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('system-auth/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

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

// Aplicar middlewares nas rotas do sistema
// Route::prefix('system')
//     ->middleware(['auth', 'verified', 'role:super_admin', 'audit', 'security'])
//     ->name('system.')
//     ->group(function () {
//         // Suas rotas...
//     });
// System Administration routes (Super Admin only)


/*
|--------------------------------------------------------------------------
| Super Admin Routes (/system)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.unified', 'user.type:super_admin'])->prefix('system')->name('system.')->group(function () {

    Route::get('/dashboard', SystemDashboard::class)->name('dashboard');

    // Company Management
    Route::get('/companies', CompanyManagement::class)->name('companies');

    // User Management  
    Route::get('/users', UserManagement::class)->name('users');

    // Subscription Management
    Route::get('/subscriptions', SubscriptionManagement::class)->name('subscriptions');

    // Plans Management (will be implemented later)
    Route::get('/plans', PlanManagement::class)->name('plans');

    Route::get('/logs', ActivityLogsManagement::class)->name('logs');

    // Relatórios do Sistema
    // Route::get('/reports', SystemReports::class)->name('reports');

    // Configurações do Sistema
    // Route::get('/settings', SystemSettings::class)->name('settings');

    // Analytics e Estatísticas
    Route::get('/analytics', function () {
        return view('system.analytics');
    })->name('analytics');

    // Backup e Manutenção
    Route::get('/maintenance', function () {
        return view('system.maintenance');
    })->name('maintenance');

    // Auditoria
    Route::get('/audit', function () {
        return view('system.audit');
    })->name('audit');
});

/*
|--------------------------------------------------------------------------
| Área das Empresas
|--------------------------------------------------------------------------
*/

Route::get('companies/login', CompanyLogin::class)->name('company.login');
//Rotas para Admin de Empresa 
Route::prefix('company')->middleware(['auth.unified', 'user.type:company_admin,company_user'])->name('company.')->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    // ===== GESTÃO DE DADOS =====
    Route::prefix('manage')->name('manage.')->group(function () {

        // Funcionários
        Route::get('/employees', EmployeeManagement::class)->name('employees');

        // Clientes
        Route::get('/clients', ClientManagement::class)->name('clients');

        // Materiais
        Route::get('/materials', MaterialManagement::class)->name('materials');

        // Departamentos
        Route::get('/departments', DepartmentManagement::class)->name('departments');

        // Tipos de Manutenção
        Route::get('/maintenance-types', MaintenanceTypeManagement::class)->name('maintenance-types');

        // Estados
        Route::get('/statuses-locations', StatusLocationManagement::class)->name('statuses');

        // Localizações
        Route::get('/locations', function () {
            return view('company.manage.locations', [
                'title' => 'Gestão de Localizações',
                'company' => auth()->user()->company,
                'locations_count' => \App\Models\Company\Location::where('company_id', auth()->user()->company_id)->count()
            ]);
        })->name('locations');

        // Números de Máquina
        Route::get('/machine-numbers', MachineNumberManagement::class)->name('machine-numbers');

        // Solicitantes
        Route::get('/requesters', RequesterManagement::class)->name('requesters');

        // Custos por Cliente
        Route::get('/client-costs', ClientCostManagement::class)->name('client-costs');

        // Usuários e Permissões
        Route::get('/users-permissions', UserPermissionManagement::class)->name('users-permissions');
    });

    // ===== FORMULÁRIOS DE ORDENS DE REPARAÇÃO =====
    Route::prefix('repair-orders')->name('repair-orders.')->group(function () {

        // Formulário 1 - Inicial
        Route::get('/form1', RepairOrderForm1::class)->name('form1')->middleware('form.access:1');

        // Formulário 2 - Técnicos + Materiais  
        Route::get('/form2/{order?}', RepairOrderForm2::class)->name('form2')->middleware('form.access:2');

        // Formulário 3 - Faturação Real
        Route::get('/form3/{order?}', RepairOrderForm3::class)->name('form3')->middleware('form.access:3');

        // Formulário 4 - Número de Máquina
        Route::get('/form4/{order?}', RepairOrderForm4::class)->name('form4')->middleware('form.access:4');

        // Formulário 5 - Equipamento + Validação
        Route::get('/form5/{order?}', RepairOrderForm5::class)->name('form5')->middleware('form.access:5');;
    });

    // ===== LISTAGENS DE ORDENS =====
    Route::prefix('repair-orders')->name('orders.')->group(function () {
        Route::get('/', RepairOrdersList::class)->name('index');

        // Listagens por formulário
        Route::get('/form1-list', RepairOrdersForm1List::class)->name('form1-list');

        Route::get('/form2-list', RepairOrdersForm2List::class)->name('form2-list');

        Route::get('/form3-list', RepairOrdersForm3List::class)->name('form3-list');

        Route::get('/form4-list', RepairOrdersForm4List::class)->name('form4-list');

        Route::get('/form5-list', RepairOrdersForm5List::class)->name('form5-list');

        // Listagem avançada (todos os campos de todos os formulários)
        Route::get('/advanced-list', AdvancedListing::class)->name('advanced-list');
    });

    // ===== SISTEMA DE FATURAÇÃO =====
    Route::prefix('billing')->name('billing.')->middleware('permission:billing.view_all')->group(function () {

        // Faturação Real
        Route::get('/real', BillingRealManagement::class)->name('real')->middleware('permission:billing.real.manage');

        // Faturação Estimada
        Route::get('/estimated', BillingEstimatedManagement::class)->name('estimated');

        // Faturação HH (Preços do Sistema)
        Route::get('/hh', BillingHHManagement::class)->name('hh');
    });

    // ===== AVALIAÇÃO DE DESEMPENHO (apenas Company Admin) =====
    Route::prefix('performance')
        // ->middleware('user.type:company_admin')
        ->name('performance.')
        ->group(function () {

            // Gestão de Métricas
            Route::get('/metrics', MetricsManagement::class)->name('metrics');

            // Avaliações
            Route::get('/evaluations', EvaluationManagement::class)->name('evaluations')->middleware('permission:evaluation.create');
            Route::get('/evaluations-stages-management', ApprovalStageManagement::class)->name('evaluations.stages');

            Route::get('/evaluations/approvals', EvaluationApprovals::class)->name('evaluations.approvals');
            // Relatórios de Desempenho
            Route::get('/reports', EvaluationReports::class)->name('reports');
        });
    // ===== RELATÓRIOS E EXPORTAÇÕES =====
    Route::prefix('reports')->name('reports.')->group(function () {

        Route::get('/export', function () {
            return view('company.reports.export', [
                'title' => 'Exportações',
                'company' => auth()->user()->company,
                'available_exports' => [
                    'repair_orders' => 'Ordens de Reparação',
                    'billing' => 'Faturação',
                    'employees' => 'Funcionários',
                    'clients' => 'Clientes',
                    'materials' => 'Materiais',
                    'performance' => 'Avaliações de Desempenho'
                ]
            ]);
        })->name('export');

        Route::get('/analytics', function () {
            return view('company.reports.analytics', [
                'title' => 'Analytics',
                'company' => auth()->user()->company,
                'stats' => [
                    'orders_this_month' => 0,
                    'billing_this_month' => 0,
                    'active_employees' => \App\Models\Company\Employee::where('company_id', auth()->user()->company_id)->active()->count(),
                    'active_clients' => \App\Models\Company\Client::where('company_id', auth()->user()->company_id)->active()->count()
                ]
            ]);
        })->name('analytics');
    });

    // ===== CONFIGURAÇÕES DA EMPRESA (apenas Company Admin) =====
    Route::prefix('settings')
        ->middleware('user.type:company_admin')
        ->name('settings.')
        ->group(function () {

            Route::get('/profile', function () {
                return view('company.settings.profile', [
                    'title' => 'Perfil da Empresa',
                    'company' => auth()->user()->company
                ]);
            })->name('profile');

            Route::get('/users', function () {
                return view('company.settings.users', [
                    'title' => 'Gestão de Usuários',
                    'company' => auth()->user()->company,
                    'users' => auth()->user()->company->users
                ]);
            })->name('users');

            Route::get('/preferences', function () {
                return view('company.settings.preferences', [
                    'title' => 'Preferências',
                    'company' => auth()->user()->company,
                    'settings' => auth()->user()->company->settings ?? []
                ]);
            })->name('preferences');
        });
});



/*
|--------------------------------------------------------------------------
| System Authentication Routes
|--------------------------------------------------------------------------
*/

// Login específico para o sistema
Route::get('/system/login', \App\Livewire\System\SystemLogin::class)
    ->middleware('guest')
    ->name('system.login');

// Logout do sistema
Route::post('/system/logout', function () {
    $user = Auth::user();

    // Log do logout
    if ($user) {
        $logger = app(\App\Services\ActivityLoggerService::class);
        $logger->log(
            'system_logout',
            "Super Admin {$user->name} fez logout do sistema de administração",
            'auth',
            'info'
        );
    }

    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('system.login')->with('message', 'Logout realizado com sucesso.');
})
    ->middleware('auth')
    ->name('system.logout');

Route::post('/company/logout', function () {
    $user = Auth::user();

    // Log do logout
    if ($user) {
        $logger = app(\App\Services\ActivityLoggerService::class);
        $logger->log(
            'system_logout',
            "Usuário da empresa {$user->name} fez logout do sistema de administração",
            'auth',
            'info'
        );
    }

    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('company.login')->with('message', 'Logout realizado com sucesso.');
})
    ->middleware('auth')
    ->name('company.logout');

require __DIR__ . '/portal.php';
// require __DIR__ . '/auth.php';

