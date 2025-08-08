<?php

namespace App\Livewire\Company;


use App\Models\Company\Status;
use App\Models\Company\Location;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Component;

class StatusLocationManagement extends Component
{
    use WithPagination;

    // Tab management
    public $activeTab = 'statuses'; // 'statuses' or 'locations'

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $editingId = null;
    public $deleteId = null;
    public $editingType = ''; // 'status' or 'location'

    // Form properties for Status
    public $status_name = '';
    public $status_description = '';
    public $status_form_type = 'form1';
    public $status_color = '#6B7280';
    public $status_is_final = false;
    public $status_sort_order = 0;

    // Form properties for Location
    public $location_name = '';
    public $location_description = '';
    public $location_form_type = 'form1';

    // Filter properties
    public $search = '';
    public $formTypeFilter = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Form types available
    public $formTypes = [
        'form1' => 'Formulário 1 - Inicial',
        'form2' => 'Formulário 2 - Técnicos',
        'form3' => 'Formulário 3 - Faturação',
        'form4' => 'Formulário 4 - Máquina',
        'form5' => 'Formulário 5 - Equipamento'
    ];

    // Predefined colors for statuses
    public $statusColors = [
        '#6B7280' => 'Cinza',
        '#3B82F6' => 'Azul',
        '#10B981' => 'Verde',
        '#F59E0B' => 'Amarelo',
        '#EF4444' => 'Vermelho',
        '#8B5CF6' => 'Roxo',
        '#F97316' => 'Laranja',
        '#06B6D4' => 'Ciano',
        '#84CC16' => 'Lima',
        '#EC4899' => 'Rosa'
    ];

    protected function getStatusRules()
    {
        return [
            'status_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('statuses', 'name')
                    ->where('company_id', auth()->user()->company_id)
                    ->where('form_type', $this->status_form_type)
                    ->ignore($this->editingId)
            ],
            'status_description' => 'nullable|string|max:500',
            'status_form_type' => 'required|in:form1,form2,form3,form4,form5',
            'status_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'status_is_final' => 'boolean',
            'status_sort_order' => 'integer|min:0|max:999',
        ];
    }

    protected function getLocationRules()
    {
        return [
            'location_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('locations', 'name')
                    ->where('company_id', auth()->user()->company_id)
                    ->where('form_type', $this->location_form_type)
                    ->ignore($this->editingId)
            ],
            'location_description' => 'nullable|string|max:500',
            'location_form_type' => 'required|in:form1,form2,form3,form4,form5',
        ];
    }

    protected $messages = [
        'status_name.required' => 'O nome do status é obrigatório.',
        'status_name.unique' => 'Já existe um status com este nome para este formulário.',
        'status_form_type.required' => 'Selecione um formulário.',
        'status_color.required' => 'Selecione uma cor.',
        'location_name.required' => 'O nome da localização é obrigatório.',
        'location_name.unique' => 'Já existe uma localização com este nome para este formulário.',
        'location_form_type.required' => 'Selecione um formulário.',
    ];

    public function render()
    {
         if ($this->activeTab === 'statuses') {
            $data = $this->getStatuses();
            $dataKey = 'statuses';
        } else {
            $data = $this->getLocations();
            $dataKey = 'locations';
        }
        
        return view('livewire.company.status-location-management', [
            $dataKey => $data,
            'formTypes' => $this->formTypes
        ])
        ->title('Estados & Localização')
        ->layout('layouts.company');
    }

      public function getStatuses()
    {
        return Status::where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->formTypeFilter, function ($query) {
                $query->where('form_type', $this->formTypeFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->orderBy('sort_order')
            ->paginate($this->perPage);
    }

    public function getLocations()
    {
        return Location::where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->formTypeFilter, function ($query) {
                $query->where('form_type', $this->formTypeFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->resetFilters();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->formTypeFilter = '';
    }

    public function openModal($type)
    {
        $this->editingType = $type;
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
        
        // Reset status fields
        $this->status_name = '';
        $this->status_description = '';
        $this->status_form_type = 'form1';
        $this->status_color = '#6B7280';
        $this->status_is_final = false;
        $this->status_sort_order = 0;
        
        // Reset location fields
        $this->location_name = '';
        $this->location_description = '';
        $this->location_form_type = 'form1';
    }

    public function save()
    {
        if ($this->editingType === 'status') {
            $this->saveStatus();
        } else {
            $this->saveLocation();
        }
    }

    public function saveStatus()
    {
        $this->validate($this->getStatusRules());

        try {
            $data = [
                'company_id' => auth()->user()->company_id,
                'name' => $this->status_name,
                'description' => $this->status_description ?: null,
                'form_type' => $this->status_form_type,
                'color' => $this->status_color,
                'is_final' => $this->status_is_final,
                'sort_order' => $this->status_sort_order,
            ];

            if ($this->editingId) {
                $status = Status::findOrFail($this->editingId);
                $status->update($data);
                session()->flash('success', 'Status atualizado com sucesso!');
            } else {
                Status::create($data);
                session()->flash('success', 'Status criado com sucesso!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar status: ' . $e->getMessage());
        }
    }

    public function saveLocation()
    {
        $this->validate($this->getLocationRules());

        try {
            $data = [
                'company_id' => auth()->user()->company_id,
                'name' => $this->location_name,
                'description' => $this->location_description ?: null,
                'form_type' => $this->location_form_type,
            ];

            if ($this->editingId) {
                $location = Location::findOrFail($this->editingId);
                $location->update($data);
                session()->flash('success', 'Localização atualizada com sucesso!');
            } else {
                Location::create($data);
                session()->flash('success', 'Localização criada com sucesso!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar localização: ' . $e->getMessage());
        }
    }

    public function editStatus($id)
    {
        $status = Status::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $this->editingType = 'status';
        $this->editingId = $id;
        $this->status_name = $status->name;
        $this->status_description = $status->description;
        $this->status_form_type = $status->form_type;
        $this->status_color = $status->color;
        $this->status_is_final = $status->is_final;
        $this->status_sort_order = $status->sort_order;
        
        $this->showModal = true;
    }

    public function editLocation($id)
    {
        $location = Location::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $this->editingType = 'location';
        $this->editingId = $id;
        $this->location_name = $location->name;
        $this->location_description = $location->description;
        $this->location_form_type = $location->form_type;
        
        $this->showModal = true;
    }

    public function confirmDelete($id, $type)
    {
        $this->deleteId = $id;
        $this->editingType = $type;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            if ($this->editingType === 'status') {
                $status = Status::where('company_id', auth()->user()->company_id)
                    ->findOrFail($this->deleteId);
                
                // TODO: Check if status is being used in repair orders
                
                $status->delete();
                session()->flash('success', 'Status eliminado com sucesso!');
            } else {
                $location = Location::where('company_id', auth()->user()->company_id)
                    ->findOrFail($this->deleteId);
                
                // TODO: Check if location is being used in repair orders
                
                $location->delete();
                session()->flash('success', 'Localização eliminada com sucesso!');
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->editingType = '';
    }

    // Create default statuses for all forms
    public function createDefaultStatuses()
    {
        try {
            $defaultStatuses = [
                // Form 1 - Initial
                ['form' => 'form1', 'name' => 'Pendente', 'description' => 'Aguardando processamento', 'color' => '#F59E0B', 'final' => false, 'order' => 1],
                ['form' => 'form1', 'name' => 'Em Análise', 'description' => 'Em análise técnica', 'color' => '#3B82F6', 'final' => false, 'order' => 2],
                ['form' => 'form1', 'name' => 'Aprovado', 'description' => 'Aprovado para execução', 'color' => '#10B981', 'final' => true, 'order' => 3],
                ['form' => 'form1', 'name' => 'Rejeitado', 'description' => 'Rejeitado', 'color' => '#EF4444', 'final' => true, 'order' => 4],
                
                // Form 2 - Technicians
                ['form' => 'form2', 'name' => 'Aguardando Técnicos', 'description' => 'Aguardando disponibilidade', 'color' => '#F59E0B', 'final' => false, 'order' => 1],
                ['form' => 'form2', 'name' => 'Em Execução', 'description' => 'Trabalho em andamento', 'color' => '#3B82F6', 'final' => false, 'order' => 2],
                ['form' => 'form2', 'name' => 'Concluído', 'description' => 'Trabalho concluído', 'color' => '#10B981', 'final' => true, 'order' => 3],
                ['form' => 'form2', 'name' => 'Suspenso', 'description' => 'Trabalho suspenso', 'color' => '#EF4444', 'final' => false, 'order' => 4],
                
                // Form 3 - Billing
                ['form' => 'form3', 'name' => 'Aguardando Faturação', 'description' => 'Aguardando processamento', 'color' => '#F59E0B', 'final' => false, 'order' => 1],
                ['form' => 'form3', 'name' => 'Faturado', 'description' => 'Fatura emitida', 'color' => '#10B981', 'final' => true, 'order' => 2],
                ['form' => 'form3', 'name' => 'Pago', 'description' => 'Pagamento recebido', 'color' => '#06B6D4', 'final' => true, 'order' => 3],
                ['form' => 'form3', 'name' => 'Cancelado', 'description' => 'Faturação cancelada', 'color' => '#EF4444', 'final' => true, 'order' => 4],
                
                // Form 4 - Machine
                ['form' => 'form4', 'name' => 'Registado', 'description' => 'Máquina registada', 'color' => '#10B981', 'final' => false, 'order' => 1],
                ['form' => 'form4', 'name' => 'Em Teste', 'description' => 'Em fase de teste', 'color' => '#F59E0B', 'final' => false, 'order' => 2],
                ['form' => 'form4', 'name' => 'Operacional', 'description' => 'Máquina operacional', 'color' => '#10B981', 'final' => true, 'order' => 3],
                
                // Form 5 - Equipment
                ['form' => 'form5', 'name' => 'Aguardando Validação', 'description' => 'Aguardando validação', 'color' => '#F59E0B', 'final' => false, 'order' => 1],
                ['form' => 'form5', 'name' => 'Validado', 'description' => 'Equipamento validado', 'color' => '#10B981', 'final' => true, 'order' => 2],
                ['form' => 'form5', 'name' => 'Rejeitado', 'description' => 'Validação rejeitada', 'color' => '#EF4444', 'final' => true, 'order' => 3],
            ];

            $created = 0;
            foreach ($defaultStatuses as $status) {
                $exists = Status::where('company_id', auth()->user()->company_id)
                    ->where('name', $status['name'])
                    ->where('form_type', $status['form'])
                    ->exists();
                
                if (!$exists) {
                    Status::create([
                        'company_id' => auth()->user()->company_id,
                        'name' => $status['name'],
                        'description' => $status['description'],
                        'form_type' => $status['form'],
                        'color' => $status['color'],
                        'is_final' => $status['final'],
                        'sort_order' => $status['order']
                    ]);
                    $created++;
                }
            }
            
            session()->flash('success', "{$created} status padrão criados com sucesso!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar status padrão: ' . $e->getMessage());
        }
    }

    // Create default locations for all forms
    public function createDefaultLocations()
    {
        try {
            $defaultLocations = [
                // Form 1 - Initial
                ['form' => 'form1', 'name' => 'Escritório Principal', 'description' => 'Localização do escritório principal'],
                ['form' => 'form1', 'name' => 'Oficina 1', 'description' => 'Oficina de manutenção mecânica'],
                ['form' => 'form1', 'name' => 'Oficina 2', 'description' => 'Oficina de manutenção elétrica'],
                ['form' => 'form1', 'name' => 'Armazém', 'description' => 'Armazém de materiais'],
                ['form' => 'form1', 'name' => 'Cliente - Local', 'description' => 'No local do cliente'],
                
                // Form 2 - Technicians
                ['form' => 'form2', 'name' => 'Oficina Principal', 'description' => 'Oficina principal de trabalho'],
                ['form' => 'form2', 'name' => 'Oficina Secundária', 'description' => 'Oficina secundária'],
                ['form' => 'form2', 'name' => 'Campo - Externa', 'description' => 'Trabalho externo em campo'],
                ['form' => 'form2', 'name' => 'Instalações Cliente', 'description' => 'Nas instalações do cliente'],
                
                // Form 3 - Billing
                ['form' => 'form3', 'name' => 'Departamento Financeiro', 'description' => 'Processamento financeiro'],
                ['form' => 'form3', 'name' => 'Escritório', 'description' => 'Escritório administrativo'],
                
                // Form 4 - Machine
                ['form' => 'form4', 'name' => 'Oficina', 'description' => 'Localização da oficina'],
                ['form' => 'form4', 'name' => 'Campo', 'description' => 'Trabalho de campo'],
                ['form' => 'form4', 'name' => 'Cliente', 'description' => 'Local do cliente'],
                
                // Form 5 - Equipment
                ['form' => 'form5', 'name' => 'Sala de Equipamentos', 'description' => 'Sala dedicada aos equipamentos'],
                ['form' => 'form5', 'name' => 'Laboratório', 'description' => 'Laboratório de testes'],
                ['form' => 'form5', 'name' => 'Área de Produção', 'description' => 'Área de produção'],
            ];

            $created = 0;
            foreach ($defaultLocations as $location) {
                $exists = Location::where('company_id', auth()->user()->company_id)
                    ->where('name', $location['name'])
                    ->where('form_type', $location['form'])
                    ->exists();
                
                if (!$exists) {
                    Location::create([
                        'company_id' => auth()->user()->company_id,
                        'name' => $location['name'],
                        'description' => $location['description'],
                        'form_type' => $location['form']
                    ]);
                    $created++;
                }
            }
            
            session()->flash('success', "{$created} localizações padrão criadas com sucesso!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar localizações padrão: ' . $e->getMessage());
        }
    }

    // Duplicate status/location to other forms
    public function duplicateToOtherForms($id, $type, $targetForms = [])
    {
        try {
            if ($type === 'status') {
                $original = Status::where('company_id', auth()->user()->company_id)
                    ->findOrFail($id);
                
                $created = 0;
                foreach ($targetForms as $formType) {
                    if ($formType === $original->form_type) continue;
                    
                    $exists = Status::where('company_id', auth()->user()->company_id)
                        ->where('name', $original->name)
                        ->where('form_type', $formType)
                        ->exists();
                    
                    if (!$exists) {
                        Status::create([
                            'company_id' => auth()->user()->company_id,
                            'name' => $original->name,
                            'description' => $original->description,
                            'form_type' => $formType,
                            'color' => $original->color,
                            'is_final' => $original->is_final,
                            'sort_order' => $original->sort_order
                        ]);
                        $created++;
                    }
                }
                
                session()->flash('success', "Status duplicado para {$created} formulários!");
                
            } else {
                $original = Location::where('company_id', auth()->user()->company_id)
                    ->findOrFail($id);
                
                $created = 0;
                foreach ($targetForms as $formType) {
                    if ($formType === $original->form_type) continue;
                    
                    $exists = Location::where('company_id', auth()->user()->company_id)
                        ->where('name', $original->name)
                        ->where('form_type', $formType)
                        ->exists();
                    
                    if (!$exists) {
                        Location::create([
                            'company_id' => auth()->user()->company_id,
                            'name' => $original->name,
                            'description' => $original->description,
                            'form_type' => $formType
                        ]);
                        $created++;
                    }
                }
                
                session()->flash('success', "Localização duplicada para {$created} formulários!");
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao duplicar: ' . $e->getMessage());
        }
    }

    // Reorder statuses
    public function reorderStatuses($formType, $statusIds)
    {
        try {
            foreach ($statusIds as $index => $statusId) {
                Status::where('company_id', auth()->user()->company_id)
                    ->where('id', $statusId)
                    ->where('form_type', $formType)
                    ->update(['sort_order' => $index + 1]);
            }
            
            session()->flash('success', 'Ordem dos status atualizada com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao reordenar: ' . $e->getMessage());
        }
    }

    // Get color name
    public function getColorName($hexColor)
    {
        return $this->statusColors[$hexColor] ?? 'Personalizada';
    }

    // Generate random color
    public function generateRandomColor()
    {
        $colors = array_keys($this->statusColors);
        $this->status_color = $colors[array_rand($colors)];
    }

    // Sorting
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Export functionality
    public function export($format = 'excel')
    {
        if ($this->activeTab === 'statuses') {
            $data = $this->getStatuses()->items();
            $type = 'status';
        } else {
            $data = $this->getLocations()->items();
            $type = 'localizações';
        }
        
        // TODO: Implement export service
        session()->flash('info', "Exportação de {$type} em desenvolvimento...");
    }

    // Statistics
    public function getStatsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total_statuses' => Status::where('company_id', $companyId)->count(),
            'total_locations' => Location::where('company_id', $companyId)->count(),
            'statuses_by_form' => Status::where('company_id', $companyId)
                ->selectRaw('form_type, COUNT(*) as count')
                ->groupBy('form_type')
                ->pluck('count', 'form_type'),
            'locations_by_form' => Location::where('company_id', $companyId)
                ->selectRaw('form_type, COUNT(*) as count')
                ->groupBy('form_type')
                ->pluck('count', 'form_type'),
            'final_statuses' => Status::where('company_id', $companyId)
                ->where('is_final', true)
                ->count(),
            'non_final_statuses' => Status::where('company_id', $companyId)
                ->where('is_final', false)
                ->count(),
        ];
    }

    // Get form statistics
    public function getFormStatsProperty()
    {
        $stats = [];
        foreach ($this->formTypes as $formType => $formName) {
            $stats[$formType] = [
                'name' => $formName,
                'statuses' => Status::where('company_id', auth()->user()->company_id)
                    ->where('form_type', $formType)
                    ->count(),
                'locations' => Location::where('company_id', auth()->user()->company_id)
                    ->where('form_type', $formType)
                    ->count(),
            ];
        }
        return $stats;
    }

    // Check completeness (forms with missing statuses/locations)
    public function getCompletenessCheckProperty()
    {
        $issues = [];
        
        foreach ($this->formTypes as $formType => $formName) {
            $statusCount = Status::where('company_id', auth()->user()->company_id)
                ->where('form_type', $formType)
                ->count();
                
            $locationCount = Location::where('company_id', auth()->user()->company_id)
                ->where('form_type', $formType)
                ->count();
            
            if ($statusCount === 0) {
                $issues[] = "{$formName} não tem status definidos";
            }
            
            if ($locationCount === 0) {
                $issues[] = "{$formName} não tem localizações definidas";
            }
            
            // Check for final status
            $hasFinalStatus = Status::where('company_id', auth()->user()->company_id)
                ->where('form_type', $formType)
                ->where('is_final', true)
                ->exists();
                
            if ($statusCount > 0 && !$hasFinalStatus) {
                $issues[] = "{$formName} não tem status final definido";
            }
        }
        
        return $issues;
    }

    // Livewire lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFormTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // Auto-set sort order for new statuses
    public function updatedStatusFormType()
    {
        $maxOrder = Status::where('company_id', auth()->user()->company_id)
            ->where('form_type', $this->status_form_type)
            ->max('sort_order');
            
        $this->status_sort_order = ($maxOrder ?? 0) + 1;
    }
}
