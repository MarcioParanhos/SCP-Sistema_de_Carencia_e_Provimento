@extends('layout.main')

@section('title', 'SCP - Unidades Escolares')

@section('content')
<style>
    .btn {
        padding: 6px !important;
    }
</style>
<div class="bg-primary card text-white card_title">
    <h4 class=" title_show_carencias">UNIDADES ESCOLARES</h4>
</div>
<div class="form_content mb-0">
    <div class="mb-2 print-btn">
        <a id="active_filters" class="mb-2 btn bg-primary text-white" data-toggle="tooltip" data-placement="top" title="Filtros Personalizaveis" onclick="active_filters()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
            </svg>
        </a>
        <!-- <a class="mb-2 btn bg-primary text-white" target="_blank" href="{{ route('uees.excel') }}"><i class="ti-download"></i> EXCEL</a> -->
        <a class="mb-2 btn bg-primary text-white" target="_blank" href="{{ route('uees.excel') }}" data-toggle="tooltip" data-placement="top" title="Download em Excel">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-download">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M12 17v-6" />
                <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
            </svg>
        </a>
        <!-- <a class="mb-2 btn bg-primary text-white" href="" data-toggle="modal" data-target="#createUeesMoral"><i class="ti-plus"></i> ADICIONAR</a> -->
    </div>
    <form id="active_form" class="border shadow bg-light rounded pt-3 pl-3 pr-3" action="{{ route('uees.showByForm') }}" method="post" hidden>
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
                <div class="col-md-3">
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
                <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label for="codigo_unidade_escolar" class="">COD. UE</label>
                        <input value="" name="search_codigo_unidade_escolar" id="search_codigo_unidade_escolar" type="text" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_situacao_homologacao">HOMOLOGADA</label>
                        <select name="search_situacao_homologacao" id="search_situacao_homologacao" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="HOMOLOGADA">SIM</option>
                            <option value="PENDENTE">NÃO</option>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_tipologia">TIPOLOGIA</label>
                        <select name="search_tipologia" id="search_tipologia" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="SEDE">SEDE</option>
                            <option value="ANEXO">ANEXO</option>
                            <option value="CEMIT">CEMIT</option>
                        </select>
                    </div>
                </div> -->
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_tipologia">ETAPA DA PROGRAMAÇÃO</label>
                        <select name="search_etapa_pch" id="search_etapa_pch" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="0">1ª PROGRAMAÇÂO</option>
                            <option value="2">2ª PROGRAMAÇÃO</option>
                            <option value="3">3ª PROGRAMAÇÃO</option>
                            <option value="4">4ª PROGRAMAÇÃO</option>
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
                            <option value="Educacao Especial">EDUCAÇÃO ESPECIAL</option>
                            <option value="No Campo">NO CAMPO</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_carencia">CARÊNCIA</label>
                        <select name="search_carencia" id="search_carencia" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="SIM">SIM</option>
                            <option value="NÃO">NÃO</option>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-3">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_situcao">SITUAÇÃO</label>
                        <select name="search_situcao" id="search_situcao" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="Desativada">DESATIVADAS</option>
                        </select>
                    </div>
                </div> -->
                <div class="col-md-3">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="eixo">SELECIONE UMA OU MAIS TIPOLOGIAS</label>
                        <select name="search_tipologia[]" class="form-control form-control-sm" id="search_disciplina" multiple>
                            <option value="SEDE">SEDE</option>
                            <option value="ANEXO">ANEXO</option>
                            <option value="CEMIT">CEMIT</option>
                        </select>
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
            </div>
        </div>
    </form>
    <hr>
    <div id="unidades_info" class="col-md-12 d-flex justify-content-center">
        <div class="col-md-4">
            <div class="mb-1 bg-primary card text-white card_title">
                <h6 class="title_show_carencias">UNIDADES HOMOLOGADAS</h6>
            </div>
            <table class="mt-0 table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">1º PROG</th>
                        <th class="text-center" scope="col">2º PROG.</th>
                        <th class="text-center" scope="col">3º PROG.</th>
                        <th class="text-center" scope="col">4º PROG.</th>
                        <th class="text-center" scope="col">TOTAL PROG.</th>
                    </tr>
                </thead>
                <tr>
                    <th class="text-center" scope="row">{{ $totalUnits }}</th>
                    <th class="text-center" scope="row"> {{ $totalUnits2Pch }}</th>
                    <th class="text-center" scope="row">{{ $totalUnits3Pch }}</th>
                    <th class="text-center" scope="row">{{ $totalUnits4Pch }}</th>
                    <th class="text-center" scope="row">{{ $totalUnits3Pch + $totalUnits2Pch + $totalUnits + $totalUnits4Pch}}</th>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="mb-1 bg-primary card text-white card_title">
                <h6 class="text-sm title_show_carencias">QTD. GERAL DE UNIDADES POR TIPOLOGIA</h6>
            </div>
            <table class="mt-0 table table-sm table-bordered">
                <thead>
                    <tr>
                        <th style="vertical-align: middle; width: 25%;" class="text-center" scope="col">SEDE</th>
                        <th style="vertical-align: middle; width: 25%;" class="text-center" scope="col">ANEXO</th>
                        <th style="vertical-align: middle; width: 25%;" class="text-center" scope="col">CEMIT/EMITEC</th>
                        <th style="vertical-align: middle; width: 25%;" class="text-center" scope="col">TOTAL</th>
                    </tr>
                </thead>
                <tr>
                    <th style="vertical-align: middle; width: 25%;" class="text-center" scope="row">{{ $totalUnitsSedes }}</th>
                    <th style="vertical-align: middle; width: 25%;" class="text-center" scope="row"> {{ $totalUnitsAnexos }}</th>
                    <th style="vertical-align: middle; width: 25%;" class="text-center" scope="row">{{ $totalUnitsCemits }}</th>
                    <th style="vertical-align: middle; width: 25%;" class="text-center" scope="row">{{ $totalUnitsSedes+$totalUnitsAnexos+$totalUnitsCemits }}</th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3 table-responsive">
    <table id="consultarCarencias" class="table-bordered table-sm table">
        <thead>
            <tr class="bg-primary text-white">
                <th class="text-center" scope="col">NTE</th>
                <th scope="col">MUNICIPIO</th>
                <th scope="col">UNIDADE ESCOLAR</th>
                <th class="text-center" scope="col">COD. SEC</th>
                <th class="text-center" scope="col">COD. SAP</th>
                <th class="text-center" scope="col">TIPOLOGIA</th>
                @if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpg_tecnico") )
                <th class="text-center" scope="col">HOMOLOGADA</th>
                @endif
                <th class="text-center" scope="col">CARÊNCIA</th>
                <th class="text-center" scope="col">2ª PROG.</th>
                <th class="text-center" scope="col">3ª PROG.</th>
                <th class="text-center" scope="col">4ª PROG.</th>
                <th class="text-center" scope="col">CATEGORIAS</th>
                @if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpg_tecnico") )
                <th class="text-center" scope="col">AÇÕES</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($uees as $uee)
            <tr>
                @if ( $uee->nte < 10)<th class="text-center" scope="row">0{{ $uee->nte }}</th>
                    @endif
                    @if ( $uee->nte >= 10)
                    <th class="text-center" scope="row">{{ $uee->nte }}</th>
                    @endif
                    <td>{{ $uee->municipio }}</td>
                    <td>{{ $uee->unidade_escolar }}</td>
                    <td class="text-center">{{ $uee->cod_unidade }}</td>
                    <td class="text-center">{{ $uee->uo ?? 'Sem UO' }}</td>
                    <td class="text-center">{{ $uee->tipo }}</td>
                    @if ( $uee->tipo === "NTE")
                    <td class="text-center"></td>
                    @else
                    @if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpg_tecnico") )
                    <td class="text-center">
                        @if ($uee->situacao === "HOMOLOGADA")
                        <label class="toggle">
                            <input class="toggle-checkbox" type="checkbox" checked>
                            <div class="toggle-switch"></div>
                            <!-- <span class="toggle-label">HOMOLOGADA</span> -->
                        </label>
                        @elseif ($uee->situacao == "PENDENTE")
                        <label class="toggle">
                            <input class="toggle-checkbox" type="checkbox">
                            <div class="toggle-switch"></div>
                            <!-- <span class="toggle-label">HOMOLOGADA</span> -->
                        </label>
                        @else

                        @endif
                    </td>
                    @endif
                    @endif
                    @if ($uee->situacao === "HOMOLOGADA")
                    @if ($uee->carencia === "SIM")
                    <td class="text-danger text-center"><strong>SIM</strong></td>
                    @else
                    <td class="text-success text-center"><strong>NÃO</strong></td>
                    @endif
                    @else
                    <td class="text-success text-center"></td>
                    @endif
                    @if ($uee->programming_stage === 2)
                    <td class="text-center">
                        <span class="bg-success tipo_carencia"><i class="ti-check"></i></span>
                    </td>
                    <td class="text-center">
                    </td>
                    <td class="text-center">
                    </td>
                    @endif
                    @if ($uee->programming_stage === 3)
                    <td class="text-center">
                        <span class="bg-success tipo_carencia"><i class="ti-check"></i></span>
                    </td>
                    <td class="text-center">
                        <span class="bg-success tipo_carencia"><i class="ti-check"></i></span>
                    </td>
                    <td class="text-center">
                    </td>
                    @endif
                    @if ($uee->programming_stage === 4)
                    <td class="text-center">
                        <span class="bg-success tipo_carencia"><i class="ti-check"></i></span>
                    </td>
                    <td class="text-center">
                        <span class="bg-success tipo_carencia"><i class="ti-check"></i></span>
                    </td>
                    <td class="text-center">
                        <span class="bg-success tipo_carencia"><i class="ti-check"></i></span>
                    </td>
                    @endif
                    @if (($uee->programming_stage === 0) || ($uee->programming_stage === null))
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    @endif
                    <div class="d-flex">
                        <td class="align-items-center justify-content-center">
                            @php
                            $searchCategoria = request('search_categoria');
                            @endphp
                            @if($uee->categorias)
                            @foreach(json_decode($uee->categorias) as $categoria)
                            @if (empty($searchCategoria) || $categoria == $searchCategoria)
                            @if ( $categoria == 'sedeCemit')
                            <span class="badge badge-pill badge-primary" style="margin: 2px;">Sede / CEMIT</span>
                            @else
                            <span class="badge badge-pill badge-primary" style="margin: 2px;">{{ $categoria }}</span>
                            @endif
                            @endif
                            @endforeach
                            @endif
                        </td>
                    </div>
                    @if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpg_tecnico"))
                    @if ( $uee->tipo === "NTE")
                    <td class="text-center"></td>
                    @else
                    <td class="text-center">
                        <div class="d-flex">
                            @if ($uee->situacao != "NTE")
                            <a title="Detalhar" href="/uees/detail/{{ $uee->id }}"><button id="" class="btn-show-carência btn btn-primary"><i class="ti-search"></i></button></a>
                            @endif
                            @if (Auth::user()->profile === "administrador")
                            <a title="Excluir" href="/uee/destroy/{{ $uee->id }}" class="ml-1 btn-show-carência btn btn-danger"><i class="ti-trash"></i></a>
                            @endif
                        </div>
                    </td>
                    @endif
                    @endif
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
                    <form class="forms-sample " id="InsertReasonVacancies" action="#" method="POST">
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
@endsection