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
                max-height: 180px;
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
                        <div>
                            <button id="btn-edit-dados" type="button" class="btn btn-sm btn-primary" style="border-radius:5px; display:inline-flex; align-items:center; justify-content:center; padding:5px !important;">
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
                            @endphp
                            @if (is_array($serverDocs) && count($serverDocs))
                                @foreach ($serverDocs as $doc)
                                    @php
                                        $k = $doc['key'] ?? ($doc->key ?? null);
                                        $lbl = $doc['label'] ?? ($doc->label ?? $k);
                                        $isChecked =
                                            isset($existingDocs[$k]) &&
                                            (!empty($existingDocs[$k]['validated']) || $existingDocs[$k] === true);
                                    @endphp
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="doc_{{ $k }}" data-key="{{ $k }}"
                                            {{ $isChecked ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2"
                                            for="doc_{{ $k }}">{{ $lbl }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="d-grid mb-2">
                            <button id="save-checklist" class="btn btn-primary btn-action" disabled>Validar toda a
                                documentação</button>
                        </div>

                        <hr />

                        <div class="section-title">Registrar Processo SEI</div>
                        @if (Route::has('ingresso.assign'))
                            <form id="sei-form" method="POST"
                                action="{{ route('ingresso.assign', $candidate['id'] ?? $candidate['num_inscricao']) }}">
                                @csrf
                                <div class="form-group mb-2">
                                    <input type="text" name="sei_number" class="form-control form-control-sm"
                                        placeholder="Número do processo SEI (ex: 00000.000000/0000-00)"
                                        value="{{ $candidate['sei_number'] ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary btn-action">Registrar Processo SEI</button>
                            </form>
                        @else
                            <input class="form-control form-control-sm mb-2" disabled
                                placeholder="Rota de registro indisponível">
                            <button class="btn btn-primary btn-action" disabled>Registrar Processo SEI</button>
                        @endif

                        <div class="mt-2">
                            <button id="btnEncaminhar" class="btn btn-outline-secondary btn-action" type="button">Encaminhar</button>
                        </div>

                        {{-- Painel embutido para encaminhamento (inicialmente escondido) --}}
                        <div id="encaminhar-panel" class="card-panel" style="display:none;">
                            <style>
                                /* Make Select2 look like bootstrap inputs inside the panel */
                                #encaminhar-panel .select2-container--default .select2-selection--single,
                                #encaminhar-panel .select2-container--bootstrap4 .select2-selection--single {
                                    height: calc(1.5em + .75rem + 2px) !important;
                                    padding: .375rem .75rem !important;
                                    border: 1px solid #ced4da !important;
                                    border-radius: .25rem !important;
                                    background-color: #fff !important;
                                }
                                #encaminhar-panel .select2-container--default .select2-selection__rendered,
                                #encaminhar-panel .select2-container--bootstrap4 .select2-selection__rendered {
                                    color: #495057 !important;
                                    text-align: left !important;
                                    white-space: nowrap !important;
                                    overflow: hidden !important;
                                    text-overflow: ellipsis !important;
                                }
                                #encaminhar-panel .select2-container { width:100% !important; }
                                /* Remove button style inside panel */
                                #encaminhar-panel .remove-discipline {
                                    border-radius: 5px !important;
                                    padding: .2rem .45rem !important;
                                }
                            </style>
                            <div class="section-title">Encaminhar Candidato</div>
                            <form id="encaminhar-form">
                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label class="small font-weight-bold">Unidade Escolar <span class="text-danger">*</span></label>
                                        <select id="uee-select" name="uee_id" class="form-control form-control-sm" style="width:100%"></select>
                                        <input type="hidden" name="uee_name" id="uee_name">
                                        <input type="hidden" name="uee_code" id="uee_code">
                                        <input type="hidden" name="uee_municipio" id="uee_municipio">

                                        <div class="mt-2">
                                            <label class="small font-weight-bold">Motivo <span class="text-danger">*</span></label>
                                            <select id="motivo-select" name="motivo" class="form-control form-control-sm">
                                                <option value="" disabled selected>Selecione motivo</option>
                                                <option value="Substituição de Licença">Substituição de Licença</option>
                                                <option value="Aposentadoria">Aposentadoria</option>
                                                <option value="Reda Emergencial">Reda Emergencial</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 form-group">
                                        <label class="small font-weight-bold">Disciplinas <span class="text-danger">*</span></label>
                                        <div id="disciplinas-root">
                                            <div class="discipline-row d-flex align-items-start mb-2" data-index="0">
                                                <div style="flex:1 1 60%">
                                                    <select name="disciplinas[0][disciplina_id]" class="form-control form-control-sm disciplina-select" style="width:100%"></select>
                                                </div>
                                                <div class="ml-2" style="width:100px">
                                                    <input type="number" min="0" name="disciplinas[0][quant_matutino]" class="form-control form-control-sm" placeholder="Mat." />
                                                </div>
                                                <div class="ml-2" style="width:100px">
                                                    <input type="number" min="0" name="disciplinas[0][quant_vespertino]" class="form-control form-control-sm" placeholder="Vesp." />
                                                </div>
                                                <div class="ml-2" style="width:100px">
                                                    <input type="number" min="0" name="disciplinas[0][quant_noturno]" class="form-control form-control-sm" placeholder="Not." />
                                                </div>
                                                <div class="ml-2">
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-discipline" aria-label="Remover disciplina" style="border-radius:5px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" id="add-discipline" class="btn btn-secondary btn-sm" style="border-radius:5px;">Adicionar disciplina</button>
                                        </div>
                                        <small class="form-text text-muted">Selecione uma disciplina e informe a quantidade por turno. Você pode adicionar várias disciplinas.</small>
                                    </div>

                                    <div class="col-12 form-group">
                                        <label class="small font-weight-bold">Observação (Opcional)</label>
                                        <textarea name="observacao" class="form-control form-control-sm" rows="3"></textarea>
                                    </div>
                                </div>
                            </form>
                            <div class="mt-2 text-right">
                                <button id="encaminhar-cancel" type="button" class="btn btn-link text-secondary">Cancelar</button>
                                <button id="encaminhar-submit" type="button" class="btn btn-primary px-4">Enviar Encaminhamento</button>
                            </div>
                        </div>

                        <hr />
                        <div class="section-title">Encaminhamentos Registrados</div>
                        @if (isset($encaminhamentos) && count($encaminhamentos))
                            @php $isCpmEnc = (optional(Auth::user())->sector_id == 2 && optional(Auth::user())->profile_id == 1); @endphp
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Unidade Escolar</th>
                                            <th>Motivo</th>
                                            <th>Disciplina</th>
                                            <th class="text-center">Mat.</th>
                                            <th class="text-center">Vesp.</th>
                                            <th class="text-center">Not.</th>
                                            <th class="text-center">Total</th>
                                            <th>Usuário</th>
                                            @if($isCpmEnc)
                                                <th>Ações</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sum_mat = 0;
                                            $sum_vesp = 0;
                                            $sum_not = 0;
                                            $sum_total = 0;
                                        @endphp
                                        @foreach ($encaminhamentos as $e)
                                            @php
                                                $m = (int) ($e->quant_matutino ?? 0);
                                                $v = (int) ($e->quant_vespertino ?? 0);
                                                $n = (int) ($e->quant_noturno ?? 0);
                                                $rowTotal = $m + $v + $n;
                                                $sum_mat += $m; $sum_vesp += $v; $sum_not += $n; $sum_total += $rowTotal;
                                            @endphp
                                            <tr data-enc-id="{{ $e->id ?? $e->encaminhamento_id }}">
                                                <td>{{ \Carbon\Carbon::parse($e->created_at)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                                                <td>{{ $e->uee_name ?? $e->uee_code ?? '-' }}</td>
                                                <td>{{ $e->motivo ?? '-' }}</td>
                                                <td>{{ $e->disciplina_name ?? $e->disciplina_code ?? '-' }}</td>
                                                <td class="text-center">{{ $m }}</td>
                                                <td class="text-center">{{ $v }}</td>
                                                <td class="text-center">{{ $n }}</td>
                                                <td class="text-center">{{ $rowTotal }}</td>
                                                <td>{{ $e->created_by_name ?? $e->created_by ?? '-' }}</td>
                                                @if($isCpmEnc)
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-enc" data-id="{{ $e->id ?? $e->encaminhamento_id }}" aria-label="Excluir encaminhamento" style="border-radius:5px; padding:5px !important; display:inline-flex; align-items:center; justify-content:center; line-height:1;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-trash" style="border-radius:5px;">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" />
                                                                <path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Total Geral</th>
                                            <th class="text-center">{{ $sum_mat }}</th>
                                            <th class="text-center">{{ $sum_vesp }}</th>
                                            <th class="text-center">{{ $sum_not }}</th>
                                            <th class="text-center">{{ $sum_total }}</th>
                                            <th></th>
                                            @if($isCpmEnc)
                                                <th></th>
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-muted">Nenhum encaminhamento registrado para este candidato.</div>
                        @endif
                        
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
                            @if ($isCpm && $hasSei)
                                {{-- Show validate button only when documents are validated and there are encaminhamentos --}}
                                @if ($docsValid && $hasEnc)
                                    <button id="btn-validar-ingresso" class="btn btn-success btn-sm" style="border-radius:5px;">Validar Ingresso</button>
                                @endif
                                    <button id="btn-imprimir-oficio" class="btn btn-outline-primary btn-sm" style="border-radius:5px; {{ $showPrintFromStatus ? 'display:inline-block;' : 'display:none;' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-file-description" style="margin-right:6px;vertical-align:middle;width:16px;height:16px;"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                                        Imprimir Ofício
                                    </button>
                            @elseif ($showPrintFromStatus && $hasSei)
                                <button id="btn-imprimir-oficio" class="btn btn-outline-primary btn-sm" style="border-radius:5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-file-description" style="margin-right:6px;vertical-align:middle;width:16px;height:16px;"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                                    Imprimir Ofício
                                </button>
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
            // current user role info (used to decide CPM confirmation)
            const currentUserSector = @json(optional(Auth::user())->sector_id);
            const currentUserProfile = @json(optional(Auth::user())->profile_id);
            // current candidate status string from server
            let candidateStatus = @json($candidate['status'] ?? null);
            // server-authoritative status and lock to prevent client overwrites unless a server response forces update
            let serverStatus = candidateStatus ? String(candidateStatus).trim() : null;
            let serverStatusLock = serverStatus && serverStatus !== '';
            // explicit documentos_validados flag from server (used to decide strict locking)
            const initialDocsValid = {!! json_encode(isset($candidate['documentos_validados']) && ($candidate['documentos_validados'] == 1 || $candidate['documentos_validados'] === true)) !!};

            // If this user is NTE and the candidate is validated by CPM, lock the UI (disable actions)
            (function lockUiForNteIfValidated(){
                try {
                    const isNte = (currentUserSector == 7 && currentUserProfile == 1);
                    if (!isNte) return;
                    // Only enforce lock for NTE when server explicitly indicates final validation ('Ingresso Validado')
                    const srv = serverStatus ? String(serverStatus).toLowerCase().trim() : '';
                    const shouldLock = (srv === 'ingresso validado');
                    if (!shouldLock) return;
                    // enforce lock only when server status is the final validated marker
                    if (srv === 'ingresso validado') {
                        // Disable interactive controls except print button
                        const except = ['btn-imprimir-oficio'];
                        // Buttons
                        document.querySelectorAll('button').forEach(b => {
                            if (except.indexOf(b.id) === -1) b.disabled = true;
                        });
                        // Inputs, selects, textareas, checkboxes
                        document.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                        // Ensure print button visible and enabled
                        const printBtn = document.getElementById('btn-imprimir-oficio');
                        if (printBtn) { printBtn.style.display = 'inline-block'; printBtn.disabled = false; }
                        // Hide the encaminhar trigger so NTE cannot open panel
                        const encBtn = document.getElementById('btnEncaminhar'); if (encBtn) encBtn.style.display = 'none';
                        // Hide any add/remove discipline controls if present
                        document.querySelectorAll('#add-discipline, .remove-discipline').forEach(el => el.style.display = 'none');
                        // Disable SEI form submission button (if present)
                        const seiFormBtn = document.querySelector('#sei-form button[type=submit]'); if (seiFormBtn) seiFormBtn.disabled = true;
                    }
                } catch(e){}
            })();

            function renderList(list, existing) {
                // normalize existing values to booleans: either `true` or object with `validated` property
                const normalized = {};
                if (existing && typeof existing === 'object') {
                    Object.keys(existing).forEach(function(k) {
                        const v = existing[k];
                        normalized[k] = (v === true) || (v && (v.validated === true || v.validated === 1));
                    });
                }

                const container = document.getElementById('document-list');
                container.innerHTML = '';
                list.forEach(function(item) {
                    const isChecked = !!normalized[item.key];
                    const checked = isChecked ? 'checked' : '';
                    const row = document.createElement('div');
                    row.className = 'form-check mb-1';
                    row.innerHTML = '<input class="form-check-input" type="checkbox" value="" id="doc_' +
                        item.key + '" data-key="' + item.key + '" ' + checked + '>' +
                        '<label class="form-check-label ms-2" for="doc_' + item.key + '">' + item.label +
                        '</label>';
                    container.appendChild(row);

                    // attach change handler to auto-save this single item
                    const cb = row.querySelector('.form-check-input');
                    if (cb) {
                        cb.addEventListener('change', function(evt) {
                            const key = cb.dataset.key;
                            const label = cb.nextElementSibling ? cb.nextElementSibling.textContent
                                .trim() : key;
                            const validated = cb.checked;
                            // optimistically update badge and UI, but revert on error
                            saveSingle({
                                key: key,
                                label: label,
                                validated: validated
                            }, cb);
                        });
                    }
                });
                // ensure the save button state reflects current checks
                updateActionButtonState();
            }

            function updateBadgeState() {
                // Do not change the badge here. The badge should update only when
                // the user confirms validation via the "Validar" button.
                updateActionButtonState();
            }

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

            function updateActionButtonState() {
                const btn = document.getElementById('save-checklist');
                if (!btn) return;
                // If documents already validated by CPM, NTE users cannot modify
                if (candidateValidated && currentUserSector == 7 && currentUserProfile == 1) {
                    // disable all checkboxes and the button
                    const checksAll = Array.from(document.querySelectorAll('#document-list .form-check-input'));
                    checksAll.forEach(ch => { ch.disabled = true; });
                    btn.disabled = true;
                    btn.className = 'btn btn-secondary btn-action';
                    btn.textContent = 'Documentos validados pela CPM';
                    return;
                }
                const checks = Array.from(document.querySelectorAll('#document-list .form-check-input'));
                const all = checks.length && checks.every(ch => ch.checked);
                // Decide labels based on role and current candidate status
                if (all) {
                    if (candidateValidated) {
                        btn.disabled = false;
                        btn.className = 'btn btn-danger btn-action';
                        btn.textContent = 'Retirar validação';
                        setBadgeStatus('Documentos Validados');
                    } else {
                        // If status is already pending for CPM and current user is CPM, show CPM confirm
                        if (candidateStatus && String(candidateStatus).toLowerCase().indexOf('pendente') !== -1) {
                            // If pending for CPM: only CPM may confirm; NTE should see button disabled
                            if (currentUserSector == 2 && currentUserProfile == 1) {
                                btn.disabled = false;
                                btn.className = 'btn btn-success btn-action';
                                btn.textContent = 'Confirmar validação (CPM)';
                            } else {
                                btn.disabled = true;
                                btn.className = 'btn btn-secondary btn-action';
                                btn.textContent = 'Aguardando confirmação pela CPM';
                            }
                        } else {
                            btn.disabled = false;
                            btn.className = 'btn btn-primary btn-action';
                            btn.textContent = 'Validar toda a documentação';
                        }
                        // do not change badge here — badge changes only on explicit confirmation
                    }
                } else {
                    // not all checked: disable and reset to default
                    btn.disabled = true;
                    btn.className = 'btn btn-primary btn-action';
                    btn.textContent = 'Validar toda a documentação';
                    // if candidate already validated keep badge, otherwise show pending/pendentes
                    if (!candidateValidated) {
                        // if server had marked the candidate as pending for CPM, reflect that
                        if (candidateStatus && String(candidateStatus).toLowerCase().indexOf('pendente') !== -1) {
                            setBadgeStatus(candidateStatus);
                        } else {
                            setBadgeStatus('Documentos Pendentes');
                        }
                    }
                }
            }

            function saveSingle(item, checkboxEl) {
                // send single-item payload to backend
                fetch(postUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        items: [item]
                    })
                }).then(r => r.json()).then(function(json) {
                    if (json && json.success) {
                        if (item.validated) {
                            showToast('Documento marcado como validado: ' + item.label, 'success');
                        } else {
                            showToast('Validação removida: ' + item.label, 'info');
                        }
                        // Do not change overall badge here — only update button state.
                        updateActionButtonState();
                        // If server indicates finalization, reload to reflect authoritative state
                        if (json.finalized) {
                            try { setTimeout(() => location.reload(), 700); } catch(e){}
                        }
                    } else {
                        // revert checkbox
                        if (checkboxEl) checkboxEl.checked = !item.validated;
                        showToast((json && json.message) || 'Erro ao salvar alteração', 'error');
                    }
                }).catch(function() {
                    if (checkboxEl) checkboxEl.checked = !item.validated;
                    showToast('Erro ao comunicar com o servidor', 'error');
                });
            }

            // initial render: use server-provided list when available
            const serverList = {!! json_encode($documentList ?? []) !!};
            const existing = {!! json_encode($existingDocuments ?? []) !!};
            if (serverList && serverList.length) {
                renderList(serverList, existing);
            } else {
                // fetch from API
                fetch(getUrl, {
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json()).then(function(json) {
                        if (json.list) renderList(json.list, json.existing || {});
                    }).catch(() => {});
            }

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

            // Encaminhar: painel embutido -------------------------------------------------
            (function() {
                const btnEnc = document.getElementById('btnEncaminhar');
                if (!btnEnc) return;

                const $panel = $('#encaminhar-panel');
                const $uee = $('#uee-select');

                // toggle panel visibility
                btnEnc.addEventListener('click', function() {
                    const bd = document.querySelector('.candidate-body');
                    if ($panel.is(':visible')) {
                        $panel.slideUp(180);
                        if (bd) bd.classList.remove('encaminhar-open');
                    } else {
                        $panel.slideDown(200, function() {
                            // initialize selects when panel is shown
                            initUeeSelect();
                            if (window.initDisciplinaSelects) window.initDisciplinaSelects();
                        });
                        if (bd) bd.classList.add('encaminhar-open');
                        // scroll to panel for better UX
                        $('html,body').animate({ scrollTop: $panel.offset().top - 80 }, 240);
                    }
                });

                // cancel button
                $('#encaminhar-cancel').on('click', function() { $panel.slideUp(150); const bd = document.querySelector('.candidate-body'); if (bd) bd.classList.remove('encaminhar-open'); });

                function initUeeSelect() {
                    if (!$uee.length) return;
                    if ($uee.hasClass('select2-hidden-accessible')) return;
                    $uee.select2({
                        dropdownParent: $panel,
                        placeholder: 'Digite o nome da escola ou município...',
                        allowClear: true,
                        width: '100%',
                        minimumInputLength: 2,
                        language: {
                            inputTooShort: function (args) {
                                var remaining = args.minimum - args.input.length;
                                return 'Por favor, digite ' + remaining + ' ou mais caracteres';
                            }
                        },
                        ajax: {
                            url: '/uees/autocomplete',
                            type: 'POST',
                            dataType: 'json',
                            delay: 250,
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: params => ({ q: params.term }),
                            processResults: data => ({ results: (data||[]).map(d => ({ id: d.cod_unidade, text: `${d.name} — ${d.municipio} (${d.cod_unidade})`, _raw: d })) }),
                            cache: true
                        }
                    }).on('select2:select', function(e) {
                        const d = e.params.data._raw;
                        document.getElementById('uee_name').value = d.name;
                        document.getElementById('uee_code').value = d.cod_unidade;
                        document.getElementById('uee_municipio').value = d.municipio;
                    });
                }

                // disciplinas dinâmicas (define window.initDisciplinaSelects)
                (function(){
                    let discIndex = 1;

                    function initDisciplinaSelect($sel) {
                        if (!$sel.length) return;
                        if ($sel.hasClass('select2-hidden-accessible')) return;
                        $sel.select2({
                            dropdownParent: $panel,
                            placeholder: 'Procure a disciplina...',
                            allowClear: true,
                            width: '100%',
                            minimumInputLength: 1,
                            language: {
                                inputTooShort: function (args) {
                                    var remaining = args.minimum - args.input.length;
                                    return 'Por favor, digite ' + remaining + ' ou mais caracteres';
                                },
                                searching: function () { return 'Procurando...'; },
                                noResults: function () { return 'Nenhum resultado encontrado'; }
                            },
                            ajax: {
                                url: '/consultarDisciplina',
                                type: 'POST',
                                dataType: 'json',
                                delay: 250,
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                data: params => ({ q: params.term }),
                                processResults: data => ({ results: (data||[]).map(d=>({ id: d.id ?? d.ID ?? d.id_disciplina, text: d.nome ?? d.name ?? d.Nome, _raw: d })) }),
                                cache: true
                            }
                        });
                    }

                    function initDisciplinaSelects(){
                        $('#disciplinas-root').find('.disciplina-select').each(function(){ initDisciplinaSelect($(this)); });
                    }

                    // init first time when panel shown (if visible now)
                    if ($panel.is(':visible')) initDisciplinaSelects();

                    $('#add-discipline').on('click', function(){
                        const idx = discIndex++;
                        const row = $(
                            '<div class="discipline-row d-flex align-items-start mb-2" data-index="'+idx+'">' +
                                '<div style="flex:1 1 60%">' +
                                    '<select name="disciplinas['+idx+'][disciplina_id]" class="form-control form-control-sm disciplina-select" style="width:100%"></select>' +
                                '</div>' +
                                '<div class="ml-2" style="width:100px">' +
                                    '<input type="number" min="0" name="disciplinas['+idx+'][quant_matutino]" class="form-control form-control-sm" placeholder="Mat." />' +
                                '</div>' +
                                '<div class="ml-2" style="width:100px">' +
                                    '<input type="number" min="0" name="disciplinas['+idx+'][quant_vespertino]" class="form-control form-control-sm" placeholder="Vesp." />' +
                                '</div>' +
                                '<div class="ml-2" style="width:100px">' +
                                    '<input type="number" min="0" name="disciplinas['+idx+'][quant_noturno]" class="form-control form-control-sm" placeholder="Not." />' +
                                '</div>' +
                                    '<div class="ml-2">' +
                                    '<button type="button" class="btn btn-outline-danger btn-sm remove-discipline" aria-label="Remover disciplina" style="border-radius:5px;">' +
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>' +
                                '</button>' +
                                '</div>' +
                            '</div>'
                        );
                        $('#disciplinas-root').append(row);
                        initDisciplinaSelect(row.find('.disciplina-select'));
                    });

                    $('#disciplinas-root').on('click', '.remove-discipline', function(){
                        const rows = $('#disciplinas-root').find('.discipline-row');
                        if (rows.length <= 1) {
                            const r = $(this).closest('.discipline-row');
                            r.find('.disciplina-select').val(null).trigger('change');
                            r.find('input[type=number]').val('');
                        } else {
                            const r = $(this).closest('.discipline-row');
                            try { r.find('.disciplina-select').select2('destroy'); } catch(e){}
                            r.remove();
                        }
                    });

                    // expose initializer to outer scope
                    window.initDisciplinaSelects = initDisciplinaSelects;
                })();

                // submit
                document.getElementById('encaminhar-submit').addEventListener('click', async function() {
                    const form = document.getElementById('encaminhar-form');

                    // build structured payload to match controller expectations
                    const uee_code = document.getElementById('uee_code') ? document.getElementById('uee_code').value : null;
                    const uee_name = document.getElementById('uee_name') ? document.getElementById('uee_name').value : null;
                    const observacaoEl = form.querySelector('textarea[name="observacao"]');
                    const observacao = observacaoEl ? observacaoEl.value.trim() : null;
                    const motivoEl = document.getElementById('motivo-select');
                    const motivo = motivoEl ? (motivoEl.value || null) : null;

                    // gather disciplinas rows
                    const disciplinas = Array.from(document.querySelectorAll('#disciplinas-root .discipline-row')).map(row => {
                        const $row = $(row);
                        const sel = $row.find('.disciplina-select');
                        const disciplinaId = sel.length ? sel.val() : null;
                        let disciplinaName = null;
                        try {
                            const ddata = sel.select2 ? sel.select2('data') : null;
                            if (ddata && ddata.length) disciplinaName = ddata[0].text || ddata[0].nome || ddata[0].Nome || null;
                        } catch (e) {}
                        // quantity inputs
                        const mat = row.querySelector('input[name$="[quant_matutino]"]');
                        const vesp = row.querySelector('input[name$="[quant_vespertino]"]');
                        const not = row.querySelector('input[name$="[quant_noturno]"]');
                        return {
                            disciplina_id: disciplinaId || null,
                            disciplina_name: disciplinaName,
                            quant_matutino: mat ? (mat.value !== '' ? parseInt(mat.value, 10) : null) : null,
                            quant_vespertino: vesp ? (vesp.value !== '' ? parseInt(vesp.value, 10) : null) : null,
                            quant_noturno: not ? (not.value !== '' ? parseInt(not.value, 10) : null) : null,
                        };
                    }).filter(d => d.disciplina_id !== null && d.disciplina_id !== '');

                    // basic validation
                    if (!uee_name && !uee_code) {
                        return Swal.fire('Atenção', 'Por favor, selecione a escola.', 'warning');
                    }
                    if (!motivo) {
                        return Swal.fire('Atenção', 'Por favor, selecione o motivo do encaminhamento.', 'warning');
                    }
                    if (!disciplinas.length) {
                        return Swal.fire('Atenção', 'Por favor, adicione pelo menos uma disciplina.', 'warning');
                    }

                    const payload = {
                        uee_code: uee_code || null,
                        uee_name: uee_name || null,
                        motivo: motivo || null,
                        observacao: observacao || null,
                        disciplinas: disciplinas
                    };

                    try {
                        console.log('Encaminhar payload:', payload);
                        const response = await fetch(`/ingresso/${candidateId}/encaminhar`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();
                        if (result.success) {
                            const bd = document.querySelector('.candidate-body'); if (bd) bd.classList.remove('encaminhar-open');
                            $panel.slideUp(180);
                            Swal.fire('Sucesso', result.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Erro', result.message || 'Erro na operação', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Erro', 'Falha na comunicação com o servidor.', 'error');
                    }
                });
            })();

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

            document.getElementById('save-checklist').addEventListener('click', function() {
                const btn = document.getElementById('save-checklist');
                if (!btn) return;
                // prevent action when button is intentionally disabled (e.g., NTE while pending CPM)
                if (btn.disabled) {
                    showToast('Ação indisponível: aguardando confirmação pela CPM.', 'info');
                    return;
                }
                const checks = Array.from(document.querySelectorAll('#document-list .form-check-input'));
                const items = checks.map(function(ch) {
                    return {
                        key: ch.dataset.key,
                        label: ch.nextElementSibling ? ch.nextElementSibling.textContent.trim() : ch
                            .dataset.key,
                        validated: !
                            candidateValidated // if currently validated -> we will unvalidate (false); else validate (true)
                    };
                });

                // confirmation text depends on action
                const isUnvalidate = !!candidateValidated;
                const title = isUnvalidate ? 'Remover validação de toda a documentação?' :
                    'Validar toda a documentação?';
                const text = isUnvalidate ? 'Confirme para remover a validação de todos os documentos.' :
                    'Confirme para marcar todos os documentos como validados. Esta ação será registrada.';
                const confirmText = isUnvalidate ? 'Sim, retirar validação' : 'Sim, validar tudo';

                function doRequest() {
                    fetch(postUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            items: items,
                            confirm: true,
                            unvalidate: isUnvalidate
                        })
                    }).then(r => r.json()).then(function(json) {
                        if (json && json.success) {
                                if (isUnvalidate) {
                                showToast('Validação removida de todos os documentos.', 'success');
                                candidateValidated = false;
                                setBadgeStatus('Documentos Pendentes', true);
                                // reload after successful unvalidation so server state is reflected
                                try { setTimeout(() => location.reload(), 700); } catch(e){}
                            } else {
                                // server will indicate if the action finalized validation (CPM) or set as pending (NTE)
                                if (json.finalized) {
                                    showToast('Todos os documentos foram validados.', 'success');
                                    candidateValidated = true;
                                    setBadgeStatus('Documentos Validados', true);
                                    // reload to reflect authoritative server state
                                    try { setTimeout(() => location.reload(), 700); } catch(e){}
                                } else {
                                    showToast(
                                        'Validação registrada. Aguardando confirmação pela CPM.',
                                        'success');
                                    candidateValidated = false;
                                    setBadgeStatus(json.status || 'Pendente validação pela CPM', true);
                                }
                            }
                            // ensure checkboxes reflect server action
                            checks.forEach(function(ch) {
                                ch.checked = !isUnvalidate;
                            });
                            updateActionButtonState();
                        } else {
                            showToast((json && json.message) || 'Erro ao validar documentos',
                                'error');
                        }
                    }).catch(function() {
                        showToast('Erro ao comunicar com o servidor', 'error');
                    });
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) doRequest();
                });
            });
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
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const btnVal = document.getElementById('btn-validar-ingresso');
            const btnPrint = document.getElementById('btn-imprimir-oficio');
            const candidateId = '{{ $candidate['id'] ?? ($candidate['num_inscricao'] ?? '') }}';
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
                                if (btnPrint) btnPrint.style.display = 'inline-block';
                                // reload page so server-side state is authoritative after CPM validation
                                try { location.reload(); } catch(e) {}
                            } else {
                                Swal.fire({ icon: 'error', title: 'Erro', text: j.message || 'Erro ao validar' });
                            }
                    } catch (e) { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro de comunicação' }); }
                });
            }
            if (btnPrint) {
                btnPrint.addEventListener('click', function(){
                    window.open(`/ingresso/${candidateId}/oficio?print=1`, '_blank');
                });
            }
            // Encaminhamento deletion (CPM only)
            (function(){
                const table = document.querySelector('#document-list').closest('div').previousElementSibling;
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
                            const btn = document.getElementById('btn-imprimir-oficio');
                            if (btn) {
                                if (status.toLowerCase().trim() === 'ingresso validado') {
                                    btn.style.display = 'inline-block';
                                } else {
                                    btn.style.display = 'none';
                                }
                            }
                        }
                    }).catch(()=>{});
            } catch(e){}
        });
    </script>
@endpush
