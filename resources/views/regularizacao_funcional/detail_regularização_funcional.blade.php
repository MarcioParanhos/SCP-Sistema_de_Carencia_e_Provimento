@extends('layout.main')

@section('title', 'SCP - Reg. Funcional')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif

<style>
    .btn {
        padding: 6px !important;
    }

    .icon-tabler-search,
    .icon-tabler-trash,
    .icon-tabler-replace {
        width: 16px;
        height: 16px;
    }

    td span {
        font-size: 10px !important;
        font-weight: 900 !important;
        border-radius: 50% !important;
    }
</style>

<style type="text/css" media="print">
    .col-md-1 {
        width: 10% !important;  
    }
    .col-md-6 {
        width: 45% !important;   
    }
    /* Forçar que as colunas mantenham o layout durante a impressão */
    .col-md-2, .col-sm-6, .col-md-3 {
      float: left;
      width: 33.33% !important; /* Ajuste para colunas de 6 */
    }
    
    .col-md-4 {
      width: 25% !important; /* Ajuste para colunas de 4 */
    }
    
    .col-md-3 {
      width: 25% !important; /* Ajuste para colunas de 3 */
    }
    
    /* Estilos adicionais, caso necessário */
    .form-group {
      margin-bottom: 1rem; /* Manter espaçamento entre campos */
    }
    
    /* Se estiver usando flexbox, pode ser necessário forçar o display em blocos */
    .row {
      display: flex;
      flex-wrap: wrap;
    }
    body {
        zoom: 0.77;
        /* Reduz a escala para 90% */
        margin: 0;
    }

    @page {
        size: auto;
        margin: 5mm;
    }

    tr {
        padding: 0 !important;
    }

    h1 {
        display: none;
    }

    .print-none {
        display: none !important;
    }
</style>

<div class="card shadow rounded">
    <div class="shadow bg-primary text-white card_title">
        <h4 class="title_show_carencias">Regularização Funcional Detalhada</h4>
        <div class="print-none  d-flex justify-content-center align-items-center">
            <a data-toggle="tooltip" data-placement="top" title="Voltar" class="m-1 btn bg-white text-primary" href="/regularizacao_funcional/filter">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back-up">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 14l-4 -4l4 -4" />
                    <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                </svg>
            </a>
            <a class="m-1 btn bg-white text-primary" data-toggle="tooltip" data-placement="top" title="Imprimir" onclick="javascript:window.print();">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                    <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                </svg>
            </a>
        </div>
    </div>
    <div class="edit-container subheader ">
        <div class="user-edit">
            <i class="ti-pencil-alt"></i>
            <h4>{{ $regularizacao->create_user }}</h4>
        </div>
        <div class="user-edit">
            <i class="ti-time"></i>
            <h4>{{ \Carbon\Carbon::parse($regularizacao->created_at)->format('d/m/Y') }}</h4>
        </div>
    </div>
    <form class="p-4" action="/regularizacao_funcional/update" method="post">
        @csrf
        @method ('PUT')
        <input value="{{ $regularizacao->id  }}" id="" name="regularizacao_id" type="text" class="form-control form-control-sm" hidden>
        <h5 class="border-bottom border-primary">SERVIDOR</h5>
        <div class="form-row pt-2">
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="cadastro">MATRICULA</label>
                    <input value="{{ $servidor_regularizacao[$regularizacao->id]->cadastro }}" name="" id="cadastro" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group_disciplina">
                    <label class="control-label" for="nome">NOME</label>
                    <input value="{{ $servidor_regularizacao[$regularizacao->id]->nome }}" name="" id="nome" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="vinculo">VINCULO</label>
                    <input value="{{ $servidor_regularizacao[$regularizacao->id]->vinculo }}" name="" id="vinculo" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="regime">REGIME</label>
                    <input value="{{ $servidor_regularizacao[$regularizacao->id]->regime }}h" name="" id="regime" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="regime">TIPO DE REGULARIZAÇÃO</label>
                    <input value="{{ $regularizacao->tipo_regularizacao }}" name="" id="tipo_regularizacao" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
        </div>
        <h5 class="border-bottom mt-3 border-primary">UNIDADE ESCOLAR DE ORIGEM</h5>
        <div class="form-row pt-2">
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="nte_origem">NTE</label>
                    <input value="{{ $nome_uee_origem[$regularizacao->id]->nte }}" name="" id="nte_origem" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="municipio_origem">MUNICIPIO</label>
                    <input value="{{ $nome_uee_origem[$regularizacao->id]->municipio }}" name="" id="municipio_origem" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group_disciplina">
                    <label class="control-label" for="municipio_origem">UNIDADE ESCOLAR</label>
                    <input value="{{ $nome_uee_origem[$regularizacao->id]->unidade_escolar }}" name="" id="municipio_origem" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="municipio_origem">COD. UEE</label>
                    <input value="{{ $nome_uee_origem[$regularizacao->id]->cod_unidade }}" name="" id="municipio_origem" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="municipio_origem">COD. UO</label>
                    <input value="{{ $regularizacao->uo_origem }}" name="" id="uo_origem" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
        </div>
        <h5 class="mt-3 border-bottom  border-primary">UNIDADE ESCOLAR DE DESTINO</h5>
        <div class="form-row pt-2">
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="nte_origem">NTE</label>
                    <input value="{{ $nome_uee_destino[$regularizacao->id]->nte }}" name="" id="nte_origem" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="municipio_origem">MUNICIPIO</label>
                    <input value="{{ $nome_uee_destino[$regularizacao->id]->municipio }}" name="" id="municipio_origem" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group_disciplina">
                    <label class="control-label" for="municipio_origem">UNIDADE ESCOLAR</label>
                    <input value="{{ $nome_uee_destino[$regularizacao->id]->unidade_escolar }}" name="" id="municipio_origem" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="municipio_origem">COD. UEE</label>
                    <input value="{{ $nome_uee_destino[$regularizacao->id]->cod_unidade }}" name="" id="municipio_origem" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="municipio_origem">COD. UO</label>
                    <input value="{{ $regularizacao->uo_destino }}" name="" id="uo_destino" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
        </div>
        <h5 class="mt-3 border-bottom  border-primary">INFORMAÇÕES GERAIS</h5>
        <div class="form-row pt-2">
            @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
            <div class="col-md-3" id="motivo_vaga_row">
                <div class="form-group_disciplina">
                    <label class="control-label" for="tipo_regularizacao">TIPO DE REGULARIZAÇÃO</label>
                    <select name="tipo_regularizacao" id="tipo_regularizacao" class="form-control select2" required>
                        <option value="{{ $regularizacao->tipo_regularizacao }}" selected>{{ $regularizacao->tipo_regularizacao }}</option>
                        <option value="REMOÇÃO">REMOÇÃO</option>
                        <option value="RELOTAÇÃO">RELOTAÇÃO</option>
                        <option value="MUDAR LOCAL DE INGRESSO">MUDAR LOCAL DE INGRESSO</option>
                        <option value="ABRIR COMPLEMENTAÇÃO">ABRIR COMPLEMENTAÇÃO</option>
                        <option value="FECHAR COMPLEMENTAÇÃO">FECHAR COMPLEMENTAÇÃO</option>
                        <option value="RESCISÃO CONTRATO REDA">RESCISÃO CONTRATO REDA</option>
                        <option value="RETIFICAÇÃO">RETIFICAÇÃO</option>
                        <option value="TORNAR SEM EFEITO ATO">TORNAR SEM EFEITO ATO</option>
                    </select>
                </div>
            </div>
            @else
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="regime">TIPO DE REGULARIZAÇÃO</label>
                    <input value="{{ $regularizacao->tipo_regularizacao }}" name="" id="tipo_regularizacao" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            @endif
            @if ($regularizacao->tipo_retificacao != "")
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="tipo_retificacao">TIPO DE RETIFICAÇÃO</label>
                    <input value="{{ $regularizacao->tipo_retificacao }}" name="" id="tipo_retificacao" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            @elseif ($regularizacao->tipo_tornar_sem_efeito != "")
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="tipo_tornar_sem_efeito">TIPO DE RETIFICAÇÃO</label>
                    <input value="{{ $regularizacao->tipo_tornar_sem_efeito }}" name="" id="tipo_tornar_sem_efeito" type="text" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            @endif
            @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="regime">DATA DE ASSUNÇÃO</label>
                    <input value="{{ $regularizacao->data }}" name="data" id="tipo_regularizacao" type="date" class="form-control form-control-sm" required>
                </div>
            </div>
            @else
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="regime">DATA DE ASSUNÇÃO</label>
                    <input value="{{ $regularizacao->data }}" name="data" id="tipo_regularizacao" type="date" class="form-control form-control-sm" required readonly>
                </div>
            </div>
            @endif
        </div>
        <hr>
        <div class="card shadow mb-4">
            <div class="d-flex justify-content-between">
                <h5 class="mb-4 pl-4 pt-3 text-primary text-center"><strong>ANÁLISE CPM</strong></h5>
                <div class="edit-container subheader">
                    <div class="user-edit">
                        <i class="ti-pencil-alt"></i>
                        <h4>{{ $regularizacao->update_user_cpm }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-12 d-flex p-3">
                <div class="form-row col-md-4">
                    @if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_tecnico"))
                    <input value="{{ Auth::user()->name }}" name="update_user_cpm" id="" type="text" class="form-control form-control-sm" hidden>
                    <div class="col-md-12" id="">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="situacao_cpm">SITUAÇÃO DA REGULARIZAÇÃO</label>
                            <select name="situacao_cpm" id="situacao_cpm" class="form-control select2">
                                <option value="{{ $regularizacao->situacao_cpm }}" selected>{{ $regularizacao->situacao_cpm }}</option>
                                <option value="EM ANÁLISE">EM ANÁLISE</option>
                                <option value="PENDENTE">PENDENTE</option>
                                <option value="PENDENTE CPG">PENDENTE CPG</option>
                                <option value="REGULARIZADA">REGULARIZADA</option>
                                <option value="ATO EM APROVAÇÃO">ATO EM APROVAÇÃO</option>
                                <option value="SERVIDOR AFASTADO">SERVIDOR AFASTADO</option>
                            </select>
                        </div>
                    </div>
                    @else
                    <div class="col-md-12">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="regime">SITUAÇÃO DA REGULARIZAÇÃO</label>
                            <input value="{{ $regularizacao->situacao_cpm }}" name="" id="tipo_regularizacao" type="text" class="form-control form-control-sm" required readonly>
                        </div>
                    </div>
                    @endif
                    @if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_tecnico"))
                    <div class="col-md-6" id="motivo_vaga_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="num_ato">Nº DO ATO</label>
                            <input value="{{ $regularizacao->num_ato }}" name="num_ato" id="num_ato" type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-md-6" id="motivo_vaga_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="data_ato">DATA DO ATO</label>
                            <input value="{{ $regularizacao->data_ato }}" name="data_ato" id="" type="date" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    @else
                    <div class="col-md-6" id="motivo_vaga_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="num_ato">Nº DO ATO</label>
                            <input value="{{ $regularizacao->num_ato }}" name="" id="" type="number" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-6" id="motivo_vaga_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="num_ato">DATA DO ATO</label>
                            <input value="{{ $regularizacao->data_ato }}" name="" id="" type="date" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    @endif
                </div>
                @if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_tecnico"))
                <div class="form-row col-md-8">
                    <div id="data_assuncao_row" class="col-md-12">
                        <div class="form-group_disciplina">
                            <label for="obs_cpm">Observações CPM <i class="ti-pencil"></i></label>
                            <textarea name="obs_cpm" class="form-control" id="obs_cpm" rows="6">{{ $regularizacao->obs_cpm }}</textarea>
                        </div>
                    </div>
                </div>
                @else
                <div class="form-row col-md-8">
                    <div id="data_assuncao_row" class="col-md-12">
                        <div class="form-group_disciplina">
                            <label for="obs_cpm">Observações CPM <i class="ti-pencil"></i></label>
                            <textarea name="obs_cpm" class="form-control" id="obs_cpm" rows="6" readonly>{{ $regularizacao->obs_cpm }}</textarea>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @if (($regularizacao->situacao_cpm === "REGULARIZADA") || ($regularizacao->situacao_cgi === "ATO EM APROVAÇÃO") || ($regularizacao->situacao_cgi === "PENDENTE CPM") || ($regularizacao->situacao_cgi === "EM ANÁLISE"))
            <hr>
            <div class="d-flex justify-content-between">
                <h5 class="mb-4 pl-4 pt-3 text-primary text-center"><strong>ANÁLISE CGI</strong></h5>
                <div class="edit-container subheader">
                    <div class="user-edit">
                        <i class="ti-pencil-alt"></i>
                        <h4>{{ $regularizacao->update_user_cgi }}</h4>
                    </div>
                </div>
            </div>
            @if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cgi_tecnico"))
            <input value="{{ Auth::user()->name }}" name="update_user_cgi" id="" type="text" class="form-control form-control-sm" hidden>
            <div class="col-md-12 d-flex p-3">
                <div class="form-row col-md-4">
                    <div class="col-md-12" id="">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="situacao_cpm">SITUAÇÃO DA REGULARIZAÇÃO</label>
                            <select name="situacao_cgi" id="situacao_cgi" class="form-control select2">
                                <option value="{{ $regularizacao->situacao_cgi }}" selected>{{ $regularizacao->situacao_cgi }}</option>
                                <option value="EM ANÁLISE">EM ANÁLISE</option>
                                <option value="REGULARIZADA">REGULARIZADA</option>
                                <option value="PENDENTE">PENDENTE</option>
                                <option value="PENDENTE CPM">PENDENTE CPM</option>
                                <option value="ATO EM APROVAÇÃO">ATO EM APROVAÇÃO</option>
                                <option value="AGUARDANDO INTEGRAÇÃO">AGUARDANDO INTEGRAÇÃO</option>

                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-md-12" id="motivo_vaga_row">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="situacao_cpm">SELECT EM ABERTO</label>
                                <select name="" id="" class="form-control select2">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div> -->
                </div>
                <div class="form-row col-md-8">
                    <div id="data_assuncao_row" class="col-md-12">
                        <div class="form-group_disciplina">
                            <label for="obs_cpm">Observações CGI <i class="ti-pencil"></i></label>
                            <textarea name="obs_cgi" class="form-control" id="obs_cgi" rows="6">{{ $regularizacao->obs_cgi }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-12 d-flex p-3">
                <div class="form-row col-md-4">
                    <div class="col-md-12" id="">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="situacao_cpm">SITUAÇÃO DA REGULARIZAÇÃO</label>
                            <input value="{{ $regularizacao->situacao_cgi }}" name="" id="" type="text" class="form-control form-control-sm" required readonly>
                        </div>
                    </div>
                </div>
                <div class="form-row col-md-8">
                    <div id="data_assuncao_row" class="col-md-12">
                        <div class="form-group_disciplina">
                            <label for="obs_cgi">Observações CGI <i class="ti-pencil"></i></label>
                            <textarea name="" class="form-control" id="obs_cgi" rows="6" readonly>{{ $regularizacao->obs_cgi }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <hr>
            @endif
            @if (($regularizacao->situacao_cgi === "REGULARIZADA") || ($regularizacao->situacao_cpm === "PENDENTE CPG") || ($regularizacao->situacao_cpg === "EM ANÁLISE") || ($regularizacao->situacao_cpg === "PENDENTE CPM") || ($regularizacao->situacao_cpg === "PENDENTE CGI"))
            <div class="d-flex justify-content-between">
                <h5 class="mb-4 pl-4 pt-3 text-primary text-center"><strong>ANÁLISE CPG</strong></h5>
                <div class="edit-container subheader">
                    <div class="user-edit">
                        <i class="ti-pencil-alt"></i>
                        <h4>{{ $regularizacao->update_user_cpg }}</h4>
                    </div>
                </div>
            </div>
            @if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpg_tecnico"))
            <input value="{{ Auth::user()->name }}" name="update_user_cpg" id="" type="text" class="form-control form-control-sm" hidden>
            <div class="col-md-12 d-flex p-3">
                <div class="form-row col-md-4">
                    <div class="col-md-12" id="">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="situacao_cpg">SITUAÇÃO DA REGULARIZAÇÃO</label>
                            <select name="situacao_cpg" id="situacao_cpg" class="form-control select2">
                                <option value="{{ $regularizacao->situacao_cpg }}" selected>{{ $regularizacao->situacao_cpg }}</option>
                                <option value="EM ANÁLISE">EM ANÁLISE</option>
                                <option value="REGULARIZADA">PROGRAMADO</option>
                                <option value="PENDENTE">PENDENTE</option>
                                <option value="PENDENTE CPM">PENDENTE CPM</option>
                                <option value="PENDENTE CGI">PENDENTE CGI</option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-md-12" id="motivo_vaga_row">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="situacao_cpm">SELECT EM ABERTO</label>
                                <select name="" id="" class="form-control select2">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div> -->
                </div>
                <div class="form-row col-md-8">
                    <div id="data_assuncao_row" class="col-md-12">
                        <div class="form-group_disciplina">
                            <label for="obs_cpm">Observações CPG <i class="ti-pencil"></i></label>
                            <textarea name="obs_cpg" class="form-control" id="obs_cpg" rows="6">{{ $regularizacao->obs_cpg }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-12 d-flex p-3">
                <div class="form-row col-md-4">
                    <div class="col-md-12" id="">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="situacao_cpm">SITUAÇÃO DA REGULARIZAÇÃO</label>
                            <input value="{{ $regularizacao->situacao_cpg }}" name="" id="" type="text" class="form-control form-control-sm" required readonly>
                        </div>
                    </div>
                </div>
                <div class="form-row col-md-8">
                    <div id="data_assuncao_row" class="col-md-12">
                        <div class="form-group_disciplina">
                            <label for="obs_cgi">Observações CPG <i class="ti-pencil"></i></label>
                            <textarea name="" class="form-control" id="obs_cgi" rows="6" readonly>{{ $regularizacao->obs_cpg }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
        <hr class="print-none ">
        @if($regularizacao->situacao_cpg != "PROGRAMADO")
        <div id="buttons" class="print-none  buttons">
            <!-- <button id="btn_submit_carencia" type="submit" class="btn btn-primary mr-2" >CADASTRAR</button> -->
            <button id="" class="button" type="submit">
                <span class="button__text">ATUALIZAR</span>
                <span class="button__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                    </svg>
                </span>
            </button>
        </div>
        @endif
    </form>
</div>

@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const session_message = document.getElementById("session_message");

        if (session_message) {
            if (session_message.value === "error") {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'A exclusão desta disciplina não é viável devido à existência de carências e provimentos a ela.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Registros atualizados!',
                })
            } else if (session_message.value === "delete_success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Disciplina excluida com sucesso!',
                })
            } else {

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Motivo de vaga adicionado com sucesso!',
                    showConfirmButton: true,
                })
            }
        }
    });
</script>