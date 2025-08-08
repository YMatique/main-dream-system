<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\System\CompanyController;
use App\Livewire\Auth\System\ForgotPassword;
use App\Livewire\Auth\System\ResetPassword;
use App\Livewire\Company\ClientManagement;
use App\Livewire\Company\CompanyLogin;
use App\Livewire\Company\Dashboard;
use App\Livewire\Company\DepartmentManagement;
use App\Livewire\Company\EmployeeManagement;
use App\Livewire\Company\MachineNumberManagement;
use App\Livewire\Company\RequesterManagement;
use App\Livewire\System\ActivityLogsManagement;
use App\Livewire\System\CompanyManagement;
use App\Livewire\System\PlanManagement;
use App\Livewire\System\SubscriptionManagement;
use App\Livewire\System\SystemDashboard;
use App\Livewire\System\UserManagement;
use App\Livewire\Website\Home;
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
    
    // Sobre
    Route::get('/sobre', function () {
        return view('website.about');
    })->name('about');
    
    Route::get('/missao', function () {
        return view('website.mission');
    })->name('mission');
    
    Route::get('/equipe', function () {
        return view('website.team');
    })->name('team');
    
    // Serviços
    Route::get('/servicos', function () {
        return view('website.services');
    })->name('services');
    
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
    Route::get('/projetos', function () {
        return view('website.projects');
    })->name('projects');
    
    // Contato
    Route::get('/contacto', function () {
        return view('website.contact');
    })->name('contact');
    
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

Route::middleware(['auth.unified','user.type:super_admin'])->prefix('system')->name('system.')->group(function () {
    
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
Route::prefix('company')->middleware(['auth.unified', 'user.type:company_admin,company_user'])->name('company.')->group(function(){
   Route::get('dashboard', Dashboard::class)->name('dashboard'); 

   // ===== GESTÃO DE DADOS =====
        Route::prefix('manage')->name('manage.')->group(function () {
            
            // Funcionários
            Route::get('/employees',EmployeeManagement::class)->name('employees');
            
            // Clientes
            Route::get('/clients', ClientManagement::class)->name('clients');
            
            // Materiais
            Route::get('/materials', function () {
                return view('company.manage.materials', [
                    'title' => 'Gestão de Materiais',
                    'company' => auth()->user()->company,
                    'materials_count' => \App\Models\Company\Material::where('company_id', auth()->user()->company_id)->count()
                ]);
            })->name('materials');
            
            // Departamentos
            Route::get('/departments', DepartmentManagement::class)->name('departments');
            
            // Tipos de Manutenção
            Route::get('/maintenance-types', function () {
                return view('company.manage.maintenance-types', [
                    'title' => 'Tipos de Manutenção',
                    'company' => auth()->user()->company,
                    'types_count' => \App\Models\Company\MaintenanceType::where('company_id', auth()->user()->company_id)->count()
                ]);
            })->name('maintenance-types');
            
            // Estados
            Route::get('/statuses', function () {
                return view('company.manage.statuses', [
                    'title' => 'Gestão de Estados',
                    'company' => auth()->user()->company,
                    'statuses_count' => \App\Models\Company\Status::where('company_id', auth()->user()->company_id)->count()
                ]);
            })->name('statuses');
            
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
            Route::get('/client-costs', function () {
                return view('company.manage.client-costs', [
                    'title' => 'Custos por Cliente',
                    'company' => auth()->user()->company,
                    'costs_count' => \App\Models\Company\ClientCost::where('company_id', auth()->user()->company_id)->count()
                ]);
            })->name('client-costs');
        });

        // ===== FORMULÁRIOS DE ORDENS DE REPARAÇÃO =====
        Route::prefix('repair-orders')->name('repair-orders.')->group(function () {
            
            // Formulário 1 - Inicial
            Route::get('/form1', function () {
                return view('company.repair-orders.form1', [
                    'title' => 'Formulário 1 - Ordem Inicial',
                    'company' => auth()->user()->company,
                    'clients' => \App\Models\Company\Client::where('company_id', auth()->user()->company_id)->active()->get(),
                    'maintenance_types' => \App\Models\Company\MaintenanceType::where('company_id', auth()->user()->company_id)->active()->get(),
                    'statuses' => \App\Models\Company\Status::where('company_id', auth()->user()->company_id)->forForm('form1')->get(),
                    'locations' => \App\Models\Company\Location::where('company_id', auth()->user()->company_id)->forForm('form1')->get(),
                    'requesters' => \App\Models\Company\Requester::where('company_id', auth()->user()->company_id)->active()->get(),
                    'machine_numbers' => \App\Models\Company\MachineNumber::where('company_id', auth()->user()->company_id)->active()->get()
                ]);
            })->name('form1');
            
            // Formulário 2 - Técnicos + Materiais  
            Route::get('/form2/{order?}', function ($order = null) {
                return view('company.repair-orders.form2', [
                    'title' => 'Formulário 2 - Técnicos e Materiais',
                    'company' => auth()->user()->company,
                    'order' => $order,
                    'employees' => \App\Models\Company\Employee::where('company_id', auth()->user()->company_id)->active()->get(),
                    'materials' => \App\Models\Company\Material::where('company_id', auth()->user()->company_id)->active()->get(),
                    'statuses' => \App\Models\Company\Status::where('company_id', auth()->user()->company_id)->forForm('form2')->get(),
                    'locations' => \App\Models\Company\Location::where('company_id', auth()->user()->company_id)->forForm('form2')->get()
                ]);
            })->name('form2');
            
            // Formulário 3 - Faturação Real
            Route::get('/form3/{order?}', function ($order = null) {
                return view('company.repair-orders.form3', [
                    'title' => 'Formulário 3 - Faturação Real',
                    'company' => auth()->user()->company,
                    'order' => $order,
                    'materials' => \App\Models\Company\Material::where('company_id', auth()->user()->company_id)->active()->get(),
                    'statuses' => \App\Models\Company\Status::where('company_id', auth()->user()->company_id)->forForm('form3')->get(),
                    'locations' => \App\Models\Company\Location::where('company_id', auth()->user()->company_id)->forForm('form3')->get()
                ]);
            })->name('form3');
            
            // Formulário 4 - Número de Máquina
            Route::get('/form4/{order?}', function ($order = null) {
                return view('company.repair-orders.form4', [
                    'title' => 'Formulário 4 - Número de Máquina',
                    'company' => auth()->user()->company,
                    'order' => $order,
                    'statuses' => \App\Models\Company\Status::where('company_id', auth()->user()->company_id)->forForm('form4')->get(),
                    'locations' => \App\Models\Company\Location::where('company_id', auth()->user()->company_id)->forForm('form4')->get(),
                    'machine_numbers' => \App\Models\Company\MachineNumber::where('company_id', auth()->user()->company_id)->active()->get()
                ]);
            })->name('form4');
            
            // Formulário 5 - Equipamento + Validação
            Route::get('/form5/{order?}', function ($order = null) {
                return view('company.repair-orders.form5', [
                    'title' => 'Formulário 5 - Equipamento e Validação',
                    'company' => auth()->user()->company,
                    'order' => $order,
                    'clients' => \App\Models\Company\Client::where('company_id', auth()->user()->company_id)->active()->get(),
                    'employees' => \App\Models\Company\Employee::where('company_id', auth()->user()->company_id)->active()->get(),
                    'machine_numbers' => \App\Models\Company\MachineNumber::where('company_id', auth()->user()->company_id)->active()->get()
                ]);
            })->name('form5');
        });
        
        // ===== LISTAGENS DE ORDENS =====
        Route::prefix('orders')->name('orders.')->group(function () {
            
            // Listagens por formulário
            Route::get('/form1-list', function () {
                return view('company.orders.list', [
                    'title' => 'Listagem - Formulário 1',
                    'form_type' => 'form1',
                    'company' => auth()->user()->company,
                    'orders_count' => 0 // TODO: implementar quando tiver RepairOrder model
                ]);
            })->name('form1-list');
            
            Route::get('/form2-list', function () {
                return view('company.orders.list', [
                    'title' => 'Listagem - Formulário 2', 
                    'form_type' => 'form2',
                    'company' => auth()->user()->company,
                    'orders_count' => 0
                ]);
            })->name('form2-list');
            
            Route::get('/form3-list', function () {
                return view('company.orders.list', [
                    'title' => 'Listagem - Formulário 3',
                    'form_type' => 'form3', 
                    'company' => auth()->user()->company,
                    'orders_count' => 0
                ]);
            })->name('form3-list');
            
            Route::get('/form4-list', function () {
                return view('company.orders.list', [
                    'title' => 'Listagem - Formulário 4',
                    'form_type' => 'form4',
                    'company' => auth()->user()->company,
                    'orders_count' => 0
                ]);
            })->name('form4-list');
            
            Route::get('/form5-list', function () {
                return view('company.orders.list', [
                    'title' => 'Listagem - Formulário 5',
                    'form_type' => 'form5',
                    'company' => auth()->user()->company,
                    'orders_count' => 0
                ]);
            })->name('form5-list');
            
            // Listagem avançada (todos os campos de todos os formulários)
            Route::get('/advanced-list', function () {
                return view('company.orders.advanced-list', [
                    'title' => 'Listagem Avançada',
                    'company' => auth()->user()->company,
                    'all_fields' => [
                        'form1' => ['carimbo', 'ordem_reparacao', 'tipo_manutencao', 'cliente', 'estado', 'localizacao', 'descricao_avaria', 'mes', 'ano', 'solicitante', 'numero_maquina'],
                        'form2' => ['carimbo', 'ordem_reparacao', 'localizacao', 'estado_obra', 'tempo_total', 'tecnicos', 'material', 'material_adicional', 'actividade_realizada'],
                        'form3' => ['carimbo', 'ordem_reparacao', 'localizacao', 'estado', 'data_faturacao', 'horas_faturadas', 'materiais'],
                        'form4' => ['carimbo', 'ordem_reparacao', 'localizacao', 'estado', 'numero_maquina'],
                        'form5' => ['carimbo', 'ordem_reparacao', 'numero_equipamento', 'data_faturacao_1', 'horas_faturadas_1', 'data_faturacao_2', 'horas_faturadas_2', 'cliente', 'descricao_actividades', 'tecnico']
                    ]
                ]);
            })->name('advanced-list');
        });
        
        // ===== SISTEMA DE FATURAÇÃO =====
        Route::prefix('billing')->name('billing.')->group(function () {
            
            // Faturação Real
            Route::get('/real', function () {
                return view('company.billing.real', [
                    'title' => 'Faturação Real',
                    'company' => auth()->user()->company,
                    'billing_count' => 0, // TODO: implementar
                    'total_amount_mzn' => 0,
                    'total_amount_usd' => 0
                ]);
            })->name('real');
            
            // Faturação Estimada
            Route::get('/estimated', function () {
                return view('company.billing.estimated', [
                    'title' => 'Faturação Estimada',
                    'company' => auth()->user()->company,
                    'billing_count' => 0,
                    'total_amount_mzn' => 0,
                    'total_amount_usd' => 0
                ]);
            })->name('estimated');
            
            // Faturação HH (Preços do Sistema)
            Route::get('/hh', function () {
                return view('company.billing.hh', [
                    'title' => 'Faturação HH',
                    'company' => auth()->user()->company,
                    'billing_count' => 0,
                    'total_amount_mzn' => 0,
                    'total_amount_usd' => 0
                ]);
            })->name('hh');
        });
        
        // ===== AVALIAÇÃO DE DESEMPENHO (apenas Company Admin) =====
        Route::prefix('performance')
            ->middleware('user.type:company_admin')
            ->name('performance.')
            ->group(function () {
                
                // Gestão de Métricas
                Route::get('/metrics', function () {
                    return view('company.performance.metrics', [
                        'title' => 'Métricas de Desempenho',
                        'company' => auth()->user()->company,
                        'departments' => \App\Models\Company\Department::where('company_id', auth()->user()->company_id)->active()->get(),
                        'metrics_count' => 0 // TODO: implementar PerformanceMetric model
                    ]);
                })->name('metrics');
                
                // Avaliações
                Route::get('/evaluations', function () {
                    return view('company.performance.evaluations', [
                        'title' => 'Avaliações de Desempenho',
                        'company' => auth()->user()->company,
                        'employees' => \App\Models\Company\Employee::where('company_id', auth()->user()->company_id)->active()->get(),
                        'evaluations_count' => 0 // TODO: implementar
                    ]);
                })->name('evaluations');
                
                // Relatórios de Desempenho
                Route::get('/reports', function () {
                    return view('company.performance.reports', [
                        'title' => 'Relatórios de Desempenho',
                        'company' => auth()->user()->company,
                        'employees' => \App\Models\Company\Employee::where('company_id', auth()->user()->company_id)->active()->get(),
                        'departments' => \App\Models\Company\Department::where('company_id', auth()->user()->company_id)->active()->get()
                    ]);
                })->name('reports');
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


// ===== PORTAL DO FUNCIONÁRIO =====
Route::prefix('employee')
    ->middleware(['auth.unified', 'user.type:company_admin,company_user'])
    ->name('employee.')
    ->group(function () {
        
        Route::get('/portal', function () {
            return view('employee.portal', [
                'title' => 'Portal do Funcionário',
                'user' => auth()->user(),
                'company' => auth()->user()->company,
                'employee' => auth()->user()->company->employees()->where('email', auth()->user()->email)->first()
            ]);
        })->name('portal');
        
        Route::get('/performance', function () {
            return view('employee.performance', [
                'title' => 'Meu Desempenho',
                'user' => auth()->user(),
                'employee' => auth()->user()->company->employees()->where('email', auth()->user()->email)->first(),
                'evaluations' => [] // TODO: implementar quando tiver PerformanceEvaluation model
            ]);
        })->name('performance');
        
        Route::get('/profile', function () {
            return view('employee.profile', [
                'title' => 'Meu Perfil',
                'user' => auth()->user(),
                'employee' => auth()->user()->company->employees()->where('email', auth()->user()->email)->first()
            ]);
        })->name('profile');
    });
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
