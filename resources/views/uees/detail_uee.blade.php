@extends('layout.main')

@section('title', 'SCP - Unidades Escolares')

@section('content')

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
<style>
    .hidden-form {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.5s, opacity 0.5s;
    }

    .visible-form {
        max-height: 1000px;
        /* Ajuste de acordo com a altura máxima necessária */
        opacity: 1;
        overflow: visible;
        transition: max-height 0.8s, opacity 1.6s;
    }

    .hidden-historico {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.5s, opacity 0.5s;
    }

    .visible-historico {
        max-height: 1000px;
        /* Ajuste de acordo com a altura máxima necessária */
        opacity: 1;
        overflow: visible;
        transition: max-height 0.8s, opacity 1.6s;
    }
</style>
<style>
    .mult-select-tag .body {
        display: flex;
        border: 1px solid #AAAAAA !important;
        background: #fff !important;
        min-height: 2.15rem;
        width: 100%;
        min-width: 14rem;
        border-radius: 5px !important;
    }

    .mult-select-tag .item-container {
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fbf8f3 !important;
        padding: .2rem .4rem;
        margin: .2rem;
        font-weight: 500;
        border: 1px solid #fbf8f3 !important;
        background: #36425a !important;
        border-radius: 5px !important;
    }

    .mult-select-tag .item-label {
        max-width: 100%;
        line-height: 1;
        font-size: .75rem;
        font-weight: 400;
        flex: 0 1 auto;
        color: #fbf8f3 !important;
    }

    h5 {
        font-size: 0.7rem !important;
        font-weight: bold;
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
    @if ($uee->desativation_situation == "Desativada")
    <div class="d-flex bg-danger justify-content-center align-items-center">
        <h5 class="pt-3 text-white">UNIDADE DESATIVADA NO DIA {{ \Carbon\Carbon::parse($uee->desativation_status_date)->format('d/m/Y') }}</h5>
    </div>
    @else
    <div class="d-flex bg-success justify-content-center align-items-center">
        <h5 class="pt-3 text-white">UNIDADE ATIVA</h5>
    </div>
    @endif
    <div class="shadow bg-primary text-white card_title">
        <h4 class=" title_show_carencias">DETALHES UNIDADE ESCOLAR</h4>
        <a class="mr-2" title="Voltar" href="{{ Session::get('previous_url') }}">
            <button>
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                    <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
                </svg>
                <span>VOLTAR</span>
            </button>
        </a>

    </div>
    <form class="p-4" action="/uees/update/{{ $uee->id }}" method="post">
        @csrf
        @method ('PUT')
        @if (Auth::user()->profile === "cpg_tecnico")
        <div class="form-row">
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="">NTE</label>
                    <input value="{{ $uee->nte }}" name="nte" id="nte" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="email">MUNICIPIO</label>
                    <input value="{{ $uee->municipio }}" name="municipio" id="municipio" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group_disciplina">
                    <label class="control-label" for="email">UNIDADE ESCOLAR</label>
                    <input value="{{ $uee->unidade_escolar }}" name="unidade_escolar" id="unidade_escolar" type="text" class="form-control form-control-sm" >
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="email">COD. UNIDADE</label>
                    <input value="{{ $uee->cod_unidade }}" name="cod_unidade" id="cod_unidade" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="tipo">TIPOLOGIA</label>
                    <input value="{{$uee->tipo}}" name="tipo" id="tipo" type="text" class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group_disciplina">
                    <label class="control-label" for="eixo">CATEGORIAS</label>
                    <select name="categorias[]" class="form-control form-control-sm" id="areas" multiple>
                        <option value="Quilombola" {{ $uee->categorias && in_array('Quilombola', json_decode($uee->categorias)) ? 'selected' : '' }}>QUILOMBOLA</option>
                        <option value="Tempo integral" {{ $uee->categorias && in_array('Tempo integral', json_decode($uee->categorias)) ? 'selected' : '' }}>TEMPO INTEGRAL</option>
                        <option value="Educacao Basica" {{ $uee->categorias && in_array('Educacao Basica', json_decode($uee->categorias)) ? 'selected' : '' }}>EDUCAÇÃO BÁSICA</option>
                        <option value="Assentamento" {{ $uee->categorias && in_array('Assentamento', json_decode($uee->categorias)) ? 'selected' : '' }}>ASSENTAMENTO</option>
                        <option value="Indigena" {{ $uee->categorias && in_array('Indigena', json_decode($uee->categorias)) ? 'selected' : '' }}>INDÍGENA</option>
                        <option value="Prisional" {{ $uee->categorias && in_array('Prisional', json_decode($uee->categorias)) ? 'selected' : '' }}>PRISIONAL / CASE</option>
                        <option value="Profissional" {{ $uee->categorias && in_array('Profissional', json_decode($uee->categorias)) ? 'selected' : '' }}>PROFISSIONAL</option>
                        <option value="Mediacao Tecnologica" {{ $uee->categorias && in_array('Mediacao Tecnologica', json_decode($uee->categorias)) ? 'selected' : '' }}>MEDIAÇÃO TECNOLÓGICA</option>
                        <option value="Educacao Especial" {{ $uee->categorias && in_array('Educacao Especial', json_decode($uee->categorias)) ? 'selected' : '' }}>EDUCAÇÃO ESPECIAL</option>
                        <option value="No Campo" {{ $uee->categorias && in_array('No Campo', json_decode($uee->categorias)) ? 'selected' : '' }}>NO CAMPO</option>
                        <option value="sedeCemit" {{ $uee->categorias && in_array('sedeCemit', json_decode($uee->categorias)) ? 'selected' : '' }}>SEDE / CEMIT</option>
                        <option value="Oficinas Pedagogicas" {{ $uee->categorias && in_array('Oficinas Pedagogicas', json_decode($uee->categorias)) ? 'selected' : '' }}>OFICINAS PEDAGOGICAS</option>
                    </select>
                </div>
            </div>
        </div>
        @endif
        @if (Auth::user()->profile === "administrador")
        <div class="form-row">
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="">NTE</label>
                    <input value="{{ $uee->nte }}" name="nte" id="nte" type="text" class="form-control form-control-sm" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="email">MUNICIPIO</label>
                    <input value="{{ $uee->municipio }}" name="municipio" id="municipio" type="text" class="form-control form-control-sm" required>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group_disciplina">
                    <label class="control-label" for="email">UNIDADE ESCOLAR</label>
                    <input value="{{ $uee->unidade_escolar }}" name="unidade_escolar" id="unidade_escolar" type="text" class="form-control form-control-sm" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="email">COD. UNIDADE</label>
                    <input value="{{ $uee->cod_unidade }}" name="cod_unidade" id="cod_unidade" type="text" class="form-control form-control-sm" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="tipo">TIPOLOGIA</label>
                    <select name="tipo" id="tipo" class="form-control select2" required>
                        <option value="{{ $uee->tipo }}"></option>
                        <option value="SEDE">SEDE</option>
                        <option value="ANEXO">ANEXO</option>
                        <option value="CEMIT">CEMIT</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group_disciplina">
                    <label class="control-label" for="eixo">CATEGORIAS</label>
                    <select name="categorias[]" class="form-control form-control-sm" id="areas" multiple>
                        <option value="Quilombola" {{ $uee->categorias && in_array('Quilombola', json_decode($uee->categorias)) ? 'selected' : '' }}>QUILOMBOLA</option>
                        <option value="Tempo integral" {{ $uee->categorias && in_array('Tempo integral', json_decode($uee->categorias)) ? 'selected' : '' }}>TEMPO INTEGRAL</option>
                        <option value="Educacao Basica" {{ $uee->categorias && in_array('Educacao Basica', json_decode($uee->categorias)) ? 'selected' : '' }}>EDUCAÇÃO BÁSICA</option>
                        <option value="Assentamento" {{ $uee->categorias && in_array('Assentamento', json_decode($uee->categorias)) ? 'selected' : '' }}>ASSENTAMENTO</option>
                        <option value="Indigena" {{ $uee->categorias && in_array('Indigena', json_decode($uee->categorias)) ? 'selected' : '' }}>INDÍGENA</option>
                        <option value="Prisional" {{ $uee->categorias && in_array('Prisional', json_decode($uee->categorias)) ? 'selected' : '' }}>PRISIONAL / CASE</option>
                        <option value="Profissional" {{ $uee->categorias && in_array('Profissional', json_decode($uee->categorias)) ? 'selected' : '' }}>PROFISSIONAL</option>
                        <option value="Mediacao Tecnologica" {{ $uee->categorias && in_array('Mediacao Tecnologica', json_decode($uee->categorias)) ? 'selected' : '' }}>MEDIAÇÃO TECNOLÓGICA</option>
                        <option value="Educacao Especial" {{ $uee->categorias && in_array('Educacao Especial', json_decode($uee->categorias)) ? 'selected' : '' }}>EDUCAÇÃO ESPECIAL</option>
                        <option value="No Campo" {{ $uee->categorias && in_array('No Campo', json_decode($uee->categorias)) ? 'selected' : '' }}>NO CAMPO</option>
                        <option value="sedeCemit" {{ $uee->categorias && in_array('sedeCemit', json_decode($uee->categorias)) ? 'selected' : '' }}>SEDE / CEMIT</option>
                        <option value="Oficinas Pedagogicas" {{ $uee->categorias && in_array('Oficinas Pedagogicas', json_decode($uee->categorias)) ? 'selected' : '' }}>OFICINAS PEDAGOGICAS</option>
                    </select>
                </div>
            </div>
        </div>
        @endif
        <hr>
        <h4 class="text-center">ANDAMENTO DA PROGRAMAÇÃO</h4>
        @if ((Auth::user()->profile === "cpg_tecnico")|| (Auth::user()->profile === "administrador"))
        <div class="d-flex mt-5">
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="typing_started">INICIOU A DIGITAÇÃO?</label>
                    <select name="typing_started" id="typing_started" class="form-control select2">
                        @if ($uee->typing_started == "SIM")
                        <option value="SIM" selected>SIM</option>
                        <option value="NÃO">NÃO</option>
                        @elseif ($uee->typing_started == "NÃO")
                        <option value="SIM">SIM</option>
                        <option value="NÃO" selected>NÃO</option>
                        @else
                        <option value=""></option>
                        <option value="SIM">SIM</option>
                        <option value="NÃO">NÃO</option>
                        @endif
                    </select>
                </div>

            </div>
            @if ($uee->description_typing_started != null)
            <div id="remove_typing_hidden" class="col-md-4">
                <div class="form-group_disciplina">
                    <label class="control-label" for="description_typing_started">MOTIVO DO NÃO INÍCIO</label>
                    <select name="description_typing_started" id="description_typing_started" class="form-control select2">
                        <option value="{{ $uee->description_typing_started }}" selected>{{ $uee->description_typing_started }}</option>
                        <option value="DEMORA DO GESTOR (A)">DEMORA DO GESTOR (A)</option>
                        <option value="MATRIZ SUPROT">MATRIZ SUPROT</option>
                        <option value="MATRIZ BASICA">MATRIZ BASICA</option>
                        <option value="TURMAS SUPROT">TURMAS SUPROT</option>
                        <option value="TURMAS EDUC. BASICA">TURMAS EDUC. BASICA</option>
                        <option value="MUDANÇA DE OFERTA">MUDANÇA DE OFERTA</option>
                        <option value="REALIZANDO DISTRIBUIÇÃO C.H.">REALIZANDO DISTRIBUIÇÃO C.H.</option>
                        <option value="NOVA PROGRAMAÇÃO EFETIVOS INGRESSADOS">NOVA PROGRAMAÇÃO EFETIVOS INGRESSADOS</option>
                        <option value="NOVA PROGRAMAÇÃO REDAS INGRESSADOS">NOVA PROGRAMAÇÃO REDAS INGRESSADOS</option>
                        <option value="CORREÇÃO DO CALENDÁRIO">CORREÇÃO DO CALENDÁRIO</option>
                        <option value="TURMAS DE EDUCAÇÃO ESPECIAL">TURMAS DE EDUCAÇÃO ESPECIAL</option>
                        <option value="TURMAS DAS OFICINAS CJCC">TURMAS DAS OFICINAS CJCC</option>
                        <option value="INTEGRAÇÃO SIGEDUC X SPE">INTEGRAÇÃO SIGEDUC X SPE</option>
                    </select>
                </div>
            </div>
            @else
            <div id="remove_typing_hidden" class="col-md-4" hidden>
                <div class="form-group_disciplina">
                    <label class="control-label" for="description_typing_started">MOTIVO DO NÃO INICIO</label>
                    <select name="description_typing_started" id="description_typing_started" class="form-control select2">
                        <option value="{{ $uee->description_typing_started }}" selected>{{ $uee->description_typing_started }}</option>
                        <option value="DEMORA DO GESTOR (A)">DEMORA DO GESTOR (A)</option>
                        <option value="MATRIZ SUPROT">MATRIZ SUPROT</option>
                        <option value="MATRIZ BASICA">MATRIZ BASICA</option>
                        <option value="TURMAS SUPROT">TURMAS SUPROT</option>
                        <option value="TURMAS EDUC. BASICA">TURMAS EDUC. BASICA</option>
                        <option value="MUDANÇA DE OFERTA">MUDANÇA DE OFERTA</option>
                        <option value="REALIZANDO DISTRIBUIÇÃO C.H.">REALIZANDO DISTRIBUIÇÃO C.H.</option>
                        <option value="NOVA PROGRAMAÇÃO EFETIVOS INGRESSADOS">NOVA PROGRAMAÇÃO EFETIVOS INGRESSADOS</option>
                        <option value="NOVA PROGRAMAÇÃO REDAS INGRESSADOS">NOVA PROGRAMAÇÃO REDAS INGRESSADOS</option>
                        <option value="CORREÇÃO DO CALENDÁRIO">CORREÇÃO DO CALENDÁRIO</option>
                        <option value="TURMAS DE EDUCAÇÃO ESPECIAL">TURMAS DE EDUCAÇÃO ESPECIAL</option>
                        <option value="TURMAS DAS OFICINAS CJCC">TURMAS DAS OFICINAS CJCC</option>
                        <option value="INTEGRAÇÃO SIGEDUC X SPE">INTEGRAÇÃO SIGEDUC X SPE</option>
                    </select>
                </div>
            </div>
            @endif
            @if ($uee->typing_started == "SIM")
            <div class="col-md-2" id="remove_finished_typing">
                <div class="form-group_disciplina">
                    <label class="control-label" for="finished_typing">CONCLUIU A DIGITAÇÃO?</label>
                    <select name="finished_typing" id="finished_typing" class="form-control select2">
                        @if ($uee->finished_typing == "SIM")
                        <option value="SIM" selected>SIM</option>
                        <option value="NÃO">NÃO</option>
                        @elseif ($uee->finished_typing == "NÃO")
                        <option value="SIM">SIM</option>
                        <option value="NÃO" selected>NÃO</option>
                        @else
                        <option value="{{ $uee->finished_typing }}">{{ $uee->finished_typing }}</option>
                        <option value="SIM">SIM</option>
                        <option value="NÃO">NÃO</option>
                        @endif
                    </select>
                </div>
            </div>
            @else
            <div class="col-md-2" id="remove_finished_typing" hidden>
                <div class="form-group_disciplina">
                    <label class="control-label" for="finished_typing">CONCLUIU A DIGITAÇÃO?</label>
                    <select name="finished_typing" id="finished_typing" class="form-control select2">
                        @if ($uee->finished_typing == "SIM")
                        <option value="SIM" selected>SIM</option>
                        <option value="NÃO">NÃO</option>
                        @elseif ($uee->finished_typing == "NÃO")
                        <option value="SIM">SIM</option>
                        <option value="NÃO" selected>NÃO</option>
                        @else
                        <option value=""></option>
                        <option value="SIM">SIM</option>
                        <option value="NÃO">NÃO</option>
                        @endif
                    </select>
                </div>
            </div>
            @endif
            @if (($uee->finished_typing == "NÃO") && ($uee->typing_started == "SIM"))
            <div class="col-md-4" id="remove_finished_typing_description">
                <div class="form-group_disciplina">
                    <label class="control-label" for="finished_typing_description">MOTIVO DA NÃO CONCLUSÃO</label>
                    <select name="finished_typing_description" id="finished_typing_description" class="form-control select2">
                        <option value="{{ $uee->finished_typing_description }}" selected>{{ $uee->finished_typing_description }}</option>
                        <option value="DEMORA DO GESTOR (A)">DEMORA DO GESTOR (A)</option>
                        <option value="MATRIZ SUPROT">MATRIZ SUPROT</option>
                        <option value="MATRIZ BASICA">MATRIZ BASICA</option>
                        <option value="TURMAS SUPROT">TURMAS SUPROT</option>
                        <option value="TURMAS EDUC. BASICA">TURMAS EDUC. BASICA</option>
                        <option value="MUDANÇA DE OFERTA">MUDANÇA DE OFERTA</option>
                        <option value="REALIZANDO DISTRIBUIÇÃO C.H.">REALIZANDO DISTRIBUIÇÃO C.H.</option>
                        <option value="NOVA PROGRAMAÇÃO EFETIVOS INGRESSADOS">NOVA PROGRAMAÇÃO EFETIVOS INGRESSADOS</option>
                        <option value="NOVA PROGRAMAÇÃO REDAS INGRESSADOS">NOVA PROGRAMAÇÃO REDAS INGRESSADOS</option>
                        <option value="CORREÇÃO DO CALENDÁRIO">CORREÇÃO DO CALENDÁRIO</option>
                        <option value="TURMAS DE EDUCAÇÃO ESPECIAL">TURMAS DE EDUCAÇÃO ESPECIAL</option>
                        <option value="TURMAS DAS OFICINAS CJCC">TURMAS DAS OFICINAS CJCC</option>
                        <option value="INTEGRAÇÃO SIGEDUC X SPE">INTEGRAÇÃO SIGEDUC X SPE</option>
                    </select>
                    </select>
                </div>
            </div>
            @else
            <div class="col-md-4" id="remove_finished_typing_description" hidden>
                <div class="form-group_disciplina">
                    <label class="control-label" for="finished_typing_description">MOTIVO DA NÃO CONCLUSÃO</label>
                    <select name="finished_typing_description" id="finished_typing_description" class="form-control select2">
                        <option value="{{ $uee->finished_typing_description }}" selected>{{ $uee->finished_typing_description }}</option>
                        <option value="DEMORA DO GESTOR (A)">DEMORA DO GESTOR (A)</option>
                        <option value="MATRIZ SUPROT">MATRIZ SUPROT</option>
                        <option value="MATRIZ BASICA">MATRIZ BASICA</option>
                        <option value="TURMAS SUPROT">TURMAS SUPROT</option>
                        <option value="TURMAS EDUC. BASICA">TURMAS EDUC. BASICA</option>
                        <option value="MUDANÇA DE OFERTA">MUDANÇA DE OFERTA</option>
                        <option value="REALIZANDO DISTRIBUIÇÃO C.H.">REALIZANDO DISTRIBUIÇÃO C.H.</option>
                        <option value="NOVA PROGRAMAÇÃO EFETIVOS INGRESSADOS">NOVA PROGRAMAÇÃO EFETIVOS INGRESSADOS</option>
                        <option value="NOVA PROGRAMAÇÃO REDAS INGRESSADOS">NOVA PROGRAMAÇÃO REDAS INGRESSADOS</option>
                        <option value="CORREÇÃO DO CALENDÁRIO">CORREÇÃO DO CALENDÁRIO</option>
                        <option value="TURMAS DE EDUCAÇÃO ESPECIAL">TURMAS DE EDUCAÇÃO ESPECIAL</option>
                        <option value="TURMAS DAS OFICINAS CJCC">TURMAS DAS OFICINAS CJCC</option>
                        <option value="INTEGRAÇÃO SIGEDUC X SPE">INTEGRAÇÃO SIGEDUC X SPE</option>
                    </select>
                    </select>
                </div>
            </div>
            @endif
            @if ($uee->finished_typing == "SIM")
            <div id="pch_phases" class="pch_phases">
                @if ($uee->programming_stage === 2)
                <ul class="ks-cboxtags">
                    <li><input name="check_2_pch" type="checkbox" id="checkboxOne" checked><label for="checkboxOne">2ª PROGRAMAÇãO</label></li>
                </ul>
                <ul class="ks-cboxtags">
                    <li><input name="check_3_pch" type="checkbox" id="checkboxTwo"><label for="checkboxTwo">3ª PROGRAMAÇãO</label></li>
                </ul>
                <ul class="ks-cboxtags">
                    <li><input name="check_4_pch" type="checkbox" id="checkboxThree"><label for="checkboxThree">4ª PROGRAMAÇãO</label></li>
                </ul>
                @endif
                @if ($uee->programming_stage === 3)
                <ul class="ks-cboxtags">
                    <li><input name="check_2_pch" type="checkbox" id="checkboxOne" checked><label for="checkboxOne">2ª PROGRAMAÇãO</label></li>
                </ul>
                <ul class="ks-cboxtags">
                    <li><input name="check_3_pch" type="checkbox" id="checkboxTwo" checked><label for="checkboxTwo">3ª PROGRAMAÇãO</label></li>
                </ul>
                <ul class="ks-cboxtags">
                    <li><input name="check_4_pch" type="checkbox" id="checkboxThree"><label for="checkboxThree">4ª PROGRAMAÇãO</label></li>
                </ul>
                @endif
                @if ($uee->programming_stage === 4)
                <ul class="ks-cboxtags">
                    <li><input name="check_2_pch" type="checkbox" id="checkboxOne" checked><label for="checkboxOne">2ª PROGRAMAÇãO</label></li>
                </ul>
                <ul class="ks-cboxtags">
                    <li><input name="check_3_pch" type="checkbox" id="checkboxTwo" checked><label for="checkboxTwo">3ª PROGRAMAÇãO</label></li>
                </ul>
                <ul class="ks-cboxtags">
                    <li><input name="check_4_pch" type="checkbox" id="checkboxThree" checked><label for="checkboxThree">4ª PROGRAMAÇãO</label></li>
                </ul>
                @endif
                @if (($uee->programming_stage === 0) || ($uee->programming_stage === null))
                <ul class="ks-cboxtags">
                    <li><input name="check_2_pch" type="checkbox" id="checkboxOne"><label for="checkboxOne">2ª PROGRAMAÇãO</label></li>
                </ul>
                <ul class="ks-cboxtags">
                    <li><input name="check_3_pch" type="checkbox" id="checkboxTwo"><label for="checkboxTwo">3ª PROGRAMAÇãO</label></li>
                </ul>
                <ul class="ks-cboxtags">
                    <li><input name="check_4_pch" type="checkbox" id="checkboxThree"><label for="checkboxThree">4ª PROGRAMAÇãO</label></li>
                </ul>
                @endif
            </div>
            @endif
        </div>
        <div class="form-row col-md-12">
            <div id="data_assuncao_row" class="col-md-12">
                <div class="form-group_disciplina">
                    <label for="obs_cpg">Observações <i class="ti-pencil"></i></label>
                    <textarea name="obs_cpg" class="form-control" id="obs_cpg" rows="6">{{ $uee->obs_cpg }}</textarea>
                </div>
            </div>
        </div>
        @endif
        @if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpg_tecnico"))
        <hr>
        <div class="buttons d-flex" style="gap: 20px;">
            @if (($uee->desativation_situation == "Ativa") || ($uee->desativation_situation == null))
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
            @if ($uee->desativation_situation == "Desativada")
            <button id="toggle-button" type="button" class="btn btn-success"><i class="ti-unlock"></i> REATIVAR UNIDADE</button>
            @else
            <button id="toggle-button" type="button" class="btn btn-danger"><i class="ti-lock"></i> DESATIVAR UNIDADE</button>
            @endif
            @if ($uee->typing_started != null)
            <button type="button" onclick="limparDadosDigitação()" class="btn btn-primary">LIMPAR DADOS</button>
            @endif
        </div>
        @endif
    </form>
    <div class="pl-4 pr-4 pb-4 hidden-form">
        <form class="mb-2" action="{{ route('uees.desativation_uee') }}" method="post">
            <input name="uee_id" id="uee_id" value="{{ $uee->id }}" type="number" class="form-control form-control-sm" hidden>
            <input value="{{ Auth::user()->id }}" id="user_id" name="user_id" type="text" class="form-control form-control-sm" hidden>
            @csrf
            <div class="form-row">
                <div class="col-md-2">
                    <div class="form-group_disciplina">
                        <label class="control-label" for="desativation_date">DATA</label>
                        <input name="desativation_date" id="desativation_date" type="date" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group_disciplina">
                        @if (($uee->desativation_situation == "Ativa") || ($uee->desativation_situation == null))
                        <label class="control-label" for="desativation_reason">MOTIVO DA DESATIVAÇÃO</label>
                        <select name="desativation_reason" id="desativation_reason" class="form-control select2" required>
                            <option value=""></option>
                            <option value="municipalizacao">MUNICIPALIZAÇÃO</option>
                            <option value="extincao">EXTINÇÃO</option>
                            <option value="extincao_convenio">EXTINÇÃO DE CONVÊNIO</option>
                        </select>
                        @else
                        <label class="control-label" for="desativation_reason">MOTIVO DA REATIVAÇÃO</label>
                        <select name="desativation_reason" id="desativation_reason" class="form-control select2" required>
                            <option value=""></option>
                            <option value="reativacao">REATIVAÇÃO</option>
                        </select>
                        @endif
                    </div>
                </div>
                <div id="data_assuncao_row" class="col-md-12">
                    <div class="form-group_disciplina">
                        <label for="observation">Observações<i class="ti-pencil"></i></label>
                        <textarea name="observation" class="form-control" id="observation" rows="4"></textarea>
                    </div>
                </div>
                <div class="buttons d-flex justify-content-end align-items-center">
                    @if ($uee->desativation_situation == "Desativada")
                    <button id="" type="submit" class="btn btn-success">REATIVAR</button>
                    @else
                    <button id="" type="submit" class="btn btn-danger">DESATIVAR</button>
                    @endif
                    <button id="toggle-button-historico" type="button" class="ml-2 btn btn-primary">HISTÓRICO</button>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggle-button');
        const form = document.querySelector('.hidden-form');
        const toggleButtonHistorico = document.getElementById('toggle-button-historico');
        const formHistorico = document.querySelector('.hidden-historico');

        toggleButton.addEventListener('click', () => {
            const isFormVisible = form.classList.contains('visible-form');

            if (isFormVisible) {
                form.classList.remove('visible-form');
                form.classList.add('hidden-form');
            } else {
                form.classList.remove('hidden-form');
                form.classList.add('visible-form');
            }
        });

        toggleButtonHistorico.addEventListener('click', () => {
            const isHistoricoVisible = formHistorico.classList.contains('visible-historico');

            if (isHistoricoVisible) {
                formHistorico.classList.remove('visible-historico');
                formHistorico.classList.add('hidden-historico');
            } else {
                formHistorico.classList.remove('hidden-historico');
                formHistorico.classList.add('visible-historico');
            }
        });
    });
</script>

@endsection