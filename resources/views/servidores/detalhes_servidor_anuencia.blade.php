@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')

    <div class="card p-2 shadow">
        <div class="bg-primary d-flex flex-row card text-white card_title">
            <h3 class=" title_show_carencias">DETALHES DO SUPRIMENTO DO SERVIDOR</h3>
            <a class="mr-2" title="Voltar" href="{{ route('provimentos.validarDocs') }}">
                <button>
                    <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1"
                        viewBox="0 0 1024 1024">
                        <path
                            d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z">
                        </path>
                    </svg>
                    <span>VOLTAR</span>
                </button>
            </a>
        </div>
        <div class="card mt-2 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                            style="width:64px;height:64px;font-size:28px;">
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
                            {{ $initials }}
                        </div>
                    </div>

                    <div class="col">
                        <h5 class="mb-1">{{ $servidor->nome }}</h5>
                        <div class="small text-muted mb-1">
                            MATRÍCULA / CPF:
                            <span class="font-weight-bold text-dark ml-1">{{ $servidor->cadastro }}</span>
                        </div>
                        <div class="d-flex flex-wrap align-items-center">
                            <span class="badge badge-info mr-2 mb-1 text-uppercase">{{ $servidor->vinculo }}</span>
                            <span class="badge badge-secondary mb-1">{{ $servidor->regime }}h</span>
                        </div>
                    </div>

                    <div class="col-auto">
                        <div class="btn-group-vertical" role="group">
                            <a class="btn btn-gradient-primary mb-2 shadow-sm" data-toggle="collapse" href="#validarDocs" role="button"
                                aria-expanded="false" aria-controls="validarDocs">
                                <i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checklist"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" /><path d="M14 19l2 2l4 -4" /><path d="M9 8h4" /><path d="M9 12h2" /></svg></i>
                                ASSUNÇÃO
                            </a>
                            <a class="btn btn-gradient-success shadow-sm" data-toggle="collapse" href="#validarAssuncao" role="button"
                                aria-expanded="false" aria-controls="validarDocs">
                                <i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-text-spark"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5" /><path d="M9 9h1" /><path d="M9 13h6" /><path d="M9 17h3" /><path d="M19 22.5a4.75 4.75 0 0 1 3.5 -3.5a4.75 4.75 0 0 1 -3.5 -3.5a4.75 4.75 0 0 1 -3.5 3.5a4.75 4.75 0 0 1 3.5 3.5" /></svg></i>
                                COP
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered">
                <thead class="bg-primary text-white">
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
                <tbody>
                    @foreach ($provimentosByServidor as $servidor)
                        <tr>
                            @if ($servidor->nte >= 10)
                                <td class="text-center">{{ $servidor->nte }}</td>
                            @endif
                            @if ($servidor->nte < 10)
                                <td class="text-center">0{{ $servidor->nte }}</td>
                            @endif
                            <td>{{ $servidor->unidade_escolar }}</td>
                            <td class="text-center">{{ $servidor->cod_unidade }}</td>
                            <td>{{ $servidor->disciplina }}</td>
                            <td class="text-center">{{ $servidor->provimento_matutino }}</td>
                            <td class="text-center">{{ $servidor->provimento_vespertino }}</td>
                            <td class="text-center">{{ $servidor->provimento_noturno }}</td>
                            <td class="text-center">{{ $servidor->total }}</td>
                            <td class="text-center">{{ $servidor->forma_suprimento }}</td>
                            <td class="text-center">{{ $servidor->tipo_movimentacao }}</td>
                            @if ($servidor->situacao_provimento === 'provida')
                                <td class="text-center">PROVIDO</td>
                            @endif
                            @if ($servidor->situacao_provimento === 'tramite')
                                <td class="text-center">TRÂMITE</td>
                            @endif
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($servidor->data_encaminhamento)->format('d/m/Y') }}</td>
                            @if ($servidor->situacao_provimento === 'tramite')
                                <td class="text-center">PENDENTE</td>
                            @endif
                            @if ($servidor->situacao_provimento === 'provida')
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($servidor->data_assuncao)->format('d/m/Y') }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="collapse @if ($provimento->arquivo_comprobatorio) show @endif  " id="validarDocs">
                <div class="card card-body">
                    <hr>
                    <form action="/update/atualizarAssuncao" method="POST" enctype="multipart/form-data"
                        class="px-2 py-2">
                        @csrf
                        <input type="hidden" name="servidor_cadastro" value="{{ $servidor->cadastro }}">

                        <div class="form-row mb-2 g-2 align-items-end">
                            <div class="col-12 col-sm-6 col-md-2">
                                <label for="situacao_provimento_detail" class="control-label small">Situação do
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

                            <div class="col-6 col-sm-4 col-md-2" id="data_encaminhamento_col"
                                style="display: {{ $provimento->situacao_provimento === 'tramite' ? 'block' : 'none' }};">
                                <label for="data_encaminhamento" class="control-label small">Data de
                                    Encaminhamento</label>
                                <input type="date"
                                    name="{{ $provimento->situacao_provimento === 'tramite' ? 'data_encaminhamento' : '' }}"
                                    id="data_encaminhamento"
                                    value="{{ old('data_encaminhamento', optional($provimento->data_encaminhamento ? \Carbon\Carbon::parse($provimento->data_encaminhamento) : null)->format('Y-m-d')) }}"
                                    class="form-control form-control-sm"
                                    {{ $provimento->situacao_provimento === 'tramite' ? 'required' : '' }}>
                            </div>

                            <div class="col-6 col-sm-4 col-md-2" id="data_assuncao_col"
                                style="display: {{ $provimento->situacao_provimento === 'provida' ? 'block' : 'none' }};">
                                <label for="data_assuncao" class="control-label small">Data de Assunção</label>
                                <input type="date"
                                    name="{{ $provimento->situacao_provimento === 'provida' ? 'data_assuncao' : '' }}"
                                    id="data_assuncao"
                                    value="{{ old('data_assuncao', optional($provimento->data_assuncao ? \Carbon\Carbon::parse($provimento->data_assuncao) : null)->format('Y-m-d')) }}"
                                    class="form-control form-control-sm"
                                    {{ $provimento->situacao_provimento === 'provida' ? 'required' : '' }}>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4 mb-2" id="arquivo_comprobatorio_row"
                                style="display: {{ $provimento->situacao_provimento === 'provida' ? 'block' : 'none' }};">
                                <div class="card mb-0">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                <label class="control-label font-weight-bold small mb-0"
                                                    for="arquivo_comprobatorio">
                                                    <i class="fas fa-paperclip text-primary"></i> Termo de Assunção
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <small class="form-text text-muted">
                                                    Formatos: PDF, JPEG, JPG — máximo 5MB.
                                                </small>
                                            </div>
                                        </div>

                                        <div class="custom-file mb-2">
                                            <input type="file" id="arquivo_comprobatorio" class="custom-file-input"
                                                accept=".pdf,.jpg,.jpeg"
                                                {{ $provimento->situacao_provimento === 'provida' ? 'name=arquivo_comprobatorio' : 'disabled' }}
                                                {{ $provimento->situacao_provimento === 'provida' && !$provimento->arquivo_comprobatorio ?: '' }}>
                                            <label class="custom-file-label small" for="arquivo_comprobatorio">
                                                <i class="fas fa-cloud-upload-alt"></i> Escolher arquivo...
                                            </label>
                                        </div>

                                        @if ($provimento->arquivo_comprobatorio)
                                            <div class="mt-1">
                                                <div class="list-group">
                                                    <div
                                                        class="list-group-item d-flex justify-content-between align-items-center p-2">
                                                        <div class="d-flex align-items-center">
                                                            <i
                                                                class="fas {{ Str::endsWith(strtolower($provimento->arquivo_comprobatorio), '.pdf') ? 'fa-file-pdf text-danger' : 'fa-file-image text-primary' }} fa-lg mr-2"></i>
                                                            <div class="small text-truncate" style="max-width:180px;">
                                                                <strong>Arquivo atual:</strong>
                                                                <div class="text-muted">
                                                                    {{ Str::limit($provimento->arquivo_comprobatorio, 40) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="{{ route('provimento.arquivo', $provimento->arquivo_comprobatorio) }}"
                                                                target="_blank" class="btn btn-outline-primary btn-sm">
                                                                <i class="fas fa-external-link-alt"></i> Abrir
                                                            </a>
                                                            <a href="{{ route('provimento.arquivo', $provimento->arquivo_comprobatorio) }}"
                                                                download class="btn btn-outline-secondary btn-sm">
                                                                <i class="fas fa-download"></i> Baixar
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @error('arquivo_comprobatorio')
                                            <div class="invalid-feedback d-block mt-1 small">
                                                <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                            </div>
                                        @enderror

                                        <small class="form-text text-muted mt-1 small">Dica: nomes curtos facilitam o
                                            download.</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row g-3">
                            <div class="mt-4 col-12 d-flex justify-content-end">
                                <div id="buttons" class="buttons">
                                    <button id="" class="button" type="submit">
                                        <span class="button__text">Salvar</span>
                                        <span class="button__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M14 4l0 4l-6 0l0 -4" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <script>
                    // Toggle which date field is submitted and whether file is sent.
                    (function() {
                        const situacao = document.getElementById('situacao_provimento_detail');
                        const arquivo = document.getElementById('arquivo_comprobatorio');
                        const arquivoRow = document.getElementById('arquivo_comprobatorio_row');
                        const dataEncCol = document.getElementById('data_encaminhamento_col');
                        const dataAssCol = document.getElementById('data_assuncao_col');
                        const dataEnc = document.getElementById('data_encaminhamento');
                        const dataAss = document.getElementById('data_assuncao');

                        function updateFields() {
                            const isProvida = situacao.value === 'provida';

                            // Show/hide blocks
                            if (arquivoRow) arquivoRow.style.display = isProvida ? 'block' : 'none';
                            if (dataAssCol) dataAssCol.style.display = isProvida ? 'block' : 'none';
                            if (dataEncCol) dataEncCol.style.display = isProvida ? 'none' : 'block';

                            // File input: only enabled and named when PROVIDA
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
                                        lbl.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Escolher arquivo...';
                                    }
                                }
                            }

                            // Dates: only the active one should have a name/required so server receives only one
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

                        if (situacao) {
                            situacao.addEventListener('change', updateFields);
                            // initialize on load
                            setTimeout(updateFields, 50);
                        }
                    })();
                </script>
            </div>
            <div class="collapse @if ($provimento->num_cop) show @endif  " id="validarAssuncao">
                <div class="card card-body">
                    <hr>
                    <form action="/update/atualizarCOP" method="POST">
                        @csrf
                        <input type="hidden" name="servidor_cadastro" value="{{ $servidor->cadastro }}">
                        <div class="form-row">
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="num_cop">Nº DO COP</label>
                                    <select name="num_cop" id="num_cop" class="form-control form-control-sm select2">
                                        <option value="{{ $provimento->num_cop }}">{{ $provimento->num_cop }}</option>
                                        {{-- Itera sobre a lista completa de COPs enviada pelo controller --}}
                                        @foreach ($num_cop as $cop)
                                            <option value="{{ $cop->num }}">{{ $cop->num }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group_disciplina">
                                    <label for="num_sei" class="">Nº PROCESSO SEI</label>
                                    <input name="" id="num_sei" type="number"
                                        class="form-control form-control-sm" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="mt-4 col-12 d-flex justify-content-end">
                                <div id="buttons" class="buttons">
                                    <button id="" class="button" type="submit">
                                        <span class="button__text">Atualizar</span>
                                        <span class="button__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
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
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
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
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
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
            border-radius: 12px !important;
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
