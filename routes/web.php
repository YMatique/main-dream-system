<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\System\CompanyController;
use App\Livewire\System\ActivityLogsManagement;
use App\Livewire\System\CompanyManagement;
use App\Livewire\System\PlanManagement;
use App\Livewire\System\SubscriptionManagement;
use App\Livewire\System\SystemDashboard;
use App\Livewire\System\UserManagement;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

// WEBSITE ROUTES
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, config('app.available_locales'))) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/sobre', function () {
    return view('pages.about');
})->name('about');

Route::get('/missao', function () {
    return view('pages.mission');
})->name('mission');

Route::get('/equipe', function () {
    return view('pages.team');
})->name('team');

Route::get('/servicos', function () {
    return view('pages.services');
})->name('services');

Route::get('/servicos/engenharia', function () {
    return view('pages.services.engineering');
})->name('services.engineering');

Route::get('/servicos/manutencao', function () {
    return view('pages.services.maintenance');
})->name('services.maintenance');

Route::get('/servicos/tecnologia', function () {
    return view('pages.services.technology');
})->name('services.technology');

Route::get('/servicos/pecas', function () {
    return view('pages.services.spare-parts');
})->name('services.spare_parts');

Route::get('/projetos', function () {
    return view('pages.projects');
})->name('projects');

Route::get('/contacto', function () {
    return view('pages.contact');
})->name('contact');



// 

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

//Rotas para Admin de Empresa 

// Administração da Empresa (Company Admin + Super Admin)
/*
|--------------------------------------------------------------------------
| Company Routes (/company) - Para Company Admin e Company User
|--------------------------------------------------------------------------
*/
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

/*
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
*/

/*
Route::prefix('company')
    ->middleware(['auth', 'verified', 'user_type:company_admin,company_user', 'active_subscription'])
    ->name('company.')
    ->group(function () {
        
        // Dashboard da Empresa
        Route::get('/dashboard', CompanyDashboard::class)->name('dashboard');
        
        // =============================================
        // GESTÃO DE DADOS DA EMPRESA
        // =============================================
        
        // Gestão de Funcionários/Técnicos
        Route::get('/employees', EmployeeManagement::class)->name('employees.index');
        
        // Gestão de Clientes
        Route::get('/clients', ClientManagement::class)->name('clients.index');
        
        // Gestão de Materiais
        Route::get('/materials', MaterialManagement::class)->name('materials.index');
        
        // Gestão de Departamentos
        Route::get('/departments', DepartmentManagement::class)->name('departments.index');
        
        // Tipos de Manutenção
        Route::get('/maintenance-types', MaintenanceTypeManagement::class)->name('maintenance-types.index');
        
        // Gestão de Números de Máquina
        Route::get('/machine-numbers', MachineNumberManagement::class)->name('machine-numbers.index');
        
        // Gestão de Estados
        Route::get('/statuses', StatusManagement::class)->name('statuses.index');
        
        // Gestão de Localização
        Route::get('/locations', LocationManagement::class)->name('locations.index');
        
        // Gestão de Solicitantes
        Route::get('/requesters', RequesterManagement::class)->name('requesters.index');
        
        // Custos por Cliente
        Route::get('/client-costs', ClientCostManagement::class)->name('client-costs.index');
        
        // =============================================
        // ORDENS DE REPARAÇÃO - 5 FORMULÁRIOS
        // =============================================
        
        Route::prefix('repair-orders')->name('repair-orders.')->group(function () {
            // Listagem de ordens
            Route::get('/', RepairOrdersList::class)->name('index');
            
            // Formulário 1 - Ordem inicial (carimbo, ordem de reparação, tipo manutenção, cliente, estado, localização, descrição avaria, mês, ano, solicitante, numero máquina)
            Route::get('/form1', RepairOrderForm1::class)->name('form1');
            Route::get('/form1/{repairOrder?}', RepairOrderForm1::class)->name('form1.edit');
            
            // Formulário 2 - Técnicos e materiais (carimbo, ordem reparação, localização, estado obra, tempo total horas, técnicos afetos, material, material_adicional, actividade realizada)
            Route::get('/form2', RepairOrderForm2::class)->name('form2');
            Route::get('/form2/{repairOrder}', RepairOrderForm2::class)->name('form2.edit');
            
            // Formulário 3 - Faturação (carimbo, ordem reparacao, localizacao, estado, data faturação, horas faturadas, materiais)
            Route::get('/form3', RepairOrderForm3::class)->name('form3');
            Route::get('/form3/{repairOrder}', RepairOrderForm3::class)->name('form3.edit');
            
            // Formulário 4 - Número de máquina (carimbo, ordem reparacao, localizacao, estado, numero máquina dinâmico)
            Route::get('/form4', RepairOrderForm4::class)->name('form4');
            Route::get('/form4/{repairOrder}', RepairOrderForm4::class)->name('form4.edit');
            
            // Formulário 5 - Equipamento e validação (carimbo, ordem reparacao, numero equipamento/maquina, data faturacao 1, horas faturadas 1, data faturacao 2, horas faturadas 2, cliente dinâmico, descrição actividades, técnico)
            Route::get('/form5', RepairOrderForm5::class)->name('form5');
            Route::get('/form5/{repairOrder}', RepairOrderForm5::class)->name('form5.edit');
            
            // Listagem avançada (campos customizáveis de todos os formulários)
            Route::get('/advanced-listing', AdvancedListing::class)->name('advanced-listing');
            
            // Exportações
            Route::get('/export/{type}', function ($type) {
                // Implementar exportação
                return response()->download(storage_path('app/exports/repair-orders-' . $type . '.xlsx'));
            })->name('export');
        });
        
        // =============================================
        // SISTEMA DE FATURAÇÃO (3 TIPOS)
        // =============================================
        
        Route::prefix('billing')->name('billing.')->group(function () {
            // Faturação HH (automática após formulário 2, usa preço do sistema)
            Route::get('/hh', BillingHH::class)->name('hh');
            
            // Faturação Estimada (permite ajuste de preços)
            Route::get('/estimated', BillingEstimated::class)->name('estimated');
            
            // Faturação Real (automática após formulário 3)
            Route::get('/real', BillingReal::class)->name('real');
            
            // Relatórios de faturação com filtros
            Route::get('/reports', BillingReports::class)->name('reports');
            
            // Gestão de moedas (alteração após faturação)
            Route::get('/currency-management', function () {
                return view('company.billing.currency-management');
            })->name('currency-management');
            
            // Exportações de faturação
            Route::get('/export/{type}', function ($type) {
                return response()->download(storage_path('app/exports/billing-' . $type . '.xlsx'));
            })->name('export');
        });
        
        // =============================================
        // AVALIAÇÃO DE DESEMPENHO (apenas company_admin)
        // =============================================
        
        Route::middleware('user_type:company_admin')->group(function () {
            Route::prefix('performance')->name('performance.')->group(function () {
                // Gestão de métricas dinâmicas por departamento
                Route::get('/metrics', PerformanceMetrics::class)->name('metrics');
                
                // Sistema de avaliação (0-10 ou Péssimo/Satisfatório/Bom/Excelente)
                Route::get('/evaluations', PerformanceEvaluations::class)->name('evaluations');
                Route::get('/evaluations/create', PerformanceEvaluations::class)->name('evaluations.create');
                Route::get('/evaluations/{evaluation}', PerformanceEvaluations::class)->name('evaluations.show');
                
                // Aprovação multi-estágio
                Route::get('/approvals', function () {
                    return view('company.performance.approvals');
                })->name('approvals');
                
                // Relatórios de desempenho por funcionário
                Route::get('/reports', PerformanceReports::class)->name('reports');
                
                // Configuração de pesos das métricas
                Route::get('/weights', function () {
                    return view('company.performance.weights');
                })->name('weights');
                
                // Notificações para avaliações < 50%
                Route::get('/notifications', function () {
                    return view('company.performance.notifications');
                })->name('notifications');
            });
        });
        
        // =============================================
        // RELATÓRIOS E EXPORTAÇÕES
        // =============================================
        
        Route::get('/reports', function () {
            return view('company.reports.index');
        })->name('reports');
        
        // Exportações gerais
        Route::get('/exports', function () {
            return view('company.exports');
        })->name('exports');
        
        // =============================================
        // CONFIGURAÇÕES DA EMPRESA (apenas company_admin)
        // =============================================
        
        Route::middleware('user_type:company_admin')->group(function () {
            Route::get('/settings', function () {
                return view('company.settings');
            })->name('settings');
            
            // Gestão de usuários da empresa
            Route::get('/users', function () {
                return view('company.users');
            })->name('users');
            
            // Configurações de notificações
            Route::get('/notifications', function () {
                return view('company.notifications');
            })->name('notifications');
        });
        
        // =============================================
        // PÁGINAS ESPECIAIS
        // =============================================
        
        // Página de subscrição expirada
        Route::get('/subscription-expired', function () {
            return view('company.subscription-expired');
        })->name('subscription.expired')->withoutMiddleware('active_subscription');
    });

    */




/*
|--------------------------------------------------------------------------
| Portal do Funcionário (/portal) - Para funcionários verem avaliações
|--------------------------------------------------------------------------
*/

/*
Route::prefix('portal')
    ->middleware(['auth', 'verified'])
    ->name('portal.')
    ->group(function () {
        
        // Dashboard do Portal do Funcionário
        Route::get('/dashboard', EmployeeDashboard::class)->name('dashboard');
        
        // Minhas Avaliações (acesso por link + credenciais)
        Route::get('/evaluations', EmployeeEvaluations::class)->name('evaluations');
        Route::get('/evaluations/{month}/{year}', EmployeeEvaluations::class)->name('evaluations.monthly');
        
        // Histórico de Desempenho com pesquisa por mês
        Route::get('/performance-history', EmployeePerformanceHistory::class)->name('performance-history');
        
        // Perfil do Funcionário
        Route::get('/profile', EmployeeProfile::class)->name('profile');
        
        // Impressão de avaliações
        Route::get('/evaluations/{evaluation}/print', function ($evaluation) {
            return view('portal.evaluations.print', compact('evaluation'));
        })->name('evaluations.print');
        
        // Download de avaliações
        Route::get('/evaluations/{evaluation}/download', function ($evaluation) {
            return response()->download(storage_path('app/evaluations/evaluation-' . $evaluation . '.pdf'));
        })->name('evaluations.download');
    });
 */
require __DIR__.'/auth.php';
