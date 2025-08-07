<?php

namespace App\Livewire\Company;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
     public $companyName;
    public $userName;
    public $userType;
    
    // Stats básicos (implementaremos depois com dados reais)
    public $stats = [
        'total_orders' => 0,
        'pending_orders' => 0,
        'completed_orders' => 0,
        'total_billing' => 0,
        'active_employees' => 0,
        'active_clients' => 0,
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->companyName = $user->company->name;
        $this->userName = $user->name;
        $this->userType = $user->user_type === 'company_admin' ? 'Administrador' : 'Usuário';
        
        // TODO: Implementar cálculos reais quando tivermos os modelos
        $this->loadStats();
    }

    private function loadStats()
    {
        // Por enquanto, dados fictícios - implementaremos depois
        $this->stats = [
            'total_orders' => 147,
            'pending_orders' => 23,
            'completed_orders' => 124,
            'total_billing' => 125450.00,
            'active_employees' => 12,
            'active_clients' => 8,
        ];
    }

    #[Layout('layouts.company')]
    #[Title('Dashboard')]

    public function render()
    {
        return view('livewire.company.dashboard');
    }
}
