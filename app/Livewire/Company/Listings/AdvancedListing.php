<?php

namespace App\Livewire\Company\Listings;

use App\Models\Company\Client;
use App\Models\Company\Department;
use App\Models\Company\Employee;
use App\Models\Company\Location;
use App\Models\Company\MaintenanceType;
use App\Models\Company\RepairOrder\RepairOrder;
use App\Models\Company\Status;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AdvancedListing extends Component
{
    use WithPagination;

    // =============================================
    // PASSO 1: FILTROS PRINCIPAIS
    // =============================================
    
    public $filterPeriodStart = '';
    public $filterPeriodEnd = '';
    public $filterOrderNumber = '';
    public $filterClient = '';
    public $filterTechnicians = [];
    public $filterStatus = '';
    public $filterLocation = '';
    public $filterMaintenanceType = '';
    public $filterDescription = '';

    // =============================================
    // PASSO 2: SELETOR DE CAMPOS (ACCORDION)
    // =============================================
    
    public $selectedFields = [];
    public $availableFields = [];
    public $accordionOpen = [];

    // =============================================
    // PASSO 3: CONFIGURAÇÕES DA TABELA
    // =============================================
    
    public $currentStep = 1; // 1=Filtros, 2=Campos, 3=Resultados
    public $perPage = 25;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // =============================================
    // DADOS PARA DROPDOWNS
    // =============================================
    
    public $clients = [];
    public $technicians = [];
    public $statuses = [];
    public $locations = [];
    public $maintenanceTypes = [];
    public $departments = [];

    // =============================================
    // RESULTADOS E EXPORT
    // =============================================
    
    public $results = [];
    public $totalResults = 0;
    public $showResults = false;

    // =============================================
    // MOUNT E INICIALIZAÇÃO
    // =============================================

    public function mount()
    {
        $this->initializeDefaultFilters();
        $this->loadFilterData();
        $this->defineAvailableFields();
        $this->initializeAccordion();
    }

    private function initializeDefaultFilters()
    {
        // Período padrão: últimos 30 dias
        $this->filterPeriodStart = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->filterPeriodEnd = Carbon::now()->format('Y-m-d');
    }

    private function loadFilterData()
    {
        $companyId = auth()->user()->company_id;

        $this->clients = Client::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->technicians = Employee::where('company_id', $companyId)
            ->where('is_active', true)
            ->with('department')
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'department_id']);

        $this->statuses = Status::where('company_id', $companyId)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'form_type', 'color']);

        $this->locations = Location::where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name', 'form_type']);

        $this->maintenanceTypes = MaintenanceType::where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name', 'description']);

        $this->departments = Department::where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function defineAvailableFields()
    {
        $this->availableFields = [
            'form1' => [
                'order_number' => 'Ordem de Reparação',
                'carimbo' => 'Carimbo (Data/Hora)',
                'maintenance_type' => 'Tipo de Manutenção',
                'client' => 'Cliente',
                'status' => 'Estado',
                'location' => 'Localização',
                'descricao_avaria' => 'Descrição de Avaria',
                'mes' => 'Mês',
                'ano' => 'Ano',
                'requester' => 'Solicitante',
                'machine_number' => 'Número da Máquina',
            ],
            'form2' => [
                'carimbo' => 'Carimbo (Data/Hora)',
                'location' => 'Localização',
                'status' => 'Estado da Obra',
                'tempo_total' => 'Tempo Total (horas)',
                'technicians' => 'Técnicos Afetos',
                'technicians_hours' => 'Horas por Técnico',
                'materials' => 'Materiais',
                'materials_quantity' => 'Quantidade de Materiais',
                'additional_materials' => 'Materiais Adicionais',
                'activity' => 'Atividade Realizada',
            ],
            'form3' => [
                'carimbo' => 'Carimbo (Data/Hora)',
                'location' => 'Localização',
                'status' => 'Estado',
                'billing_date' => 'Data de Faturação',
                'billed_hours' => 'Horas Faturadas',
                'materials' => 'Materiais Faturados',
                'materials_quantity' => 'Quantidade Faturada',
            ],
            'form4' => [
                'carimbo' => 'Carimbo (Data/Hora)',
                'location' => 'Localização',
                'status' => 'Estado',
                'machine_number' => 'Número da Máquina',
            ],
            'form5' => [
                'carimbo' => 'Carimbo (Data/Hora)',
                'machine_number' => 'Número do Equipamento',
                'billing_date_1' => 'Data de Faturação 1',
                'billed_hours_1' => 'Horas Faturadas 1',
                'billing_date_2' => 'Data de Faturação 2',
                'billed_hours_2' => 'Horas Faturadas 2',
                'client' => 'Cliente',
                'activities_description' => 'Descrição de Atividades',
                'technician' => 'Técnico',
            ],
        ];
    }

    private function initializeAccordion()
    {
        // Inicializar accordion fechado
        $this->accordionOpen = [
            'form1' => false,
            'form2' => false,
            'form3' => false,
            'form4' => false,
            'form5' => false,
        ];

        // Inicializar com alguns campos básicos selecionados
        $this->selectedFields = [
            'form1' => ['order_number', 'client', 'maintenance_type'],
            'form2' => [],
            'form3' => [],
            'form4' => [],
            'form5' => [],
        ];
    }

    // =============================================
    // NAVEGAÇÃO ENTRE PASSOS
    // =============================================

    public function goToStep($step)
    {
        $this->currentStep = $step;
        
        if ($step === 3) {
            $this->generateResults();
        }
    }

    public function nextStep()
    {
        if ($this->currentStep < 3) {
            $this->currentStep++;
            
            if ($this->currentStep === 3) {
                $this->generateResults();
            }
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    // =============================================
    // AÇÕES DO ACCORDION
    // =============================================

    public function toggleAccordion($form)
    {
        $this->accordionOpen[$form] = !$this->accordionOpen[$form];
    }

    public function toggleField($form, $field)
    {
        if (!isset($this->selectedFields[$form])) {
            $this->selectedFields[$form] = [];
        }

        $index = array_search($field, $this->selectedFields[$form]);
        
        if ($index !== false) {
            // Remove field
            unset($this->selectedFields[$form][$index]);
            $this->selectedFields[$form] = array_values($this->selectedFields[$form]);
        } else {
            // Add field
            $this->selectedFields[$form][] = $field;
        }
    }

    public function selectAllFields($form)
    {
        $this->selectedFields[$form] = array_keys($this->availableFields[$form]);
    }

    public function clearAllFields($form)
    {
        $this->selectedFields[$form] = [];
    }

    // =============================================
    // GERAÇÃO DE RESULTADOS
    // =============================================

    public function generateResults()
    {
        $query = $this->buildQuery();
        
        // Obter total para paginação
        $this->totalResults = $query->count();
        
        // Obter resultados paginados
        $orders = $query->orderBy($this->sortField, $this->sortDirection)
            ->skip(($this->getPage() - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        // Processar resultados para a tabela dinâmica
        $this->results = $this->processResultsForTable($orders);
        $this->showResults = true;
    }

    private function buildQuery()
    {
        $companyId = auth()->user()->company_id;
        $query = RepairOrder::where('company_id', $companyId);

        // Aplicar filtros principais
        if ($this->filterPeriodStart && $this->filterPeriodEnd) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->filterPeriodStart)->startOfDay(),
                Carbon::parse($this->filterPeriodEnd)->endOfDay()
            ]);
        }

        if ($this->filterOrderNumber) {
            $query->where('order_number', 'like', '%' . $this->filterOrderNumber . '%');
        }

        if ($this->filterClient) {
            $query->whereHas('form1', function ($q) {
                $q->where('client_id', $this->filterClient);
            });
        }

        if (!empty($this->filterTechnicians)) {
            $query->whereHas('form2.employees', function ($q) {
                $q->whereIn('employee_id', $this->filterTechnicians);
            });
        }

        if ($this->filterStatus) {
            $query->where(function ($q) {
                $q->whereHas('form1', function ($subQ) {
                    $subQ->where('status_id', $this->filterStatus);
                })->orWhereHas('form2', function ($subQ) {
                    $subQ->where('status_id', $this->filterStatus);
                })->orWhereHas('form3', function ($subQ) {
                    $subQ->where('status_id', $this->filterStatus);
                })->orWhereHas('form4', function ($subQ) {
                    $subQ->where('status_id', $this->filterStatus);
                });
            });
        }

        if ($this->filterLocation) {
            $query->where(function ($q) {
                $q->whereHas('form1', function ($subQ) {
                    $subQ->where('location_id', $this->filterLocation);
                })->orWhereHas('form2', function ($subQ) {
                    $subQ->where('location_id', $this->filterLocation);
                })->orWhereHas('form3', function ($subQ) {
                    $subQ->where('location_id', $this->filterLocation);
                })->orWhereHas('form4', function ($subQ) {
                    $subQ->where('location_id', $this->filterLocation);
                });
            });
        }

        if ($this->filterMaintenanceType) {
            $query->whereHas('form1', function ($q) {
                $q->where('maintenance_type_id', $this->filterMaintenanceType);
            });
        }

        if ($this->filterDescription) {
            $query->whereHas('form1', function ($q) {
                $q->where('descricao_avaria', 'like', '%' . $this->filterDescription . '%');
            });
        }

        // Incluir relacionamentos necessários baseados nos campos selecionados
        $query->with($this->getRequiredRelationships());

        return $query;
    }

    private function getRequiredRelationships()
    {
        $relationships = [];

        // Sempre incluir relacionamentos básicos
        $relationships[] = 'form1';
        $relationships[] = 'form2';
        $relationships[] = 'form3';
        $relationships[] = 'form4';
        $relationships[] = 'form5';

        // Relacionamentos específicos baseados nos campos selecionados
        foreach ($this->selectedFields as $form => $fields) {
            if (empty($fields)) continue;

            switch ($form) {
                case 'form1':
                    if (in_array('client', $fields)) $relationships[] = 'form1.client';
                    if (in_array('maintenance_type', $fields)) $relationships[] = 'form1.maintenanceType';
                    if (in_array('status', $fields)) $relationships[] = 'form1.status';
                    if (in_array('location', $fields)) $relationships[] = 'form1.location';
                    if (in_array('requester', $fields)) $relationships[] = 'form1.requester';
                    if (in_array('machine_number', $fields)) $relationships[] = 'form1.machineNumber';
                    break;

                case 'form2':
                    if (in_array('technicians', $fields) || in_array('technicians_hours', $fields)) {
                        $relationships[] = 'form2.employees';
                        $relationships[] = 'form2.employees.department';
                    }
                    if (in_array('materials', $fields) || in_array('materials_quantity', $fields)) {
                        $relationships[] = 'form2.materials';
                    }
                    if (in_array('additional_materials', $fields)) {
                        $relationships[] = 'form2.additionalMaterials';
                    }
                    if (in_array('status', $fields)) $relationships[] = 'form2.status';
                    if (in_array('location', $fields)) $relationships[] = 'form2.location';
                    break;

                case 'form3':
                    if (in_array('materials', $fields) || in_array('materials_quantity', $fields)) {
                        $relationships[] = 'form3.materials';
                    }
                    if (in_array('status', $fields)) $relationships[] = 'form3.status';
                    if (in_array('location', $fields)) $relationships[] = 'form3.location';
                    break;

                case 'form4':
                    if (in_array('machine_number', $fields)) $relationships[] = 'form4.machineNumber';
                    if (in_array('status', $fields)) $relationships[] = 'form4.status';
                    if (in_array('location', $fields)) $relationships[] = 'form4.location';
                    break;

                case 'form5':
                    if (in_array('client', $fields)) $relationships[] = 'form5.client';
                    if (in_array('technician', $fields)) $relationships[] = 'form5.employee';
                    if (in_array('machine_number', $fields)) $relationships[] = 'form5.machineNumber';
                    break;
            }
        }

        return array_unique($relationships);
    }

    private function processResultsForTable($orders)
    {
        $processedResults = [];

        foreach ($orders as $order) {
            $row = ['id' => $order->id];

            // Processar cada formulário e seus campos selecionados
            foreach ($this->selectedFields as $form => $fields) {
                foreach ($fields as $field) {
                    $key = $form . '_' . $field;
                    $row[$key] = $this->extractFieldValue($order, $form, $field);
                }
            }

            $processedResults[] = $row;
        }

        return $processedResults;
    }

    private function extractFieldValue($order, $form, $field)
    {
        $formData = $order->$form;
        
        if (!$formData) {
            return '-';
        }

        switch ($form) {
            case 'form1':
                return $this->extractForm1Field($order, $formData, $field);
            case 'form2':
                return $this->extractForm2Field($order, $formData, $field);
            case 'form3':
                return $this->extractForm3Field($order, $formData, $field);
            case 'form4':
                return $this->extractForm4Field($order, $formData, $field);
            case 'form5':
                return $this->extractForm5Field($order, $formData, $field);
            default:
                return '-';
        }
    }

    private function extractForm1Field($order, $formData, $field)
    {
        switch ($field) {
            case 'order_number':
                return $order->order_number;
            case 'carimbo':
                return $formData->carimbo ? $formData->carimbo->format('d/m/Y H:i') : '-';
            case 'maintenance_type':
                return $formData->maintenanceType?->name ?? '-';
            case 'client':
                return $formData->client?->name ?? '-';
            case 'status':
                return $formData->status?->name ?? '-';
            case 'location':
                return $formData->location?->name ?? '-';
            case 'descricao_avaria':
                return $formData->descricao_avaria ?? '-';
            case 'mes':
                return $formData->mes ?? '-';
            case 'ano':
                return $formData->ano ?? '-';
            case 'requester':
                return $formData->requester?->name ?? '-';
            case 'machine_number':
                return $formData->machineNumber?->number ?? '-';
            default:
                return '-';
        }
    }

    private function extractForm2Field($order, $formData, $field)
    {
        switch ($field) {
            case 'carimbo':
                return $formData->carimbo ? $formData->carimbo->format('d/m/Y H:i') : '-';
            case 'location':
                return $formData->location?->name ?? '-';
            case 'status':
                return $formData->status?->name ?? '-';
            case 'tempo_total':
                return $formData->tempo_total_horas ? number_format($formData->tempo_total_horas, 1) . 'h' : '-';
            case 'technicians':
                return $formData->employees->pluck('name')->implode(', ') ?: '-';
            case 'technicians_hours':
                $hours = $formData->employees->map(function ($emp) {
                    return $emp->name . ': ' . ($emp->pivot->horas_trabalhadas ?? 0) . 'h';
                });
                return $hours->implode('; ') ?: '-';
            case 'materials':
                return $formData->materials->pluck('name')->implode(', ') ?: '-';
            case 'materials_quantity':
                $materials = $formData->materials->map(function ($mat) {
                    return $mat->name . ': ' . ($mat->pivot->quantidade ?? 0);
                });
                return $materials->implode('; ') ?: '-';
            case 'additional_materials':
                return $formData->additionalMaterials->pluck('name')->implode(', ') ?: '-';
            case 'activity':
                return $formData->actividade_realizada ?? '-';
            default:
                return '-';
        }
    }

    private function extractForm3Field($order, $formData, $field)
    {
        switch ($field) {
            case 'carimbo':
                return $formData->carimbo ? $formData->carimbo->format('d/m/Y H:i') : '-';
            case 'location':
                return $formData->location?->name ?? '-';
            case 'status':
                return $formData->status?->name ?? '-';
            case 'billing_date':
                return $formData->data_faturacao ? $formData->data_faturacao->format('d/m/Y') : '-';
            case 'billed_hours':
                return $formData->horas_faturadas ? number_format($formData->horas_faturadas, 1) . 'h' : '-';
            case 'materials':
                return $formData->materials->pluck('name')->implode(', ') ?: '-';
            case 'materials_quantity':
                $materials = $formData->materials->map(function ($mat) {
                    return $mat->name . ': ' . ($mat->pivot->quantidade ?? 0);
                });
                return $materials->implode('; ') ?: '-';
            default:
                return '-';
        }
    }

    private function extractForm4Field($order, $formData, $field)
    {
        switch ($field) {
            case 'carimbo':
                return $formData->carimbo ? $formData->carimbo->format('d/m/Y H:i') : '-';
            case 'location':
                return $formData->location?->name ?? '-';
            case 'status':
                return $formData->status?->name ?? '-';
            case 'machine_number':
                return $formData->machineNumber?->number ?? '-';
            default:
                return '-';
        }
    }

    private function extractForm5Field($order, $formData, $field)
    {
        switch ($field) {
            case 'carimbo':
                return $formData->carimbo ? $formData->carimbo->format('d/m/Y H:i') : '-';
            case 'machine_number':
                return $formData->machineNumber?->number ?? '-';
            case 'billing_date_1':
                return $formData->data_faturacao_1 ? $formData->data_faturacao_1->format('d/m/Y') : '-';
            case 'billed_hours_1':
                return $formData->horas_faturadas_1 ? number_format($formData->horas_faturadas_1, 1) . 'h' : '-';
            case 'billing_date_2':
                return $formData->data_faturacao_2 ? $formData->data_faturacao_2->format('d/m/Y') : '-';
            case 'billed_hours_2':
                return $formData->horas_faturadas_2 ? number_format($formData->horas_faturadas_2, 1) . 'h' : '-';
            case 'client':
                return $formData->client?->name ?? '-';
            case 'activities_description':
                return $formData->descricao_actividades ?? '-';
            case 'technician':
                return $formData->employee?->name ?? '-';
            default:
                return '-';
        }
    }

    // =============================================
    // EXPORTAÇÃO
    // =============================================

    public function exportResults($format = 'excel')
    {
        if (empty($this->results)) {
            session()->flash('error', 'Nenhum resultado para exportar. Execute a consulta primeiro.');
            return;
        }

        try {
            // Obter todos os resultados (sem paginação) para exportação
            $query = $this->buildQuery();
            $allOrders = $query->orderBy($this->sortField, $this->sortDirection)->get();
            $allResults = $this->processResultsForTable($allOrders);

            // Preparar headers da tabela
            $headers = $this->getTableHeaders();
            
            // Preparar dados para exportação
            $exportData = [];
            $exportData[] = $headers; // Primeira linha são os headers
            
            foreach ($allResults as $row) {
                $exportRow = [];
                foreach ($headers as $header) {
                    $key = $this->getKeyFromHeader($header);
                    $exportRow[] = $row[$key] ?? '-';
                }
                $exportData[] = $exportRow;
            }

            // Gerar arquivo
            $timestamp = date('Y-m-d-H-i-s');
            $filename = "listagem-avancada-{$timestamp}.{$format}";
            
            $tempPath = storage_path('app/temp');
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }
            
            $filePath = $tempPath . '/' . $filename;

            switch ($format) {
                case 'excel':
                case 'csv':
                    $this->generateCSVForExport($exportData, $filePath);
                    break;
                default:
                    throw new \Exception('Formato não suportado.');
            }

            session()->flash('success', count($allResults) . " registros exportados com sucesso!");
            
            return response()->download($filePath, $filename)->deleteFileAfterSend();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao exportar: ' . $e->getMessage());
        }
    }

    private function generateCSVForExport($data, $filePath)
    {
        $handle = fopen($filePath, 'w');
        fwrite($handle, "\xEF\xBB\xBF"); // BOM para UTF-8
        
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);
    }

    // =============================================
    // HELPERS
    // =============================================

    public function getTableHeaders()
    {
        $headers = [];
        
        foreach ($this->selectedFields as $form => $fields) {
            foreach ($fields as $field) {
                $headers[] = $this->availableFields[$form][$field];
            }
        }
        
        return $headers;
    }

    private function getKeyFromHeader($header)
    {
        foreach ($this->selectedFields as $form => $fields) {
            foreach ($fields as $field) {
                if ($this->availableFields[$form][$field] === $header) {
                    return $form . '_' . $field;
                }
            }
        }
        return '';
    }

    public function getTotalSelectedFields()
    {
        $total = 0;
        foreach ($this->selectedFields as $fields) {
            $total += count($fields);
        }
        return $total;
    }

    public function clearAllFilters()
    {
        $this->filterPeriodStart = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->filterPeriodEnd = Carbon::now()->format('Y-m-d');
        $this->filterOrderNumber = '';
        $this->filterClient = '';
        $this->filterTechnicians = [];
        $this->filterStatus = '';
        $this->filterLocation = '';
        $this->filterMaintenanceType = '';
        $this->filterDescription = '';
    }

    // =============================================
    // RENDER
    // =============================================

    public function render()
    {
        return view('livewire.company.listings.advanced-listing') ->layout('layouts.company', [
                'title' => 'Listagem Avançada - Ordens de Reparação'
            ]);
    }
}
