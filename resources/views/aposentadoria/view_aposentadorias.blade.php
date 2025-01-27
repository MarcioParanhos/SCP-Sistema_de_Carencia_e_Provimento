@extends('layout.main')

@section('title', 'SCP - Processos Tramitados')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif

<style>
    .btn {
        padding: 6px !important;
    }

    .outroteste {
        display: flex !important;
        flex-direction: row !important;
    }

    .uppercase-option {
        text-transform: uppercase;
    }

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
        font-size: 12px !important;
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

<div class="bg-primary card text-white card_title">
    <h4 class=" title_show_carencias">PROCESSOS TRAMITADOS PARA APOSENTADORIA (SUPREV)</h4>
</div>
<div id="aposentadoria_info" class="d-flex justify-content-between mb-4">
    <div id="aposentadoria_info_content" class="d-flex justify-content-between" style="width: 50%;">
        <div class="col-md-6">
            <table class="table-bordered">
                <tr>
                    <td colspan="2" class="pl-2 text-center bg-primary text-white subheader"><b>{{ $totalProcess }} - Processos Tramitados</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>pendentes análise CPG</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $totalProcessPendenteAnaliseCpg }}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>Não geraram carências</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $totalNaoCarencia }}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>Geraram carências</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $totalCarencia }}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>pendentes análise CPM</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $totalProcessPendenteAnaliseCpm }}</b></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table-bordered">
                <tr>
                    <td colspan="2" class="pl-2 text-center bg-primary text-white subheader"><b>TOTAL GERAL</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>Processos Finalizados</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $processosFinalizadosConclusao }}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>Processos finalizados por desistência</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $desistencia }}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>Processos finalizados por Publicação</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $publicacao }}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>Processos Pendente Finalização</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $totalProcess - $processosFinalizadosConclusao  }}</b></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="mb-2 ">
    <a id="active_filters" data-toggle="tooltip" data-placement="top" title="Filtros Personalizaveis" class="mb-2 btn bg-primary text-white" onclick="active_filters()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
            </svg>
        </a>
        @if((Auth::user()->profile === "cad_tecnico") ||(Auth::user()->profile === "administrador"))
        <button type="button" class="mb-2 btn bg-primary text-white" data-toggle="modal" data-target="#addNewProcess">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 5l0 14" />
                <path d="M5 12l14 0" />
            </svg>
        </button>
        @endif
        <a class="mb-2 btn bg-primary text-white" data-toggle="tooltip" data-placement="top" title="Download em Excel" target="_blank" href="{{ route('aposentadorias.excel') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-download">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M12 17v-6" />
                <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
            </svg>
        </a>
    </div>
</div>
<hr>
<form id="active_form" class="pr-4 pl-4" action="{{ route('aposentadorias.filter') }}" method="post" hidden>
    @csrf
    <div class="form-row">
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_nte_provimento_efetivos">NTE</label>
                <select name="nte_search" id="nte_search" class="form-control form-control-sm select2">
                    <option></option>
                    @foreach ($ntes as $nte)
                    <option class="text-uppercase" value="{{ $nte }}">{{ $nte }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_nte_provimento_efetivos">MUNICIPIO</label>
                <select name="municipio_search" id="municipio_search" class="form-control form-control-sm select2">
                    <option></option>
                    @foreach ($municipios as $municipio)
                    <option class="uppercase-option" value="{{ $municipio }}">{{ $municipio }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="cod_search" class="">COD. UE</label>
                <input value="" name="cod_search" id="cod_search" type="text" class="form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="tipo_processo_search">TIPO DE PROCESSO</label>
                <select name="tipo_processo_search" id="tipo_processo_search" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="EXONERAÇÃO">EXONERAÇÃO</option>
                    <option value="ÓBITO">ÓBITO</option>
                    <option value="APOS. VOLUNTÁRIA">APOS. VOLUNTÁRIA</option>
                    <option value="APOS. COMPULSÓRIA">APOS. COMPULSÓRIA</option>
                    <option value="APOS. POR INCAPACIDADE">APOS. POR INCAPACIDADE</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="matricula_search" class="">MATRICULA</label>
                <input value="" name="matricula_search" id="matricula_search" type="number" class="form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="conclusao_search">CONCLUSÃO</label>
                <select name="conclusao_search" id="conclusao_search" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="PUBLICAÇÃO">PUBLICAÇÃO</option>
                    <option value="DESISTÊNCIA">DESISTÊNCIA</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label class="control-label" for="carencia_search">COM CARÊNCIA</label>
                <select name="carencia_search" id="carencia_search" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="Sim">SIM</option>
                    <option value="Não">NÃO</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label class="control-label" for="carencia_pendente_cpg">ANÁLISE CPG</label>
                <select name="carencia_pendente_cpg" id="carencia_pendente_cpg" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="Pendente">PENDENTE</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="local_carencia_search">LOCAL DE CARÊNCIA</label>
                <select name="local_carencia_search" id="local_carencia_search" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="Lotação">LOTAÇÃO</option>
                    <option value="Complementação">COMPLEMENTAÇÃO</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label class="control-label" for="pendencia_cpm">ANÁLISE CPM</label>
                <select name="pendencia_cpm" id="pendencia_cpm" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="Pendente">PENDENTE</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="forma_suprimento_search">FORMA DE SUPRIMENTO</label>
                <select name="forma_suprimento_search" id="forma_suprimento_search" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="INGRESSO">INGRESSO</option>
                    <option value="RELOTAÇÃO">RELOTAÇÃO</option>
                    <option value="REMOÇÃO">REMOÇÃO</option>
                    <option value="PRÓPRIA UE">NA PRÓPRIA UE</option>
                    <option value="COMPLEMENTAÇÃO">COMPLEMENTAÇÃO</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label for="num_process_search" class="">Nº DO PROCESSO</label>
                <input id="num_process_search" name="num_process_search" type="text" class="form-control form-control-sm" data-mask="000.0000.0000.0000000-00">
            </div>
        </div>
    </div>
    <div id="buttons" class="buttons d-flex align-items-center">
        <button id="" class="button" type="submit">
            <span class="button__text">BUSCAR</span>
            <span class="button__icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                    <path d="M21 21l-6 -6" />
                </svg>
            </span>
        </button>
    </div>
    <hr>
</form>
<div class="table-responsive">
    <table id="consultarCarencias" style="width: 100%;" class="table-bordered table-sm table ">
        <thead class="bg-primary text-white">
            <tr>
                <td style="width: 70%;" class="text-center" rowspan="2" colspan="7">INFORMAÇÕES CAD - PROCESSOS ENVIADOS PARA ATO - APOSENTADORIA SUPREV</td>
                <td style="width: 20%;" class="text-center" colspan="3">ANÁLISE CPG</td>
                <td style="width: 10%;" class="text-center" colspan="1">ANÁLISE CPM</td>
                <td style="width: 2%;" class="text-center" colspan="1" rowspan="3">AÇÃO</td>
            </tr>
            <tr>
                <td style="width: 8%;" class="text-center" rowspan="2" colspan="1">CARÊNCIA</td>
                <td class="text-center" colspan="2">LOCAL DA CARÊNCIA</td>
                <td class="text-center" rowspan="2" colspan="1">FORMA DE SUPRIMENTO</td>
            </tr>
            <tr class="text-center">
                <td style="width: 12%;">PROCESSO SEI</td>
                <td>NTE</td>
                <td>MUNICIPIO</td>
                <td>MATRÍCULA</td>
                <td>SERVIDOR</td>
                <td>TIPO</td>
                <td>CONCLUSÃO</td>
                <td>LOTAÇÃO</td>
                <td>COMP.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($processos as $processo)
            <tr>
                <td class="text-center">{{ $processo->num_process }}</td>
                @if ($processo->nte > 9)
                <td class="text-center">{{ $processo->nte }}</td>
                @else
                <td class="text-center">0{{ $processo->nte }}</td>
                @endif
                <td class="text-center text-uppercase">{{ $processo->municipio }}</td>
                <td class="text-center">{{ $processo->matricula }}</td>
                <td class="text-center">{{ $processo->servidor }}</td>
                <td class="text-center">{{ $processo->situacao_processo }}</td>
                @if (($processo->conclusao == null) && ($processo->forma_suprimento !=null))
                <td class="text-center text-danger"><span class="badge badge-pill badge-warning">PENDENTE</span></td>
                @elseif (($processo->conclusao == null) && ($processo->carencia == "Não"))
                <td class="text-center text-danger"><span class="badge badge-pill badge-warning">PENDENTE</span></td>
                @else
                <td class="text-center">{{ $processo->conclusao }}</td>
                @endif
                @if ( $processo->carencia === null)
                @if ($processo->conclusao == null)
                <td class="text-center text-danger"><span class="badge badge-pill badge-warning">PENDENTE</span></td>
                @else
                <td class="text-center">-</td>
                @endif
                @elseif ($processo->carencia === 'Sim')
                <td class="text-center text-danger"><span class="badge badge-pill badge-danger">SIM</span></td>
                @else
                <td class="text-center text-danger"><span class="badge badge-pill badge-primary">NÃO</span></td>
                @endif
                @if ( $processo->carencia_lot === null)
                <td class="text-center">-</td>
                @elseif ($processo->carencia_lot === 'Sim')
                <td class="text-center text-danger"><span class="badge badge-pill badge-danger">SIM</span></td>
                @else
                <td class="text-center text-danger"><span class="badge badge-pill badge-primary">NÃO</span></td>
                @endif
                @if ( $processo->carencia_comp === null)
                <td class="text-center">-</td>
                @elseif ($processo->carencia_comp === 'Sim')
                <td class="text-center text-danger"><span class="badge badge-pill badge-danger">SIM</span></td>
                @else
                <td class="text-center text-danger"><span class="badge badge-pill badge-primary">NÃO</span></td>
                @endif
                @if ( $processo->forma_suprimento === null)
                @if (($processo->conclusao != "DESISTÊNCIA") && ( $processo->carencia === "Sim"))
                <td class="text-center text-danger"><span class="badge badge-pill badge-warning">PENDENTE</span></td>
                @else
                <td class="text-center">-</td>
                @endif
                @else
                <td class="text-center">{{ $processo->forma_suprimento }}</td>
                @endif
                @if ($processo->conclusao != null)
                <td class="text-center text-danger"><a onclick="view('<?php echo $processo->id; ?>')"><span class="badge badge-pill badge-success">PROCESSO FINALIZADO</span></a></td>
                @elseif (($processo->conclusao == null) && ($processo->carencia != null) && ( $processo->forma_suprimento != null))
                <td class="text-center text-danger"><a href="/aposentadorias/view/{{ $processo->id }}"><span class="badge badge-pill text-white badge-secondary">AÇÕES FINALIZADAS</span></a></td>
                @elseif ($processo->carencia == "Não")
                <td class="text-center text-danger"><a href="/aposentadorias/view/{{ $processo->id }}"><span class="badge badge-pill text-white badge-secondary">AÇÕES FINALIZADAS</span></a></td>
                @elseif (($processo->conclusao == null) && ($processo->carencia != 'Não'))
                <td class="text-center">
                    <div class="btn-group dropleft">
                        <button type="button" class="btn  btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu">
                            <a class="text-primary dropdown-item" href="/aposentadorias/view/{{ $processo->id }}"><i class="fas fa-eye"></i> Ver</a>
                            <a title="Excluir" id="" onclick="destroyProcesso('{{ $processo->id }}')" class="text-danger dropdown-item"><i class="ti-trash"></i> Excluir</a>
                        </div>
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="addNewProcess" tabindex="-1" role="dialog" aria-labelledby="TituloaddNewProcess" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloaddNewProcess">Adicionar novo processo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('aposentadorias.create') }}" method="post">
                @csrf
                <input value="{{ Auth::user()->name }}" id="usuario" name="usuario" type="text" class="form-control form-control-sm" hidden>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group_disciplina">
                                    <label for="nte" class="">Nº DO PROCESSO</label>
                                    <input value="" id="num_process" name="num_process" type="text" class="form-control form-control-sm" data-mask="000.0000.0000.0000000-00" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="process_situation">TIPO DO PROCESSO</label>
                                    <select name="process_situation" id="process_situation" class="form-control form-control-sm select2" required>
                                        <option></option>
                                        <option value="EXONERAÇÃO">EXONERAÇÃO</option>
                                        <option value="ÓBITO">ÓBITO</option>
                                        <option value="APOS. VOLUNTÁRIA">APOS. VOLUNTÁRIA</option>
                                        <option value="APOS. COMPULSÓRIA">APOS. COMPULSÓRIA</option>
                                        <option value="APOS. POR INCAPACIDADE">APOS. POR INCAPACIDADE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="outroteste form-group_disciplina">
                                    <div>
                                        <label for="cadastro" class="">Matrícula / CPF</label>
                                        <input value="" minlength="8" maxlength="11" name="cadastro" id="cadastro" type="cadastro" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="btn_carencia_seacrh">
                                        <button id="cadastro_btn" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchServidorCompleto()">
                                            <i class="ti-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group_disciplina">
                                    <label for="nte" class="">SERVIDOR</label>
                                    <input value="" id="servidor" name="servidor" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="nte" class="">VINCULO</label>
                                    <input value="" id="vinculo" name="vinculo" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group_disciplina">
                                    <label for="nte" class="">REGIME</label>
                                    <input value="" name="regime" required id="regime" type="text" class=" form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group_disciplina">
                                    <label for="nte" class="">NTE</label>
                                    <input value="" name="nte" required id="nte" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group_disciplina">
                                    <label for="municipio" class="">MUNICIPIO</label>
                                    <input value="" name="municipio" required id="municipio" type="text" class=" form-control form-control-sm text-uppercase" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group_disciplina">
                                    <label for="unidade" class="">UNIDADE DE LOTAÇÃO</label>
                                    <input value="" name="unidade_escolar" required id="unidade" type="text" class=" form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="unidade" class="">COD. UNIDADE LOTAÇÃO</label>
                                    <input value="" name="cod_unidade" required id="cod_unidade" type="text" class=" form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>
                        <div id="row_unidade_complementar" class="row" hidden>
                            <hr>
                            <div class="col-md-4">
                                <div class="form-group_disciplina">
                                    <label for="unidade_escolar_complementacao" class="">UNIDADE DE COMPLEMENTAÇÃO</label>
                                    <input value="" name="unidade_complementar" required id="unidade_escolar_complementacao" type="text" class=" form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="cod_unidade_complementacao" class="">COD. UNIDADE DE COMPLEMENTAÇÃO</label>
                                    <input value="" name="cod_unidade_complementar" required id="cod_unidade_complementacao" type="text" class=" form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-minimize">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 9l4 0l0 -4" />
                            <path d="M3 3l6 6" />
                            <path d="M5 15l4 0l0 4" />
                            <path d="M3 21l6 -6" />
                            <path d="M19 9l-4 0l0 -4" />
                            <path d="M15 9l6 -6" />
                            <path d="M19 15l-4 0l0 4" />
                            <path d="M15 15l6 6" />
                        </svg>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="TituloaddNewProcess" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloaddNewProcess">Visualização do processo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group_disciplina">
                                <label for="nte" class=""><strong>Nº DO PROCESSO</strong></label>
                                <input value="" id="process" type="text" class="form-control form-control-sm" data-mask="000.0000.0000.0000000-00" style="border: none;">
                                <input value="" id="id_process" type="text" class="form-control form-control-sm" hidden>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group_disciplina">
                                <label for="nte" class=""><strong>TIPO DE PROCESSO</strong></label>
                                <input value="" id="type" type="text" class="form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="view_carencia" class=""><strong>POSSUI CARÊNCIA?</strong></label>
                                <input value="" id="view_carencia" type="text" class="text-uppercase form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                        <div class="col-md-4" id="local_carencia_hidden" hidden>
                            <div class="form-group_disciplina">
                                <label for="view_local_carencia" class=""><strong>LOCAL DA CARÊNCIA</strong></label>
                                <input value="" id="view_local_carencia" type="text" class="text-uppercase form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6 class="text-center">UNIDADE DE LOTAÇÃO - <span id="view_unidade"></span></h6>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="form-group_disciplina">
                                <label for="nte" class=""><strong>NTE</strong></label>
                                <input value="" id="view_nte" type="view_nte" class="form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group_disciplina">
                                <label for="view_municipio" class=""><strong>MUNICIPIO</strong></label>
                                <input value="" id="view_municipio" type="text" class="form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="view_cod_unidade" class=""><strong>COD. UNIDADE</strong></label>
                                <input value="" id="view_cod_unidade" type="text" class="form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6 class="text-center">SERVIDOR</h6>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="form-group_disciplina">
                                <label for="view_servidor" class=""><strong>NOME</strong></label>
                                <input value="" id="view_servidor" type="text" class="form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group_disciplina">
                                <label for="view_matricula" class=""><strong>MATRICULA</strong></label>
                                <input value="" id="view_matricula" type="text" class="form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="view_matricula" class=""><strong>REGIME</strong></label>
                                <input value="40h" id="" type="text" class="form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group_disciplina">
                                <label for="view_matricula" class=""><strong>VINCULO</strong></label>
                                <input value="EFETIVO" id="" type="text" class="form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                        <div class="col-md-2" hidden>
                            <div class="form-group_disciplina">
                                <label for="nte" class=""><strong>CONCLUSÃO</strong></label>
                                <input value="" id="conclusao" type="text" class=" form-control form-control-sm" style="border: none;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-minimize" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 9l4 0l0 -4" />
                        <path d="M3 3l6 6" />
                        <path d="M5 15l4 0l0 4" />
                        <path d="M3 21l6 -6" />
                        <path d="M19 9l-4 0l0 -4" />
                        <path d="M15 9l6 -6" />
                        <path d="M19 15l-4 0l0 4" />
                        <path d="M15 15l6 6" />
                    </svg>
                </button>
                <a href="" target="_blank" id="imprimir_aposentadoria">
                    <button type="button" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                            <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                        </svg>
                    </button>
                </a>
            </div>
        </div>
    </div>
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
                    text: 'Não é possível excluir esse motivo porque existem carências associadas.',
                })
            } else if (session_message.value === "error_duplicated") {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Lamentamos, mas não podemos adicionar este processo, pois já há um registro com o mesmo número em nosso banco de dados.',
                })
            } else if (session_message.value === "error_server_null") {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Lamentamos, mas não podemos salvar este processo sem um servidor valido selecionado.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Processo adicionado com sucesso!',
                })
            } else if (session_message.value === "success_destroy") {
                Swal.fire({
                    icon: 'success',
                    text: 'Processo excluido com sucesso!',
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
<script>
    function view(id) {

        fetch(`/aposentadorias/select/${id}`).then((response) => response.json()).then((data) => {
            const link = "/aposentadorias/print/" + data.aposentadoria.id
            let modal = new bootstrap.Modal(document.getElementById("viewModal"));
            let process = document.getElementById("process");
            let type = document.getElementById("type");
            let conclusao = document.getElementById("conclusao");
            let view_nte = document.getElementById("view_nte");
            let view_cod_unidade = document.getElementById("view_cod_unidade");
            let view_municipio = document.getElementById("view_municipio");
            let view_unidade = document.getElementById("view_unidade");
            let view_servidor = document.getElementById("view_servidor");
            let view_matricula = document.getElementById("view_matricula");
            let view_carencia = document.getElementById("view_carencia");
            let view_local_carencia = document.getElementById("view_local_carencia");
            let local_carencia_hidden = document.getElementById("local_carencia_hidden");
            let imprimir_aposentadoria = document.getElementById("imprimir_aposentadoria")
            process.value = data.aposentadoria.num_process;
            type.value = data.aposentadoria.situacao_processo;
            conclusao.value = data.aposentadoria.conclusao;
            view_nte.value = data.aposentadoria.nte;
            view_cod_unidade.value = data.aposentadoria.cod_unidade;
            view_municipio.value = data.aposentadoria.municipio;
            view_unidade.innerHTML = data.aposentadoria.unidade_escolar;
            view_servidor.value = data.aposentadoria.servidor;
            view_matricula.value = data.aposentadoria.matricula;
            view_carencia.value = data.aposentadoria.carencia;
            imprimir_aposentadoria.setAttribute("href", link);
            if (data.aposentadoria.carencia == "Sim") {
                local_carencia_hidden.hidden = false
                if ((data.aposentadoria.carencia_lot == "Sim") && ((data.aposentadoria.carencia_comp == "Não") || (data.aposentadoria.carencia_comp == null))) {
                    view_local_carencia.value = "LOTAÇÃO"
                } else if ((data.aposentadoria.carencia_comp == "Sim") && ((data.aposentadoria.carencia_lot == "Não") || (data.aposentadoria.carencia_lot == null))) {
                    view_local_carencia.value = "COMPLEMENTAÇÃO"
                } else {
                    view_local_carencia.value = "AMBOS (LOTAÇÃO + COMPLEMENTAÇÃO)"
                }
            }
            modal.show();
        }).catch((error) => {
            console.error("Erro ao buscar a marca: " + error);
        });
    }
</script>
<script>
    function searchServidorCompleto() {
        const matricula_servidor_completo = document.getElementById("cadastro");

        $.get("/consultarServidorCompleto/" + matricula_servidor_completo.value, function(response) {

            if (response) {
                const servidor = document.getElementById(
                    "servidor"
                );
                const vinculo = document.getElementById(
                    "vinculo"
                );
                const regime = document.getElementById(
                    "regime"
                );
                const nte = document.getElementById(
                    "nte"
                );
                const municipio = document.getElementById(
                    "municipio"
                );
                const unidade = document.getElementById(
                    "unidade"
                );
                const cod_unidade = document.getElementById(
                    "cod_unidade"
                );
                const unidade_escolar_complementacao = document.getElementById(
                    "unidade_escolar_complementacao"
                );
                const cod_unidade_complementacao = document.getElementById(
                    "cod_unidade_complementacao"
                );
                const row_unidade_complementar = document.getElementById(
                    "row_unidade_complementar"
                );

                if (response.data.unidade_complementar) {
                    row_unidade_complementar.hidden = false
                } else {
                    row_unidade_complementar.hidden = true
                }

                servidor.value = response.data.nome;
                vinculo.value = response.data.tipo_servidor;
                regime.value = response.data.regime;
                nte.value = response.data.nte;
                municipio.value = response.data.municipio;
                unidade.value = response.data.unidade;
                cod_unidade.value = response.data.cod_unidade;
                unidade_escolar_complementacao.value = response.data.unidade_complementar;
                cod_unidade_complementacao.value = response.data.cod_unidade_complementar;

            } else {
                Swal.fire({
                    icon: "warning",
                    title: "Atenção!",
                    text: "Servidor não encontrado. Tente novamente.",
                });
            }
        });
    }
</script>
<script>
    function destroyProcesso(id) {

        const modalDestroyAposentadoria = document.querySelector("#modalDestroyAposentadoria a");
        // Declarando a variavel Link
        let link = "/aposentadorias/destroy/" + id
        // Inserindo um elemento dentro do elemento selecionado com QuerySelector
        modalDestroyAposentadoria.setAttribute("href", link)
        //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
        $("#modalDestroyAposentadoria").modal({
            show: true
        });

    }
</script>
<!-- Modal Delete Provimento-->
<div class="modal fade" id="modalDestroyAposentadoria" tabindex="-1" role="dialog" aria-labelledby="TituloModalDeleteProvimento aria-hidden=" true">
    <div class="modal-dialog modal-dialog-centered" role="document" style=" margin-top: 0px; margin-bottom: 0px;">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h4 class="modal-title text-center text-dark" id="TituloModalDeleteProvimento"><strong>Excluir Dados</strong></h4>
            </div>
            <div class="modal-body modal-destroy">
                <h4><strong>Tem certeza?</strong></h4>
                <h4><strong>O registro sera excluido permanentemente!</strong></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                <a title="Excluir"><button id="btn_delete_provimento" type="button" class="btn float-right btn-danger"><i class="fas fa-trash-alt"></i> Excluir</button></a>
            </div>
        </div>
    </div>
</div>