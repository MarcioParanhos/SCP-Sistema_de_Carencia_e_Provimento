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
                <input value="{{ $carencia->cadastro }}" name="cadastro" id="cadastro" type="text" class="form-control form-control-sm" readonly>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group_disciplina">
                <label class="control-label" for="servidor">Servidor</label>
                <input value="{{ $carencia->servidor }}" name="servidor" id="servidor" type="text" class="form-control form-control-sm" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="vinculo">vinculo</label>
                <input value="{{ $carencia->vinculo }}" name="vinculo" id="vinculo" type="text" class="form-control form-control-sm" readonly>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="regime" class="control-label">regime</label>
                <input value="{{ $carencia->regime }}" name="regime" id="regime" type="text" class="form-control form-control-sm" readonly>
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
                <textarea  class="form-control" id="obs_cpg" rows="4" readonly>{{ $carencia->obs_cpg }}</textarea>
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

@endsection