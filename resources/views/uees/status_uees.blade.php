@extends('layout.main')

@section('title', 'SCP - Status UEES')

@section('content')

<style>
    .btn {
        padding: 6px !important;
    }

    .print-visible {
        display: none;
    }

    .icon-tabler-search {
        width: 16px;
        height: 16px;
    }

    @media print {

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
    }

    @page {
        size: landscape;
    }
</style>

<div class="d-flex justify-content-end mb-2 print-hidden">
    <button type="button" class="btn btn-primary " onclick="javascript:window.print();" data-toggle="tooltip" data-placement="top" title="Imprimir tabela de digitação">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
            <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
        </svg>
    </button>
</div>

<div class="header print-visible">
    <img class="img-logo" src="../images/SCP.png" alt="people">
</div>

<div class="pb-0 bg-primary card text-white card_title">
    <h4 class="title_show_carencias">STATUS DE DIGITAÇÃO - 2024</h4>
</div>


<div class="">
    <table class="table table-sm table-hover print-table" id="resumoTable">
        <thead>
            <tr class="bg-dark text-white">
                <th class="text-center" colspan="29">QTD. UNIDADES ESCOLARES POR NTE</th>
            </tr>
            <tr>
                <th>NTE</th>
                <th class="text-center">01</th>
                <th class="text-center">02</th>
                <th class="text-center">03</th>
                <th class="text-center">04</th>
                <th class="text-center">05</th>
                <th class="text-center">06</th>
                <th class="text-center">07</th>
                <th class="text-center">08</th>
                <th class="text-center">09</th>
                <th class="text-center">10</th>
                <th class="text-center">11</th>
                <th class="text-center">12</th>
                <th class="text-center">13</th>
                <th class="text-center">14</th>
                <th class="text-center">15</th>
                <th class="text-center">16</th>
                <th class="text-center">17</th>
                <th class="text-center">18</th>
                <th class="text-center">19</th>
                <th class="text-center">20</th>
                <th class="text-center">21</th>
                <th class="text-center">22</th>
                <th class="text-center">23</th>
                <th class="text-center">24</th>
                <th class="text-center">25</th>
                <th class="text-center">26</th>
                <th class="text-center">27</th>
                <th class="text-center">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="width: 10%;">QTD. SEDE</th>
                <?php
                $total = 0; // Inicializa a variável total
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valor = $nteCountsSedes[$i];
                                                $total += $valor; // Adiciona o valor atual ao total
                                                ?> <td class="text-center">{{ $valor }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $total }}</strong></td>
            </tr>

            <tr>
                <th>QTD. ANEXO</th>
                <?php
                $totalAnexo = 0; // Inicializa a variável total para os anexos
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valorAnexo = $nteCountsAnexos[$i];
                                                $totalAnexo += $valorAnexo; // Adiciona o valor atual ao total dos anexos
                                                ?> <td class="text-center">{{ $valorAnexo }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $totalAnexo }}</strong></td>
            </tr>

            <tr>
                <th>QTD. CEMIT/EMITEC</th>
                <?php
                $totalCemits = 0; // Inicializa a variável total para os CEMIT/EMITEC
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valorCemits = $nteCountsCemits[$i];
                                                $totalCemits += $valorCemits; // Adiciona o valor atual ao total dos CEMIT/EMITEC
                                                ?> <td class="text-center">{{ $valorCemits }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $totalCemits }}</strong></td>
            </tr>

            <tr class="table-primary mb-4">
                <th>TOTAL</th>
                <?php
                $totalTotal = array_sum($nteCountsTotal); // Calcula a soma total dos valores
                ?>
                @foreach ($nteCountsTotal as $count)
                <td class="text-center"><strong>{{ $count }}</strong></td>
                @endforeach
                <td class="text-center"><strong>{{ $totalTotal }}</strong></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <thead>
                <tr class="bg-dark text-white">
                    <th class="text-center" colspan="29">INICIO DE DIGITAÇÃO POR NTE </th>
                </tr>
                <tr class="">
                    <th>NTE</th>
                    <th class="text-center">01</th>
                    <th class="text-center">02</th>
                    <th class="text-center">03</th>
                    <th class="text-center">04</th>
                    <th class="text-center">05</th>
                    <th class="text-center">06</th>
                    <th class="text-center">07</th>
                    <th class="text-center">08</th>
                    <th class="text-center">09</th>
                    <th class="text-center">10</th>
                    <th class="text-center">11</th>
                    <th class="text-center">12</th>
                    <th class="text-center">13</th>
                    <th class="text-center">14</th>
                    <th class="text-center">15</th>
                    <th class="text-center">16</th>
                    <th class="text-center">17</th>
                    <th class="text-center">18</th>
                    <th class="text-center">19</th>
                    <th class="text-center">20</th>
                    <th class="text-center">21</th>
                    <th class="text-center">22</th>
                    <th class="text-center">23</th>
                    <th class="text-center">24</th>
                    <th class="text-center">25</th>
                    <th class="text-center">26</th>
                    <th class="text-center">27</th>
                    <th class="text-center">TOTAL</th>
                </tr>
            </thead>
        <tbody>

            <tr>
                <th>QTD. SEDE</th>
                <?php
                $totalSede = 0;
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valorSede = $nteCountsStartsDigitationSedes[$i];
                                                $totalSede += $valorSede;
                                                ?> <td class="text-center">{{ $valorSede }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $totalSede }}</strong></td>
            </tr>

            <tr>
                <th>QTD. ANEXO</th>
                <?php
                $totalAnexo = 0;
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valorAnexo = $nteCountsStartsDigitationAnexos[$i];
                                                $totalAnexo += $valorAnexo;
                                                ?> <td class="text-center">{{ $valorAnexo }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $totalAnexo }}</strong></td>
            </tr>

            <tr>
                <th>QTD. CEMIT/EMITEC</th>
                <?php
                $totalCemits = 0;
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valorCemits = $nteCountsStartsDigitationCemits[$i];
                                                $totalCemits += $valorCemits;
                                                ?> <td class="text-center">{{ $valorCemits }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $totalCemits }}</strong></td>
            </tr>

            <tr class="table-primary">
                <th>TOTAL</th>
                <?php
                $totalTotalStartsDigitation = array_sum($nteCountsTotalStartsDigitation);
                ?>
                @foreach ($nteCountsTotalStartsDigitation as $count)
                <td class="text-center"><strong>{{ $count }}</strong></td>
                @endforeach
                <td class="text-center"><strong>{{ $totalTotalStartsDigitation }}</strong></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <thead>
                <tr class="bg-dark text-white">
                    <th class="text-center" colspan="29">CONCLUSÃO DE DIGITAÇÃO POR NTE </th>
                </tr>
                <tr class="">
                    <th>NTE</th>
                    <th class="text-center">01</th>
                    <th class="text-center">02</th>
                    <th class="text-center">03</th>
                    <th class="text-center">04</th>
                    <th class="text-center">05</th>
                    <th class="text-center">06</th>
                    <th class="text-center">07</th>
                    <th class="text-center">08</th>
                    <th class="text-center">09</th>
                    <th class="text-center">10</th>
                    <th class="text-center">11</th>
                    <th class="text-center">12</th>
                    <th class="text-center">13</th>
                    <th class="text-center">14</th>
                    <th class="text-center">15</th>
                    <th class="text-center">16</th>
                    <th class="text-center">17</th>
                    <th class="text-center">18</th>
                    <th class="text-center">19</th>
                    <th class="text-center">20</th>
                    <th class="text-center">21</th>
                    <th class="text-center">22</th>
                    <th class="text-center">23</th>
                    <th class="text-center">24</th>
                    <th class="text-center">25</th>
                    <th class="text-center">26</th>
                    <th class="text-center">27</th>
                    <th class="text-center">TOTAL</th>
                </tr>
            </thead>
        <tbody>
            <tr>
                <th>QTD. SEDE</th>
                <?php
                $totalSede = 0;
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valorSede = $nteCountsFinishedDigitationSedes[$i];
                                                $totalSede += $valorSede;
                                                ?> <td class="text-center">{{ $valorSede }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $totalSede }}</strong></td>
            </tr>

            <tr>
                <th>QTD. ANEXO</th>
                <?php
                $totalAnexo = 0;
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valorAnexo = $nteCountsFinishedDigitationAnexos[$i];
                                                $totalAnexo += $valorAnexo;
                                                ?> <td class="text-center">{{ $valorAnexo }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $totalAnexo }}</strong></td>
            </tr>

            <tr>
                <th>QTD. CEMIT/EMITEC</th>
                <?php
                $totalCemits = 0;
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valorCemits = $nteCountsFinishedDigitationCemits[$i];
                                                $totalCemits += $valorCemits;
                                                ?> <td class="text-center">{{ $valorCemits }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $totalCemits }}</strong></td>
            </tr>

            <tr class="table-primary">
                <th>TOTAL</th>
                <?php
                $totalTotalFinishedDigitation = array_sum($nteCountsTotalFinishedDigitation);
                ?>
                @foreach ($nteCountsTotalFinishedDigitation as $count)
                <td class="text-center"><strong>{{ $count }}</strong></td>
                @endforeach
                <td class="text-center"><strong>{{ $totalTotalFinishedDigitation }}</strong></td>
            </tr>

        <tbody>
    </table>
</div>


<hr>
<div class="form_content mb-0 print-hidden">
    <div class="mb-2 print-btn">
        <a id="active_filters" class="mb-2 btn bg-primary text-white" data-toggle="tooltip" data-placement="top" title="Filtros Personalizaveis" onclick="active_filters()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
            </svg>
        </a>
        <a class="mb-2 btn bg-primary text-white" target="_blank" href="/status/digitacao/data" data-toggle="tooltip" data-placement="top" title="Download em Excel">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-download">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M12 17v-6" />
                <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
            </svg>
        </a>
    </div>
    <form id="active_form" class="border shadow bg-light rounded pt-3 pl-3 pr-3" action="/status/digitacao/search" method="post" hidden>
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
                <div class="col-md-6">
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
                        <input value="" name="search_codigo_unidade_escolar" id="search_codigo_unidade_escolar" type="text" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="search_tipologia">TIPOLOGIA</label>
                        <select name="search_tipologia" id="search_tipologia" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="SEDE">SEDE</option>
                            <option value="ANEXO">ANEXO</option>
                            <option value="CEMIT">CEMIT</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="started_digitation">INICIOU</label>
                        <select name="started_digitation" id="started_digitation" class="form-control form-control-sm select2">
                            <option value=""></option>
                            <option value="SIM">SIM</option>
                            <option value="NAO">NAO</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="finish_digitation">CONCLUIU</label>
                        <select name="finish_digitation" id="finish_digitation" class="form-control form-control-sm select2">
                            <option value=""></option>
                            <option value="SIM">SIM</option>
                            <option value="NAO">NAO</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="situacao_homologacao">HOMOLOGADA</label>
                        <select name="situacao_homologacao" id="situacao_homologacao" class="form-control form-control-sm select2">
                            <option value=""></option>
                            <option value="HOMOLOGADA">SIM</option>
                            <option value="PENDENTE">NAO</option>
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
</div>
<div class="table-responsive print-hidden">
    <table id="consultarCarencias" class="table table-sm table-hover table-bordered">
        <thead>
            <tr class="bg-primary text-white border">
                <th class="text-center" colspan="5"></th>
                <th class="text-center" scope="col" colspan="2"><strong>DIGITAÇÃO</strong></th>
                <th class="text-center" scope="col" rowspan="2">HOMOLOGAÇÃO</th>
                <th class="text-center" scope="col" rowspan="2">AÇÃO</th>
            </tr>
            <tr class="bg-primary text-white">
                <td class="text-center" scope="col">NTE</td>
                <td class="text-center" scope="col">MUNICIPIO</td>
                <td class="text-center" scope="col">UNIDADE ESCOLAR</td>
                <td class="text-center" scope="col">COD.</td>
                <td class="text-center" scope="col">TIPO</td>
                <td class="text-center" scope="col">INICIOU</td>
                <td class="text-center" scope="col">CONCLUIU</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($uees as $uee)
            <tr>
                <td class="text-center">{{ $uee->nte }}</td>
                <td>{{ $uee->municipio }}</td>
                <td>{{ $uee->unidade_escolar }}</td>
                <td class="text-center">{{ $uee->cod_unidade }}</td>
                <td class="text-center">{{ $uee->tipo }}</td>
                @if ($uee->typing_started == 'SIM')
                <td class="text-center text-success"><span class="badge badge-success"><i class="fa-solid fa-check-double"></i></span></td>
                @elseif ($uee->typing_started == 'NÃO')
                <td class="text-center text-danger"><span class="badge badge-danger"><i class="fa-solid fa-xmark"></i></span></td>
                @else
                <td></td>
                @endif
                @if ($uee->finished_typing == 'SIM')
                <td class="text-center text-success"><span class="badge badge-success"><i class="fa-solid fa-check-double"></i></span></td>
                @elseif ($uee->finished_typing == 'NÃO')
                <td class="text-center text-danger"><span class="badge badge-danger"><i class="fa-solid fa-xmark"></i></span></td>
                @else
                <td></td>
                @endif
                @if ( $uee->situacao === "PENDENTE")
                <td class="text-center text-warning"><strong>{{ $uee->situacao }}</strong></td>
                @else
                <td class="text-center text-success"><strong>{{ $uee->situacao }}</strong></td>
                @endif
                <td class="text-center">
                    <a title="Detalhar" href="/uees/detail/{{ $uee->id }}"><button id="" class=" btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </button></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection