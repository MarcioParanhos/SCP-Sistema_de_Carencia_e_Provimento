@extends('layout.main')

@section('title', 'SCP - Carência')

@section('content')

    <?php
    
    use Carbon\Carbon;
    
    $data_atual = Carbon::now();
    $ano_atual = $data_atual->year;
    ?>

    @if (session('msg'))
        <div class="col-12">
            <div class="alert text-center text-white bg-success container alert-success alert-dismissible fade show"
                role="alert" style="min-width: 100%">
                <strong>{{ session('msg') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="shadow bg-primary text-white card_title ">
            @if ($provimento->pch === 'PENDENTE')
                @if ($provimento->situacao === 'BLOQUEADO')
                    <h4 class="testandoO badge badge-danger print-none"><strong>BLOQUEADO PARA EDIÇÃO</strong></h4>
                @endif
                @if ($provimento->situacao === 'DESBLOQUEADO')
                    <h4 class="badge badge-success print-none"><strong>DESBLOQUEADO</strong></h4>
                @endif
            @endif
            @if ($provimento->pch === 'OK')
                <h4 class="testandoO badge badge-danger print-none"><strong>PROVIMENTO JÁ VALIDADO PELA CPG - EDIÇÃO
                        BLOQUEADA</strong></h4>
            @endif
            <h4 class="title_show_carencias">suprimento detalhado</h4>
            <div class="print-none  d-flex justify-content-center align-items-center print-none">
                <a data-toggle="tooltip" data-placement="top" title="Voltar" class="m-1 btn bg-white text-primary"
                    href="/buscar/provimento/filter_provimentos">
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

        <div class="info-edit">
            <div class="edit-container-homlogada">
                <div class="user-edit">

                </div>
            </div>
            <div class="edit-container">
                <div class="user-edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                    <h4>{{ $provimento->usuario }}</h4>
                </div>
                <div class="user-edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                    <h4>{{ \Carbon\Carbon::parse($provimento->created_at)->format('d/m/Y') }}</h4>
                </div>
            </div>
        </div>
        <div class="shadow card_info">
            @can('view-cpg-technician-or-administrator')
                @if ($provimento->situacao_provimento != 'tramite')
                    @if ($provimento->pch === 'OK')
                        <div class="d-flex justify-content-end container-fluid print-none mb-4">
                            <input id="check_provimento_id" value="{{ $provimento->id }}" type="text" hidden>
                            <label class="toggle">
                                <input id="check-pch" class="toggle-checkbox" type="checkbox" checked>
                                <div class="toggle-switch"></div>
                                <span class="toggle-label">PROGRAMADO - PCH</span>
                            </label>
                        </div>
                    @endif
                    @if ($provimento->pch === 'PENDENTE')
                        <div class="d-flex justify-content-end container-fluid print-none mb-4">
                            <input id="check_provimento_id" value="{{ $provimento->id }}" type="text" hidden>
                            <label class="toggle">
                                <input id="check-pch" class="toggle-checkbox" type="checkbox">
                                <div class="toggle-switch"></div>
                                <span class="toggle-label">PROGRAMADO - PCH</span>
                            </label>
                        </div>
                    @endif
                @endif
            @endcan
            <form action="{{ route('provimento.update', $provimento->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method ('PUT')
                <input value="{{ Auth::user()->name }}" id="" name="user_cpg_update" type="text"
                    class="form-control form-control-sm" hidden>
                <input value="{{ Auth::user()->profile }}" id="" name="profile_cpg_update" type="text"
                    class="form-control form-control-sm" hidden>
                <div class="form-row">
                    <div class="col-md-1">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="nte">NTE</label>
                            <input value="{{ $provimento->nte }}" name="nte" id="nte" type="text"
                                class="text-center form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="municipio_search">MUNICIPIO</label>
                            <input value="{{ $provimento->municipio }}" name="municipio" id="municipio" type="text"
                                class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="search_uee">NOME DA UNIDADE ESCOLAR</label>
                            <input value="{{ $provimento->unidade_escolar }}" name="unidade_escolar"
                                id="unidade_escolar" type="text" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group_disciplina">
                            <label for="codigo_unidade_escolar" class="">COD. UE</label>
                            <input value="{{ $provimento->cod_unidade }}" name="cod_unidade" id="cod_unidade"
                                type="text" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    @if ($provimento->pch === 'OK')
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="nte_seacrh">MATRÍCULA / CPF</label>
                                <input value="{{ $provimento->cadastro }}" name="cadastro" id="cadastro"
                                    type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="municipio_search">Servidor</label>
                                <input value="{{ $provimento->servidor }}" name="servidor" id="servidor"
                                    type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    @endif
                    @if ($provimento->pch === 'PENDENTE')
                        @can('view-blocked-provimento-details', $provimento)
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="nte_seacrh">MATRÍCULA / CPF</label>
                                    <input value="{{ $provimento->cadastro }}" name="cadastro" id="cadastro"
                                        type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="municipio_search">Servidor</label>
                                    <input value="{{ $provimento->servidor }}" name="servidor" id="servidor"
                                        type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        @endcan
                        @auth
                            @can('view-sensitive-validations-fields')
                                <div class=" col-md-2">
                                    <div class="display_btn position-relative form-group">
                                        <div>
                                            <label for="cadastro" class="">Matrícula / CPF</label>
                                            <input value="{{ $provimento->cadastro }}" minlength="8" maxlength="11"
                                                name="cadastro" id="cadastro" type="text"
                                                class="form-control form-control-sm">
                                        </div>
                                        <div class="btn_carencia_seacrh print-none">
                                            <button id="cadastro_btn"
                                                class="position-relative btn_search_carencia btn btn-sm btn-primary"
                                                type="button" onclick="searchServidor()">
                                                <i class="ti-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="servidor">Servidor</label>
                                        <input value="{{ $provimento->servidor }}" name="servidor" id="servidor"
                                            type="text" class="form-control form-control-sm">
                                    </div>
                                </div>
                            @endcan
                        @endauth
                    @endif
                    <div class="col-md-1">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="search_uee">VÍNCULO</label>
                            <input value="{{ $provimento->vinculo }}" name="vinculo" id="vinculo" type="text"
                                class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group_disciplina">
                            <label for="codigo_unidade_escolar" class="control-label">regime</label>
                            <input value="{{ $provimento->regime }}" name="regime" id="regime" type="text"
                                class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="eixo">FORMA DO SUPRIMENTO</label>
                            <input value="{{ $provimento->forma_suprimento }}" name="tipo_movimentacao"
                                id="tipo_movimentacao" type="text" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="eixo">TIPO DE MOVIMENTAÇÃO</label>
                            <input value="{{ $provimento->tipo_movimentacao }}" name="tipo_movimentacao"
                                id="tipo_movimentacao" type="text" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-3">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="nte_seacrh">Disciplina</label>
                            <input value="{{ $provimento->disciplina }}" name="disciplina" id="disciplina"
                                type="text" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="municipio_search">mat</label>
                            <input value="{{ $provimento->provimento_matutino }}" name="provimento_matutino"
                                id="provimento_matutino" type="text" class="text-center form-control form-control-sm"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="search_uee">vesp</label>
                            <input value="{{ $provimento->provimento_vespertino }}" name="provimento_vespertino"
                                id="provimento_vespertino" type="text"
                                class="text-center form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group_disciplina">
                            <label for="codigo_unidade_escolar" class="">not</label>
                            <input value="{{ $provimento->provimento_noturno }}" name="provimento_noturno"
                                id="provimento_noturno" type="text" class="text-center form-control form-control-sm"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group_disciplina">
                            <label for="codigo_unidade_escolar" class="">total</label>
                            <input value="{{ $provimento->total }}" name="total" id="total" type="text"
                                class="text-center form-control form-control-sm" readonly>
                        </div>
                    </div>
                    @if ($provimento->tipo_carencia_provida === 'Temp')
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="codigo_unidade_escolar" class="">TIPO DE VAGA PROVIDA</label>
                                <input value="TEMPORÁRIA" name="" id="" type="text"
                                    class="text-center form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="codigo_unidade_escolar" class="">DATA FIM DA VAGA</label>
                                <input value="{{ $provimento->data_fim_by_temp }}" name="" id=""
                                    type="date" class="text-center form-control form-control-sm" readonly>
                            </div>
                        </div>
                    @endif
                    @if ($provimento->tipo_carencia_provida === 'Real')
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="codigo_unidade_escolar" class="">TIPO DE VAGA PROVIDA</label>
                                <input value="REAL" name="" id="" type="text"
                                    class="text-center form-control form-control-sm" readonly>
                            </div>
                        </div>
                    @endif
                    @if ($provimento->num_cop)
                        <div class="col-md-1">
                            <div class="form-group_disciplina">
                                <label for="num_cop" class="">Nº DO COPE</label>
                                <input value="{{ $provimento->num_cop }}" name="" id="" type="text"
                                    class="text-center form-control form-control-sm" readonly>
                            </div>
                        </div>
                    @endif
                    @if ($provimento->metodo)
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="num_cop" class="">METODO DE PROVIMENTO</label>
                                <input value="{{ $provimento->metodo }}" name="" id="" type="text"
                                    class="text-center form-control form-control-sm" readonly>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="form-row mb-2">
                    @can('view-sensitive-validation-fields-cpm-coordinator')
                        @if ($provimento->situacao === 'DESBLOQUEADO' && $provimento->pch === 'PENDENTE')
                            <div class="col-md-2" id="">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="situacao_provimento">situação do provimento</label>
                                    @if ($provimento->situacao_provimento === 'provida')
                                        <input value="PROVIDO" name="" id="" type="text"
                                            class="text-center form-control form-control-sm" readonly>
                                    @else
                                        <input value="EM TRÃMITE" name="" id="" type="text"
                                            class="text-center form-control form-control-sm" readonly>
                                    @endif
                                    {{-- <select name="situacao_provimento" id="situacao_provimento_detail"
                                        class="form-control select2" required>
                                        @if ($provimento->situacao_provimento === 'tramite')
                                            <option value="{{ $provimento->situacao_provimento }}">EM TRÂMITE</option>
                                            <option value="provida">PROVIDA</option>
                                        @endif
                                        @if ($provimento->situacao_provimento === 'provida')
                                            <option value="{{ $provimento->situacao_provimento }}">PROVIDO</option>
                                            <option value="tramite">EM TRÂMITE</option>
                                        @endif
                                    </select> --}}
                                </div>
                            </div>
                        @endif


                        {{-- @if ($provimento->situacao === 'DESBLOQUEADO' && $provimento->pch === 'PENDENTE')
                            <div class="col-md-6" id="arquivo_comprobatorio_row" style="display: {{ $provimento->situacao_provimento === 'provida' ? 'block' : 'none' }};">
                                <div class="form-group_disciplina">
                                    <label class="control-label font-weight-bold" for="arquivo_comprobatorio">
                                        <i class="fas fa-paperclip text-primary"></i> Arquivo Comprobatório 
                                        <span class="text-danger">*</span>
                                    </label>
                                
                                    
                                    @if ($provimento->arquivo_comprobatorio)
                                        <div class="alert alert-success mt-2 p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success mr-2"></i>
                                                <div>
                                                    <strong>Arquivo atual:</strong><br>
                                                    <a href="{{ route('provimento.arquivo', $provimento->arquivo_comprobatorio) }}" 
                                                       target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                                        <i class="fas fa-eye"></i> Visualizar: {{ Str::limit($provimento->arquivo_comprobatorio, 30) }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @error('arquivo_comprobatorio')
                                        <div class="alert alert-danger mt-2 p-2">
                                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endif --}}

                        @if ($provimento->situacao === 'BLOQUEADO' || $provimento->pch === 'OK')
                            <div class="col-md-2" id="">
                                {{-- <div class="form-group_disciplina">
                                    <label class="control-label" for="situacao_provimento">situação do provimento</label>
                                    <select name="situacao_provimento" id="situacao_provimento_detail"
                                        class="form-control select2" disabled>
                                        @if ($provimento->situacao_provimento === 'tramite')
                                            <option value="{{ $provimento->situacao_provimento }}">EM TRÂMITE</option>
                                        @endif
                                        @if ($provimento->situacao_provimento === 'provida')
                                            <option value="{{ $provimento->situacao_provimento }}">PROVIDO</option>
                                        @endif
                                    </select>
                                </div> --}}
                                <div class="form-group_disciplina">
                                    <label for="codigo_unidade_escolar" class="">situação do provimento</label>
                                    @if ($provimento->situacao_provimento === 'tramite')
                                        <input value="EM TRÂMITE" name="" id=""
                                            type="text"class="text-center form-control form-control-sm" readonly>
                                    @else
                                        <input value="PROVIDO" name="" id=""
                                            type="text"class="text-center form-control form-control-sm" readonly>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if (
                            $provimento->situacao_provimento === 'provida' &&
                                ($provimento->situacao === 'BLOQUEADO' || $provimento->pch === 'OK'))
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_encaminhamento" class="">DATA DO ENCAMINHAMENTO</label>
                                    <input value="{{ $provimento->data_encaminhamento }}" name="data_encaminhamento"
                                        id="data_encaminhamento" type="date"
                                        class="text-center form-control form-control-sm" readonly>
                                </div>
                            </div>
                        @endif
                        @if (
                            $provimento->situacao_provimento === 'provida' &&
                                ($provimento->situacao === 'DESBLOQUEADO' && $provimento->pch === 'PENDENTE'))
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_encaminhamento" class="">DATA DO ENCAMINHAMENTO</label>
                                    <input value="{{ $provimento->data_encaminhamento }}" name="data_encaminhamento"
                                        id="data_encaminhamento" type="date"
                                        class="text-center form-control form-control-sm">
                                </div>
                            </div>
                        @endif
                        @if ($provimento->situacao_provimento === 'tramite' && $provimento->situacao === 'BLOQUEADO')
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_encaminhamento" class="">DATA DO ENCAMINHAMENTO</label>
                                    <input value="{{ $provimento->data_encaminhamento }}" name="data_encaminhamento"
                                        id="data_encaminhamento" type="date"
                                        class="text-center form-control form-control-sm" readonly>
                                </div>
                            </div>
                        @endif
                        @if ($provimento->situacao_provimento === 'tramite' && $provimento->situacao === 'DESBLOQUEADO')
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_encaminhamento" class="">DATA DO ENCAMINHAMENTO</label>
                                    <input value="{{ $provimento->data_encaminhamento }}" name="data_encaminhamento"
                                        id="data_encaminhamento" type="date"
                                        class="text-center form-control form-control-sm">
                                </div>
                            </div>
                        @endif
                        @if ($provimento->situacao_provimento === 'tramite' && $provimento->situacao === 'BLOQUEADO')
                            <div id="data_assuncao_row_detail" class="col-md-2" hidden>
                                <div class="form-group_disciplina">
                                    <label for="codigo_unidade_escolar" class="">ASSUNÇÃO</label>
                                    <input value="{{ $provimento->data_assuncao }}" name="data_assuncao" id="data_assuncao"
                                        type="date" class="form-control form-control-sm">
                                </div>
                            </div>
                        @endif
                        @if ($provimento->situacao_provimento === 'tramite' && $provimento->situacao === 'DESBLOQUEADO')
                            <div id="data_assuncao_row_detail" class="col-md-2" hidden>
                                <div class="form-group_disciplina">
                                    <label for="codigo_unidade_escolar" class="">ASSUNÇÃO</label>
                                    <input value="{{ $provimento->data_assuncao }}" name="data_assuncao" id="data_assuncao"
                                        type="date" class="form-control form-control-sm">
                                </div>
                            </div>
                        @endif
                        @if (
                            $provimento->situacao_provimento === 'provida' &&
                                ($provimento->situacao === 'BLOQUEADO' || $provimento->pch === 'OK'))
                            <div id="data_assuncao_row_detail" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="codigo_unidade_escolar" class="">ASSUNÇÃO</label>
                                    <input value="{{ $provimento->data_assuncao }}" name="data_assuncao" id="data_assuncao"
                                        type="date" class="text-center form-control form-control-sm" readonly>
                                </div>
                            </div>
                        @endif
                        @if (
                            $provimento->situacao_provimento === 'provida' &&
                                ($provimento->situacao === 'DESBLOQUEADO' && $provimento->pch === 'PENDENTE'))
                            <div id="data_assuncao_row_detail" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="codigo_unidade_escolar" class="">ASSUNÇÃO</label>
                                    <input value="{{ $provimento->data_assuncao }}" name="data_assuncao" id="data_assuncao"
                                        type="date" class="text-center form-control form-control-sm">
                                </div>
                            </div>
                        @endif
                    @endcan
                    @can('view-cpg-technician')
                        <div class="col-md-2" id="">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="situacao_provimento">situação do provimento</label>
                                @if ($provimento->situacao_provimento === 'tramite')
                                    <input value="EM TRÂMITE" name="" id="" type="text"
                                        class="form-control form-control-sm" readonly>
                                @endif
                                @if ($provimento->situacao_provimento === 'provida')
                                    <input value="PROVIDO" name="" id="" type="text"
                                        class="form-control form-control-sm" readonly>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="data_encaminhamento" class="">DATA DE ENCAMINHAMENTO</label>
                                <input value="{{ $provimento->data_encaminhamento }}" name="data_encaminhamento"
                                    id="data_encaminhamento" type="date" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="data_assuncao" class="">ASSUNÇÃO</label>
                                <input value="{{ $provimento->data_assuncao }}" name="data_assuncao" id="data_assuncao"
                                    type="date" class="form-control form-control-sm" readonly>
                            </div>
                        </div>

                    @endcan
                    @can('view-cpg-technician-or-administrator')
                        <div class="col-md-2" id="">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="situacao_programacao">situação da
                                    Programação</label>
                                <select name="situacao_programacao" id="situacao_programacao" class="form-control select2">

                                    {{-- Opção para limpar/deixar em branco --}}
                                    <option value=""
                                        {{ old('situacao_programacao', $provimento->situacao_programacao ?? '') == '' ? 'selected' : '' }}>
                                        Selecione uma opção...
                                    </option>

                                    {{-- Opções existentes --}}
                                    <option value="NO ACOMPANHAMENTO"
                                        {{ old('situacao_programacao', $provimento->situacao_programacao ?? '') == 'NO ACOMPANHAMENTO' ? 'selected' : '' }}>
                                        NO ACOMPANHAMENTO
                                    </option>

                                    <option value="EM SUBSTITUIÇÃO"
                                        {{ old('situacao_programacao', $provimento->situacao_programacao ?? '') == 'EM SUBSTITUIÇÃO' ? 'selected' : '' }}>
                                        EM SUBSTITUIÇÃO
                                    </option>

                                    <option value="SEM INICIO DAS ATIVIDADES"
                                        {{ old('situacao_programacao', $provimento->situacao_programacao ?? '') == 'SEM INICIO DAS ATIVIDADES' ? 'selected' : '' }}>
                                        SEM INICIO DAS ATIVIDADES
                                    </option>

                                    <option value="NAO ASSUMIU"
                                        {{ old('situacao_programacao', $provimento->situacao_programacao ?? '') == 'NAO ASSUMIU' ? 'selected' : '' }}>
                                        NÃO ASSUMIU
                                    </option>
                                </select>
                            </div>
                        </div>
                        @can('view-blocked-provimento')
                            <div class="col-md-2 print-none" id="">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="situacao_provimento">situação</label>
                                    <select name="situacao" id="situacao" class="form-control select2" required>
                                        @if ($provimento->situacao === 'DESBLOQUEADO')
                                            <option value="{{ $provimento->situacao }}">{{ $provimento->situacao }}</option>
                                            <option value="BLOQUEADO">BLOQUEADO</option>
                                        @endif
                                        @if ($provimento->situacao === 'BLOQUEADO')
                                            <option value="{{ $provimento->situacao }}">{{ $provimento->situacao }}</option>
                                            <option value="DESBLOQUEADO">DESBLOQUEADO</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @endcan
                        @if (
                            $provimento->situacao_programacao === 'NAO ASSUMIU' ||
                                $provimento->situacao_programacao === 'SEM INICIO DAS ATIVIDADES')
                            <div class="col-md-2 print-none">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="situacao_carencia">CARÊNCIA EXISTE ?</label>
                                    <select name="situacao_carencia_existente" id="situacao_carencia"
                                        class="form-control select2">
                                        <option value=""></option>
                                        @php
                                            $selectedCarencia = old(
                                                'situacao_carencia_existente',
                                                $provimento->situacao_carencia_existente ?? '',
                                            );
                                        @endphp
                                        <option value="SIM" {{ $selectedCarencia === 'SIM' ? 'selected' : '' }}>SIM
                                        </option>
                                        <option value="NÃO" {{ $selectedCarencia === 'NÃO' ? 'selected' : '' }}>NÃO
                                        </option>
                                    </select>
                                </div>
                            </div>
                        @endif
                    @endcan



                    <div class="col-md-6 ml-auto" id="arquivo_comprobatorio_row"
                        style="display: {{ $provimento->situacao_provimento === 'provida' ? 'block' : 'none' }}; max-width:420px;">
                        <div class="form-group_disciplina d-flex flex-column align-items-end">
                            <label class="control-label font-weight-bold w-100 text-right" for="arquivo_comprobatorio">
                                <i class="fas fa-paperclip text-primary"></i> Termo de assunção
                            </label>
                            @if ($provimento->arquivo_comprobatorio)
                                <div class="mt-2">
                                    <div class="list-group">
                                        <div class="list-group-item d-flex justify-content-between align-items-center p-2"
                                            style="width: 415px">
                                            <div class="d-flex align-items-center">
                                                <i
                                                    class="fas {{ Str::endsWith(strtolower($provimento->arquivo_comprobatorio), '.pdf') ? 'fa-file-pdf text-danger' : 'fa-file-image text-primary' }} fa-lg mr-2"></i>
                                                <div class="small text-truncate" style="max-width:220px;">
                                                    <strong>Arquivo atual:</strong>
                                                    <div class="text-muted">
                                                        {{ Str::limit($provimento->arquivo_comprobatorio, 40) }}</div>
                                                </div>
                                            </div>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('provimento.arquivo', $provimento->arquivo_comprobatorio) }}"
                                                    target="_blank" class="btn btn-outline-primary">
                                                    <i class="fas fa-external-link-alt"></i> Abrir
                                                </a>
                                                <a href="{{ route('provimento.arquivo', $provimento->arquivo_comprobatorio) }}"
                                                    download class="btn btn-outline-secondary">
                                                    <i class="fas fa-download"></i> Baixar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            @error('arquivo_comprobatorio')
                                <div class="alert alert-danger mt-2 p-2 w-100">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>


                </div>
                @can('view-cpm-technician-or-administrator')
                    <div class="form-row">
                        <div id="data_assuncao_row" class="col-md-12">
                            <div class="form-group_disciplina">
                                <label for="obs">Observações CPM <i class="ti-pencil"></i></label>
                                <textarea name="obs" class="form-control" id="obs" rows="4">{{ $provimento->obs }}</textarea>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('view-cpg-technician')
                    <div class="form-row">
                        <div id="data_assuncao_row" class="col-md-12">
                            <div class="form-group_disciplina">
                                <label for="obs">Observações CPM <i class="ti-pencil"></i></label>
                                <textarea name="obs" class="form-control" id="obs" rows="4" readonly>{{ $provimento->obs }}</textarea>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('view-cpg-technician-or-administrator')
                    <div class="form-row">
                        <div id="data_assuncao_row" class="col-md-12">
                            <div class="form-group_disciplina">
                                <label for="obs">Observações CPG <i class="ti-pencil"></i></label>
                                <textarea name="obs_cpg" class="form-control" id="obs_cpg" rows="4">{{ $provimento->obs_cpg }}</textarea>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('view-cpm-technician')
                    <div class="form-row">
                        <div id="data_assuncao_row" class="col-md-12">
                            <div class="form-group_disciplina">
                                <label for="obs">Observações CPG <i class="ti-pencil"></i></label>
                                <textarea name="obs_cpg" class="form-control" id="obs_cpg" rows="4" readonly>{{ $provimento->obs_cpg }}</textarea>
                            </div>
                        </div>
                    </div>
                @endcan
                <div class="buttons d-flex justify-content-between align-middle print-none">
                    @if (Auth::user()->profile != 'consulta')
                        <div id="buttons" class="buttons">
                            <button id="" class="button" type="submit">
                                <span class="button__text">ATUALIZAR</span>
                                <span class="button__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    @endif
                    <a href="/detalhar_carencia/{{ $provimento->id_carencia }}"><button id="" type="button"
                            class="btn  btn-primary"><i class="ti-layers-alt"></i> ORIGEM DA VAGA</button></a>
                </div>

            </form>
        </div>
    </div>


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
@endsection
