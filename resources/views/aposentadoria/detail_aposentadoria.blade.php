@extends('layout.main')

@section('title', 'SCP - Processos Tramitados')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif

<style>
    .outroteste {
        display: flex !important;
        flex-direction: row !important;
    }

    .text-upercase {
        text-transform: uppercase !important;
    }
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

<div class="card shadow rounded">
    <div class="shadow bg-primary text-white card_title">
        <h4 class=" title_show_carencias">Detalhes do processo - {{ $aposentadoria->num_process }}</h4>
        <a class="mr-2" title="Voltar" href="/aposentadorias">
            <button>
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                    <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
                </svg>
                <span>VOLTAR</span>
            </button>
        </a>
    </div>
    <div class="edit-container">
        <div class="user-edit">
            <i class="ti-pencil-alt"></i>
            <h4>{{ $aposentadoria->user_create }}</h4>
        </div>
        <div class="user-edit">
            <i class="ti-time"></i>
            <h4>{{ \Carbon\Carbon::parse($aposentadoria->created_at)->format('d/m/Y') }}</h4>
        </div>
    </div>
    <form class="p-4" action="/aposentadorias/update" method="post">
        @csrf
        <input value="{{ $aposentadoria->id }}" id="process_id" name="process_id" type="text" class="form-control form-control-sm" hidden>
        <div class="form-row">
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label for="nte" class="">Nº DO PROCESSO</label>
                    <input value="{{ $aposentadoria->num_process }}" id="num_process" name="num_process" type="text" class="form-control form-control-sm" data-mask="000.0000.0000.0000000-00" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label for="nte" class="">NTE</label>
                    <input value="{{ $aposentadoria->nte }}" id="nte" name="nte" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="municipio">MUNICIPIO</label>
                    <input value="{{ $aposentadoria->municipio }}" id="municipio" name="municipio" type="text" class="text-uppercase form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group_disciplina">
                    <label class="control-label" for="unidade">UNIDADE DE LOTAÇÃO</label>
                    <input value="{{ $aposentadoria->unidade_escolar }}" id="unidade" name="unidade_escolar" type="text" class="text-uppercase form-control form-control-sm" readonly>
                </div>
            </div>

        </div>
        <div class="form-row">
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label for="nregimete" class="">COD. UNIDADE</label>
                    <input value="{{ $aposentadoria->cod_unidade }}" name="regime" required id="regime" type="text" class="text-center form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label for="matricula" class="">MATRICULA</label>
                    <input value="{{ $aposentadoria->matricula }}" id="matricula" name="matricula" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group_disciplina">
                    <label for="servidor" class="">SERVIDOR</label>
                    <input value="{{ $aposentadoria->servidor }}" id="servidor" name="servidor" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label for="vinculo" class="">VINCULO</label>
                    <input value="{{ $aposentadoria->vinculo }}" id="vinculo" name="vinculo" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label for="nregimete" class="">REGIME</label>
                    <input value="{{ $aposentadoria->regime }}" name="regime" required id="regime" type="text" class="text-center form-control form-control-sm" readonly>
                </div>
            </div>
        </div>
        @if($aposentadoria->unidade_complementar)
        <hr>
        <h4 class="pb-4 title_show_carencias">UNIDADE DE COMPLEMENTAÇÃO</h4>
        <div class="form-row">
            <div class="col-md-5">
                <div class="form-group_disciplina">
                    <label for="unidade_escolar_complementacao" class="">UNIDADE DE COMPLEMENTAÇÃO</label>
                    <input value="{{ $aposentadoria->unidade_complementar }}" name="unidade_complementar" required id="unidade_escolar_complementacao" type="text" class=" form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label for="cod_unidade_complementacao" class="">COD. UNIDADE DE COMPLEMENTAÇÃO</label>
                    <input value="{{ $aposentadoria->cod_unidade_complementar }}" name="cod_unidade_complementar" required id="cod_unidade_complementacao" type="text" class=" form-control form-control-sm" readonly>
                </div>
            </div>

        </div>
        <hr>
        @endif
        <div class="form-row">
            @if ((Auth::user()->profile === "cad_tecnico") || (Auth::user()->profile === "administrador"))
            @if ($aposentadoria->carencia == null)
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="process_situation">SITUAÇÃO DO PROCESSO</label>
                    <select name="situacao_processo" id="process_situation" class="form-control form-control-sm select2" required>
                        <option value="{{ $aposentadoria->situacao_processo }}">{{ $aposentadoria->situacao_processo }}</option>
                        <option value="EXONERAÇÃO">EXONERAÇÃO</option>
                        <option value="ÓBITO">ÓBITO</option>
                        <option value="APOS. VOLUNTÁRIA">APOS. VOLUNTÁRIA</option>
                        <option value="APOS. COMPULSÓRIA">APOS. COMPULSÓRIA</option>
                        <option value="APOS. POR INCAPACIDADE">APOS. POR INCAPACIDADE</option>
                    </select>
                </div>
            </div>
            @else
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label for="nte" class="">SITUAÇÃO DO PROCESSO</label>
                    <input value="{{ strtoupper($aposentadoria->situacao_processo) }}" id="situacao_processo" name="situacao_processo" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            @endif
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="process_situation">CONCLUSÃO DO PROCESSO</label>
                    <select name="conclusao" id="conclusao" class="form-control form-control-sm select2">
                        <option value="{{ $aposentadoria->conclusao }}">{{ $aposentadoria->conclusao }}</option>
                        <option value="PUBLICAÇÃO">PUBLICAÇÃO</option>
                        <option value="DESISTÊNCIA">DESISTÊNCIA</option>
                    </select>
                </div>
            </div>
            @elseif ((Auth::user()->profile != "administrador") || (Auth::user()->profile != "cad_tecnico"))
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label for="nte" class="">SITUAÇÃO DO PROCESSO</label>
                    <input value="{{ strtoupper($aposentadoria->situacao_processo) }}" id="situacao_processo" name="situacao_processo" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            @endif
        </div>

        <hr>
        <h4 class="pb-4 title_show_carencias">Análise CPG</h4>
        @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
        <div class="form-row">
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="process_situation">Aposentadoria gerou carência?</label>
                    <select name="carencia" id="carencia" class="form-control form-control-sm select2">
                        <option></option>
                        <option value="Não" {{ $aposentadoria->carencia == "Não" ? 'selected' : '' }}>NÃO</option>
                        <option value="Sim" {{ $aposentadoria->carencia == "Sim" ? 'selected' : '' }}>SIM</option>
                    </select>
                </div>
            </div>
            <div id="remove_hidden" class="col-md-3" {{ $aposentadoria->carencia == "Não" ? 'hidden' : '' }}>
                <div class="form-group_disciplina">
                    <label class="control-label" for="process_situation">Local da Carência</label>
                    <select name="local_carencia" id="local_carencia" class="form-control form-control-sm select2">
                        @if (($aposentadoria->carencia_lot == "Sim") && ($aposentadoria->carencia_comp == "Sim"))
                        <option value="Lotacao">LOTAÇÃO</option>
                        <option value="Complementacao">COMPLEMENTAÇÃO</option>
                        <option selected value="Ambos">AMBOS (LOTAÇÃO E COMPLEMENTAÇÃO)</option>
                        @elseif (($aposentadoria->carencia_lot == "Sim") && ($aposentadoria->carencia_comp == null))
                        <option selected value="Lotacao">LOTAÇÃO</option>
                        <option value="Complementacao">COMPLEMENTAÇÃO</option>
                        <option value="Ambos">AMBOS (LOTAÇÃO E COMPLEMENTAÇÃO)</option>
                        @elseif (($aposentadoria->carencia_lot == null) && ($aposentadoria->carencia_comp == 'Sim'))
                        <option value="Lotacao">LOTAÇÃO</option>
                        <option selected value="Complementacao">COMPLEMENTAÇÃO</option>
                        <option value="Ambos">AMBOS (LOTAÇÃO E COMPLEMENTAÇÃO)</option>
                        @else
                        <option></option>
                        <option value="Lotacao">LOTAÇÃO</option>
                        <option value="Complementacao">COMPLEMENTAÇÃO</option>
                        <option value="Ambos">AMBOS (LOTAÇÃO E COMPLEMENTAÇÃO)</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div id="data_assuncao_row" class="col-md-12">
                <div class="form-group_disciplina">
                    <label for="obs_cpg">Observações CPG <i class="ti-pencil"></i></label>
                    <textarea name="obs_cpg" class="form-control" id="obs_cpg" rows="4">{{ $aposentadoria->obs_cpg }}</textarea>
                </div>
            </div>
        </div>
        @elseif ((Auth::user()->profile != "administrador") || (Auth::user()->profile != "cpg_tecnico"))
        <div class="form-row">
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="process_situation">Aposentadoria gerou carência?</label>
                    <input value="{{ $aposentadoria->carencia }}" id="carencia" name="carencia" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            @if ($aposentadoria->carencia == 'Sim')
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="process_situation">Local da Carência</label>
                    @if (($aposentadoria->carencia_lot == "Sim") && ($aposentadoria->carencia_comp == "Sim"))
                    <input value="AMBOS (LOTAÇÃO E COMPLEMENTAÇÃO)" id="carencia" name="" type="text" class="form-control form-control-sm" readonly>
                    @elseif (($aposentadoria->carencia_lot == "Sim") && ($aposentadoria->carencia_comp == null))
                    <input value="LOTAÇÃO" id="carencia" name="" type="text" class="form-control form-control-sm" readonly>
                    @elseif (($aposentadoria->carencia_lot == null) && ($aposentadoria->carencia_comp == 'Sim'))
                    <input value="COMPLEMENTAÇÃO" id="carencia" name="" type="text" class="form-control form-control-sm" readonly>
                    @else
                    <input value="SEM REGISTRO" id="carencia" name="" type="text" class="form-control form-control-sm" readonly>
                    @endif
                </div>
            </div>
            @endif
        </div>
        <div class="form-row">
            <div id="data_assuncao_row" class="col-md-12">
                <div class="form-group_disciplina">
                    <label for="obs_cpg">Observações CPG <i class="ti-pencil"></i></label>
                    <textarea name="obs_cpg" class="form-control" id="obs_cpg" rows="4" readonly>{{ $aposentadoria->obs_cpg }}</textarea>
                </div>
            </div>
        </div>
        @endif
        @if ($aposentadoria->carencia == "Sim")
        <hr>
        <h4 class="pb-4 title_show_carencias">Análise CPM</h4>
        @if ((Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador"))
        <div class="form-row">
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="process_situation">TIPO DA MOVIMENTAÇÃO</label>
                    <select name="forma_suprimento" id="forma_suprimento" class="form-control form-control-sm select2">
                        <option value="{{ $aposentadoria->forma_suprimento }}">{{ $aposentadoria->forma_suprimento }}</option>
                        <option value="INGRESSO REDA">INGRESSO REDA</option>
                        <option value="INGRESSO EFETIVO">INGRESSO EFETIVO</option>
                        <option value="RELOTAÇÃO">RELOTAÇÃO</option>
                        <option value="REMOÇÃO">REMOÇÃO</option>
                        <option value="PRÓPRIA UE">NA PRÓPRIA UE</option>
                        <option value="COMPLEMENTAÇÃO">COMPLEMENTAÇÃO</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div id="data_assuncao_row" class="col-md-12">
                <div class="form-group_disciplina">
                    <label for="obs_cpm">Observações CPM <i class="ti-pencil"></i></label>
                    <textarea name="obs_cpm" class="form-control" id="obs_cpm" rows="4">{{ $aposentadoria->obs_cpm }}</textarea>
                </div>
            </div>
        </div>
        @elseif ((Auth::user()->profile != "administrador") || (Auth::user()->profile != "cpm_tecnico"))
        <div class="form-row">
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="process_situation">TIPO DA MOVIMENTAÇÃO</label>
                    @if ($aposentadoria->forma_suprimento != null)
                    <input value="{{ $aposentadoria->forma_suprimento }}" id="carencia" name="" type="text" class="form-control form-control-sm" >
                    @else
                    <input value="SEM REGISTRO" id="carencia" name="" type="text" class="form-control form-control-sm" >
                    @endif
                </div>
            </div>
            <div id="data_assuncao_row" class="col-md-12">
                <div class="form-group_disciplina">
                    <label for="obs_cpm">Observações CPM <i class="ti-pencil"></i></label>
                    <textarea name="obs_cpm" class="form-control" id="obs_cpm" rows="4" >{{ $aposentadoria->obs_cpm }}</textarea>
                </div>
            </div>
        </div>
        @endif
        @endif
        <div class="pt-4 d-flex justify-content-end buttons">
            <!-- <button id="" type="submit" class="btn btn-primary"><i class="ti-reload"></i> ATUALIZAR</button> -->
            <div id="buttons" class="buttons">
                <!-- <button id="btn_submit_carencia" type="submit" class="btn btn-primary mr-2" >CADASTRAR</button> -->
                <button id="" class="button" type="submit">
                    <span class="button__text">ATUALIZAR</span>
                    <span class="button__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                    </span>
                </button>
            </div>
        </div>
    </form>
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
                    text: 'Não é possível excluir esse motivo porque existem carências associadas.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Registros atualizados com sucesso!',
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