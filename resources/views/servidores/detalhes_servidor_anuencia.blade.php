@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')

    <div class="card p-2 shadow">

        <div class="card-header d-flex align-items-center justify-content-between bg-primary text-white">
            <div>
                <h4 class="mb-0" style="font-weight:700;letter-spacing:0.2px;">DETALHES DO SUPRIMENTO DO SERVIDOR</h3>
                    <small class="text-white-50">Visualize e atualize informações relacionadas ao provimento</small>
            </div>

            <div class="text-right">
                <a href="{{ route('provimentos.validarDocs') }}"
                    class="btn btn-light shadow-sm d-inline-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back-up">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="card mt-3 mb-4 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto pr-3">
                        @php
                            $nome = trim($servidor->nome ?? '');
                            $parts = preg_split('/\s+/', $nome, -1, PREG_SPLIT_NO_EMPTY);
                            $first = $parts[0] ?? '';
                            $last = count($parts) > 1 ? $parts[count($parts) - 1] : '';
                            $initials = strtoupper(substr($first, 0, 1) . ($last ? substr($last, 0, 1) : ''));
                            if ($initials === '') {
                                $initials = 'S';
                            }
                        @endphp

                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width:64px;height:64px;font-size:22px;font-weight:700;color:#fff;
                                    background: linear-gradient(135deg,#5561ff 0%,#6f42c1 60%);
                                    box-shadow: 0 6px 18px rgba(95,62,179,0.12); user-select: none;">
                            {{ $initials }}
                        </div>
                    </div>

                    <div class="col">
                        <div class="d-flex justify-content-between align-items-start w-100">
                            <div class="pr-2">
                                <h5 class="mb-0" style="font-weight:700;letter-spacing:0.2px;color:#222;">
                                    {{ $servidor->nome ?? '---' }}
                                </h5>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-id-card mr-1"></i>
                                    MATRÍCULA / CPF:
                                    <span class="font-weight-bold text-dark ml-1">{{ $servidor->cadastro ?? '---' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2 d-flex flex-wrap align-items-center">
                            <span class="badge badge-pill badge-light mr-2 mb-1"
                                style="border:1px solid rgba(0,0,0,0.06);font-weight:600;">
                                <i class="fas fa-user-tie mr-1"></i> {{ $servidor->vinculo ?? '---' }}
                            </span>

                            <span class="badge badge-pill badge-light mr-2 mb-1"
                                style="border:1px solid rgba(0,0,0,0.06);font-weight:600;">
                                <i class="fas fa-clock mr-1"></i> {{ $servidor->regime ?? '0' }}h
                            </span>

                            @if (!empty($servidor->cod_unidade))
                                <span class="badge badge-pill badge-secondary mr-2 mb-1"
                                    style="background:#f1f3f5;color:#333;border:none;">
                                    <i class="fas fa-school mr-1"></i> {{ $servidor->cod_unidade }}
                                </span>
                            @endif

                            <small class="text-muted ml-2 mb-1 d-block d-sm-inline">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span class="d-none d-md-inline">Informações rápidas</span>
                            </small>
                        </div>
                    </div>

                    <div class="col-auto pl-2">
                        <div class="btn-group-vertical" role="group" aria-label="Ações do servidor">
                            <a class="btn btn-gradient-primary mb-2 shadow-sm d-flex align-items-center justify-content-center"
                                data-toggle="collapse" href="#validarDocs" role="button" aria-expanded="false"
                                aria-controls="validarDocs">
                                <span class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" />
                                        <path d="M14 19l2 2l4 -4" />
                                        <path d="M9 8h4" />
                                        <path d="M9 12h2" />
                                    </svg>
                                    <span>ASSUNÇÃO</span>
                                </span>
                            </a>

                            <a class="btn btn-gradient-success shadow-sm d-flex align-items-center justify-content-center"
                                data-toggle="collapse" href="#validarAssuncao" role="button" aria-expanded="false"
                                aria-controls="validarAssuncao">
                                <span class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5" />
                                        <path d="M9 9h1" />
                                        <path d="M9 13h6" />
                                        <path d="M9 17h3" />
                                        <path
                                            d="M19 22.5a4.75 4.75 0 0 1 3.5 -3.5a4.75 4.75 0 0 1 -3.5 -3.5a4.75 4.75 0 0 1 -3.5 3.5a4.75 4.75 0 0 1 3.5 3.5" />
                                    </svg>
                                    <span>COP</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mb-3">
            <table class="table table-sm table-hover table-striped table-bordered mb-0">
                <thead class="bg-primary text-white small">
                    <tr>
                        <th class="text-center" scope="col">NTE</th>
                        <th scope="col">UNIDADE ESCOLAR</th>
                        <th class="text-center" scope="col">COD.UE</th>
                        <th scope="col">DISCIPLINA</th>
                        <th class="text-center" scope="col">MAT</th>
                        <th class="text-center" scope="col">VESP</th>
                        <th class="text-center" scope="col">NOT</th>
                        <th class="text-center" scope="col">TOTAL</th>
                        <th class="text-center" scope="col">FORMA DE PROVIMENTO</th>
                        <th class="text-center" scope="col">TIPO DE MOVIMENTO</th>
                        <th class="text-center" scope="col">SITUAÇÃO</th>
                        <th class="text-center" scope="col">DATA DE ENCAMINHAMENTO</th>
                        <th class="text-center" scope="col">ASSUNÇÃO</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @foreach ($provimentosByServidor as $servidor)
                        <tr>
                            <td class="text-center align-middle text-nowrap">
                                {{ sprintf('%02d', $servidor->nte ?? 0) }}
                            </td>

                            <td class="align-middle text-truncate" style="max-width:320px;"
                                title="{{ $servidor->unidade_escolar }}">
                                {{ $servidor->unidade_escolar }}
                            </td>

                            <td class="text-center align-middle text-nowrap">
                                {{ $servidor->cod_unidade }}
                            </td>

                            <td class="align-middle text-truncate" style="max-width:220px;"
                                title="{{ $servidor->disciplina }}">
                                {{ $servidor->disciplina }}
                            </td>

                            <td class="text-center align-middle font-weight-bold text-nowrap">
                                {{ $servidor->provimento_matutino ?? 0 }}
                            </td>
                            <td class="text-center align-middle font-weight-bold text-nowrap">
                                {{ $servidor->provimento_vespertino ?? 0 }}
                            </td>
                            <td class="text-center align-middle font-weight-bold text-nowrap">
                                {{ $servidor->provimento_noturno ?? 0 }}
                            </td>
                            <td class="text-center align-middle font-weight-bold text-nowrap">
                                {{ $servidor->total ?? $servidor->provimento_matutino + $servidor->provimento_vespertino + $servidor->provimento_noturno }}
                            </td>

                            <td class="align-middle text-center text-truncate" style="max-width:160px;"
                                title="{{ $servidor->forma_suprimento }}">
                                <small class="text-muted">{{ $servidor->forma_suprimento ?? '—' }}</small>
                            </td>

                            <td class="align-middle text-center text-truncate" style="max-width:160px;"
                                title="{{ $servidor->tipo_movimentacao }}">
                                <small class="text-muted">{{ $servidor->tipo_movimentacao ?? '—' }}</small>
                            </td>

                            <td class="text-center align-middle">
                                @if ($servidor->situacao_provimento === 'provida')
                                    <span class="badge badge-success">PROVIDO</span>
                                @else
                                    <span class="badge badge-warning">TRÂMITE</span>
                                @endif
                            </td>

                            <td class="text-center align-middle text-nowrap">
                                @if (!empty($servidor->data_encaminhamento))
                                    {{ \Carbon\Carbon::parse($servidor->data_encaminhamento)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td class="text-center align-middle text-nowrap">
                                @if ($servidor->situacao_provimento === 'provida' && !empty($servidor->data_assuncao))
                                    {{ \Carbon\Carbon::parse($servidor->data_assuncao)->format('d/m/Y') }}
                                @elseif ($servidor->situacao_provimento === 'tramite')
                                    <small class="text-muted">PENDENTE</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="collapse @if ($provimento->arquivo_comprobatorio) show @endif" id="validarDocs">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between bg-light">
                    <div>
                        <h6 class="mb-0" style="font-weight:700;letter-spacing:0.2px;">Validar Documentos</h6>
                        <small class="text-muted">Atualize situação, datas e anexe o termo de assunção quando
                            necessário.</small>
                    </div>
                </div>

                <div class="card-body">
                    <form action="/update/atualizarAssuncao" method="POST" enctype="multipart/form-data"
                        class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="servidor_cadastro" value="{{ $servidor->cadastro }}">
                        <input type="hidden" name="provimento_id" value="{{ $provimento->id ?? '' }}">
                        <input type="hidden" name="cod_unidade" value="{{ $provimento->cod_unidade ?? '' }}">

                        <div class="form-row align-items-end">
                            <div class="col-12 col-sm-6 col-md-3 mb-2">
                                <label for="situacao_provimento_detail" class="small font-weight-bold">Situação do
                                    provimento</label>
                                <select name="situacao_provimento" id="situacao_provimento_detail"
                                    class="form-control form-control-sm">
                                    <option value="tramite"
                                        {{ old('situacao_provimento', $provimento->situacao_provimento) == 'tramite' ? 'selected' : '' }}>
                                        TRÂMITE</option>
                                    <option value="provida"
                                        {{ old('situacao_provimento', $provimento->situacao_provimento) == 'provida' ? 'selected' : '' }}>
                                        PROVIDA</option>
                                </select>
                            </div>

                            <div id="data_encaminhamento_col" class="col-6 col-sm-4 col-md-3 mb-2"
                                style="display: {{ $provimento->situacao_provimento === 'tramite' ? 'block' : 'none' }};">
                                <label for="data_encaminhamento" class="small font-weight-bold">Data de
                                    Encaminhamento</label>
                                <input type="date"
                                    name="{{ $provimento->situacao_provimento === 'tramite' ? 'data_encaminhamento' : '' }}"
                                    id="data_encaminhamento"
                                    value="{{ old('data_encaminhamento', optional($provimento->data_encaminhamento ? \Carbon\Carbon::parse($provimento->data_encaminhamento) : null)->format('Y-m-d')) }}"
                                    class="form-control form-control-sm"
                                    {{ $provimento->situacao_provimento === 'tramite' ? 'required' : '' }}>
                            </div>

                            <div id="data_assuncao_col" class="col-6 col-sm-4 col-md-3 mb-2"
                                style="display: {{ $provimento->situacao_provimento === 'provida' ? 'block' : 'none' }};">
                                <label for="data_assuncao" class="small font-weight-bold">Data de Assunção</label>
                                <input type="date"
                                    name="{{ $provimento->situacao_provimento === 'provida' ? 'data_assuncao' : '' }}"
                                    id="data_assuncao"
                                    value="{{ old('data_assuncao', optional($provimento->data_assuncao ? \Carbon\Carbon::parse($provimento->data_assuncao) : null)->format('Y-m-d')) }}"
                                    class="form-control form-control-sm"
                                    {{ $provimento->situacao_provimento === 'provida' ? 'required' : '' }}>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="form-row">
                            <div id="arquivo_comprobatorio_row" class="col-12 col-md-6 mb-3"
                                style="display: {{ $provimento->situacao_provimento === 'provida' ? 'block' : 'none' }};">
                                <label class="small font-weight-bold d-block mb-2">Termo de Assunção <span
                                        class="text-danger">*</span></label>

                                <div class="input-group input-group-sm mb-2">
                                    <div class="custom-file">
                                        <input type="file" id="arquivo_comprobatorio" class="custom-file-input"
                                            accept=".pdf,.jpg,.jpeg"
                                            {{ $provimento->situacao_provimento === 'provida' ? 'name=arquivo_comprobatorio' : 'disabled' }}>
                                        <label class="custom-file-label" for="arquivo_comprobatorio">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icon-tabler-upload mr-1"
                                                style="vertical-align:middle;width:14px;height:14px;margin-right:6px;">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                <path d="M7 9l5 -5l5 5" />
                                                <path d="M12 4l0 12" />
                                            </svg> Escolher arquivo...</label>
                                    </div>

                                </div>

                                <small class="form-text text-muted mb-2">Formatos aceitos: PDF, JPG. Máx. 5MB. Use nomes
                                    curtos para facilitar downloads.</small>

                                @if ($provimento->arquivo_comprobatorio)
                                    <div class="card border-0 bg-light p-2 small">
                                        <div class="d-flex align-items-center">
                                            <i
                                                class="fas {{ Str::endsWith(strtolower($provimento->arquivo_comprobatorio), '.pdf') ? 'fa-file-pdf text-danger' : 'fa-file-image text-primary' }} fa-lg mr-2"></i>
                                            <div class="text-truncate" style="max-width:280px;">
                                                <strong>Arquivo atual</strong>
                                                <div class="text-muted">
                                                    {{ Str::limit($provimento->arquivo_comprobatorio, 60) }}</div>
                                            </div>
                                            <div class="ml-auto">
                                                @if ($provimento->arquivo_comprobatorio)
                                                    <div class="input-group-append">
                                                        <a href="{{ route('provimento.arquivo', $provimento->arquivo_comprobatorio) }}"
                                                            target="_blank" class="btn btn-outline-primary"
                                                            title="Abrir" data-toggle="tooltip">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                        <a href="{{ route('provimento.arquivo', $provimento->arquivo_comprobatorio) }}"
                                                            download class="ml-2 btn btn-outline-secondary" title="Baixar"
                                                            data-toggle="tooltip">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @error('arquivo_comprobatorio')
                                    <div class="invalid-feedback d-block mt-2 small">
                                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary  mr-2">
                                <i class="fas fa-save mr-1"></i> Salvar
                            </button>
                            <a href="{{ route('provimentos.validarDocs') }}" class="btn btn-outline-secondary ">
                                <i class="fas fa-times mr-1"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                (function() {
                    const situacao = document.getElementById('situacao_provimento_detail');
                    const arquivo = document.getElementById('arquivo_comprobatorio');
                    const arquivoRow = document.getElementById('arquivo_comprobatorio_row');
                    const dataEncCol = document.getElementById('data_encaminhamento_col');
                    const dataAssCol = document.getElementById('data_assuncao_col');
                    const dataEnc = document.getElementById('data_encaminhamento');
                    const dataAss = document.getElementById('data_assuncao');

                    function fmtBytes(bytes) {
                        if (!bytes) return '0 B';
                        const units = ['B', 'KB', 'MB', 'GB'];
                        let i = 0;
                        while (bytes >= 1024 && i < units.length - 1) {
                            bytes /= 1024;
                            i++;
                        }
                        return `${bytes.toFixed(2)} ${units[i]}`;
                    }

                    function updateFields() {
                        const isProvida = situacao && situacao.value === 'provida';

                        if (arquivoRow) arquivoRow.style.display = isProvida ? 'block' : 'none';
                        if (dataAssCol) dataAssCol.style.display = isProvida ? 'block' : 'none';
                        if (dataEncCol) dataEncCol.style.display = isProvida ? 'none' : 'block';

                        if (arquivo) {
                            if (isProvida) {
                                arquivo.removeAttribute('disabled');
                                arquivo.setAttribute('name', 'arquivo_comprobatorio');
                                arquivo.required = true;
                            } else {
                                arquivo.value = '';
                                arquivo.required = false;
                                arquivo.removeAttribute('name');
                                arquivo.setAttribute('disabled', 'disabled');
                                const lbl = arquivo.nextElementSibling;
                                if (lbl && lbl.classList.contains('custom-file-label')) {
                                    lbl.innerHTML = '<i class="fas fa-cloud-upload-alt mr-1"></i> Escolher arquivo...';
                                }
                            }
                        }

                        if (dataEnc && dataAss) {
                            if (isProvida) {
                                dataEnc.removeAttribute('name');
                                dataEnc.required = false;

                                dataAss.setAttribute('name', 'data_assuncao');
                                dataAss.required = true;
                            } else {
                                dataAss.removeAttribute('name');
                                dataAss.required = false;

                                dataEnc.setAttribute('name', 'data_encaminhamento');
                                dataEnc.required = true;
                            }
                        }
                    }

                    // file input label update
                    if (arquivo) {
                        arquivo.addEventListener('change', function() {
                            const label = this.nextElementSibling;
                            if (!label) return;
                            if (this.files && this.files[0]) {
                                const f = this.files[0];
                                const size = fmtBytes(f.size);
                                const icon = f.type && f.type.includes('pdf') ? 'fa-file-pdf text-danger' :
                                    'fa-file-image text-primary';
                                label.innerHTML =
                                    `<i class="fas ${icon} mr-1"></i> ${f.name} <small class="text-muted ml-2">(${size})</small>`;
                            } else {
                                label.innerHTML = '<i class="fas fa-cloud-upload-alt mr-1"></i> Escolher arquivo...';
                            }
                        }, {
                            passive: true
                        });
                    }

                    if (situacao) {
                        situacao.addEventListener('change', updateFields);
                        // init after short delay to allow other scripts to run
                        setTimeout(updateFields, 50);
                    }

                    // enable bootstrap tooltips if available
                    if (window.jQuery && typeof $(function() {}) === 'function') {
                        try {
                            $('[data-toggle="tooltip"]').tooltip();
                        } catch (e) {}
                    }
                })();
            </script>
        </div>

        <div class="collapse @if ($provimento->num_cop) show @endif" id="validarAssuncao">
            <div class="card card-body">
                <hr class="my-2">

                <form action="/update/atualizarCOP" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="servidor_cadastro" value="{{ $servidor->cadastro }}">

                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="num_cop" class="small font-weight-bold">Nº DO COP</label>
                            <select name="num_cop" id="num_cop"
                                class="form-control form-control-sm select2 @error('num_cop') is-invalid @enderror"
                                aria-label="Número do COP" required>
                                <option value="">Selecione o COP</option>

                                @foreach ($num_cop as $cop)
                                    <option value="{{ $cop->num }}"
                                        {{ old('num_cop', $provimento->num_cop) == $cop->num ? 'selected' : '' }}>
                                        {{ $cop->num }}
                                    </option>
                                @endforeach
                            </select>

                            @error('num_cop')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="num_sei" class="small font-weight-bold">Nº PROCESSO SEI</label>
                            <input name="num_sei" id="num_sei" type="text"
                                value="{{ old('num_sei', $provimento->num_sei ?? '') }}"
                                class="form-control form-control-sm @error('num_sei') is-invalid @enderror"
                                placeholder="Ex: 12345.678901/2025-01">
                            <small class="form-text text-muted">Informe o número do processo SEI, se houver.</small>

                            @error('num_sei')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row mt-3">
                        <div class="col-12 d-flex justify-content-end">

                            <button type="submit" class="btn btn-primary  mr-2">
                                <i class="fas fa-save mr-1"></i> Salvar
                            </button>
                            <a href="{{ route('provimentos.validarDocs') }}" class="btn btn-outline-secondary ">
                                <i class="fas fa-times mr-1"></i> Cancelar
                            </a>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provimentoMat = document.getElementById('provimento_matutino');
            const provimentoVesp = document.getElementById('provimento_vespertino');
            const provimentoNot = document.getElementById('provimento_noturno');
            const totalEl = document.getElementById('total');
            const situacaoSelect = document.getElementById('situacao_provimento_detail');
            const arquivoRow = document.getElementById('arquivo_comprobatorio_row');
            const arquivoInput = document.getElementById('arquivo_comprobatorio');

            function toNumber(value) {
                if (value === undefined || value === null) return 0;
                const n = parseFloat(String(value).replace(',', '.'));
                return Number.isFinite(n) ? n : 0;
            }

            function addTotal() {
                if (!totalEl) return;
                const sum = toNumber(provimentoMat && provimentoMat.value) + toNumber(provimentoVesp &&
                    provimentoVesp.value) + toNumber(provimentoNot && provimentoNot.value);
                totalEl.value = sum;
            }

            // Calculadora de total
            if (provimentoMat && provimentoVesp && provimentoNot && totalEl) {
                ['blur', 'change', 'input'].forEach(evt => {
                    provimentoMat.addEventListener(evt, addTotal);
                    provimentoVesp.addEventListener(evt, addTotal);
                    provimentoNot.addEventListener(evt, addTotal);
                });
            }

            // Controle do campo de arquivo baseado na situação do provimento
            function toggleArquivoField() {
                if (!situacaoSelect || !arquivoRow || !arquivoInput) {
                    console.log('Elementos não encontrados:', {
                        situacaoSelect: !!situacaoSelect,
                        arquivoRow: !!arquivoRow,
                        arquivoInput: !!arquivoInput
                    });
                    return;
                }

                const isProvida = situacaoSelect.value === 'provida';
                console.log('Toggle arquivo field:', {
                    value: situacaoSelect.value,
                    isProvida
                });

                arquivoRow.style.display = isProvida ? 'block' : 'none';
                arquivoInput.required = isProvida;

                if (!isProvida) {
                    arquivoInput.value = ''; // Limpa o arquivo se não for provida
                    // Reset custom file label
                    const label = arquivoInput.nextElementSibling;
                    if (label && label.classList.contains('custom-file-label')) {
                        label.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Escolher arquivo...';
                    }
                }
            }

            // Bootstrap 4 Custom File Input - Update label with selected filename
            if (arquivoInput) {
                arquivoInput.addEventListener('change', function() {
                    const label = this.nextElementSibling;
                    const fileName = this.files[0] ? this.files[0].name :
                        '<i class="fas fa-cloud-upload-alt"></i> Escolher arquivo...';

                    if (label && label.classList.contains('custom-file-label')) {
                        if (this.files[0]) {
                            // File selected - show file name with icon
                            const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
                            label.innerHTML =
                                `<i class="fas fa-file-${this.files[0].type.includes('pdf') ? 'pdf' : 'image'} text-primary"></i> ${fileName} <small class="text-muted">(${fileSize} MB)</small>`;
                        } else {
                            // No file selected - reset label
                            label.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Escolher arquivo...';
                        }
                    }
                });
            }

            if (situacaoSelect) {
                // Suporte para select normal
                situacaoSelect.addEventListener('change', function(e) {
                    console.log('Change event triggered:', e.target.value);
                    toggleArquivoField();
                });

                // Suporte para Select2 (se estiver sendo usado)
                if (window.jQuery && $(situacaoSelect).length) {
                    $(situacaoSelect).on('change', function() {
                        console.log('Select2 change event triggered:', this.value);
                        toggleArquivoField();
                    });
                }

                // Executa na inicialização também
                setTimeout(function() {
                    console.log('Executando toggle inicial...');
                    toggleArquivoField();
                }, 500); // Aumentei o delay para garantir que tudo foi carregado
            } else {
                console.log('Elemento situacao_provimento_detail não encontrado');
            }

            // Função de teste para verificar se o toggle funciona
            window.testToggle = function() {
                console.log('Teste toggle executado');
                if (arquivoRow) {
                    const isVisible = arquivoRow.style.display !== 'none';
                    arquivoRow.style.display = isVisible ? 'none' : 'block';
                    console.log('Campo arquivo agora está:', isVisible ? 'oculto' : 'visível');
                } else {
                    console.log('arquivo_comprobatorio_row não encontrado');
                }
            };

            // Expose searchServidor to global scope so existing onclicks keep working
            window.searchServidor = function() {
                const cadastroInput = document.getElementById('cadastro');
                const servidorInput = document.getElementById('servidor');
                const vinculoInput = document.getElementById('vinculo');
                const regimeInput = document.getElementById('regime');

                if (!cadastroInput) return;
                const cadastro_servidor = String(cadastroInput.value || '').trim();
                if (!cadastro_servidor) {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Atenção!',
                            text: 'Matrícula não informada. Tente novamente.'
                        });
                    }
                    return;
                }

                // Prefer jQuery post if available (project already loads jQuery)
                if (window.jQuery && $.post) {
                    $.post('/consultarServidor/' + cadastro_servidor)
                        .done(function(response) {
                            const data = response && response[0];
                            if (data) {
                                servidorInput && (servidorInput.value = data.nome || '');
                                vinculoInput && (vinculoInput.value = data.vinculo || '');
                                regimeInput && (regimeInput.value = data.regime || '');
                                cadastroInput && (cadastroInput.value = data.cadastro || cadastroInput
                                    .value);
                            } else if (window.Swal) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Atenção!',
                                    text: 'Servidor não encontrado. Tente novamente.'
                                });
                            }
                        })
                        .fail(function() {
                            if (window.Swal) Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                text: 'Falha na requisição.'
                            });
                        });
                    return;
                }

                // Fallback using fetch (POST) with CSRF token
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const headers = {
                    'Content-Type': 'application/json'
                };
                if (tokenMeta) headers['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');

                fetch('/consultarServidor/' + cadastro_servidor, {
                        method: 'POST',
                        headers: headers
                    })
                    .then(res => res.json())
                    .then(response => {
                        const data = response && response[0];
                        if (data) {
                            servidorInput && (servidorInput.value = data.nome || '');
                            vinculoInput && (vinculoInput.value = data.vinculo || '');
                            regimeInput && (regimeInput.value = data.regime || '');
                            cadastroInput && (cadastroInput.value = data.cadastro || cadastroInput.value);
                        } else if (window.Swal) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção!',
                                text: 'Servidor não encontrado. Tente novamente.'
                            });
                        }
                    })
                    .catch(() => {
                        if (window.Swal) Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Falha na requisição.'
                        });
                    });
            };
        });
    </script>

    <style>
        /* Botões com cores do sistema e animações avançadas */
        .btn-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            color: white;
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            min-width: 150px;
        }

        .btn-gradient-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-gradient-primary:hover::before {
            left: 100%;
        }

        .btn-gradient-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
            color: white;
        }

        .btn-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            border: none;
            color: white;
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            min-width: 150px;
        }

        .btn-gradient-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-gradient-success:hover::before {
            left: 100%;
        }

        .btn-gradient-success:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
            background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
            color: white;
        }

        .btn-group-vertical .btn {
            border-radius: 5px !important;
            padding: 12px 20px;
            font-size: 0.9rem;
        }

        .btn-group-vertical .btn:active {
            transform: translateY(-1px) scale(0.98);
        }

        .btn-group-vertical .btn i {
            transition: transform 0.3s ease;
        }

        .btn-group-vertical .btn:hover i {
            transform: scale(1.1) rotate(5deg);
        }

        /* Estilos customizados para o campo de upload */
        #arquivo_comprobatorio_row .custom-file-label {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-weight: 500;
        }

        #arquivo_comprobatorio_row .custom-file-label:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }

        #arquivo_comprobatorio_row .custom-file-input:focus~.custom-file-label {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        #arquivo_comprobatorio_row .custom-file-input:valid~.custom-file-label {
            border-color: #28a745;
            border-style: solid;
        }

        #arquivo_comprobatorio_row .alert {
            border-radius: 8px;
            border: none;
        }

        #arquivo_comprobatorio_row .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid #28a745;
        }

        #arquivo_comprobatorio_row .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left: 4px solid #dc3545;
        }

        #arquivo_comprobatorio_row .btn-outline-primary {
            border-radius: 6px;
            font-size: 0.85rem;
            padding: 0.375rem 0.75rem;
            transition: all 0.2s ease;
        }

        #arquivo_comprobatorio_row .btn-outline-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }

        /* Animação para aparecer/sumir */
        #arquivo_comprobatorio_row {
            transition: opacity 0.3s ease, max-height 0.3s ease;
            overflow: hidden;
        }

        #arquivo_comprobatorio_row[style*="none"] {
            opacity: 0;
            max-height: 0;
        }

        #arquivo_comprobatorio_row[style*="block"] {
            opacity: 1;
            max-height: 500px;
        }
    </style>
@endpush
