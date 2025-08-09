<?php

namespace App\Livewire\Company\Perfomance;

use App\Models\Company\Department;
use App\Models\Company\Evaluation\PerformanceMetric;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class MetricsManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showMetricModal = false;
    public $showDeleteModal = false;
    public $showDepartmentModal = false;

    // Metric management
    public $editingMetricId = null;
    public $deleteMetricId = null;
    public $selectedDepartmentId = null;

    // Metric form properties
    public $metric_name = '';
    public $metric_description = '';
    public $metric_type = 'numeric';
    public $metric_weight = 10;
    public $metric_min_value = 0;
    public $metric_max_value = 10;
    public $metric_rating_options = [];
    public $is_active = true;
    public $sort_order = 0;

    // Filters
    public $search = '';
    public $departmentFilter = '';
    public $typeFilter = '';
    public $perPage = 15;

    // Data collections
    public $departments = [];
    public $departmentMetrics = [];
    public $departmentWeightTotal = 0;

    // Rating options management
    public $newRatingOption = '';

    protected function rules()
    {
        return [
            'metric_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('performance_metrics', 'name')
                    ->where('company_id', auth()->user()->company_id)
                    ->where('department_id', $this->selectedDepartmentId)
                    ->ignore($this->editingMetricId)
            ],
            'metric_description' => 'nullable|string|max:1000',
            'metric_type' => 'required|in:numeric,rating,boolean',
            'metric_weight' => 'required|integer|min:1|max:100',
            'metric_min_value' => 'required_if:metric_type,numeric|numeric|min:0',
            'metric_max_value' => 'required_if:metric_type,numeric|numeric|min:1',
            'metric_rating_options' => 'required_if:metric_type,rating|array|min:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ];
    }

    protected $messages = [
        'metric_name.required' => 'O nome da métrica é obrigatório.',
        'metric_name.unique' => 'Já existe uma métrica com este nome neste departamento.',
        'metric_type.required' => 'O tipo de métrica é obrigatório.',
        'metric_weight.required' => 'O peso da métrica é obrigatório.',
        'metric_weight.min' => 'O peso mínimo é 1%.',
        'metric_weight.max' => 'O peso máximo é 100%.',
        'metric_rating_options.required_if' => 'As opções de avaliação são obrigatórias.',
        'metric_rating_options.min' => 'Deve ter pelo menos 2 opções de avaliação.'
    ];

    public function mount()
    {
        $this->loadDepartments();
        $this->initializeDefaultRatingOptions();
    }

    public function render()
    {
        $metrics = $this->getMetrics();

        return view('livewire.company.perfomance.metrics-management', [
            'metrics' => $metrics,
            'stats' => $this->getStats(),
        ])
            ->title('Gestão de Métricas de Desempenho')
            ->layout('layouts.company');
    }

    public function loadDepartments()
    {
        $this->departments = Department::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function initializeDefaultRatingOptions()
    {
        $this->metric_rating_options = ['Péssimo', 'Satisfatório', 'Bom', 'Excelente'];
    }

    public function getMetrics()
    {
        $query = PerformanceMetric::where('company_id', auth()->user()->company_id)
            ->with(['department'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->departmentFilter, function ($q) {
                $q->where('department_id', $this->departmentFilter);
            })
            ->when($this->typeFilter, function ($q) {
                $q->where('type', $this->typeFilter);
            });

        return $query->orderBy('department_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($this->perPage);
    }

    public function getStats()
    {
        $companyId = auth()->user()->company_id;

        $totalMetrics = PerformanceMetric::where('company_id', $companyId)->count();
        $activeMetrics = PerformanceMetric::where('company_id', $companyId)->where('is_active', true)->count();
        $departmentsWithMetrics = PerformanceMetric::where('company_id', $companyId)
            ->distinct('department_id')
            ->count('department_id');

        $incompleteWeights = 0;
        foreach ($this->departments as $department) {
            $weightTotal = PerformanceMetric::where('company_id', $companyId)
                ->where('department_id', $department->id)
                ->where('is_active', true)
                ->sum('weight');

            if ($weightTotal !== 100 && $weightTotal > 0) {
                $incompleteWeights++;
            }
        }

        return [
            'total_metrics' => $totalMetrics,
            'active_metrics' => $activeMetrics,
            'departments_with_metrics' => $departmentsWithMetrics,
            'total_departments' => $this->departments->count(),
            'incomplete_weights' => $incompleteWeights
        ];
    }

    public function selectDepartment($departmentId)
    {
        $this->selectedDepartmentId = $departmentId;
        $this->loadDepartmentMetrics();
        $this->showDepartmentModal = true;
    }

    public function loadDepartmentMetrics()
    {
        if (!$this->selectedDepartmentId) {
            return;
        }

        $this->departmentMetrics = PerformanceMetric::where('company_id', auth()->user()->company_id)
            ->where('department_id', $this->selectedDepartmentId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $this->departmentWeightTotal = $this->departmentMetrics->where('is_active', true)->sum('weight');
    }

    public function createMetric($departmentId = null)
    {
        $this->resetMetricForm();
        $this->selectedDepartmentId = $departmentId;
        $this->showMetricModal = true;
    }

    public function editMetric($metricId)
    {
        $metric = PerformanceMetric::where('company_id', auth()->user()->company_id)
            ->findOrFail($metricId);

        $this->editingMetricId = $metricId;
        $this->selectedDepartmentId = $metric->department_id;
        $this->metric_name = $metric->name;
        $this->metric_description = $metric->description;
        $this->metric_type = $metric->type;
        $this->metric_weight = $metric->weight;
        $this->metric_min_value = $metric->min_value;
        $this->metric_max_value = $metric->max_value;
        $this->metric_rating_options = $metric->rating_options ?? $this->metric_rating_options;
        $this->is_active = $metric->is_active;
        $this->sort_order = $metric->sort_order;

        $this->showMetricModal = true;
    }

    public function saveMetric()
    {
        // Validar se departamento foi selecionado
        if (!$this->selectedDepartmentId) {
            session()->flash('error', 'Selecione um departamento primeiro.');
            return;
        }

        $this->validate();

        // Validar peso total do departamento
        if (!$this->validateDepartmentWeight()) {
            return;
        }

        try {
            $data = [
                'company_id' => auth()->user()->company_id,
                'department_id' => $this->selectedDepartmentId,
                'name' => $this->metric_name,
                'description' => $this->metric_description,
                'type' => $this->metric_type,
                'weight' => $this->metric_weight,
                'min_value' => $this->metric_type === 'numeric' ? $this->metric_min_value : 0,
                'max_value' => $this->metric_type === 'numeric' ? $this->metric_max_value : 10,
                'rating_options' => $this->metric_type === 'rating' ? $this->metric_rating_options : null,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order
            ];

            if ($this->editingMetricId) {
                $metric = PerformanceMetric::findOrFail($this->editingMetricId);
                $metric->update($data);
                session()->flash('success', 'Métrica atualizada com sucesso!');
            } else {
                PerformanceMetric::create($data);
                session()->flash('success', 'Métrica criada com sucesso!');
            }

            $this->closeMetricModal();
            $this->loadDepartmentMetrics();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar métrica: ' . $e->getMessage());
        }
    }

    public function validateDepartmentWeight()
    {
        $currentWeight = PerformanceMetric::where('company_id', auth()->user()->company_id)
            ->where('department_id', $this->selectedDepartmentId)
            ->where('is_active', true)
            ->when($this->editingMetricId, function ($q) {
                $q->where('id', '!=', $this->editingMetricId);
            })
            ->sum('weight');

        $newTotalWeight = $currentWeight + ($this->is_active ? $this->metric_weight : 0);

        if ($newTotalWeight > 100) {
            $this->addError('metric_weight', "O peso total do departamento não pode exceder 100%. Atual: {$currentWeight}%, tentando adicionar: {$this->metric_weight}%");
            return false;
        }

        return true;
    }

    public function confirmDeleteMetric($metricId)
    {
        $this->deleteMetricId = $metricId;
        $this->showDeleteModal = true;
    }

    public function deleteMetric()
    {
        try {
            $metric = PerformanceMetric::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteMetricId);

            // Verificar se a métrica está sendo usada em avaliações
            $evaluationsCount = $metric->evaluationResponses()->count();

            if ($evaluationsCount > 0) {
                session()->flash('error', "Não é possível eliminar esta métrica pois está sendo usada em {$evaluationsCount} avaliação(ões).");
                $this->showDeleteModal = false;
                return;
            }

            $metric->delete();
            session()->flash('success', 'Métrica eliminada com sucesso!');

            $this->loadDepartmentMetrics();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar métrica: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteMetricId = null;
    }

    public function toggleMetricStatus($metricId)
    {
        try {
            $metric = PerformanceMetric::where('company_id', auth()->user()->company_id)
                ->findOrFail($metricId);

            $newStatus = !$metric->is_active;

            // Se está ativando, verificar peso total
            if ($newStatus) {
                $currentWeight = PerformanceMetric::where('company_id', auth()->user()->company_id)
                    ->where('department_id', $metric->department_id)
                    ->where('is_active', true)
                    ->where('id', '!=', $metricId)
                    ->sum('weight');

                if (($currentWeight + $metric->weight) > 100) {
                    session()->flash('error', 'Não é possível ativar esta métrica pois o peso total excederia 100%.');
                    return;
                }
            }

            $metric->update(['is_active' => $newStatus]);

            $status = $newStatus ? 'ativada' : 'desativada';
            session()->flash('success', "Métrica {$status} com sucesso!");

            $this->loadDepartmentMetrics();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar status da métrica: ' . $e->getMessage());
        }
    }

    public function updateSortOrder($metricId, $direction)
    {
        try {
            $metric = PerformanceMetric::where('company_id', auth()->user()->company_id)
                ->findOrFail($metricId);

            $currentOrder = $metric->sort_order;
            $newOrder = $direction === 'up' ? $currentOrder - 1 : $currentOrder + 1;

            // Verificar se existe métrica na posição de destino
            $targetMetric = PerformanceMetric::where('company_id', auth()->user()->company_id)
                ->where('department_id', $metric->department_id)
                ->where('sort_order', $newOrder)
                ->first();

            if ($targetMetric) {
                // Trocar posições
                $targetMetric->update(['sort_order' => $currentOrder]);
                $metric->update(['sort_order' => $newOrder]);
            } else {
                $metric->update(['sort_order' => $newOrder]);
            }

            $this->loadDepartmentMetrics();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao reordenar métrica: ' . $e->getMessage());
        }
    }

    // Rating options management
    public function addRatingOption()
    {
        if (empty($this->newRatingOption)) {
            return;
        }

        if (!in_array($this->newRatingOption, $this->metric_rating_options)) {
            $this->metric_rating_options[] = $this->newRatingOption;
            $this->newRatingOption = '';
        }
    }

    public function removeRatingOption($index)
    {
        if (isset($this->metric_rating_options[$index])) {
            unset($this->metric_rating_options[$index]);
            $this->metric_rating_options = array_values($this->metric_rating_options);
        }
    }

    public function moveRatingOption($index, $direction)
    {
        $newIndex = $direction === 'up' ? $index - 1 : $index + 1;

        if ($newIndex >= 0 && $newIndex < count($this->metric_rating_options)) {
            $temp = $this->metric_rating_options[$index];
            $this->metric_rating_options[$index] = $this->metric_rating_options[$newIndex];
            $this->metric_rating_options[$newIndex] = $temp;
        }
    }

    public function resetToDefaultRatingOptions()
    {
        $this->initializeDefaultRatingOptions();
    }

    // Preview functionality
    public function previewEvaluationForm($departmentId)
    {
        $this->selectedDepartmentId = $departmentId;
        $this->loadDepartmentMetrics();

        // Redirect to preview page or open modal
        return redirect()->route('company.performance.metrics.preview', ['department' => $departmentId]);
    }

    // Bulk operations
    public function bulkUpdateWeights($departmentId, $weights)
    {
        try {
            $totalWeight = array_sum($weights);

            if ($totalWeight !== 100) {
                session()->flash('error', "O peso total deve ser exatamente 100%. Atual: {$totalWeight}%");
                return;
            }

            foreach ($weights as $metricId => $weight) {
                PerformanceMetric::where('id', $metricId)
                    ->where('company_id', auth()->user()->company_id)
                    ->update(['weight' => $weight]);
            }

            session()->flash('success', 'Pesos atualizados com sucesso!');
            $this->loadDepartmentMetrics();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar pesos: ' . $e->getMessage());
        }
    }

    public function duplicateMetricsFromDepartment($sourceDepartmentId, $targetDepartmentId)
    {
        try {
            $sourceMetrics = PerformanceMetric::where('company_id', auth()->user()->company_id)
                ->where('department_id', $sourceDepartmentId)
                ->get();

            if ($sourceMetrics->isEmpty()) {
                session()->flash('error', 'Departamento de origem não possui métricas.');
                return;
            }

            foreach ($sourceMetrics as $metric) {
                PerformanceMetric::create([
                    'company_id' => $metric->company_id,
                    'department_id' => $targetDepartmentId,
                    'name' => $metric->name,
                    'description' => $metric->description,
                    'type' => $metric->type,
                    'weight' => $metric->weight,
                    'min_value' => $metric->min_value,
                    'max_value' => $metric->max_value,
                    'rating_options' => $metric->rating_options,
                    'is_active' => $metric->is_active,
                    'sort_order' => $metric->sort_order
                ]);
            }

            session()->flash('success', "Métricas duplicadas com sucesso! {$sourceMetrics->count()} métricas copiadas.");
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao duplicar métricas: ' . $e->getMessage());
        }
    }

    // Helper methods
    public function resetMetricForm()
    {
        $this->editingMetricId = null;
        $this->metric_name = '';
        $this->metric_description = '';
        $this->metric_type = 'numeric';
        $this->metric_weight = 10;
        $this->metric_min_value = 0;
        $this->metric_max_value = 10;
        $this->initializeDefaultRatingOptions();
        $this->is_active = true;
        $this->sort_order = 0;
        $this->newRatingOption = '';
        $this->resetValidation();
    }

    public function closeMetricModal()
    {
        $this->showMetricModal = false;
        $this->resetMetricForm();
    }

    public function closeDepartmentModal()
    {
        $this->showDepartmentModal = false;
        $this->selectedDepartmentId = null;
        $this->departmentMetrics = [];
        $this->departmentWeightTotal = 0;
    }

    public function getDepartmentWeightStatus($departmentId)
    {
        $totalWeight = PerformanceMetric::where('company_id', auth()->user()->company_id)
            ->where('department_id', $departmentId)
            ->where('is_active', true)
            ->sum('weight');

        return [
            'total' => $totalWeight,
            'status' => $totalWeight === 100 ? 'complete' : ($totalWeight > 100 ? 'over' : 'under'),
            'class' => $totalWeight === 100 ? 'success' : ($totalWeight > 100 ? 'danger' : 'warning')
        ];
    }

    // Lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatedMetricType()
    {
        if ($this->metric_type === 'rating') {
            $this->initializeDefaultRatingOptions();
        } elseif ($this->metric_type === 'boolean') {
            $this->metric_min_value = 0;
            $this->metric_max_value = 1;
        } elseif ($this->metric_type === 'numeric') {
            $this->metric_min_value = 0;
            $this->metric_max_value = 10;
        }
    }

    public function updatedSelectedDepartmentId()
    {
        if ($this->selectedDepartmentId) {
            $this->loadDepartmentMetrics();
        }
        // }
        // public function render()
        // {
        //     return view('livewire.company.perfomance.metrics-management');
        // }
    }
}
