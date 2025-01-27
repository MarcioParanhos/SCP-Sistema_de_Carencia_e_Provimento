@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif
<div class="col-12 grid-margin stretch-card">
    <div class="card shadow rounded">
        <div class="mb-0 shadow bg-primary text-white card_title">
            <h4 class=" title_show_carencias">carência detalhada</h4>
            <a class="mr-2" title="Voltar" href="/carencias/filter_carencias">
                <button>
                    <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                        <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
                    </svg>
                    <span>VOLTAR</span>
                </button>
            </a>
        </div>
        @if ( $uee->situacao === "PENDENTE" )
        <h4 class="badge badge-danger"><strong>UEE NÃO HOMOLOGADA ( PARA PROVER UM COMPONENTE SERÁ NECESSARIO A HOMOLOGAÇÃO DA UNIDADE ESCOLAR )</strong></h4>
        @endif
        @if ( $uee->situacao === "HOMOLOGADA" )
        <h4 class="badge badge-success"><strong>UEE HOMOLOGADA</strong></h4>
        @endif
        <div class="card-body">
            <form class="forms-sample" id="InsertForm">
                @csrf
                <input value="" id="tipo_vaga" name="tipo_vaga" type="text" class="form-control form-control-sm" hidden>
                <input value="Real" id="tipo_carencia" name="tipo_carencia" type="text" class="form-control form-control-sm" hidden>
                <div class="form-row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="nte" class="">NTE</label>
                            <input value="{{ $uee->nte }}" id="" name="nte" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="municipio" class="">Município</label>
                            <input value="{{ $uee->municipio }}" id="" name="" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="unidade_escolar" class="">Nome da Unidade Escolar</label>
                            <input value="{{ $uee->unidade_escolar }}" name="" required id="" type="text" class="form-control form-control-sm" readonly required>
                        </div>
                    </div>
                    <div class=" col-md-2">
                        <div class="form-group">
                            <label for="cod_unidade_provimento" class="">COD. UEE</label>
                            <input value="{{ $uee->cod_unidade }}" minlength="8" maxlength="9" name="cod_unidade_provimento" id="cod_unidade_provimento" type="number" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                </div>
            </form>
            <div id="table_form">
                <div class="mt-4 mb-2 ">
                    <h5 class="card-title">VAGAS DETALHADAS AGUARDANDO PROVIMENTO</h5>
                </div>
                <div class="table-responsive">
                    <table id="table1" class=" mb-4 table table-bordered table-sm table-hover">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="text-center">DISCIPLINA</th>
                                <th class="text-center">TIPO</th>
                                <th class="text-center">MAT</th>
                                <th class="input_provimento hidden">PROVER MAT</th>
                                <th class="text-center">VESP</th>
                                <th class="input_provimento hidden">PROVER VESP</th>
                                <th class="text-center">NOT</th>
                                <th class="input_provimento hidden">PROVER NOT</th>
                                <th class="text-center">TOTAL</th>
                                <th class="text-center">TIPO</th>
                                <th class="text-center">PROVER</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($carencias as $carencia)
                            @if ($carencia->total > 0) 
                            <tr>
                                <td id="disciplina_table" class="text-center">{{ $carencia->disciplina }}</td>
                                @if ($carencia->tipo_carencia === "Real")
                                <td class="text-center"><span class="tipo_carencia">R</span></td>
                                @endif
                                @if ($carencia->tipo_carencia === "Temp")
                                <td class="text-center"><span class="tipo_carencia">T</span></td>
                                @endif
                                <td class="text-center">{{ $carencia->matutino }}</td>
                                <td class="remove_hidden" hidden><input value="0" name="provimento_matutino" class="input_provimento form-control form-control-sm" type="number" min="0" max="{{ $carencia->matutino }}"></td>
                                <td class="text-center">{{ $carencia->vespertino }}</td>
                                <td class="remove_hidden" hidden><input value="0" name="provimento_vespertino" class="input_provimento form-control form-control-sm " type="number" min="0" max="{{ $carencia->vespertino }}"></td>
                                <td class="text-center">{{ $carencia->noturno }}</td>
                                <td class="remove_hidden" hidden><input value="0" name="provimento_noturno" class="input_provimento form-control form-control-sm" type="number" min="0" max="{{ $carencia->noturno }}"></td>
                                <td class="text-center">{{ $carencia->total }}</td>
                                <td class="text-center">{{ $carencia->tipo_vaga }}</td>
                                <td id="class-button" class="text-center">
                                    <a title="Consultar" data-id="{{ $carencia->id }}" id="{{ $carencia->id }}" class="transferir btn-show-carencia btn btn-sm btn-primary"><i class="ti-plus"></i></a>
                                </td>
                            </tr>
                            @else 
                            <tr>
                                <td class="text-center" colspan="10"><strong>Nenhum resultado encontrado.</strong></td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mb-2 span-table-2">
                        <div>
                            <span class="pt-2 tipo_carencia">R</span> - Real
                        </div>
                        <div>
                            <span class="pt-2 tipo_carencia">T</span> - Temporaria
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <form class="forms-sample" id="InsertForm" action="/addNewProvimento" method="post">
                @csrf
                <input name="nte" value="{{ $uee->nte }}" type="text" hidden>
                <input name="municipio" value="{{ $uee->municipio }}" type="text" hidden>
                <input name="unidade_escolar" value="{{ $uee->unidade_escolar }}" type="text" hidden>
                <input name="cod_unidade" value="{{ $uee->cod_unidade }}" type="text" hidden>
                <input value="{{ Auth::user()->name }}" id="usuario" name="usuario" type="text" class="form-control form-control-sm" hidden>
                <div id="">
                    <div class="mt-4 mb-2 ">
                        <h5 class="card-title">COMPONENTE A PROVER</h5>
                    </div>
                    <div class="form-row">
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
                    </div>
                    <div class="form-row">
                        <div class="col-md-3" id="motivo_vaga_row">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="forma_suprimento">FORMA DE SUPRIMENTO</label>
                                <select name="forma_suprimento" id="forma_suprimento" class="form-control select2" required>
                                    <option value=""></option>
                                    @foreach ($forma_suprimentos as $forma_suprimento)
                                    <option value="{{$forma_suprimento->forma}}">{{$forma_suprimento->forma}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" id="motivo_vaga_row">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="tipo_movimentacao">TIPO de movimentação</label>
                                <select name="tipo_movimentacao" id="tipo_movimentacao" class="form-control select2" required>
                                    <option value="">SELECIONE...</option>
                                    <option value="INGRESSO">INGRESSO</option>
                                    <option value="RELOTAÇÃO">RELOTAÇÃO</option>
                                    <option value="REMOÇÃO">REMOÇÃO</option>
                                    <option value="PRÓPRIA UE">NA PRÓPRIA UE</option>
                                    <option value="COMPLEMENTAÇÃO">COMPLEMENTAÇÃO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" id="motivo_vaga_row">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="tipo_aula">Tipo de Aula </label>
                                <select name="tipo_aula" id="tipo_aula" class="form-control select2" required>
                                    <option value="">SELECIONE...</option>
                                    <option value="NORMAL">NORMAL</option>
                                    <option value="EXTRA">EXTRA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" id="">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="situacao_provimento">situação do provimento</label>
                                <select name="situacao_provimento" id="situacao_provimento" class="form-control select2" required>
                                    <option value="">SELECIONE...</option>
                                </select>
                            </div>
                        </div>
                        <div id="data_encaminhamento_row" class="col-md-2" hidden>
                            <div class="form-group_disciplina">
                                <label for="data_encaminhamento" class="">data de encaminhamento</label>
                                <input value="data_encaminhamento" name="data_encaminhamento" id="data_encaminhamento" type="date" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div id="data_assuncao_row" class="col-md-2" hidden>
                            <div class="form-group_disciplina">
                                <label for="data_assuncao   " class="">assunção</label>
                                <input value="data_assuncao" name="data_assuncao" id="data_assuncao" type="date" class="form-control form-control-sm">
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
                    <div class="table-responsive">
                        <table id="table2" class="mb-4 table table-bordered table-sm table-hover">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-center">DISCIPLINA</th>
                                    <th class="text-center"></th>
                                    <th class="text-center">MAT</th>
                                    <th class="input_provimento ">PROVER MAT</th>
                                    <th class="text-center">VESP</th>
                                    <th class="input_provimento ">PROVER VESP</th>
                                    <th class="text-center">NOT</th>
                                    <th class="input_provimento ">PROVER NOT</th>
                                    <th class="text-center">TOTAL</th>
                                    <th class="text-center">TIPO</th>
                                    <th class="text-center">PROVER</th>
                                </tr>
                            </thead>
                        </table>
                        <div id="buttons" class="buttons mb-4">
                            @if ( $uee->situacao === "PENDENTE" )
                            <button id="btn_submit_provimento" type="submit" class="btn btn-primary mr-2" disabled><i class="ti-plus"></i> INCLUIR PROVIMENTO</button>
                            @endif
                            @if ( $uee->situacao === "HOMOLOGADA" )
                            <button id="btn_submit_provimento" type="submit" class="btn btn-primary mr-2"><i class="ti-plus"></i> INCLUIR PROVIMENTO</button>
                            @endif
                        </div>
                    </div>
                    <hr>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    data_encaminhamento.addEventListener("change", function() {
        data_assuncao.min = data_encaminhamento.value;
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const session_message = document.getElementById("session_message");

        if (session_message) {
            if (session_message.value === "error") {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Não é possível prover um total de 0 h.',
                })
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Vaga suprida com Sucesso!',
                    showConfirmButton: true,
                })
            }
        }
    });
</script>
@endsection