<?php

namespace App\Livewire\System;

use App\Models\System\Company;
use App\Models\System\Plan;
use App\Models\System\Subscription;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class SubscriptionManagement extends Component
{
     use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $showCancelModal = false;
    public $cancelId = null;
    public $cancelReason = '';

    // Form fields
    public $company_id = '';
    public $plan_id = '';
    public $starts_at = '';
    public $ends_at = '';
    public $billing_cycle = 'monthly';
    public $amount_paid_mzn = '';
    public $amount_paid_usd = '';
    public $payment_currency = 'MZN';
    public $notes = '';

    // Search and filters
    public $search = '';
    public $statusFilter = '';
    public $planFilter = '';
    public $companyFilter = '';
    public $expiringFilter = '';
    public $perPage = 10;

    protected $queryString = ['search', 'statusFilter', 'planFilter', 'companyFilter', 'expiringFilter'];

    protected function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'plan_id' => 'required|exists:plans,id',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'billing_cycle' => 'required|in:monthly,quarterly,annual',
            'amount_paid_mzn' => 'required|numeric|min:0',
            'amount_paid_usd' => 'required|numeric|min:0',
            'payment_currency' => 'required|in:MZN,USD',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'company_id.required' => 'A empresa é obrigatória.',
        'plan_id.required' => 'O plano é obrigatório.',
        'starts_at.required' => 'A data de início é obrigatória.',
        'ends_at.required' => 'A data de fim é obrigatória.',
        'ends_at.after' => 'A data de fim deve ser posterior à data de início.',
    ];
    public function render()
    {
         $subscriptions = Subscription::query()
            ->with(['company', 'plan'])
            ->when($this->search, function ($query) {
                $query->whereHas('company', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })->orWhereHas('plan', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'active') {
                    $query->active();
                } elseif ($this->statusFilter === 'expired') {
                    $query->expired();
                } else {
                    $query->where('status', $this->statusFilter);
                }
            })
            ->when($this->planFilter, function ($query) {
                $query->where('plan_id', $this->planFilter);
            })
            ->when($this->companyFilter, function ($query) {
                $query->where('company_id', $this->companyFilter);
            })
            ->when($this->expiringFilter, function ($query) {
                if ($this->expiringFilter === 'expiring_30') {
                    $query->expiringInDays(30);
                } elseif ($this->expiringFilter === 'expiring_7') {
                    $query->expiringInDays(7);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $companies = Company::active()->orderBy('name')->get();
        $plans = Plan::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('livewire.system.subscription-management',compact('subscriptions', 'companies', 'plans'))
            ->layout('layouts.system');
    }
}
