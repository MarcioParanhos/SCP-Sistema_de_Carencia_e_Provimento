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

    /* Top action buttons spacing and responsive stacking */
    .top-action { display: flex; gap: 0.5rem; align-items: center; }
    @media (max-width: 576px) {
        .top-action { flex-direction: column; width: 100%; }
        .top-action .btn { width: 100%; }
    }

    /* Icon-only buttons that reveal label on hover/focus with smooth transition */
    /* ensure icon is perfectly centered when label hidden (no gap reserved) */
    .top-action .btn { display:inline-flex; align-items:center; justify-content:center; padding:8px 10px; min-width:44px; overflow:visible; }
    .top-action .btn .btn-icon { display:inline-flex; align-items:center; justify-content:center; transition: opacity .28s ease, transform .28s ease; }
    .top-action .btn .btn-label { display:inline-block; white-space:nowrap; opacity:0; max-width:0; overflow:hidden; padding-left:0; transition: opacity .28s ease, max-width .28s ease, padding-left .28s ease; }
    .top-action .btn { text-decoration:none; }

    /* On hover/focus: reveal label smoothly and fade icon */
    .top-action .btn:focus .btn-label,
    .top-action .btn:hover .btn-label,
    .top-action .btn:active .btn-label { opacity:1; max-width:220px; padding-left:8px; }
    .top-action .btn:focus .btn-icon,
    .top-action .btn:hover .btn-icon,
    .top-action .btn:active .btn-icon { opacity:0; transform: scale(.92); }

    @media (max-width: 576px) {
        /* On small screens, show labels by default for tappable targets */
        .top-action .btn .btn-label { opacity:1; max-width:1000px; padding-left:8px; }
        .top-action .btn .btn-icon { opacity:1; }
    }

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
    /* Modal guide sizing: limit overall height and make body scrollable so footer stays visible */
    .modal-dialog {
        max-height: 90vh;
        margin: 1.75rem auto;
    }
    .modal-content {
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }
    .modal-body {
        overflow-y: auto;
        max-height: calc(90vh - 160px);
        -webkit-overflow-scrolling: touch;
    }
    /* Convocação tab top border */
    #aptosTabs { border-bottom: 0; }
    .aptos-tab-link { border-top: 4px solid transparent; border-radius: 6px 6px 0 0; padding-top: 6px; background: #fff; position: relative; z-index: 2; }
    .aptos-tab-link.active { border-top-color: var(--bs-primary, #0d6efd); box-shadow: 0 -2px 8px rgba(14,21,47,0.04); }
    /* COP panel modern styles */
    .cop-panel .card { border-radius:12px; overflow:visible; }
    .cop-panel .card-body { display:flex; gap:1rem; align-items:center; padding:1rem 1.25rem; }
    .cop-block { display:flex; align-items:center; gap:0.9rem; min-width:160px; }
    .cop-icon { width:40px; height:40px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; }
    .cop-meta { min-width:150px; }
    .cop-title { font-size:0.8rem; color:#6c757d; }
    .cop-value { font-size:1.25rem; font-weight:700; }
    .cop-aux { text-align:right; margin-left:auto; min-width:180px; }
    .cop-aux .cop-free { font-size:1.1rem; font-weight:700; color:#198754; }
    .cop-progress { height:10px; border-radius:8px; overflow:hidden; background:#e9ecef; }
    .cop-progress .bar { height:100%; background: linear-gradient(90deg,#0d6efd,#6610f2); }
    @media (max-width: 767.98px) {
        .cop-aux { min-width:120px; text-align:left; width:100%; }
        .cop-panel .card-body { flex-direction:column; align-items:flex-start; gap:0.5rem; }
    }
</style>

@php
    $currentConv = session('filter_convocacao', request()->query('filter_convocacao', 1));
    $currentConv = is_numeric($currentConv) ? intval($currentConv) : 1;
@endphp

<div class="container-fluid ingresso-vh full-panel">
    <div class="row mb-3">
        <div class="col-12 d-flex align-items-center justify-content-between mb-2">
            <div>
                <h4 class="mb-0"><strong>VALIDAÇÃO DA DOCUMENTAÇÃO</strong></h4>
                <small class="text-muted">Visão geral do processo de ingresso</small>
            </div>
            <div class="top-action">
                <a id="btn-view-all" href="{{ route('ingresso.index') }}" class="btn btn-primary" aria-label="Ver todos os convocados">
                    <span class="btn-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                    </span>
                    <span class="btn-label">Ver todos os convocados</span>
                </a>

                <a href="{{ route('ingresso.aptos') }}?filter_convocacao={{ $currentConv }}" class="btn btn-primary" aria-label="Aptos para encaminhamento">
                    <span class="btn-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-share"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h3" /><path d="M16 22l5 -5" /><path d="M21 21.5v-4.5h-4.5" /></svg>
                    </span>
                    <span class="btn-label">Aptos para encaminhamento</span>
                </a>

                <!-- Exportar Substituídos button removed from dashboard (available on index) -->

                <button id="btn-open-dashboard-doc" type="button" class="btn btn-primary" title="Guia da página" aria-label="Guia da página">
                    <span class="btn-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
                    </span>
                    <span class="btn-label">Guia da Página</span>
                </button>
            </div>
        </div>
    </div>

    @if(auth()->check() && optional(auth()->user())->sector_id == 2)
        {{-- Painel COP: valores calculados no controller e injetados na view --}}
        @php
            $copNumber = $copNumber ?? '—';
            $copQuantity = $copQuantity ?? 0;
            $candidatesCount = $candidatesCount ?? 0;
            $copFree = $copFree ?? max(0, (int)$copQuantity - (int)$candidatesCount);
        @endphp

        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm cop-panel">
                    <div class="card-body">
                        <div class="cop-block">
                            <div class="cop-icon" style="background:linear-gradient(180deg,#0d6efd,#6610f2);">
                                <!-- Tabler: file-text icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6"></path><path d="M9 13h6"></path><path d="M9 17h6"></path></svg>
                            </div>
                            <div class="cop-meta">
                                <div class="cop-title">Nº do COPE</div>
                                <div class="cop-value">{{ $copNumber ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="cop-block">
                            <div class="cop-icon" style="background:#6f42c1;">
                                <!-- Tabler: hash icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 3v18"></path><path d="M16 3v18"></path><path d="M3 8h18"></path><path d="M3 16h18"></path></svg>
                            </div>
                            <div class="cop-meta">
                                <div class="cop-title">Quantidade total</div>
                                <div class="cop-value">{{ number_format($copQuantity ?? 0,0,',','.') }}</div>
                            </div>
                        </div>

                        <div class="cop-block">
                            <div class="cop-icon" style="background:#e74a3b;">
                                <!-- Tabler: users icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 7a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/></svg>
                            </div>
                            <div class="cop-meta">
                                <div class="cop-title">Candidatos atribuídos</div>
                                <div class="cop-value">{{ number_format(($candidatesCount ?? 0),0,',','.') }}</div>
                            </div>
                        </div>

                        <div class="cop-aux">
                            @php
                                $used = max(0, ($copQuantity ?? 0) - ($copFree ?? 0));
                                $pct = ($copQuantity > 0) ? round(($used / max(1,$copQuantity)) * 100) : 0;
                            @endphp
                            <div class="text-muted small">COPEs livres</div>
                            <div class="cop-free">{{ number_format(($copFree ?? 0) + 18,0,',','.') }}</div>
                            <div class="cop-progress mt-2">
                                <div class="bar" style="width: {{ $pct }}%;"></div>
                            </div>
                            <div class="small text-muted mt-1">Usados: {{ $pct }}% — {{ number_format($used - 18,0,',','.') }} / {{ number_format($copQuantity ?? 0,0,',','.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal: Dashboard Guide -->
    <div class="modal fade" id="dashboardGuideModal" tabindex="-1" role="dialog" aria-labelledby="dashboardGuideModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dashboardGuideModalLabel">Guia: Dashboard de Validação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="dashboardGuideContent" class="markdown-body">Carregando...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs will contain full dashboards per convocação --}}

    <style>
        /* Active tab styling: apply to the active nav-link, not the UL element */
        #aptosTabs .aptos-tab-link.active {
            border-top: 3px solid #0050e3 !important;
            background-color: #fff !important;
            box-shadow: 0 -2px 8px rgba(14,21,47,0.04);
        }
    </style>
    <div class="card mb-3">
        <div class="card-body">
            <ul class="nav nav-tabs" id="aptosTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link aptos-tab-link {{ $currentConv === 1 ? 'active' : '' }}" id="conv1-tab" data-toggle="tab" href="#conv1" role="tab" aria-controls="conv1" aria-selected="{{ $currentConv === 1 ? 'true' : 'false' }}">1ª Convocação</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link aptos-tab-link {{ $currentConv === 2 ? 'active' : '' }}" id="conv2-tab" data-toggle="tab" href="#conv2" role="tab" aria-controls="conv2" aria-selected="{{ $currentConv === 2 ? 'true' : 'false' }}">2ª Convocação</a>
                </li>
            </ul>

            <div class="tab-content" id="aptosTabsContent">
                <div class="tab-pane fade {{ $currentConv === 1 ? 'show active' : '' }}" id="conv1" role="tabpanel" aria-labelledby="conv1-tab">
                    <div class="row card-section">
                        <div class="metric-col mb-3">
                            <div class="position-relative metric-card bg-primary">
                                <div>
                                    <div class="metric-title">Total de Candidatos (1ª)</div>
                                    <div class="metric-value">{{ $stats_conv1['total_candidates'] ?? 0 }}</div>
                                </div>
                                <div class="metric-icon">&#9881;</div>
                            </div>
                        </div>
                        <div class="metric-col mb-3">
                            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#ffc107,#ff9800);">
                                <div>
                                    <div class="metric-title">Pendência de Docs</div>
                                    <div class="metric-value">{{ ($stats_conv1['pendencia_documentos'] ?? 0)}}</div>
                                </div>
                                <div class="metric-icon">&#9888;</div>
                            </div>
                        </div>
                        <div class="metric-col mb-3">
                            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#e74a3b,#ff6b6b);">
                                <div>
                                    <div class="metric-title">Corrigir documentação</div>
                                    <div class="metric-value">{{ $stats_conv1['corrigir_documentacao'] ?? 0 }}</div>
                                </div>
                                <div class="metric-icon">&#9888;</div>
                            </div>
                        </div>
                        <div class="metric-col mb-3">
                            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#6f42c1,#8e44ad);">
                                <div>
                                    <div class="metric-title">Pendente Confirmação CPM</div>
                                    <div class="metric-value">{{ $stats_conv1['pendente_confirmacao_cpm'] ?? 0 }}</div>
                                </div>
                                <div class="metric-icon">&#9203;</div>
                            </div>
                        </div>
                        <div class="metric-col mb-3">
                            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#17a2b8,#0dcaf0);">
                                <div>
                                    <div class="metric-title">Docs Validados</div>
                                    <div class="metric-value">{{ $stats_conv1['documentos_validados'] ?? 0 }}</div>
                                </div>
                                <div class="metric-icon">&#128214;</div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-8 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Quantidade por NTE (1ª Convocação)</h5>
                                    <div class="chart-container">
                                        <canvas id="nteChart1" style="width:100%; height:100%; display:block;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">Resumo (1ª)</h5>
                                    <p class="text-muted">Visão rápida da 1ª convocação.</p>

                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Aptos para encaminhamento
                                            <span class="badge bg-success text-white">{{ $stats_conv1['ingressados'] ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Candidatos totais
                                            <span class="badge bg-primary text-white">{{ $stats_conv1['total_candidates'] ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Pendências
                                            <span class="badge bg-warning text-dark">{{ $stats_conv1['pendencia_documentos'] ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Corrigir documentação
                                            <span class="badge bg-danger text-white">{{ $stats_conv1['corrigir_documentacao'] ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Não Assumiu
                                            <span class="badge bg-dark text-white">{{ $stats_conv1['nao_assumiu'] ?? 0 }}</span>
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

                <div class="tab-pane fade {{ $currentConv === 2 ? 'show active' : '' }}" id="conv2" role="tabpanel" aria-labelledby="conv2-tab">
                    <div class="row card-section">
                        <div class="metric-col mb-3">
                            <div class="position-relative metric-card bg-primary">
                                <div>
                                    <div class="metric-title">Total de Candidatos (2ª)</div>
                                    <div class="metric-value">{{ $stats_conv2['total_candidates'] ?? 0 }}</div>
                                </div>
                                <div class="metric-icon">&#9881;</div>
                            </div>
                        </div>
                        <div class="metric-col mb-3">
                            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#e74a3b,#ff6b6b);">
                                <div>
                                    <div class="metric-title">Corrigir documentação</div>
                                    <div class="metric-value">{{ $stats_conv2['corrigir_documentacao'] ?? 0 }}</div>
                                </div>
                                <div class="metric-icon">&#9888;</div>
                            </div>
                        </div>
                        <div class="metric-col mb-3">
                            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#ffc107,#ff9800);">
                                <div>
                                    <div class="metric-title">Pendência de Docs</div>
                                    <div class="metric-value">{{ $stats_conv2['pendencia_documentos'] ?? 0 }}</div>
                                </div>
                                <div class="metric-icon">&#9888;</div>
                            </div>
                        </div>
                        <div class="metric-col mb-3">
                            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#17a2b8,#0dcaf0);">
                                <div>
                                    <div class="metric-title">Docs Validados</div>
                                    <div class="metric-value">{{ $stats_conv2['documentos_validados'] ?? 0 }}</div>
                                </div>
                                <div class="metric-icon">&#128214;</div>
                            </div>
                        </div>
                        <div class="metric-col mb-3">
                            <div class="position-relative metric-card" style="background: linear-gradient(90deg,#6f42c1,#8e44ad);">
                                <div>
                                    <div class="metric-title">Pendente Confirmação CPM</div>
                                    <div class="metric-value">{{ $stats_conv2['pendente_confirmacao_cpm'] ?? 0 }}</div>
                                </div>
                                <div class="metric-icon">&#9203;</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-8 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Quantidade por NTE (2ª Convocação)</h5>
                                    <div class="chart-container">
                                        <canvas id="nteChart2" style="width:100%; height:100%; display:block;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">Resumo (2ª)</h5>
                                    <p class="text-muted">Visão rápida da 2ª convocação.</p>

                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Candidatos totais
                                            <span class="badge bg-primary text-white">{{ $stats_conv2['total_candidates'] ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Pendências
                                            <span class="badge bg-warning text-dark">{{ $stats_conv2['pendencia_documentos'] ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Corrigir documentação
                                            <span class="badge bg-danger text-white">{{ $stats_conv2['corrigir_documentacao'] ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Não Assumiu
                                            <span class="badge bg-dark text-white">{{ $stats_conv2['nao_assumiu'] ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Aptos para encaminhamento
                                            <span class="badge bg-success text-white">{{ $stats_conv2['ingressados'] ?? 0 }}</span>
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
            </div>
        </div>
    </div>

    {{-- global chart/summary removed; charts are per-tab now --}}

</div>

@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function renderNteChart(canvasId, rawData) {
            if (!rawData || !rawData.length) return;
            const labels = rawData.map(r => r.nte);
            const data = rawData.map(r => r.count);
            const ctxEl = document.getElementById(canvasId);
            if (!ctxEl) return;
            const ctx = ctxEl.getContext('2d');
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
                    plugins: { legend: { display: false } },
                    scales: { x: { grid: { display: false } }, y: { beginAtZero: true } }
                }
            });
        }

        renderNteChart('nteChart1', @json($nte_breakdown_conv1 ?? []));
        renderNteChart('nteChart2', @json($nte_breakdown_conv2 ?? []));
    });
</script>
<script>
    // Keep the "Ver todos os convocados" link in sync with the active convocação tab
    document.addEventListener('DOMContentLoaded', function(){
        var link = document.getElementById('btn-view-all');
        if (!link) return;

        function setConvLink(conv) {
            var base = '{{ route('ingresso.index') }}';
            var url = new URL(base, window.location.origin);
            url.searchParams.set('filter_convocacao', String(conv));
            link.href = url.pathname + url.search;
        }

        // initialize based on currently active tab
        var active = document.querySelector('#aptosTabs .nav-link.active');
        if (active && active.id === 'conv2-tab') setConvLink(2); else setConvLink(1);

        // Delegated capture handler on the tabs container to ensure interception
        var tabsContainer = document.getElementById('aptosTabs');
        if (tabsContainer) {
            tabsContainer.addEventListener('click', function(ev){
                try { console.debug('aptosTabs clicked'); } catch(e){}
                var a = ev.target.closest && ev.target.closest('a[data-toggle="tab"]');
                if (!a) return;
                // ensure we only handle actual tab anchors inside this container
                if (!tabsContainer.contains(a)) return;
                ev.preventDefault();
                ev.stopPropagation && ev.stopPropagation();
                ev.stopImmediatePropagation && ev.stopImmediatePropagation();

                var id = a.id || '';
                var val = (id === 'conv2-tab') ? 2 : 1;
                var label = a.textContent.trim() || ('Convocação ' + val);

                function confirmAndShow() {
                    try { console.debug('confirmAndShow for', label); } catch(e){}
                    // set session and show tab
                    try { fetch('{{ route('ingresso.session.convocacao') }}', { method: 'POST', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify({ convocacao: val }) }); } catch(e){}
                    setConvLink(val);
                    try {
                        // mark as confirmed so Bootstrap's show handler doesn't re-prompt
                        if (window.jQuery && typeof $(a).data === 'function') { $(a).data('confirmed', true); }
                        else if (a && a.dataset) { a.dataset.confirmed = 'true'; }
                        if (window.jQuery && typeof $(a).tab === 'function') { $(a).tab('show'); }
                        else {
                            document.querySelectorAll('#aptosTabs .nav-link').forEach(function(x){ x.classList.remove('active'); });
                            a.classList.add('active');
                            document.querySelectorAll('.tab-pane').forEach(function(p){ p.classList.remove('show','active'); });
                            var pane = document.querySelector(a.getAttribute('href'));
                            if (pane) pane.classList.add('show','active');
                        }
                    } catch(e){}
                }

                if (window.Swal && typeof Swal.fire === 'function') {
                    Swal.fire({ title: 'Confirmar alteração', text: 'Deseja mudar para ' + label + '?', icon: 'question', showCancelButton: true, confirmButtonText: 'Sim', cancelButtonText: 'Não' }).then(function(resp){ if (resp && resp.isConfirmed) confirmAndShow(); });
                } else {
                    if (confirm('Deseja mudar para ' + label + '?')) confirmAndShow();
                }
            }, true);
        }

        // listen for tab changes: support both jQuery/Bootstrap events and plain clicks
        var tabs = document.querySelectorAll('#aptosTabs a[data-toggle="tab"]');
        tabs.forEach(function(t){
            // intercept click and ask for confirmation before switching convocação
            // use capture phase and stopImmediatePropagation to prevent Bootstrap/jQuery handlers
            t.addEventListener('click', function(e){
                e.preventDefault();
                e.stopImmediatePropagation && e.stopImmediatePropagation();
            }, true);

            t.addEventListener('click', function(e){
                var val = (t.id === 'conv2-tab') ? 2 : 1;
                var label = t.textContent.trim() || ('Convocação ' + val);

                function proceed() {
                    // set server-side session so subsequent actions use this convocação
                    try {
                        fetch('{{ route('ingresso.session.convocacao') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ convocacao: val })
                        }).catch(function(){});
                    } catch (err) {}
                    if (t.id === 'conv2-tab') setConvLink(2); else setConvLink(1);
                    // show the tab (support Bootstrap jQuery tab if available)
                    try {
                        if (window.jQuery && typeof $(t).tab === 'function') {
                            $(t).tab('show');
                        } else {
                            // fallback: manually toggle classes
                            document.querySelectorAll('#aptosTabs .nav-link').forEach(function(a){ a.classList.remove('active'); });
                            t.classList.add('active');
                            document.querySelectorAll('.tab-pane').forEach(function(p){ p.classList.remove('show','active'); });
                            var pane = document.querySelector(t.getAttribute('href'));
                            if (pane) pane.classList.add('show','active');
                        }
                    } catch (err) {}
                }

                // Use SweetAlert2 if available, otherwise fallback to native confirm
                if (window.Swal && typeof Swal.fire === 'function') {
                    Swal.fire({
                        title: 'Confirmar alteração',
                        text: 'Deseja mudar para ' + label + '?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Não'
                    }).then(function(resp){ if (resp && resp.isConfirmed) proceed(); });
                } else {
                    if (confirm('Deseja mudar para ' + label + '?')) proceed();
                }
            });
        });
        // if jQuery + Bootstrap is present, also listen to the shown.bs.tab event for programmatic changes
        if (window.jQuery && typeof jQuery === 'function' && typeof jQuery.fn !== 'undefined') {
            try {
                $(document).on('shown.bs.tab', '#aptosTabs a[data-toggle="tab"]', function(e){
                    if (!e || !e.target) return;
                    var id = e.target.id || '';
                    if (id === 'conv2-tab') {
                        try { fetch('{{ route('ingresso.session.convocacao') }}', { method: 'POST', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify({ convocacao: 2 }) }); } catch(e){}
                        setConvLink(2);
                    } else {
                        try { fetch('{{ route('ingresso.session.convocacao') }}', { method: 'POST', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify({ convocacao: 1 }) }); } catch(e){}
                        setConvLink(1);
                    }
                });
            } catch (err) {
                // ignore if Bootstrap's tab plugin not available
            }
        }

        // Additionally, intercept Bootstrap's show event to force confirmation before changing tabs
        try {
            if (window.jQuery && typeof jQuery === 'function' && typeof jQuery.fn !== 'undefined') {
                $(document).on('show.bs.tab', '#aptosTabs a[data-toggle="tab"]', function(e){
                        var $t = $(this);
                        // if already confirmed programmatically, allow (check jQuery data or DOM dataset)
                        var domEl = $t && $t.get && $t.get(0) ? $t.get(0) : null;
                        var confirmedFlag = false;
                        try { confirmedFlag = ($t.data && $t.data('confirmed')) || (domEl && domEl.dataset && domEl.dataset.confirmed === 'true'); } catch(e) { confirmedFlag = false; }
                        if (confirmedFlag) {
                            try { $t.data && $t.data('confirmed', false); } catch(e){}
                            try { if (domEl && domEl.dataset) domEl.dataset.confirmed = 'false'; } catch(e){}
                            return;
                        }
                    e.preventDefault();
                    var id = this.id || '';
                    var val = (id === 'conv2-tab') ? 2 : 1;
                    var label = $t.text().trim() || ('Convocação ' + val);

                    function doShow() {
                        $t.data('confirmed', true);
                        $t.tab('show');
                        try { fetch('{{ route('ingresso.session.convocacao') }}', { method: 'POST', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify({ convocacao: val }) }); } catch(e){}
                        setConvLink(val);
                    }

                    if (window.Swal && typeof Swal.fire === 'function') {
                        Swal.fire({ title: 'Confirmar alteração', text: 'Deseja mudar para ' + label + '?', icon: 'question', showCancelButton: true, confirmButtonText: 'Sim', cancelButtonText: 'Não' }).then(function(resp){ if (resp && resp.isConfirmed) doShow(); });
                    } else {
                        if (confirm('Deseja mudar para ' + label + '?')) doShow();
                    }
                });
            }
        } catch (err) {
            // ignore any errors
        }
    });
</script>
    <script>
        // Dashboard guide: load marked.js dynamically and render the markdown guide
        (function(){
            function ensureMarked(cb){
                if (window.marked) return cb();
                var s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/marked/marked.min.js';
                s.onload = function(){ cb(); };
                s.onerror = function(){ cb(new Error('failed to load marked')); };
                document.head.appendChild(s);
            }

            function openGuideModal(){
                var container = document.getElementById('dashboardGuideContent');
                if (!container) return;
                fetch('/docs/INGRESSO_DASHBOARD.md').then(function(r){ return r.text(); }).then(function(md){
                    try {
                        ensureMarked(function(err){
                            if (!err && window.marked) {
                                container.innerHTML = marked.parse(md);
                                return;
                            }

                            // Fallback minimal renderer: convert image markdown to <img> and escape the rest
                            try {
                                // Convert image syntax ![alt](url) to img tags
                                var html = md.replace(/!\[([^\]]*)\]\(([^)]+)\)/g, function(_, alt, src){
                                    // sanitize src by allowing only relative or absolute paths
                                    var safeSrc = src.replace(/"/g, '%22');
                                    return '<img src="' + safeSrc + '" alt="' + (alt||'') + '" style="height:20px; vertical-align:middle; margin-right:8px;" />';
                                });

                                // Replace double newlines with paragraph breaks and single newlines with <br>
                                html = html.replace(/\r/g,'').replace(/\n\n+/g, '</p><p>').replace(/\n/g, '<br>');
                                html = '<div class="markdown-body"><p>' + html + '</p></div>';
                                container.innerHTML = html;
                                return;
                            } catch (e) {
                                container.innerHTML = '<pre style="white-space:pre-wrap;">' + md.replace(/</g,'&lt;') + '</pre>';
                                return;
                            }
                        });
                    } catch(e){
                        container.innerHTML = '<pre style="white-space:pre-wrap;">' + md.replace(/</g,'&lt;') + '</pre>';
                    }
                }).catch(function(){
                    container.innerHTML = '<div class="text-danger">Não foi possível carregar o guia.</div>';
                });

                // show modal (support Bootstrap/jQuery if present)
                try {
                    if (window.jQuery && typeof jQuery === 'function' && typeof jQuery.fn.modal === 'function') {
                        $('#dashboardGuideModal').modal('show');
                    } else {
                        var m = document.getElementById('dashboardGuideModal');
                        if (m) m.style.display = 'block';
                    }
                } catch(e){}
            }

            document.addEventListener('DOMContentLoaded', function(){
                var btn = document.getElementById('btn-open-dashboard-doc');
                if (!btn) return;
                btn.addEventListener('click', function(){ openGuideModal(); });
            });
        })();
    </script>
@endpush
