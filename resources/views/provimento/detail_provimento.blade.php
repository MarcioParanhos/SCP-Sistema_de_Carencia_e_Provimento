@extends('layout.main')

@section('title', 'SCP - Carência')

@section('content')

<?php

use Carbon\Carbon;

$data_atual = Carbon::now();
$ano_atual = $data_atual->year;
?>

<style>
    .print-visible {
        display: none;
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

    .card_title {
        font-size: 25px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        box-shadow: none !important;
    }

    .card_title .title_show_carencias {
        font-size: 25px !important;
        color: #323232 !important;
        font-weight: bold !important;
    }

    .col-md-1 {
        width: 14% !important;
    }

    .col-md-6 {
        width: 45% !important;
    }

    .col-md-7 {
        width: 40% !important;
    }

    .col-md-2 {
        width: 23% !important;
    }

    .col-md-4 {
        width: 25% !important;
        /* Ajuste para colunas de 4 */
    }

    .col-md-3 {
        width: 25% !important;
        /* Ajuste para colunas de 3 */
    }

    /* Estilos adicionais, caso necessário */
    .form-group {
        margin-bottom: 1rem;
        /* Manter espaçamento entre campos */
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

    .print-hidden {
        display: none !important;
    }

    .print-visible {
        display: block;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header img {
        width: 200px !important;
        height: 120px;
    }

    .logo-educacao {
        height: 80px !important;
    }

    .title_show_carencia {
        font-weight: 800;
        color: black !important;
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

    @page {
        size: landscape;
    }
</style>

@if(session('msg'))
<div class="col-12">
    <div class="alert text-center text-white bg-success container alert-success alert-dismissible fade show" role="alert" style="min-width: 100%">
        <strong>{{ session('msg')}}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif
<div class="header print-visible">
    <img class="img-logo" src="/images/SCP.png" alt="people">
</div>
<div class="card">
    <div class="shadow bg-primary text-white card_title ">
        @if ($provimento->pch === "PENDENTE")
        @if ($provimento->situacao === "BLOQUEADO")
        <h4 class="testandoO badge badge-danger print-none"><strong>BLOQUEADO PARA EDIÇÃO</strong></h4>
        @endif
        @if ($provimento->situacao === "DESBLOQUEADO")
        <h4 class="badge badge-success print-none"><strong>DESBLOQUEADO</strong></h4>
        @endif
        @endif
        @if ($provimento->pch === "OK")
        <h4 class="testandoO badge badge-danger print-none"><strong>PROVIMENTO JÁ VALIDADO PELA CPG - EDIÇÃO BLOQUEADA</strong></h4>
        @endif
        <h4 class="title_show_carencias">suprimento detalhado</h4>
        <!-- <a class="mr-2" title="Voltar" href="/buscar/provimento/filter_provimentos">
            <button>
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                    <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
                </svg>
                <span>VOLTAR</span>
            </button>
        </a> -->
        <div class="print-none  d-flex justify-content-center align-items-center print-none">
            <a data-toggle="tooltip" data-placement="top" title="Voltar" class="m-1 btn bg-white text-primary" href="/buscar/provimento/filter_provimentos">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back-up">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 14l-4 -4l4 -4" />
                    <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                </svg>
            </a>
            <a class="m-1 btn bg-white text-primary " data-toggle="tooltip" data-placement="top" onclick="javascript:window.print();">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                    <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
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
                <i class="ti-pencil-alt"></i>
                <h4>{{ $provimento->usuario }}</h4>
            </div>
            <div class="user-edit">
                <i class="ti-time"></i>
                <h4>{{ \Carbon\Carbon::parse($provimento->created_at)->format('d/m/Y') }}</h4>
            </div>
        </div>
    </div>
    <div class="shadow card_info">
        <form action="/provimento/update/{{ $provimento->id }}" method="post">
            @csrf
            @method ('PUT')
            <input value="{{Auth::user()->name}}" id="" name="user_cpg_update" type="text" class="form-control form-control-sm" hidden>
            <input value="{{Auth::user()->profile}}" id="" name="profile_cpg_update" type="text" class="form-control form-control-sm" hidden>
            <div class="form-row">
                <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="nte_seacrh">NTE</label>
                        @if ($provimento->nte < 10) <input value="{{ $provimento->nte }}" name="nte" id="nte" type="text" class="text-center form-control form-control-sm" readonly>
                            @endif
                            @if ($provimento->nte >= 10)
                            <input value="{{ $provimento->nte }}" name="nte" id="nte" type="text" class="text-center form-control form-control-sm" readonly>
                            @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="municipio_search">MUNICIPIO</label>
                        <input value="{{ $provimento->municipio }}" name="municipio" id="municipio" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_uee">NOME DA UNIDADE ESCOLAR</label>
                        <input value="{{ $provimento->unidade_escolar }}" name="unidade_escolar" id="unidade_escolar" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">COD. UE</label>
                        <input value="{{ $provimento->cod_unidade }}" name="cod_unidade" id="cod_unidade" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
            </div>
            <div class="form-row">
                @if ($provimento->pch === "OK")
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="nte_seacrh">MATRÍCULA / CPF</label>
                        <input value="{{ $provimento->cadastro }}" name="cadastro" id="cadastro" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="municipio_search">Servidor</label>
                        <input value="{{ $provimento->servidor }}" name="servidor" id="servidor" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                @endif
                @if ($provimento->pch === "PENDENTE")
                @if (($provimento->situacao === "BLOQUEADO") || (Auth::user()->profile === "cpm_tecnico"))
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="nte_seacrh">MATRÍCULA / CPF</label>
                        <input value="{{ $provimento->cadastro }}" name="cadastro" id="cadastro" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="municipio_search">Servidor</label>
                        <input value="{{ $provimento->servidor }}" name="servidor" id="servidor" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                @endif
                @if ((Auth::user()->profile === "cpm_coordenador") || (Auth::user()->profile === "administrador"))

                <div class=" col-md-2">
                    <div class="display_btn position-relative form-group">
                        <div>
                            <label for="cadastro" class="">Matrícula / CPF</label>
                            <input value="{{ $provimento->cadastro }}" minlength="8" maxlength="11" name="cadastro" id="cadastro" type="cadastro" class="form-control form-control-sm">
                        </div>
                        <div class="btn_carencia_seacrh print-none">
                            <button id="cadastro_btn" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchServidor()">
                                <i class="ti-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="municipio_search">Servidor</label>
                        <input value="{{ $provimento->servidor }}" name="servidor" id="servidor" type="text" class="form-control form-control-sm">
                    </div>
                </div>
                @endif
                @endif
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="control-label" for="search_uee">VÍNCULO</label>
                        <input value="{{ $provimento->vinculo }}" name="vinculo" id="vinculo" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codigo_unidade_escolar" class="control-label">regime</label>
                        <input value="{{ $provimento->regime }}" name="regime" id="regime" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label" for="eixo">FORMA DO SUPRIMENTO</label>
                        <input value="{{ $provimento->forma_suprimento }}" name="tipo_movimentacao" id="tipo_movimentacao" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label" for="eixo">TIPO DE MOVIMENTAÇÃO</label>
                        <input value="{{ $provimento->tipo_movimentacao }}" name="tipo_movimentacao" id="tipo_movimentacao" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-3">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="nte_seacrh">Disciplina</label>
                        <input value="{{ $provimento->disciplina }}" name="disciplina" id="disciplina" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="municipio_search">mat</label>
                        <input value="{{ $provimento->provimento_matutino }}" name="provimento_matutino" id="provimento_matutino" type="text" class="text-center form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_uee">vesp</label>
                        <input value="{{ $provimento->provimento_vespertino }}" name="provimento_vespertino" id="provimento_vespertino" type="text" class="text-center form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">not</label>
                        <input value="{{ $provimento->provimento_noturno }}" name="provimento_noturno" id="provimento_noturno" type="text" class="text-center form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">total</label>
                        <input value="{{ $provimento->total }}" name="total" id="total" type="text" class="text-center form-control form-control-sm" readonly>
                    </div>
                </div>
                @if ($provimento->tipo_carencia_provida === "Temp")
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">TIPO DE VAGA PROVIDA</label>
                        <input value="TEMPORÁRIA" name="" id="" type="text" class="text-center form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">DATA FIM DA VAGA</label>
                        <input value="{{ $provimento->data_fim_by_temp }}" name="" id="" type="date" class="text-center form-control form-control-sm" readonly>
                    </div>
                </div>
                @endif
                @if ($provimento->tipo_carencia_provida === "Real")
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">TIPO DE VAGA PROVIDA</label>
                        <input value="REAL" name="" id="" type="text" class="text-center form-control form-control-sm" readonly>
                    </div>
                </div>
                @endif
            </div>
            <div class="form-row mb-2">
                @if ((Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "cpm_coordenador") || (Auth::user()->profile === "administrador"))
                @if (($provimento->situacao === "DESBLOQUEADO") && ($provimento->pch === "PENDENTE"))
                <div class="col-md-2" id="">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="situacao_provimento">situação do provimento</label>
                        <select name="situacao_provimento" id="situacao_provimento_detail" class="form-control select2" required>
                            @if ($provimento->situacao_provimento === "tramite")
                            <option value="{{ $provimento->situacao_provimento }}">EM TRÂMITE</option>
                            <option value="provida">PROVIDA</option>
                            @endif
                            @if ($provimento->situacao_provimento === "provida")
                            <option value="{{ $provimento->situacao_provimento }}">PROVIDO</option>
                            <option value="tramite">EM TRÂMITE</option>
                            @endif
                        </select>
                    </div>
                </div>
                @endif
                @if (($provimento->situacao === "BLOQUEADO") || ($provimento->pch === "OK"))
                <div class="col-md-2" id="">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="situacao_provimento">situação do provimento</label>
                        <select name="situacao_provimento" id="situacao_provimento_detail" class="form-control select2" disabled>
                            @if ($provimento->situacao_provimento === "tramite")
                            <option value="{{ $provimento->situacao_provimento }}">EM TRÂMITE</option>
                            @endif
                            @if ($provimento->situacao_provimento === "provida")
                            <option value="{{ $provimento->situacao_provimento }}">PROVIDO</option>
                            @endif
                        </select>
                    </div>
                </div>
                @endif
                @if (($provimento->situacao_provimento === "provida") && (($provimento->situacao === "BLOQUEADO") || ($provimento->pch === "OK")))
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="data_encaminhamento" class="">DATA DO ENCAMINHAMENTO</label>
                        <input value="{{ $provimento->data_encaminhamento }}" name="data_encaminhamento" id="data_encaminhamento" type="date" class="text-center form-control form-control-sm" readonly>
                    </div>
                </div>
                @endif
                @if (($provimento->situacao_provimento === "provida") && (($provimento->situacao === "DESBLOQUEADO") && ($provimento->pch === "PENDENTE")))
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="data_encaminhamento" class="">DATA DO ENCAMINHAMENTO</label>
                        <input value="{{ $provimento->data_encaminhamento }}" name="data_encaminhamento" id="data_encaminhamento" type="date" class="text-center form-control form-control-sm">
                    </div>
                </div>
                @endif
                @if (($provimento->situacao_provimento === "tramite") && ($provimento->situacao === "BLOQUEADO"))
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="data_encaminhamento" class="">DATA DO ENCAMINHAMENTO</label>
                        <input value="{{ $provimento->data_encaminhamento }}" name="data_encaminhamento" id="data_encaminhamento" type="date" class="text-center form-control form-control-sm" readonly>
                    </div>
                </div>
                @endif
                @if (($provimento->situacao_provimento === "tramite") && ($provimento->situacao === "DESBLOQUEADO"))
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="data_encaminhamento" class="">DATA DO ENCAMINHAMENTO</label>
                        <input value="{{ $provimento->data_encaminhamento }}" name="data_encaminhamento" id="data_encaminhamento" type="date" class="text-center form-control form-control-sm">
                    </div>
                </div>
                @endif
                @if (($provimento->situacao_provimento === "tramite") && ($provimento->situacao === "BLOQUEADO"))
                <div id="data_assuncao_row_detail" class="col-md-2" hidden>
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">ASSUNÇÃO</label>
                        <input value="{{ $provimento->data_assuncao }}" name="data_assuncao" id="data_assuncao" type="date" class="form-control form-control-sm">
                    </div>
                </div>
                @endif
                @if (($provimento->situacao_provimento === "tramite") && ($provimento->situacao === "DESBLOQUEADO"))
                <div id="data_assuncao_row_detail" class="col-md-2" hidden>
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">ASSUNÇÃO</label>
                        <input value="{{ $provimento->data_assuncao }}" name="data_assuncao" id="data_assuncao" type="date" class="form-control form-control-sm">
                    </div>
                </div>
                @endif
                @if (($provimento->situacao_provimento === "provida") && (($provimento->situacao === "BLOQUEADO") || ($provimento->pch === "OK")))
                <div id="data_assuncao_row_detail" class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">ASSUNÇÃO</label>
                        <input value="{{ $provimento->data_assuncao }}" name="data_assuncao" id="data_assuncao" type="date" class="text-center form-control form-control-sm" readonly>
                    </div>
                </div>
                @endif
                @if (($provimento->situacao_provimento === "provida") && (($provimento->situacao === "DESBLOQUEADO") && ($provimento->pch === "PENDENTE")))
                <div id="data_assuncao_row_detail" class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">ASSUNÇÃO</label>
                        <input value="{{ $provimento->data_assuncao }}" name="data_assuncao" id="data_assuncao" type="date" class="text-center form-control form-control-sm">
                    </div>
                </div>
                @endif
                @endif
                @if (Auth::user()->profile === "cpg_tecnico")
                <div class="col-md-2" id="">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="situacao_provimento">situação do provimento</label>
                        @if ($provimento->situacao_provimento === "tramite")
                        <input value="EM TRÂMITE" name="regime" id="regime" type="text" class="form-control form-control-sm" readonly>
                        @endif
                        @if ($provimento->situacao_provimento === "provida")
                        <input value="PROVIDO" name="regime" id="regime" type="text" class="form-control form-control-sm" readonly>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="data_encaminhamento" class="">DATA DE ENCAMINHAMENTO</label>
                        <input value="{{ $provimento->data_encaminhamento }}" name="data_encaminhamento" id="data_encaminhamento" type="date" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="data_assuncao" class="">ASSUNÇÃO</label>
                        <input value="{{ $provimento->data_assuncao }}" name="data_assuncao" id="data_assuncao" type="date" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                @endif
                @if ((Auth::user()->profile === "cpg_tecnico")|| (Auth::user()->profile === "administrador"))
                @if (($provimento->situacao_provimento === "provida") || ($provimento->situacao_provimento === "tramite"))
                @if ($provimento->pch === "OK")
                <div class="d-flex justify-content-end container-fluid print-none">
                    <input id="check_provimento_id" value="{{ $provimento->id }}" type="text" hidden>
                    <label class="toggle">
                        <input id="check-pch" class="toggle-checkbox" type="checkbox" checked>
                        <div class="toggle-switch"></div>
                        <span class="toggle-label">PROGRAMADO - PCH</span>
                    </label>
                </div>
                @endif
                @if ($provimento->pch === "PENDENTE")
                <div class="d-flex justify-content-end container-fluid print-none">
                    <input id="check_provimento_id" value="{{ $provimento->id }}" type="text" hidden>
                    <label class="toggle">
                        <input id="check-pch" class="toggle-checkbox" type="checkbox">
                        <div class="toggle-switch"></div>
                        <span class="toggle-label">PROGRAMADO - PCH</span>
                    </label>
                </div>
                @endif
                @endif
                @endif
                @if ((Auth::user()->profile === "cpm_coordenador") || (Auth::user()->profile === "administrador"))
                <div class="col-md-2 print-none" id="">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="situacao_provimento">situação</label>
                        <select name="situacao" id="situacao" class="form-control select2" required>
                            @if($provimento->situacao === "DESBLOQUEADO")
                            <option value="{{ $provimento->situacao }}">{{ $provimento->situacao }}</option>
                            <option value="BLOQUEADO">BLOQUEADO</option>
                            @endif
                            @if($provimento->situacao === "BLOQUEADO")
                            <option value="{{ $provimento->situacao }}">{{ $provimento->situacao }}</option>
                            <option value="DESBLOQUEADO">DESBLOQUEADO</option>
                            @endif
                        </select>
                    </div>
                </div>
                @endif
            </div>
            @if ((Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador"))
            <div class="form-row">
                <div id="data_assuncao_row" class="col-md-12">
                    <div class="form-group_disciplina">
                        <label for="obs">Observações CPM <i class="ti-pencil"></i></label>
                        <textarea name="obs" class="form-control" id="obs" rows="4">{{ $provimento->obs }}</textarea>
                    </div>
                </div>
            </div>
            @endif
            @if (Auth::user()->profile === "cpg_tecnico")
            <div class="form-row">
                <div id="data_assuncao_row" class="col-md-12">
                    <div class="form-group_disciplina">
                        <label for="obs">Observações CPM <i class="ti-pencil"></i></label>
                        <textarea name="obs" class="form-control" id="obs" rows="4" readonly>{{ $provimento->obs }}</textarea>
                    </div>
                </div>
            </div>
            @endif
            @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
            <div class="form-row">
                <div id="data_assuncao_row" class="col-md-12">
                    <div class="form-group_disciplina">
                        <label for="obs">Observações CPG <i class="ti-pencil"></i></label>
                        <textarea name="obs_cpg" class="form-control" id="obs_cpg" rows="4">{{ $provimento->obs_cpg }}</textarea>
                    </div>
                </div>
            </div>
            @endif
            @if (Auth::user()->profile === "cpm_tecnico")
            <div class="form-row">
                <div id="data_assuncao_row" class="col-md-12">
                    <div class="form-group_disciplina">
                        <label for="obs">Observações CPG <i class="ti-pencil"></i></label>
                        <textarea name="obs_cpg" class="form-control" id="obs_cpg" rows="4" readonly>{{ $provimento->obs_cpg }}</textarea>
                    </div>
                </div>
            </div>
            @endif
            <div class="buttons d-flex justify-content-between align-middle print-none">
                @if (Auth::user()->profile != "consulta")
                <div id="buttons" class="buttons">
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
                <a href="/detalhar_carencia/{{ $provimento->id_carencia }}"><button id="" type="button" class="btn  btn-primary"><i class="ti-layers-alt"></i> ORIGEM DA VAGA</button></a>
            </div>

        </form>
    </div>
</div>

<Script>
    const provimento_matutino = document.getElementById("provimento_matutino")
    const provimento_vespertino = document.getElementById("provimento_vespertino")
    const provimento_noturno = document.getElementById("provimento_noturno")
    const total = document.getElementById("total")

    provimento_matutino.addEventListener("blur", addTotal1)
    provimento_vespertino.addEventListener("blur", addTotal1)
    provimento_noturno.addEventListener("blur", addTotal1)

    // ADICIONA O TOTAL DE FORMA ASSINCRONA
    function addTotal1() {

        let matModify = parseFloat(provimento_matutino.value)
        let vespModify = parseFloat(provimento_vespertino.value)
        let notpModify = parseFloat(provimento_noturno.value)

        total.value = matModify + vespModify + notpModify
    }

    function searchServidor() {

        let cadastro_servidor = cadastro.value;

        if (cadastro_servidor == "") {
            Swal.fire({
                icon: 'error',
                title: 'Atenção!',
                text: 'Matrícula não informada. Tente novamente.',
            })
        }

        $.post('/consultarServidor/' + cadastro_servidor, function(response) {

            let data = response[0]

            if (data) {

                const cadastro = document.getElementById("cadastro")
                servidor.value = data.nome
                vinculo.value = data.vinculo
                regime.value = data.regime
                cadastro.value = data.cadastro

            } else {

                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Servidor não encontrado. Tente novamente.',
                })
            }
        })
    }
</Script>
@endsection