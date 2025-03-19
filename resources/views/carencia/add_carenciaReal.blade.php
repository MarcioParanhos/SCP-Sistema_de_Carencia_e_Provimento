@extends('layout.main')

@section('title', 'SCP - Carência')

@section('content')

<style>
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
</style>

<div class="col-12 grid-margin stretch-card">
    <div class="card shadow rounded">
        <div class="card_title_form">
            <h4 class="card-title">INCLUIR CARÊNCIA - REAL ( <span id="type"></span> ) </h4>
            <div class="col-12 col-xl-4">
                <div class="botao_de_tipo d-flex">
                    <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                        <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            TIPO DE CARÊNCIA
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                            <a class=" dropdown-item" onclick="addTipoCarencia(1)">EDUCAÇÃO BÁSICA</a>
                            <a class="dropdown-item" onclick="addTipoCarencia(2)">PROFISSIONALIZANTE</a>
                            <a class="dropdown-item" onclick="addTipoCarencia(3)">EDUCAÇÃO ESPECIAL</a>
                            <a class="dropdown-item" onclick="addTipoCarencia(4)">EMITEC</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h4 id="title_situacao" class="badge d-flex justify-content-between align-items-center"></h4>
        <div class="card-body">
            <form class="forms-sample" id="InsertForm">
                @csrf
                <input value="" id="tipo_vaga" name="tipo_vaga" type="text" class="form-control form-control-sm" hidden>
                <input value="Real" id="tipo_carencia" name="tipo_carencia" type="text" class="form-control form-control-sm" hidden>
                <input value="{{ Auth::user()->name }}" id="usuario" name="usuario" type="text" class="form-control form-control-sm" hidden>
                <input value="{{ Auth::user()->id }}" id="user_id" name="user_id" type="text" class="form-control form-control-sm" hidden>
                <div class="form-row">
                    <div class=" col-md-2">
                        <div class="display_btn position-relative form-group">
                            <div>
                                <label for="cod_ue" class="">Codigo da UEE <span class="span_required">*</span></label>
                                <input value="" minlength="8" maxlength="9" name="cod_ue" id="cod_ue" type="number" class="form-control form-control-sm">
                            </div>
                            <div class="btn_carencia_seacrh">
                                <button id="btn-cadastro" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="addNewCarencia()" disabled required>
                                    <i class="ti-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="nte" class="">NTE</label>
                            <input value="" id="nte" name="nte" type="text" class="form-control form-control-sm" readonly required>
                            <input value="" id="unidade_id" name="unidade_id" type="text" class="form-control form-control-sm" readonly required hidden>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="municipio" class="">Município</label>
                            <input value="" id="municipio" name="municipio" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="unidade_escolar" class="">Nome da Unidade Escolar</label>
                            <input value="" name="unidade_escolar" required id="unidade_escolar" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-4 hidden" id="curso_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="curso">Curso <span class="span_required">*</span></label>
                            <select name="curso" id="curso" class="form-control form-control-sm select2" required>
                                <option></option>
                                @foreach ($eixo_cursos as $eixo_cursos)
                                <option>{{$eixo_cursos -> curso}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 hidden" id="eixo_row">
                        <div class="form-group_disciplina">
                            <label for="unidade_escolar" class="">Eixo <span class="span_required">*</span></label>
                            <input value="" name="eixo" required id="eixo" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="col-md-4 hidden" id="disciplina_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="disciplina">Disciplina <span class="span_required">*</span></label>
                            <select name="disciplina" id="disciplina" class="form-control select2" required>
                                <option></option>
                                <option value="FUNDAMENTAL 1">FUNDAMENTAL 1</option>
                                @foreach ($disciplinas as $disciplinas)
                                <option>{{$disciplinas -> nome}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 hidden" id="area_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="area">ÁREA <span class="span_required">*</span></label>
                            <select name="area" id="area" class="form-control select2">
                                <option></option>
                                @foreach ($areas as $area)
                                <option value="{{ $area->nome }}">{{ $area->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 hidden" id="disciplina_especial_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="disciplina_especial">COMPONENTE ESPECIAL</label>
                            <select name="disciplina_especial" id="disciplina_especial" class="form-control select2">
                                <option></option>
                                @foreach ($componentes as $componente)
                                <option value="{{ $componente->nome }}">{{ $componente->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 hidden" id="motivo_vaga_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="motivo_vaga">Motivo da vaga <span class="span_required">*</span></label>
                            <select name="motivo_vaga" id="motivo_vaga" class="form-control select2" required>
                                <option></option>
                                @foreach ($motivo_vagas as $motivo_vaga)
                                <option value="{{$motivo_vaga -> motivo}}">{{$motivo_vaga -> motivo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 hidden" id="inicio_vaga_row">
                        <div class="form-group_disciplina">
                            <label for="inicio_vaga" class="">Inicio da vaga <span class="span_required">*</span></label>
                            <input value="" id="inicio_vaga" name="inicio_vaga" type="date" class="form-control form-control-sm" required>
                        </div>
                    </div>
                </div>
                <div id="servidor_row" class="hidden">
                    <div class="form-row">
                        <div id="matricula-row" class="col-md-2">
                            <div  class="display_btn position-relative form-group">
                                <div>
                                    <label for="cadastro" class="">Matrícula / CPF</label>
                                    <input value="" minlength="8" maxlength="11" name="cadastro" id="cadastro" type="cadastro" class="form-control form-control-sm">
                                </div>
                                <div class="btn_carencia_seacrh">
                                    <button id="cadastro_btn" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchServidor()" disabled>
                                        <i class="ti-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="servidor" class="">nome do servidor</label>
                                <input value="" id="servidor" name="servidor" type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="vinculo" class="">vinculo</label>
                                <input value="" id="vinculo" name="vinculo" type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="position-relative form-group">
                                <label for="regime" class="">regime</label>
                                <input value="" name="regime" required id="regime" type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="matutino" class="">MAT</label>
                                <input value="0" id="matutino" name="matutino" type="number" class="text-center form-control form-control-sm" required min="0">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="vespertino" class="">VESP</label>
                                <input value="0" id="vespertino" name="vespertino" type="number" class="text-center form-control form-control-sm" required min="0">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="noturno" class="">NOT</label>
                                <input value="0" id="noturno" name="noturno" type="number" class="text-center form-control form-control-sm" required min="0">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="total" class="">TOTAL</label>
                                <input value="" id="total" name="total" type="text" class="text-center form-control form-control-sm" readonly>
                            </div>
                        </div>

                    </div>
                    <div id="buttons" class="buttons">
                        <!-- <button id="btn_submit_carencia" type="submit" class="btn btn-primary mr-2" >CADASTRAR</button> -->
                        <!-- <button id="btn_submit_carencia" type="submit">
                            <div class="svg-wrapper-1">
                                <div class="svg-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                </div>
                            </div>
                            <span>SALVAR</span>
                        </button> -->
                        <button id="btn_submit_carencia" class="button" type="submit">
                            <span class="button__text">ADICIONAR</span>
                            <span class="button__icon"><svg class="svg" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="12" x2="12" y1="5" y2="19"></line>
                                    <line x1="5" x2="19" y1="12" y2="12"></line>
                                </svg></span>
                        </button>
                    </div>
                </div>
            </form>
            <div id="table_form" hidden>
                <div class="mt-4 mb-2 card_title_form_table">
                    <h5 class="card-title">VAGAS DETALHADAS PARA ESTA UNIDADE</h5>
                </div>
                <div class="table-responsive">
                    <table id="table_carencia" class="table-carencia table table-sm table-hover table-bordered">
                        <caption class="mt-2">Carências da UEE selecionada</caption>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        Swal.fire({
            icon: 'info',
            title: '',
            text: 'Selecione um tipo de carência para cadastrar!',
        })
    }
</script>


@endsection