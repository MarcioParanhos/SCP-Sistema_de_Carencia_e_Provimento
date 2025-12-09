@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')

    <style>
        .button {
            --main-focus: #2d8cf0;
            --font-color: #323232;
            --bg-color-sub: #fff;
            --bg-color: #fff;
            --main-color: #2F3F64;
            position: relative;
            width: 150px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            border: 2px solid var(--main-color);
            box-shadow: 3px 3px var(--main-color);
            background-color: var(--bg-color);
            border-radius: 10px;
            overflow: hidden;
            padding: 0;
        }

        .button,
        .button__icon,
        .button__text {
            transition: all 0.3s;
        }

        .button .button__text {
            transform: translateX(20px);
            color: var(--font-color);
            font-weight: 600;
        }

        .button .button__icon {
            position: absolute;
            transform: translateX(100px);
            height: 100%;
            width: 46px;
            background-color: var(--bg-color-sub);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .button .svg {
            width: 20px;
            fill: var(--main-color);
        }

        .button:hover {
            background: var(--bg-color);
        }

        .button:hover .button__text {
            color: transparent;
        }

        .button:hover .button__icon {
            width: 148px;
            transform: translateX(0);
        }

        .button:active {
            transform: translate(3px, 3px);
            box-shadow: 0px 0px var(--main-color);
        }
    </style>

    @if (session('msg'))
        <input id="session_message" value="{{ session('msg') }}" type="text" hidden>
    @endif
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow rounded">
            <div class="card_title_form">
                <h4 class="card-title">INCLUIR PROVIMENTO - {{ $uee->unidade_escolar }}</h4>
            </div>
            @if ($uee->situacao === 'HOMOLOGADA')
                <h4 class="badge badge-success"><strong>UE HOMOLOGADA</strong></h4>
            @endif
            @if ($uee->situacao === 'PENDENTE')
                <!-- <h4 class="badge badge-danger"><strong>UEE NÃO HOMOLOGADA ( NÃO É PERMITIDO PROVER VAGAS PARA UMA UNIDADE QUE NÃO ESTEJA HOMOLOGADA )</strong></h4> -->
                <h4 class="badge badge-danger"><strong>UEE NÃO HOMOLOGADA</strong></h4>
            @endif
            <div class="card-body">
                <form class="forms-sample" id="InsertForm">
                    @csrf
                    <input value="" id="tipo_vaga" name="tipo_vaga" type="text"
                        class="form-control form-control-sm" hidden>
                    <input value="Real" id="tipo_carencia" name="tipo_carencia" type="text"
                        class="form-control form-control-sm" hidden>

                    <div class="form-row">
                        <div class=" col-md-2">
                            <div class="display_btn position-relative form-group">
                                <div>
                                    <label for="cod_unidade_provimento" class="">buscar Cod. UE</label>
                                    <input value="{{ $uee->cod_unidade }}" minlength="8" maxlength="9"
                                        name="cod_unidade_provimento" id="cod_unidade_provimento" type="number"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="btn_carencia_seacrh">
                                    <button id="btn-cadastro" class="btn_search_carencia btn btn-sm btn-primary"
                                        type="button" onclick="addNewProvimento()" required>
                                        <i class="ti-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="nte" class="">NTE</label>
                                @if ($uee->nte < 9)
                                    <input value="0{{ $uee->nte }}" id="" name="" type="text"
                                        class="form-control form-control-sm" readonly required>
                                @endif
                                @if ($uee->nte > 9)
                                    <input value="{{ $uee->nte }}" id="" name="" type="text"
                                        class="form-control form-control-sm" readonly required>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="municipio" class="">Município</label>
                                <input value="{{ $uee->municipio }}" id="" name="" type="text"
                                    class="form-control form-control-sm" readonly required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="unidade_escolar" class="">Nome da Unidade Escolar</label>
                                <input value="{{ $uee->unidade_escolar }}" name="" required id=""
                                    type="text" class="form-control form-control-sm" readonly required>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="table_form">
                    <div class="mt-4 mb-2 ">
                        <h5 class="card-title">VAGAS DETALHADAS AGUARDANDO PROVIMENTO</h5>
                    </div>
                    <div class="table-responsive">
                        <table id="table1" class=" mb-4 table table-bordered table-sm table-hover">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-center bg-primary text-white">DISCIPLINA</th>
                                    <th class="text-center bg-primary text-white">TIPO</th>
                                    <th class="text-center bg-primary text-white">MAT</th>
                                    <th class="input_provimento hidden">PROVER MAT</th>
                                    <th class="text-center bg-primary text-white">VESP</th>
                                    <th class="input_provimento hidden">PROVER VESP</th>
                                    <th class="text-center bg-primary text-white">NOT</th>
                                    <th class="input_provimento hidden">PROVER NOT</th>
                                    <th class="text-center bg-primary text-white">TOTAL</th>
                                    <th class="text-center bg-primary text-white">TIPO</th>
                                    <th class="text-center bg-primary text-white">PROVER</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($carencias as $carencia)
                                    <tr>
                                        <td id="disciplina_table" class="text-center">{{ $carencia->disciplina }}</td>

                                        {{-- Tipo da carência (R ou T) --}}
                                        <td class="text-center">
                                            <span class="tipo_carencia">
                                                {{ $carencia->tipo_carencia === 'Real' ? 'R' : ($carencia->tipo_carencia === 'Temp' ? 'T' : '') }}
                                            </span>
                                        </td>

                                        {{-- Turno: Matutino --}}
                                        <td class="text-center">{{ $carencia->matutino }}</td>
                                        <td class="remove_hidden" hidden>
                                            <input id="validation_mat_{{ $carencia->id }}" value="0"
                                                name="provimento_matutino[{{ $carencia->id }}]"
                                                class="input_provimento form-control form-control-sm" type="number"
                                                min="0" max="{{ $carencia->matutino }}">
                                        </td>

                                        {{-- Turno: Vespertino --}}
                                        <td class="text-center">{{ $carencia->vespertino }}</td>
                                        <td class="remove_hidden" hidden>
                                            <input id="validation_vesp_{{ $carencia->id }}" value="0"
                                                name="provimento_vespertino[{{ $carencia->id }}]"
                                                class="input_provimento form-control form-control-sm" type="number"
                                                min="0" max="{{ $carencia->vespertino }}">
                                        </td>

                                        {{-- Turno: Noturno --}}
                                        <td class="text-center">{{ $carencia->noturno }}</td>
                                        <td class="remove_hidden" hidden>
                                            <input id="validation_not_{{ $carencia->id }}" value="0"
                                                name="provimento_noturno[{{ $carencia->id }}]"
                                                class="input_provimento form-control form-control-sm" type="number"
                                                min="0" max="{{ $carencia->noturno }}">
                                        </td>

                                        {{-- Total e tipo da vaga --}}
                                        <td class="text-center">{{ $carencia->total }}</td>
                                        <td class="text-center">{{ $carencia->tipo_vaga }}</td>

                                        {{-- Botão --}}
                                        <td id="class-button" class="text-center">
                                            <a title="Consultar" data-id="{{ $carencia->id }}" id="{{ $carencia->id }}"
                                                class="transferir btn-show-carencia btn btn-sm btn-primary">
                                                <i class="ti-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">Nenhum resultado encontrado.</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                        <div class="mb-2 span-table-2">
                            <div>
                                <span class="pt-2 tipo_carencia">R</span> - Real
                            </div>
                            <div>
                                <span class="pt-2 tipo_carencia">T</span> - Temporaria
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <form class="forms-sample" id="InsertForm" action="/addNewProvimento" method="post" enctype="multipart/form-data">
                    @csrf
                    <input name="nte" value="{{ $uee->nte }}" type="text" hidden>
                    <input name="municipio" value="{{ $uee->municipio }}" type="text" hidden>
                    <input name="unidade_escolar" value="{{ $uee->unidade_escolar }}" type="text" hidden>
                    <input name="cod_unidade" value="{{ $uee->cod_unidade }}" type="text" hidden>
                    <input value="{{ Auth::user()->name }}" id="usuario" name="usuario" type="text"
                        class="form-control form-control-sm" hidden>
                    <input value="{{ Auth::user()->profile }}" id="profile" name="profile" type="text"
                        class="form-control form-control-sm" hidden>
                    <input value="{{ Auth::user()->id }}" id="user_id" name="user_id" type="text"
                        class="form-control form-control-sm" hidden>
                    <div id="">
                        <div class="mt-4 mb-2 ">
                            <h5 class="card-title">COMPONENTE A PROVER</h5>
                        </div>
                        @if (Auth::user()->profile != 'cpg_tecnico')
                            <div class="form-row">
                                <div class=" col-md-2">
                                    <div class="display_btn position-relative form-group">
                                        <div>
                                            <label for="cadastro" class="">Matrícula / cpf</label>
                                            <input value="" minlength="8" maxlength="11" name="cadastro"
                                                id="cadastro" type="cadastro" class="form-control form-control-sm">
                                        </div>
                                        <div class="btn_carencia_seacrh">
                                            <button id="cadastro_btn"
                                                class="position-relative btn_search_carencia btn btn-sm btn-primary"
                                                type="button" onclick="searchServidor()">
                                                <i class="ti-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="servidor" class="">nome do servidor</label>
                                        <input value="" id="servidor" name="servidor" type="text"
                                            class="form-control form-control-sm" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vinculo" class="">vinculo</label>
                                        <input value="" id="vinculo" name="vinculo" type="text"
                                            class="form-control form-control-sm" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="position-relative form-group">
                                        <label for="regime" class="">regime</label>
                                        <input value="" name="regime" required id="regime" type="text"
                                            class="form-control form-control-sm" readonly required>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-row">
                            @if (Auth::user()->profile != 'cpg_tecnico')
                                <div class="col-md-3" id="motivo_vaga_row">
                                    <div class="form-group_disciplina">
                                        <label class="control-label" for="forma_suprimento">FORMA DE SUPRIMENTO</label>
                                        <select name="forma_suprimento" id="forma_suprimento"
                                            class="form-control select2" required>
                                            <option value=""></option>
                                            @foreach ($forma_suprimentos as $forma_suprimento)
                                                <option value="{{ $forma_suprimento->forma }}">
                                                    {{ $forma_suprimento->forma }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-3" id="motivo_vaga_row">
                                    <div class="form-group_disciplina">
                                        <label class="control-label" for="forma_suprimento">FORMA DE SUPRIMENTO</label>
                                        <select name="forma_suprimento" id="forma_suprimento"
                                            class="form-control select2" required>
                                            <option value=""></option>
                                            <option value="AJUSTE INTERNO">AJUSTE INTERNO</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->profile != 'cpg_tecnico')
                                <div class="col-md-2" id="motivo_vaga_row">
                                    <div class="form-group_disciplina">
                                        <label class="control-label" for="tipo_movimentacao">TIPO de movimentação</label>
                                        <select name="tipo_movimentacao" id="tipo_movimentacao"
                                            class="form-control select2" required>
                                            <option value="">SELECIONE...</option>
                                            <option value="INGRESSO">INGRESSO</option>
                                            <option value="RELOTAÇÃO">RELOTAÇÃO</option>
                                            <option value="REMOÇÃO">REMOÇÃO</option>
                                            <option value="PRÓPRIA UE">NA PRÓPRIA UE</option>
                                            <option value="COMPLEMENTAÇÃO">COMPLEMENTAÇÃO</option>
                                            <option value="COMPLEMENTAÇÃO">COMPLEMENTAÇÃO</option>
                                            <option value="CONVOCAÇÃO SELETIVO">CONVOCAÇÃO SELETIVO</option>
                                            <option value="AUTORIZAÇÃO EMERGENCIAL">AUTORIZAÇÃO EMERGENCIAL</option>
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-2" id="motivo_vaga_row">
                                    <div class="form-group_disciplina">
                                        <label class="control-label" for="tipo_movimentacao">TIPO de movimentação</label>
                                        <select name="tipo_movimentacao" id="tipo_movimentacao"
                                            class="form-control select2" required>
                                            <option value="">SELECIONE...</option>
                                            <option value="PRÓPRIA UE">NA PRÓPRIA UE</option>
                                            <option value="REDUÇÃO DE TURMA">REDUÇÃO DE TURMA</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3" id="motivo_vaga_row">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="tipo_aula">Tipo de Aula </label>
                                    <select name="tipo_aula" id="tipo_aula" class="form-control select2" required>
                                        <option value="">SELECIONE...</option>
                                        <option value="NORMAL">NORMAL</option>
                                        <option value="EXTRA">EXTRA</option>
                                    </select>
                                </div>
                            </div>
                            @if (Auth::user()->profile != 'cpg_tecnico')
                                <div class="col-md-3" id="">
                                    <div class="form-group_disciplina">
                                        <label class="control-label" for="situacao_provimento">situação do
                                            provimento</label>
                                        <select name="situacao_provimento" id="situacao_provimento"
                                            class="form-control select2">
                                            <option value="">SELECIONE...</option>
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-2" id="">
                                    <div class="form-group_disciplina">
                                        <label class="control-label" for="situacao_provimento">situação do
                                            provimento</label>
                                        <input type="text" value="provida" name="situacao_provimento"
                                            class="form-control form-control-sm" readonly>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->profile != 'cpg_tecnico')
                                <div id="data_encaminhamento_row" class="col-md-2">
                                    <div class="form-group_disciplina">
                                        <label for="data_encaminhamento" class="">data de encaminhamento</label>
                                        <input value="data_encaminhamento" name="data_encaminhamento"
                                            id="data_encaminhamento" type="date" class="form-control form-control-sm"
                                            required>
                                    </div>
                                </div>
                                <div id="data_assuncao_row" class="col-md-2" hidden>
                                    <div class="form-group_disciplina">
                                        <label for="data_assuncao" class="">assunção</label>
                                        <input value="data_assuncao" name="data_assuncao" id="data_assuncao"
                                            type="date" class="form-control form-control-sm">
                                    </div>
                                </div>
                            @else
                                <div id="data_encaminhamento_row" class="col-md-2">
                                    <div class="form-group_disciplina">
                                        <label for="data_encaminhamento" class="">data de encaminhamento</label>
                                        <input value="data_encaminhamento" name="data_encaminhamento"
                                            id="data_encaminhamento" type="date" class="form-control form-control-sm"
                                            required>
                                    </div>
                                </div>
                                <div id="data_assuncao_row" class="col-md-2">
                                    <div class="form-group_disciplina">
                                        <label for="data_assuncao" class="">assunção</label>
                                        <input value="data_assuncao" name="data_assuncao" id="data_assuncao"
                                            type="date" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                            @endif
                            {{-- <div id="num_cop_row" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="num_cop" class="">Nº COP</label>
                                    <input value="" name="num_cop" id="num_cop"
                                        type="text" class="form-control form-control-sm">
                                </div>
                            </div> --}}
                            <div id="num_cop_row" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="num_cop">Nº COP</label>
                                    <select name="num_cop" id="num_cop" class="form-control select2">
                                        <option value="">SELECIONE...</option>
                                        @foreach ($num_cop as $cop)
                                            <option value="{{ $cop->num }}">{{ $cop->num }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div id="arquivo_comprobatorio_row" class="col-12 col-md-6 mb-3">
                                <label class="small font-weight-bold d-block mb-2">Termo de Assunção <span
                                        class="text-danger">*</span></label>

                                <div class="input-group input-group-sm mb-2">
                                    <div class="custom-file">
                                        <input type="file" id="arquivo_comprobatorio" name="arquivo_comprobatorio" class="custom-file-input"
                                            accept=".pdf,.jpg,.jpeg">
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



                                @error('arquivo_comprobatorio')
                                    <div class="invalid-feedback d-block mt-2 small">
                                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                    </div>
                                @enderror


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
                                            arquivo.required = false;
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
                        <div class="form-row">
                            <div id="data_assuncao_row" class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label for="obs">Observações<i class="ti-pencil"></i></label>
                                    <textarea name="obs" class="form-control" id="obs" rows="4" maxlength="120"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="table2" class="mb-4 table table-bordered table-sm table-hover">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th class="text-center">DISCIPLINA</th>
                                        <th class="text-center"></th>
                                        <th class="text-center">MAT</th>
                                        <th class="input_provimento ">PROVER MAT</th>
                                        <th class="text-center">VESP</th>
                                        <th class="input_provimento ">PROVER VESP</th>
                                        <th class="text-center">NOT</th>
                                        <th class="input_provimento ">PROVER NOT</th>
                                        <th class="text-center">TOTAL</th>
                                        <th class="text-center">TIPO</th>
                                        <th class="text-center">PROVER</th>
                                    </tr>
                                </thead>
                            </table>
                            <div id="buttons" class="buttons mb-4">
                                <button class="button" type="submit">
                                    <span class="button__text">ADICIONAR</span>
                                    <span class="button__icon"><svg class="svg" fill="none" height="24"
                                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" viewBox="0 0 24 24" width="24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <line x1="12" x2="12" y1="5" y2="19"></line>
                                            <line x1="5" x2="19" y1="12" y2="12"></line>
                                        </svg></span>
                                </button>
                            </div>
                        </div>
                        <hr>
                    </div>
                </form>
                <div class="mt-4 mb-2 ">
                    <h5 class=" p-2  card-title">COMPONENTES PROVIDOS PARA ESTA UNIDADE</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-2 table-bordered">
                        <thead>
                            <tr class="text-white bg-primary">
                                <th class="text-center bg-primary text-white" scope="col">DISCIPLINA</th>
                                <th class="text-center bg-primary text-white" scope="col">MAT</th>
                                <th class="text-center bg-primary text-white" scope="col">VESP</th>
                                <th class="text-center bg-primary text-white" scope="col">NOT</th>
                                <th class="text-center bg-primary text-white" scope="col">TOTAL</th>
                                <th class="text-center bg-primary text-white" scope="col">SERVIDOR</th>
                                <th class="text-center bg-primary text-white" scope="col">CADASTRO / CPF</th>
                                <th class="text-center bg-primary text-white" scope="col">VINCULO</th>
                                <th class="text-center bg-primary text-white" scope="col">TIPO MOVIMENTO</th>
                                <th class="text-center bg-primary text-white" scope="col">SITUAÇÃO</th>
                                <th class="text-center bg-primary text-white" scope="col">ENCAMINHAMENTO</th>
                                <th class="text-center bg-primary text-white" scope="col">ASSUNÇÃO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($provimentos as $provimento)
                                <tr>
                                    <td>{{ $provimento->disciplina }}</td>
                                    <td class="text-center">{{ $provimento->provimento_matutino }}</td>
                                    <td class="text-center">{{ $provimento->provimento_vespertino }}</td>
                                    <td class="text-center">{{ $provimento->provimento_noturno }}</td>
                                    <td class="text-center">{{ $provimento->total }}</td>
                                    <td>{{ $provimento->servidor }}</td>
                                    <td class="text-center">{{ $provimento->cadastro }}</td>
                                    <td class="text-center">{{ $provimento->vinculo }}</td>
                                    <td class="text-center">{{ $provimento->tipo_movimentacao }}</td>
                                    @if ($provimento->situacao_provimento === 'tramite')
                                        <td class="text-center">EM TRÂMITE</td>
                                    @endif
                                    @if ($provimento->situacao_provimento === 'provida')
                                        <td class="text-center">PROVIDO</td>
                                    @endif
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($provimento->data_encaminhamento)->format('d/m/Y') }}</td>
                                    @if (!$provimento->data_assuncao)
                                        <td class="text-center">PENDENTE</td>
                                    @endif
                                    @if ($provimento->data_assuncao)
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($provimento->data_assuncao)->format('d/m/Y') }}</td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">Nenhum resultado encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <script>
        data_encaminhamento.addEventListener("change", function() {
            data_assuncao.min = data_encaminhamento.value;
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const session_message = document.getElementById("session_message");

            if (session_message) {
                if (session_message.value === "error") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Atenção!',
                        text: 'Não é possível prover um total de 0 h.',
                    })
                } else if (session_message.value === "carência inexistente") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Atenção!',
                        text: 'Provimento não realizado, é preciso selecionar uma carência!',
                    })
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Vaga suprida com Sucesso!',
                        showConfirmButton: true,
                    })
                }
            }
        });
    </script>
@endsection
