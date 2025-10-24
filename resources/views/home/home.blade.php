@extends('layout.main')

@section('title', 'SCP - Home')

@section('content')

    <style>

    </style>

    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="title-app font-weight-bold">VISÃO GERAL</h3>
                    <h6 class="user_auth font-weight-normal mb-0"><span
                            class="text-primary subheader">{{ Auth::user()->name }} | {{ Auth::user()->sector->name }} - {{ Auth::user()->sector->tag }}</span></h6>
                </div>
                <div class="justify-content-end align-items-center d-flex col-12 col-xl-4">
                    <label class="mr-3 pt-2 ano-title" for="">Ano de Referência</label>
                    <select id="ref_year" class="form-control-sm dropdown-toggle">
                        @if ($anoRef === '2025')
                            <option selected>{{ $anoRef }}</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        @endif
                        @if ($anoRef === '2024')
                            <option value="2025">2025</option>
                            <option selected>{{ $anoRef }}</option>
                            <option value="2023">2023</option>
                        @endif
                        @if ($anoRef === '2023')
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option selected>{{ $anoRef }}</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="cards_system" class="row">
        <div id="logo_system" class="col-md-4 grid-margin stretch-card ">
            <div class="shadow card tale-bg">
                <div class="card-people">
                    <img class=" img-logo" src="images/SCP.png" alt="Logo Sistema">
                </div>
            </div>
        </div>
        <div id="cards_system_unit" class="col-md-8 grid-margin transparent">
            <div class="card p-4 shadow">
                <div class="mb-2 row gap_mobile">
                    <div class="col-md-3 mb-2 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="text-center font_title mb-2 subheader"><strong>VAGA REAL -
                                        {{ number_format($carenciasBasicaReal + $carenciasProfiReal, 0, ',', '.') }} h
                                    </strong></p>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-center subheader">BÁSICA</p>
                                        <p class="font_sub-title">{{ number_format($carenciasBasicaReal, 0, ',', '.') }} h
                                        </p>
                                    </div>
                                    <div class="mt-2 d-flex flex-column  justify-content-center align-middle">
                                        <p class="text-center subheader">PROFISS.</p>
                                        <p class="text-center font_sub-title">
                                            {{ number_format($carenciasProfiReal, 0, ',', '.') }} h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="text-center font_title mb-2 subheader"><strong>VAGA TEMPORÁRIA -
                                        {{ number_format($carenciasBasicaTemp + $carenciasProfiTemp, 0, ',', '.') }}
                                        h</strong></p>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-center subheader">BÁSICA</p>
                                        <p class="text-center font_sub-title" style="font-size: 22px;">
                                            {{ number_format($carenciasBasicaTemp, 0, ',', '.') }} h</p>
                                    </div>
                                    <div class="mt-2 d-flex flex-column  justify-content-center align-middle">
                                        <p class="text-center subheader">PROFISS.</p>
                                        <p class="text-center font_sub-title" style="font-size: 22px;">
                                            {{ number_format($carenciasProfiTemp, 0, ',', '.') }} h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="text-center font_title mb-2 subheader"><strong>VAGA ED. ESPECIAL -
                                        {{ number_format($carenciasRealEdEspecial + $carenciasTempEdEspecial, 0, ',', '.') }}
                                        h</strong></p>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-center subheader">REAL</p>
                                        <p class="text-center font_sub-title" style="font-size: 22px;">
                                            {{ number_format($carenciasRealEdEspecial, 0, ',', '.') }} h</p>
                                    </div>
                                    <div class="mt-2 d-flex flex-column  justify-content-center align-middle">
                                        <p class="text-center subheader">TEMP.</p>
                                        <p class="text-center font_sub-title" style="font-size: 22px;">
                                            {{ number_format($carenciasTempEdEspecial, 0, ',', '.') }} h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="text-center font_title mb-2 subheader"><strong>VAGA EMITEC -
                                        {{ number_format($vagaEmitec, 0, ',', '.') }} h </strong></p>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-center subheader">QTD. SERVIDORES EMITEC</p>
                                        <p class="font_sub-title text-center">
                                            {{ number_format($vagaEmitec / 20, 0, ',', '.') }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-2 row gap_mobile">
                    <div class="col-md-6 mb-2 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="text-center font_title mb-2 subheader"><strong>TOTAL DE PROVIMENTO -
                                        {{ number_format($provimentosReal + $provimentosTemp, 0, ',', '.') }} h</strong>
                                </p>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-center subheader">REAL</p>
                                        <p class="font_sub-title" style="font-size: 22px;">
                                            {{ number_format($provimentosReal, 0, ',', '.') }} h</p>
                                    </div>
                                    <div class="mt-2 d-flex flex-column  justify-content-center align-middle">
                                        <p class="text-center subheader">TEMP.</p>
                                        <p class="font_sub-title fs-30" style="font-size: 22px;">
                                            {{ number_format($provimentosTemp, 0, ',', '.') }} h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="text-center font_title mb-2 subheader"><strong>PROV. EM TRÂMITE -
                                        {{ number_format($provimentosTramiteReal + $provimentosTramiteTemp, 0, ',', '.') }}
                                        h</strong></p>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-center subheader">REAL</p>
                                        <p class="font_sub-title" style="font-size: 22px;">
                                            {{ number_format($provimentosTramiteReal, 0, ',', '.') }} h</p>
                                    </div>
                                    <div class="mt-2 d-flex flex-column  justify-content-center align-middle">
                                        <p class="text-center subheader">TEMP.</p>
                                        <p class="font_sub-title fs-30" style="font-size: 22px;">
                                            {{ number_format($provimentosTramiteTemp, 0, ',', '.') }} h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4 mb-2 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <p class="font_title mb-4">PROV. EM TRÂMITE (REAL)</p>
                                            <div class="d-flex justify-content-between align-middle">
                                                <p class="font_sub-title fs-30">{{ number_format($provimentosTramiteReal, 0, ',', '.') }} h</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <p class="font_title mb-4">PROV. EM TRÂMITE (TEMP.)</p>
                                            <div class="d-flex justify-content-between align-middle">
                                                <p class="font_sub-title fs-30">{{ number_format($provimentosTramiteTemp, 0, ',', '.') }} h</p>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                    <!-- <div class="col-md-4 mb-4 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <p class="font_title mb-4">PROV. COM PCH PROGRAMADA</p>
                                            <div class="d-flex justify-content-between align-middle">
                                                <p class="font_sub-title fs-30">{{ $provimentosPCH }} h</p>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" width="30" height="30">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>

                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                </div>
                <div class="row gap_mobile">
                    {{-- <div class="col-md-4 mb-2 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="text-center font_title mb-4 subheader"><strong>UNIDADES SEDE HOMOLOGADAS</strong>
                                </p>
                                <p class="text-center font_sub-title fs-30 mb-0">
                                    {{ number_format($totalUnitsSedes, 0, ',', '.') }} -
                                    {{ number_format($percentSedes, 1, ',', '') }}%</p>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-4 mb-2 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="text-center text-white mb-2 subheader border-bottom"
                                    style="font-size: 14px; opacity: 0.9;">
                                    Total Geral de SEDES: <strong>{{ $totalUnitsSedesAll }}</strong>
                                </p>
                                <p class="text-center font_title  subheader">
                                    <strong>UNIDADES SEDE HOMOLOGADAS</strong>
                                </p>

                                <p class="text-center font_sub-title fs-30 mb-2">
                                    <strong> {{ number_format($totalUnitsSedes, 0, ',', '.') }} -
                                        {{ number_format($percentSedes, 1, ',', '') }}%
                                </p></strong>
                                </p>

                                <p class="text-center text-warning mb-0" style="font-size: 13px;">
                                    Pendente: <strong >{{ $pendingUnitsSedes }}</strong> -
                                    {{ number_format($percentPendingSedes, 1, ',', '') }}%
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="text-center text-white mb-2 subheader border-bottom"
                                    style="font-size: 14px; opacity: 0.9;">
                                    Total Geral de Anexos: <strong>{{ $totalUnitsAnexosAll }}</strong>
                                </p>
                                <p class="text-center font_title  subheader">
                                    <strong>ANEXOS HOMOLOGADOS</strong>
                                </p>

                                <p class="text-center font_sub-title fs-30 mb-2">
                                    <strong> {{ number_format($totalUnitsAnexos, 0, ',', '.') }} -
                                        {{ number_format($percentAnexos, 1, ',', '') }}%
                                </p></strong>
                                </p>

                                <p class="text-center text-warning mb-0" style="font-size: 13px;">
                                    Pendente: <strong >{{ $pendingUnitsAnexos }}</strong> -
                                    {{ number_format($percentPendingAnexos, 1, ',', '') }}%
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="text-center text-white mb-2 subheader border-bottom"
                                    style="font-size: 14px; opacity: 0.9;">
                                    Total Geral de Cemits: <strong>{{ $totalUnitsCemitAll }}</strong>
                                </p>
                                <p class="text-center font_title  subheader">
                                    <strong>CEMIT/EMITEC HOMOLOGADOS</strong>
                                </p>

                                <p class="text-center font_sub-title fs-30 mb-2">
                                    <strong> {{ number_format($totalUnitsCemits, 0, ',', '.') }} -
                                        {{ number_format($percentCemits, 1, ',', '') }}%
                                </p></strong>
                                </p>

                                <p class="text-center text-warning  mb-0" style="font-size: 13px;">
                                    Pendente: <strong class="">{{ $pendingUnitsCemits }}</strong> -
                                    {{ number_format($percentPendingCemits, 1, ',', '') }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="status_system" class="mobile-hidden row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="shadow card position-relative">
                <div class="card-body">
                    <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2"
                        data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                        <div class="ml-xl-4 mt-3">
                                            <p class="card-title">TOP 5 - Disciplinas com carência</p>
                                            <p class="mb-2 mb-xl-0">Top 5 disciplinas com maior quantidade de carência
                                                lançada no sistema do SCP</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-9">
                                        <div class="row">
                                            <div class="col-md-12 border-right">
                                                <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                    <table class="table table-borderless report-table">
                                                        @foreach ($disciplinas as $disciplina)
                                                            <tr>
                                                                <div hidden>
                                                                    @if ($totalCarencia != 0)
                                                                        {{ $total = $disciplina->total / $totalCarencia }}
                                                                    @else
                                                                        {{ $total = 0 }}
                                                                    @endif
                                                                    {{ $new_total = $total * 100 }}
                                                                </div>
                                                                <td class="text-muted subheader">
                                                                    {{ $disciplina->disciplina }}</td>
                                                                <td>
                                                                    {{ $new_total = number_format($new_total, 1) }}%
                                                                </td>
                                                                <td class="w-100 px-0">
                                                                    <div class="progress progress-md mx-4">
                                                                        <div class="progress-bar bg-primary"
                                                                            role="progressbar"
                                                                            style="width: {{ $new_total }}%"
                                                                            aria-valuenow="{{ $disciplina->total }}"
                                                                            aria-valuemin="0"
                                                                            aria-valuemax="{{ $totalCarencia }}">
                                                                        </div>
                                                                </td>
                                                                <td>
                                                                    <h5 class="font-weight-bold mb-0">
                                                                        {{ $disciplina->total }} h</h5>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="row">
                                    <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                        <div class="ml-xl-4 mt-3">
                                            <p class="card-title ">TOP 5 - UEE's com carência</p>
                                            <p class="mb-2 mb-xl-0">Top 5 Unidades Escolares com maior quantidade de
                                                carência lançada no sistema do SCP</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-9">
                                        <div class="row">
                                            <div class="col-md-12 border-right">
                                                <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                    <table class="table table-borderless report-table">
                                                        @foreach ($uees as $uee)
                                                            <tr>
                                                                <div hidden>
                                                                    @if ($totalCarencia != 0)
                                                                        {{ $totalUEE = $uee->total / $totalCarencia }}
                                                                    @else
                                                                        {{ $totalUEE = 0 }}
                                                                    @endif
                                                                    {{ $new_totalUEE = $totalUEE * 100 }}
                                                                </div>

                                                                @if ($uee->nte >= 10)
                                                                    <td class="text-muted subheader">NTE
                                                                        {{ $uee->nte }} - {{ $uee->municipio }} -
                                                                        {{ $uee->unidade_escolar }} - {{ $uee->cod_ue }}
                                                                    </td>
                                                                @endif
                                                                @if ($uee->nte < 10)
                                                                    <td class="text-muted">NTE 0{{ $uee->nte }} -
                                                                        {{ $uee->municipio }} -
                                                                        {{ $uee->unidade_escolar }} - {{ $uee->cod_ue }}
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                    {{ $new_totalUEE = number_format($new_totalUEE, 1) }}%
                                                                </td>
                                                                <td class="w-100 px-0">
                                                                    <div class="progress progress-md mx-4">
                                                                        <div class="progress-bar bg-primary"
                                                                            role="progressbar"
                                                                            style="width: {{ $new_totalUEE }}%"
                                                                            aria-valuenow="70" aria-valuemin="0"
                                                                            aria-valuemax="100"></div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <h5 class="font-weight-bold mb-0">{{ $uee->total }}h
                                                                    </h5>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#detailedReports" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#detailedReports" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-title">Advanced Table</p>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="example" class="display expandable-table" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Quote#</th>
                                                            <th>Product</th>
                                                            <th>Business type</th>
                                                            <th>Policy holder</th>
                                                            <th>Premium</th>
                                                            <th>Status</th>
                                                            <th>Updated at</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

    <script>
        // Altera o ano de referencia do sistema para consultas de acordo com o ano escolhido
        const selectElement = document.getElementById("ref_year");

        selectElement.addEventListener("change", function() {
            let selectedValue = selectElement.value;

            Swal.fire({
                title: 'Tem certeza?',
                text: "Você esta prestes a mudar o ano de referência do sistema!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Não, cancelar!',
                confirmButtonText: 'Sim, alterar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envia o valor selecionado para o backend
                    $.post('/atualizar_ano_ref/' + selectedValue, function(response) {

                        window.location.reload();

                    })
                }
            })
        });
    </script>

@endsection
