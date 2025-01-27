@extends('layout.main')

@section('title', 'SCP - Carência')

@section('content')
<!-- <h2 class="text-center">EXCEL EM CONSTRUÇÃO <lord-icon src="https://cdn.lordicon.com/sbiheqdr.json" trigger="loop" delay="1" style="width:60px;height:60px">
    </lord-icon></h2> -->
@if(session('msg'))
<div class="col-12">
    <div class="alert text-center text-white bg-success container alert-success alert-dismissible fade show" role="alert" style="min-width: 100%">
        <strong>{{ session('msg')}} <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" delay="1" colors="primary:#ffffff,secondary:#ffffff" style="width:36px;height:36px">
            </lord-icon></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif
<div class="card">
    <div class="mb-0 shadow bg-primary text-white card_title">
        <h4 class=" title_show_carencias">CARÊNCIA (Apoio Pedagógico)</h4>
    </div>
    <div class="pr-4 pl-4 pt-4">
        <form class="mb-4" action="{{ route('carencia.ApoioPedagogicoByForm') }}" method="post" style="width: 100%;">
            @csrf
            <div class="form-row">
                <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="nte_ap">NTE</label>
                        <select name="nte_ap" id="nte_ap" class="form-control form-control-sm select2">
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
                        <label class="control-label" for="municipio_ap">MUNICIPIO</label>
                        <select name="municipio_ap" id="municipio_ap" class="form-control form-control-sm select2">

                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="uee_ap">UNIDADE ESCOLAR</label>
                        <select name="uee_ap" id="uee_ap" class="form-control form-control-sm select2">
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label for="cod_ue_ap" class="">COD. UE</label>
                        <input value="" name="cod_ue_ap" id="cod_ue" type="text" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-3">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="profissional_search">profissional</label>
                        <select name="profissional_search" id="profissional_search" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="BRAILISTA">BRAILISTA</option>
                            <option value="INTERPRETE DE LIBRAS">INTERPRETE DE LIBRAS</option>
                            <option value="CUIDADOR">CUIDADOR</option>
                            <option value="TECNICO DE AEE">TECNICO DE AEE</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="regime_search">regime</label>
                        <select name="regime_search" id="regime_search" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="20">20h</option>
                            <option value="40">40h</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="turno_search">turno</label>
                        <select name="turno_search" id="turno_search" class="form-control form-control-sm select2">
                            <option></option>
                            <option value="MATUTINO">MATUTINO</option>
                            <option value="VESPERTINO">VESPERTINO</option>
                            <option value="NOTURNO">NOTURNO</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">BUSCAR</button>
        </form>
    </div>
    <div class="shadow card_info">
        <div class="print-btn">
            @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
            <a class="mb-2 btn bg-primary text-white" data-toggle="modal" data-target="#ModalAddNewVacancyPedagogical"><i class="ti-plus"></i> NOVA VAGA</a>
            @endif
            <a class="mb-2 btn bg-primary text-white" target="_blank" href="{{ route('carencia.apoioPedagogico.relatorio') }}"><i class="ti-printer"></i> IMPRIMIR</a>
            <a class="mb-2 btn bg-primary text-white" href="{{ route('carencia.apoioPedagogico.excel') }}"><i class="ti-download"></i> EXCEL</a>
        </div>
        <h5>RELAÇÃO DE CARÊNCIA DE APOIO PEDAGÓGICO</h5>
        <div class="p-1 table-responsive">
            <table id="consultarCarencias" class="table table-sm table-hover table-bordered">
                <thead class="bg-primary text-white">
                    <tr class="text-center">
                        <th scope="col">NTE</th>
                        <th scope="col">MUNICIPIO</th>
                        <th scope="col">UNIDADE ESCOLAR</th>
                        <th scope="col">COD. UE</th>
                        <th scope="col">PROFISSIONAL</th>
                        <th scope="col">REGIME</th>
                        <th scope="col">TURNO</th>
                        <th scope="col">QUANTIDADE</th>
                        @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                        <th scope="col">AÇÔES</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vagas_apoio_pedagocigo as $vaga)
                    <tr>
                        @if ($vaga->nte >= 10)
                        <td class="text-center">{{ $vaga->nte }}</td>
                        @endif
                        @if ($vaga->nte < 10) <td class="text-center">0{{ $vaga->nte }}</td>
                            @endif
                            <td class="text-center">{{ $vaga->municipio }}</td>
                            <td class="">{{ $vaga->unidade_escolar }}</td>
                            <td class="text-center">{{ $vaga->cod_unidade }}</td>
                            <td class="text-center">{{ $vaga->profissional }}</td>
                            <td class="text-center">{{ $vaga->regime }}</td>
                            <td class="text-center">{{ $vaga->turno }}</td>
                            <td class="text-center">{{ $vaga->qtd }}</td>
                            @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                            <td class="text-center">
                                <a title="Excluir" id="" onclick="destroyVacancyPedagogical('{{ $vaga -> id }}')" class="btn-show-carência btn btn-sm btn-danger"><i class="ti-trash"></i></a>
                            </td>
                            @endif
                    </tr>
                    @endforeach
                    <tr class="text-white bg-primary">
                        <td class="border-right-0 text-center"><strong>TOTAL</strong></td>
                        <td class="border-right-0 text-center"></td>
                        <td class="border-right-0 text-center"></td>
                        <td class="border-right-0 text-center"></td>
                        <td class="border-right-0 text-center"></td>
                        <td class="border-right-0 text-center"></td>
                        <td class="border-right-0 text-center"></td>
                        <td class="border-right-0 text-center"><strong>{{ $sumVacancyPedagogical }}</strong></td>
                        @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                        <td class="text-center"></td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal ADD Vacancy-->
<div class="modal fade" id="ModalAddNewVacancyPedagogical" tabindex="-1" role="dialog" aria-labelledby="TituloModalAddNewVacancyPedagogical" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalAddNewVacancyPedagogical">NOVA CARÊNCIA DE APOIO PEDAGÓGICO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="mb-4" action="/addCarenciaPedagogica" method="post" style="width: 100%;">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-1">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="nte_seacrh">NTE</label>
                                <select name="nte_seacrh" id="nte_seacrh" class="form-control form-control-sm select2" required>
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
                                <select name="municipio_search" id="municipio_search" class="form-control form-control-sm select2" required>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="search_uee">NOME DA UNIDADE ESCOLAR</label>
                                <select name="search_uee" id="search_uee" class="form-control form-control-sm select2" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="cod_ue" class="">COD. UE</label>
                                <input value="" name="cod_ue" id="cod_ue" type="text" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="profissional">profissional</label>
                                <select name="profissional" id="profissional" class="form-control form-control-sm select2" required>
                                    <option></option>
                                    <option value="BRAILISTA">BRAILISTA</option>
                                    <option value="INTERPRETE DE LIBRAS">INTERPRETE DE LIBRAS</option>
                                    <option value="CUIDADOR">CUIDADOR</option>
                                    <option value="TECNICO DE AEE">TECNICO DE AEE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="regime">regime</label>
                                <select name="regime" id="regime" class="form-control form-control-sm select2" required>
                                    <option></option>
                                    <option value="20">20h</option>
                                    <option value="40">40h</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="turno">turno</label>
                                <select name="turno" id="turno" class="form-control form-control-sm select2" required>
                                    <option></option>
                                    <option value="MATUTINO">MATUTINO</option>
                                    <option value="VESPERTINO">VESPERTINO</option>
                                    <option value="NOTURNO">NOTURNO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label for="qtd" class="">quantidade</label>
                                <input value="" name="qtd" id="qtd" type="number" min="0" class="text-center form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>
                    <button id="btn_submit_carencia" type="submit" class="btn btn-primary mr-2">CADASTRAR</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection