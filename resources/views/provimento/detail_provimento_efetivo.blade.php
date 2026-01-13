@extends('layout.main')

@section('title', 'SCP - Encaminhamento')

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

<div class="col-12 grid-margin stretch-card">
    <div class="card shadow rounded">
        <div class="shadow bg-primary text-white card_title">
            <h4 class="title_show_carencias">ENCAMINHAMENTO DE SERVIDOR EFETIVO</h4>
            <!-- <a class="mr-2" title="Voltar" href="/provimento/efetivo/filter">
                <button>
                    <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                        <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
                    </svg>
                    <span>VOLTAR</span>
                </button>
            </a> -->
            <div class="print-none  d-flex justify-content-center align-items-center">
                <a data-toggle="tooltip" data-placement="top" title="Voltar" class="m-1 btn bg-white text-primary" href="/encaminhamento/efetivo/show">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back-up">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                </a>
                <a class="m-1 btn bg-white text-primary" href="/provimentos/encaminhamento/{{ $provimento_efetivo->id }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Imprimir Encaminhamento">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                        <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                    </svg>
                    IMPRIMIR ENCAMINHAMENTO
                </a>
            </div>
        </div>
        <div class="info-edit">
            <div class="edit-container-homlogada">
                <div class="user-edit">
                    @if ($provimento_efetivo->pch == "OK")
                    <h4 class="p-2 text-white bg-success"><strong>BLOQUEADO PARA EDIÇÃO - ENCAMINHAMENTO VALIDADO PELA CPG</strong></h4>
                    @endif
                </div>
            </div>
            <div class="edit-container">
                <div class="user-edit">
                    <i class="ti-pencil-alt"></i>
                    <h4 class="subheader">{{$provimento_efetivo->user->name}}</h4>
                </div>
                <div class="user-edit">
                    <i class="ti-time"></i>
                    <h4 class="subheader">{{ \Carbon\Carbon::parse($provimento_efetivo->created_at)->format('d/m/Y') }}</h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="forms-sample" action="/provimento/efetivo/update/{{ $provimento_efetivo->id }}" method="post">
                @csrf
                @method ('PUT')
                <input value="{{ Auth::user()->id }}" id="usuario" name="user_id" type="number" class="form-control form-control-sm" hidden>
                <div id="servidor_row">
                @php
                    $srv = $servidor_encaminhado->servidorEncaminhado ?? $servidor_encaminhado->ingressoCandidato ?? null;
                    $srvNte = $srv->nte ?? $srv->uee_code ?? $srv->uee_name ?? '';
                    $srvName = $srv->name ?? $srv->nome ?? '';
                    $srvCpf = $srv->cpf ?? '';
                    $srvCargo = $srv->cargo ?? $srv->funcao ?? '';
                    $srvId = $srv->id ?? ($servidor_encaminhado->servidorEncaminhado->id ?? '');

                    $sSub = $servidor_subistituido->servidorSubstituido ?? $servidor_subistituido->ingressoCandidato ?? null;
                    $sSubCadastro = $sSub->cadastro ?? $sSub->matricula ?? '';
                    $sSubName = $sSub->nome ?? $sSub->name ?? '';
                    $sSubId = $sSub->id ?? ($servidor_subistituido->servidorSubstituido->id ?? '');
                    $sSubVinculo = $sSub->vinculo ?? '';
                    $sSubRegime = $sSub->regime ?? '';

                    $seg = $segundo_servidor_subistituido->segundoServidorSubstituido ?? $segundo_servidor_subistituido->ingressoCandidato ?? null;
                    $segCadastro = $seg->cadastro ?? $seg->matricula ?? '';
                    $segName = $seg->nome ?? $seg->name ?? '';
                    $segId = $seg->id ?? ($segundo_servidor_subistituido->segundoServidorSubstituido->id ?? '');
                    $segVinculo = $seg->vinculo ?? '';
                    $segRegime = $seg->regime ?? '';
                @endphp
                    <div class="form-row">
                        <div class=" col-md-2">
                            <div class=" position-relative form-group">
                                <div>
                                    <label for="cpf_cervidor" class="">CPF / MATRÍCULA</label>
                                    <input value="{{ $srvCpf }}" minlength="8" maxlength="11" name="" id="cpf_cervidor" type="number" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="nte_efetivo" class="">NTE</label>
                                <input value="{{ $srvNte }}" id="nte_efetivo" name="" type="text" class="form-control form-control-sm" readonly>
                                <input value="{{ $provimento_efetivo->id }}" id="provimento_id" type="number" class="form-control form-control-sm" hidden>
                                <input value="{{ $srvId }}" id="servidor_id" name="ingresso_candidato_id" type="number" class="form-control form-control-sm" hidden>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="servidor_efetivo" class="">nome do servidor</label>
                                <input value="{{ $srvName }}" id="servidor_efetivo" name="" type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="position-relative form-group">
                                <label for="regime" class="">regime</label>
                                @if ($srvCargo === "REDA SELETIVO")
                                <input value="20h" name="" required id="" type="text" class="form-control form-control-sm" readonly>
                                @else
                                <input value="40h" name="" required id="" type="text" class="form-control form-control-sm" readonly>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="servidor_efetivo" class="">FUNÇÃO</label>
                                <input value="{{ $srvCargo }}" id="servidor_efetivo" name="" type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>
                    <div id="hidden_select_unidade_escolar">
                        <hr>
                        <h5><strong>UNIDADE DE ENCAMINHAMENTO</strong></h5>
                        <br>
                        <div class="form-row">
                            <div class=" col-md-2">
                                <div class="form-group">
                                    @if (Auth::user()->profile === "cpm_tecnico")
                                    <div class="display_btn position-relative form-group">
                                        <div>
                                            <label for="cod_ue" class="">Codigo da UEE <span class="span_required">*</span></label>
                                            <input value="{{ $unidade_encaminhamento->uee->cod_unidade }}" minlength="8" maxlength="9" name="" id="cod_ue" type="number" class="form-control form-control-sm">
                                            <input value="{{ $unidade_encaminhamento->uee->id }}" id="unidade_id" name="uee_id" type="number" class="form-control form-control-sm" hidden>
                                        </div>
                                        <div class="btn_carencia_seacrh">
                                            <button id="btn-cadastro" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="addNewCarencia()" required>
                                                <i class="ti-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @else
                                    <div>
                                        <label for="cod_ue" class="">Codigo da UEE <span class="span_required">*</span></label>
                                        <input value="{{ $unidade_encaminhamento->uee->cod_unidade }}" minlength="8" maxlength="9" name="" id="cod_ue" type="number" class="form-control form-control-sm" readonly>
                                        <input value="{{ $unidade_encaminhamento->uee->id }}" id="unidade_id" name="uee_id" type="number" class="form-control form-control-sm" hidden>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="nte" class="">NTE</label>
                                    <input value="{{ $unidade_encaminhamento->uee->nte }}" id="nte" name="" type="text" class="form-control form-control-sm" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="municipio" class="">Município</label>
                                    <input value="{{ $unidade_encaminhamento->uee->municipio }}" id="municipio" name="" type="text" class="form-control form-control-sm" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="unidade_escolar" class="">Nome da Unidade Escolar</label>
                                    <input value="{{ $unidade_encaminhamento->uee->unidade_escolar }}" name="" required id="unidade_escolar" type="text" class="form-control form-control-sm" readonly required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="edit-container">
                            <div class="user-edit">
                                <i class="ti-pencil-alt"></i>
                            </div>
                            <div class="user-edit">
                                <i class="ti-time"></i>
                                <h4 class="subheader">{{ \Carbon\Carbon::parse($provimento_efetivo->updated_at)->format('d/m/Y') }}</h4>
                            </div>
                        </div>
                        <div class="form-row">
                            @if (Auth::user()->profile != "cpm_tecnico")
                            <div class=" col-md-1">
                                <div class="form-group">
                                    <label for="cadastro" class="">Matrícula</label>
                                    <input value="{{ $sSubCadastro }}" minlength="8" maxlength="11" name="" id="cadastro" type="cadastro" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            @else
                            <div class=" col-md-2">
                                <div class="display_btn position-relative form-group">
                                    <div>
                                        <label for="cadastro" class="">Matrícula</label>
                                        <input value="{{ $sSubCadastro }}" minlength="8" maxlength="11" name="" id="cadastro" type="cadastro" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="btn_carencia_seacrh">
                                        <button id="cadastro_btn" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchServidor()">
                                            <i class="ti-search"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="servidor" class="">Nome do servidor subistituido</label>
                                    <input value="{{ $sSubName }}" id="servidor" name="" type="text" class="form-control form-control-sm" readonly>
                                    <input value="{{ $sSubId }}" id="servidor_subistituido" name="servidor_substituido_id" type="number" class="form-control form-control-sm" hidden>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="vinculo" class="">Vinculo</label>
                                    <input value="{{ $sSubVinculo }}" id="vinculo" name="" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="position-relative form-group">
                                    <label for="regime" class="">Regime</label>
                                    <input value="{{ $sSubRegime }}" name="" required id="regime" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label class="control-label" for="">SITUAÇÃO - ANÁLISE CPG</label>
                                    <select name="" id="selected_situation_server_1" class="form-control form-control-sm select2">

                                        @if ($provimento_efetivo->server_1_situation == 1)
                                        <option value="1" selected>EXCEDENTE</option>
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 3)
                                        <option value="3" selected>REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 2)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2" selected>PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 4)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4" selected>EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 5)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="5" selected>REDA DESLIGAMENTO</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 6)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6" selected>APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 7)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7" selected>DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 8)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8" selected>COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 9)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9" selected>VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 10)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10" selected>VAGA DE CARGO</option>
                                        @else
                                        <option value="404" selected>Selecione...</option>
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif
                            @if (Auth::user()->profile == "cpm_tecnico")
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label class="control-label" for="">SITUAÇÃO - ANÁLISE CPG</label>
                                    <select name="" id="selected_situation_server_1" class="form-control form-control-sm select2" disabled>
                                        @if ($provimento_efetivo->server_1_situation == 1)
                                        <option value="1" selected>EXCEDENTE</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 3)
                                        <option value="3" selected>REAPROVEITADO NA UEE</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 2)
                                        <option value="2" selected>PROVIMENTO INCORRETO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 4)
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 5)
                                        <option value="5">REDA DESLIGAMENTOA</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 6)
                                        <option value="6">APOSENTADORIA</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 7)
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 8)
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 9)
                                        <option value="9">VAGA REAL</option>
                                        @elseif ($provimento_efetivo->server_1_situation == 10)
                                        <option value="10">VAGA DE CARGO</option>
                                        @else
                                        <option selected>SEM INFORMAÇÃO</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif
                            @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                            <div class="col-md-1 d-flex align-items-end">
                                <div class="form-group">
                                    <button title="Resetar Situação" id="clear_situation_server1" class=" btn  btn-danger" type="button"><i class="far fa-times-circle"></i></button>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if ($seg)
                        <div id="segundo_servidor" class="form-row">
                            @if (Auth::user()->profile != "cpm_tecnico")
                            <div class=" col-md-1">
                                <div class="form-group">
                                    <label for="cadastro_segundo_servidor" class="">Matrícula 2</label>
                                    <input value="{{ $segCadastro }}" minlength="8" maxlength="11" name="" id="cadastro_segundo_servidor" type="number" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            @else
                            <div class=" col-md-2">
                                <div class="display_btn position-relative form-group">
                                    <div>
                                        <label for="cadastro_segundo_servidor" class="">Matrícula 2º SERVIDOR</label>
                                        <input value="{{ $segCadastro }}" minlength="8" maxlength="11" name="" id="cadastro_segundo_servidor" type="number" class="form-control form-control-sm">
                                    </div>
                                    <div class="btn_carencia_seacrh">
                                        <button id="cadastro_btn" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchSegundoServidor()">
                                            <i class="ti-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="segundo_servidor_name" class="">Nome do segundo servidor subistituido</label>
                                    <input value="{{ $segName }}" id="segundo_servidor_name" name="" type="text" class="form-control form-control-sm" readonly>
                                    <input value="{{ $segId }}" id="id_segundo_servidor_subistituido" name="segundo_servidor_subistituido" type="number" class="form-control form-control-sm" hidden readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="vinculo_segundo_servidor" class="">Vinculo</label>
                                    <input value="{{ $segVinculo }}" id="vinculo_segundo_servidor" name="" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="position-relative form-group">
                                    <label for="regime_segundo_servidor" class="">Regime</label>
                                    <input value="{{ $segRegime }}" name="" required id="regime_segundo_servidor" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label class="control-label" for="">SITUAÇÃO - ANÁLISE CPG</label>
                                    <select name="" id="selected_situation_server_2" class="form-control form-control-sm select2">
                                        @if ($provimento_efetivo->server_2_situation == 1)
                                        <option value="1" selected>EXCEDENTE</option>
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 3)
                                        <option value="3" selected>REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 2)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2" selected>PROVIMENTO INCORRETO</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 4)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4" selected>EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 5)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5" selected>REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 6)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6" selected>APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 7)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7" selected>DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 8)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8" selected>COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 9)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9" selected>VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 10)
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10" selected>VAGA DE CARGO</option>
                                        @else
                                        <option value="404" selected>Selecione...</option>
                                        <option value="3">REAPROVEITADO NA UEE</option>
                                        <option value="1">EXCEDENTE</option>
                                        <option value="2">PROVIMENTO INCORRETO</option>
                                        <option value="5">REDA DESLIGAMENTO</option>
                                        <option value="4">EFETIVO EM LICENÇA</option>
                                        <option value="6">APOSENTADORIA</option>
                                        <option value="7">DEIXAR HORAS EXTRAS</option>
                                        <option value="8">COORD. PEDAGÓGICO</option>
                                        <option value="9">VAGA REAL</option>
                                        <option value="10">VAGA DE CARGO</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif
                            @if (Auth::user()->profile === "cpm_tecnico")
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label class="control-label" for="">SITUAÇÃO - ANÁLISE CPG</label>
                                    <select name="" id="selected_situation_server_2" class="form-control form-control-sm select2" disabled>
                                        @if ($provimento_efetivo->server_2_situation == 1)
                                        <option value="1" selected>EXCEDENTE</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 2)
                                        <option value="2" selected>PROVIMENTO INCORRETO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 3)
                                        <option value="3" selected>REAPROVEITADO NA UEE</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 4)
                                        <option value="4" selected>EFETIVO EM LICENÇA</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 5)
                                        <option value="5" selected>REDA DESLIGAMENTO</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 6)
                                        <option value="6" selected>APOSENTADORIA</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 7)
                                        <option value="7" selected>DEIXAR HORAS EXTRAS</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 8)
                                        <option value="8" selected>DEIXAR HORAS EXTRAS</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 9)
                                        <option value="9" selected>VAGA REAL</option>
                                        @elseif ($provimento_efetivo->server_2_situation == 10)
                                        <option value="10" selected>VAGA DE CARGO</option>
                                        @else
                                        <option selected>SEM INFORMAÇÃO</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif
                            @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                            <div class="col-md-1 d-flex align-items-end">
                                <div class="form-group">
                                    <button title="Resetar Situação" id="clear_situation_server2" class="btn btn-danger" type="button"><i class="far fa-times-circle"></i></button>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        <div class="form-row">
                            <div id="data_encaminhamento_row" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_encaminhamento" class="">Data de encaminhamento</label>
                                    <input value="{{ $provimento_efetivo->data_encaminhamento }}" name="data_encaminhamento" id="data_encaminhamento" type="date" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div id="data_assuncao_row" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_assuncao" class="">Assunção</label>
                                    <input value="{{ $provimento_efetivo->data_assuncao }}" name="data_assuncao" id="data_assuncao" type="date" class="form-control form-control-sm">
                                </div>
                            </div>

                            @if (((Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador")) && ($provimento_efetivo->server_1_situation == 2))
                            <div class="col-8">
                                <div class="d-flex justify-content-end">
                                    <ul class="ks-cboxtags">
                                        @if ($provimento_efetivo->inconsistencia === "OK")
                                        <li><input name="" type="checkbox" id="checkbox50" checked><label for="checkbox50">INCONSISTÊNCIA AJUSTADA</label></li>
                                        @else
                                        <li><input name="" type="checkbox" id="checkbox50"><label for="checkbox50">INCONSISTÊNCIA AJUSTADA</label></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            @endif
                            @if (Auth::user()->profile === "cpm_tecnico")
                            <div id="data_assuncao_row" class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label for="obs">Observações CPG <i class="ti-pencil"></i></label>
                                    <textarea name="obs" class="form-control" id="obs" rows="4" readonly>{{ $provimento_efetivo->obs }}</textarea>
                                </div>
                            </div>
                            @else
                            <div id="data_assuncao_row" class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label for="obs">Observações CPG <i class="ti-pencil"></i></label>
                                    <textarea name="obs" class="form-control" id="obs" rows="4">{{ $provimento_efetivo->obs }}</textarea>
                                </div>
                            </div>
                            @endif
                            @if (Auth::user()->profile === "cpg_tecnico")
                            <div id="data_assuncao_row" class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label for="obs_cpm">Observações CPM<i class="ti-pencil"></i></label>
                                    <textarea name="obs_cpm" class="form-control" id="obs_cpm" rows="4" readonly>{{ $provimento_efetivo->obs_cpm }}</textarea>
                                </div>
                            </div>
                            @else
                            <div id="data_assuncao_row" class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label for="obs_cpm">Observações CPM <i class="ti-pencil"></i></label>
                                    <textarea name="obs_cpm" class="form-control" id="obs_cpm" rows="4">{{ $provimento_efetivo->obs_cpm }}</textarea>
                                </div>
                            </div>
                            @endif
                        </div>
                            <div class="form-row">
                            <div class="col-12 d-flex justify-content-end mb-2">
                                <button type="button" class="btn btn-primary subheader" onclick="adicionarDisciplinaReda()"><i class="ti-plus"></i> Adicionar Disciplina</button>
                            </div>
                            <div class="form-row">
                                <div id="disciplinas-container">
                                    @php
                                        // Quando a action passou um grupo, usamos todos os registros do grupo.
                                        $group = isset($provimentos_group) ? $provimentos_group : collect([$provimento_efetivo]);
                                    @endphp

                                    @if ($group && $group->count())
                                        @foreach ($group as $index => $row)
                                            <div class="form-row disciplina-row mb-2">
                                                <div class="col-md-6">
                                                    <div class="form-group_disciplina">
                                                        <label class="control-label">Disciplina</label>
                                                        <input value="{{ $row->disciplina }}" name="disciplinas[]" type="text" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group_disciplina">
                                                        <label for="mat">MAT</label>
                                                        <input type="text" name="matutino[]" value="{{ $row->matutino ?? '' }}" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group_disciplina">
                                                        <label for="vesp">VESP</label>
                                                        <input type="text" name="vespertino[]" value="{{ $row->vespertino ?? '' }}" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group_disciplina">
                                                        <label for="not">NOT</label>
                                                        <input type="text" name="noturno[]" value="{{ $row->noturno ?? '' }}" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center">
                                                    <button type="button" class="btn btn-danger btn-sm ml-2" onclick="this.closest('.disciplina-row').remove()">Remover</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="form-row disciplina-row mb-2">
                                            <div class="col-md-6">
                                                <div class="form-group_disciplina">
                                                    <label class="control-label">Disciplina</label>
                                                    <input value="" name="disciplinas[]" type="text" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group_disciplina">
                                                    <label for="mat">MAT</label>
                                                    <input type="text" name="matutino[]" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group_disciplina">
                                                    <label for="vesp">VESP</label>
                                                    <input type="text" name="vespertino[]" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group_disciplina">
                                                    <label for="not">NOT</label>
                                                    <input type="text" name="noturno[]" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center">
                                                <button type="button" class="btn btn-danger btn-sm ml-2" onclick="this.closest('.disciplina-row').remove()">Remover</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <hr>
                @if (($provimento_efetivo->pch == "PENDENTE") || ($provimento_efetivo->pch == "INCONSISTENCIA") || (Auth::user()->profile === "cpg_tecnico"))
                <div id="buttons" class="buttons d-flex" style="position: relative;">
                    <button id="btn_submit" type="submit" class="btn btn-primary mr-2">Atualizar</button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
<script>
    // Aguarde até que o documento esteja totalmente carregado
    document.addEventListener("DOMContentLoaded", function() {
        var selectedButtonServer1 = document.getElementById("clear_situation_server1");

        clear_situation_server1.addEventListener("click", function() {
            var selectElement1 = $("#selected_situation_server_1");
            selectElement1.val(404).trigger("change");
            var provimento_id = document.getElementById('provimento_id');
            var id = provimento_id.value;

            $.post("/update/situation_server1/" + 404 + "/" + id, function(response) {

            });
        });

    });
</script>
<script>
    // Aguarde até que o documento esteja totalmente carregado
    document.addEventListener("DOMContentLoaded", function() {
        var selectedButtonServer2 = document.getElementById("clear_situation_server2");

        clear_situation_server2.addEventListener("click", function() {
            var selectElement2 = $("#selected_situation_server_2");
            selectElement2.val(404).trigger("change");
            var provimento_id = document.getElementById('provimento_id');
            var id = provimento_id.value;

            $.post("/update/situation_server2/" + 404 + "/" + id, function(response) {

            });
        });

    });
</script>
<script>
    // Aguarde até que o documento esteja totalmente carregado
    document.addEventListener("DOMContentLoaded", function() {
        var selectElement = document.getElementById("selected_situation_server_2");

        // Inicialize o Select2 no elemento
        $(selectElement).select2();

        // Adicione um ouvinte de evento para quando a seleção for alterada
        $(selectElement).on("change", function() {
            var selectedValue = $(this).val();
            var provimento_id = document.getElementById('provimento_id');
            var id = provimento_id.value;

            $.post("/update/situation_server2/" + selectedValue + "/" + id, function(response) {

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Situação atualizada',
                    showConfirmButton: false,
                    timer: 2500
                });
            });
        });
    });
</script>
<script>
    // Aguarde até que o documento esteja totalmente carregado
    document.addEventListener("DOMContentLoaded", function() {
        var selectElement1 = document.getElementById("selected_situation_server_1");

        // Inicialize o Select2 no elemento
        $(selectElement1).select2();

        // Adicione um ouvinte de evento para quando a seleção for alterada
        $(selectElement1).on("change", function() {
            var selectedValue = $(this).val();
            var provimento_id = document.getElementById('provimento_id');
            var id = provimento_id.value;

            $.post("/update/situation_server1/" + selectedValue + "/" + id, function(response) {

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Situação atualizada',
                    showConfirmButton: false,
                    timer: 2500
                });
            });
        });

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
                    text: 'Não é possível excluir esse motivo porque existem carências associadas.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Registros atualizados com sucesso!',
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
    const checkbox50 = document.getElementById("checkbox50")

    checkbox50.addEventListener('click', function() {

        let provimento_id = document.getElementById('provimento_id')

        $.post("/update/inconsistencia/" + provimento_id.value, function(response) {

            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Inconsistência atualizada com sucesso!',
                showConfirmButton: false,
                timer: 2500
            })
        });
    })
</script>

<script>
    cadastro_segundo_servidor_btn.addEventListener('click', () => {
        if (servidor_subistituido.value == '') {

            Swal.fire({
                icon: "warning",
                title: "Atenção!",
                text: "Para liberar o segundo servidor é preciso ter o primeiro servidor selacionado",
            });

        } else {
            if (cadastro_segundo_servidor_btn.classList.contains('btn-primary')) {
                cadastro_segundo_servidor_btn.classList.remove('btn-primary');
                cadastro_segundo_servidor_btn.classList.add('btn-danger');
                icon_segundo_servidor.classList.remove('fa-solid', 'fa-user-plus');
                icon_segundo_servidor.classList.add('fa-solid', 'fa-user-xmark');
                segundo_servidor.hidden = false;

            } else {
                const segundo_servidor_name = document.getElementById(
                    "segundo_servidor_name"
                );
                const vinculo_segundo_servidor = document.getElementById(
                    "vinculo_segundo_servidor"
                );
                const regime_segundo_servidor = document.getElementById(
                    "regime_segundo_servidor"
                );
                const id_segundo_servidor_subistituido = document.getElementById(
                    "id_segundo_servidor_subistituido"
                );
                const cadastro_segundo_servidor = document.getElementById(
                    "cadastro_segundo_servidor"
                );
                cadastro_segundo_servidor_btn.classList.remove('btn-danger');
                cadastro_segundo_servidor_btn.classList.add('btn-primary');
                icon_segundo_servidor.classList.add('fa-solid', 'fa-user-plus');
                icon_segundo_servidor.classList.remove('fa-solid', 'fa-user-xmark');
                segundo_servidor.hidden = true
                segundo_servidor_name.value = ''
                vinculo_segundo_servidor.value = ''
                regime_segundo_servidor.value = ''
                id_segundo_servidor_subistituido.value = ''
                cadastro_segundo_servidor.value = ''
            }
        }
    })
</script>

<script>
    function searchSegundoServidorTeste() {

        const cadastro_segundo_servidor = document.getElementById(
            "cadastro_segundo_servidor"
        );

        let segundo_servidor = cadastro_segundo_servidor.value;

        if (segundo_servidor == "") {
            Swal.fire({
                icon: "error",
                title: "Atenção!",
                text: "Matrícula não informada. Tente novamente.",
            });
        }

        $.post("/consultarServidor/" + segundo_servidor, function(response) {
            let data = response[0];

            if (data) {
                const segundo_servidor_name = document.getElementById(
                    "segundo_servidor_name"
                );
                const vinculo_segundo_servidor = document.getElementById(
                    "vinculo_segundo_servidor"
                );
                const regime_segundo_servidor = document.getElementById(
                    "regime_segundo_servidor"
                );
                const id_segundo_servidor_subistituido = document.getElementById(
                    "id_segundo_servidor_subistituido"
                );
                id_segundo_servidor_subistituido.value = data.id;
                segundo_servidor_name.value = data.nome;
                vinculo_segundo_servidor.value = data.vinculo;
                regime_segundo_servidor.value = data.regime;

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

@endsection