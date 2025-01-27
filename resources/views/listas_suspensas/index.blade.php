@extends('layout.main')

@section('title', 'SCP - Listas Suspensas')

@section('content')

<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias">CONFIGURAÇÃO DE LISTAS SUSPENSAS</h3>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="accordion mb-2 card" id="accordionExampleOne">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center" id="headingOne" type="button" data-toggle="collapse" data-target="#carencia" aria-expanded="true" aria-controls="carencia">
                <h5 class="mb-0 text-white subheader">CARÊNCIA</h5>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fbf8f3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-caret-up-down">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18 10l-6 -6l-6 6h12" />
                    <path d="M18 14l-6 6l-6 -6h12" />
                </svg>
            </div>
            <div id="carencia" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExampleOne">
                <div class="card-body d-flex flex-column">
                    <a class="subheader text-primary mb-2 border-bottom" href="{{ route('motivo_vagas.show') }}">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-x">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                                <path d="M22 22l-5 -5" />
                                <path d="M17 22l5 -5" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">Motivo de Vaga</span>
                        </div>
                    </a>
                    <a class="subheader text-primary mb-2 border-bottom" href="{{ route('listas_suspensas_disciplinas.index') }}">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-book-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z" />
                                <path d="M19 16h-12a2 2 0 0 0 -2 2" />
                                <path d="M9 8h6" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">Disciplina</span>
                        </div>
                    </a>
                    <a class="subheader text-primary mb-2 border-bottom" href="{{ route('listas_suspensas_areas.index') }}">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-certificate">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5" />
                                <path d="M6 14m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M4.5 17l-1.5 5l3 -1.5l3 1.5l-1.5 -5" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">Área</span>
                        </div>
                    </a>
                    <a class="subheader text-primary mb-2 border-bottom" href="{{ route('listas_suspensas_cursos.index') }}">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-school">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                                <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">Curso</span>
                        </div>
                    </a>
                    <a class="subheader text-primary border-bottom" href="{{ route('listas_suspensas_componente_especial.index') }}">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-components">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12l3 3l3 -3l-3 -3z" />
                                <path d="M15 12l3 3l3 -3l-3 -3z" />
                                <path d="M9 6l3 3l3 -3l-3 -3z" />
                                <path d="M9 18l3 3l3 -3l-3 -3z" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">Componente Especial</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="accordion mb-2 card" id="accordionExampleTwo">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center" id="headingOne" type="button" data-toggle="collapse" data-target="#provimento" aria-expanded="true" aria-controls="provimento">
                <h5 class="mb-0 text-white subheader">PROVIMENTO</h5>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fbf8f3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-caret-up-down">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18 10l-6 -6l-6 6h12" />
                    <path d="M18 14l-6 6l-6 -6h12" />
                </svg>
            </div>
            <div id="provimento" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExampleTwo">
                <div class="card-body d-flex flex-column">
                    <a class="subheader text-primary mb-2 border-bottom" href="{{ route('forma_suprimento.index') }}">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-share">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h3" />
                                <path d="M16 22l5 -5" />
                                <path d="M21 21.5v-4.5h-4.5" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">FORMA DE SUPRIMENTO</span>
                        </div>
                    </a>
                    <a class="subheader text-primary mb-2 border-bottom" href="#">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-replace">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 3m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M15 15m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M21 11v-3a2 2 0 0 0 -2 -2h-6l3 3m0 -6l-3 3" />
                                <path d="M3 13v3a2 2 0 0 0 2 2h6l-3 -3m0 6l3 -3" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">TIPO DE MOVIMENTAÇÃO</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-md-3">
        <div class="accordion mb-2" id="accordionExampleThree">
            <div class="card-header bg-primary" id="headingOne" type="button" data-toggle="collapse" data-target="#afastamento" aria-expanded="true" aria-controls="afastamento">
                <h5 class="mb-0 text-white subheader">AFASTAMENTO</h5>
            </div>
            <div id="afastamento" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExampleThree">
                <div class="card-body">
                    Testando
                </div>
            </div>
        </div>
        <div class="accordion mb-2" id="accordionExampleFor">
            <div class="card-header bg-primary" id="headingOne" type="button" data-toggle="collapse" data-target="#unidades" aria-expanded="true" aria-controls="unidades">
                <h5 class="mb-0 text-white subheader">UNIDADES ESCOLARES</h5>
            </div>
            <div id="unidades" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExampleFor">
                <div class="card-body">
                    testando
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="accordion mb-2" id="accordionExampleThree">
            <div class="card-header bg-primary" id="headingOne" type="button" data-toggle="collapse" data-target="#reg_funcional" aria-expanded="true" aria-controls="afastamento">
                <h5 class="mb-0 text-white subheader">REGULARIZAÇÃO FUNCIONAL</h5>
            </div>
            <div id="reg_funcional" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExampleThree">
                <div class="card-body">
                    Testando
                </div>
            </div>
        </div>
    </div> -->
</div>


@endsection