<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $company['name'] }}</title>
    <style>
        /* Reset e configuração base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            line-height: 1.5;
            color: #1f2937;
            background: #f9fafb;
            font-size: 12px;
        }
        
        /* CSS específico para PDF */
        @media print {
            body { 
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .no-break {
            page-break-inside: avoid;
        }
        
        /* Header Principal */
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .header-info {
            display: flex;
            justify-content: center;
            gap: 40px;
            font-size: 14px;
        }
        
        /* Seções */
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid #3b82f6;
            display: flex;
            align-items: center;
        }
        
        .section-icon {
            margin-right: 8px;
            font-size: 20px;
        }
        
        /* Grid Systems */
        .grid {
            display: grid;
            gap: 20px;
        }
        
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-5 { grid-template-columns: repeat(5, 1fr); }
        .grid-7 { grid-template-columns: repeat(7, 1fr); }
        
        /* Cards */
        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        .card-gradient {
            background: linear-gradient(135deg, #fef3c7 0%, #f59e0b 100%);
            color: #92400e;
        }
        
        .card-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }
        
        .badge {
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-purple { background: #ede9fe; color: #7c3aed; }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .badge-red { background: #fee2e2; color: #dc2626; }
        .badge-emerald { background: #d1fae5; color: #059669; }
        .badge-gray { background: #f3f4f6; color: #374151; }
        
        /* Workflow Status Cards */
        .workflow-card {
            text-align: center;
            padding: 15px;
        }
        
        .workflow-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .bg-blue { background: #3b82f6; }
        .bg-green { background: #10b981; }
        .bg-orange { background: #f59e0b; }
        .bg-purple { background: #8b5cf6; }
        .bg-red { background: #ef4444; }
        .bg-emerald { background: #10b981; }
        .bg-gray { background: #6b7280; }
        
        .workflow-number {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .workflow-label {
            font-size: 11px;
            color: #6b7280;
        }
        
        /* Métricas Principais */
        .metric-card {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .metric-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .metric-icon-blue { background: #dbeafe; color: #1e40af; }
        .metric-icon-green { background: #dcfce7; color: #166534; }
        .metric-icon-purple { background: #ede9fe; color: #7c3aed; }
        .metric-icon-amber { background: #fef3c7; color: #92400e; }
        
        .metric-content h3 {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        
        .metric-content .value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 2px;
        }
        
        .metric-content .subtitle {
            font-size: 10px;
            color: #6b7280;
        }
        
        .change-positive { color: #059669; }
        .change-negative { color: #dc2626; }
        
        /* Departamentos */
        .dept-card {
            text-align: center;
            position: relative;
        }
        
        .dept-position {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }
        
        .pos-1 { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
        .pos-2 { background: linear-gradient(135deg, #9ca3af, #6b7280); }
        .pos-3 { background: linear-gradient(135deg, #d97706, #92400e); }
        
        .dept-name {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .dept-employees {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 15px;
        }
        
        .dept-score {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .score-excellent { color: #059669; }
        .score-good { color: #0ea5e9; }
        .score-fair { color: #f59e0b; }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            margin: 15px 0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        .progress-excellent { background: linear-gradient(90deg, #10b981, #059669); }
        .progress-good { background: linear-gradient(90deg, #3b82f6, #1e40af); }
        .progress-fair { background: linear-gradient(90deg, #f59e0b, #d97706); }
        
        .dept-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 15px 0;
            font-size: 11px;
        }
        
        .dept-metrics div {
            text-align: center;
        }
        
        .dept-metrics .value {
            font-weight: bold;
            color: #1f2937;
            font-size: 14px;
        }
        
        .dept-metrics .label {
            color: #6b7280;
        }
        
        /* Faturação Cards */
        .billing-card h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .billing-hh h4 { color: #1e40af; }
        .billing-estimated h4 { color: #166534; }
        .billing-real h4 { color: #7c3aed; }
        .billing-materials h4 { color: #92400e; }
        .billing-total h4 { color: #3730a3; }
        
        .billing-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 11px;
        }
        
        .billing-row .label {
            color: #6b7280;
        }
        
        .billing-row .value {
            font-weight: bold;
            color: #1f2937;
        }
        
        .billing-total-row {
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .billing-total-row .value {
            color: #7c3aed;
            font-size: 12px;
        }
        
        /* Centro de Comando */
        .command-center {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
        }
        
        .command-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .command-title {
            font-size: 16px;
            font-weight: bold;
        }
        
        .command-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .command-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }
        
        .command-card h4 {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .command-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 10px;
        }
        
        .command-progress {
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            margin: 8px 0;
        }
        
        .command-progress-fill {
            height: 100%;
            border-radius: 2px;
        }
        
        .progress-green { background: #10b981; }
        .progress-yellow { background: #fbbf24; }
        
        /* Insights */
        .insight-card {
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }
        
        .insight-blue {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-color: #0ea5e9;
        }
        
        .insight-amber {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-color: #f59e0b;
        }
        
        .insight-green {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-color: #10b981;
        }
        
        .insight-purple {
            background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
            border-color: #8b5cf6;
        }
        
        .insight-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .insight-icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: white;
            font-size: 12px;
        }
        
        .insight-icon-blue { background: #0ea5e9; }
        .insight-icon-amber { background: #f59e0b; }
        .insight-icon-green { background: #10b981; }
        .insight-icon-purple { background: #8b5cf6; }
        
        .insight-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .insight-subtitle {
            font-size: 10px;
            opacity: 0.7;
        }
        
        .insight-content {
            font-size: 10px;
            margin-bottom: 8px;
        }
        
        .insight-tip {
            background: rgba(255, 255, 255, 0.5);
            padding: 6px 8px;
            border-radius: 4px;
            font-size: 9px;
        }
        
        /* Materiais */
        .material-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        
        .material-info {
            flex: 1;
        }
        
        .material-name {
            font-weight: 600;
            font-size: 11px;
            color: #1f2937;
            margin-bottom: 2px;
        }
        
        .material-details {
            font-size: 9px;
            color: #6b7280;
        }
        
        .material-value {
            text-align: right;
        }
        
        .material-amount {
            font-weight: bold;
            font-size: 11px;
            color: #1f2937;
        }
        
        .material-currency {
            font-size: 9px;
            color: #6b7280;
        }
        
        /* Warning Alert */
        .alert-warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 10px;
            margin-top: 15px;
            font-size: 10px;
            color: #92400e;
        }
        
        /* Ordens Recentes */
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        
        .order-info {
            flex: 1;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .order-id {
            font-weight: bold;
            font-size: 11px;
            color: #1f2937;
        }
        
        .order-status {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        
        .status-completed { background: #dcfce7; color: #166534; }
        .status-progress { background: #dbeafe; color: #1e40af; }
        .status-pending { background: #fef3c7; color: #92400e; }
        
        .order-client {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 6px;
        }
        
        .order-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #6b7280;
        }
        
        .priority-high { background: #fee2e2; color: #dc2626; }
        .priority-medium { background: #fef3c7; color: #92400e; }
        .priority-low { background: #dcfce7; color: #166534; }
        
        /* Rodapé */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        
        .footer strong {
            color: #1f2937;
        }
        
        /* Utilitários */
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .mt-4 { margin-top: 16px; }
        
    </style>
</head>
<body>
    <div class="container">
        
        {{-- HEADER PRINCIPAL --}}
        <div class="header no-break">
            <h1>{{ $company['name'] }}</h1>
            <p>Dashboard da Empresa - Visão Geral das Operações e Métricas</p>
            <div class="header-info">
                <div>
                    <strong>Período:</strong> {{ $company['period'] }}
                </div>
                <div>
                    <strong>Gerado em:</strong> {{ $company['generated_at'] }}
                </div>
            </div>
        </div>

        {{-- STATUS DO WORKFLOW --}}
        <div class="section no-break">
            <h2 class="section-title">
                <span class="section-icon">🔄</span>
                Status do Workflow
            </h2>
            <div class="grid grid-7">
                {{-- FORM 1 --}}
                <div class="card workflow-card">
                    <div class="workflow-icon bg-blue">1</div>
                    <div class="workflow-number">{{ $dashboardData['workflow_metrics']['orders_by_stage']['form1'] ?? 0 }}</div>
                    <div class="workflow-label">FORM 1<br>Inicial</div>
                </div>

                {{-- FORM 2 --}}
                <div class="card workflow-card">
                    <div class="workflow-icon bg-green">2</div>
                    <div class="workflow-number">{{ $dashboardData['workflow_metrics']['orders_by_stage']['form2'] ?? 0 }}</div>
                    <div class="workflow-label">FORM 2<br>Técnicos</div>
                </div>

                {{-- FORM 3 --}}
                <div class="card workflow-card">
                    <div class="workflow-icon bg-orange">3</div>
                    <div class="workflow-number">{{ $dashboardData['workflow_metrics']['orders_by_stage']['form3'] ?? 0 }}</div>
                    <div class="workflow-label">FORM 3<br>Faturação</div>
                </div>

                {{-- FORM 4 --}}
                <div class="card workflow-card">
                    <div class="workflow-icon bg-purple">4</div>
                    <div class="workflow-number">{{ $dashboardData['workflow_metrics']['orders_by_stage']['form4'] ?? 0 }}</div>
                    <div class="workflow-label">FORM 4<br>Máquina</div>
                </div>

                {{-- FORM 5 --}}
                <div class="card workflow-card">
                    <div class="workflow-icon bg-red">5</div>
                    <div class="workflow-number">{{ $dashboardData['workflow_metrics']['orders_by_stage']['form5'] ?? 0 }}</div>
                    <div class="workflow-label">FORM 5<br>Final</div>
                </div>

                {{-- COMPLETAS --}}
                <div class="card workflow-card">
                    <div class="workflow-icon bg-emerald">✓</div>
                    <div class="workflow-number">{{ $dashboardData['workflow_metrics']['orders_completed'] ?? 0 }}</div>
                    <div class="workflow-label">COMPLETAS<br>Finalizadas</div>
                </div>

                {{-- PENDENTES --}}
                <div class="card workflow-card">
                    <div class="workflow-icon bg-gray">⏱</div>
                    <div class="workflow-number">{{ $dashboardData['workflow_metrics']['orders_pending'] ?? 0 }}</div>
                    <div class="workflow-label">PENDENTES<br>Em Andamento</div>
                </div>
            </div>

            {{-- MÉTRICAS ADICIONAIS DO WORKFLOW --}}
            <div class="grid grid-4 mt-4">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Taxa de Conclusão</span>
                        <span class="badge {{ ($dashboardData['workflow_metrics']['completion_rate'] ?? 0) >= 80 ? 'badge-green' : 'badge-amber' }}">
                            {{ $dashboardData['workflow_metrics']['completion_rate'] ?? 0 }}%
                        </span>
                    </div>
                    <div style="font-size: 20px; font-weight: bold;">{{ $dashboardData['workflow_metrics']['completion_rate'] ?? 0 }}%</div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Tempo Médio</span>
                        <span class="badge badge-blue">dias</span>
                    </div>
                    <div style="font-size: 20px; font-weight: bold;">{{ $dashboardData['workflow_metrics']['avg_completion_time'] ?? 0 }}</div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Criadas Hoje</span>
                        <span class="badge badge-purple">hoje</span>
                    </div>
                    <div style="font-size: 20px; font-weight: bold;">{{ $dashboardData['workflow_metrics']['orders_created_today'] ?? 0 }}</div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Total Ordens</span>
                        <span class="badge badge-gray">período</span>
                    </div>
                    <div style="font-size: 20px; font-weight: bold;">{{ $dashboardData['metrics']['orders']['current_period'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        {{-- TOP 3 DEPARTAMENTOS --}}
        <div class="section no-break">
            <h2 class="section-title">
                <span class="section-icon">🏆</span>
                Top 3 Departamentos por Produtividade
            </h2>
            <div class="grid grid-3">
                @foreach($dashboardData['top_departments'] ?? [] as $index => $dept)
                    <div class="card dept-card {{ $index === 0 ? 'card-gradient' : '' }}">
                        <div class="dept-position {{ $index === 0 ? 'pos-1' : ($index === 1 ? 'pos-2' : 'pos-3') }}">
                            {{ $index === 0 ? '🥇' : ($index === 1 ? '🥈' : '🥉') }}
                        </div>
                        <div class="dept-name">{{ $dept['name'] }}</div>
                        <div class="dept-employees">{{ $dept['total_employees'] }} funcionários</div>
                        
                        <div class="dept-score {{ $dept['productivity_score'] >= 80 ? 'score-excellent' : ($dept['productivity_score'] >= 60 ? 'score-good' : 'score-fair') }}">
                            {{ $dept['productivity_score'] }}%
                        </div>
                        <div style="font-size: 10px; color: #6b7280; margin-bottom: 15px;">Score de Produtividade</div>
                        
                        <div class="progress-bar">
                            <div class="progress-fill {{ $dept['productivity_score'] >= 80 ? 'progress-excellent' : ($dept['productivity_score'] >= 60 ? 'progress-good' : 'progress-fair') }}" 
                                 style="width: {{ $dept['productivity_score'] }}%"></div>
                        </div>
                        
                        <div class="dept-metrics">
                            <div>
                                <div class="value">{{ $dept['total_hours'] }}h</div>
                                <div class="label">Total Horas</div>
                            </div>
                            <div>
                                <div class="value">{{ $dept['orders_worked'] }}</div>
                                <div class="label">Ordens</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- NOVA PÁGINA --}}
        <div class="page-break"></div>

        {{-- FATURAÇÃO DETALHADA --}}
        <div class="section no-break">
            <h2 class="section-title">
                <span class="section-icon">💰</span>
                Faturação Detalhada
            </h2>
            <div class="grid grid-5">
                {{-- FATURAÇÃO HH --}}
                <div class="card billing-card billing-hh">
                    <div class="card-header">
                        <h4>Faturação HH</h4>
                        <span class="badge badge-blue">{{ $dashboardData['metrics']['billing']['hh']['count'] ?? 0 }}</span>
                    </div>
                    <div class="billing-row">
                        <span class="label">MZN:</span>
                        <span class="value">{{ number_format($dashboardData['metrics']['billing']['hh']['total_mzn'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="billing-row">
                        <span class="label">USD:</span>
                        <span class="value">${{ number_format($dashboardData['metrics']['billing']['hh']['total_usd'] ?? 0, 0, '.', ',') }}</span>
                    </div>
                    <div style="text-align: center; margin-top: 12px; font-size: 9px; color: #6b7280;">
                        Preço Sistema
                    </div>
                </div>

                {{-- FATURAÇÃO ESTIMADA --}}
                <div class="card billing-card billing-estimated">
                    <div class="card-header">
                        <h4>Faturação Estimada</h4>
                        <span class="badge badge-green">{{ $dashboardData['metrics']['billing']['estimated']['count'] ?? 0 }}</span>
                    </div>
                    <div class="billing-row">
                        <span class="label">MZN:</span>
                        <span class="value">{{ number_format($dashboardData['metrics']['billing']['estimated']['total_mzn'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="billing-row">
                        <span class="label">USD:</span>
                        <span class="value">${{ number_format($dashboardData['metrics']['billing']['estimated']['total_usd'] ?? 0, 0, '.', ',') }}</span>
                    </div>
                    <div style="text-align: center; margin-top: 12px; font-size: 9px; color: #6b7280;">
                        Preço Ajustável
                    </div>
                </div>

                {{-- FATURAÇÃO REAL --}}
                <div class="card billing-card billing-real" style="border: 2px solid #10b981;">
                    <div class="card-header">
                        <h4>Faturação Real ⭐</h4>
                        <span class="badge badge-green">{{ $dashboardData['metrics']['billing']['real']['count'] ?? 0 }}</span>
                    </div>
                    <div class="billing-row">
                        <span class="label">MZN:</span>
                        <span class="value" style="color: #7c3aed;">{{ number_format($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="billing-row">
                        <span class="label">USD:</span>
                        <span class="value" style="color: #7c3aed;">${{ number_format($dashboardData['metrics']['billing']['real']['total_usd'] ?? 0, 0, '.', ',') }}</span>
                    </div>
                    <div style="text-align: center; margin-top: 12px; font-size: 9px; color: #059669; font-weight: 600;">
                        Receita Confirmada
                    </div>
                </div>

                {{-- MATERIAIS --}}
                <div class="card billing-card billing-materials">
                    <div class="card-header">
                        <h4>Materiais</h4>
                        <span class="badge badge-amber">{{ $dashboardData['materials_breakdown']['summary']['total_materials_types'] ?? 0 }}</span>
                    </div>
                    <div class="billing-row">
                        <span class="label">Cadastrados:</span>
                        <span class="value">{{ number_format($dashboardData['materials_breakdown']['totals']['registered_mzn'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="billing-row">
                        <span class="label">Adicionais:</span>
                        <span class="value">{{ number_format($dashboardData['materials_breakdown']['totals']['additional_mzn'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="billing-total-row">
                        <div style="display: flex; justify-content: space-between; font-weight: bold;">
                            <span>Total:</span>
                            <span class="value">{{ number_format($dashboardData['materials_breakdown']['totals']['grand_total_mzn'] ?? 0, 0, ',', '.') }} MZN</span>
                        </div>
                    </div>
                </div>

                {{-- TOTAL GERAL --}}
                <div class="card billing-card billing-total" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1px solid #3730a3;">
                    <div class="card-header">
                        <h4>💰 Total Geral</h4>
                        <span class="badge" style="background: #e0f2fe; color: #3730a3;">SOMA</span>
                    </div>
                    @php
                        $totalMzn = ($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0) + 
                                   ($dashboardData['materials_breakdown']['totals']['grand_total_mzn'] ?? 0);
                        $totalUsd = ($dashboardData['metrics']['billing']['real']['total_usd'] ?? 0);
                    @endphp
                    <div class="billing-row">
                        <span class="label">MZN:</span>
                        <span class="value" style="font-size: 14px; color: #3730a3;">{{ number_format($totalMzn, 0, ',', '.') }}</span>
                    </div>
                    <div class="billing-row">
                        <span class="label">USD:</span>
                        <span class="value" style="font-size: 14px; color: #3730a3;">${{ number_format($totalUsd, 0, '.', ',') }}</span>
                    </div>
                    <div style="text-align: center; margin-top: 12px; font-size: 9px; color: #3730a3; font-weight: 600;">
                        Receita + Materiais
                    </div>
                </div>
            </div>
        </div>

        {{-- MATERIAIS BREAKDOWN DETALHADO --}}
        <div class="section no-break">
            <h2 class="section-title">
                <span class="section-icon">📦</span>
                Breakdown de Materiais
            </h2>
            <div class="grid grid-2">
                {{-- TOP MATERIAIS CADASTRADOS --}}
                <div class="card">
                    <div class="card-header">
                        <h3 style="font-size: 14px; font-weight: 600;">Top Materiais Cadastrados</h3>
                        <span style="font-size: 11px; color: #6b7280;">{{ $dashboardData['materials_breakdown']['totals']['registered_percentage'] ?? 0 }}% do total</span>
                    </div>
                    @forelse($dashboardData['materials_breakdown']['registered'] ?? [] as $material)
                        <div class="material-item">
                            <div class="material-info">
                                <div class="material-name">{{ $material->name }}</div>
                                <div class="material-details">{{ number_format($material->total_qty, 1) }} {{ $material->unit }} • {{ $material->orders_count }} ordens</div>
                            </div>
                            <div class="material-value">
                                <div class="material-amount">{{ number_format($material->total_mzn, 0, ',', '.') }}</div>
                                <div class="material-currency">MZN</div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 20px; color: #6b7280; font-size: 11px;">
                            Nenhum material utilizado
                        </div>
                    @endforelse
                </div>

                {{-- TOP MATERIAIS ADICIONAIS --}}
                <div class="card">
                    <div class="card-header">
                        <h3 style="font-size: 14px; font-weight: 600;">Top Materiais Adicionais</h3>
                        <span style="font-size: 11px; color: #6b7280;">{{ $dashboardData['materials_breakdown']['totals']['additional_percentage'] ?? 0 }}% do total</span>
                    </div>
                    @forelse($dashboardData['materials_breakdown']['additional'] ?? [] as $material)
                        <div class="material-item">
                            <div class="material-info">
                                <div class="material-name">{{ $material->nome_material }}</div>
                                <div class="material-details">{{ number_format($material->total_qty, 1) }} unid. • {{ number_format($material->avg_unit_cost, 2) }} MZN/unid • {{ $material->orders_count }} ordens</div>
                            </div>
                            <div class="material-value">
                                <div class="material-amount">{{ number_format($material->total_cost, 0, ',', '.') }}</div>
                                <div class="material-currency">MZN</div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 20px; color: #6b7280; font-size: 11px;">
                            Nenhum material adicional
                        </div>
                    @endforelse

                    @if(($dashboardData['materials_breakdown']['totals']['additional_percentage'] ?? 0) > 30)
                        <div class="alert-warning">
                            <strong>⚠️ Atenção:</strong> Alto uso de materiais não cadastrados ({{ $dashboardData['materials_breakdown']['totals']['additional_percentage'] }}%). Considere cadastrar materiais frequentes.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- CENTRO DE COMANDO OPERACIONAL --}}
        <div class="command-center no-break">
            <div class="command-header">
                <div class="command-title">📊 Centro de Comando Operacional</div>
                <div style="font-size: 11px; opacity: 0.8;">Visão Estratégica</div>
            </div>
            
            <div class="command-grid">
                {{-- SITUAÇÃO ATUAL --}}
                <div class="command-card">
                    <h4>ℹ️ Situação Atual</h4>
                    <div class="command-row">
                        <span>Ordens Ativas:</span>
                        <span style="font-weight: bold;">{{ $dashboardData['workflow_metrics']['orders_pending'] ?? 0 }}</span>
                    </div>
                    <div class="command-row">
                        <span>Funcionários Ativos:</span>
                        <span style="font-weight: bold;">{{ $dashboardData['metrics']['employees']['total_active'] ?? 0 }}</span>
                    </div>
                    <div class="command-row">
                        <span>Taxa Conclusão:</span>
                        <span style="font-weight: bold;">{{ $dashboardData['workflow_metrics']['completion_rate'] ?? 0 }}%</span>
                    </div>
                    <div class="command-row">
                        <span>Tempo Médio:</span>
                        <span style="font-weight: bold;">{{ $dashboardData['workflow_metrics']['avg_completion_time'] ?? 0 }} dias</span>
                    </div>
                </div>
                
                {{-- AÇÕES URGENTES --}}
                <div class="command-card">
                    <h4>⚠️ Ações Urgentes</h4>
                    @if(count($dashboardData['alerts'] ?? []) > 0)
                        @foreach($dashboardData['alerts'] as $alert)
                            @if($alert['type'] === 'warning')
                                <div style="background: rgba(239, 68, 68, 0.2); padding: 6px; border-radius: 4px; margin-bottom: 6px; border-left: 3px solid #ef4444;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 9px;">
                                        <span>{{ str_replace($alert['count'], '', $alert['message']) }}</span>
                                        <span style="background: #ef4444; color: white; padding: 2px 6px; border-radius: 8px; font-weight: bold;">{{ $alert['count'] }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div style="text-align: center; padding: 15px;">
                            <div style="color: #10b981; font-size: 11px;">✅ Tudo em ordem</div>
                            <div style="opacity: 0.7; font-size: 9px;">Nenhuma ação urgente necessária</div>
                        </div>
                    @endif
                </div>
                
                {{-- MÉTRICAS DE PERFORMANCE --}}
                <div class="command-card">
                    <h4>📈 Performance Global</h4>
                    <div>
                        <div class="command-row">
                            <span>Performance Média:</span>
                            <span style="font-weight: bold;">{{ $dashboardData['metrics']['employees']['avg_performance'] ?? 0 }}%</span>
                        </div>
                        <div class="command-progress">
                            <div class="command-progress-fill progress-green" style="width: {{ $dashboardData['metrics']['employees']['avg_performance'] ?? 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 10px;">
                        <div class="command-row">
                            <span>Receita MZN:</span>
                            <span style="font-weight: bold;">{{ number_format(($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0) / 1000, 0) }}k</span>
                        </div>
                        <div class="command-progress">
                            <div class="command-progress-fill progress-yellow" style="width: {{ min(100, ($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0) / 10000) }}%"></div>
                        </div>
                    </div>

                    <div style="padding-top: 8px; border-top: 1px solid rgba(255, 255, 255, 0.2); margin-top: 8px;">
                        <div class="command-row">
                            <span>Avaliações Pendentes:</span>
                            <span style="font-weight: bold;">{{ $dashboardData['metrics']['employees']['evaluations_pending'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- INSIGHTS INTELIGENTES --}}
        <div class="section no-break">
            <h2 class="section-title">
                <span class="section-icon">💡</span>
                Insights Inteligentes
            </h2>
            <div class="grid grid-4">
                {{-- INSIGHT 1: EFICIÊNCIA --}}
                <div class="card insight-card insight-blue">
                    <div class="insight-header">
                        <div class="insight-icon insight-icon-blue">📈</div>
                        <div>
                            <div class="insight-title">Eficiência</div>
                            <div class="insight-subtitle">Tempo vs Meta</div>
                        </div>
                    </div>
                    @php
                        $efficiency = ($dashboardData['workflow_metrics']['avg_completion_time'] ?? 0) <= 5 ? 'Excelente' : 
                                     (($dashboardData['workflow_metrics']['avg_completion_time'] ?? 0) <= 10 ? 'Boa' : 'Precisa melhorar');
                    @endphp
                    <div class="insight-content">
                        Tempo médio de {{ $dashboardData['workflow_metrics']['avg_completion_time'] ?? 0 }} dias está <strong>{{ strtolower($efficiency) }}</strong>
                    </div>
                    <div class="insight-tip">
                        💡 {{ $efficiency === 'Excelente' ? 'Continue assim!' : ($efficiency === 'Boa' ? 'Manter consistência' : 'Revisar processos') }}
                    </div>
                </div>

                {{-- INSIGHT 2: MATERIAIS --}}
                <div class="card insight-card insight-amber">
                    <div class="insight-header">
                        <div class="insight-icon insight-icon-amber">📦</div>
                        <div>
                            <div class="insight-title">Materiais</div>
                            <div class="insight-subtitle">Cadastro vs Adicional</div>
                        </div>
                    </div>
                    @php
                        $additionalPercent = $dashboardData['materials_breakdown']['totals']['additional_percentage'] ?? 0;
                    @endphp
                    <div class="insight-content">
                        {{ $additionalPercent }}% são materiais não cadastrados
                    </div>
                    <div class="insight-tip">
                        💡 {{ $additionalPercent > 30 ? 'Considere cadastrar materiais frequentes' : 'Controle adequado de materiais' }}
                    </div>
                </div>

                {{-- INSIGHT 3: DEPARTAMENTOS --}}
                <div class="card insight-card insight-green">
                    <div class="insight-header">
                        <div class="insight-icon insight-icon-green">👥</div>
                        <div>
                            <div class="insight-title">Departamentos</div>
                            <div class="insight-subtitle">Performance Global</div>
                        </div>
                    </div>
                    @php
                        $topDept = $dashboardData['top_departments'][0] ?? null;
                        $deptScore = $topDept['productivity_score'] ?? 0;
                    @endphp
                    <div class="insight-content">
                        @if($topDept)
                            <strong>{{ $topDept['name'] }}</strong> lidera com {{ $deptScore }}%
                        @else
                            Nenhum departamento ativo
                        @endif
                    </div>
                    <div class="insight-tip">
                        💡 {{ $deptScore >= 80 ? 'Excelente liderança!' : 'Oportunidade de melhoria' }}
                    </div>
                </div>

                {{-- INSIGHT 4: TENDÊNCIA --}}
                <div class="card insight-card insight-purple">
                    <div class="insight-header">
                        <div class="insight-icon insight-icon-purple">📊</div>
                        <div>
                            <div class="insight-title">Tendência</div>
                            <div class="insight-subtitle">Próximo Período</div>
                        </div>
                    </div>
                    @php
                        $changePercent = $dashboardData['metrics']['orders']['percentage_change'] ?? 0;
                        $trend = $changePercent > 10 ? 'Crescimento forte' : 
                                ($changePercent > 0 ? 'Crescimento moderado' : 
                                ($changePercent > -10 ? 'Estável' : 'Declínio'));
                    @endphp
                    <div class="insight-content">
                        {{ $changePercent > 0 ? '+' : '' }}{{ $changePercent }}% vs período anterior
                    </div>
                    <div class="insight-tip">
                        💡 {{ $trend }} - {{ $changePercent > 0 ? 'Preparar recursos' : 'Revisar estratégias' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- NOVA PÁGINA --}}
        <div class="page-break"></div>

        {{-- ORDENS RECENTES --}}
        <div class="section no-break">
            <h2 class="section-title">
                <span class="section-icon">📋</span>
                Ordens Recentes
            </h2>
            <div class="card">
                @forelse($dashboardData['recent_orders'] ?? [] as $order)
                    <div class="order-item">
                        <div class="order-info">
                            <div class="order-header">
                                <span class="order-id">{{ $order['id'] }}</span>
                                <span class="order-status {{ $order['status'] === 'Concluída' ? 'status-completed' : ($order['status'] === 'Em Andamento' ? 'status-progress' : 'status-pending') }}">
                                    {{ $order['status'] }}
                                </span>
                            </div>
                            <div class="order-client">
                                <strong>{{ $order['client'] }}</strong>
                            </div>
                            <div class="order-details">
                                <span>👤 {{ $order['technician'] }}</span>
                                <span>⏰ {{ $order['days_ago'] }} dia(s)</span>
                                <span class="order-status {{ $order['priority'] === 'high' ? 'priority-high' : ($order['priority'] === 'medium' ? 'priority-medium' : 'priority-low') }}">
                                    {{ ucfirst($order['priority']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <div style="font-size: 48px; margin-bottom: 15px;">📋</div>
                        <div style="font-size: 12px; font-weight: 600; margin-bottom: 5px;">Nenhuma ordem recente</div>
                        <div style="font-size: 10px;">Crie uma nova ordem para começar</div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MÉTRICAS PRINCIPAIS (RESUMO) --}}
        <div class="section no-break">
            <h2 class="section-title">
                <span class="section-icon">📊</span>
                Métricas Principais - Resumo
            </h2>
            <div class="grid grid-4">
                {{-- TOTAL ORDENS --}}
                <div class="card">
                    <div class="metric-card">
                        <div class="metric-icon metric-icon-blue">📋</div>
                        <div class="metric-content">
                            <h3>Total Ordens</h3>
                            <div class="value">{{ $dashboardData['metrics']['orders']['current_period'] ?? 0 }}</div>
                            @php $change = $dashboardData['metrics']['orders']['percentage_change'] ?? 0; @endphp
                            <div class="subtitle {{ $change >= 0 ? 'change-positive' : 'change-negative' }}">
                                {{ $change >= 0 ? '+' : '' }}{{ $change }}% vs anterior
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FUNCIONÁRIOS ATIVOS --}}
                <div class="card">
                    <div class="metric-card">
                        <div class="metric-icon metric-icon-green">👥</div>
                        <div class="metric-content">
                            <h3>Funcionários Ativos</h3>
                            <div class="value">{{ $dashboardData['metrics']['employees']['total_active'] ?? 0 }}</div>
                            <div class="subtitle">Performance: {{ $dashboardData['metrics']['employees']['avg_performance'] ?? 0 }}%</div>
                        </div>
                    </div>
                </div>

                {{-- FATURAÇÃO TOTAL --}}
                <div class="card">
                    <div class="metric-card">
                        <div class="metric-icon metric-icon-purple">💰</div>
                        <div class="metric-content">
                            <h3>Faturação Total</h3>
                            <div class="value" style="font-size: 18px;">{{ number_format($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0, 0, ',', '.') }}</div>
                            <div class="subtitle">MZN</div>
                        </div>
                    </div>
                </div>

                {{-- TAXA CONCLUSÃO --}}
                <div class="card">
                    <div class="metric-card">
                        <div class="metric-icon metric-icon-amber">✅</div>
                        <div class="metric-content">
                            <h3>Taxa Conclusão</h3>
                            <div class="value">{{ $dashboardData['workflow_metrics']['completion_rate'] ?? 100 }}%</div>
                            <div class="subtitle">Tempo médio: {{ $dashboardData['workflow_metrics']['avg_completion_time'] ?? 0 }} dias</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RODAPÉ --}}
        <div class="footer">
            <p><strong>{{ $company['name'] }}</strong> - Dashboard Executivo</p>
            <p>Relatório gerado automaticamente em {{ $company['generated_at'] }} • Período: {{ $company['period'] }}</p>
            <p style="margin-top: 8px; font-size: 9px;">Este relatório contém informações confidenciais da empresa.</p>
        </div>

    </div>
</body>
</html>