<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Avaliação de Desempenho - {{ $evaluation->employee->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .company-info { text-align: center; margin-bottom: 20px; }
        .evaluation-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .employee-info { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .metrics-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .metrics-table th, .metrics-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .metrics-table th { background-color: #f2f2f2; }
        .performance-summary { background: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .signatures { display: flex; justify-content: space-between; margin-top: 50px; }
        .signature-box { text-align: center; width: 200px; }
        .signature-line { border-top: 1px solid #333; margin-top: 50px; padding-top: 5px; }
        .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #666; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>AVALIAÇÃO DE DESEMPENHO</h1>
        <div class="company-info">
            <h2>{{ $evaluation->employee->company->name }}</h2>
            <p>{{ $evaluation->employee->company->email }} | {{ $evaluation->employee->company->phone }}</p>
        </div>
    </div>

    <div class="evaluation-info">
        <div class="employee-info">
            <div>
                <strong>Funcionário:</strong> {{ $evaluation->employee->name }}<br>
                <strong>Código:</strong> {{ $evaluation->employee->code }}<br>
                <strong>Departamento:</strong> {{ $evaluation->employee->department->name }}
            </div>
            <div>
                <strong>Período:</strong> {{ $evaluation->evaluation_period_formatted }}<br>
                <strong>Data de Avaliação:</strong> {{ $evaluation->approved_at?->format('d/m/Y') }}<br>
                <strong>Avaliador:</strong> {{ $evaluation->evaluator->name }}
            </div>
        </div>
    </div>

    <div class="performance-summary">
        <h3>RESUMO DA PERFORMANCE</h3>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong>Pontuação Final:</strong> {{ number_format($evaluation->final_percentage, 1) }}%<br>
                <strong>Classificação:</strong> {{ $evaluation->performance_class }}
            </div>
            <div style="font-size: 24px; font-weight: bold; color: #1976d2;">
                {{ number_format($evaluation->final_percentage, 1) }}%
            </div>
        </div>
    </div>

    <h3>DETALHAMENTO POR MÉTRICA</h3>
    <table class="metrics-table">
        <thead>
            <tr>
                <th>Métrica</th>
                <th>Tipo</th>
                <th>Peso</th>
                <th>Valor Avaliado</th>
                <th>Pontuação</th>
                <th>Comentários</th>
            </tr>
        </thead>
        <tbody>
            @foreach($evaluation->responses as $response)
            <tr>
                <td>{{ $response->metric->name }}</td>
                <td>{{ $response->metric->type_display }}</td>
                <td>{{ $response->metric->weight }}%</td>
                <td>{{ $response->display_value }}</td>
                <td>{{ number_format($response->calculated_score, 1) }}</td>
                <td>{{ $response->comments ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($evaluation->recommendations)
    <div style="margin-bottom: 30px;">
        <h3>RECOMENDAÇÕES</h3>
        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
            {{ $evaluation->recommendations }}
        </div>
    </div>
    @endif

    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line">
                {{ $evaluation->employee->name }}<br>
                <small>Funcionário</small>
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                {{ $evaluation->evaluator->name }}<br>
                <small>Avaliador</small>
            </div>
        </div>
        @if($evaluation->approvedBy)
        <div class="signature-box">
            <div class="signature-line">
                {{ $evaluation->approvedBy->name }}<br>
                <small>Aprovado por</small>
            </div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Documento gerado em {{ now()->format('d/m/Y H:i') }}</p>
        <p>Sistema de Gestão de Reparações - {{ $evaluation->employee->company->name }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>