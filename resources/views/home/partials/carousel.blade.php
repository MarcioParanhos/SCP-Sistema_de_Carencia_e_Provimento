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
                                                                <div class="progress-bar bg-primary" role="progressbar"
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
                                                                <div class="progress-bar bg-primary" role="progressbar"
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
