<div id="cards_system_unit" class="col-md-12 grid-margin transparent">
    <div class="card p-4 shadow bg-primary">
        <div class="mb-2 row gap_mobile">
            <div class="col-md-3 mb-2 stretch-card transparent">
                <div class="card border-0 shadow-sm rounded h-100">
                    <div class="card-body p-3">
                        @php
                            $totalReal = $carenciasBasicaReal + $carenciasProfiReal;
                            $pctBasica = $totalReal ? round($carenciasBasicaReal * 100 / $totalReal) : 0;
                            $pctProfi = $totalReal ? 100 - $pctBasica : 0;
                        @endphp

                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <small class="text-muted text-uppercase">VAGA REAL</small>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h4 class="mb-0 fw-bold">
                                        {{ number_format($totalReal, 0, ',', '.') }} <small class="text-muted" style="font-size:0.7rem;">h</small>
                                    </h4>
                                </div>
                            </div>

                            <!-- Tabler user icon -->
                            <div class="ms-2 text-primary" aria-hidden="true" style="font-size:1.4rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="7" r="4" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                            </div>
                        </div>

                        <div class="mb-2" style="height:1px; background:rgba(0,0,0,0.06)"></div>

                        <div class="d-flex gap-5" style="gap: 20px !important">
                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">BÁSICA</small>
                                    <strong>{{ number_format($carenciasBasicaReal, 0, ',', '.') }} h</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $pctBasica }}%;" aria-valuenow="{{ $pctBasica }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $pctBasica }}% do total</small>
                            </div>

                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">PROFISS.</small>
                                    <strong>{{ number_format($carenciasProfiReal, 0, ',', '.') }} h</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pctProfi }}%;" aria-valuenow="{{ $pctProfi }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $pctProfi }}% do total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2 stretch-card transparent">
                <div class="card border-0 shadow-sm rounded h-100">
                    <div class="card-body p-3">
                        @php
                            $totalTemp = $carenciasBasicaTemp + $carenciasProfiTemp;
                            $pctBasicaTemp = $totalTemp ? round($carenciasBasicaTemp * 100 / $totalTemp) : 0;
                            $pctProfiTemp = $totalTemp ? 100 - $pctBasicaTemp : 0;
                        @endphp

                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <small class="text-muted text-uppercase">VAGA TEMPORÁRIA</small>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h4 class="mb-0 fw-bold">
                                        {{ number_format($totalTemp, 0, ',', '.') }} <small class="text-muted" style="font-size:0.7rem;">h</small>
                                    </h4>
                                </div>
                            </div>

                            <div class="ms-2 text-info" aria-hidden="true" style="font-size:1.4rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="7" />
                                    <path d="M12 9v3l2 1" />
                                </svg>
                            </div>
                        </div>

                        <div class="mb-2" style="height:1px; background:rgba(0,0,0,0.06)"></div>

                        <div class="d-flex gap-5" style="gap: 20px !important">
                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">BÁSICA</small>
                                    <strong>{{ number_format($carenciasBasicaTemp, 0, ',', '.') }} h</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $pctBasicaTemp }}%;" aria-valuenow="{{ $pctBasicaTemp }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $pctBasicaTemp }}% do total</small>
                            </div>

                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">PROFISS.</small>
                                    <strong>{{ number_format($carenciasProfiTemp, 0, ',', '.') }} h</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pctProfiTemp }}%;" aria-valuenow="{{ $pctProfiTemp }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $pctProfiTemp }}% do total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2 stretch-card transparent">
                <div class="card border-0 shadow-sm rounded h-100">
                    <div class="card-body p-3">
                        @php
                            $totalEdEsp = $carenciasRealEdEspecial + $carenciasTempEdEspecial;
                            $pctRealEdEsp = $totalEdEsp ? round($carenciasRealEdEspecial * 100 / $totalEdEsp) : 0;
                            $pctTempEdEsp = $totalEdEsp ? 100 - $pctRealEdEsp : 0;
                        @endphp

                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <small class="text-muted text-uppercase">VAGA ED. ESPECIAL</small>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h4 class="mb-0 fw-bold">
                                        {{ number_format($totalEdEsp, 0, ',', '.') }} <small class="text-muted" style="font-size:0.7rem;">h</small>
                                    </h4>
                                </div>
                            </div>

                            <div class="ms-2 text-warning" aria-hidden="true" style="font-size:1.4rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 17.75l-6.172 3.245 1.179-6.873L2 9.745l6.914-1.005L12 2.5l3.086 6.24L22 9.745l-5.007 4.377 1.179 6.873z" />
                                </svg>
                            </div>
                        </div>

                        <div class="mb-2" style="height:1px; background:rgba(0,0,0,0.06)"></div>

                        <div class="d-flex gap-5" style="gap: 20px !important">
                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">REAL</small>
                                    <strong>{{ number_format($carenciasRealEdEspecial, 0, ',', '.') }} h</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $pctRealEdEsp }}%;" aria-valuenow="{{ $pctRealEdEsp }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $pctRealEdEsp }}% do total</small>
                            </div>

                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">TEMP.</small>
                                    <strong>{{ number_format($carenciasTempEdEspecial, 0, ',', '.') }} h</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pctTempEdEsp }}%;" aria-valuenow="{{ $pctTempEdEsp }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $pctTempEdEsp }}% do total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2 stretch-card transparent">
                <div class="card border-0 shadow-sm rounded h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <small class="text-muted text-uppercase">VAGA EMITEC</small>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h4 class="mb-0 fw-bold">
                                        {{ number_format($vagaEmitec, 0, ',', '.') }} <small class="text-muted" style="font-size:0.7rem;">h</small>
                                    </h4>
                                </div>
                            </div>

                            <div class="ms-2 text-secondary" aria-hidden="true" style="font-size:1.4rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 21v-13a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v13" />
                                    <path d="M7 21v-6a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v6" />
                                    <path d="M7 10h.01" />
                                    <path d="M12 10h.01" />
                                    <path d="M17 10h.01" />
                                </svg>
                            </div>
                        </div>

                        <div class="mb-2" style="height:1px; background:rgba(0,0,0,0.06)"></div>

                        <div class="d-flex gap-5" style="gap: 20px !important">
                            <div class="flex-fill text-center">
                                <div class="d-flex flex-column justify-content-center align-middle">
                                    <p class="text-center subheader">QTD. SERVIDORES EMITEC</p>
                                    <p class="font_sub-title text-center mb-1">
                                        {{ number_format($vagaEmitec / 20, 0, ',', '.') }}
                                    </p>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2 row gap_mobile">
            <div class="col-md-6 mb-2 stretch-card transparent">
                <div class="card border-0 shadow-sm rounded h-100">
                    <div class="card-body p-3">
                        @php
                            $totalProv = $provimentosReal + $provimentosTemp;
                            $pctProvReal = $totalProv ? round($provimentosReal * 100 / $totalProv) : 0;
                            $pctProvTemp = $totalProv ? 100 - $pctProvReal : 0;
                        @endphp

                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <small class="text-muted text-uppercase">TOTAL DE PROVIMENTO</small>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h4 class="mb-0 fw-bold">
                                        {{ number_format($totalProv, 0, ',', '.') }} <small class="text-muted" style="font-size:0.7rem;">h</small>
                                    </h4>
                                </div>
                            </div>

                            <div class="ms-2 text-success" aria-hidden="true" style="font-size:1.4rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M9 12l2 2l4 -4" />
                                    <path d="M12 20a8 8 0 1 0 0 -16a8 8 0 0 0 0 16z" />
                                </svg>
                            </div>
                        </div>

                        <div class="mb-2" style="height:1px; background:rgba(0,0,0,0.06)"></div>

                        <div class="d-flex gap-5" style="gap: 20px !important">
                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">REAL</small>
                                    <strong>{{ number_format($provimentosReal, 0, ',', '.') }} h</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $pctProvReal }}%;" aria-valuenow="{{ $pctProvReal }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $pctProvReal }}% do total</small>
                            </div>

                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">TEMP.</small>
                                    <strong>{{ number_format($provimentosTemp, 0, ',', '.') }} h</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pctProvTemp }}%;" aria-valuenow="{{ $pctProvTemp }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $pctProvTemp }}% do total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2 stretch-card transparent">
                <div class="card border-0 shadow-sm rounded h-100">
                    <div class="card-body p-3">
                        @php
                            $totalProvTramite = $provimentosTramiteReal + $provimentosTramiteTemp;
                            $pctTrReal = $totalProvTramite ? round($provimentosTramiteReal * 100 / $totalProvTramite) : 0;
                            $pctTrTemp = $totalProvTramite ? 100 - $pctTrReal : 0;
                        @endphp

                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <small class="text-muted text-uppercase">PROV. EM TRÂMITE</small>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h4 class="mb-0 fw-bold">
                                        {{ number_format($totalProvTramite, 0, ',', '.') }} <small class="text-muted" style="font-size:0.7rem;">h</small>
                                    </h4>
                                </div>
                            </div>

                            <div class="ms-2 text-warning" aria-hidden="true" style="font-size:1.4rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M6 2h12" />
                                    <path d="M6 22h12" />
                                    <path d="M7 7a5 5 0 0 0 10 0" />
                                    <path d="M7 17a5 5 0 0 1 10 0" />
                                </svg>
                            </div>
                        </div>

                        <div class="mb-2" style="height:1px; background:rgba(0,0,0,0.06)"></div>

                        <div class="d-flex gap-5" style="gap: 20px !important">
                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">REAL</small>
                                    <strong>{{ number_format($provimentosTramiteReal, 0, ',', '.') }} h</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $pctTrReal }}%;" aria-valuenow="{{ $pctTrReal }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $pctTrReal }}% do total</small>
                            </div>

                            <div class="flex-fill">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">TEMP.</small>
                                    <strong>{{ number_format($provimentosTramiteTemp, 0, ',', '.') }} h</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pctTrTemp }}%;" aria-valuenow="{{ $pctTrTemp }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $pctTrTemp }}% do total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gap_mobile">
            <div class="col-md-4 mb-2 stretch-card transparent">
                <div class="card border-0 shadow-sm rounded h-100">
                    <div class="card-body p-3">
                        <p class="text-center text-dark mb-2 subheader border-bottom" style="font-size: 14px; opacity: 0.9;">
                            Total Geral de SEDES: <strong>{{ $totalUnitsSedesAll }}</strong>
                        </p>
                        <p class="text-center font_title  subheader">
                            <strong>UNIDADES SEDE HOMOLOGADAS</strong>
                        </p>

                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <h3 class="mb-0 me-2">{{ number_format($totalUnitsSedes, 0, ',', '.') }}</h3>
                            <small class="text-muted">- {{ number_format($percentSedes, 1, ',', '') }}%</small>
                        </div>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percentSedes }}%;" aria-valuenow="{{ $percentSedes }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <p class="text-center text-primary mb-0 mt-2" style="font-size: 13px;">
                            Pendente: <strong>{{ $pendingUnitsSedes }}</strong> -
                            {{ number_format($percentPendingSedes, 1, ',', '') }}%
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2 stretch-card transparent">
                <div class="card border-0 shadow-sm rounded h-100">
                    <div class="card-body p-3">
                        <p class="text-center text-dark mb-2 subheader border-bottom" style="font-size: 14px; opacity: 0.9;">
                            Total Geral de Anexos: <strong>{{ $totalUnitsAnexosAll }}</strong>
                        </p>
                        <p class="text-center font_title  subheader">
                            <strong>ANEXOS HOMOLOGADOS</strong>
                        </p>

                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <h3 class="mb-0 me-2">{{ number_format($totalUnitsAnexos, 0, ',', '.') }}</h3>
                            <small class="text-muted">- {{ number_format($percentAnexos, 1, ',', '') }}%</small>
                        </div>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percentAnexos }}%;" aria-valuenow="{{ $percentAnexos }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <p class="text-center text-primary mb-0 mt-2" style="font-size: 13px;">
                            Pendente: <strong>{{ $pendingUnitsAnexos }}</strong> -
                            {{ number_format($percentPendingAnexos, 1, ',', '') }}%
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2 stretch-card transparent">
                <div class="card border-0 shadow-sm rounded h-100">
                    <div class="card-body p-3">
                        <p class="text-center text-dark mb-2 subheader border-bottom" style="font-size: 14px; opacity: 0.9;">
                            Total Geral de Cemits: <strong>{{ $totalUnitsCemitAll }}</strong>
                        </p>
                        <p class="text-center font_title  subheader">
                            <strong>CEMIT/EMITEC HOMOLOGADOS</strong>
                        </p>

                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <h3 class="mb-0 me-2">{{ number_format($totalUnitsCemits, 0, ',', '.') }}</h3>
                            <small class="text-muted">- {{ number_format($percentCemits, 1, ',', '') }}%</small>
                        </div>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percentCemits }}%;" aria-valuenow="{{ $percentCemits }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <p class="text-center text-primary mb-0 mt-2" style="font-size: 13px;">
                            Pendente: <strong class="">{{ $pendingUnitsCemits }}</strong> -
                            {{ number_format($percentPendingCemits, 1, ',', '') }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
