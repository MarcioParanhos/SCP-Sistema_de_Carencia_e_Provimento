@extends('layout.main')

@section('title', 'SCP - Encaminhamento')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif
<div class="col-12 grid-margin stretch-card">
    <div class="card shadow rounded">
        <div class="card_title_form">
            <h4 class="card-title">ENCAMINHAMENTO DE SERVIDOR EFETIVO</h4>
            <div class="col-12 col-xl-4">
                <div class="botao_de_tipo d-flex">

                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="forms-sample" action="/provimento/efetivo/create" method="post">
                @csrf
                <input value="{{ Auth::user()->id }}" id="usuario" name="usuario" type="text" class="form-control form-control-sm" hidden>
                <div id="servidor_row">
                    <div class="form-row">
                        <div class=" col-md-2">
                            <div class="display_btn position-relative form-group">
                                <div>
                                    <label for="cpf_cervidor" class="">CPF</label>
                                    <input value="" minlength="8" maxlength="11" name="" id="cpf_cervidor" type="number" class="form-control form-control-sm">
                                </div>
                                <div class="btn_carencia_seacrh">
                                    <button id="cadastro_btn" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchEfetivo()">
                                        <i class="ti-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="nte_efetivo" class="">NTE</label>
                                <input value="" id="nte_efetivo" name="nte_efetivo" type="text" class="form-control form-control-sm" readonly>
                                <input value="" id="servidor_id" name="servidor_id" type="number" class="form-control form-control-sm" hidden>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="servidor_efetivo" class="">nome do servidor</label>
                                <input value="" id="servidor_efetivo" name="" type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <!-- <div class="col-md-1">
                            <div class="form-group">
                                <label for="disciplina_efetivo" class="">DISCIPLINA</label>
                                <input value="" id="disciplina_efetivo" name="" type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div> -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cargo_efetivo" class="">CARGO</label>
                                <input value="" id="cargo_efetivo" name="" type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="position-relative form-group">
                                <label for="regime" class="">regime</label>
                                <input value="40h" name="" required id="" type="text" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div id="buttons" class="buttons">
                            <button id="encaminhamento_btn" type="button" class="btn btn-primary mr-2">ENCAMINHAMENTO</button>
                        </div>
                        <!-- <div id="buttons" class="buttons">
                            <button id="vaga_real_btn" type="button" class="btn btn-primary mr-2" hidden>VAGA REAL</button>
                        </div> -->
                    </div>
                    <div id="hidden_select_unidade_escolar" hidden>
                        <hr>
                        <h5><strong>UNIDADE DE ENCAMINHAMENTO</strong></h5>
                        <br>
                        <div class="form-row">
                            <div class=" col-md-2">
                                <div class="display_btn position-relative form-group">
                                    <div>
                                        <label for="cod_ue" class="">Codigo da UEE <span class="span_required">*</span></label>
                                        <input value="" minlength="8" maxlength="9" name="" id="cod_ue" type="number" class="form-control form-control-sm" required>
                                        <input value="" id="unidade_id" name="unidade_id" type="number" class="form-control form-control-sm" hidden>
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
                                    <label for="nte" class="">NTE</label>
                                    <input value="" id="nte" name="" type="text" class="form-control form-control-sm" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="municipio" class="">Município</label>
                                    <input value="" id="municipio" name="" type="text" class="form-control form-control-sm" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="unidade_escolar" class="">Nome da Unidade Escolar</label>
                                    <input value="" name="" required id="unidade_escolar" type="text" class="form-control form-control-sm" readonly required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <div class=" col-md-2">
                                <div class="display_btn position-relative form-group">
                                    <div>
                                        <label for="cadastro" class="">Matrícula</label>
                                        <input value="" minlength="8" maxlength="11" name="cadastro" id="cadastro" type="cadastro" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="btn_carencia_seacrh">
                                        <button id="cadastro_btn" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchServidor()">
                                            <i class="ti-search"></i>
                                        </button>
                                        <button title="SEGUNDO SERVIDOR" id="cadastro_segundo_servidor_btn" class="ml-2 position-relative btn_search_carencia btn btn-sm btn-primary" type="button">
                                            <i id="icon_segundo_servidor" class="fas fa-user-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="servidor" class="">Nome do servidor subistituido</label>
                                    <input value="" id="servidor" name="servidor" type="text" class="form-control form-control-sm" readonly>
                                    <input value="" id="servidor_subistituido" name="servidor_subistituido" type="number" class="form-control form-control-sm" hidden>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="vinculo" class="">Vinculo</label>
                                    <input value="" id="vinculo" name="vinculo" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="position-relative form-group">
                                    <label for="regime" class="">Regime</label>
                                    <input value="" name="regime" required id="regime" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <div id="segundo_servidor" class="form-row" hidden>
                            <div class=" col-md-2">
                                <div class="display_btn position-relative form-group">
                                    <div>
                                        <label for="cadastro_segundo_servidor" class="">Matrícula 2º SERVIDOR</label>
                                        <input value="" minlength="8" maxlength="11" name="" id="cadastro_segundo_servidor" type="number" class="form-control form-control-sm">
                                    </div>
                                    <div class="btn_carencia_seacrh">
                                        <button id="cadastro_btn" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchSegundoServidor()">
                                            <i class="ti-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="segundo_servidor_name" class="">Nome do segundo servidor subistituido</label>
                                    <input value="" id="segundo_servidor_name" name="" type="text" class="form-control form-control-sm" readonly>
                                    <input value="" id="id_segundo_servidor_subistituido" name="id_segundo_servidor_subistituido" type="number" class="form-control form-control-sm" hidden readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="vinculo_segundo_servidor" class="">Vinculo do segundo servidor</label>
                                    <input value="" id="vinculo_segundo_servidor" name="" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="position-relative form-group">
                                    <label for="regime_segundo_servidor" class="">Regime</label>
                                    <input value="" name="" required id="regime_segundo_servidor" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div id="data_encaminhamento_row" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_encaminhamento" class="">Data de encaminhamento</label>
                                    <input value="data_encaminhamento" name="data_encaminhamento" id="data_encaminhamento" type="date" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div id="data_assuncao_row" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_assuncao" class="">Assunção</label>
                                    <input value="data_assuncao" name="data_assuncao" id="data_assuncao" type="date" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div id="data_assuncao_row" class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label for="obs">Observações<i class="ti-pencil"></i></label>
                                    <textarea name="obs" class="form-control" id="obs" rows="4" maxlength="120"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="hidden_select_unidade_escolar_vaga_real" hidden>
                        <hr>
                        <h5><strong>UNIDADE DA VAGA REAL</strong></h5>
                        <br>
                        <div class="form-row">
                            <div class=" col-md-2">
                                <div class="display_btn position-relative form-group">
                                    <div>
                                        <label for="cod_ue" class="">Codigo da UEE <span class="span_required">*</span></label>
                                        <input value="" minlength="8" maxlength="9" name="" id="cod_ue_provimento" type="number" class="form-control form-control-sm">
                                    </div>
                                    <div class="btn_carencia_seacrh">
                                        <button id="btn-cadastro" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="buscarUeeParaVagaReal()">
                                            <i class="ti-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="nte" class="">NTE</label>
                                    <input value="" id="nte_provimento_vaga_real" name="" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="municipio" class="">Município</label>
                                    <input value="" id="municipio_provimento_vaga_real" name="" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="unidade_escolar" class="">Nome da Unidade Escolar</label>
                                    <input value="" name="" required id="unidade_escolar_provimento_vaga_real" type="text" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div id="data_encaminhamento_row" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_encaminhamento" class="">data de encaminhamento</label>
                                    <input value="data_encaminhamento" name="" id="data_encaminhamento" type="date" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div id="data_assuncao_row" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_assuncao" class="">assunção</label>
                                    <input value="data_assuncao" name="" id="data_assuncao" type="date" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div id="data_assuncao_row" class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label for="obs">Observações<i class="ti-pencil"></i></label>
                                    <textarea name="" class="form-control" id="obs" rows="4" maxlength="120"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="buttons" class="buttons d-flex" style="position: relative;">
                    <button id="btn_submit" type="submit" class="btn btn-primary mr-2" hidden>CADASTRAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const btn_submit = document.getElementById('btn_submit')
    const encaminhamento_btn = document.getElementById('encaminhamento_btn')
    const vaga_real_btn = document.getElementById('vaga_real_btn')
    const hidden_select_unidade_escolar_vaga_real = document.getElementById("hidden_select_unidade_escolar_vaga_real");
    const hidden_select_unidade_escolar = document.getElementById("hidden_select_unidade_escolar");
    const cadastro_segundo_servidor_btn = document.getElementById('cadastro_segundo_servidor_btn');
    const segundo_servidor = document.getElementById('segundo_servidor');
    const icon_segundo_servidor = document.getElementById('icon_segundo_servidor')



    btn_submit.addEventListener('click', function(event) {
        const nte = document.getElementById('nte')
        const municipio = document.getElementById('municipio')
        const vinculo = document.getElementById('vinculo')
        const regime = document.getElementById('regime')

        if ((nte.value == "") || (municipio.value == "")) {
            event.preventDefault();
            Swal.fire({
                icon: "warning",
                title: "Atenção!",
                text: "Nenhuma unidade escolar foi selecionada!",
            });
        } else if ((vinculo.value == "") || (regime.value == "")) {
            event.preventDefault();
            Swal.fire({
                icon: "warning",
                title: "Atenção!",
                text: "Nenhum servidor foi selecionado!",
            });
        } else {

        }

    });

    cadastro_segundo_servidor_btn.addEventListener('click', () => {
        if (servidor_subistituido.value == '') {

            Swal.fire({
                icon: "warning",
                title: "Atenção!",
                text: "Para liberar o segundo servidor é preciso ter o primeiro servidor selacionado",
            });

        } else {
            if (cadastro_segundo_servidor_btn.classList.contains('btn-primary')) {
                cadastro_segundo_servidor_btn.classList.remove('btn-primary');
                cadastro_segundo_servidor_btn.classList.add('btn-danger');
                icon_segundo_servidor.classList.remove('fa-solid', 'fa-user-plus');
                icon_segundo_servidor.classList.add('fa-solid', 'fa-user-xmark');
                segundo_servidor.hidden = false;

            } else {
                const segundo_servidor_name = document.getElementById(
                    "segundo_servidor_name"
                );
                const vinculo_segundo_servidor = document.getElementById(
                    "vinculo_segundo_servidor"
                );
                const regime_segundo_servidor = document.getElementById(
                    "regime_segundo_servidor"
                );
                const id_segundo_servidor_subistituido = document.getElementById(
                    "id_segundo_servidor_subistituido"
                );
                const cadastro_segundo_servidor = document.getElementById(
                    "cadastro_segundo_servidor"
                );
                cadastro_segundo_servidor_btn.classList.remove('btn-danger');
                cadastro_segundo_servidor_btn.classList.add('btn-primary');
                icon_segundo_servidor.classList.add('fa-solid', 'fa-user-plus');
                icon_segundo_servidor.classList.remove('fa-solid', 'fa-user-xmark');
                segundo_servidor.hidden = true
                segundo_servidor_name.value = ''
                vinculo_segundo_servidor.value = ''
                regime_segundo_servidor.value = ''
                id_segundo_servidor_subistituido.value = ''
                cadastro_segundo_servidor.value = ''
            }
        }
    })

    encaminhamento_btn.addEventListener('click', () => {
        hidden_select_unidade_escolar.hidden = !hidden_select_unidade_escolar.hidden;
        encaminhamento_btn.classList.toggle('btn-primary');
        encaminhamento_btn.classList.toggle('btn-info');

        if ((hidden_select_unidade_escolar_vaga_real.hidden == true) && (hidden_select_unidade_escolar.hidden == false)) {
            btn_submit.hidden = false
        } else if ((hidden_select_unidade_escolar_vaga_real.hidden == false) && (hidden_select_unidade_escolar.hidden == true)) {
            btn_submit.hidden = false
        } else if ((hidden_select_unidade_escolar_vaga_real.hidden == true) && (hidden_select_unidade_escolar.hidden == true)) {
            btn_submit.hidden = true
        } else if ((hidden_select_unidade_escolar_vaga_real.hidden == false) && (hidden_select_unidade_escolar.hidden == false)) {
            btn_submit.hidden = false
        }
    })

    vaga_real_btn.addEventListener('click', () => {
        hidden_select_unidade_escolar_vaga_real.hidden = !hidden_select_unidade_escolar_vaga_real.hidden;
        vaga_real_btn.classList.toggle('btn-primary');
        vaga_real_btn.classList.toggle('btn-info');
        if ((hidden_select_unidade_escolar_vaga_real.hidden == true) && (hidden_select_unidade_escolar.hidden == true)) {
            btn_submit.hidden = true
        } else if ((hidden_select_unidade_escolar_vaga_real.hidden == true) && (hidden_select_unidade_escolar.hidden == false)) {
            btn_submit.hidden = false
        } else if ((hidden_select_unidade_escolar_vaga_real.hidden == false) && (hidden_select_unidade_escolar.hidden == true)) {
            btn_submit.hidden = false
        }
    })
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const session_message = document.getElementById("session_message");

        if (session_message) {
            if (session_message.value === "error") {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Já existe um registro de servidor associado a este número de CPF na unidade escolar selecionada.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Encaminhamento adicionado com sucesso!',
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

@endsection