@extends('layout.main')

@section('title', 'SCP - Provimento')

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
    </style>

    <div class="bg-primary card text-white card_title">
        <h3 class=" title_show_carencias">Pesquisa de PROVIMENTOS</h3>
    </div>
    <div class="form_content mb-0">
        <div class="mb-2 d-flex justify-content-end " style="gap: 3px;">
            <a id="active_filters" class="mb-2 btn bg-primary text-white" data-toggle="tooltip" data-placement="top"
                title="Filtros Personalizaveis" onclick="active_filters()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
                </svg>
            </a>
            <a class="mb-2 btn bg-primary text-white" target="_blank" href="{{ route('provimentos.relatorio') }}"
                data-toggle="tooltip" data-placement="top" title="Imprimir">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                    <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                </svg>
            </a>
            <a class="mb-2 btn bg-primary text-white" target="_blank" href="{{ route('provimentos.excel') }}"
                data-toggle="tooltip" data-placement="top" title="Download em Excel">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-download">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <path d="M12 17v-6" />
                    <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
                </svg>
            </a>
            <a class="mb-2 btn bg-info text-white" data-toggle="modal" data-target="#modalInfo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
                    <path d="M12 19l0 .01" />
                </svg>
            </a>
        </div>
        <form id="active_form" class="border shadow bg-light rounded p-3" action="{{ route('provimentos.showByForm') }}"
            method="post" hidden>
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
                        <select name="municipio_search" id="municipio_search"
                            class="form-control form-control-sm select2">

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
                        <label for="codigo_unidade_escolar" class="">COD. UE</label>
                        <input value="" name="search_codigo_unidade_escolar" id="search_codigo_unidade_escolar"
                            type="text" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_tipo">TIPO DE PROVIMENTO</label>
                        <select name="search_tipo" id="search_tipo" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="Real">REAL</option>
                            <option value="Temp">TEMPORÁRIA</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="eixo">EIXO</label>
                        <select name="eixo" id="eixo" class="form-control form-control-sm select2">
                            <option></option>
                            @foreach ($eixo_cursos as $eixo_cursos)
                                <option>{{ $eixo_cursos->eixo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="">CURSO</label>
                        <select name="curso" id="curso" class="form-control form-control-sm select2">
                            <option></option>
                            @foreach ($cursos as $cursos)
                                <option value="{{ $cursos->curso }}">{{ $cursos->curso }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_situacao_provimento">situação do provimento</label>
                        <select name="search_situacao_provimento" id="search_situacao_provimento"
                            class="form-control form-control-sm select2">
                            <option></option>
                            <option value="tramite">EM TRÂMITE</option>
                            <option value="provida">PROVIDA</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-row mb-4">
                <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_vinculo">VINCULO</label>
                        <select name="search_vinculo" id="search_vinculo" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="REDA">REDA</option>
                            <option value="EFETIVO">EFETIVO</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_forma">FORMA DE SUP.</label>
                        <select name="search_forma" id="search_forma" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="AJUSTE INTERNO">AJUSTE INTERNO</option>
                            <option value="ALTERAÇÃO DE CH">ALTERAÇÃO DE CH</option>
                            <option value="CONCURSO EFETIVO">CONCURSO EFETIVO</option>
                            <option value="EXEDENTE EFETIVO">EXEDENTE EFETIVO</option>
                            <option value="EXEDENTE REDA">EXEDENTE REDA</option>
                            <option value="REDA EMERGENCIAL">REDA EMERGENCIAL</option>
                            <option value="REDA CONCURSO">REDA CONCURSO</option>
                            <option value="RESERVA DE VAGA">RESERVA DE VAGA</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_tipo_movimentacao">TIPO DE MOV.</label>
                        <select name="search_tipo_movimentacao" id="search_tipo_movimentacao"
                            class="form-control form-control-sm select2">
                            <option></option>
                            <option value="INGRESSO">INGRESSO</option>
                            <option value="RELOTAÇÃO">RELOTAÇÃO</option>
                            <option value="REMOÇÃO">REMOÇÃO</option>
                            <option value="PRÓPRIA UE">NA PRÓPRIA UE</option>
                            <option value="COMPLEMENTAÇÃO">COMPLEMENTAÇÃO</option>
                            <option value="CONVOCAÇÃO SELETIVO">CONVOCAÇÃO SELETIVO</option>
                            <option value="AUTORIZAÇÃO EMERGENCIAL">AUTORIZAÇÃO EMERGENCIAL</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_pch">PCH</label>
                        <select name="search_pch" id="search_pch" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="OK">PROGRAMADO</option>
                            <option value="PENDENTE">PENDENTE</option>
                            <option value="NO ACOMPANHAMENTO">NO ACOMPANHAMENTO</option>
                            <option value="EM SUBSTITUICAO">EM SUBSTITUIÇÃO</option>
                            <option value="SEM INICIO DAS ATIVIDADES">SEM INICIO DAS ATIVIDADES</option>
                            <option value="NAO ASSUMIU">NÃO ASSUMIU</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="search_matricula_servidor" class="">MATRICULA OU CPF</label>
                        <input value="" name="search_matricula_servidor" id="search_matricula_servidor"
                            type="number" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="eixo">SELECIONE UMA OU MAIS DISCIPLINAS</label>
                        <select name="search_disciplina[]" class="form-control form-control-sm" id="search_disciplina"
                            multiple>
                            @foreach ($disciplinas as $disciplinas)
                                <option value="{{ $disciplinas->nome }}">{{ $disciplinas->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="search_matricula_servidor" class="">SERVIDORES</label>
                        <select name="search_servidor_matricula" id="search_servidor_matricula"
                            class="form-control form-control-sm select2">
                            <option></option>
                            <option value="sim">COM MATRICULA</option>
                            <option value="nao">SEM MATRICULA</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="search_created" class="">LANÇAMENTO A PARTIR DE:</label>
                        <input name="search_created" id="search_created" type="date"
                            class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="search_reserva" class="">ORIGEM</label>
                        <select name="search_reserva" id="search_reserva"
                            class="form-control form-control-sm select2">
                            <option></option>
                            <option value="RESERVA">RESERVA</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="search_num_cop" class="">Nº COP</label>
                        <select name="search_num_cop" id="search_num_cop"
                            class="form-control form-control-sm select2">
                            <option></option>
                            @foreach ($numero_do_cop as $num)
                            <option value="{{ $num->num }}">{{ $num->num }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div id="buttons" class="buttons d-flex align-items-center">
                <button id="" class="button" type="submit">
                    <span class="button__text">BUSCAR</span>
                    <span class="button__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                        </svg>
                    </span>
                </button>
                <button id="" class="ml-2 button" type="button" onclick="clearForm()">
                    <span class="button__text text-danger">LIMPAR</span>
                    <span class="button__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="red" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eraser">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3" />
                            <path d="M18 13.3l-6.3 -6.3" />
                        </svg>
                    </span>
                </button>
            </div>
        </form>
        <hr>
    </div>
    <div class="table-responsive">
        <table id="consultarCarencias" class="table table-sm table-hover table-bordered">
            <thead class="bg-primary text-white">
                <tr>
                    <th style="vertical-align: middle;" class="text-center" scope="col">NTE</th>
                    <th style="vertical-align: middle;" scope="col">MUNICIPIO</th>
                    <th style="vertical-align: middle;" scope="col">UNIDADE ESCOLAR</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">COD. UE</th>
                    <th style="vertical-align: middle;" scope="col">SERVIDOR</th>
                    <th style="vertical-align: middle;" scope="col">MATRÍCULA/CPF</th>
                    <th style="vertical-align: middle;" scope="col">TIPO</th>
                    <th style="vertical-align: middle;" scope="col">DISCIPLINA</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">MAT</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">VESP</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">NOT</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">TOTAL</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">MOV.</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">SITUAÇÃO</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">ENCAM.</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">ASSUNÇÃO</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">PCH</th>
                    <th style="vertical-align: middle;" class="text-center" scope="col">AÇÃO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($provimentos as $provimento)
                    <tr>
                        @if ($provimento->nte < 10)
                            <td class="text-center" scope="row">0{{ $provimento->nte }}</td>
                        @endif
                        @if ($provimento->nte >= 10)
                            <td class="text-center" scope="row">{{ $provimento->nte }}</td>
                        @endif
                        <td>{{ $provimento->municipio }}</td>
                        <td>{{ $provimento->unidade_escolar }}</td>
                        <td class="text-center">{{ $provimento->cod_unidade }}</td>
                        <td>{{ $provimento->servidor }}</td>
                        <td class="text-center">{{ $provimento->cadastro }}</td>
                        @if ($provimento->tipo_carencia_provida === 'Real')
                            <td class="text-center"><span class="tipo_carencia">R</span></td>
                        @endif
                        @if ($provimento->tipo_carencia_provida === 'Temp')
                            <td class="text-center"><span class="tipo_carencia">T</span></td>
                        @endif
                        <td>{{ $provimento->disciplina }}</td>
                        <td class="text-center">{{ $provimento->provimento_matutino }}</td>
                        <td class="text-center">{{ $provimento->provimento_vespertino }}</td>
                        <td class="text-center">{{ $provimento->provimento_noturno }}</td>
                        <td class="text-center">{{ $provimento->total }}</td>
                        <td class="text-center">{{ $provimento->tipo_movimentacao }}</td>
                        @if ($provimento->situacao_provimento === 'provida')
                            <td class="text-center">PROVIDO</td>
                        @endif
                        @if ($provimento->situacao_provimento === 'tramite')
                            <td class="text-center">TRÂMITE</td>
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
                        @if ($provimento->pch != 'PENDENTE')
                            @if ($provimento->pch === 'OK')
                                <td class="align-middle text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                </td>
                            @endif
                        @else
                            @if ($provimento->situacao_programacao === 'NO ACOMPANHAMENTO')
                                <td class="text-center" title="Encontra-se no acompanhamento de erro.">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#FFBF00" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-file-text-spark">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5" />
                                        <path d="M9 9h1" />
                                        <path d="M9 13h6" />
                                        <path d="M9 17h3" />
                                        <path
                                            d="M19 22.5a4.75 4.75 0 0 1 3.5 -3.5a4.75 4.75 0 0 1 -3.5 -3.5a4.75 4.75 0 0 1 -3.5 3.5a4.75 4.75 0 0 1 3.5 3.5" />
                                    </svg>
                                </td>
                            @elseif ($provimento->situacao_programacao === 'EM SUBSTITUIÇÃO')
                                <td class="text-center" title="Em substituição de licença">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#007bff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                        <path d="M12 7v5l3 3" />
                                    </svg>
                                </td>
                            @elseif ($provimento->situacao_programacao === 'NAO ASSUMIU')
                                <td class="text-center" title="Não Assumiu as Atividades">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                        <path d="M10 10l4 4m0 -4l-4 4" />
                                    </svg>
                                </td>
                            @elseif ($provimento->situacao_programacao === 'SEM INICIO DAS ATIVIDADES')
                                <td class="text-center" title="Sem inicio das Atividases">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#ff4747" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-question">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                                        <path d="M19 22v.01" />
                                        <path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
                                    </svg>
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endif
                        <td class="text-center" style="vertical-align: middle;">
                            <div class="d-flex">
                                <a href="/provimento/detalhes_provimento/{{ $provimento->id }}"><button id=""
                                        class=" btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                            <path d="M21 21l-6 -6" />
                                        </svg>
                                    </button>
                                </a>

                                @if (Auth::user()->profile === 'cpm_tecnico' ||
                                        Auth::user()->profile === 'administrador' ||
                                        Auth::user()->profile === 'cpm_coordenador' ||
                                        Auth::user()->profile === 'cpg_tecnico')
                                    @if ($provimento->total > 0)
                                        @if (
                                            $provimento->situacao === 'BLOQUEADO' ||
                                                ($provimento->pch === 'OK' && $provimento->situacao_provimento === 'provida'))
                                            <a title="Excluir" id="" onclick="event.preventDefault()"
                                                class="text-white ml-1 btn-show-carência btn btn-sm btn-muted" disabled
                                                hidden></a>
                                        @elseif (
                                            ($provimento->situacao === 'DESBLOQUEADO' && $provimento->pch === 'PENDENTE') ||
                                                Auth::user()->profile === 'administrador')
                                            <a id="" onclick="destroyProvimento('{{ $provimento->id }}')"
                                                class="ml-1 btn btn-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </a>
                                        @endif
                                    @elseif ($provimento->total < 0)
                                        <a title="Excluir" id=""
                                            onclick="destroyProvimento('{{ $provimento->id }}')"
                                            class="ml-1 btn-show-carência btn btn-sm btn-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
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
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tr class="bg-primary">
                <td class="bg-primary text-white text-center border-right-0"><strong>TOTAL</strong></td>
                <td class="border-right-0"></td>
                <td class="border-right-0"></td>
                <td class="border-right-0"></td>
                <td class="border-right-0"></td>
                <td class="border-right-0"></td>
                <td class="border-right-0"></td>
                <td class="border-right-0"></td>
                <td class="border-right-0 bg-primary text-white text-center"><strong>{{ $provimentosMat }}</strong></td>
                <td class="border-right-0 bg-primary text-white text-center"><strong>{{ $provimentosVesp }}</strong></td>
                <td class="border-right-0 bg-primary text-white text-center"><strong>{{ $provimentosNot }}</strong></td>
                <td class="border-right-0 bg-primary text-white text-center"><strong>{{ $provimentosTotal }}</strong></td>
                <td class="border-right-0"></td>
                <td class="border-right-0"></td>
                <td class="border-right-0"></td>
                <td class="border-right-0"></td>
                <td class="border-right-0"></td>
                <td class=""></td>
            </tr>
        </table>
        <div class="d-flex align-items-center my-3" style="font-size: 0.9em;">
            <div class="d-flex align-items-center mr-4">
                {{-- Ícone para Programado --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="#57b657" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12l5 5l10 -10" />
                </svg>
                <span class="ml-2">Programado</span>
            </div>
            <div class="d-flex align-items-center mr-4">
                {{-- Ícone para No Acompanhamento --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="#FFBF00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-text-spark">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5" />
                    <path d="M9 9h1" />
                    <path d="M9 13h6" />
                    <path d="M9 17h3" />
                    <path
                        d="M19 22.5a4.75 4.75 0 0 1 3.5 -3.5a4.75 4.75 0 0 1 -3.5 -3.5a4.75 4.75 0 0 1 -3.5 3.5a4.75 4.75 0 0 1 3.5 3.5" />
                </svg>
                <span class="ml-2">No Acompanhamento</span>
            </div>
            <div class="d-flex align-items-center">
                {{-- Ícone para Em Substituição --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="#007bff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                    <path d="M12 7v5l3 3" />
                </svg>
                <span class="ml-2">Em Substituição de Licença</span>
            </div>
            <div class="d-flex align-items-center ml-4">
                {{-- Ícone para SEM INICIO DAS ATIVIDADES --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="#ff4747" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-question">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                    <path d="M19 22v.01" />
                    <path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
                </svg>
                <span class="ml-2">Sem Início das Atividades na Unidade Escolar</span>
            </div>
            <div class="d-flex align-items-center ml-4 text-danger">
                {{-- Ícone para SEM INICIO DAS ATIVIDADES --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M10 10l4 4m0 -4l-4 4" />
                </svg>
                <span class="ml-2 text-dark">Não Assumiu</span>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="TitulomodalInfo"
        aria-hidden="true">
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
                                <span class="tipo_carencia">R</span> - Vaga Real
                            </div>
                            <div class="mt-3">
                                <span class="tipo_carencia">T</span> - Vaga Temporária
                            </div>
                            <div class="mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#57b657" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l5 5l10 -10" />
                                </svg> - Programado
                            </div>
                            <div class="mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#FFBF00" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-text-spark">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5" />
                                    <path d="M9 9h1" />
                                    <path d="M9 13h6" />
                                    <path d="M9 17h3" />
                                    <path
                                        d="M19 22.5a4.75 4.75 0 0 1 3.5 -3.5a4.75 4.75 0 0 1 -3.5 -3.5a4.75 4.75 0 0 1 -3.5 3.5a4.75 4.75 0 0 1 3.5 3.5" />
                                </svg> - Vaga encontra-se no acompanhamento de erros.
                            </div>
                            <div class="mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#007bff" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                    <path d="M12 7v5l3 3" />
                                </svg> - Vaga de Substituição de Licença.
                            </div>
                            <div class="mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#ff4747" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-question">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                                    <path d="M19 22v.01" />
                                    <path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
                                </svg> - Sem Início das Atividades na Unidade Escolar.
                            </div>
                            <div class="mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M10 10l4 4m0 -4l-4 4" />
                                </svg> - Não Assumiu.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-minimize">
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
