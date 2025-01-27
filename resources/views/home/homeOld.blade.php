@extends('layouts.main')

@section('title', 'SCP- Home')

@section('content')

<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Visão geral
                </div>
                <h2 class="page-title UperCase">

                </h2>
            </div>
            <!-- Page title actions -->
            <!-- <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Create new report
                    </a>
                    <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-report" aria-label="Create new report">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                    </a>
                </div>
            </div> -->
        </div>
    </div>
</div>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row ">
            <div id="teste" class="col-md-5 grid-margin stretch-card">
                <div class="shadow card tale-bg">
                    <div class="card-people text-center">
                        <img class="img-logo p-1" src="images/SCP.png" alt="Logo Sistema">
                    </div>
                </div>
            </div>
            <div class=" col-md-7 grid-margin transparent">
                <div class="mb-2 row gap_mobile">
                    <div class="col-md-4 mb-2 stretch-card transparent">
                        <div class="card bg-blue">
                            <div class="card-body">
                                <h5 class="text-center font_title text-white mb-2 "><strong>VAGA REAL - {{ number_format($carenciasBasicaReal+$carenciasProfiReal, 0, ',', '.') }} h </strong></h5>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-center text-white subheader">BÁSICA</p>
                                        <p class="font_sub-title text-white">{{ number_format($carenciasBasicaReal, 0, ',', '.') }} h</p>
                                    </div>
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-center subheader text-white">PROFISS.</p>
                                        <p class="text-center text-white font_sub-title">{{ number_format($carenciasProfiReal, 0, ',', '.') }} h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 stretch-card transparent">
                        <div class="card bg-blue">
                            <div class="card-body">
                                <h5 class="text-center text-white font_title mb-2 "><strong>VAGA TEMPORÁRIA - {{ number_format($carenciasBasicaTemp+$carenciasProfiTemp, 0, ',', '.') }} h</strong></h5>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-center text-white subheader">BÁSICA</p>
                                        <p class="text-white font_sub-title">{{ number_format($carenciasBasicaTemp, 0, ',', '.') }} h</p>
                                    </div>
                                    <div class="mt-2 d-flex flex-column  justify-content-center align-middle">
                                        <p class="text-white text-center subheader">PROFISS.</p>
                                        <p class="text-white font_sub-title">{{ number_format($carenciasProfiTemp, 0, ',', '.') }} h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 stretch-card transparent">
                        <div class="card bg-blue">
                            <div class="card-body">
                                <h5 class="text-center text-white font_title mb-2 "><strong>VAGA ED. ESPECIAL - {{ number_format($carenciasRealEdEspecial+$carenciasTempEdEspecial, 0, ',', '.') }} h</strong> <i class="ti-stats-up"></i></h5>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-white text-center subheader">REAL</p>
                                        <p class="text-white font_sub-title">{{ number_format($carenciasRealEdEspecial, 0, ',', '.') }} h</p>
                                    </div>
                                    <div class="mt-2 d-flex flex-column  justify-content-center align-middle">
                                        <p class="text-white text-center subheader">TEMP.</p>
                                        <p class="text-white font_sub-title">{{ number_format($carenciasTempEdEspecial, 0, ',', '.') }} h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-2 row gap_mobile">
                    <div class="col-md-6 mb-2 stretch-card transparent">
                        <div class="card bg-blue">
                            <div class="card-body">
                                <h5 class="text-center text-white font_title mb-2 "> <strong>TOTAL DE PROVIMENTO - {{ number_format($provimentosReal+$provimentosTemp, 0, ',', '.') }} h</strong></h5>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-white text-center subheader">REAL</p>
                                        <p class="text-white font_sub-title">{{ number_format($provimentosReal, 0, ',', '.') }} h</p>
                                    </div>
                                    <div class="mt-2 d-flex flex-column  justify-content-center align-middle">
                                        <p class="text-white text-center subheader">TEMP.</p>
                                       <p class="text-white font_sub-title">{{ number_format($provimentosTemp, 0, ',', '.') }} h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2 stretch-card transparent">
                        <div class="card bg-blue">
                            <div class="card-body">
                                <h5 class="text-white text-center font_title mb-2 "><i class="fas fa-user-clock"></i> <strong>PROV. EM TRÂMITE - {{ number_format($provimentosTramiteReal+$provimentosTramiteTemp, 0, ',', '.') }} h</strong></h5>
                                <div class="d-flex justify-content-around" style="border-top: 2px solid #fbf8f3;">
                                    <div class="mt-2 d-flex flex-column justify-content-center align-middle">
                                        <p class="text-white text-center subheader">REAL</p>
                                        <p class="text-white font_sub-title">{{ number_format($provimentosTramiteReal, 0, ',', '.') }} h</p>
                                    </div>
                                    <div class="mt-2 d-flex flex-column  justify-content-center align-middle">
                                        <p class="text-white text-center subheader">TEMP.</p>
                                       <p class="text-white font_sub-title">{{ number_format($provimentosTramiteTemp, 0, ',', '.') }} h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gap_mobile">
                    <div class="col-md-4 mb-2 stretch-card transparent">
                        <div class="card bg-blue">
                            <div class="card-body">
                                <h3 class="text-white text-center font_title mb-4 "><strong>UNIDADES SEDE HOMOLOGADAS</strong></h3>
                                <p class="text-white text-center font_sub-title fs-30">{{ number_format($totalUnitsSedes, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-2 stretch-card transparent">
                        <div class="card bg-blue">
                            <div class="card-body">
                                <h3 class="text-white text-center font_title mb-4"><strong>ANEXOS HOMOLOGADOS</strong></h3>
                                <p class="text-white text-center font_sub-title fs-30">{{ number_format($totalUnitsAnexos, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 stretch-card transparent">
                        <div class="card bg-blue">
                            <div class="card-body">
                                <h3 class="text-white text-center font_title mb-4 "><strong>CEMIT/EMITEC HOMOLOGADOS</strong></h3>
                                <p class="text-white text-center  font_sub-title fs-30">{{ number_format($totalUnitsCemits, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    const active_home = document.getElementById('active_home')
    active_home.classList.add('active')
</script>
@endsection