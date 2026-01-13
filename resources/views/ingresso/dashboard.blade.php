@extends('layout.main')

@section('title', 'SCP - Ingresso Dashboard')

@section('content')

<style>
    .ingresso-vh {
        min-height: calc(100vh - 80px);
    }
    .metric-card {
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        padding: 20px;
        color: #fff;
        height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    /* make the metric row support five equal columns on wide screens */
    .row.card-section { display: flex; flex-wrap: nowrap; gap: 0.6rem; margin: 0 -0.3rem; }
    .metric-col { flex: 0 0 calc(20% - 0.48rem); max-width: calc(20% - 0.48rem); padding: 0 0.3rem; box-sizing: border-box; }
    @media (max-width: 1199.98px) {
        .row.card-section { flex-wrap: wrap; }
        .metric-col { flex: 0 0 50%; max-width: 50%; padding: 0 0.5rem; }
    }
    @media (max-width: 575.98px) {
        .metric-col { flex: 0 0 100%; max-width: 100%; padding: 0; }
    }
    .metric-title { font-size: 0.95rem; opacity: 0.9; }
    .metric-value { font-size: 2.2rem; font-weight: 700; }
    .metric-icon { font-size: 2.4rem; opacity: 0.15; position: absolute; right: 18px; top: 18px; }
    .card-section { margin-bottom: 18px; }
    .nte-table th, .nte-table td { vertical-align: middle; }
    .full-panel { padding: 22px; }
    .chart-container { width: 100%; }

    /* Mobile adjustments */
    @media (max-width: 576px) {
        .ingresso-vh { min-height: auto; padding: 12px; }
        .full-panel { padding: 12px; }
        .metric-card { height: auto; padding: 14px; }
        .metric-title { font-size: 0.85rem; }
        .metric-value { font-size: 1.6rem; }
        .metric-icon { right: 12px; top: 12px; }
        .chart-container { height: 220px !important; }
        .card-section { margin-bottom: 12px; }
        .top-action { width: 100%; margin-top: 8px; }
    }

    @media (min-width: 577px) {
        .chart-container { height: 320px; }
    }
</style>

<div class="container-fluid ingresso-vh full-panel">
    <div class="row mb-3">
        <div class="col-12 d-flex align-items-center justify-content-between mb-2">
            <div>
                <h3 class="mb-0">Ingresso</h3>
                <small class="text-muted">Visão geral do processo de ingresso</small>
            </div>
            <div class="top-action">
                <a href="{{ route('ingresso.index') }}" class="btn btn-outline-primary">Ver todos os convocados</a>
            </div>
        </div>
    </div>

    <div class="row card-section">
        <div class="metric-col mb-3">
            <div class="position-relative metric-card bg-primary">
                <div>
                    <div class="metric-title">Total de Candidatos</div>
                    <div class="metric-value">{{ $stats['total_candidates'] }}</div>
                </div>
                <div class="metric-icon">&#9881;</div>
            </div>
        </div>
        <div class="metric-col mb-3">
            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#28a745,#20c997);">
                <div>
                    <div class="metric-title">Já Ingressados</div>
                    <div class="metric-value">{{ $stats['ingressados'] }}</div>
                </div>
                <div class="metric-icon">&#10004;</div>
            </div>
        </div>
        <div class="metric-col mb-3">
            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#ffc107,#ff9800);">
                <div>
                    <div class="metric-title">Pendência de Docs</div>
                    <div class="metric-value">{{ $stats['pendencia_documentos'] }}</div>
                </div>
                <div class="metric-icon">&#9888;</div>
            </div>
        </div>
        <div class="metric-col mb-3">
            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#17a2b8,#0dcaf0);">
                <div>
                    <div class="metric-title">Docs Validados</div>
                    <div class="metric-value">{{ $stats['documentos_validados'] }}</div>
                </div>
                <div class="metric-icon">&#128214;</div>
            </div>
        </div>
        <div class="metric-col mb-3">
            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#6f42c1,#8e44ad);">
                <div>
                    <div class="metric-title">Pendente Confirmação CPM</div>
                    <div class="metric-value">{{ $stats['pendente_confirmacao_cpm'] ?? '-' }}</div>
                </div>
                <div class="metric-icon">&#9203;</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Quantidade por NTE</h5>
                    <div class="chart-container">
                        <canvas id="nteChart" style="width:100%; height:100%; display:block;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Resumo</h5>
                    <p class="text-muted">Ações rápidas e resumo geral.</p>

                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Candidatos totais
                            <span class="badge bg-primary text-white">{{ $stats['total_candidates'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Ingressados
                            <span class="badge bg-success">{{ $stats['ingressados'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Pendências
                            <span class="badge bg-warning text-dark">{{ $stats['pendencia_documentos'] }}</span>
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('ingresso.index') }}" class="btn btn-primary btn-block">Abrir lista completa</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const raw = @json($nte_breakdown ?? []);
        if (!raw || !raw.length) return;

        const labels = raw.map(r => r.nte);
        const data = raw.map(r => r.count);

        const ctx = document.getElementById('nteChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Quantidade por NTE',
                    data: data,
                    backgroundColor: labels.map((_,i) => {
                        const palette = ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b'];
                        return palette[i % palette.length];
                    }),
                    borderRadius: 6,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endpush
