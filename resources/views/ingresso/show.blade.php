@extends('layout.main')

@section('title', 'SCP - Ingresso - Detalhes')

@section('content')

    <div class="card">
        @if (session('status'))
            <div class="col-12">
                <div class="alert text-center text-white bg-success container alert-success alert-dismissible fade show"
                    role="alert" style="min-width: 100%">
                    <strong>{{ session('status') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
        <style>
            /* Corporate candidate view */
            .candidate-header {
                padding: 1rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                background: linear-gradient(90deg, #ffffff 0%, #f8fafc 100%);
                border-radius: 8px;
            }

            .candidate-left-header {
                display: flex;
                align-items: center;
                gap: 0.9rem;
            }

            .candidate-avatar {
                width: 54px;
                height: 54px;
                border-radius: 50%;
                background: linear-gradient(135deg, #0ea5a4, #3b82f6);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                font-size: 1.05rem;
                box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
            }

            .candidate-info {
                display: flex;
                flex-direction: column;
            }

            .candidate-title {
                font-size: 1.125rem;
                font-weight: 800;
                color: #0f172a;
                letter-spacing: 0.2px;
            }

            .sei-badge {
                display: inline-block;
                background: linear-gradient(90deg, #06b6d4, #3b82f6);
                color: #fff;
                padding: 0.35rem 0.6rem;
                border-radius: 8px;
                font-weight: 800;
                margin-top: 0.45rem;
                letter-spacing: 0.2px;
                box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
            }

            .candidate-sub {
                color: #64748b;
                font-size: 0.9rem;
                margin-top: 2px;
            }

            .candidate-actions {
                text-align: right;
                min-width: 170px;
            }

            .candidate-body {
                display: flex;
                flex-direction: column; /* stack columns so each section is full width (12 cols) */
                gap: 1.5rem;
                padding: 1rem;
            }

            /* Ensure left/right sections occupy full width */
            .candidate-left,
            .candidate-right {
                flex: 1 1 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
            }

            /* When a different layout is needed, remove these overrides */
            .candidate-left {
                /* left now full width */
            }

            .candidate-right {
                /* right now full width */
            }

            .card-panel {
                background: #fff;
                border-radius: 8px;
                padding: 1rem;
                box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
            }

            /* Checklist container limit to avoid overflowing the card */
            #document-list {
                max-height: 300px; /* increased from 180px */
                overflow-y: auto;
                padding-right: 0.25rem;
            }

            #document-list .form-check {
                white-space: normal;
                display: flex;
                align-items: center;
            }

            #document-list .form-check-label {
                margin-left: 0.35rem;
                word-break: break-word;
            }

            /* Ensure checkboxes are clickable even if some global CSS sets pointer-events:none
                              or if .form-check-input is absolutely positioned by global bootstrap rules. */
            #document-list,
            #document-list * {
                pointer-events: auto !important;
            }

            #document-list .form-check {
                position: relative;
                padding-left: 0;
            }

            #document-list .form-check-input {
                position: relative;
                margin: 0 0.35rem 0 0;
                flex-shrink: 0;
            }

            /* ensure the card doesn't expand due to long content */
            .card-panel {
                box-sizing: border-box;
            }

            .label-cell {
                font-weight: 700;
                color: #334155;
                width: 40%;
            }

            .value-cell {
                color: #0f172a;
            }

            .section-title {
                font-weight: 700;
                margin-bottom: 0.5rem;
                color: #0f172a;
            }

            .badge-status {
                border-radius: 5px;
                padding: 0.35em 0.6em;
                font-weight: 700;
            }

            .btn-action {
                width: 100%;
                padding: 0.5rem;
                border-radius: 6px;
                font-weight: 600;
            }

            .compact-table td,
            .compact-table th {
                border-top: 0 !important;
                padding: 0.45rem 0.5rem !important;
            }

            .btn-more {
                border-radius: 6px;
                padding: 0.25rem 0.6rem;
                border: 1px solid #e2e8f0;
                background: #f8fafc;
                color: #0f172a;
                font-weight: 600;
            }

            .btn-more .icon-chevron {
                width: 14px;
                height: 14px;
                transition: transform 160ms ease;
                margin-right: 0.5rem;
            }

            .btn-more[aria-expanded="true"] .icon-chevron {
                transform: rotate(180deg);
            }

            /* Toast removed in favor of SweetAlert2 */
            /* SweetAlert2 custom styles for report modal */
            .swal2-custom-popup {
                max-width: 900px !important;
                width: 92% !important;
                padding: 1.25rem !important;
                border-radius: 12px !important;
                box-shadow: 0 10px 40px rgba(2,6,23,0.12) !important;
            }
            .swal2-custom-title {
                font-size: 1.05rem !important;
                font-weight: 700 !important;
                color: #0f172a !important;
                margin-bottom: 0.25rem !important;
            }
            .swal2-custom-text {
                color: #475569 !important;
                margin-bottom: 0.6rem !important;
            }
            .swal2-custom-textarea,
            .swal2-custom-textarea textarea,
            .swal2-textarea {
                width: 100% !important;
                box-sizing: border-box !important;
                min-height: 140px !important;
                max-height: 400px !important;
                resize: vertical !important;
                padding: 0.6rem !important;
                font-size: 0.95rem !important;
                border-radius: 8px !important;
                border: 1px solid #e6e9ef !important;
                box-shadow: none !important;
            }
            .swal2-footer { font-size: 0.85rem !important; color: #64748b !important; }
        </style>

        <div class="candidate-header">
            @php
                $displayName = $candidate['name'] ?? ($candidate['nome'] ?? '-');
                $inscricao = $candidate['num_inscricao'] ?? '-';
                $cpf = $candidate['cpf'] ?? '-';
                $parts = preg_split('/\s+/', trim($displayName));
                $initials = '';
                if (is_array($parts) && count($parts)) {
                    $initials = strtoupper(mb_substr($parts[0], 0, 1, 'UTF-8'));
                    if (isset($parts[1])) {
                        $initials .= strtoupper(mb_substr($parts[1], 0, 1, 'UTF-8'));
                    }
                }
            @endphp

            <div class="candidate-left-header">
                <div class="candidate-avatar">{{ $initials }}</div>
                <div class="candidate-info">
                    <div class="candidate-title">{{ $displayName }}</div>
                    <div class="candidate-sub">Inscrição: <strong>{{ $inscricao }}</strong> &nbsp;•&nbsp; CPF:
                        <strong>{{ $cpf }}</strong>
                    </div>
                    @if (!empty($candidate['sei_number']))
                        <div><span class="sei-badge">SEI: {{ $candidate['sei_number'] }}</span></div>
                    @endif
                </div>
            </div>

            <div class="candidate-actions">
                @php
                    $statusLabel = isset($candidate['status']) ? (string) $candidate['status'] : null;
                    $statusText = $statusLabel !== null ? (string) $statusLabel : '';
                    $statusLower = mb_strtolower($statusText, 'UTF-8');
                    $statusIndicatesValidated = $statusText !== '' && mb_stripos($statusText, 'valid', 0, 'UTF-8') !== false;
                    $isValidated = (isset($candidate['documentos_validados']) && ($candidate['documentos_validados'] == 1 || $candidate['documentos_validados'] === true)) || $statusIndicatesValidated;

                    // Prefer the explicit status stored in DB; otherwise fall back to computed labels
                    if ($statusLabel && trim($statusLabel) !== '') {
                        $displayStatus = $statusLabel;
                    } elseif ($isValidated) {
                        $displayStatus = 'Documentos Validados';
                    } else {
                        $displayStatus = 'Documentos Pendentes';
                    }

                    // determine badge class from actual status text or validation flag
                    if ($statusIndicatesValidated || $isValidated) {
                        $badgeClass = 'bg-success text-white';
                    } elseif ($statusLower !== '' && (mb_stripos($statusLower, 'pendente', 0, 'UTF-8') !== false || mb_stripos($statusLower, 'pendentes', 0, 'UTF-8') !== false)) {
                        $badgeClass = 'bg-warning text-dark';
                    } else {
                        $badgeClass = 'bg-secondary text-white';
                    }
                @endphp
                <span class="badge-status {{ $badgeClass }}">{{ $displayStatus }}</span>
            </div>
        </div>

        <div class="candidate-body">
            <div class="candidate-left">
                <div class="card-panel mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="section-title">Dados Principais</div>
                        @php
                            $isIngressadoForButtons = isset($candidate['status']) && mb_strtolower(trim($candidate['status']), 'UTF-8') === 'ingresso validado';
                        @endphp
                        <div>
                            <button id="btn-edit-dados" type="button" class="btn btn-sm btn-primary" style="border-radius:5px; display:inline-flex; align-items:center; justify-content:center; padding:5px !important;" {{ $isIngressadoForButtons ? 'disabled' : '' }}>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415" /><path d="M16 5l3 3" /></svg>
                            </button>
                            <button id="btn-save-dados" type="button" class="btn btn-sm btn-primary" style="display:none; border-radius:5px; padding:5px !important; align-items:center; justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M10 14a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                            </button>
                            <button id="btn-cancel-dados" type="button" class="btn btn-sm btn-link text-secondary" style="display:none;">Cancelar</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless compact-table">
                            <tbody>
                                @php
                                    $rendered = [];
                                    $map = $labelMap ?? [];
                                    $groupDefs = $groups ?? [];
                                @endphp

                                {{-- Mostrar apenas campos principais por padrão e deixar o resto em um colapsável "Ver mais" --}}
                                @php
                                    // Show main fields; allow controller to override via $primaryKeys
                                    $primaryKeys = $primaryKeys ?? [
                                        'num_inscricao',
                                        'name',
                                        'nome',
                                        'cpf',
                                        'data_nascimento',
                                        'nota',
                                        'classificacao_ampla',
                                        'classificacao_quota_pne',
                                        'classificacao_quota_racial',
                                        'classificacao_racial',
                                        'classificacao',
                                    ];
                                    $rendered = [];

                                    // Closure to render values and append ordinal 'º' for classification fields when numeric
                                    $renderField = function ($key) use ($candidate) {
                                        $val = $candidate[$key] ?? null;
                                        if ($val === null || $val === '') {
                                            return '-';
                                        }
                                        // if key contains 'classificacao' or is exactly 'classificacao' and value is numeric, append º
                                        if (preg_match('/classificacao/i', $key) && is_numeric($val)) {
                                            return $val . 'º';
                                        }
                                        // format common date keys to Brazilian format (d/m/Y)
                                        try {
                                            if (preg_match('/(^|_)data/i', $key) || preg_match('/_at$/i', $key) || preg_match('/date/i', $key)) {
                                                // try using Carbon if available
                                                if (class_exists('\Carbon\Carbon')) {
                                                    try {
                                                        $d = \Carbon\Carbon::parse($val);
                                                        $d->setTimezone('America/Sao_Paulo');
                                                        if ((int) $d->format('H') !== 0 || strpos((string)$val, ':') !== false) {
                                                            return $d->format('d/m/Y H:i');
                                                        }
                                                        return $d->format('d/m/Y');
                                                    } catch (\Throwable $e) {
                                                        // fallback to raw value
                                                        return $val;
                                                    }
                                                }
                                                // fallback: try strtotime
                                                $ts = strtotime($val);
                                                if ($ts !== false) {
                                                    return date('d/m/Y', $ts);
                                                }
                                            }
                                        } catch (\Throwable $e) {
                                            // ignore and return raw value
                                        }

                                        return $val;
                                    };
                                @endphp

                                {{-- Render primary fields --}}
                                @foreach ($primaryKeys as $k)
                                    @if (array_key_exists($k, $candidate))
                                        <tr data-key="{{ $k }}">
                                            <th class="label-cell">{{ $map[$k] ?? ucwords(str_replace(['_', 'id'], [' ', ''], $k)) }}</th>
                                            <td class="value-cell">{{ $renderField($k) }}</td>
                                        </tr>
                                        @php $rendered[] = $k; @endphp
                                    @endif
                                @endforeach

                                {{-- Button to toggle remaining fields --}}
                                <tr>
                                    <td colspan="2" style="padding-top:0.25rem;padding-bottom:0.75rem;">
                                        <button id="btnToggleMore" class="btn-more" type="button" data-toggle="collapse"
                                            data-target="#moreFields" aria-expanded="false" aria-controls="moreFields">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-chevron" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                            <span>Ver mais informações</span>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Collapsible with remaining fields --}}
                                <tr>
                                    <td colspan="2" style="border-top:0;padding:0;">
                                        <div id="moreFields" class="collapse">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    @php
                                                        // Allow the controller/view to specify exactly which columns
                                                        // should be shown and their display names.
                                                        $colsToShow = $visibleColumns ?? $columns ?? [];
                                                        $columnNames = $columnNames ?? [];
                                                    @endphp
                                                    @foreach ($colsToShow as $col)
                                                        @if (!in_array($col, $rendered))
                                                            <tr data-key="{{ $col }}">
                                                                <th class="label-cell">
                                                                    {{ $columnNames[$col] ?? $map[$col] ?? ucwords(str_replace(['_', 'id'], [' ', ''], $col)) }}
                                                                </th>
                                                                <td class="value-cell">{{ $renderField($col) }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="candidate-right">
                <div class="card-panel mb-3">
                    <div class="section-title">Ações Rápidas</div>
                    <p class="mb-2">Situação atual: <strong>{{ $displayStatus }}</strong></p>

                    <div id="document-checklist-root">
                        <div class="section-title">Checklist de Documentos</div>
                        <div id="document-list" class="mb-2">
                            {{-- Server-side fallback render: ensures checkboxes show even if JS fails --}}
                            @php
                                $serverDocs = $documentList ?? [];
                                $existingDocs = $existingDocuments ?? [];
                                $docsValidated = (isset($candidate['documentos_validados']) && ($candidate['documentos_validados'] == 1 || $candidate['documentos_validados'] === true)) || (isset($candidate['status']) && mb_stripos($candidate['status'], 'valid', 0, 'UTF-8') !== false);
                            @endphp
                            @if (is_array($serverDocs) && count($serverDocs))
                                @foreach ($serverDocs as $doc)
                                    @php
                                        $k = $doc['key'] ?? ($doc->key ?? null);
                                        $lbl = $doc['label'] ?? ($doc->label ?? $k);
                                        $isChecked =
                                            isset($existingDocs[$k]) &&
                                            (!empty($existingDocs[$k]['validated']) || $existingDocs[$k] === true);
                                        $sexoRaw = $candidate['sexo'] ?? $candidate['sex'] ?? '';
                                        $sexo = is_string($sexoRaw) ? mb_strtolower(trim($sexoRaw), 'UTF-8') : '';
                                        $isMale = $sexo !== '' && mb_substr($sexo, 0, 1, 'UTF-8') === 'm';
                                        // detect if candidate is registered in PIS/PASEP
                                        $hasPis = !empty($candidate['pis_pasep'] ?? $candidate['pis'] ?? null);

                                        // Skip reservista item for non-male candidates
                                        if ((mb_stripos($lbl, 'reservista', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'certificado_militar', 0, 'UTF-8') !== false) && !$isMale) {
                                            continue;
                                        }

                                        // Mark certain docs as required (examples: Diploma, Histórico, CPF, RG)
                                        $isRequired = false;
                                        if (
                                            mb_stripos($lbl, 'diploma', 0, 'UTF-8') !== false ||
                                            mb_stripos($lbl, 'hist', 0, 'UTF-8') !== false ||
                                            mb_stripos($lbl, 'cpf', 0, 'UTF-8') !== false ||
                                            mb_stripos($lbl, 'rg', 0, 'UTF-8') !== false ||
                                            mb_stripos($lbl, 'certid', 0, 'UTF-8') !== false ||
                                            mb_stripos((string)$k, 'diploma', 0, 'UTF-8') !== false ||
                                            mb_stripos((string)$k, 'historico', 0, 'UTF-8') !== false ||
                                            mb_stripos((string)$k, 'cpf', 0, 'UTF-8') !== false ||
                                            mb_stripos((string)$k, 'rg', 0, 'UTF-8') !== false ||
                                            mb_stripos((string)$k, 'certidao', 0, 'UTF-8') !== false ||
                                            // specifically require Banco do Brasil comprovante
                                            mb_stripos($lbl, 'banco do brasil', 0, 'UTF-8') !== false ||
                                            (mb_stripos($lbl, 'comprovante', 0, 'UTF-8') !== false && mb_stripos($lbl, 'banco', 0, 'UTF-8') !== false && mb_stripos($lbl, 'brasil', 0, 'UTF-8') !== false) ||
                                            (mb_stripos((string)$k, 'banco', 0, 'UTF-8') !== false && mb_stripos((string)$k, 'brasil', 0, 'UTF-8') !== false) ||
                                            // require Comprovante de Residência explicitly
                                            mb_stripos($lbl, 'comprovante de resid', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'comprovante_residencia', 0, 'UTF-8') !== false ||
                                            // require comprovante de votação dos dois últimos pleitos
                                            mb_stripos($lbl, 'vot', 0, 'UTF-8') !== false ||
                                            mb_stripos($lbl, 'pleit', 0, 'UTF-8') !== false ||
                                            mb_stripos((string)$k, 'vot', 0, 'UTF-8') !== false ||
                                            mb_stripos((string)$k, 'pleit', 0, 'UTF-8') !== false
                                            // require PIS/PASEP (original + copy) only if candidate is inscrito
                                            || ((mb_stripos($lbl, 'pis', 0, 'UTF-8') !== false || mb_stripos($lbl, 'pasep', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'pis', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'pasep', 0, 'UTF-8') !== false) && $hasPis)
                                            // require Carteira de Trabalho (original + copy)
                                            || mb_stripos($lbl, 'carteira', 0, 'UTF-8') !== false || mb_stripos($lbl, 'carteira de trabalho', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'carteira', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'ctps', 0, 'UTF-8') !== false
                                            // require Ficha de Cadastro (original)
                                            || mb_stripos($lbl, 'ficha de cadastro', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'ficha_cadastro', 0, 'UTF-8') !== false || mb_stripos($lbl, 'etnia', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'declaracao_etnia', 0, 'UTF-8') !== false || mb_stripos($lbl, 'benefic', 0, 'UTF-8') !== false || mb_stripos($lbl, 'inss', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'declaracao_beneficio', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'declaracao_beneficio_inss', 0, 'UTF-8') !== false || mb_stripos($lbl, 'bens', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'declaracao_bens', 0, 'UTF-8') !== false || mb_stripos($lbl, 'acumul', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'declaracao_acumulacao', 0, 'UTF-8') !== false || mb_stripos($lbl, 'aso', 0, 'UTF-8') !== false || mb_stripos($lbl, 'atestado', 0, 'UTF-8') !== false || mb_stripos($lbl, 'saude ocup', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'aso', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'atestado', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'atestado_saude', 0, 'UTF-8') !== false
                                        ) {
                                            $isRequired = true;
                                            // but exclude documents clearly about dependents (e.g., 'dependente', 'filho')
                                            if (mb_stripos($lbl, 'depend', 0, 'UTF-8') !== false || mb_stripos($lbl, 'filho', 0, 'UTF-8') !== false || mb_stripos((string)$k, 'depend', 0, 'UTF-8') !== false) {
                                                $isRequired = false;
                                            }
                                        }

                                        // Additional required document check: Certidão Negativa do Cadastro Nacional de Condenações Civeis
                                        if (! $isRequired) {
                                            if (
                                                mb_stripos($lbl, 'negativa', 0, 'UTF-8') !== false ||
                                                mb_stripos($lbl, 'condena', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'certidao_negativa', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'condenacoes', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'certidao_negativa_condenacoes', 0, 'UTF-8') !== false ||
                                                mb_stripos($lbl, 'eleitoral', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'justica_eleitoral', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'certidao_negativa_justica_eleitoral', 0, 'UTF-8') !== false ||
                                                mb_stripos($lbl, 'militar', 0, 'UTF-8') !== false ||
                                                mb_stripos($lbl, 'militar federal', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'justica_militar', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'justica_militar_federal', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'certidao_negativa_justica_militar_federal', 0, 'UTF-8') !== false
                                            ) {
                                                $isRequired = true;
                                            }
                                        }
                                            // Título de Eleitor / Quitação Eleitoral intentionally ignored for required-check


                                        // Additional required document check: Antecedentes Polícia Federal (estados últimos 8 anos)
                                        if (! $isRequired) {
                                            if (
                                                (mb_stripos($lbl, 'antecedentes', 0, 'UTF-8') !== false && (mb_stripos($lbl, 'polic', 0, 'UTF-8') !== false || mb_stripos($lbl, 'federal', 0, 'UTF-8') !== false)) ||
                                                mb_stripos((string)$k, 'antecedentes_pf', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'policia_federal', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'antecedentes_policia_federal', 0, 'UTF-8') !== false ||
                                                mb_stripos((string)$k, 'antecedentes_pf_estados_8_anos', 0, 'UTF-8') !== false
                                            ) {
                                                $isRequired = true;
                                            }
                                        }
                                    @endphp

                                    @php
                                        // Additional required document check: Certidões de Foros Criminais (Federal / Estadual)
                                        if (! $isRequired) {
                                            if (
                                                (
                                                    mb_stripos($lbl, 'foro', 0, 'UTF-8') !== false ||
                                                    mb_stripos($lbl, 'foros', 0, 'UTF-8') !== false ||
                                                    mb_stripos($lbl, 'foro criminal', 0, 'UTF-8') !== false ||
                                                    mb_stripos($lbl, 'foros criminais', 0, 'UTF-8') !== false ||
                                                    mb_stripos($lbl, 'distribu', 0, 'UTF-8') !== false ||
                                                    mb_stripos($lbl, 'criminal', 0, 'UTF-8') !== false
                                                ) && (
                                                    mb_stripos($lbl, 'federal', 0, 'UTF-8') !== false ||
                                                    mb_stripos($lbl, 'estadual', 0, 'UTF-8') !== false ||
                                                    mb_stripos((string)$k, 'foros_federal', 0, 'UTF-8') !== false ||
                                                    mb_stripos((string)$k, 'foros_estadual', 0, 'UTF-8') !== false ||
                                                    mb_stripos((string)$k, 'certidao_negativa_foros', 0, 'UTF-8') !== false
                                                )
                                            ) {
                                                $isRequired = true;
                                            }
                                        }
                                    @endphp

                                    <div class="form-check mb-1 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="doc_{{ $k }}" data-key="{{ $k }}" data-required="{{ $isRequired ? '1' : '0' }}"
                                                {{ $isChecked ? 'checked' : '' }} {{ $docsValidated ? 'disabled' : '' }}>
                                            <label class="form-check-label ms-2" for="doc_{{ $k }}">{{ $lbl }}@if($isRequired) <span class="text-danger">*</span>@endif
                                                @php $rep = $existingDocs[$k] ?? null; @endphp
                                                @if(is_array($rep) && !empty($rep['report']))
                                                    @php $rstatus = intval($rep['report']); @endphp
                                                    @if($rstatus === 2)
                                                        <span class="text-success issue-badge ms-2 small" title="{{ $rep['report_description'] ?? 'Problema resolvido pelo NTE' }}">Problema resolvido pelo NTE</span>
                                                    @else
                                                        <span class="text-danger issue-badge ms-2 small" title="{{ $rep['report_description'] ?? 'Problema reportado' }}">Problema reportado</span>
                                                    @endif
                                                @endif
                                            </label>
                                        </div>
                                        @php
                                            $isCpmLocal = (optional(Auth::user())->sector_id == 2 && optional(Auth::user())->profile_id == 1);
                                            $isNteLocal = (optional(Auth::user())->sector_id == 7 && optional(Auth::user())->profile_id == 1);
                                            $rep = $existingDocs[$k] ?? null;
                                            $candidateDocsValidatedLocal = (isset($candidate['documentos_validados']) && ($candidate['documentos_validados'] == 1 || $candidate['documentos_validados'] === true)) || (isset($candidate['status']) && mb_stripos($candidate['status'], 'valid', 0, 'UTF-8') !== false);
                                        @endphp
                                        @if ($isCpmLocal && ! $candidateDocsValidatedLocal)
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-report-issue" style="border-radius:5px;" data-key="{{ $k }}" data-label="{{ e($lbl) }}" title="Reportar problema">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                            </button>
                                        @endif
                                        @if ($isNteLocal && is_array($rep) && isset($rep['report']) && intval($rep['report']) === 1)
                                            <button type="button" class="btn btn-sm btn-outline-info btn-view-report" style="border-radius:5px;" data-key="{{ $k }}" data-label="{{ e($lbl) }}" data-desc="{{ e($rep['report_description'] ?? '') }}" title="Ver descrição do report">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        @php
                            $isNte = (optional(Auth::user())->sector_id == 7 && optional(Auth::user())->profile_id == 1);
                            $isCpm = (optional(Auth::user())->sector_id == 2 && optional(Auth::user())->profile_id == 1);
                            // detect if any existing document has a report
                            $hasReports = false;
                            if (is_array($existingDocs) && count($existingDocs)) {
                                foreach ($existingDocs as $_ed) {
                                    if (is_array($_ed) && !empty($_ed['report'])) { $hasReports = true; break; }
                                }
                            }
                        @endphp
                        @if ($isNte)
                            <div class="d-grid mb-2">
                                <button id="nte-none-selected" class="btn btn-secondary btn-action" disabled>Nenhum Documento Selecionado</button>
                            </div>
                        @endif
                        @if ($isCpm)
                            @php
                                $docsValidated = (isset($candidate['documentos_validados']) && ($candidate['documentos_validados'] == 1 || $candidate['documentos_validados'] === true)) || (isset($candidate['status']) && mb_stripos($candidate['status'], 'valid', 0, 'UTF-8') !== false);
                                $isIngressadoForButtons = isset($candidate['status']) && mb_strtolower(trim($candidate['status']), 'UTF-8') === 'ingresso validado';
                            @endphp
                            <div class="d-flex gap-2 mb-2">
                                @if ($docsValidated)
                                    <button type="button" id="btn-validar-documentos-cpm" data-validated="1" class="btn btn-danger btn-action me-2 mr-2" {{ $isIngressadoForButtons ? 'disabled' : '' }}>Retirar Validação dos Documentos</button>
                                    <button type="button" id="btn-return-to-nte" class="btn btn-outline-warning btn-action" style="border-radius:5px;" {{ ($hasReports && ! $isIngressadoForButtons) ? '' : 'disabled' }}>Retornar para o NTE</button>
                                @else
                                    <button type="button" id="btn-validar-documentos-cpm" class="btn btn-success btn-action me-2 mr-2" {{ $isIngressadoForButtons ? 'disabled' : '' }}>Validar documentação</button>
                                    <button type="button" id="btn-return-to-nte" class="btn btn-outline-warning btn-action" style="border-radius:5px;" {{ ($hasReports && ! $isIngressadoForButtons) ? '' : 'disabled' }}>Retornar para o NTE</button>
                                @endif
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                        try {
                                        const cpmBtn = document.getElementById('btn-validar-documentos-cpm');
                                        if (!cpmBtn) return;
                                        const requiredChecks = Array.from(document.querySelectorAll('#document-list .form-check-input[data-required="1"]'));

                                        function updateCpmBtn() {
                                            const anyMissing = requiredChecks.length ? requiredChecks.some(chk => !chk.checked) : false;
                                            if (cpmBtn.dataset.validated === '1') {
                                                cpmBtn.disabled = false;
                                            } else {
                                                cpmBtn.disabled = anyMissing;
                                            }
                                            try { console.log('CPM update:', { requiredCount: requiredChecks.length, anyMissing: anyMissing, validatedFlag: cpmBtn.dataset.validated, disabled: cpmBtn.disabled }); } catch(e) {}
                                        }

                                        requiredChecks.forEach(chk => chk.addEventListener('change', updateCpmBtn));
                                        // initial state
                                        updateCpmBtn();
                                    } catch (e) {
                                        console.error('Validation guard error', e);
                                    }
                                    // Return to NTE handler (CPM)
                                    try {
                                        const btnReturn = document.getElementById('btn-return-to-nte');
                                        const candidateId = '{{ $candidate['id'] ?? ($candidate['num_inscricao'] ?? '') }}';
                                        const csrf = (document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}');
                                        if (btnReturn) {
                                            btnReturn.addEventListener('click', async function(){
                                                if (btnReturn.disabled) return;
                                                const confirmed = await Swal.fire({
                                                    title: 'Retornar documentação ao NTE?',
                                                    text: 'Enviar este ingresso ao NTE para correção dos documentos reportados?',
                                                    icon: 'question',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Sim, retornar',
                                                    cancelButtonText: 'Cancelar'
                                                });
                                                if (!confirmed.isConfirmed) return;
                                                try {
                                                    const res = await fetch(`/ingresso/${candidateId}/documentos`, {
                                                        method: 'POST',
                                                        credentials: 'same-origin',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': csrf,
                                                            'X-Requested-With': 'XMLHttpRequest',
                                                            'Accept': 'application/json'
                                                        },
                                                        body: JSON.stringify({ return_to_nte: true })
                                                    });
                                                    let j = null;
                                                    try { j = await res.json(); } catch(e) { console.warn('Non-JSON response', e); }
                                                    console.log('return_to_nte response', res.status, j);
                                                    if (res.ok && j && j.success) {
                                                        await Swal.fire({ icon: 'success', title: 'Enviado', text: j.message || 'Ingressos retornado para correção pelo NTE.' });
                                                        try { location.reload(); } catch(e) {}
                                                    } else {
                                                        const msg = (j && j.message) || ('HTTP ' + res.status + ' - ' + (j && JSON.stringify(j) || res.statusText));
                                                        console.error('Failed return_to_nte', msg);
                                                        Swal.fire({ icon: 'error', title: 'Erro', text: msg });
                                                    }
                                                } catch(e) { console.error('fetch error', e); Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação' }); }
                                            });
                                        }
                                    } catch(e) { console.error('return-to-nte init failed', e); }
                                });
                            </script>
                        @endif

                        {{-- debug button removed --}}

                        <hr />

                        <div class="section-title">Registrar Processo SEI</div>
                        @php
                            $isIngressadoForButtons = isset($candidate['status']) && mb_strtolower(trim($candidate['status']), 'UTF-8') === 'ingresso validado';
                        @endphp
                        @if (Route::has('ingresso.assign'))
                            <form id="sei-form" method="POST"
                                action="{{ route('ingresso.assign', $candidate['id'] ?? $candidate['num_inscricao']) }}">
                                @csrf
                                <div class="form-group mb-2">
                                    <input type="text" name="sei_number" class="form-control form-control-sm"
                                        placeholder="Número do processo SEI (ex: 00000.000000/0000-00)"
                                        value="{{ $candidate['sei_number'] ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary btn-action" {{ $isIngressadoForButtons ? 'disabled' : '' }}>Registrar Processo SEI</button>
                            </form>
                        @else
                            <input class="form-control form-control-sm mb-2" disabled
                                placeholder="Rota de registro indisponível">
                            <button class="btn btn-primary btn-action" disabled>Registrar Processo SEI</button>
                        @endif

                        
                        {{-- Encaminhamentos Registrados removido --}}
                        
                        {{-- Ações CPM: Validar ingresso e Imprimir Ofício --}}
                        <div class="mt-3">
                            @php
                                $isCpm = (optional(Auth::user())->sector_id == 2 && optional(Auth::user())->profile_id == 1);
                                $docsValid = (isset($candidate['documentos_validados']) && ($candidate['documentos_validados'] == 1 || $candidate['documentos_validados'] === true)) || (isset($candidate['status']) && mb_stripos($candidate['status'], 'valid', 0, 'UTF-8') !== false);
                                $hasSei = !empty($candidate['sei_number']);
                                $hasEnc = isset($encaminhamentos) && count($encaminhamentos) > 0;
                                // show print button only when DB status equals exactly 'Ingresso Validado'
                                $showPrintFromStatus = isset($candidate['status']) && mb_strtolower(trim($candidate['status']), 'UTF-8') === 'ingresso validado';
                            @endphp
                            @if ($isCpm)
                                {{-- Show validate button only when documents are validated and there are encaminhamentos --}}
                                @if ($docsValid && $hasEnc)
                                        @php $isIngressado = isset($candidate['status']) && mb_strtolower(trim($candidate['status']), 'UTF-8') === 'ingresso validado'; @endphp
                                        @if ($isIngressado)
                                            <button id="btn-retirar-validacao-ingresso" class="btn btn-danger btn-sm" style="border-radius:5px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-cancel" style="margin-right:6px;vertical-align:middle;width:16px;height:16px;"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" /><path d="M16 19a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M17 21l4 -4" /></svg>
                                                <span>Retirar Validação do Ingresso</span>
                                            </button>
                                        @else
                                            <button id="btn-validar-ingresso" class="btn btn-success btn-sm" style="border-radius:5px;">Validar Ingresso</button>
                                        @endif
                                    @endif
                                {{-- CPM document validation button is rendered below the document checklist; removed duplicate here --}}
                                    {{-- botão Imprimir Ofício removido --}}
                            @elseif ($showPrintFromStatus && $hasSei)
                                {{-- botão Imprimir Ofício removido --}}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Resumo Rápido removido temporariamente --}}
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // global flags used across multiple script blocks
        var candidateValidated = {!! json_encode(
            (
                (isset($candidate['documentos_validados']) && ($candidate['documentos_validados'] == 1 || $candidate['documentos_validados'] === true))
                || (isset($candidate['status']) && is_string($candidate['status']) && (mb_stripos($candidate['status'], 'valid') !== false))
            )
        ) !!};
        var isCpmUser = {!! json_encode(optional(Auth::user())->sector_id == 2 && optional(Auth::user())->profile_id == 1) !!};
    </script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const candidateId = '{{ $candidate['id'] ?? ($candidate['num_inscricao'] ?? '') }}';
            const getUrl = '{{ url('/ingresso') }}' + '/' + encodeURIComponent(candidateId) + '/documentos';
            const postUrl = '{{ url('/ingresso') }}' + '/' + encodeURIComponent(candidateId) + '/documentos';
            const csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector(
                'meta[name="csrf-token"]').getAttribute('content') : '';
            // initial validated state from server (true if candidate already has documents validated)
            let candidateValidated = {!! json_encode(
                (
                    (isset($candidate['documentos_validados']) && ($candidate['documentos_validados'] == 1 || $candidate['documentos_validados'] === true))
                    || (isset($candidate['status']) && is_string($candidate['status']) && (mb_stripos($candidate['status'], 'valid') !== false))
                )
            ) !!};
            // current user role info (used to decide CPM/NTE behavior)
            const currentUserSector = @json(optional(Auth::user())->sector_id);
            const currentUserProfile = @json(optional(Auth::user())->profile_id);
            // boolean flags computed server-side to avoid JS type/coercion issues
            const isCpmUser = {!! json_encode(optional(Auth::user())->sector_id == 2 && optional(Auth::user())->profile_id == 1) !!};
            const isNteUser = {!! json_encode(optional(Auth::user())->sector_id == 7 && optional(Auth::user())->profile_id == 1) !!};
            // current candidate status string from server
            let candidateStatus = @json($candidate['status'] ?? null);
            // server-authoritative status and lock to prevent client overwrites unless a server response forces update
            let serverStatus = candidateStatus ? String(candidateStatus).trim() : null;
            let serverStatusLock = serverStatus && serverStatus !== '';
            // explicit documentos_validados flag from server (used to decide strict locking)
            const initialDocsValid = {!! json_encode(isset($candidate['documentos_validados']) && ($candidate['documentos_validados'] == 1 || $candidate['documentos_validados'] === true)) !!};

            // Normalize candidateValidated: only true when DB flag is set or status clearly indicates final validation
            try {
                const s = serverStatus ? String(serverStatus).toLowerCase() : '';
                // if status contains 'valid' but also contains 'aguard', treat as not-yet-finalized
                if (s.indexOf('valid') !== -1 && s.indexOf('aguard') === -1) {
                    candidateValidated = true;
                } else {
                    candidateValidated = !!initialDocsValid;
                }
            } catch (e) {}

            // show a centered SweetAlert2 modal with current candidate status on load
            try {
                if (typeof Swal !== 'undefined') {
                    const s = candidateStatus ? String(candidateStatus).trim() : 'Sem status';
                    const low = s.toLowerCase();
                    let icon = 'info';
                    if (low.indexOf('valid') !== -1) icon = 'success';
                    else if (low.indexOf('pendente') !== -1 || low.indexOf('aguard') !== -1) icon = 'warning';
                    Swal.fire({
                        title: 'Status do Candidato',
                        text: s,
                        icon: icon,
                        confirmButtonText: 'OK'
                    });
                }
            } catch (e) {}

            // If this user is NTE and the candidate is validated by CPM, lock the UI (disable actions)
            // NOTE: only disable controls inside the candidate panel to avoid blocking navbar/user menu
            (function lockUiForNteIfValidated(){
                try {
                    if (!isNteUser) return;
                    const srv = serverStatus ? String(serverStatus).toLowerCase().trim() : '';
                    const shouldLock = (srv === 'ingresso validado');
                    if (!shouldLock) return;
                    // scope to candidate area (prefer .candidate-body, fallback to .card)
                    const container = document.querySelector('.candidate-body') || document.querySelector('.card');
                    const except = [];
                    if (container) {
                        container.querySelectorAll('button').forEach(b => {
                            // keep collapse toggles and 'Ver mais informações' interactive
                            const isCollapseToggle = b.getAttribute('data-toggle') === 'collapse' || b.getAttribute('data-bs-toggle') === 'collapse' || b.classList.contains('btn-more') || b.getAttribute('aria-controls') === 'moreFields' || (b.getAttribute('data-target') || '').indexOf('#moreFields') !== -1;
                            if (except.indexOf(b.id) === -1 && !isCollapseToggle) b.disabled = true;
                        });
                        container.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                        // Hide the encaminhar trigger inside container
                        const encBtn = container.querySelector('#btnEncaminhar'); if (encBtn) encBtn.style.display = 'none';
                        // Hide any add/remove discipline controls if present inside container
                        container.querySelectorAll('#add-discipline, .remove-discipline').forEach(el => el.style.display = 'none');
                        // Disable SEI form submission button (if present) inside container
                        const seiFormBtn = container.querySelector('#sei-form button[type=submit]'); if (seiFormBtn) seiFormBtn.disabled = true;
                    } else {
                        // conservative fallback: only disable known candidate-local elements, but keep collapse toggles
                        document.querySelectorAll('#document-list button, .card-panel form button, .card-panel input, .card-panel select, .card-panel textarea').forEach(el => {
                            const b = el;
                            if (b && b.tagName === 'BUTTON') {
                                const isCollapseToggle = b.getAttribute('data-toggle') === 'collapse' || b.getAttribute('data-bs-toggle') === 'collapse' || b.classList.contains('btn-more') || b.getAttribute('aria-controls') === 'moreFields' || (b.getAttribute('data-target') || '').indexOf('#moreFields') !== -1;
                                if (!isCollapseToggle) b.disabled = true;
                            } else {
                                el.disabled = true;
                            }
                        });
                        const encBtn = document.getElementById('btnEncaminhar'); if (encBtn) encBtn.style.display = 'none';
                        const seiFormBtn = document.querySelector('#sei-form button[type=submit]'); if (seiFormBtn) seiFormBtn.disabled = true;
                    }
                    // print button removed; no-op
                } catch(e){}
            })();

            // Document checklist rendering and immediate-action helpers removed.
            // Starting from a clean slate: renderList / updateBadgeState handled server-side or
            // will be re-implemented. This avoids accidental auto-saves and inconsistent client logic.

            function setBadgeStatus(status, force = false) {
                // respect server lock unless forced by a server response
                if (serverStatusLock && !force) return;
                const badge = document.querySelector('.badge-status');
                if (!badge) return;
                const txt = String(status || '').trim();
                const low = txt.toLowerCase();
                if (low.indexOf('valid') !== -1 || txt === 'Documentos Validados') {
                    badge.className = 'badge-status bg-success text-white';
                    badge.textContent = txt || 'Documentos Validados';
                } else if (low.indexOf('pendente') !== -1) {
                    badge.className = 'badge-status bg-warning text-dark';
                    badge.textContent = txt;
                } else if (txt !== '') {
                    badge.className = 'badge-status bg-secondary text-white';
                    badge.textContent = txt;
                }
                // keep local candidateStatus in sync
                if (txt !== '') candidateStatus = txt;
            }

            // Document checklist action handlers removed — starting over from scratch.
            // Server will render checklist markup and initial state; client-side handlers
            // for rendering, per-checkbox saves and bulk save are intentionally removed.

            // Intercept SEI form submit and use SweetAlert2 for feedback
            const seiForm = document.getElementById('sei-form');
            if (seiForm) {
                seiForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    }).then(r => r.json()).then(function(json) {
                        if (json && json.success) {
                            const seiValue = formData.get('sei_number') || (json.sei_number || '');
                            Swal.fire({
                                icon: 'success',
                                title: 'Processo SEI registrado',
                                text: json.message || 'Número SEI registrado com sucesso.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                let seiBadge = document.querySelector('.sei-badge');
                                if (seiBadge) {
                                    seiBadge.textContent = 'SEI: ' + seiValue;
                                } else {
                                    const leftInfo = document.querySelector('.candidate-info');
                                    if (leftInfo) {
                                        const div = document.createElement('div');
                                        div.innerHTML = '<span class="sei-badge">SEI: ' + seiValue + '</span>';
                                        leftInfo.appendChild(div);
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                text: (json && json.message) || 'Erro ao registrar o processo SEI.'
                            });
                        }
                    }).catch(function() {
                        Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro ao comunicar com o servidor.' });
                    });
                });
            }

            

            // Use SweetAlert2 for notifications. Ensure the library is loaded (CDN included below).
            function showToast(message, type = 'info', duration = 4200) {
                if (typeof Swal !== 'undefined') {
                    // show a centered, non-blocking modal using SweetAlert2
                    Swal.fire({
                        toast: false,
                        position: 'center',
                        icon: type === 'error' ? 'error' : (type === 'success' ? 'success' : 'info'),
                        title: message,
                        showConfirmButton: false,
                        timer: duration,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'swal2-popup-modern'
                        }
                    });
                    return;
                }
                // always use SweetAlert2 for notifications
                Swal.fire({ icon: type === 'error' ? 'error' : (type === 'success' ? 'success' : 'info'), title: message });
            }

            // Document checklist bulk-save handler removed — implement fresh logic as needed.
            // debug handler removed
            // Edit Dados Principais -----------------------------------------------------
            (function(){
                const btnEdit = document.getElementById('btn-edit-dados');
                const btnSave = document.getElementById('btn-save-dados');
                const btnCancel = document.getElementById('btn-cancel-dados');
                const candidateId = '{{ $candidate['id'] ?? ($candidate['num_inscricao'] ?? '') }}';
                if (!btnEdit || !btnSave || !btnCancel) return;

                function enterEditMode(){
                    btnEdit.style.display = 'none';
                    btnSave.style.display = 'inline-flex';
                    btnCancel.style.display = 'inline-block';
                    const nonEditable = ['num_inscricao','name','nome','cpf','data_nascimento','nota','classificacao_ampla','classificacao_quota_pne','classificacao_quota_racial','classificacao'];
                    document.querySelectorAll('tr[data-key]').forEach(function(row){
                        const key = row.dataset.key;
                        // do not make certain fields editable
                        if (nonEditable.indexOf(key) !== -1) return;
                        const td = row.querySelector('.value-cell');
                        if (!td) return;
                        const txt = td.textContent.trim();
                        // create input (use date type for data_nascimento)
                        let input;
                        if (key === 'data_nascimento') {
                            input = document.createElement('input');
                            input.type = 'date';
                            // try to parse dd/mm/yyyy to yyyy-mm-dd
                            const m = txt.match(/(\d{2})\/(\d{2})\/(\d{4})/);
                            input.value = m ? (m[3] + '-' + m[2] + '-' + m[1]) : (txt === '-' ? '' : txt);
                        } else if (key === 'nota' || key.toLowerCase().includes('nota') ) {
                            input = document.createElement('input'); input.type = 'number'; input.step='any'; input.value = (txt === '-' ? '' : txt);
                        } else {
                            input = document.createElement('input'); input.type = 'text'; input.value = (txt === '-' ? '' : txt);
                        }
                        input.className = 'form-control form-control-sm';
                        td.dataset.original = txt;
                        td.innerHTML = '';
                        td.appendChild(input);
                    });
                }

                function exitEditMode(revert){
                    btnEdit.style.display = '';
                    btnSave.style.display = 'none';
                    btnCancel.style.display = 'none';
                    document.querySelectorAll('tr[data-key]').forEach(function(row){
                        const td = row.querySelector('.value-cell');
                        if (!td) return;
                        const inp = td.querySelector('input');
                        if (inp && !revert) {
                            // format date back to display if needed
                            const key = row.dataset.key;
                            if (key === 'data_nascimento' && inp.value) {
                                const d = inp.value.split('-');
                                td.textContent = d.length===3 ? (d[2] + '/' + d[1] + '/' + d[0]) : inp.value;
                            } else {
                                td.textContent = inp.value || '-';
                            }
                        } else if (td.dataset.original !== undefined) {
                            td.textContent = td.dataset.original;
                            delete td.dataset.original;
                        }
                    });
                }

                btnEdit.addEventListener('click', function(){ enterEditMode(); });
                btnCancel.addEventListener('click', function(){ exitEditMode(true); });

                btnSave.addEventListener('click', async function(){
                    // collect values
                    const payload = {};
                    const nonEditablePayload = ['num_inscricao','name','nome','cpf','data_nascimento','nota','classificacao_ampla','classificacao_quota_pne','classificacao_quota_racial','classificacao'];
                    document.querySelectorAll('tr[data-key]').forEach(function(row){
                        const key = row.dataset.key;
                        // skip non-editable fields when building payload
                        if (nonEditablePayload.indexOf(key) !== -1) return;
                        const td = row.querySelector('.value-cell');
                        if (!td) return;
                        const inp = td.querySelector('input');
                        if (!inp) return;
                        let val = inp.value;
                        // normalize date from yyyy-mm-dd to yyyy-mm-dd (DB may accept)
                        if (key === 'data_nascimento' && val === '') val = null;
                        payload[key] = val;
                    });

                    try {
                        const res = await fetch(`/ingresso/${candidateId}/update`, {
                            method: 'PUT',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });
                        const j = await res.json();
                        if (j && j.success) {
                            // update UI from returned candidate if present
                            if (j.candidate) {
                                const cand = j.candidate;
                                document.querySelectorAll('tr[data-key]').forEach(function(row){
                                    const key = row.dataset.key;
                                    const td = row.querySelector('.value-cell');
                                    if (!td) return;
                                    let val = cand[key] ?? cand[Object.keys(cand).find(k=>k===key)] ?? null;
                                    if (val === null || val === '') {
                                        td.textContent = '-';
                                    } else {
                                        const isDateKey = /(^|_)data/i.test(key) || /_at$/i.test(key) || /date/i.test(key);
                                        if (isDateKey) {
                                            try {
                                                const d = new Date(val);
                                                if (!isNaN(d)) {
                                                    const dd = ('0'+d.getDate()).slice(-2);
                                                    const mm = ('0'+(d.getMonth()+1)).slice(-2);
                                                    const yyyy = d.getFullYear();
                                                    td.textContent = dd + '/' + mm + '/' + yyyy;
                                                } else {
                                                    td.textContent = val;
                                                }
                                            } catch(e) { td.textContent = val; }
                                        } else {
                                            td.textContent = val;
                                        }
                                    }
                                });
                            } else {
                                exitEditMode(false);
                            }
                            try { await Swal.fire({ icon: 'success', title: 'Sucesso', text: j.message || 'Dados atualizados', confirmButtonText: 'OK' }); } catch(e){}
                            // reload so server-side state is authoritative
                            try { location.reload(); } catch(e) {}
                        } else {
                            try { Swal.fire({ icon: 'error', title: 'Erro', text: (j && j.message) || 'Falha ao salvar', confirmButtonText: 'OK' }); } catch(e){}
                        }
                    } catch(err) {
                        try { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação', confirmButtonText: 'OK' }); } catch(e){}
                    }
                });
            })();

            // Helper: swap the validate button for a danger-styled 'Retirar Validação do Ingresso' button.
            // This only updates the UI and does NOT implement the unvalidate action.
            window.replaceValidateWithRetirar = function() {
                try {
                    const old = document.getElementById('btn-validar-ingresso');
                    if (!old) return;
                    const parent = old.parentNode;
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.id = 'btn-retirar-validacao-ingresso';
                    btn.className = 'btn btn-danger btn-sm';
                    btn.style.borderRadius = '5px';
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-cancel" style="margin-right:6px;vertical-align:middle;width:16px;height:16px;"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" /><path d="M16 19a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M17 21l4 -4" /></svg><span>Retirar Validação do Ingresso</span>';
                    // replace element (removes existing event listeners)
                    parent.replaceChild(btn, old);
                } catch (e) { /* ignore */ }
            };

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const btnVal = document.getElementById('btn-validar-ingresso');
            const candidateId = '{{ $candidate['id'] ?? ($candidate['num_inscricao'] ?? '') }}';
            const btnConfirmDocsCpm = document.getElementById('btn-validar-documentos-cpm');
            const serverCandidateStatus = @json($candidate['status'] ?? null);
            if (btnVal) {
                btnVal.addEventListener('click', async function(){
                    const confirmed = await Swal.fire({
                        title: 'Confirma validação final do ingresso?',
                        text: 'Esta ação será registrada.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, validar',
                        cancelButtonText: 'Cancelar'
                    });
                    if (!confirmed.isConfirmed) return;
                    try {
                        const res = await fetch(`/ingresso/${candidateId}/validar`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'X-Requested-With':'XMLHttpRequest' }});
                        const j = await res.json();
                        if (j.success) {
                                await Swal.fire({ icon: 'success', title: 'Sucesso', text: j.message || 'Ingresso validado' });
                                // if controller returned the updated candidate, update UI state immediately
                                if (j.candidate) {
                                    try {
                                                const cand = j.candidate;
                                                // prefer explicit server status; update local state so other code doesn't overwrite
                                                const statusText = cand.status ? String(cand.status).trim() : '';
                                                candidateValidated = (cand.documentos_validados == 1 || cand.documentos_validados === true);
                                                candidateStatus = statusText || (candidateValidated ? 'Documentos Validados' : candidateStatus);
                                                // update badge using the server-provided status (avoid fallback that overwrites final status)
                                                try {
                                                    if (statusText && statusText !== '') {
                                                        serverStatus = statusText; serverStatusLock = true;
                                                        setBadgeStatus(statusText, true);
                                                    } else if (candidateValidated) {
                                                        serverStatus = 'Documentos Validados'; serverStatusLock = true;
                                                        setBadgeStatus('Documentos Validados', true);
                                                    }
                                                } catch(e) {}
                                                // if SEI present on returned candidate, ensure badge shows SEI
                                                if (cand.sei_number) {
                                                    const seiBadge = document.querySelector('.sei-badge');
                                                    if (seiBadge) seiBadge.textContent = 'SEI: ' + cand.sei_number;
                                                }
                                    } catch (e) { /* ignore UI update errors */ }
                                }
                                // swap validate button UI for a danger 'Retirar Validação' button (no action implemented)
                                try { if (typeof window.replaceValidateWithRetirar === 'function') window.replaceValidateWithRetirar(); } catch(e){}
                                if (btnPrint) btnPrint.style.display = 'inline-block';
                                // reload page so server-side state is authoritative after CPM validation
                                try { location.reload(); } catch(e) {}
                            } else {
                                Swal.fire({ icon: 'error', title: 'Erro', text: j.message || 'Erro ao validar' });
                            }
                    } catch (e) { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação' }); }
                });
            }
            // Delegate handler for 'Retirar Validação do Ingresso' (may be dynamically swapped)
            document.addEventListener('click', async function(ev){
                const btn = ev.target.closest('#btn-retirar-validacao-ingresso');
                if (!btn) return;
                ev.preventDefault();
                const confirmed = await Swal.fire({
                    title: 'Retirar validação do ingresso?',
                    text: 'Isto reverterá a validação final do ingresso, retornando o status para "Documentos Validados".',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, retirar',
                    cancelButtonText: 'Cancelar'
                });
                if (!confirmed.isConfirmed) return;
                try {
                    const res = await fetch(`/ingresso/${candidateId}/retirar-validacao`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    const j = await res.json();
                    if (j && j.success) {
                        await Swal.fire({ icon: 'success', title: 'Sucesso', text: j.message || 'Validação do ingresso removida.' });
                        try { location.reload(); } catch(e) {}
                    } else {
                        Swal.fire({ icon: 'error', title: 'Erro', text: j.message || 'Falha ao remover validação' });
                    }
                } catch(e) { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação' }); }
            });
            if (btnConfirmDocsCpm) {
                btnConfirmDocsCpm.addEventListener('click', async function(){
                    // if server status indicates validated, perform unvalidate action
                    const s = serverCandidateStatus ? String(serverCandidateStatus).toLowerCase() : '';
                    if (s.indexOf('valid') !== -1 || s.indexOf('documentos validados') !== -1) {
                        const confirmed = await Swal.fire({
                            title: 'Retirar validação?',
                            text: 'Confirma que deseja retirar a validação dos documentos para este ingresso?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sim, retirar',
                            cancelButtonText: 'Cancelar'
                        });
                        if (!confirmed.isConfirmed) return;
                        try {
                            const res = await fetch(`/ingresso/${candidateId}/documentos`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ unvalidate: true })
                            });
                            const j = await res.json();
                            if (j && j.success) {
                                await Swal.fire({ icon: 'success', title: 'Sucesso', text: j.message || 'Validação removida.' });
                                try { location.reload(); } catch(e) {}
                            } else {
                                Swal.fire({ icon: 'error', title: 'Erro', text: j.message || 'Falha ao remover validação' });
                            }
                        } catch(e) { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação' }); }
                        return;
                    }
                    // otherwise proceed with CPM validation flow
                    const confirmed = await Swal.fire({
                        title: 'Validar documentação?',
                        text: 'Confirma que os documentos devem ser validados pela CPM?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, validar',
                        cancelButtonText: 'Cancelar'
                    });
                    if (!confirmed.isConfirmed) return;
                    try {
                        const res = await fetch(`/ingresso/${candidateId}/documentos/confirmar_cpm`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        const j = await res.json();
                        if (j && j.success) {
                            await Swal.fire({ icon: 'success', title: 'Sucesso', text: j.message || 'Documentos validados.' });
                            try { location.reload(); } catch(e) { }
                        } else {
                            Swal.fire({ icon: 'error', title: 'Erro', text: j.message || 'Falha ao validar' });
                        }
                    } catch(e) { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação' }); }
                });
            }
            if (btnPrint) {
                btnPrint.addEventListener('click', function(){
                    window.open(`/ingresso/${candidateId}/oficio?print=1`, '_blank');
                });
            }
            // Encaminhamento deletion (CPM only)
            (function(){
                const docListEl = document.getElementById('document-list');
                const table = (docListEl && typeof docListEl.closest === 'function') ? docListEl.closest('div').previousElementSibling : null;
                // use event delegation within the encaminhamentos table area
                const encTable = document.querySelector('table.table-sm.table-striped');
                if (!encTable) return;
                encTable.addEventListener('click', async function(ev){
                    const btn = ev.target.closest('.btn-delete-enc');
                    if (!btn) return;
                    const id = btn.dataset.id;
                    if (!id) return;
                    const confirmed = await Swal.fire({
                        title: 'Confirma exclusão do encaminhamento?',
                        text: 'Esta ação não pode ser desfeita.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, excluir',
                        cancelButtonText: 'Cancelar'
                    });
                    if (!confirmed.isConfirmed) return;
                    try {
                        const res = await fetch(`/ingresso/${candidateId}/encaminhar/${encodeURIComponent(id)}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        const j = await res.json();
                            if (j && j.success) {
                            // remove row
                            const row = btn.closest('tr');
                            if (row) row.remove();
                            // show SweetAlert2 success modal
                            try {
                                Swal.fire({ icon: 'success', title: 'Excluído', text: j.message || 'Encaminhamento excluído', confirmButtonText: 'OK' });
                            } catch(e) { /* fallback to toast */ showToast(j.message || 'Encaminhamento excluído', 'success'); }
                            // if no encaminhamentos left, show fallback message and hide validate button
                            const encRows = encTable.querySelectorAll('tbody tr');
                            if (!encRows.length) {
                                const container = encTable.parentElement;
                                if (container) container.innerHTML = '<div class="text-muted">Nenhum encaminhamento registrado para este candidato.</div>';
                                const valBtn = document.getElementById('btn-validar-ingresso');
                                if (valBtn) valBtn.style.display = 'none';
                            }
                        } else {
                            try { Swal.fire({ icon: 'error', title: 'Erro', text: (j && j.message) || 'Erro ao excluir', confirmButtonText: 'OK' }); } catch(e) { showToast((j && j.message) || 'Erro ao excluir', 'error'); }
                        }
                    } catch(e) { try { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação', confirmButtonText: 'OK' }); } catch(err) { showToast('Erro de comunicação', 'error'); } }
                });
            })();
        });
    </script>
@endpush

@push('scripts')
    <script>
        // Simple autosave: when a document checkbox is toggled, persist change to server
        document.addEventListener('DOMContentLoaded', function(){
            try {
                const candidateId = '{{ $candidate['id'] ?? ($candidate['num_inscricao'] ?? '') }}';
                if (!candidateId) return;
                const postUrl = '{{ url('/ingresso') }}' + '/' + encodeURIComponent(candidateId) + '/documentos';
                const csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
                const checkboxes = Array.from(document.querySelectorAll('#document-list .form-check-input'));
                if (!checkboxes.length) return;

                checkboxes.forEach(function(cb){
                    cb.addEventListener('change', async function(){
                        if (cb.disabled) return;
                        const key = cb.dataset.key;
                        if (!key) return;
                        const payload = { items: [{ key: key, label: (cb.nextElementSibling ? cb.nextElementSibling.textContent.trim() : key), validated: !!cb.checked }] };
                        try {
                            const res = await fetch(postUrl, {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });
                            const j = await res.json().catch(()=>null);
                            if (j && j.success) {
                                try {
                                    if (typeof Swal !== 'undefined') {
                                        const title = (cb.checked) ? 'Documento salvo' : 'Documento removido';
                                        Swal.fire({ toast: false, position: 'center', icon: 'success', title: j.message || title, showConfirmButton: false, timer: 1100 });
                                    }
                                } catch (e) { /* ignore UI errors */ }
                            } else {
                                console.error('Failed to save document state', j);
                                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Erro', text: (j && j.message) ? j.message : 'Falha ao salvar documento' });
                            }
                        } catch (e) {
                            console.error('Autosave error', e);
                            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação' });
                        }
                    });
                });
            } catch (e) { console.error('Autosave setup failed', e); }
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const candidateId = '{{ $candidate['id'] ?? ($candidate['num_inscricao'] ?? '') }}';
            try {
                fetch(`/ingresso/debug-status/${encodeURIComponent(candidateId)}`, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.json()).then(function(json){
                        if (json && json.success && json.candidate) {
                            const cand = json.candidate;
                            const status = cand.status ? String(cand.status).trim() : '';
                            const low = status.toLowerCase();
                            const badge = document.querySelector('.badge-status');
                            if (badge) {
                                serverStatus = status || null;
                                serverStatusLock = !!(serverStatus);
                                if (low.indexOf('valid') !== -1) {
                                    setBadgeStatus(status || 'Documentos Validados', true);
                                } else if (low.indexOf('pendente') !== -1 || low.indexOf('pendentes') !== -1) {
                                    setBadgeStatus(status || 'Documentos Pendentes', true);
                                } else if (status !== '') {
                                    setBadgeStatus(status, true);
                                }
                            }
                                // If NTE button exists, ensure it reflects server state (validated / awaiting)
                            try {
                                const btnNte = document.getElementById('nte-none-selected');
                                if (btnNte) {
                                        // if already validated, allow removal: show red button to unvalidate
                                        if (low.indexOf('valid') !== -1 || low.indexOf('documentos validados') !== -1) {
                                            if (isCpmUser) {
                                                btnNte.disabled = false;
                                                btnNte.className = 'btn btn-danger btn-action';
                                                btnNte.textContent = 'Retirar Validação dos Documentos';
                                            } else {
                                                btnNte.disabled = true;
                                                btnNte.className = 'btn btn-secondary btn-action';
                                                btnNte.textContent = 'Documentos Validados';
                                            }
                                            try {
                                                const cbs = Array.from(document.querySelectorAll('#document-list .form-check-input'));
                                                cbs.forEach(cb => cb.disabled = true);
                                            } catch(e) {}
                                        } else if (low.indexOf('aguard') !== -1 || low.indexOf('aguardando confirma') !== -1) {
                                            btnNte.disabled = true;
                                            btnNte.className = 'btn btn-secondary btn-action';
                                            btnNte.textContent = 'Aguardando Confirmação pela CPM';
                                            // also disable all document checkboxes to prevent changes
                                            try {
                                                const cbs = Array.from(document.querySelectorAll('#document-list .form-check-input'));
                                                cbs.forEach(cb => cb.disabled = true);
                                            } catch(e) {}
                                        }
                                }
                                // Update CPM button appearance: red when validated, green otherwise.
                                // If the overall ingresso is already final ('Ingresso Validado'), keep the CPM button disabled.
                                try {
                                    const btnCpm = document.getElementById('btn-validar-documentos-cpm');
                                    if (btnCpm) {
                                        if (low.indexOf('ingresso validado') !== -1) {
                                            btnCpm.disabled = true;
                                            btnCpm.className = 'btn btn-danger btn-action';
                                            btnCpm.textContent = 'Retirar Validação dos Documentos';
                                        } else if (low.indexOf('valid') !== -1 || low.indexOf('documentos validados') !== -1) {
                                            btnCpm.disabled = false;
                                            btnCpm.className = 'btn btn-danger btn-action';
                                            btnCpm.textContent = 'Retirar Validação dos Documentos';
                                        } else {
                                            btnCpm.disabled = false;
                                            btnCpm.className = 'btn btn-success btn-action';
                                            btnCpm.textContent = 'Validar documentação';
                                        }
                                    }
                                        try { console.log('debug-status set CPM:', { low: low, btnDisabled: btnCpm ? btnCpm.disabled : null, text: btnCpm ? btnCpm.textContent : null }); } catch(e) {}
                                    // If the candidate status is 'Ingresso Validado', replace the validate-ingresso button with a danger 'Retirar Validação' UI-only button
                                    try {
                                        const btnValNow = document.getElementById('btn-validar-ingresso');
                                        if (btnValNow && low.indexOf('ingresso validado') !== -1) {
                                            if (typeof window.replaceValidateWithRetirar === 'function') window.replaceValidateWithRetirar();
                                        }
                                    } catch(e) {}
                                } catch(e) {}
                            } catch(e) {}
                            // CPM document-confirmation handler removed — will re-implement server-backed flow.
                            // print button removed; no-op
                        }
                    }).catch(()=>{});
            } catch(e){}
        });
    </script>
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    // NTE: view report description handler
                    try {
                        // helper to escape HTML
                        function _escHtml(s) {
                            if (!s && s !== '') return '';
                            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
                        }
                        document.addEventListener('click', function(ev){
                            const btn = ev.target.closest('.btn-view-report');
                            if (!btn) return;
                            ev.preventDefault();
                            const label = btn.dataset.label || 'Descrição do relatório';
                            const desc = btn.dataset.desc || '';
                            try {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: label,
                                        html: '<div style="white-space:pre-wrap; text-align:left; margin-top:0.15rem;">' + _escHtml(desc || 'Sem descrição') + '</div>',
                                        icon: 'info',
                                        customClass: {
                                            popup: 'swal2-custom-popup',
                                            title: 'swal2-custom-title',
                                            htmlContainer: 'swal2-custom-text'
                                        },
                                        showCloseButton: true,
                                        confirmButtonText: 'Fechar',
                                        allowOutsideClick: true,
                                    });
                                } else {
                                    alert(label + '\n\n' + (desc || 'Sem descrição'));
                                }
                            } catch(e) { console.error('show report failed', e); }
                        });
                    } catch(e) { console.error('view-report init failed', e); }
                    const btn = document.getElementById('nte-none-selected');
                    if (!btn) return; // not an NTE user or button removed
                    const candidateId = '{{ $candidate['id'] ?? ($candidate['num_inscricao'] ?? '') }}';
                    const postUrl = '{{ url('/ingresso') }}' + '/' + encodeURIComponent(candidateId) + '/documentos';
                    const csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
                    const serverCandidateStatus = @json($candidate['status'] ?? null);
                    const checkboxes = Array.from(document.querySelectorAll('#document-list .form-check-input'));
                    // initial lock flag: if server status already indicates awaiting CPM confirmation,
                    // ensure the NTE button is disabled and labeled accordingly on load.
                    let nteLocked = false;
                    try {
                        const s = serverCandidateStatus ? String(serverCandidateStatus).toLowerCase() : '';
                        if (s.indexOf('aguard') !== -1 || s.indexOf('aguardando confirma') !== -1) {
                            nteLocked = true;
                            btn.disabled = true;
                            btn.className = 'btn btn-secondary btn-action';
                            btn.textContent = 'Aguardando Confirmação pela CPM';
                            // disable checkboxes as well
                            try { checkboxes.forEach(cb => cb.disabled = true); } catch(e) {}
                        }
                    } catch(e) {}
                    // ensure CPM button is active and styled as success for CPM users, but disable if ingresso already validated
                    try {
                        const btnCpm = document.getElementById('btn-validar-documentos-cpm');
                        const s = serverCandidateStatus ? String(serverCandidateStatus).toLowerCase() : '';
                        if (btnCpm) {
                            if (s.indexOf('ingresso validado') !== -1) {
                                btnCpm.disabled = true;
                                btnCpm.className = 'btn btn-danger btn-action';
                                btnCpm.textContent = 'Retirar Validação dos Documentos';
                            } else {
                                btnCpm.disabled = false;
                                btnCpm.className = 'btn btn-success btn-action';
                            }
                        }
                    } catch(e) {}
                    function updateNteButton() {
                        // Respect server-side final states first
                        try {
                            const s = serverCandidateStatus ? String(serverCandidateStatus).toLowerCase() : '';
                            if (s.indexOf('valid') !== -1 || s.indexOf('documentos validados') !== -1 || candidateValidated) {
                                if (isCpmUser) {
                                    btn.disabled = false;
                                    btn.className = 'btn btn-danger btn-action';
                                    btn.textContent = 'Retirar Validação dos Documentos';
                                } else {
                                    btn.disabled = true;
                                    btn.className = 'btn btn-secondary btn-action';
                                    btn.textContent = 'Documentos Validados';
                                }
                                try { checkboxes.forEach(cb => cb.disabled = true); } catch(e) {}
                                return;
                            }
                            // If server explicitly requested corrections to NTE, do not bypass required-docs check;
                            // leave enforcement to the required-docs logic below so reported/unchecked docs keep button disabled.
                            if (s.indexOf('aguardando confirma') !== -1 || s.indexOf('aguard') !== -1) {
                                btn.disabled = true;
                                btn.className = 'btn btn-secondary btn-action';
                                btn.textContent = 'Aguardando Confirmação pela CPM';
                                return;
                            }
                        } catch(e) {}

                        // Require that ALL required documents are checked before allowing NTE validation
                        const requiredChecks = Array.from(document.querySelectorAll('#document-list .form-check-input[data-required="1"]'));
                        const allRequiredChecked = requiredChecks.length ? requiredChecks.every(cb => cb.checked) : true;

                        if (!allRequiredChecked) {
                            // Some required documents are missing -> disable NTE action
                            try { checkboxes.forEach(cb => cb.disabled = !!nteLocked); } catch(e) {}
                            btn.disabled = true;
                            btn.className = 'btn btn-secondary btn-action';
                            btn.textContent = 'Faltam Documentos Obrigatórios';
                            return;
                        }

                        // If required docs are satisfied, proceed to previous selection-based logic
                        const anyChecked = checkboxes.length && checkboxes.some(cb => cb.checked);
                        if (anyChecked) {
                            if (nteLocked) {
                                try { checkboxes.forEach(cb => cb.disabled = true); } catch(e) {}
                                btn.disabled = true;
                                btn.className = 'btn btn-secondary btn-action';
                                btn.textContent = 'Aguardando Confirmação pela CPM';
                                return;
                            }
                            try { checkboxes.forEach(cb => cb.disabled = false); } catch(e) {}
                            btn.disabled = false;
                            btn.className = 'btn btn-success btn-action';
                            btn.textContent = 'Validar pelo NTE';
                        } else {
                            try { checkboxes.forEach(cb => cb.disabled = !!nteLocked); } catch(e) {}
                            btn.disabled = true;
                            btn.className = 'btn btn-secondary btn-action';
                            btn.textContent = nteLocked ? 'Aguardando Confirmação pela CPM' : 'Nenhum Documento Selecionado';
                        }
                    }
                    checkboxes.forEach(cb => cb.addEventListener('change', updateNteButton));
                    // initialize
                    updateNteButton();

                    // click: persist selected documents as NTE confirmation
                    btn.addEventListener('click', async function(){
                        if (btn.disabled) return;
                        // if documents already validated, allow removal (unvalidate)
                        const s = serverCandidateStatus ? String(serverCandidateStatus).toLowerCase() : '';
                        if (isCpmUser && (candidateValidated || s.indexOf('valid') !== -1 || s.indexOf('documentos validados') !== -1 || (btn.textContent || '').toLowerCase().indexOf('retirar') !== -1)) {
                            const confirmed = await Swal.fire({
                                title: 'Retirar validação?',
                                text: 'Confirma que deseja retirar a validação dos documentos para este ingresso?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Sim, retirar',
                                cancelButtonText: 'Cancelar'
                            });
                            if (!confirmed.isConfirmed) return;
                            try {
                                const res = await fetch(postUrl, {
                                    method: 'POST',
                                    credentials: 'same-origin',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrf,
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ unvalidate: true })
                                });
                                const j = await res.json();
                                if (j && j.success) {
                                    try { await Swal.fire({ icon: 'success', title: 'Sucesso', text: j.message || 'Validação removida.' }); } catch(e){}
                                    try { setTimeout(() => location.reload(), 700); } catch(e) { location.reload(); }
                                } else {
                                    try { Swal.fire({ icon: 'error', title: 'Erro', text: j.message || 'Erro ao remover validação' }); } catch(e){}
                                }
                            } catch (e) {
                                try { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação' }); } catch(err){}
                            }
                            return;
                        }

                        const selected = checkboxes.filter(cb => cb.checked);
                        if (!selected.length) return;
                        const items = selected.map(cb => ({ key: cb.dataset.key, label: (cb.nextElementSibling ? cb.nextElementSibling.textContent.trim() : cb.dataset.key), validated: true }));
                        try {
                            const res = await fetch(postUrl, {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ items: items, confirm: true })
                            });
                            const j = await res.json();
                            if (j && j.success) {
                                try { await Swal.fire({ icon: 'success', title: 'Sucesso', text: j.message || 'Validação registrada. Aguardando Confirmação pela CPM.' }); } catch(e){}
                                btn.disabled = true;
                                btn.className = 'btn btn-secondary btn-action';
                                btn.textContent = 'Aguardando Confirmação pela CPM';
                                try { setTimeout(() => location.reload(), 700); } catch(e) { location.reload(); }
                            } else {
                                try { Swal.fire({ icon: 'error', title: 'Erro', text: j.message || 'Erro ao salvar seleção' }); } catch(e){}
                            }
                        } catch (e) {
                            try { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação' }); } catch(err){}
                        }
                    });
                });
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    try {
                        const candidateId = '{{ $candidate['id'] ?? ($candidate['num_inscricao'] ?? '') }}';
                        const postUrl = '{{ url('/ingresso') }}' + '/' + encodeURIComponent(candidateId) + '/documentos';
                        const csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
                        const buttons = Array.from(document.querySelectorAll('.btn-report-issue'));
                        if (!buttons.length) return;

                        buttons.forEach(function(btn){
                            btn.addEventListener('click', async function(){
                                const key = btn.dataset.key;
                                const label = btn.dataset.label || key;
                                if (!key) return;
                                    try {
                                    const { value: reason } = await Swal.fire({
                                        title: 'Reportar problema em: ' + label,
                                        input: 'textarea',
                                        inputPlaceholder: 'Descreva o problema (ex: documento ilegível, dados inconsistentes)',
                                        inputAttributes: { 'aria-label': 'Motivo do problema', rows: 6, maxlength: 1000, autocapitalize: 'sentences', style: 'min-height:140px;'},
                                        showCancelButton: true,
                                        confirmButtonText: 'Enviar',
                                        cancelButtonText: 'Cancelar',
                                        allowOutsideClick: false,
                                        showLoaderOnConfirm: true,
                                        backdrop: true,
                                        customClass: {
                                            popup: 'swal2-custom-popup',
                                            title: 'swal2-custom-title',
                                            htmlContainer: 'swal2-custom-text',
                                            input: 'swal2-custom-textarea'
                                        },
                                        footer: '<small>Por favor descreva claramente o problema. Máx. 1000 caracteres.</small>',
                                        preConfirm: (val) => {
                                            if (!val || !val.trim()) {
                                                Swal.showValidationMessage('O motivo é obrigatório');
                                                return false;
                                            }
                                            return val.trim();
                                        }
                                    });
                                    if (!reason) return;

                                    const res = await fetch(postUrl, {
                                        method: 'POST',
                                        credentials: 'same-origin',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrf,
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({ issue: true, key: key, reason: reason })
                                    });
                                    const j = await res.json().catch(()=>null);
                                        if (j && j.success) {
                                        await Swal.fire({ icon: 'success', title: 'Reportado', text: j.message || 'Problema reportado ao NTE.' });
                                        // append or update small badge to label; if already marked as resolved change to reported
                                        try {
                                            const labelEl = document.querySelector('#doc_' + CSS.escape(key) + ' + label') || document.querySelector('label[for="doc_' + key + '"]');
                                            if (labelEl) {
                                                const existing = labelEl.querySelector('.issue-badge');
                                                const badgeText = 'Problema reportado';
                                                if (existing) {
                                                    existing.className = 'text-danger issue-badge ms-2 small';
                                                    existing.textContent = badgeText;
                                                    existing.title = j.message || reason || badgeText;
                                                } else {
                                                    const span = document.createElement('span');
                                                    span.className = 'text-danger issue-badge ms-2 small';
                                                    span.textContent = badgeText;
                                                    span.title = j.message || reason || badgeText;
                                                    labelEl.appendChild(span);
                                                }
                                            }
                                            // ensure 'Retornar para o NTE' button is enabled for CPM when reports exist
                                            try {
                                                const btnReturn = document.getElementById('btn-return-to-nte');
                                                if (btnReturn) {
                                                    btnReturn.disabled = false;
                                                    btnReturn.className = 'btn btn-outline-warning btn-action';
                                                }
                                            } catch(e) {}
                                        } catch(e) { /* ignore UI badge errors */ }
                                    } else {
                                        Swal.fire({ icon: 'error', title: 'Erro', text: (j && j.message) || 'Falha ao reportar' });
                                    }
                                } catch (e) {
                                    Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação' });
                                }
                            });
                        });
                    } catch (e) { console.error('Report issue setup failed', e); }
                });
            </script>
@endpush
