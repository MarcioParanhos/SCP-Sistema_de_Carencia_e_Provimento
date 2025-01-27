@extends('layouts.main')

@section('title', 'LCPA - Processos')

@section('content')
@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h3 class="page-title">
                    LISTAGEM E CONTROLE DE CARÊNCIA
                </h3>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a title="Exportar para excel" class="btn btn-primary" data-bs-toggle="tooltip" href="">
                        EXCEL
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-export" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                            <path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasStart" role="button" aria-controls="offcanvasStart">
                        FILTRAR
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z"></path>
                        </svg>

                    </a>
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="#" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#createNewRetirementsModal">
                        ADICIONAR
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                    </a>
                    <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#createNewRetirementsModal" aria-label="Create new report">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasStart" aria-labelledby="offcanvasStartLabel">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="offcanvasStartLabel">Filtro Personalizado</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body ">
        <form action="/carencia/filter_carencias" method="post" class="form-filter">
            @csrf
            <div class="form-container">
                <div class="form-section">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <select name="nte_seacrh" id="nte_seacrh" class="form-control form-control-sm select2">
                                    <option value="" selected hidden>Selecione...</option>
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
                                <label for="status_id">NTE</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <select name="municipio_search" id="municipio_search" class="form-control form-control-sm select2">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="process_type_id">MUNICIPIO</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="nte_id" name="nte_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">NTE</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="permanent_position_id" name="permanent_position_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">CARGO PERMANENTE</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="commission_position_id" name="commission_position_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">CARGO COMISSÃO</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="situation_id" name="situation_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">SITUAÇÃO</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="especificitie_id" name="especificitie_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">ESPECIFICIDADES</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="search_doe" name="doe" value="" autocomplete="off">
                                <label for="doe">DOE</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="search_aplication_date" name="aplication_date" value="" autocomplete="off">
                                <label for="aplication_date">DATA DO REQUERIMENTO</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-floating mb-3">
                                <input type="text" id="search_process" name="process" class="form-control" data-mask="000.0000.0000.0000000-00" data-mask-visible="true" autocomplete="off" />
                                <label for="process">Nº do PROCESSO</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button title="Buscar" data-bs-toggle="tooltip" class="btn btn-primary" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                            <path d="M21 21l-6 -6"></path>
                        </svg>
                    </button>
                    <button title="Fechar Filtros" data-bs-toggle="tooltip" class="btn btn-danger" type="button" data-bs-dismiss="offcanvas">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-bar-to-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M14 12l-10 0"></path>
                            <path d="M14 12l-4 4"></path>
                            <path d="M14 12l-4 -4"></path>
                            <path d="M20 4l0 16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body ">
                <div id="table-default" class="table-responsive">
                    <table id="basicTable" class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="bg-primary text-white text-center">NTE</th>
                                <th class="bg-primary text-white text-center">MUNICIPIO</th>
                                <th class="bg-primary text-white text-center">UNIDADE ESCOLAR</th>
                                <th class="bg-primary text-white text-center">COD. UNIDADE</th>
                                <th class="bg-primary text-white text-center">TIPO</th>
                                <th class="bg-primary text-white text-center">HOMOLOGADA</th>
                                <th class="bg-primary text-white text-center">DISCIPLINA</th>
                                <th class="bg-primary text-white text-center">MAT</th>
                                <th class="bg-primary text-white text-center">VESP</th>
                                <th class="bg-primary text-white text-center">NOT</th>
                                <th class="bg-primary text-white text-center">TOTAL</th>
                                <th class="bg-primary text-white text-center">MOTIVO</th>
                                <th class="bg-primary text-white text-center">SERVIDOR</th>
                                <th class="bg-primary text-white text-center">MATRÍCULA</th>
                                <th class="bg-primary text-white text-center">AÇÃO</th>
                            </tr>
                        </thead>
                        <tbody class="table-tbody">
                            @foreach ($filteredCarencias as $filteredCarencia)
                            <tr class="">
                                <td class="text-center subheader">{{ $filteredCarencia->nte }}</td>
                                <td class="text-center subheader">{{ $filteredCarencia->municipio }}</td>
                                <td class="text-center subheader">{{ $filteredCarencia->unidade_escolar }}</td>
                                <td class="text-center subheader">{{ $filteredCarencia->cod_ue }}</td>
                                @if ($filteredCarencia->tipo_carencia === "Real")
                                <td class="text-center subheader"><span class="badge bg-azure">R</span></td>
                                @endif
                                @if ($filteredCarencia->tipo_carencia === "Temp")
                                <td class="text-center subheader"><span class="badge bg-azure">T</span></td>
                                @endif
                                @if ( $filteredCarencia -> hml === "SIM")
                                <td class="subheader text-center"><strong><span class="badge bg-lime">SIM</span></strong></td>
                                @endif
                                @if ( $filteredCarencia -> hml === "NÃO")
                                <td class="subheader text-center"><strong><span class="badge bg-red">NÃO</span></strong></td>
                                @endif
                                <td class="subheader">{{ $filteredCarencia -> disciplina }}</td>
                                <td class="text-center subheader">{{ $filteredCarencia -> matutino }}</td>
                                <td class="text-center subheader">{{ $filteredCarencia -> vespertino }}</td>
                                <td class="text-center subheader">{{ $filteredCarencia -> noturno }}</td>
                                <td class="text-center subheader">{{ $filteredCarencia -> total }}</td>
                                <td class="text-center subheader">{{ $filteredCarencia -> motivo_vaga }}</td>
                                <td class="text-center subheader">{{ $filteredCarencia -> servidor }}</td>
                                <td class="text-center subheader">{{ $filteredCarencia -> cadastro }}</td>
                                <td class="text-center ">
                                    <div class="dropdown">
                                        <a href="#" class="btn-action" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots-vertical" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                                <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                                <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                            </svg>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu">
                                            <a class="dropdown-item" href="">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-dark dropdown-item-icon icon-tabler icon-tabler-eye-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                                    <path d="M11.102 17.957c-3.204 -.307 -5.904 -2.294 -8.102 -5.957c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6a19.5 19.5 0 0 1 -.663 1.032"></path>
                                                    <path d="M15 19l2 2l4 -4"></path>
                                                </svg>
                                                <span class="subheader text-dark">Visualizar</span>
                                            </a>
                                            <a href="" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler text-danger dropdown-item-icon icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M4 7l16 0"></path>
                                                    <path d="M10 11l0 6"></path>
                                                    <path d="M14 11l0 6"></path>
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                </svg>
                                                <span class="subheader text-danger">Excluir</span>
                                            </a>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="createNewRetirementsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ADICIONAR NOVO PROCESSO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <input type="number" class="form-control" id="user_id" name="user_id" value="" hidden>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <select required class="form-select" id="status_id" name="status_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">status</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <select required class="form-select" id="process_type_id" name="process_type_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">tipo</label>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-floating mb-3">
                                <input required type="text" id="process" name="process" class="form-control" data-mask="000.0000.0000.0000000-00" data-mask-visible="true" placeholder="00/00/0000" autocomplete="off" />
                                <label for="process">Nº do PROCESSO</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input required type="date" class="form-control" id="aplication_date" name="aplication_date" value="" autocomplete="off">
                                <label for="aplication_date">DATA DO REQUERIMENTO</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input required type="date" class="form-control" id="first_aplication_date" name="first_aplication_date" value="" autocomplete="off">
                                <label for="first_aplication_date">DATA DA 1ª ENTRADA</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input required type="date" class="form-control" id="last_diligence_date" name="last_diligence_date" value="" autocomplete="off">
                                <label for="last_diligence_date">DATA DA ÚLTIMA DILIGÊNCIA</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input required type="number" class="form-control" id="registration" name="registration" value="" autocomplete="off">
                                <label for="registration">MATRÍCULA</label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-floating mb-3">
                                <input required type="text" class="form-control" id="server_name" name="server_name" value="" autocomplete="off">
                                <label for="server_name">NOME DO SERVIDOR</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-floating mb-3">
                                <select required class="form-select" id="nte_id" name="nte_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">NTE</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <select required class="form-select" id="permanent_position_id" name="permanent_position_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">CARGO PERMANENTE</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <select required class="form-select" id="commission_position_id" name="commission_position_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">CARGO COMISSÃO</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <select required class="form-select" id="situation_id" name="situation_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">SITUAÇÃO</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input required type="date" class="form-control" id="date" name="date" value="" autocomplete="off">
                                <label for="date">DATA</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="especificitie_id" name="especificitie_id" aria-label="Floating label select example">
                                    <option value="" selected hidden>Selecione...</option>

                                </select>
                                <label for="floatingSelect">ESPECIFICIDADES</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="public" name="public" value="" autocomplete="off">
                                <label for="public">PUBLIC</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input required type="date" class="form-control" id="doe" name="doe" value="" autocomplete="off">
                                <label for="doe">DOE</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="instruction_time" name="instruction_time" value="" readonly>
                                <label for="instruction_time">TEMPO INSTRUÇÃO</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-floating">
                                <textarea name="description" id="description" class="form-control" data-bs-toggle="autosize"></textarea>
                                <label class="form-label">INFORMAÇÕES ADICIONAIS</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between pb-3">
                    <button title="Fechar" data-bs-toggle="tooltip" class="btn btn-danger " type="button" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-minimize" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 9l4 0l0 -4"></path>
                            <path d="M3 3l6 6"></path>
                            <path d="M5 15l4 0l0 4"></path>
                            <path d="M3 21l6 -6"></path>
                            <path d="M19 9l-4 0l0 -4"></path>
                            <path d="M15 9l6 -6"></path>
                            <path d="M19 15l-4 0l0 4"></path>
                            <path d="M15 15l6 6"></path>
                        </svg>
                    </button>
                    <button title="Salvar" data-bs-toggle="tooltip" class="btn btn-primary " type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                            <path d="M14 4l0 4l-6 0l0 -4"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scriptis for this page -->
<script src="{{ asset('dist/js/retirements.js') }}" defer></script>

<script>
    const active_retirement = document.getElementById('active_retirement')
    active_retirement.classList.add('active')
</script>

@endsection