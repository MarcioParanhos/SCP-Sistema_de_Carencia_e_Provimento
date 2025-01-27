@extends('layout.main')

@section('title', 'SCP - Manutenções')

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

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif
<div class="col-12 grid-margin stretch-card">
    <div class="card shadow rounded">
        <div class="card_title_form">
            <h4 class="card-title">INCLUIR NOVA MANUTENÇÃO - <span class="text-uppercase">{{ Auth::user()->name }}</span></h4>
        </div>
        <div class="card-body">
            <form class="forms-sample" id="" action="/regularizacao_funcional/create" method="POST">
                @csrf
                <input value="{{ Auth::user()->name }}" id="create_user" name="create_user" type="text" class="form-control form-control-sm" hidden>
               <h6>UNIDADE ESCOLAR REALIZADA A MANUTENÇÃO</h6>
                <hr>
                <div class="form-row">
                    <div class=" col-md-2">
                        <div class="display_btn position-relative form-group">
                            <div>
                                <label for="cod_ue" class="">COD. Unidade <span class="span_required">*</span></label>
                                <input value="" minlength="8" maxlength="9" name="cod_ue_origem" id="cod_ue" type="number" class="form-control form-control-sm">
                            </div>
                            <div class="btn_carencia_seacrh">
                                <button id="btn-cadastro" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="addNewCarencia()" required>
                                    <i class="ti-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="nte" class="">UO ORIGEM</label>
                            <input value="" id="uo_origem" name="uo_origem" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="nte" class="">NTE</label>
                            <input value="" id="nte" name="nte" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="municipio" class="">Município</label>
                            <input value="" id="municipio" name="municipio" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="position-relative form-group">
                            <label for="unidade_escolar" class="">Nome da Unidade Escolar</label>
                            <input value="" name="unidade_escolar" required id="unidade_escolar" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                </div>
                <h6>INFORMAÇÕES DA MANUTENÇÃO</h6>
                <hr>
                <!-- <div class="form-row">
                    <div class=" col-md-2">
                        <div class="display_btn position-relative form-group">
                            <div>
                                <label for="cadastro" class="">Matrícula / cpf</label>
                                <input value="" minlength="8" maxlength="11" name="cadastro" id="cadastro" type="cadastro" class="form-control form-control-sm" required>
                            </div>
                            <div class="btn_carencia_seacrh">
                                <button id="cadastro_btn" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchServidor()">
                                    <i class="ti-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="servidor" class="">nome do servidor</label>
                            <input value="" id="servidor" name="servidor" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="vinculo" class="">vinculo</label>
                            <input value="" id="vinculo" name="vinculo" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="position-relative form-group">
                            <label for="regime" class="">regime</label>
                            <input value="" name="regime" required id="regime" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="form-row">
                    <div class=" col-md-2">
                        <div class="display_btn position-relative form-group">
                            <div>
                                <label for="cod_ue" class="">COD. Unidade de Destino<span class="span_required">*</span></label>
                                <input value="" minlength="8" maxlength="9" name="cod_ue_destino" id="cod_ue_destino" type="number" class="form-control form-control-sm">
                            </div>
                            <div class="btn_carencia_seacrh">
                                <button id="btn-cadastro" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchUnidadeEscolar()" required>
                                    <i class="ti-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="nte" class="">UO DESTINO</label>
                            <input value="" id="uo_destino" name="uo_destino" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="nte" class="">NTE de Destino</label>
                            <input value="" id="nte_destino" name="" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="municipio" class="">Município de Destino</label>
                            <input value="" id="municipio_destino" name="" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="position-relative form-group">
                            <label for="unidade_escolar" class="">Nome da Unidade Escolar de Destino</label>
                            <input value="" name="" required id="unidade_escolar_destino" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                </div> -->
                <div class="form-row">
                    <div class="col-md-3" id="motivo_vaga_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="tipo_regularizacao">TIPO DE MANUTENÇÃO</label>
                            <select name="tipo_regularizacao" id="tipo_regularizacao" class="form-control select2" required>
                                <option value=""></option>
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
                    <div class="col-md-2" id="retificacao_row" hidden>
                        <div class="form-group_disciplina">
                            <label class="control-label" for="tipo_retificacao">TIPO DE RETIFICAÇÃO</label>
                            <select name="tipo_retificacao" id="tipo_retificacao" class="form-control select2" >
                                <option value=""></option>
                                <option value="REMOÇÃO">REMOÇÃO</option>
                                <option value="RELOTAÇÃO">RELOTAÇÃO</option>
                                <option value="ABRIR COMPLEMENTAÇÃO">COMPLEMENTAÇÃO</option>
                                <option value="MUDAR LOCAL DE INGRESSO">LOCAL DE INGRESSO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" id="tornar_sem_efeito_row" hidden>
                        <div class="form-group_disciplina">
                            <label class="control-label" for="tipo_tornar_sem_efeito">TIPO DE TORNAR SEM EFEITO</label>
                            <select name="tipo_tornar_sem_efeito" id="tipo_tornar_sem_efeito" class="form-control select2" >
                                <option value=""></option>
                                <option value="REMOÇÃO">REMOÇÃO</option>
                                <option value="RELOTAÇÃO">RELOTAÇÃO</option>
                                <option value="ABRIR COMPLEMENTAÇÃO">COMPLEMENTAÇÃO</option>
                                <option value="MUDAR LOCAL DE INGRESSO">LOCAL DE INGRESSO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" id="inicio_vaga_row">
                        <div class="form-group_disciplina">
                            <label for="inicio_vaga" class="">DATA<span class="span_required">*</span></label>
                            <input value="" id="data" name="data" type="date" class="form-control form-control-sm" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="buttons" class="buttons">
                    <!-- <button id="btn_submit_carencia" type="submit" class="btn btn-primary mr-2" >CADASTRAR</button> -->
                    <button id="" class="button" type="submit">
                        <span class="button__text">CADASTRAR</span>
                        <span class="button__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
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
                    text: 'Regularização adicionada com sucesso!',
                })
            } else if (session_message.value === "delete_success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Disciplina excluida com sucesso!',
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
        // Adicione um ouvinte de evento para o evento de mudança do Select2
      

    });
</script>