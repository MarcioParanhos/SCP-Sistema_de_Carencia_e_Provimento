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
        </div>
        <div class="row gap_mobile">
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
                            Pendente: <strong>{{ $pendingUnitsSedes }}</strong> -
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
                            Pendente: <strong>{{ $pendingUnitsAnexos }}</strong> -
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
