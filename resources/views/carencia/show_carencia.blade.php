@extends('layout.main')

@section('title', 'SCP - Carência')

@section('content')

<?php

use Carbon\Carbon;

$data_atual = Carbon::now();
$ano_atual = $data_atual->year;
?>

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif
<style>
    .icon-tabler-search,
    .icon-tabler-trash,
    .icon-tabler-replace {
        width: 16px;
        height: 16px;
    }

    .btn {
        padding: 6px !important;
    }
</style>

<div class="bg-primary card text-white card_title">
    <h4 class=" title_show_carencias">Pesquisa de carências cadastradas</h4>
</div>
<div class="form_content mb-0">
    <div class="mb-2 d-flex justify-content-end " style="gap: 3px;">
        <a id="active_filters" class="mb-2 btn bg-primary text-white" data-toggle="tooltip" data-placement="top" title="Filtros Personalizaveis" onclick="active_filters()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
            </svg>
        </a>
        <a class="mb-2 btn bg-primary text-white" target="_blank" href="/relatorio/carencia" data-toggle="tooltip" data-placement="top" title="Imprimir">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
            </svg>
        </a>
        <button class="mb-2 btn bg-primary text-white" data-toggle="modal" data-target="#exportModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-download">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M12 17v-6" />
                <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
            </svg>
        </button>
        <a class="mb-2 btn bg-info text-white" data-toggle="modal" data-target="#modalInfo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
                <path d="M12 19l0 .01" />
            </svg>
        </a>
    </div>
    <form id="active_form" class="border shadow bg-light rounded pt-3 pl-3 pr-3 transition-hidden" action="/carencia/filter_carencias" method="post" hidden>
        @csrf
        <div class="form-row">
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
                    <label class="control-label" for="search_codigo">SELECIONE UM OU MAIS CODIGOS</label>
                    <select name="search_codigo[]" class="form-control form-control-sm" id="search_codigo" multiple>
                        @foreach ($unidadesEscolares as $unidadeEscolar)
                        <option value="{{$unidadeEscolar -> cod_unidade}}">{{$unidadeEscolar -> cod_unidade}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="eixo">TIPO DE CARÊNCIA</label>
                    <select name="search_tipo" id="search_tipo" class="form-control form-control-sm select2">
                        <option></option>
                        <option value="Real">REAL</option>
                        <option value="Temp">TEMPORARIA</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="search_eixo">EIXO</label>
                    <select name="search_eixo" id="search_eixo" class="form-control form-control-sm select2">
                        <option></option>
                        @foreach ($eixo_cursos as $eixo_cursos)
                        <option value="{{$eixo_cursos -> eixo}}">{{$eixo_cursos -> eixo}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group_disciplina">
                    <label class="control-label" for="">CURSO</label>
                    <select name="search_curso" id="search_curso" class="form-control form-control-sm select2">
                        <option></option>
                        @foreach ($cursos as $cursos)
                        <option value="{{$cursos -> curso}}">{{$cursos -> curso}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="search_motivo">MOTIVO DA VAGA</label>
                    <select name="search_motivo" id="search_motivo" class="form-control form-control-sm select2">
                        <option></option>
                        @foreach ($motivo_vagas as $motivo_vaga)
                        <option>{{$motivo_vaga -> motivo}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="form-row d-flex">
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="search_situacao_homologacao">HOMOLOGADA</label>
                    <select name="search_situacao_homologacao" id="search_situacao_homologacao" class="form-control form-control-sm select2">
                        <option></option>
                        <option value="SIM">SIM</option>
                        <option value="NÃO">NÃO</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="search_tipo_vaga">TIPO DA VAGA</label>
                    <select name="search_tipo_vaga" id="search_tipo_vaga" class="form-control form-control-sm select2">
                        <option></option>
                        <option value="Basica">BASICA</option>
                        <option value="Especial">ESPECIAL</option>
                        <option value="Profissionalizante">PROFISSIONALIZANTE</option>
                        <option value="Emitec">EMITEC</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="eixo">SELECIONE UMA OU MAIS DISCIPLINAS</label>
                    <select name="search_disciplina[]" class="form-control form-control-sm" id="search_disciplina" multiple>
                        @foreach ($disciplinas as $disciplinas)
                        <option value="{{$disciplinas -> nome}}">{{$disciplinas -> nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="eixo">SELECIONE UMA OU MAIS ÁREAS</label>
                    <select name="areas[]" class="form-control form-control-sm" id="areas" multiple>
                        @foreach ($areas as $area)
                        <option value="{{ $area->nome }}">{{ $area->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="search_categoria">CATEGORIAS</label>
                    <select name="search_categoria" id="search_categoria" class="form-control form-control-sm select2">
                        <option></option>
                        <option value="Quilombola">QUILOMBOLA</option>
                        <option value="Tempo integral">TEMPO INTEGRAL</option>
                        <option value="Educacao Basica">EDUCAÇÃO BÁSICA</option>
                        <option value="Assentamento">ASSENTAMENTO</option>
                        <option value="Indigena">INDÍGENA</option>
                        <option value="Prisional">PRISIONAL / CASE</option>
                        <option value="Profissional">PROFISSIONAL</option>
                        <option value="Mediacao Tecnologica">MEDIAÇÃO TECNOLÓGICA</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="search_turno">TURNO</label>
                    <select name="search_turno" id="search_turno" class="form-control form-control-sm select2">
                        <option></option>
                        <option value="mat">MATUTINO</option>
                        <option value="vesp">VESPERTINO</option>
                        <option value="not">NOTURNO</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label for="search_matricula_servidor" class="">MATRICULA OU CPF</label>
                    <input value="" name="search_matricula_servidor" id="search_matricula_servidor" type="number" class="form-control form-control-sm">
                </div>
            </div>
            <div class="">
                <ul class="ks-cboxtags">
                    <li><input name="check" type="checkbox" id="checkbox50"><label for="checkbox50">LICENÇAS VENCIDAS</label></li>
                </ul>
            </div>
        </div>
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
    </form>
    <hr>
</div>

<div class="table-responsive">
    <table id="consultarCarencias" class=" table table-sm table-hover table-bordered">
        <caption class="mt-2 subheader">CARÊNCIAS NÃO SUPRIDAS</caption>
        <thead class="">
            <tr class="text-center" style="vertical-align: middle;">
                <th class="bg-primary text-white" scope="col">NTE</th>
                <th class="bg-primary text-white" scope="col">MUNICIPIO</th>
                <th class="bg-primary text-white" scope="col">UNIDADE ESCOLAR</th>
                <th class="bg-primary text-white" scope="col">COD.</th>
                <th class="bg-primary text-white" scope="col">TIPO</th>
                <th class="bg-primary text-white" scope="col">HOMOLOGADA</th>
                <th class="bg-primary text-white" scope="col">DISCIPLINA</th>
                <th class="bg-primary text-white" class="text-center" scope="col">MAT</th>
                <th class="bg-primary text-white" scope="col">VESP</th>
                <th class="bg-primary text-white" scope="col">NOT</th>
                <th class="bg-primary text-white" scope="col">TOTAL</th>
                <th class="bg-primary text-white" scope="col">MOTIVO</th>
                <th class="bg-primary text-white" scope="col">SERVIDOR</th>
                <th class="bg-primary text-white" scope="col">MATRÍCULA</th>
                <th class="bg-primary text-white" scope="col">AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($filteredCarencias as $carencia)
            <tr @if (!empty($carencia->vagaReserva)) class="table-warning" @endif>
                <td class="text-center" scope="row">
                    {{ str_pad($carencia->nte, 2, '0', STR_PAD_LEFT) }}
                </td>
                <td class="">{{ $carencia -> municipio }}</td>
                <td>{{ $carencia -> unidade_escolar }}</td>
                <td class="text-center">{{ $carencia -> cod_ue }}</td>
                @if ($carencia->tipo_carencia === "Real")
                <td data-toggle="modal" data-target="#modalInfo" class="text-center cursor-pointer"><span class="tipo_carencia">R</span></td>
                @endif
                @if ($carencia->tipo_carencia === "Temp")
                <td data-toggle="modal" data-target="#modalInfo" class="text-center cursor-pointer"><span class="tipo_carencia">T</span></td>
                @endif
                @if ( $carencia -> hml === "SIM")
                <td class="text-success text-center"><strong>SIM</strong></td>
                @endif
                @if ( $carencia -> hml === "NÃO")
                <td class="text-danger text-center"><strong>NÃO</strong></td>
                @endif
                <td>{{ $carencia -> disciplina }}</td>
                <td class="text-center">{{ $carencia -> matutino }}</td>
                <td class="text-center">{{ $carencia -> vespertino }}</td>
                <td class="text-center">{{ $carencia -> noturno }}</td>
                <td class="text-center">{{ $carencia -> total }}</td>
                <td>{{ $carencia -> motivo_vaga }}</td>
                <td>{{ $carencia -> servidor }}</td>
                <td class="text-center">{{ $carencia -> cadastro }}</td>
                <td class="d-flex text-center">
                    <a data-toggle="tooltip" data-placement="top" title="Detalhes" title="Detalhar" href="/detalhar_carencia/{{ $carencia -> id }}"><button id="" class=" btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </button>
                    </a>
                    @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                    @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                    @if ($carencia->hml === "SIM")
                    <a data-toggle="tooltip" data-placement="top" title="Excluir" title="Excluir" id="" onclick="destroy('{{ $carencia -> id }}')" class="ml-1 btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 7l16 0" />
                            <path d="M10 11l0 6" />
                            <path d="M14 11l0 6" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                    </a>
                    @elseif ($carencia->hml === "NÃO")
                    <a data-toggle="tooltip" data-placement="top" title="Excluir" title="Excluir" id="" onclick="destroy('{{ $carencia -> id }}')" class="ml-1 btn btn-danger">
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
                    @endif
                    @if ( (session('ano_ref') == $ano_atual) || (Auth::user()->profile === "administrador"))
                    @if ((Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador"))
                    @if ($carencia->hml === "SIM")
                    <a data-toggle="tooltip" data-placement="top" title="Prover" title="Prover" id="" href="/prover/{{ $carencia -> id }}/{{ $carencia -> cod_ue }}" class="ml-1 btn btn-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-replace">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 3m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                            <path d="M15 15m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                            <path d="M21 11v-3a2 2 0 0 0 -2 -2h-6l3 3m0 -6l-3 3" />
                            <path d="M3 13v3a2 2 0 0 0 2 2h6l-3 -3m0 6l3 -3" />
                        </svg>
                    </a>
                    @elseif ($carencia->hml === "NÃO")
                    <a data-toggle="tooltip" data-placement="top" title="Prover" title="Prover" id="" href="/prover/{{ $carencia -> id }}/{{ $carencia -> cod_ue }}" class="ml-1 btn btn-info" hidden>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-replace">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 3m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                            <path d="M15 15m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                            <path d="M21 11v-3a2 2 0 0 0 -2 -2h-6l3 3m0 -6l-3 3" />
                            <path d="M3 13v3a2 2 0 0 0 2 2h6l-3 -3m0 6l3 -3" />
                        </svg>
                    </a>
                    @endif
                    @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tr class="">
            <td class="bg-primary text-white text-center border-right-0"><strong>TOTAL</strong></td>
            <td class="bg-primary border-right-0"></td>
            <td class="bg-primary border-right-0"></td>
            <td class="bg-primary border-right-0"></td>
            <td class="bg-primary border-right-0"></td>
            <td class="bg-primary border-right-0"></td>
            <td class="bg-primary border-right-0"></td>
            <td class="bg-primary text-white text-center border-right-0"><strong>{{ $carenciasMat }}</strong></td>
            <td class="bg-primary text-white text-center border-right-0"><strong>{{ $carenciasVesp }}</strong></td>
            <td class="bg-primary text-white text-center border-right-0"><strong>{{ $carenciasNot }}</strong></td>
            <td class="bg-primary text-white text-center border-right-0"><strong>{{ $carenciasTotal }}</strong></td>
            <td class="bg-primary border-right-0"></td>
            <td class="bg-primary border-right-0"></td>
            <td class="bg-primary border-right-0"></td>
            <td class="bg-primary border-right-0"></td>
        </tr>
    </table>
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
                            <span class="tipo_carencia">R</span> - Real
                        </div>
                        <div class="mt-3">
                            <span class="tipo_carencia">T</span> - Temporária
                        </div>
                        <div class="mt-3 d-flex align-items-center">
                            <span class="tipo_carencia mr-1" style="display: inline-block; width: 80px; height: 30px; background-color: #ffeeb8;"></span>
                            <span class="ms-2"> - Carência com Reserva</span>
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

<!-- Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="exportModalLabel">
                    <i class="fas fa-file-export mr-2"></i>SELECIONAR COLUNAS PARA EXPORTAÇÃO
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Botões ajustados com mesmo tamanho -->
                <div class="row justify-content-center mb-5">
                    <div class="col-md-10">
                        <div class="d-flex">
                            <button type="button" style="width: 150px;" class="btn btn-success flex-fill mx-1 py-2 subheader" id="selectAllBtn">
                                Marcar Todas
                            </button>
                            <button type="button" style="width: 150px;" class="btn btn-danger flex-fill mx-1 py-2 subheader" id="deselectAllBtn">
                                Desmarcar todas
                            </button>
                        </div>
                    </div>
                </div>

                <form id="exportForm">
                    <div class="row">
                        <?php
                        $colunasDisponiveis = [
                            'nte' => 'NTE',
                            'municipio' => 'Município',
                            'unidade_escolar' => 'UEE',
                            'cod_ue' => 'Cod.',
                            'tipo_carencia' => 'Tipo',
                            'eixo' => 'Eixo',
                            'curso' => 'Curso',
                            'area' => 'Área',
                            'disciplina' => 'Disciplina',
                            'servidor' => 'Servidor',
                            'cadastro' => 'Cadastro',
                            'matutino' => 'MAT',
                            'vespertino' => 'VESP',
                            'noturno' => 'NOT',
                            'total' => 'Total'
                        ];

                        $metade = ceil(count($colunasDisponiveis) / 2);
                        $i = 0;

                        echo '<div class="col-md-6 pr-2">';
                        foreach ($colunasDisponiveis as $key => $label) {
                            if ($i == $metade) {
                                echo '</div><div class="col-md-6 pl-2">';
                            }
                            echo '<div class="custom-control custom-checkbox mb-3">';
                            echo '<input type="checkbox" class="custom-control-input column-checkbox" id="col_' . $key . '" name="columns[]" value="' . $key . '" checked>';
                            echo '<label class="custom-control-label" for="col_' . $key . '" style="cursor:pointer;">' . $label . '</label>';
                            echo '</div>';
                            $i++;
                        }
                        echo '</div>';
                        ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary subheader" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success subheader" onclick="exportExcel()">
                    <i class="fas fa-file-excel mr-1"></i>Exportar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-content {
        border-radius: 0.8rem;
    }

    .modal-header {
        padding: 1rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
        max-height: 65vh;
        overflow-y: auto;
        flex-direction: column;
    }

    .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
        background-color: #28a745;
        border-color: #28a745;
    }

    .col-md-6 {
        padding-left: 15px;
        padding-right: 15px;
    }

    .custom-control {
        padding-left: 2rem;
        min-height: 2rem;
    }

    .custom-control-label {
        padding-top: 2px;
        padding-left: 5px;
    }

    /* Estilo específico para os botões de seleção */
    #selectAllBtn,
    #deselectAllBtn {
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    #selectAllBtn:hover,
    #deselectAllBtn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Selecionar todas
        document.getElementById('selectAllBtn').addEventListener('click', function() {
            document.querySelectorAll('.column-checkbox').forEach(function(checkbox) {
                checkbox.checked = true;
            });
        });

        // Desmarcar todas
        document.getElementById('deselectAllBtn').addEventListener('click', function() {
            document.querySelectorAll('.column-checkbox').forEach(function(checkbox) {
                checkbox.checked = false;
            });
        });
    });
</script>
@endsection

<script>
    document.getElementById("selectAll").addEventListener("change", function() {
        let checkboxes = document.querySelectorAll(".column-checkbox");
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    function exportExcel() {
        let selectedColumns = [];
        document.querySelectorAll(".column-checkbox:checked").forEach(checkbox => {
            selectedColumns.push(checkbox.value);
        });

        let url = "/excel/carencias?columns=" + selectedColumns.join(",");
        window.open(url, "_blank");
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const session_message = document.getElementById("session_message");

        if (session_message) {
            if (session_message.value === "error") {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Não é possível excluir a carência porque existem provimentos associados a ela.',
                })
            } else {

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Carência Excluída com sucesso!',
                    showConfirmButton: true,
                })
            }
        }
    });
</script>