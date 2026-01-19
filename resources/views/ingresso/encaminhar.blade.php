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
    </style>
    <div class="container-fluid ingresso-vh py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @php
                    // Ensure $cardStatus exists before the header uses it (avoid undefined variable)
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
                                    @if (isset($cardStatus) && str_contains(mb_strtolower($cardStatus), mb_strtolower('Encaminhamento validado')))
                                        <span
                                            class="badge bg-success text-white fw-bold"><strong>{{ $cardStatus }}</strong></span>
                                    @elseif(is_null($cardStatus))
                                        <span class="badge bg-danger text-white fw-bold"><strong>Pendente
                                                Validação</strong></span>
                                    @else
                                        <span
                                            class="badge bg-danger text-white fw-bold"><strong>{{ $cardStatus }}</strong></span>
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
                                            <select id="disciplina_code" name="disciplina_code"
                                                class="form-control form-control-sm select2"
                                                data-placeholder="Selecione a disciplina" aria-label="Disciplina" required
                                                @if (!empty($isValidated)) disabled @endif>
                                                <option value="">(Selecione)</option>
                                                @foreach ($disciplinas as $d)
                                                    @php
                                                        $selectedDisc = false;
                                                        if (isset($last_encaminhamento) && $last_encaminhamento) {
                                                            $lkName = trim(
                                                                (string) ($last_encaminhamento->disciplina_name ??
                                                                    ($last_encaminhamento->disciplina_nome ?? '')),
                                                            );
                                                            $lkCode = trim(
                                                                (string) ($last_encaminhamento->disciplina_code ??
                                                                    ($last_encaminhamento->disciplina_id ?? '')),
                                                            );
                                                            // match by id/code
                                                            if ($lkCode !== '' && (string) $d->id === $lkCode) {
                                                                $selectedDisc = true;
                                                            }
                                                            // match by name (loose)
                                                            if (!$selectedDisc && $lkName !== '') {
                                                                if (
                                                                    mb_stripos($d->nome ?? '', $lkName) !== false ||
                                                                    mb_stripos($lkName, $d->nome ?? '') !== false
                                                                ) {
                                                                    $selectedDisc = true;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <option value="{{ $d->id }}" data-name="{{ $d->nome }}"
                                                        @if ($selectedDisc) selected @endif>
                                                        {{ $d->nome }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="disciplina_name" id="disciplina_name"
                                                value="{{ $cardDisciplinaName ?? '' }}">
                                        </div>


                                        <div class="col-md-12 mt-3" id="turnosWrap"
                                            style="@if (isset($cardDisciplinaName) && $cardDisciplinaName) display:block; @else display:none; @endif">
                                            <div class="row g-2">
                                                <div class="col-4">
                                                    <label class="form-label">Matutino</label>
                                                    <input type="number" min="0" id="quant_matutino"
                                                        name="quant_matutino" class="form-control form-control-sm"
                                                        value="{{ $cardMat ?? 0 }}"
                                                        @if (!empty($isValidated)) disabled @endif>
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label">Vespertino</label>
                                                    <input type="number" min="0" id="quant_vespertino"
                                                        name="quant_vespertino" class="form-control form-control-sm"
                                                        value="{{ $cardVes ?? 0 }}"
                                                        @if (!empty($isValidated)) disabled @endif>
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label">Noturno</label>
                                                    <input type="number" min="0" id="quant_noturno"
                                                        name="quant_noturno" class="form-control form-control-sm"
                                                        value="{{ $cardNot ?? 0 }}"
                                                        @if (!empty($isValidated)) disabled @endif>
                                                </div>
                                            </div>
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
                const disciplinaSelect = document.getElementById('disciplina_code');
                const disciplinaName = document.getElementById('disciplina_name');

                function handleDisciplinaChange(el) {
                    const opt = el.options[el.selectedIndex];
                    disciplinaName.value = opt ? (opt.dataset.name || '') : '';
                    const turnosWrap = document.getElementById('turnosWrap');
                    if (turnosWrap) {
                        if (el.value) turnosWrap.style.display = '';
                        else turnosWrap.style.display = 'none';
                    }
                }
                if (disciplinaSelect) {
                    disciplinaSelect.addEventListener('change', function() {
                        handleDisciplinaChange(this);
                        updateCandidateCard();
                    });
                    // Support Select2 events even if Select2 initializes later
                    if (window.jQuery) {
                        jQuery(document).on('select2:select select2:unselect change', '#disciplina_code', function() {
                            handleDisciplinaChange(this);
                            updateCandidateCard();
                        });
                    }
                }

                // Update candidate card with current selections
                function updateCandidateCard() {
                    // UEE
                    const ueeEl = document.getElementById('uee_id');
                    let ueeOpt = null;
                    if (ueeEl) {
                        if (ueeEl.selectedOptions && ueeEl.selectedOptions.length) ueeOpt = ueeEl.selectedOptions[0];
                        else if (typeof ueeEl.selectedIndex === 'number' && ueeEl.selectedIndex > -1) ueeOpt = ueeEl
                            .options[ueeEl.selectedIndex];
                    }
                    // prefer the data-name attribute only; do not fallback to option textContent
                    // to avoid placeholder text like "(Selecione)" overwriting server-rendered values
                    const ueeName = ueeOpt ? (ueeOpt.dataset.name || '') : '';
                    let ueeNte = ueeOpt ? (ueeOpt.dataset.nte || '') : '';
                    if (!ueeNte) {
                        const infoNte = document.getElementById('ueeInfoNte');
                        if (infoNte && infoNte.textContent) ueeNte = infoNte.textContent.trim();
                    }
                    const ueeCodigo = ueeOpt ? (ueeOpt.dataset.codigo || '') : (ueeEl ? ueeEl.value : '');
                    const candidateUeeName = document.getElementById('candidateUeeName');
                    const candidateUeeNte = document.getElementById('candidateUeeNte');
                    const candidateUeeMunicipio = document.getElementById('candidateUeeMunicipio');
                    const candidateUeeCodigo = document.getElementById('candidateUeeCodigo');
                    if (candidateUeeName && ueeName) candidateUeeName.textContent = ueeName;
                    if (candidateUeeNte && ueeNte) candidateUeeNte.textContent = ueeNte;
                    // municipio: prefer option dataset, fallback to the ueeInfo display (populated by fetch)
                    let ueeMunicipio = ueeOpt ? (ueeOpt.dataset.municipio || '') : '';
                    if (!ueeMunicipio) {
                        const infoMun = document.getElementById('ueeInfoMunicipio');
                        if (infoMun && infoMun.textContent) ueeMunicipio = infoMun.textContent.trim();
                    }
                    if (candidateUeeMunicipio && ueeMunicipio) candidateUeeMunicipio.textContent = ueeMunicipio;
                    if (candidateUeeCodigo && ueeCodigo) candidateUeeCodigo.textContent = ueeCodigo;

                    // Disciplina
                    const disciplinaNameVal = (document.getElementById('disciplina_name') ? document.getElementById(
                        'disciplina_name').value : '');
                    const candidateDisciplina = document.getElementById('candidateDisciplina');
                    if (candidateDisciplina && disciplinaNameVal) candidateDisciplina.textContent = disciplinaNameVal;

                    // Turnos
                    const mEl = document.getElementById('quant_matutino');
                    const vEl = document.getElementById('quant_vespertino');
                    const nEl = document.getElementById('quant_noturno');
                    const m = mEl ? parseInt(mEl.value || 0, 10) : 0;
                    const v = vEl ? parseInt(vEl.value || 0, 10) : 0;
                    const n = nEl ? parseInt(nEl.value || 0, 10) : 0;
                    const candidateTurnos = document.getElementById('candidateTurnos');
                    if (candidateTurnos) candidateTurnos.textContent = `Mat: ${m} · Ves: ${v} · Not: ${n}`;

                    // Tipo
                    const tipoEl = document.getElementById('tipo_encaminhamento');
                    const candidateTipo = document.getElementById('candidateTipo');
                    if (candidateTipo) {
                        const tipoText = tipoEl ? (tipoEl.options[tipoEl.selectedIndex] ? tipoEl.options[tipoEl
                            .selectedIndex].text : '') : '';
                        if (tipoText) candidateTipo.textContent = tipoText;
                    }

                    // Observação
                    const obsEl = document.getElementById('observacao');
                    const candidateObservacao = document.getElementById('candidateObservacao');
                    if (candidateObservacao) {
                        const obsText = obsEl ? (obsEl.value || '') : '';
                        if (obsText) candidateObservacao.textContent = obsText;
                    }

                    // Ensure status text is emphasized and sync header badge
                    try {
                        const candidateStatusEl = document.getElementById('candidateStatus');
                        let statusText = '';
                        if (candidateStatusEl) {
                            statusText = (candidateStatusEl.textContent || '').trim();
                        }
                        const mainCardStatus = document.getElementById('mainCardStatus');
                        // Pending (no status)
                        if (!statusText || statusText === '-' || statusText.toLowerCase() === 'null') {
                            if (candidateStatusEl) candidateStatusEl.innerHTML =
                                '<span class="text-danger fw-bold">Pendente Validação</span>';
                            if (mainCardStatus) mainCardStatus.innerHTML =
                                '<span class="badge bg-danger text-white fw-bold">Pendente Validação</span>';
                        } else if (/validad/i.test(statusText)) {
                            if (candidateStatusEl) candidateStatusEl.innerHTML = '<span class="text-white fw-bold">' +
                                statusText + '</span>';
                            if (mainCardStatus) mainCardStatus.innerHTML =
                                '<span class="badge bg-success text-white fw-bold">' + statusText + '</span>';
                        } else {
                            if (candidateStatusEl) candidateStatusEl.innerHTML = '<span class="text-white fw-bold">' +
                                statusText + '</span>';
                            if (mainCardStatus) mainCardStatus.innerHTML =
                                '<span class="badge bg-secondary text-white fw-bold">' + statusText + '</span>';
                        }
                    } catch (e) {}
                }

                // wire update triggers for the card
                if (document.getElementById('uee_id')) document.getElementById('uee_id').addEventListener('change',
                    updateCandidateCard);
                if (document.getElementById('disciplina_code')) document.getElementById('disciplina_code')
                    .addEventListener('change', function() {
                        handleDisciplinaChange(this);
                        updateCandidateCard();
                    });
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
                    const disciplinaCode = document.getElementById('disciplina_code').value;
                    const disciplinaNameVal = document.getElementById('disciplina_name').value;
                    const observacao = document.getElementById('observacao').value;
                    const tipoEncaminhamento = (document.getElementById('tipo_encaminhamento') ? document
                        .getElementById('tipo_encaminhamento').value : '');

                    if (!ueeId) {
                        alert('Selecione a unidade escolar');
                        return;
                    }
                    if (!disciplinaCode) {
                        alert('Selecione a disciplina');
                        return;
                    }

                    const quantMat = parseInt(document.getElementById('quant_matutino').value || 0, 10);
                    const quantVes = parseInt(document.getElementById('quant_vespertino').value || 0, 10);
                    const quantNot = parseInt(document.getElementById('quant_noturno').value || 0, 10);

                    const payload = {
                        uee_id: ueeId,
                        uee_name: ueeName,
                        uee_nte: ueeNte,
                        uee_municipio: ueeMunicipio,
                        uee_codigo: ueeCodigo,
                        observacao: observacao,
                        disciplinas: [{
                            disciplina_id: disciplinaCode,
                            disciplina_name: disciplinaNameVal,
                            quant_matutino: quantMat,
                            quant_vespertino: quantVes,
                            quant_noturno: quantNot
                        }],
                        tipo_encaminhamento: tipoEncaminhamento
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
