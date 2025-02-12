@extends('layout.main')

@section('title', 'SCP - Encaminhamento')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif
<div class="col-12 grid-margin stretch-card">
    <div class="card shadow rounded">
        <div class="card_title_form">
            <h4 class="card-title">ENCAMINHAMENTO DE SERVIDOR</h4>
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
                            <button style="padding: 6px !important; border-radius: 5px !important" id="encaminhamento_btn" type="button" class="btn btn-sm btn-primary p-auto subheader">ENCAMINHAR
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-ipad-horizontal-share">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12.5 20h-7.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7" />
                                    <path d="M9 17h3" />
                                    <path d="M16 22l5 -5" />
                                    <path d="M21 21.5v-4.5h-4.5" />
                                </svg></button>
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
                                        <label for="cod_ue" class="">Codigo SAP da UEE <span class="span_required">*</span></label>
                                        <input value="" minlength="8" maxlength="9" name="" id="cod_ue" type="number" class="form-control form-control-sm" required>
                                        <input value="" id="unidade_id" name="unidade_id" type="number" class="form-control form-control-sm" hidden>
                                    </div>
                                    <div class="btn_carencia_seacrh">
                                        <button id="btn-cadastro" class="position-relative btn_search_carencia btn btn-sm btn-primary" type="button" onclick="searchUnidadeForCodSap()" required>
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
                                        <label for="cadastro" class="">Matrícula
                                            <a style="border-radius: 8px;" class="mr-2 bg-primary text-white p-1" data-toggle="modal" data-target="#modalInfo"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
                                                    <path d="M12 19l0 .01" />
                                                </svg>
                                            </a>
                                        </label>
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
                                    <label for="data_encaminhamento" class="">Data de encaminhamento <span class="span_required">*</span></label>
                                    <input value="data_encaminhamento" name="data_encaminhamento" id="data_encaminhamento" type="date" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div id="data_assuncao_row" class="col-md-2">
                                <div class="form-group_disciplina">
                                    <label for="data_assuncao" class="">Assunção</label>
                                    <input value="data_assuncao" name="data_assuncao" id="data_assuncao" type="date" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-row d-flex justify-content-end">
                            <button type="button" class="btn btn-primary mt-2 subheader"  onclick="adicionarDisciplina()">
                                <i class="ti-plus"></i> Adicionar Disciplina
                            </button>
                        </div>
                        <div class="form-row">
                            <div id="disciplinas-container">
                                <div class="form-row disciplina-row">
                                    <div class="col-md-6">
                                        <div class="form-group_disciplina">
                                            <label class="control-label">Disciplina</label>
                                            <input value="" name="disciplinas[]"  id="" type="text" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group_disciplina">
                                            <label for="mat">MAT</label>
                                            <input type="text" name="matutino[]" class="form-control form-control-sm" >
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group_disciplina">
                                            <label for="vesp">VESP</label>
                                            <input type="text" name="vespertino[]" class="form-control form-control-sm" >
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group_disciplina">
                                            <label for="not">NOT</label>
                                            <input type="text" name="noturno[]" class="form-control form-control-sm" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
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
                            <div class="form-row">
                                <div id="" class="col-md-2">
                                    <div class="form-group_disciplina">
                                        <label for="data_assuncao" class="">assunção</label>
                                        <input value="data_assuncao" name="" id="data_assuncao" type="date" class="form-control form-control-sm">
                                    </div>
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
                    <button id="btn_submit" type="submit" class="btn btn-primary mr-2" data-toggle="tooltip" data-placement="top" title="Cadastrar novo encaminhamento" hidden>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="TitulomodalInfo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TitulomodalInfo">MATRÍCULAS FUNCIONAIS</h5>
            </div>
            <div class="modal-body justify-content-start p-4">
                <div class="">
                    <h5>Legenda:</h5>
                    <div class="pt-2">
                        <div>
                            <span class="subheader">99999999 - VAGA DE COORDENAÇÃO PEDAGOGICA</span>
                        </div>
                        <div>
                            <span class="subheader">88888888 - VAGA REAL</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button style="padding: 4px !important;" type="button" class="btn btn-danger" data-dismiss="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18 6l-12 12" />
                        <path d="M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scriptis for this page -->
<script src="{{ asset('dist/js/encaminhamento.js') }}" defer></script>



<Script>
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
</Script>

@endsection