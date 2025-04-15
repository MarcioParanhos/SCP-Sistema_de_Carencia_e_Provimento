@extends('layout.main')

@section('title', 'SCP - Carência')

@section('content')

<?php

use Carbon\Carbon;

$data_atual = Carbon::now();
$ano_atual = $data_atual->year;
?>

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
<div class="col-12">
    <div class="alert text-center text-white bg-success container alert-success alert-dismissible fade show" role="alert" style="min-width: 100%">
        <strong>{{ session('msg')}}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif
<div class="card">
    <div class="m shadow bg-primary text-white card_title">
        <h4 class="title_show_carencias">carência detalhada</h4>
        @if (session('ref_rota') != 'provimento')
        <a class="mr-2" title="Voltar" href="/carencias/filter_carencias">
            <button>
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                    <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
                </svg>
                <span>VOLTAR</span>
            </button>
        </a>
        @endif
        @if (session('ref_rota') === 'provimento')
        <a class="mr-2" title="Voltar" href="/buscar/provimento/filter_provimentos">
            <button>
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                    <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
                </svg>
                <span>VOLTAR</span>
            </button>
        </a>
        @endif
    </div>
    <div class="info-edit">
        <div class="edit-container-homlogada">
            <div class="user-edit">
                @if ($carencia->tipo_carencia === "Real")
                <h4 class="text-white badge badge-secondary">VAGA REAL</h4>
                @endif
                @if ($carencia->tipo_carencia === "Temp")
                <h4 class="text-white badge badge-secondary">VAGA TEMPORARIA</h4>
                @endif
                @if ($detailUeeHomologacao->situacao === "PENDENTE")
                <h4 class="badge badge-danger">UEE PENDENTE HOMOLOGAÇÃO</h4>
                @endif
                @if ($detailUeeHomologacao->situacao === "HOMOLOGADA")
                <h4 class="badge badge-success">UEE HOMOLOGADA</h4>
                @endif
            </div>
        </div>
        <div class="edit-container">
            <div class="user-edit">
                <i class="ti-pencil-alt"></i>
                <h4>{{ $carencia->usuario }}</h4>
            </div>
            <div class="user-edit">
                <i class="ti-time"></i>
                <h4>{{ \Carbon\Carbon::parse($carencia->created_at)->format('d/m/Y') }}</h4>
            </div>
        </div>
    </div>
    <div class="shadow card_info">
        <form class="mb-3" action="/carencias/update/{{ $carencia->id }}" method="post">
            @csrf
            @method ('PUT')
            <input value="{{ Auth::user()->id }}" id="user_id" name="user_id" type="text" class="form-control form-control-sm" hidden>
            <input value="{{ $carencia->id }}" id="carencia_id" name="carencia_id" type="text" class="form-control form-control-sm" hidden>
            <div class="form-row">
                @if ($carencia->nte < 10) <div class="col-md-1">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="nte_seacrh">NTE</label>
                        <input value="0{{ $carencia->nte }}" name="nte" id="nte" type="text" class="text-center form-control form-control-sm" readonly>
                    </div>
            </div>
            @endif
            @if ($carencia->nte > 10)
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="nte_seacrh">NTE</label>
                    <input value="{{ $carencia->nte }}" name="nte" id="nte" type="text" class="text-center form-control form-control-sm" readonly>
                </div>
            </div>
            @endif
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="municipio">MUNICIPIO</label>
                    <input value="{{ $carencia->municipio }}" name="municipio" id="municipio" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group_disciplina">
                    <label class="control-label" for="unidade_escolar">NOME DA UNIDADE ESCOLAR</label>
                    <input value="{{ $carencia->unidade_escolar }}" name="unidade_escolar" id="unidade_escolar" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label for="cod_ue" class="">COD. UE</label>
                    <input value="{{ $carencia->cod_ue }}" name="cod_ue" id="cod_ue" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="tipo_vaga">Motivo da vaga</label>
                    <select name="tipo_vaga" id="tipo_vaga" class="form-control select2" required>
                        <option value="{{ $carencia->tipo_vaga  }}" selected>{{ $carencia->tipo_vaga  }}</option>
                        <option value="Basica">Basica</option>
                        <option value="Especial">Especial</option>
                        <option value="Profissionalizante">Profissionalizante</option>
                    </select>
                </div>
            </div>
    </div>
    <div class="form-row">
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label class="control-label" for="cadastro">Matricula</label>
                <input value="{{ $carencia->cadastro }}" name="" id="" type="text" class="form-control form-control-sm" readonly>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group_disciplina">
                <label class="control-label" for="servidor">Servidor</label>
                <input value="{{ $carencia->servidor }}" name="" id="" type="text" class="form-control form-control-sm" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="vinculo">vinculo</label>
                <input value="{{ $carencia->vinculo }}" name="" id="" type="text" class="form-control form-control-sm" readonly>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="regime" class="control-label">regime</label>
                <input value="{{ $carencia->regime }}" name="" id="" type="text" class="form-control form-control-sm" readonly>
            </div>
        </div>
    </div>
    <div class="form-row">
        @if ( $carencia->tipo_vaga === "Profissionalizante" )

        <div class="col-md-4 " id="curso_row">
            <div class="form-group_disciplina">
                <label class="control-label" for="curso">Curso <span class="span_required">*</span></label>
                <select name="curso" id="curso" class="form-control form-control-sm select2" required>
                    <option value="{{ $carencia->curso }}">{{ $carencia->curso }}</option>
                    @foreach ($eixo_cursos as $eixo_cursos)
                    <option>{{$eixo_cursos -> curso}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3 " id="eixo_row">
            <div class="form-group_disciplina">
                <label for="unidade_escolar" class="">Eixo <span class="span_required">*</span></label>
                <input value="{{ $carencia->eixo }}" name="eixo" required id="eixo" type="text" class="form-control form-control-sm" readonly required>
            </div>
        </div>

        @endif
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="inicio_vaga" class="control-label">inicio da vaga</label>
                <input value="{{ $carencia->inicio_vaga }}" name="inicio_vaga" id="inicio_vaga" type="date" class="form-control form-control-sm">
            </div>
        </div>
        @if ( $carencia->tipo_carencia === "Temp" )
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="fim_vaga" class="control-label">Fim da vaga</label>
                <input value="{{ $carencia->fim_vaga }}" name="fim_vaga" id="fim_vaga" type="date" class="form-control form-control-sm">
            </div>
        </div>
        @endif
        @if ($carencia->tipo_carencia === "Real")
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="motivo_vaga">Motivo da vaga</label>
                <select name="motivo_vaga" id="motivo_vaga" class="form-control select2" required>
                    <option value="{{ $carencia->motivo_vaga }}" selected>{{ $carencia->motivo_vaga }}</option>
                    @foreach ($motivo_vagaReal as $motivo_vagaReal)
                    <option>{{$motivo_vagaReal-> motivo}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif
        @if ($carencia->tipo_carencia === "Temp")
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="motivo_vaga">Motivo da vaga</label>
                <select name="motivo_vaga" id="motivo_vaga" class="form-control select2" required>
                    <option value="{{ $carencia->motivo_vaga }}" selected>{{ $carencia->motivo_vaga }}</option>
                    @foreach ($motivo_vagaTemp as $motivo_vagaTemp)
                    <option>{{$motivo_vagaTemp-> motivo}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif
        @if($carencia->motivo_vaga === "LICENÇA POR APRAZAMENTO")
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="num_rim" class="control-label">NUMERO DO RIM</label>
                <input value="{{ $carencia->num_rim }}" name="num_rim" id="num_rim" type="text" class="form-control form-control-sm">
            </div>
        </div>
        @endif
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="disciplina">Disciplina</label>
                <select name="disciplina" id="disciplina" class="form-control select2" required>
                    <option value="{{ $carencia->disciplina }}" selected>{{ $carencia->disciplina }}</option>
                    @foreach ($disciplinas as $disciplinas)
                    <option>{{$disciplinas -> nome}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="area">ÁREA</label>
                <select name="area" id="area" class="form-control select2">
                    <option value="{{ $carencia->area }}">{{ $carencia->area }}</option>
                    <option value="CIÊNCIAS HUMANAS E SUAS TECNOLOGIAS">CIÊNCIAS HUMANAS E SUAS TECNOLOGIAS</option>
                    <option value="MATEMATICA E SUAS TECNOLOGIAS">MATEMATICA E SUAS TECNOLOGIAS</option>
                    <option value="LINGUAGENS, CODIGOS E SUAS TECNOLOGIAS">LINGUAGENS, CODIGOS E SUAS TECNOLOGIAS</option>
                    <option value="CIÊNCIAS DA NATUREZA E SUAS TECNOLOGIAS">CIÊNCIAS DA NATUREZA E SUAS TECNOLOGIAS</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-1">
            <div class="form-group_disciplina">
                <label class="control-label" for="matutino">mat</label>
                <input value="{{ $carencia->matutino }}" name="matutino" id="matutino" type="text" class="text-center form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group_disciplina">
                <label class="control-label" for="vespertino">vesp</label>
                <input value="{{ $carencia->vespertino }}" name="vespertino" id="vespertino" type="text" class="text-center form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group_disciplina">
                <label for="noturno" class="">not</label>
                <input value="{{ $carencia->noturno }}" name="noturno" id="noturno" type="text" class="text-center form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group_disciplina">
                <label for="total" class="">total</label>
                <input value="{{ $carencia->total }}" name="total" id="total" type="text" class="text-center form-control form-control-sm" readonly>
            </div>
        </div>
    </div>
    @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
    <div class="form-row">
        <div id="data_assuncao_row" class="col-md-12">
            <div class="form-group_disciplina">
                <label for="obs">Observações<i class="ti-pencil"></i></label>
                <textarea name="obs_cpg" class="form-control" id="obs_cpg" rows="4">{{ $carencia->obs_cpg }}</textarea>
            </div>
        </div>
    </div>
    @else
    <div class="form-row">
        <div id="data_assuncao_row" class="col-md-12">
            <div class="form-group_disciplina">
                <label for="obs">Observações<i class="ti-pencil"></i></label>
                <textarea class="form-control" id="obs_cpg" rows="4" readonly>{{ $carencia->obs_cpg }}</textarea>
            </div>
        </div>
    </div>
    @endif
    <div class="d-flex justify-content-end">
        @php
        $currentDate = date('Y-m-d');
        $currentDateTime = new DateTime($currentDate);
        @endphp
        @if ($carencia->total != 0)
        @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))

        @if (($carencia->fim_vaga > $currentDateTime) && ($carencia->tipo_carencia === "Temp"))
        <!-- <button id="" type="submit" class="btn btn-sm btn-primary"><i class="ti-reload"></i> ATUALIZAR</button> -->
        <div id="buttons" class="buttons">
            <!-- <button id="btn_submit_carencia" type="submit" class="btn btn-primary mr-2" >CADASTRAR</button> -->
            <button id="" class="button" type="submit">
                <span class="button__text">ATUALIZAR</span>
                <span class="button__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                    </svg>
                </span>
            </button>
        </div>
        @endif

        <!-- @if (($carencia->fim_vaga < $currentDateTime) && ($detailUeeHomologacao->situacao === "HOMOLOGADA"))
            <div id="buttons" class="buttons">
                <button id="" class="button" type="submit">
                    <span class="button__text">ATUALIZAR</span>
                    <span class="button__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                        </svg>
                    </span>
                </button>
            </div>
            @endif -->

        @if ($detailUeeHomologacao->situacao === "HOMOLOGADA")
        <!-- <button id="" type="submit" class=" btn btn-sm btn-primary" disabled hidden><i class="ti-reload"></i> ATUALIZAR</button> -->
        <div id="buttons" class="buttons">
            <!-- <button id="btn_submit_carencia" type="submit" class="btn btn-primary mr-2" >CADASTRAR</button> -->
            <button id="" class="button" type="submit" disabled hidden>
                <span class="button__text">ATUALIZAR</span>
                <span class="button__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                    </svg>
                </span>
            </button>
        </div>
        @endif

        @if (($detailUeeHomologacao->situacao === "PENDENTE") || (Auth::user()->profile != "consulta") )
        <div id="buttons" class="buttons">
            <!-- <button id="btn_submit_carencia" type="submit" class="btn btn-primary mr-2" >CADASTRAR</button> -->
            <button id="" class="button" type="submit">
                <span class="button__text">ATUALIZAR</span>
                <span class="button__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                    </svg>
                </span>
            </button>
        </div>
        @endif
        @endif
        @endif
    </div>

    </form>
    <h5>COMPONENTES PROVIDOS PARA ESTA VAGA</h5>
    <div class="p-1 table-responsive">
        <table class=" table table-sm table-hover table-bordered">
            <thead class="bg-primary text-white">
                <tr class="text-center">
                    <th scope="col">SERVIDOR</th>
                    <th scope="col">MATRICULA</th>
                    <th scope="col">VINCULO</th>
                    <th scope="col">DISCPLINA</th>
                    <th scope="col">MAT</th>
                    <th scope="col">VESP</th>
                    <th scope="col">NOT</th>
                    <th scope="col">TOTAL</th>
                    <th scope="col">TIPO DE AULA</th>
                    <th scope="col">FORMA</th>
                    <th scope="col">TIPO MOVIMENTAÇÃO</th>
                    <th scope="col">SITUAÇÃO</th>
                    <th scope="col">ENCAMINHAMENTO</th>
                    <th scope="col">ASSUNÇÃO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detailProvimentos as $detailProvimento)
                <tr>
                    <td class="text-center">{{ $detailProvimento->servidor }}</td>
                    <td class="text-center">{{ $detailProvimento->cadastro }}</td>
                    <td class="text-center">{{ $detailProvimento->vinculo }}</td>
                    <td class="text-center">{{ $detailProvimento->disciplina }}</td>
                    <td class="text-center">{{ $detailProvimento->provimento_matutino }}</td>
                    <td class="text-center">{{ $detailProvimento->provimento_vespertino }}</td>
                    <td class="text-center">{{ $detailProvimento->provimento_noturno }}</td>
                    <td class="text-center">{{ $detailProvimento->total }}</td>
                    <td class="text-center">{{ $detailProvimento->tipo_aula}}</td>
                    <td class="text-center">{{ $detailProvimento->forma_suprimento }}</td>
                    <td class="text-center">{{ $detailProvimento->tipo_movimentacao }}</td>
                    @if ($detailProvimento->situacao_provimento === "tramite")
                    <td class="text-center">EM TRÂMITE</td>
                    @endif
                    @if ($detailProvimento->situacao_provimento === "provida")
                    <td class="text-center">PROVIDA</td>
                    @endif
                    <td class="text-center">{{ \Carbon\Carbon::parse($detailProvimento->data_encaminhamento)->format('d/m/Y')}}</td>
                    @if (!$detailProvimento->data_assuncao)
                    <td class="text-center">PENDENTE</td>
                    @endif
                    @if ($detailProvimento->data_assuncao)
                    <td class="text-center">{{ \Carbon\Carbon::parse($detailProvimento->data_assuncao)->format('d/m/Y') }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
<hr>

<!-- Seção de Reserva de Vaga -->
<div class="card mt-4 shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">RESERVA DE VAGA</h4>

    </div>

    <div class="card-body">
        @if ((Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador"))
        <form action="{{ route('reserva.create') }}" method="post">
            @csrf
            <input type="hidden" name="carencia_id" value="{{ $carencia->id  }}">
            <input value="{{ Auth::user()->id }}" id="user_id" name="user_id" type="text" class="form-control form-control-sm" hidden>

            <div class="form-row">
                <div id="matricula-row" class="col-md-2">
                    <div class="display_btn position-relative form-group">
                        <div>
                            <label for="cadastro" class="">Matrícula / CPF</label>
                            <input value="" minlength="8" maxlength="11" name="cadastro" id="cadastro" type="cadastro" class="form-control form-control-sm">
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
                        <input value="" id="servidor" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="vinculo" class="">vinculo</label>
                        <input value="" id="vinculo" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="position-relative form-group">
                        <label for="regime" class="">regime</label>
                        <input value="" required id="regime" type="text" class="form-control form-control-sm" readonly>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="reserva_inicio">Data de Início da Reserva</label>
                        <input type="date" class="form-control form-control-sm" id="reserva_inicio" name="reserva_inicio" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="reserva_fim">Data de Fim da Reserva</label>
                        <input type="date" class="form-control form-control-sm" id="reserva_fim" name="reserva_fim">
                    </div>
                </div>


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
                    <div class="col-md-3" id="motivo_vaga_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="tipo_movimentacao">TIPO de movimentação</label>
                            <select name="tipo_movimentacao" id="tipo_movimentacao" class="form-control select2" required>
                                <option value="">SELECIONE...</option>
                                <option value="INGRESSO">INGRESSO</option>
                                <option value="RELOTAÇÃO">RELOTAÇÃO</option>
                                <option value="REMOÇÃO">REMOÇÃO</option>
                                <option value="PRÓPRIA UE">NA PRÓPRIA UE</option>
                                <option value="COMPLEMENTAÇÃO">COMPLEMENTAÇÃO</option>
                                <option value="COMPLEMENTAÇÃO">COMPLEMENTAÇÃO</option>
                                <option value="CONVOCAÇÃO SELETIVO">CONVOCAÇÃO SELETIVO</option>
                                <option value="AUTORIZAÇÃO EMERGENCIAL">AUTORIZAÇÃO EMERGENCIAL</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 id="motivo_vaga_row">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="tipo_aula">Tipo de Aula </label>
                            <select name="tipo_aula" id="tipo_aula" class="form-control select2" required>
                                <option value="">SELECIONE...</option>
                                <option value="NORMAL">NORMAL</option>
                                <option value="EXTRA">EXTRA</option>
                            </select>
                        </div>
                    </div>
                <div id="data_assuncao_row" class="col-md-12">
                    <div class="form-group_disciplina">
                        <label for="justificativa_reserva">Justificativa <i class="ti-pencil"></i></label>
                        <textarea class="form-control" name="justificativa_reserva" id="justificativa_reserva" rows="5"></textarea>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end mt-3">
                <div id="buttons" class="buttons">
                    <button id="" class="button" type="submit">
                        <span class="button__text">RESERVAR</span>
                        <span class="button__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M14 4l0 4l-6 0l0 -4" />
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        @endif

        <!-- Lista de Reservas Existentes -->
        <hr>
        <div class="mt-4">
            <h5>RESERVA EXISTENTE PARA ESTA VAGA</h5>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center subheader">#</th>
                            <th class="text-center subheader">Servidor</th>
                            <th class="text-center subheader">Matrícula</th>
                            <th class="text-center subheader">Vínculo</th>
                            <th class="text-center subheader">Período</th>
                            <th class="text-center subheader">Justificativa</th>
                            <th class="text-center subheader">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vaga_reserva as $reserva)
                        <tr>
                            <td class="text-center subheader">{{ $reserva->id }}</td>
                            <td class="text-center subheader">{{ $reserva->servidor->nome }}</td>
                            <td class="text-center subheader">{{ $reserva->servidor->cadastro }}</td>
                            <td class="text-center subheader">{{ $reserva->servidor->vinculo }}</td>
                            <td class="text-center subheader">
                                <strong>Início:</strong> {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }} <br>
                                <strong>Fim:</strong> {{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y') }}
                            </td>
                            <td class="text-center subheader">{{ $reserva->justificativa }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection