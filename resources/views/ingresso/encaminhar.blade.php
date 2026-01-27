@extends('layout.main')

@section('title', 'Encaminhar Candidato')

@section('content')
    <style>
        .candidate-modern-card {
            background: linear-gradient(135deg, var(--bs-primary, #2575fc) 0%, #1e6fe8 100%);
            color: #fff;
            border-radius: 0.6rem;
            padding: 0.9rem;
            box-shadow: 0 10px 30px rgba(37, 117, 252, 0.12);
        }

        .candidate-modern-card .card-label {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.85);
        }

        .candidate-modern-card .unit-name {
            font-weight: 700;
            font-size: 0.9rem;
            margin-top: 4px;
        }

        .candidate-modern-card .meta {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.92);
        }

        .candidate-modern-card .muted {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.72rem;
        }

        .candidate-modern-card .badge-code {
            background: rgba(255, 255, 255, 0.12);
            padding: 0.18rem 0.5rem;
            border-radius: 0.35rem;
            font-family: monospace;
            color: #fff;
            display: inline-block;
        }

        .candidate-modern-card hr {
            border-top-color: rgba(255, 255, 255, 0.12);
            margin: 0.6rem 0;
        }

        /* Buttons inside this form should have small radius and padding per UI request */
        #encaminharForm .btn {
            border-radius: 5px !important;
            padding: 8px !important;
        }

        /* Substituição servidor card */
        .subs-card {
            background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
            border-radius: 0.6rem;
            padding: 0.75rem 0.85rem;
            box-shadow: 0 6px 18px rgba(11,61,145,0.06);
            border: 1px solid rgba(11,61,145,0.06);
        }
        .subs-card .subs-name {
            font-weight: 700;
            color: #0b3d91;
            font-size: 1rem;
            letter-spacing: 0.2px;
        }
        .subs-card .subs-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .subs-card .subs-badges { gap: .4rem; display:flex; align-items:center; margin-top:.4rem }
        .subs-card .badge { font-size: 0.75rem; padding: .35rem .5rem; }
        #btn-substituicao-clear { border-radius: .35rem; }
    </style>
    <div class="container-fluid ingresso-vh py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @php
                    // Compute header status from last encaminhamento or first encaminhamento rows
                    $headerStatus = null;
                    if (isset($last_encaminhamento) && $last_encaminhamento) {
                        $headerStatus = $last_encaminhamento->status ?? null;
                    } elseif (isset($encaminhamentos) && $encaminhamentos instanceof \Illuminate\Support\Collection && $encaminhamentos->isNotEmpty()) {
                        $headerStatus = $encaminhamentos->first()->status ?? null;
                    }
                    // Ensure $cardStatus remains defined for later use in the card body
                    $cardStatus = $cardStatus ?? null;
                @endphp
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="mb-1">Encaminhar Candidato</h4>
                                <div class="small text-muted">Preencha os dados para encaminhar o candidato</div>
                            </div>
                            <div class="text-end">
                                <span id="mainCardStatus">
                                    @if (isset($headerStatus) && str_contains(mb_strtolower($headerStatus), mb_strtolower('Encaminhamento validado')))
                                            <span class="badge bg-success text-white fw-bold"><strong>{{ $headerStatus }}</strong></span>
                                        @elseif(is_null($headerStatus))
                                            <span class="badge bg-danger text-white fw-bold"><strong>Pendente Validação</strong></span>
                                        @else
                                            <span class="badge bg-danger text-white fw-bold"><strong>{{ $headerStatus }}</strong></span>
                                        @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="candidate-modern-card h-100 shadow-sm border">
                                    <div class="card-body">
                                        <div id="candidateCard" class=" mt-2">
                                            <div class="card-label">Nome</div>
                                            <div id="candidateNameDisplay" class="unit-name">
                                                {{ $candidate->name ?? ($candidate->nome ?? '-') }}</div>
                                            <div class="card-label mt-2">Inscrição</div>
                                            <div id="candidateInscricaoDisplay" class="meta">
                                                {{ $candidate->num_inscricao ?? '-' }}</div>

                                            <hr>

                                            @php
                                                $cardUeeName = null;
                                                $cardUeeNte = null;
                                                $cardUeeMunicipio = null;
                                                $cardUeeCodigo = null;
                                                $cardDisciplinaName = null;
                                                $cardMat = 0;
                                                $cardVes = 0;
                                                $cardNot = 0;
                                                $cardTipoText = null;
                                                $cardObservacao = null;
                                                $cardStatus = null;
                                                if (isset($last_encaminhamento) && $last_encaminhamento) {
                                                    $cardUeeName =
                                                        $last_encaminhamento->uee_name ??
                                                        ($last_encaminhamento->uee_nome ??
                                                            ($last_encaminhamento->uee_id ??
                                                                ($last_encaminhamento->uee_code ?? null)));
                                                    $cardUeeNte =
                                                        $last_encaminhamento->uee_nte ??
                                                        ($last_encaminhamento->nte ??
                                                            ($last_encaminhamento->nte_nome ?? null));
                                                    $cardUeeMunicipio =
                                                        $last_encaminhamento->uee_municipio ??
                                                        ($last_encaminhamento->municipio ??
                                                            ($last_encaminhamento->municipio_nome ?? null));
                                                    $cardUeeCodigo =
                                                        $last_encaminhamento->uee_codigo ??
                                                        ($last_encaminhamento->cod_unidade ??
                                                            ($last_encaminhamento->uee_code ?? null));
                                                    $cardDisciplinaName =
                                                        $last_encaminhamento->disciplina_name ??
                                                        ($last_encaminhamento->disciplina_nome ?? null);
                                                    $cardMat = intval(
                                                        $last_encaminhamento->quant_matutino ??
                                                            ($last_encaminhamento->matutino ?? 0),
                                                    );
                                                    $cardVes = intval(
                                                        $last_encaminhamento->quant_vespertino ??
                                                            ($last_encaminhamento->vespertino ?? 0),
                                                    );
                                                    $cardNot = intval(
                                                        $last_encaminhamento->quant_noturno ??
                                                            ($last_encaminhamento->noturno ?? 0),
                                                    );
                                                    // prefer explicit motivo when present, otherwise keep existing tipo fields
                                                    $cardTipoText =
                                                        $last_encaminhamento->motivo ??
                                                        ($last_encaminhamento->tipo_encaminhamento ??
                                                            ($last_encaminhamento->tipo ?? null));
                                                    $cardObservacao = $last_encaminhamento->observacao ?? null;
                                                    $cardStatus = $last_encaminhamento->status ?? null;
                                                }
                                                // server-side flag to mark form read-only when validated
                                                $isValidated =
                                                    isset($cardStatus) &&
                                                    mb_stripos($cardStatus, 'Encaminhamento validado') !== false;
                                            @endphp
                                            <div class="card-label">Unidade selecionada</div>
                                            <div id="candidateUeeName" class="unit-name">{{ $cardUeeName ?? '-' }}</div>

                                            <div class="row mt-3">
                                                <div class="col-4">
                                                    <div class="card-label">NTE</div>
                                                    <div id="candidateUeeNte" class="meta">{{ $cardUeeNte ?? '-' }}</div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="card-label">Município</div>
                                                    <div id="candidateUeeMunicipio" class="meta">
                                                        {{ $cardUeeMunicipio ?? '-' }}</div>
                                                </div>
                                                <div class="col-4 text-end">
                                                    <div class="card-label">Código</div>
                                                    <div id="candidateUeeCodigo" class="meta">{{ $cardUeeCodigo ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="card-label">Disciplina selecionada</div>
                                            <div id="candidateDisciplina" class="meta">{{ $cardDisciplinaName ?? '-' }}
                                            </div>

                                            @php
                                                $turnosText =
                                                    'Mat: ' .
                                                    ($cardMat ?? 0) .
                                                    ' · Ves: ' .
                                                    ($cardVes ?? 0) .
                                                    ' · Not: ' .
                                                    ($cardNot ?? 0);
                                                $tipoMap = [
                                                    'substituicao_reda' => 'Substituição de reda',
                                                    'vaga_real' => 'Vaga real',
                                                    'vaga_temporaria' => 'Vaga temporária',
                                                ];
                                                $cardTipoLabel =
                                                    $tipoMap[$cardTipoText] ?? ($cardTipoText ? $cardTipoText : '-');
                                            @endphp

                                            <div class="d-flex justify-content-between align-items-start mt-3">
                                                <div>
                                                    <div class="card-label">Turnos</div>
                                                    <div id="candidateTurnos" class="muted">{{ $turnosText }}</div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="card-label">Tipo de encaminhamento</div>
                                                    <div id="candidateTipo" class="muted">{{ $cardTipoLabel }}</div>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="card-label">Status do encaminhamento</div>
                                                <div id="candidateStatus" class="muted">
                                                    @if (is_null($cardStatus))
                                                        <span class="text-danger fw-bold">Pendente Validação</span>
                                                    @else
                                                        <span class="text-white fw-bold">{{ $cardStatus }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <hr class="mt-3">
                                            <div class="card-label">Observação</div>
                                            <div id="candidateObservacao" class="muted small" style="white-space:pre-wrap;">
                                                {{ $cardObservacao ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <form id="encaminharForm" class="needs-validation" novalidate>
                                    @csrf
                                    <div class="row gx-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Unidade Escolar</label>
                                            <select id="uee_id" name="uee_id"
                                                class="form-control form-control-sm select2"
                                                data-placeholder="Selecione a unidade escolar" aria-label="Unidade Escolar"
                                                required @if (!empty($isValidated)) disabled @endif>
                                                <option value="">(Selecione)</option>
                                                @foreach ($uees as $u)
                                                    @php
                                                        $optVal = (string) ($u->cod_unidade ?? $u->id);
                                                        $selectedUee = false;
                                                        if (isset($last_encaminhamento) && $last_encaminhamento) {
                                                            $lkCode = trim(
                                                                (string) ($last_encaminhamento->uee_codigo ??
                                                                    ($last_encaminhamento->cod_unidade ??
                                                                        ($last_encaminhamento->uee_code ??
                                                                            ($last_encaminhamento->uee_id ?? '')))),
                                                            );
                                                            $lkName = trim(
                                                                (string) ($last_encaminhamento->uee_name ??
                                                                    ($last_encaminhamento->uee_nome ?? '')),
                                                            );
                                                            if ($lkCode !== '' && $optVal === (string) $lkCode) {
                                                                $selectedUee = true;
                                                            }
                                                            if (!$selectedUee && $lkName !== '') {
                                                                if (
                                                                    mb_stripos($u->unidade_escolar ?? '', $lkName) !==
                                                                        false ||
                                                                    mb_stripos($lkName, $u->unidade_escolar ?? '') !==
                                                                        false
                                                                ) {
                                                                    $selectedUee = true;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <option value="{{ $optVal }}"
                                                        data-name="{{ $u->unidade_escolar }}"
                                                        data-nte="{{ $u->nte ?? ($u->nte_nome ?? '') }}"
                                                        data-municipio="{{ $u->municipio ?? ($u->municipio_nome ?? '') }}"
                                                        data-codigo="{{ $u->cod_unidade ?? $u->id }}"
                                                        @if ($selectedUee) selected @endif>
                                                        {{ $u->unidade_escolar }} ({{ $u->cod_unidade ?? $u->id }})
                                                    </option>
                                                @endforeach
                                            </select>

                                            @if (isset($uees) && $uees instanceof \Illuminate\Support\Collection && $uees->isEmpty())
                                                <div class="small text-danger mt-2">Nenhuma unidade escolar encontrada no
                                                    servidor. Verifique o log em <code>storage/logs/laravel.log</code> para
                                                    erros.</div>
                                            @endif

                                            @php
                                                $showUeeInfo = false;
                                                $ueeNteVal = '';
                                                $ueeMunVal = '';
                                                $ueeCodVal = '';
                                                if (isset($last_encaminhamento) && $last_encaminhamento) {
                                                    $ueeNteVal =
                                                        $last_encaminhamento->uee_nte ??
                                                        ($last_encaminhamento->nte ??
                                                            ($last_encaminhamento->nte_nome ?? ''));
                                                    $ueeMunVal =
                                                        $last_encaminhamento->uee_municipio ??
                                                        ($last_encaminhamento->municipio ??
                                                            ($last_encaminhamento->municipio_nome ?? ''));
                                                    $ueeCodVal =
                                                        $last_encaminhamento->uee_codigo ??
                                                        ($last_encaminhamento->cod_unidade ??
                                                            ($last_encaminhamento->uee_code ?? ''));
                                                    if ($ueeNteVal || $ueeMunVal || $ueeCodVal) {
                                                        $showUeeInfo = true;
                                                    }
                                                }
                                            @endphp

                                            <div id="ueeInfo" class="mt-2 p-2 bg-light border rounded"
                                                style="@if ($showUeeInfo) display:block; @else display:none; @endif">
                                                <div class="small text-muted">NTE: <span
                                                        id="ueeInfoNte">{{ $ueeNteVal }}</span></div>
                                                <div class="small text-muted">Município: <span
                                                        id="ueeInfoMunicipio">{{ $ueeMunVal }}</span></div>
                                                <div class="small text-muted">Código: <span
                                                        id="ueeInfoCodigo">{{ $ueeCodVal }}</span></div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label class="form-label">Disciplina</label>
                                            <div id="disciplinasContainer">
                                                @if(isset($encaminhamentos) && $encaminhamentos->isNotEmpty())
                                                    @foreach($encaminhamentos as $enc)
                                                        <div class="disciplina-row mb-2">
                                                            <div class="row g-2 align-items-end">
                                                                <div class="col-md-7">
                                                                    <select name="disciplina_code[]" class="form-control form-control-sm disciplina-select select2"
                                                                        data-placeholder="Selecione a disciplina" aria-label="Disciplina" @if (!empty($isValidated)) disabled @endif>
                                                                        <option value="">(Selecione)</option>
                                                                        @foreach ($disciplinas as $d)
                                                                            @php
                                                                                $selectedDisc = false;
                                                                                $lkName = trim((string) ($enc->disciplina_name ?? ($enc->disciplina_nome ?? ($enc->disciplina ?? ''))));
                                                                                $lkCode = trim((string) ($enc->disciplina_code ?? ($enc->disciplina_id ?? '')));
                                                                                if ($lkCode !== '' && (string) $d->id === $lkCode) {
                                                                                    $selectedDisc = true;
                                                                                }
                                                                                if (!$selectedDisc && $lkName !== '') {
                                                                                    try {
                                                                                        $dnNorm = mb_strtolower(trim(\Illuminate\Support\Str::ascii($d->nome ?? '')));
                                                                                        $lkNorm = mb_strtolower(trim(\Illuminate\Support\Str::ascii($lkName)));
                                                                                        if ($dnNorm === $lkNorm) {
                                                                                            $selectedDisc = true;
                                                                                        }
                                                                                    } catch (\Throwable $e) {
                                                                                        // fallback to strict case-insensitive equality without diacritics removal
                                                                                        if (mb_strtolower(trim($d->nome ?? '')) === mb_strtolower(trim($lkName))) {
                                                                                            $selectedDisc = true;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                            <option value="{{ $d->id }}" data-name="{{ $d->nome }}" @if ($selectedDisc) selected @endif>{{ $d->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-1">
                                                                    <label class="form-label small">Mat</label>
                                                                    <input type="number" min="0" name="quant_matutino[]" class="form-control form-control-sm quant-matutino" value="{{ $enc->quant_matutino ?? 0 }}" @if (!empty($isValidated)) disabled @endif>
                                                                </div>
                                                                <div class="col-1">
                                                                    <label class="form-label small">Ves</label>
                                                                    <input type="number" min="0" name="quant_vespertino[]" class="form-control form-control-sm quant-vespertino" value="{{ $enc->quant_vespertino ?? 0 }}" @if (!empty($isValidated)) disabled @endif>
                                                                </div>
                                                                <div class="col-1">
                                                                    <label class="form-label small">Not</label>
                                                                    <input type="number" min="0" name="quant_noturno[]" class="form-control form-control-sm quant-noturno" value="{{ $enc->quant_noturno ?? 0 }}" @if (!empty($isValidated)) disabled @endif>
                                                                </div>
                                                                <div class="col-md-2 text-end">
                                                                    <button type="button" class="btn btn-sm btn-outline-primary btn-add-disciplina" @if (!empty($isValidated)) disabled @endif>Adicionar disciplina</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="disciplina-row mb-2">
                                                        <div class="row g-2 align-items-end">
                                                            <div class="col-md-7">
                                                                <select name="disciplina_code[]" class="form-control form-control-sm disciplina-select select2"
                                                                    data-placeholder="Selecione a disciplina" aria-label="Disciplina"
                                                                    required @if (!empty($isValidated)) disabled @endif>
                                                                    <option value="">(Selecione)</option>
                                                                    @foreach ($disciplinas as $d)
                                                                        @php
                                                                            $selectedDisc = false;
                                                                            if (isset($last_encaminhamento) && $last_encaminhamento) {
                                                                                $lkName = trim((string) ($last_encaminhamento->disciplina_name ?? ($last_encaminhamento->disciplina_nome ?? '')));
                                                                                $lkCode = trim((string) ($last_encaminhamento->disciplina_code ?? ($last_encaminhamento->disciplina_id ?? '')));
                                                                                if ($lkCode !== '' && (string) $d->id === $lkCode) {
                                                                                    $selectedDisc = true;
                                                                                }
                                                                                if (!$selectedDisc && $lkName !== '') {
                                                                                    try {
                                                                                        $dnNorm = mb_strtolower(trim(\Illuminate\Support\Str::ascii($d->nome ?? '')));
                                                                                        $lkNorm = mb_strtolower(trim(\Illuminate\Support\Str::ascii($lkName)));
                                                                                        if ($dnNorm === $lkNorm) {
                                                                                            $selectedDisc = true;
                                                                                        }
                                                                                    } catch (\Throwable $e) {
                                                                                        if (mb_strtolower(trim($d->nome ?? '')) === mb_strtolower(trim($lkName))) {
                                                                                            $selectedDisc = true;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <option value="{{ $d->id }}" data-name="{{ $d->nome }}" @if ($selectedDisc) selected @endif>{{ $d->nome }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-1">
                                                                <label class="form-label small">Mat</label>
                                                                <input type="number" min="0" name="quant_matutino[]" class="form-control form-control-sm quant-matutino" value="{{ $cardMat ?? 0 }}" @if (!empty($isValidated)) disabled @endif>
                                                            </div>
                                                            <div class="col-1">
                                                                <label class="form-label small">Ves</label>
                                                                <input type="number" min="0" name="quant_vespertino[]" class="form-control form-control-sm quant-vespertino" value="{{ $cardVes ?? 0 }}" @if (!empty($isValidated)) disabled @endif>
                                                            </div>
                                                            <div class="col-1">
                                                                <label class="form-label small">Not</label>
                                                                <input type="number" min="0" name="quant_noturno[]" class="form-control form-control-sm quant-noturno" value="{{ $cardNot ?? 0 }}" @if (!empty($isValidated)) disabled @endif>
                                                            </div>
                                                            <div class="col-md-2 text-end">
                                                                <button type="button" class="btn btn-sm btn-outline-primary btn-add-disciplina" @if (!empty($isValidated)) disabled @endif>Adicionar disciplina</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="hidden" name="disciplina_name" id="disciplina_name" value="{{ $cardDisciplinaName ?? '' }}">

                                            {{-- template for additional disciplina rows --}}
                                            <template id="disciplinaRowTemplate">
                                                <div class="disciplina-row mb-2">
                                                    <div class="row g-2 align-items-end">
                                                        <div class="col-md-7">
                                                            <select name="disciplina_code[]" class="form-control form-control-sm disciplina-select select2" data-placeholder="Selecione a disciplina">
                                                                <option value="">(Selecione)</option>
                                                                @foreach ($disciplinas as $d)
                                                                    <option value="{{ $d->id }}" data-name="{{ $d->nome }}">{{ $d->nome }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-1">
                                                            <label class="form-label small">Mat</label>
                                                            <input type="number" min="0" name="quant_matutino[]" class="form-control form-control-sm quant-matutino" value="0">
                                                        </div>
                                                        <div class="col-1">
                                                            <label class="form-label small">Ves</label>
                                                            <input type="number" min="0" name="quant_vespertino[]" class="form-control form-control-sm quant-vespertino" value="0">
                                                        </div>
                                                        <div class="col-1">
                                                            <label class="form-label small">Not</label>
                                                            <input type="number" min="0" name="quant_noturno[]" class="form-control form-control-sm quant-noturno" value="0">
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-disciplina">Remover</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label class="form-label">Tipo de Encaminhamento</label>
                                            <select id="tipo_encaminhamento" name="tipo_encaminhamento"
                                                class="form-control form-control-sm select2 w-100"
                                                data-placeholder="Selecione o tipo" aria-label="Tipo de Encaminhamento"
                                                @if (!empty($isValidated)) disabled @endif>
                                                <option value="">(Selecione)</option>
                                                <option value="substituicao_reda"
                                                    @if (isset($cardTipoText) && $cardTipoText === 'substituicao_reda') selected @endif>Substituição de reda
                                                </option>
                                                <option value="vaga_real"
                                                    @if (isset($cardTipoText) && $cardTipoText === 'vaga_real') selected @endif>Vaga real</option>
                                                <option value="vaga_temporaria"
                                                    @if (isset($cardTipoText) && $cardTipoText === 'vaga_temporaria') selected @endif>Vaga temporária
                                                </option>
                                            </select>
                                        </div>

                                        @php
                                            $subs = null;
                                            if (isset($last_encaminhamento) && !empty($last_encaminhamento->servidor)) {
                                                $subs = $last_encaminhamento->servidor;
                                            } elseif (isset($encaminhamentos) && $encaminhamentos instanceof \Illuminate\Support\Collection && $encaminhamentos->isNotEmpty()) {
                                                $firstEnc = $encaminhamentos->first();
                                                $subs = $firstEnc->servidor ?? null;
                                            }
                                        @endphp
                                        <div id="substituicaoBox" class="col-12 mt-3" >
                                            <label class="form-label">Buscar servidor por matrícula</label>
                                            <div class="input-group">
                                                <input type="text" id="substituicao_matricula" class="form-control" placeholder="Matrícula / cadastro">
                                                <button type="button" id="btn-substituicao-search" class="btn btn-outline-secondary">Buscar</button>
                                            </div>
                                            <input type="hidden" id="substituicao_servidor_id" name="substituicao_servidor_id" value="{{ optional($subs)->id ?? '' }}">
                                            <div id="substituicao_servidor_info" class="mt-2" >
                                                <div id="substituicao_servidor_card" class="subs-card" >
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <div id="subs_nome" class="subs-name">{{ optional($subs)->nome ?? optional($subs)->Nome ?? optional($subs)->name ?? '' }}</div>
                                                            <div class="subs-badges">
                                                                <span id="subs_cadcpf" class="badge bg-primary text-white">{{ optional($subs)->cadastro ? 'Matrícula: ' . optional($subs)->cadastro : '' }}{{ optional($subs)->cpf ? ' · CPF: ' . optional($subs)->cpf : '' }}</span>
                                                                <span id="subs_regime" class="badge bg-secondary text-white">{{ optional($subs)->regime ? ('Regime: ' . optional($subs)->regime . 'h') : '' }}</span>
                                                            </div>
                                                            <div id="subs_vinculo" class="subs-meta mt-2">{{ optional($subs)->vinculo ? ('Vínculo: ' . optional($subs)->vinculo) : '' }}</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="button" id="btn-substituicao-clear" class="btn btn-sm btn-outline-secondary">Limpar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="substituicao_servidor_msg" class="small text-muted" style="display:{{ $subs ? 'none' : 'block' }}"></div>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label for="observacao" class="form-label">Observação (opcional)</label>
                                            <textarea id="observacao" name="observacao" class="form-control" rows="3" placeholder="Observações..."
                                                @if (!empty($isValidated)) disabled @endif>{{ old('observacao', $cardObservacao ?? '') }}</textarea>
                                        </div>

                                        <div class="col-12 mt-3 d-flex justify-content-end gap-3">
                                            <button type="button" id="btn-submit-encaminhar"
                                                class="btn btn-primary btn-sm d-flex align-items-center mr-2 @if (!empty($isValidated)) hidden @endif"
                                                title="Salvar encaminhamento">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icon-tabler-device-floppy me-1"
                                                    aria-hidden="true">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                                    <path d="M10 14a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                                </svg>
                                                <span id="btnText ml-2">Salvar</span>
                                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2"
                                                    role="status" aria-hidden="true" style="display:none"></span>
                                            </button>
                                            <button type="button" id="btn-validate-encaminhar"
                                                class="btn btn-success btn-sm d-flex align-items-center mr-3"
                                                title="Validar encaminhamento">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="me-1">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M5 12l5 5l10 -10" />
                                                </svg>
                                                <span class="ml-2">Validar</span>
                                            </button>
                                            @if (!empty($isValidated) && isset($last_encaminhamento) && isset($last_encaminhamento->id))
                                                <a href="{{ url('/provimentos/encaminhamento/' . $last_encaminhamento->id) }}"
                                                    target="_blank"
                                                    class="btn btn-primary text-white btn-sm d-flex align-items-center ms-2"
                                                    title="Abrir termo para impressão">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="currentColor"
                                                        class="icon icon-tabler icons-tabler-filled icon-tabler-file-invoice">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005zm4 15h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0 -2m0 -4h-8a1 1 0 0 0 0 2h8a1 1 0 0 0 0 -2m-7 -7h-1a1 1 0 1 0 0 2h1a1 1 0 1 0 0 -2" />
                                                        <path d="M19 7h-4l-.001 -4.001z" />
                                                    </svg>
                                                    <span class="ml-2">Imprimir Termo de Encaminhamento</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // SweetAlert2 Toast helper for nicer alerts
                const Toast = (typeof Swal !== 'undefined') ? Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                }) : null;

                function swalSuccess(message, title) {
                    if (Toast) return Toast.fire({
                        icon: 'success',
                        title: title || message
                    });
                    try {
                        Swal.fire({
                            icon: 'success',
                            title: title || 'Sucesso',
                            text: message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } catch (e) {}
                }

                function swalError(message, title) {
                    try {
                        Swal.fire({
                            icon: 'error',
                            title: title || 'Erro',
                            text: message,
                            confirmButtonText: 'Fechar'
                        });
                    } catch (e) {}
                }

                // If we were redirected here after validating an encaminhamento, show centered modal once
                try {
                    const _v = sessionStorage.getItem('encaminhamento_validado_show');
                    if (_v) {
                        sessionStorage.removeItem('encaminhamento_validado_show');
                        let payload = {};
                        try {
                            payload = JSON.parse(_v);
                        } catch (e) {
                            payload = {
                                message: _v
                            };
                        }
                        Swal.fire({
                            icon: 'success',
                            title: payload.title || 'Encaminhamento validado',
                            text: payload.message || 'O encaminhamento foi validado com sucesso.'
                        });
                    }
                } catch (e) {}
                const disciplinasContainer = document.getElementById('disciplinasContainer');
                const disciplinaName = document.getElementById('disciplina_name');

                function initSelect2For(el) {
                    try {
                        if (window.jQuery && jQuery(el).select2) {
                            jQuery(el).select2({ width: '100%' });
                            try {
                                // If server rendered an <option selected>, ensure Select2 reflects it
                                const pre = el.querySelector('option[selected]');
                                if (pre && pre.value) {
                                    jQuery(el).val(pre.value).trigger('change');
                                } else if (el.value) {
                                    // otherwise re-trigger change so Select2 picks up the current value
                                    jQuery(el).val(el.value).trigger('change');
                                }
                            } catch (e) {}
                        }
                    } catch (e) {}
                    if (!el) return;
                    el.addEventListener('change', function() {
                            updateDisciplinaState();
                        });
                }

                // no-op placeholder to preserve existing call sites without overwriting server-rendered card
                function updateCandidateCard() {
                    // intentionally empty: keep card values rendered by server
                }

                function updateDisciplinaState() {
                    const rows = Array.from(disciplinasContainer.querySelectorAll('.disciplina-row'));
                    const names = [];
                    let totalMat = 0,
                        totalVes = 0,
                        totalNot = 0;
                    rows.forEach(row => {
                        const sel = row.querySelector('.disciplina-select');
                        const opt = sel && sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex] : null;
                        const name = opt ? (opt.dataset.name || opt.textContent.trim()) : '';
                        if (name) names.push(name);
                        const m = parseInt(row.querySelector('.quant-matutino')?.value || 0, 10);
                        const v = parseInt(row.querySelector('.quant-vespertino')?.value || 0, 10);
                        const n = parseInt(row.querySelector('.quant-noturno')?.value || 0, 10);
                        totalMat += isNaN(m) ? 0 : m;
                        totalVes += isNaN(v) ? 0 : v;
                        totalNot += isNaN(n) ? 0 : n;
                    });
                    disciplinaName.value = names.join(' · ');
                    const mEl = document.getElementById('quant_matutino');
                    const vEl = document.getElementById('quant_vespertino');
                    const nEl = document.getElementById('quant_noturno');
                    if (mEl) mEl.value = totalMat;
                    if (vEl) vEl.value = totalVes;
                    if (nEl) nEl.value = totalNot;
                }

                function addDisciplinaRow() {
                    const tpl = document.getElementById('disciplinaRowTemplate');
                    if (!tpl) return;
                    const node = tpl.content.firstElementChild.cloneNode(true);
                    disciplinasContainer.appendChild(node);
                    const lastSel = disciplinasContainer.querySelector('.disciplina-row:last-of-type .disciplina-select');
                    if (lastSel) initSelect2For(lastSel);
                    const removeBtn = disciplinasContainer.querySelector('.disciplina-row:last-of-type .btn-remove-disciplina');
                    if (removeBtn) removeBtn.addEventListener('click', function() {
                        this.closest('.disciplina-row').remove();
                        updateDisciplinaState();
                    });
                    updateDisciplinaState();
                }

                // wire add buttons
                Array.from(document.querySelectorAll('.btn-add-disciplina')).forEach(b => {
                    b.addEventListener('click', function() {
                        addDisciplinaRow();
                    });
                });

                // initialize existing selects
                Array.from(document.querySelectorAll('.disciplina-select')).forEach(s => {
                    initSelect2For(s);
                });

                // ensure select2 events propagate for dynamically added selects
                if (window.jQuery) {
                    jQuery(document).on('select2:select select2:unselect change', '.disciplina-select', function() {
                        updateDisciplinaState();
                    });
                }

                // initial sync
                updateDisciplinaState();

                // wire update triggers for the card
                if (document.getElementById('uee_id')) document.getElementById('uee_id').addEventListener('change', updateCandidateCard);
                ['quant_matutino', 'quant_vespertino', 'quant_noturno'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.addEventListener('input', updateCandidateCard);
                });
                // observe changes to observation textarea
                const obsField = document.getElementById('observacao');
                if (obsField) obsField.addEventListener('input', updateCandidateCard);
                if (document.getElementById('tipo_encaminhamento')) document.getElementById('tipo_encaminhamento')
                    .addEventListener('change', updateCandidateCard);
                // Support Select2 events for tipo_encaminhamento
                if (window.jQuery) {
                    jQuery(document).on('select2:select select2:unselect change', '#tipo_encaminhamento', function() {
                        updateCandidateCard();
                    });
                }

                // show/hide substituição de reda search box
                (function(){
                    const tipoEl = document.getElementById('tipo_encaminhamento');
                    const box = document.getElementById('substituicaoBox');
                    const matriculaEl = document.getElementById('substituicao_matricula');
                    const btnSearch = document.getElementById('btn-substituicao-search');
                    
                    const hid = document.getElementById('substituicao_servidor_id');

                    function clearSubstituicao() {
                        if (matriculaEl) matriculaEl.value = '';
                        if (hid) hid.value = '';
                        if (info) { info.style.display = 'none'; info.textContent = ''; }
                    }

                    function updateVisibility() {
                        if (!tipoEl) return;
                        if (tipoEl.value === 'substituicao_reda') {
                            if (box) box.style.display = '';
                        } else {
                            if (box) box.style.display = 'none';
                            clearSubstituicao();
                        }
                    }

                    if (tipoEl) {
                        tipoEl.addEventListener('change', updateVisibility);
                        try { updateVisibility(); } catch(e){}
                    }

                    if (btnSearch) {
                        btnSearch.addEventListener('click', function(){
                            const q = (matriculaEl && matriculaEl.value || '').trim();
                            if (!q) return alert('Informe a matrícula do servidor');
                            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                            fetch('/consultarServidor/' + encodeURIComponent(q), {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({})
                            }).then(r => r.json()).then(data => {
                                // ServidoreController::searchServidor returns array of matches
                                    if (Array.isArray(data) && data.length) {
                                            const s = data[0];
                                            // prefer the canonical servidor `id` when present; fall back to cadastro/cpf
                                            if (hid) hid.value = s.id || s.cadastro || s.cpf || '';
                                            // show alert with servidor id (prefer s.id)
                                            try {
                                                const subs_nome = document.getElementById('subs_nome');
                                                const subs_cadcpf = document.getElementById('subs_cadcpf');
                                                const subs_regime = document.getElementById('subs_regime');
                                                const subs_vinculo = document.getElementById('subs_vinculo');
                                                if (subs_nome) subs_nome.textContent = s.nome || s.Nome || 'Nome não informado';
                                                if (subs_cadcpf) subs_cadcpf.textContent = (s.cadastro ? ('Matrícula: ' + s.cadastro) : '') + (s.cpf ? (' · CPF: ' + s.cpf) : '');
                                                if (subs_regime) subs_regime.textContent = s.regime ? ('Regime: ' + s.regime + 'h') : '';
                                                if (subs_vinculo) subs_vinculo.textContent = s.vinculo ? ('Vínculo: ' + s.vinculo) : '';
                                            } catch (e) {}
                                        if (info) {
                                            info.style.display = '';
                                            const card = document.getElementById('substituicao_servidor_card');
                                            const msg = document.getElementById('substituicao_servidor_msg');
                                            if (msg) { msg.style.display = 'none'; msg.textContent = ''; }
                                            if (card) {
                                                document.getElementById('subs_nome').textContent = s.nome || s.Nome || 'Nome não informado';
                                                document.getElementById('subs_cadcpf').textContent = (s.cadastro ? ('Matrícula: ' + s.cadastro) : '') + (s.cpf ? (' · CPF: ' + s.cpf) : '');
                                                document.getElementById('subs_vinculo').textContent = s.vinculo ? ('Vínculo: ' + s.vinculo) : '';
                                                document.getElementById('subs_regime').textContent = s.regime ? ('Regime: ' + s.regime + 'h') : '';
                                                card.style.display = '';
                                                // wire clear button
                                                const clearBtn = document.getElementById('btn-substituicao-clear');
                                                if (clearBtn) clearBtn.addEventListener('click', function() {
                                                    if (matriculaEl) matriculaEl.value = '';
                                                    if (hid) hid.value = '';
                                                    card.style.display = 'none';
                                                });
                                            }
                                        }
                                    } else {
                                        if (info) { info.style.display = ''; info.querySelector('#substituicao_servidor_msg').style.display = ''; info.querySelector('#substituicao_servidor_msg').textContent = 'Servidor não encontrado'; }
                                        if (hid) hid.value = '';
                                    }
                            }).catch(() => {
                                if (info) { info.style.display = ''; info.textContent = 'Erro ao buscar servidor'; }
                            });
                        });
                    }
                })();

                // Ensure Select2 events also toggle the substituição box
                if (window.jQuery) {
                    jQuery(document).on('select2:select select2:unselect change', '#tipo_encaminhamento', function() {
                        try {
                            const val = jQuery(this).val();
                            const box = document.getElementById('substituicaoBox');
                            const matriculaEl = document.getElementById('substituicao_matricula');
                            const hid = document.getElementById('substituicao_servidor_id');
                            const info = document.getElementById('substituicao_servidor_info');
                            if (val === 'substituicao_reda') {
                                if (box) box.style.display = '';
                            } else {
                                if (box) box.style.display = 'none';
                                if (matriculaEl) matriculaEl.value = '';
                                if (hid) hid.value = '';
                                if (info) { info.style.display = 'none'; info.textContent = ''; }
                            }
                        } catch (e) {}
                    });
                }

                // initialize card with any pre-selected values
                updateCandidateCard();

                // No automatic preselection for UEE; only disciplina remains server-selected via option attribute

                const ueeSelect = document.getElementById('uee_id');
                const ueeInfo = document.getElementById('ueeInfo');
                const ueeInfoNte = document.getElementById('ueeInfoNte');
                const ueeInfoMunicipio = document.getElementById('ueeInfoMunicipio');
                const ueeInfoCodigo = document.getElementById('ueeInfoCodigo');
                if (ueeSelect) {
                    function handleUeeChange(el) {
                        let opt = null;
                        if (el.selectedOptions && el.selectedOptions.length) {
                            opt = el.selectedOptions[0];
                        } else if (typeof el.selectedIndex === 'number' && el.selectedIndex > -1) {
                            opt = el.options[el.selectedIndex];
                        } else {
                            const val = el.value;
                            if (val) {
                                const escaped = (window.CSS && CSS.escape) ? CSS.escape(val) : val;
                                opt = document.querySelector('#' + el.id + " option[value='" + escaped + "']");
                            }
                        }

                        let nte = opt ? (opt.dataset.nte || '') : '';
                        let municipio = opt ? (opt.dataset.municipio || '') : '';
                        const codigo = opt ? (opt.dataset.codigo || '') : '';
                        ueeInfoNte.textContent = nte;
                        ueeInfoMunicipio.textContent = municipio;
                        ueeInfoCodigo.textContent = codigo;
                        // If nte/municipio empty, try fetching from server
                        if ((!nte || !municipio) && el.value) {
                            fetch('/uees/info/' + encodeURIComponent(el.value))
                                .then(r => r.json())
                                .then(data => {
                                    if (data && data.success && data.data) {
                                        const d = data.data;
                                        if (!nte && d.nte) {
                                            ueeInfoNte.textContent = d.nte;
                                            nte = d.nte;
                                        }
                                        if (!municipio && d.municipio) {
                                            ueeInfoMunicipio.textContent = d.municipio;
                                            municipio = d.municipio;
                                        }
                                        if (!codigo && d.cod_unidade) {
                                            ueeInfoCodigo.textContent = d.cod_unidade;
                                        }
                                        if (nte || municipio || codigo) ueeInfo.style.display = '';
                                        updateCandidateCard();
                                    }
                                }).catch(() => {});
                        }
                        if (nte || municipio || codigo) {
                            ueeInfo.style.display = '';
                        } else {
                            ueeInfo.style.display = 'none';
                        }
                        updateCandidateCard();
                    }
                    ueeSelect.addEventListener('change', function() {
                        handleUeeChange(this);
                    });
                    if (window.jQuery) {
                        jQuery(document).on('select2:select select2:unselect change', '#uee_id', function() {
                            handleUeeChange(this);
                            updateCandidateCard();
                        });
                    }
                    // ensure initial UEE info is populated from the server-rendered select option
                    try { if (ueeSelect) handleUeeChange(ueeSelect); } catch (e) {}
                }

                document.getElementById('btn-submit-encaminhar').addEventListener('click', async function() {
                    const candidateId = '{{ $candidate->id ?? $candidate->num_inscricao }}';
                    const ueeId = document.getElementById('uee_id').value;
                    const ueeEl = document.getElementById('uee_id');
                    let ueeOpt = null;
                    if (ueeEl) {
                        if (ueeEl.selectedOptions && ueeEl.selectedOptions.length) ueeOpt = ueeEl
                            .selectedOptions[0];
                        else if (typeof ueeEl.selectedIndex === 'number' && ueeEl.selectedIndex > -1)
                            ueeOpt = ueeEl.options[ueeEl.selectedIndex];
                    }
                    const ueeName = ueeOpt ? (ueeOpt.dataset.name || '') : '';
                    const ueeNte = ueeOpt ? (ueeOpt.dataset.nte || '') : '';
                    const ueeMunicipio = ueeOpt ? (ueeOpt.dataset.municipio || '') : '';
                    const ueeCodigo = ueeOpt ? (ueeOpt.dataset.codigo || '') : '';
                    const observacao = document.getElementById('observacao').value;
                    const tipoEncaminhamento = (document.getElementById('tipo_encaminhamento') ? document.getElementById('tipo_encaminhamento').value : '');

                    if (!ueeId) {
                        alert('Selecione a unidade escolar');
                        return;
                    }

                    const disciplinaRows = Array.from(document.querySelectorAll('#disciplinasContainer .disciplina-row'));
                    const disciplinas = disciplinaRows.map(row => {
                        const sel = row.querySelector('.disciplina-select');
                        const id = sel ? (sel.value || '') : '';
                        const opt = sel && sel.options && sel.selectedIndex > -1 ? sel.options[sel.selectedIndex] : null;
                        const name = opt ? (opt.dataset.name || opt.textContent.trim()) : '';
                        const m = parseInt(row.querySelector('.quant-matutino')?.value || 0, 10);
                        const v = parseInt(row.querySelector('.quant-vespertino')?.value || 0, 10);
                        const n = parseInt(row.querySelector('.quant-noturno')?.value || 0, 10);
                        return {
                            disciplina_id: id,
                            disciplina_name: name,
                            quant_matutino: isNaN(m) ? 0 : m,
                            quant_vespertino: isNaN(v) ? 0 : v,
                            quant_noturno: isNaN(n) ? 0 : n
                        };
                    }).filter(d => d.disciplina_id && d.disciplina_id !== '');

                    if (!disciplinas.length) {
                        alert('Selecione ao menos uma disciplina');
                        return;
                    }

                    const payload = {
                        uee_id: ueeId,
                        uee_name: ueeName,
                        uee_nte: ueeNte,
                        uee_municipio: ueeMunicipio,
                        uee_codigo: ueeCodigo,
                        observacao: observacao,
                        disciplinas: disciplinas,
                        tipo_encaminhamento: tipoEncaminhamento,
                        substituicao_servidor_id: (document.getElementById('substituicao_servidor_id') ? document.getElementById('substituicao_servidor_id').value : ''),
                        servidor_id: (document.getElementById('substituicao_servidor_id') ? document.getElementById('substituicao_servidor_id').value : ''),
                        substituicao_servidor_nome: (document.getElementById('substituicao_matricula') ? document.getElementById('substituicao_matricula').value : '')
                    };

                    try {
                        const res = await fetch('{{ url('/ingresso') }}/' + encodeURIComponent(
                            candidateId) + '/encaminhar', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });
                        const j = await res.json();
                        if (j && j.success) {
                            try {
                                swalSuccess(j.message || 'Encaminhamento registrado', 'Encaminhado');
                            } catch (e) {}
                            // reload current page so the card reflects the saved/updated encaminhamento and remain on the same page
                            setTimeout(function() {
                                window.location.reload();
                            }, 900);
                        } else {
                            swalError((j && j.message) || 'Falha ao encaminhar', 'Erro');
                        }
                    } catch (e) {
                        swalError('Erro de comunicação ao enviar encaminhamento', 'Erro');
                    }
                });

                // Validate / Unvalidate encaminhamento (toggle) — confirmation via SweetAlert2 and show centered alert after reload
                (function() {
                    const btn = document.getElementById('btn-validate-encaminhar');
                    const candidateId = '{{ $candidate->id ?? $candidate->num_inscricao }}';

                    function isCurrentlyValidated() {
                        const candidateStatusEl = document.getElementById('candidateStatus');
                        const s = candidateStatusEl ? (candidateStatusEl.textContent || '').trim() : '';
                        return /validad/i.test(s);
                    }

                    function updateValidateButton() {
                        if (!btn) return;
                        if (isCurrentlyValidated()) {
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-danger');
                            btn.title = 'Retirar validação do encaminhamento';
                            btn.querySelector('span:last-child').textContent = ' Retirar validação';
                        } else {
                            btn.classList.remove('btn-danger');
                            btn.classList.add('btn-success');
                            btn.title = 'Validar encaminhamento';
                            btn.querySelector('span:last-child').textContent = 'Validar';
                        }
                    }

                    async function sendStatusUpdate(newStatus, postMessage, postTitle) {
                        try {
                            const res = await fetch('{{ url('/ingresso') }}/' + encodeURIComponent(
                                candidateId) + '/encaminhar/status', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        status: newStatus
                                    })
                                });
                            const j = await res.json();
                            if (j && j.success) {
                                try {
                                    const titleToStore = postTitle || (newStatus ? (j.message ||
                                        'Encaminhamento validado') : 'Validação removida');
                                    sessionStorage.setItem('encaminhamento_validado_show', JSON.stringify({
                                        title: titleToStore,
                                        message: postMessage || j.message || 'Operação realizada.'
                                    }));
                                } catch (e) {}
                                window.location.reload();
                            } else {
                                swalError((j && j.message) || 'Falha ao atualizar status', 'Erro');
                            }
                        } catch (e) {
                            swalError('Erro de comunicação ao atualizar status', 'Erro');
                        }
                    }

                    if (btn) {
                        btn.addEventListener('click', function() {
                            if (!isCurrentlyValidated()) {
                                Swal.fire({
                                    title: 'Confirmar validação',
                                    text: 'Marcar este encaminhamento como "Encaminhamento validado"?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sim, validar',
                                    cancelButtonText: 'Cancelar',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (!result.isConfirmed) return;
                                    sendStatusUpdate('Encaminhamento validado',
                                        'O encaminhamento foi validado.',
                                        'Encaminhamento validado');
                                });
                            } else {
                                Swal.fire({
                                    title: 'Retirar validação',
                                    text: 'Remover a validação deste encaminhamento? Esta ação pode ser revertida marcando-o novamente.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sim, retirar validação',
                                    cancelButtonText: 'Cancelar',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (!result.isConfirmed) return;
                                    // send null to clear status
                                    sendStatusUpdate(null, 'Validação removida',
                                        'Validação removida');
                                });
                            }
                        });
                        // ensure button is in correct state on load
                        updateValidateButton();
                    }
                })();
            });
        </script>
    @endpush

@endsection


