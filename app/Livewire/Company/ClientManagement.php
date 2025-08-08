<?php

namespace App\Livewire\Company;

use App\Models\Company\Client;
use App\Models\Company\ClientCost;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

use Livewire\Component;

class ClientManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $showCostsModal = false;
    public $editingId = null;
    public $deleteId = null;
    public $viewingCostsId = null;

    // Form properties
    public $name = '';
    public $description = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $is_active = true;

    // Filter properties
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    // View mode
    public $viewMode = 'grid'; // 'grid' or 'table'

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('clients', 'name')
                    ->where('company_id', auth()->user()->company_id)
                    ->ignore($this->editingId)
            ],
            'description' => 'nullable|string|max:500',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('clients', 'email')
                    ->where('company_id', auth()->user()->company_id)
                    ->ignore($this->editingId)
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
        'name.unique' => 'Já existe um cliente com este nome.',
        'description.max' => 'A descrição deve ter no máximo 500 caracteres.',
        'email.email' => 'Digite um email válido.',
        'email.unique' => 'Este email já está sendo usado.',
        'phone.max' => 'O telefone deve ter no máximo 20 caracteres.',
        'address.max' => 'O endereço deve ter no máximo 500 caracteres.',
    ];

    public function render()
    {
        $clients = $this->getClients();
        return view('livewire.company.client-management', compact('clients'))
            ->title('Gestão de Clientes')
            ->layout('layouts.company');
    }
    public function getClients()
    {
        return Client::withCount(['clientCosts'])
            ->where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('address', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->orderBy('name')
            ->paginate($this->perPage);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'company_id' => auth()->user()->company_id,
                'name' => $this->name,
                'description' => $this->description ?: null,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'address' => $this->address ?: null,
                'is_active' => $this->is_active,
            ];

            if ($this->editingId) {
                $client = Client::findOrFail($this->editingId);
                $client->update($data);
                session()->flash('success', 'Cliente atualizado com sucesso!');
            } else {
                Client::create($data);
                session()->flash('success', 'Cliente criado com sucesso!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar cliente: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $client = Client::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $client->name;
        $this->description = $client->description;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->address = $client->address;
        $this->is_active = $client->is_active;
        
        $this->showModal = true;
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $client = Client::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteId);
            
            // Check if client has associated repair orders or costs
            if ($client->clientCosts()->exists()) {
                session()->flash('error', 'Não é possível eliminar cliente com custos associados. Desative-o em vez disso.');
                $this->showDeleteModal = false;
                return;
            }
            
            // TODO: Check repair orders when implemented
            
            $client->delete();
            session()->flash('success', 'Cliente eliminado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar cliente: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function toggleStatus($id)
    {
        try {
            $client = Client::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newStatus = !$client->is_active;
            $client->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'ativado' : 'desativado';
            session()->flash('success', "Cliente {$statusText} com sucesso!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    public function viewCosts($clientId)
    {
        $this->viewingCostsId = $clientId;
        $this->showCostsModal = true;
    }

    public function closeCostsModal()
    {
        $this->showCostsModal = false;
        $this->viewingCostsId = null;
    }

    public function getClientCostsProperty()
    {
        if (!$this->viewingCostsId) return collect();
        
        return ClientCost::with(['maintenanceType'])
            ->where('company_id', auth()->user()->company_id)
            ->where('client_id', $this->viewingCostsId)
            ->orderBy('effective_date', 'desc')
            ->get();
    }

    public function duplicateClient($id)
    {
        try {
            $client = Client::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newClient = $client->replicate();
            $newClient->name = $client->name . ' (Cópia)';
            $newClient->email = null; // Clear unique fields
            $newClient->save();
            
            session()->flash('success', 'Cliente duplicado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao duplicar cliente: ' . $e->getMessage());
        }
    }

    // Bulk actions
    public function bulkActivate($ids)
    {
        Client::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => true]);
            
        session()->flash('success', 'Clientes ativados com sucesso!');
    }

    public function bulkDeactivate($ids)
    {
        Client::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => false]);
            
        session()->flash('success', 'Clientes desativados com sucesso!');
    }

    // Export functionality
    public function export($format = 'excel')
    {
        $clients = $this->getClients()->items();
        
        // TODO: Implement export service
        session()->flash('info', 'Exportação em desenvolvimento...');
    }

    // Statistics
    public function getStatsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total' => Client::where('company_id', $companyId)->count(),
            'active' => Client::where('company_id', $companyId)->active()->count(),
            'inactive' => Client::where('company_id', $companyId)->where('is_active', false)->count(),
            'with_costs' => Client::where('company_id', $companyId)
                ->whereHas('clientCosts')
                ->count(),
            'total_repair_orders' => 0, // TODO: Count when repair orders implemented
        ];
    }

    // Client quick info
    public function getClientInfo($clientId)
    {
        return Client::with(['clientCosts.maintenanceType'])
            ->where('company_id', auth()->user()->company_id)
            ->findOrFail($clientId);
    }

    // Toggle view mode
    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'grid' ? 'table' : 'grid';
    }

    // Livewire lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
