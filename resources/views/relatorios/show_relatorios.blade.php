@extends('layout.main')

@section('title', 'SCP - Relatórios')

@section('content')

<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias">RELATÓRIOS</h3>
</div>

<div class="row">

    <div class="col-md-3">
        <div class="accordion mb-2 card" id="accordionExampleOne">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center" id="headingOne" type="button" data-toggle="collapse" data-target="#carencia" aria-expanded="true" aria-controls="carencia">
                <h5 class="mb-0 text-white subheader">RELATÓRIOS GERAIS</h5>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fbf8f3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-caret-up-down">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18 10l-6 -6l-6 6h12" />
                    <path d="M18 14l-6 6l-6 -6h12" />
                </svg>
            </div>
            <div id="carencia" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExampleOne">
                <div class="card-body d-flex flex-column">
                    <!-- <a class="subheader text-primary mb-2 border-bottom" href="">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-info">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M11 14h1v4h1" />
                                <path d="M12 11h.01" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">INFO. GERAIS CARÊNCIA/PROVIMENTO/HOMOLOGAÇÃO</span>
                        </div>
                    </a> -->
                    <a class="subheader text-primary mb-2 border-bottom" href="{{ route('nota_tecnica') }}">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-notes">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 3m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                <path d="M9 7l6 0" />
                                <path d="M9 11l6 0" />
                                <path d="M9 15l4 0" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">NOTA TÉCNICA</span>
                        </div>
                    </a>
                    @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_tecnico"))
                    <a class="subheader text-primary mb-2 border-bottom" href="{{ route('provimento.status') }}">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">STATUS EM TRÂMITE</span>
                        </div>
                    </a>
                    @endif
                    <a class="subheader text-primary mb-2 border-bottom" href="{{ route('status.diario') }}">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-graph">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 18v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                <path d="M7 14l3 -3l2 2l3 -3l2 2" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">STATUS CARÊNCIA/PROVIMENTO DIÁRIO</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="accordion mb-2 card" id="unidadesEscolares">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center" id="unidades_escolares" type="button" data-toggle="collapse" data-target="#unidade" aria-expanded="true" aria-controls="unidade">
                <h5 class="mb-0 text-white subheader">UNIDADES ESCOLARES</h5>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fbf8f3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-caret-up-down">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18 10l-6 -6l-6 6h12" />
                    <path d="M18 14l-6 6l-6 -6h12" />
                </svg>
            </div>
            <div id="unidade" class="collapse " aria-labelledby="unidades_escolares" data-parent="#unidadesEscolares">
                <div class="card-body d-flex flex-column">
                    <a class="subheader text-primary mb-2 border-bottom" href="{{ route('status.unidades_escolares') }}">
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-home-stats">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M19 13v-1h2l-9 -9l-9 9h2v7a2 2 0 0 0 2 2h2.5" />
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2" />
                                <path d="M13 22l3 -3l2 2l4 -4" />
                                <path d="M19 17h3v3" />
                            </svg>
                            <span class="ml-2 pt-1 subheader">QTD. UNIDADES ESCOLARES</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection