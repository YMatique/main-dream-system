<?php

namespace App\Livewire\System;
use App\Models\System\Plan;
use Livewire\Component;
use Livewire\WithPagination;

class PlanManagement extends Component
{

    use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $deleteId = null;
    public $showDeleteModal = false;

    // Form fields
    public $name = '';
    public $description = '';
    public $max_users = '';
    public $max_orders = '';
    public $features = [];
    public $price_mzn = '';
    public $price_usd = '';
    public $billing_cycle = 'monthly';
    public $is_active = true;
    public $sort_order = 0;

    // Search and filters
    public $search = '';
    public $statusFilter = '';
    public $billingCycleFilter = '';
    public $perPage = 10;

    protected $queryString = ['search', 'statusFilter', 'billingCycleFilter'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_users' => 'nullable|integer|min:1',
            'max_orders' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'price_mzn' => 'required|numeric|min:0',
            'price_usd' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,annual',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome do plano é obrigatório.',
        'price_mzn.required' => 'O preço em MZN é obrigatório.',
        'price_usd.required' => 'O preço em USD é obrigatório.',
        'billing_cycle.required' => 'O ciclo de faturação é obrigatório.',
    ];

    public function render()
    {

          $plans = Plan::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'active') {
                    $query->where('is_active', true);
                } else {
                    $query->where('is_active', false);
                }
            })
            ->when($this->billingCycleFilter, function ($query) {
                $query->where('billing_cycle', $this->billingCycleFilter);
            })
            ->ordered()
            ->paginate($this->perPage);

        $availableFeatures = Plan::getCommonFeatures();

        return view('livewire.system.plan-management', compact('plans', 'availableFeatures'))->layout('layouts.system');
    }
}
