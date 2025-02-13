@extends('layout.main')

@section('title', 'SCP - Encaminhamento')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif
<style>
    .mult-select-tag .body {
        display: flex;
        border: 1px solid #AAAAAA !important;
        background: #fff !important;
        min-height: 2.15rem;
        width: 100%;
        min-width: 14rem;
        border-radius: 5px !important;
    }

    .mult-select-tag .item-container {
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fbf8f3 !important;
        padding: .2rem .4rem;
        margin: .2rem;
        font-weight: 500;
        border: 1px solid #fbf8f3 !important;
        background: #36425a !important;
        border-radius: 5px !important;
    }

    .mult-select-tag .item-label {
        max-width: 100%;
        line-height: 1;
        font-size: .75rem;
        font-weight: 400;
        flex: 0 1 auto;
        color: #fbf8f3 !important;
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

    .col-1,
    .col-2,
    .col-3,
    .col-4,
    .col-5,
    .col-6,
    .lightGallery .image-tile,
    .col-7,
    .col-8,
    .col-9,
    .col-10,
    .col-11,
    .col-12,
    .col,
    .col-auto,
    .col-sm-1,
    .col-sm-2,
    .col-sm-3,
    .col-sm-4,
    .col-sm-5,
    .col-sm-6,
    .col-sm-7,
    .col-sm-8,
    .col-sm-9,
    .col-sm-10,
    .col-sm-11,
    .col-sm-12,
    .col-sm,
    .col-sm-auto,
    .col-md-1,
    .col-md-2,
    .col-md-3,
    .col-md-4,
    .col-md-5,
    .col-md-6,
    .col-md-7,
    .col-md-8,
    .col-md-9,
    .col-md-10,
    .col-md-11,
    .col-md-12,
    .col-md,
    .col-md-auto,
    .col-lg-1,
    .col-lg-2,
    .col-lg-3,
    .col-lg-4,
    .col-lg-5,
    .col-lg-6,
    .col-lg-7,
    .col-lg-8,
    .col-lg-9,
    .col-lg-10,
    .col-lg-11,
    .col-lg-12,
    .col-lg,
    .col-lg-auto,
    .col-xl-1,
    .col-xl-2,
    .col-xl-3,
    .col-xl-4,
    .col-xl-5,
    .col-xl-6,
    .col-xl-7,
    .col-xl-8,
    .col-xl-9,
    .col-xl-10,
    .col-xl-11,
    .col-xl-12,
    .col-xl,
    .col-xl-auto {
        padding-right: 2px !important;
        padding-left: 2px !important;
    }
</style>
<div class="bg-primary card text-white card_title">
    <h4 class=" title_show_carencias">Encaminhamento de servidores - CONCURSADOS 2025</h4>
</div>
<div id="aposentadoria_info" class="d-flex justify-content-between mb-4">
    
    <div id="aposentadoria_info_content" class="d-flex justify-content-between" style="width: 50%;">
        <div class="col-md-6">
            <table class="table-bordered">
                <tr>
                    <td colspan="2" class="pl-2 text-center bg-primary text-white subheader"><b>TOTAL GERAL - ENCAMINHAMENTOS</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>Servidores encaminhados</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $quantidadeRegistros }}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>COM DATA DE ASSUNÇÃO</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $quantidadeRegistrosComAssuncao }}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>SEM ASSUÇÃO DENTRO DO PRAZO</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $quantidadeRegistrosDataNula }}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>SEM ASSUÇÃO COM PRAZO VENCIDO</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $quantidadeRegistrosAtrasados }}</b></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table-bordered">
                <tr>
                    <td colspan="2" class="pl-2 text-center bg-primary text-white subheader"><b>PENDENCIAS E AÇÕES</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>Encaminhamentos com inconsistência</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ ($quantidadeRegistrosError - $quantidadeRegistrosErrorOK)}}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>Inconsistências ajustadas (CPM)</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $quantidadeRegistrosErrorOK}}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>ENCAMINHAMENTOS ANÁLISADOS- CPG</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ $quantidadeRegistrosPCH }}</b></td>
                </tr>
                <tr>
                    <td class="pl-2 subheader"><b>PENDENTES ANÁLISE (CPG)</b></td>
                    <td style="width: 20%;" class="text-center"><b>{{ ($quantidadeRegistros - $quantidadeRegistrosPCH) - ($quantidadeRegistrosError - $quantidadeRegistrosErrorOK) - $quantidadeRegistrosDataNula - $quantidadeRegistrosAtrasados }}</b></td>
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


<form id="active_form" class="border shadow bg-light rounded pt-3 pl-3 pr-3" action="{{ route('provimento_efetivo.showByForm') }}" method="post" hidden>
    @csrf
    <div class="form-row">
        <div class="col-md-1">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_nte_provimento_efetivos">NTE</label>
                <select name="search_nte_provimento_efetivos" id="nte_seacrh" class="form-control form-control-sm select2">
                    <option></option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                    <option>9</option>
                    <option>10</option>
                    <option>11</option>
                    <option>12</option>
                    <option>13</option>
                    <option>14</option>
                    <option>15</option>
                    <option>16</option>
                    <option>17</option>
                    <option>18</option>
                    <option>19</option>
                    <option>20</option>
                    <option>21</option>
                    <option>22</option>
                    <option>23</option>
                    <option>24</option>
                    <option>25</option>
                    <option>26</option>
                    <option>27</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_municipio_provimento_efetivos">MUNICIPIO</label>
                <select name="search_municipio_provimento_efetivos" id="municipio_search" class="form-control form-control-sm select2">

                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_uee_provimento_efetivos">NOME DA UNIDADE ESCOLAR</label>
                <select name="search_uee_provimento_efetivos" id="search_uee" class="form-control form-control-sm select2">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="search_codigo_unidade_escolar_efetivo" class="">COD. UE</label>
                <input value="" name="search_codigo_unidade_escolar_efetivo" id="search_codigo_unidade_escolar" type="text" class="form-control form-control-sm">
            </div>
        </div>
    </div>
    <div class="form-row mb-4">
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="search_cpf_servidor_efetivo" class="">CPF</label>
                <input value="" name="search_cpf_servidor_efetivo" id="search_matricula_servidor" type="number" class="form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_pch_efetivo">ANÁLISE CPG</label>
                <select name="search_pch_efetivo" id="search_pch_efetivo" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="OK">ANÁLISSADOS</option>
                    <option value="PENDENTE">PENDENTES</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_assuncao_efetivo">ASSUNÇÃO</label>
                <select name="search_assuncao_efetivo" id="search_assuncao_efetivo" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="COM ASSUNCAO">COM ASSUNÇÃO</option>
                    <option value="PRAZO VENCIDO">PRAZO VENCIDO</option>
                    <option value="DENTRO DO PRAZO">DENTRO DO PRAZO</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div id="buttons" class="mb-3 buttons d-flex align-items-center">
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
            <button id="" class="ml-2 button" type="button" onclick="clearForm()">
                <span class="button__text text-danger">LIMPAR</span>
                <span class="button__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eraser">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3" />
                        <path d="M18 13.3l-6.3 -6.3" />
                    </svg>
                </span>
            </button>
        </div>
    </div>
</form>
<hr>
<div class="table-responsive">
    <table id="consultarCarencias" class="table-bordered table-sm table">
        <thead class="bg-primary text-white">
            <tr class="text-center">
                <td colspan="3"><strong>SERVIDOR ENCAMINHADO</strong></td>
                <td colspan="7"><strong>UNIDADE DE ENCAMINHAMENTO</strong></td>
            </tr>
            <tr class="text-center">
                <th>NTE</th>
                <th>NOME</th>
                <th>CPF</th>
                <th>NTE</th>
                <th>MUNICIPIO</th>
                <th>COD.UEE</th>
                <th>UNIDADE ESCOLAR</th>
                <th>ANÁLISE CPG</th>
                <th>ASSUNÇÃO</th>
                <th>AÇÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($provimentos_encaminhados as $provimentos_encaminhado)
            <tr>
                @if ($provimentos_encaminhado->servidorEncaminhado->nte > 9)
                <td class="text-center">{{ $provimentos_encaminhado->servidorEncaminhado->nte }}</td>
                @else
                <td class="text-center">0{{ $provimentos_encaminhado->servidorEncaminhado->nte }}</td>
                @endif
                <td class="text-center">{{ $provimentos_encaminhado->servidorEncaminhado->nome }}</td>
                <td class="text-center">{{ $provimentos_encaminhado->servidorEncaminhado->cpf }}</td>
                @if ($provimentos_encaminhado->uee->nte > 9)
                <td class="text-center">{{ $provimentos_encaminhado->uee->nte }}</td>
                @else
                <td class="text-center">0{{ $provimentos_encaminhado->uee->nte }}</td>
                @endif
                <td class="text-center">{{ $provimentos_encaminhado->uee->municipio }}</td>
                <td class="text-center">{{ $provimentos_encaminhado->uee->cod_unidade }}</td>
                <td class="text-center">{{ $provimentos_encaminhado->uee->unidade_escolar }}</td>
                @if ($provimentos_encaminhado->pch === "OK")
                @if (($provimentos_encaminhado->server_1_situation != 2) && ($provimentos_encaminhado->server_2_situation != 2))
                <td class="text-center text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </td>
                @else
                <td class="text-center text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-alert-hexagon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path d="M12 8v4" />
                        <path d="M12 16h.01" />
                    </svg>
                </td>
                @endif
                @elseif (($provimentos_encaminhado->server_1_situation == 2) || ($provimentos_encaminhado->server_2_situation == 2))
                @if ($provimentos_encaminhado->inconsistencia === "OK")
                <td class="text-center text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-alert-hexagon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path d="M12 8v4" />
                        <path d="M12 16h.01" />
                    </svg>
                </td>
                @else
                <td class="text-center text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-alert-hexagon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path d="M12 8v4" />
                        <path d="M12 16h.01" />
                    </svg>
                </td>
                @endif
                @else
                <td class="">
                </td>
                @endif
                @php
                $dataEncaminhamento = \Carbon\Carbon::parse($provimentos_encaminhado->data_encaminhamento);
                $diferencaDias = $dataEncaminhamento->diffInDays(\Carbon\Carbon::now());
                @endphp

                @if($provimentos_encaminhado->data_encaminhamento && $diferencaDias >= 2 && $provimentos_encaminhado->data_assuncao == null)
                <td class="text-center text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-alert-hexagon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path d="M12 8v4" />
                        <path d="M12 16h.01" />
                    </svg>
                </td>
                @elseif ($provimentos_encaminhado->data_encaminhamento && $diferencaDias < 2 && $provimentos_encaminhado->data_assuncao == null)
                    <td class="text-center text-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-stats">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
                            <path d="M18 14v4h4" />
                            <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M15 3v4" />
                            <path d="M7 3v4" />
                            <path d="M3 11h16" />
                        </svg>
                    </td>
                    @else
                    <td class="text-center text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                    </td>
                    @endif
                    <td class="text-center d-flex align-items-center justify-content-center">
                        <a href="/provimento/efetivo/detail/{{ $provimentos_encaminhado->id }}" class=""><button id="" type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                            </button>
                        </a>
                        @if ( (Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_coordenador"))
                        @if (($provimentos_encaminhado->pch != "OK") || (Auth::user()->profile === "administrador"))
                        <a data-toggle="tooltip" data-placement="top" title="Excluir" title="Excluir" id="" onclick="destroyProvimentoEfetivo('{{ $provimentos_encaminhado->id }}')" class="ml-1 btn btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 7l16 0" />
                                <path d="M10 11l0 6" />
                                <path d="M14 11l0 6" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </a>
                        @endif
                        @endif
                    </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <tfoot>
        <tr>
            <td colspan="100%">
                <h6>SUMÁRIO</h6>
                <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 10px; padding: 10px; border-radius: 8px; background-color: #ffffff;" class="border mt-2">
                    <div style="display: flex; align-items: center; gap: 8px;" class="text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-alert-hexagon">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                            <path d="M12 8v4" />
                            <path d="M12 16h.01" />
                        </svg>
                        <span class="subheader"><strong>ENCAMINHAMENTOS SEM ASSUNÇÃO COM PRAZO VENCIDO</strong></span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;" class="text-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-stats">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
                            <path d="M18 14v4h4" />
                            <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M15 3v4" />
                            <path d="M7 3v4" />
                            <path d="M3 11h16" />
                        </svg>
                        <span class="subheader"><strong>ENCAMINHAMENTOS SEM ASSUNÇÃO, MAS DENTRO DO PRAZO</strong></span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;" class="text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                        <span class="subheader"><strong>ENCAMINHAMENTOS COM DATA DE ASSUNÇÃO | VALIDAÇÃO DA CPG</strong></span>
                    </div>

                </div>

            </td>
        </tr>
    </tfoot>


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
                    text: 'Já existe um servidor com esse CPF registrado.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Encaminhamento excluido com sucesso!',
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

        const elementosInfo = document.querySelectorAll(".info");

        // Adiciona um ouvinte de evento de clique a cada um deles
        elementosInfo.forEach(function(elemento) {
            elemento.addEventListener("click", function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Há inconsistências no encaminhamento (Provimento incorreto).',
                })
            });
        });
    });
</script>