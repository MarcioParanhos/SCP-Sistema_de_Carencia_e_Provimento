@extends('layout.main')

@section('title', 'SCP - Regularização Funcional')

@section('content')

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

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif

<div class="card mb-4">
    <div class="bg-primary text-center text-white card-header">
        <h4>REGULARIZAÇÃO FUNCIONAL</h4>
    </div>
</div>


<div id="regularizacao_container" class="d-flex mb-4 justify-content-between">
    <div id="count-infos" class="d-flex col-md-10">
        <!-- <div class="d-flex col-md-3">
            <div class="col-md-12">
                <table class="table-bordered">
                    <th class="pl-2 bg-primary subheader text-white">TOTAL DE REGULARIZAÇÕES</th>
                    <th class="text-center bg-primary  text-white">{{ $quantidade_de_registros }}</th>
                    <tr>
                        <td class="pl-2 subheader"><b>PENDENTES CPM</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pendente_cpm }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>PENDENTES CGI</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pendente_cgi }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>PENDENTES CPG</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pendente_cpg }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>PCH - Programado</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pch_ok }}</b></td>
                    </tr>
                </table>
            </div>
        </div> -->
        <div class="d-flex col-md-4">
            <div class="col-md-12">
                <table class="table-bordered">
                    <th colspan="2" class="p-1 text-center pl-2 bg-primary subheader text-white">CPM</th>
                    <tr>
                        <td class="pl-2 subheader"><b>PENDENTE</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pendente_cpm }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>EM ANÁLISE (<span class="text-danger"><strong>PENDENTE</strong></span>)</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_emAnalise_cpm }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>SERVIDOR AFASTADO</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_servidorAfastado_cpm }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>AGUARDANDO CPG CORRIGIR DADOS</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pendenteCPG_cpm }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>ATO EM APROVAÇÃO</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_atoEmAprovacao_cpm }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>REGULARIZADA</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_regularizado_cpm }}</b></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="d-flex col-md-4">
            <div class="col-md-12">
                <table class="table-bordered">
                    <th colspan="2" class="p-1 text-center pl-2 bg-primary subheader text-white">CGI</th>
                    <tr>
                        <td class="pl-2 subheader"><b>PENDENTE</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pendente_cgi }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>EM ANÁLISE (<span class="text-danger"><strong>PENDENTE</strong></span>)</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_emAnalise_cgi }}</b></td>
                    </tr>

                    <tr>
                        <td class="pl-2 subheader"><b>PENDENTE CPM (DEVOLUÇÃO)</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pendenteCPM_cgi }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>ATO EM APROVAÇÃO</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_atoEmAprovacao_cgi }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>AGUARDANDO INTEGRAÇÃO</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_aguardandoIntegracao_cgi }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>REGULARIZADA</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_regularizado_cgi }}</b></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="d-flex col-md-4">
            <div class="col-md-12">
                <table class="table-bordered">
                    <th colspan="2" class="p-1 text-center pl-2 bg-primary subheader text-white">CPG</th>
                    <tr>
                        <td class="pl-2 subheader"><b>PENDENTE</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pendente_cpg }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>EM ANÁLISE (<span class="text-danger"><strong>PENDENTE</strong></span>)</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_emAnalise_cpg }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>CORRIGIR DADOS</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_corrigirDados_cpg }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>PENDENTE CPM (DEVOLUÇÃO)</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pendenteCPM_cpg }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>PENDENTE CGI (DEVOLUÇÃO)</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pendenteCGI_cpg }}</b></td>
                    </tr>
                    <tr>
                        <td class="pl-2 subheader"><b>PROGRAMADO</b></td>
                        <td style="width: 20%;" class="text-center "><b>{{ $quantidade_de_registros_pch_ok }}</b></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div id="regularizacao_filter" class="mb-2">
        <a id="active_filters" class="mb-2 btn bg-primary text-white" data-toggle="tooltip" data-placement="top" title="Filtros Personalizaveis" onclick="active_filters()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
            </svg>
        </a>
        @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
        <a class="mb-2 btn bg-primary text-white" data-toggle="tooltip" data-placement="top" title="Adicionar Novo" href="{{ route('regularizacao_funcional.create') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 5l0 14" />
                <path d="M5 12l14 0" />
            </svg>
        </a>
        @endif
        <a class="mb-2 btn bg-primary text-white" target="_blank" href="/regularizacao_funcional/data/excel" data-toggle="tooltip" data-placement="top" title="Download em Excel">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-download">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M12 17v-6" />
                <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
            </svg>
        </a>
        <a class="mb-2 btn bg-info text-white" data-toggle="modal" data-target="#modalInfo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
                <path d="M12 19l0 .01" />
            </svg>
        </a>
    </div>
</div>

<form id="active_form" class="border shadow bg-light rounded pt-3 pl-3 pr-3" action="{{ route('reg_funcional.showByForm') }}" method="post" hidden>
    @csrf
    <div class="form-row col-md-12">
        <div class="form-row col-md-12">
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="nte_seacrh">NTE</label>
                    <select name="nte_seacrh" id="nte_seacrh" class="form-control form-control-sm select2">
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
                    <label class="control-label" for="municipio_search">MUNICIPIO</label>
                    <select name="municipio_search" id="municipio_search" class="form-control form-control-sm select2">

                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group_disciplina">
                    <label class="control-label" for="search_uee">NOME DA UNIDADE ESCOLAR</label>
                    <select name="search_uee" id="search_uee" class="form-control form-control-sm select2">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="cpm_seacrh">SITUAÇÃO CPM</label>
                    <select name="cpm_seacrh" id="cpm_seacrh" class="form-control form-control-sm select2">
                        <option></option>
                        <option value="EM ANÁLISE">EM ANÁLISE</option>
                        <option value="PENDENTE">PENDENTE</option>
                        <option value="PENDENTE CPG">REG. FUNC. INCORRETA</option>
                        <option value="REGULARIZADA">REGULARIZADA</option>
                        <option value="ATO EM APROVAÇÃO">ATO EM APROVAÇÃO</option>
                        <option value="SERVIDOR AFASTADO">SERVIDOR AFASTADO</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="cgi_seacrh">SITUAÇÃO CGI</label>
                    <select name="cgi_seacrh" id="cgi_seacrh" class="form-control form-control-sm select2">
                        <option></option>
                        <option value="EM ANÁLISE">EM ANÁLISE</option>
                        <option value="REGULARIZADA">REGULARIZADA</option>
                        <option value="PENDENTE">PENDENTE</option>
                        <option value="PENDENTE CPM">PENDENTE CPM</option>
                        <option value="ATO EM APROVAÇÃO">ATO EM APROVAÇÃO</option>
                        <option value="AGUARDANDO INTEGRAÇÃO">AGUARDANDO INTEGRAÇÃO</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="cpg_seacrh">SITUAÇÃO CPG</label>
                    <select name="cpg_seacrh" id="cpg_seacrh" class="form-control form-control-sm select2">
                        <option></option>
                        <option value="EM ANÁLISE">EM ANÁLISE</option>
                        <option value="PROGRAMADO">PROGRAMADO</option>
                        <option value="PENDENTE">PENDENTE</option>
                        <option value="PENDENTE CPM">PENDENTE CPM</option>
                        <option value="PENDENTE CGI">PENDENTE CGI</option>
                        <option value="CORRIGIR REGULARIZAÇÃO">CORRIGIR DADOS</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="tipo_regularizacao_search">TIPO DE REGULARIZAÇÃO</label>
                    <select name="tipo_regularizacao_search" id="tipo_regularizacao_search" class="form-control form-control-sm select2">
                        <option></option>
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
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="search_cadastro">MATRÍCUlA</label>
                    <input value="" name="search_cadastro" id="search_cadastro" type="text" class="form-control form-control-sm">
                </div>
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
    <table id="consultarCarencias" class="table table-hover wrap table-bordered table-sm">
        <thead class="bg-primary text-white">
            <tr>
                <td class="text-center" colspan="3">SERVIDOR</td>
                <td class="text-center" colspan="4">UNIDADE DE DESTINO</td>
                <td class="text-center" colspan="6">INFORMAÇÕES</td>
            </tr>
            <tr>
                <td class="text-center" scope="col">NOME</td>
                <td class="text-center" scope="col">MATRICULA</td>
                <td class="text-center" scope="col">VINCULO</td>
                <td class="text-center" scope="col">NTE</td>
                <td class="text-center" scope="col">MUNICIPIO</td>
                <td class="text-center" scope="col">UNIDADE ESCOLAR</td>
                <td class="text-center" scope="col">COD.</td>
                <td class="text-center" scope="col">TIPO</td>
                <td class="text-center" scope="col">ASSUNÇÃO</td>
                <td class="text-center" scope="col">CPM</td>
                <td class="text-center" scope="col">CGI</td>
                <td class="text-center" scope="col">CPG</td>
                <td class="text-center" scope="col">AÇÃO</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($regularizacões_funcionais as $regularizacão_funcional)
            <tr>
                <td class="">{{ $regularizacão_funcional->servidor->nome}}</td>
                <td class="text-center">{{ $regularizacão_funcional->servidor->cadastro }}</td>
                <td class="text-center">{{ $regularizacão_funcional->servidor->vinculo }}</td>
                @if ($regularizacão_funcional->ueeDestino->nte < 9) <td class="text-center">0{{ $regularizacão_funcional->ueeDestino->nte }}</td>
                    @else
                    <td class="text-center">{{ $regularizacão_funcional->ueeDestino->nte }}</td>
                    @endif
                    <td class="text-center">{{ $regularizacão_funcional->ueeDestino->municipio }}</td>
                    <td class="">{{ $regularizacão_funcional->ueeDestino->unidade_escolar }}</td>
                    <td class="text-center">{{ $regularizacão_funcional->ueeDestino->cod_unidade }}</td>
                    <td class="text-center">{{ $regularizacão_funcional->tipo_regularizacao}}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($regularizacão_funcional->data)->format('d/m/Y') }}</td>

                    @if ($regularizacão_funcional->situacao_cpm === "PENDENTE")
                    <td class="text-center text-danger"><strong><span style="width: 100%;" class="badge badge-pill badge-danger ">PENDENTE</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cpm === "EM ANÁLISE")
                    <td class="text-center text-info"><strong><span style="width: 100%;" class="badge badge-pill badge-info">EM ANÁLISE</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cpm === "PENDENTE CPG")
                    <td class="text-center text-warning"><strong><span style="width: 100%;" class="badge badge-pill badge-warning">PENDENTE CPG</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cpm === "ATO EM APROVAÇÃO")
                    <td class="text-center text-warning"><strong><span style="width: 100%;" class="badge badge-pill badge-warning">ATO EM APROVAÇÃO</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cpm === "SERVIDOR AFASTADO")
                    <td class="text-center text-warning"><strong><span style="width: 100%;" class="badge badge-pill badge-warning">SERVIDOR AFASTADO</span></strong></td>

                    @else
                    <td class="text-center text-success"><strong><span style="width: 100%;" class="badge badge-pill badge-success">REGULARIZADO</span></strong></td>
                    @endif
                    @if ($regularizacão_funcional->situacao_cgi === "PENDENTE")
                    <td class="text-center text-danger"><strong><span style="width: 100%;" class="badge badge-pill badge-danger">PENDENTE</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cgi === "EM ANÁLISE")
                    <td class="text-center text-info"><strong><span style="width: 100%;" class="badge badge-pill badge-info">EM ANÁLISE</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cgi === "PENDENTE CPM")
                    <td class="text-center text-info"><strong><span style="width: 100%;" class="badge badge-pill badge-info">PENDENTE CPM</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cgi === "ATO EM APROVAÇÃO")
                    <td class="text-center text-warning"><strong><span style="width: 100%;" class="badge badge-pill badge-warning">ATO EM APROVAÇÃO</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cgi === "AGUARDANDO INTEGRAÇÃO")
                    <td class="text-center text-warning"><strong><span style="width: 100%;" class="badge badge-pill badge-warning">AGUARDANDO INTEGRAÇÃO</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cgi === "REGULARIZADA")
                    <td class="text-center text-success"><strong><span style="width: 100%;" class="badge badge-pill badge-success">REGULARIZADO</span></strong></td>
                    @else
                    <td class="text-center"> - </td>
                    @endif
                    @if ($regularizacão_funcional->situacao_cpg === "PENDENTE")
                    <td class="text-center text-danger"><strong><strong><span style="width: 100%;" class="badge badge-pill badge-danger">PENDENTE</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cpg === "EM ANÁLISE")
                    <td class="text-center text-info"><strong><strong><span style="width: 100%;" class="badge badge-pill badge-info">EM ANÁLISE</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cpg === "PENDENTE CPM")
                    <td class="text-center text-info"><strong><strong><span style="width: 100%;" class="badge badge-pill badge-info">PENDENTE CPM</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cpg === "PENDENTE CGI")
                    <td class="text-center text-info"><strong><span style="width: 100%;" class="badge badge-pill badge-info">PENDENTE CGI</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cpg === "PROGRAMADO")
                    <td class="text-center text-success"><strong><span style="width: 100%;" class="badge badge-pill badge-success">PROGRAMADO</span></strong></td>
                    @elseif ($regularizacão_funcional->situacao_cpg === "CORRIGIR REGULARIZAÇÃO")
                    <td class="text-center"><strong><span class="badge badge-pill text-primary badge-warning">CORRIGIR DADOS</span></strong></td>
                    @else
                    <td class="text-center"> - </td>
                    @endif
                    <td class="text-center d-flex align-items-center justify-content-center">
                        <a href="/regularizacao_funcional/detail/{{ $regularizacão_funcional->id }}" class="mr-2"><button id="" type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                            </button>
                        </a>
                        @if (((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador")) && ($regularizacão_funcional->situacao_cpg != "PROGRAMADO"))
                        <a data-toggle="tooltip" data-placement="top" title="Excluir" title="Excluir" id="" onclick="destroyRegularizacao('{{ $regularizacão_funcional -> id }}')" class="ml-1 btn btn-danger">
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
                    </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Modal para adcicionar novo motivo de uma vaga -->
<div class="modal fade" id="createUeesMoral" tabindex="-1" role="dialog" aria-labelledby="createUeesMoral" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ReasonVacanciesModal">NOVA UNIDADE ESCOLAR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <form class="forms-sample" id="InsertReasonVacancies" action="#" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="nteForCreateUee">NTE</label>
                                    <select name="nte_seacrh" id="nteForCreateUee" class="form-control form-control-sm select2">
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
                                    <label class="control-label" for="municipioForCreateUee">MUNICIPIO</label>
                                    <select name="municipio_search" id="municipioForCreateUee" class="form-control form-control-sm select2">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="unidade_escolar">NOME DA UNIDADE ESCOLAR</label>
                                    <input value="" name="unidade_escolar" id="unidade_escolar" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="cod_unidade">COD. UNIDADE</label>
                                    <input value="" name="cod_unidade" id="cod_unidade" type="number" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center text-center flex-row">
                            <button style="width: 30%;" type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                            <button style="width: 30%;" type="submit" class="btn btn-primary">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="TitulomodalInfo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TitulomodalInfo">INFORMAÇÕES GERAIS</h5>
            </div>
            <div class="modal-body justify-content-start p-4">
                <div class="">
                    <h5>Legenda:</h5>
                    <div class="pt-2">
                        <div>
                            <strong><span class="badge badge-pill badge-success">REGULARIZADO</span></strong> - Etapa concluida pela coordenação.
                        </div>
                        <div class="mt-3">
                            <strong><span class="badge badge-pill badge-danger ">PENDENTE</span></strong> - Pendente conclusão da etapa.
                        </div>
                        <div class="mt-3">
                            <strong><span class="badge badge-pill badge-info ">EM ANÁLISE</span></strong> - Coordenação análisando a regularização.
                        </div>
                        <div class="mt-3">
                            <strong><span class="badge badge-pill badge-warning ">PENDENTE CPG</span></strong> - Aguardando CPG corrigir dados.
                        </div>
                        <div class="mt-3">
                            <strong><span class="badge badge-pill badge-warning ">CORRIGIR DADOS</span></strong> - Pendente CPG corrigir os dados.
                        </div>
                        <div class="mt-3">
                            <strong><span class="badge badge-pill badge-warning ">ATO EM APROVAÇÃO</span></strong> - Aguardando a publicação do ATO.
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
                    text: 'A exclusão desta disciplina não é viável devido à existência de carências e provimentos a ela.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Disciplina adicionada com sucesso!',
                })
            } else if (session_message.value === "delete_success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Regularização Funcional excluida com sucesso!',
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