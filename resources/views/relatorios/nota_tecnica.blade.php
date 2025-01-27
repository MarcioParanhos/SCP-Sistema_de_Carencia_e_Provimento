<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>NECESSIDADE DE PROFESSOR - {{$ueeDetails->unidade_escolar}}</title>

    <style>
        .sintese {
            width: 60% !important;
            display: flex !important;
            justify-content: end !important;
        }

        .sintese-content {
            display: flex;
            justify-content: end;
        }

        .text-subtitle {
            padding-left: 10px;
            line-height: 0.5 !important;
        }

        .card-body {
            line-height: 0.5 !important;
            margin: 0;
            padding: 10px;
            padding-left: 20px;
        }

        .card-body P {
            font-size: 14px !important;
        }

        body {
            padding: 5px !important;
        }

        h5 {
            font-size: 14px !important;
        }

        .logos {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .img-logo {
            width: 200px;
        }

        @media print {
            .card {
                color: #232323 !important;
            }

            .card-body P {
                font-size: 12px !important;
            }

            .text-subtitle p {
                font-size: 12px;
            }

            .table {
                font-size: 12px !important;
            }

            .footer {
                font-size: 12px !important;
            }

            td {
                line-height: 1 !important;
            }
        }
    </style>

</head>

<body>
    <div class="mt-4 container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="logos">
                    <div class="text-subtitle">
                        <p>SECRETARIA DA EDUCAÇÃO DO ESTADO DA BAHIA</p>
                        <p>SUPERINTENDÊNCIA DE RECURSOS HUMANOS DA EDUCAÇÃO - SUDEPE</p>
                        <p>DIRETORIA DE PLANEJAMENTO E DESENVOLVIMENTO DE PESSOAL - DIPES</p>
                        <p>COORDENAÇÃO DE PROGRAMAÇÂO ESCOLAR - CPG</p>
                        <p>COORDENAÇÃO DE PROVIMENTO E MOVIMENTO- CPM</p>
                    </div>
                    <img class="img-logo" src="../images/SCP.png" alt="people">
                    <!-- <img class="img-logo" src="../images/educacao_logo.png" alt=""> -->
                </div>
                <h5 class="mb-5 title_relatorio text-center">CONTROLE DE NECESSIDADE DE PROFESSOR</h5>
                <div class="mb-4 card text-white bg-info">
                    <h5 class="card-header">
                        @if( $ueeDetails->situacao === "HOMOLOGADA")
                        {{ $ueeDetails->unidade_escolar }} - UNIDADE {{ $ueeDetails->situacao }}
                        @endif
                        @if ( $ueeDetails->situacao === "PENDENTE")
                        {{ $ueeDetails->unidade_escolar }} - UNIDADE PENDENTE HOMOLOGAÇÃO
                        @endif
                    </h5>
                    <div class="d-flex justify-content-between card-body">
                        <div>
                            @if ($ueeDetails->nte >= 10)
                            <p class="card-text"><strong>NTE - {{ $ueeDetails->nte }}</strong></p>
                            @endif
                            @if ($ueeDetails->nte < 10) <p class="card-text"><strong>NTE - 0{{ $ueeDetails->nte }}</strong></p>
                                @endif
                                <p class="card-text"><strong>MUNICIPIO - {{ $ueeDetails->municipio }}</strong></p>
                                <p class="card-text"><strong>CODIGO UEE - {{ $ueeDetails->cod_unidade }}</strong></p>
                        </div>
                        <div>
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="col">CARÊNCIA LANÇADA</th>
                                        <td class="text-center"><span id="carencial_total"></span>h</td>
                                        <td class="text-center">100.0%</td>
                                    </tr>
                                    <tr>
                                        <th scope="col">SUPRIDO</th>
                                        <td class="text-center"><span id="provimento_total"></span>h</td>
                                        <td class="text-center"><span id="provimento_porc"></span>%</td>
                                    </tr>
                                    <tr>
                                        <th scope="col">PENDENTE</th>
                                        <td class="text-center"><span id="pendente_total"></span>h</td>
                                        <td class="text-center"><span id="pendente_porc"></span>%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="card-footer">
                        Card footer
                    </div> -->
                </div>
                <div class=" mb-0 card">
                    <div class="pt-2 mb-0 d-flex justify-content-center card-title">
                        <h5>PROVIMENTOS</h5>
                    </div>
                    <div class="card-body mb-0">
                        <h5>SUPRIDO</h5>
                        <table class="table table-sm table-bordered">
                            <div hidden>
                                {{ $provimento_matutino = 0 }}
                                {{ $provimento_vespertino = 0 }}
                                {{ $provimento_noturno = 0 }}
                                {{ $provimento_total = 0 }}
                                {{ $provimento_total_geral = 0 }}
                            </div>
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle; width: 48%;">DISCIPLINA</th>
                                    <th class="text-center" style="vertical-align: middle; width: 30%;">MOVIMENTAÇÃO</th>
                                    <th class="text-center">MAT</th>
                                    <th class="text-center">VESP</th>
                                    <th class="text-center">NOT</th>
                                    <th class="text-center">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ueeDetailProvimentoSuprido as $provimento)
                                <tr>
                                    <td>{{ $provimento->disciplina }}</td>
                                    <td class="text-center">{{ $provimento->tipo_movimentacao }}</td>
                                    <td class="text-center">{{ $provimento->total_suprimento_matutino}}</td>
                                    <td class="text-center">{{ $provimento->total_suprimento_vespertino  }}</td>
                                    <td class="text-center">{{ $provimento->total_suprimento_noturno }}</td>
                                    <td class="text-center">{{ $provimento->total_suprimento }}</td>
                                    <div hidden>
                                        {{ $provimento_matutino = $provimento_matutino + $provimento->total_suprimento_matutino}}
                                        {{ $provimento_vespertino = $provimento_vespertino + $provimento->total_suprimento_vespertino}}
                                        {{ $provimento_noturno = $provimento_noturno + $provimento->total_suprimento_noturno}}
                                        {{ $provimento_total = $provimento_total + $provimento->total_suprimento}}
                                        {{ $provimento_total_geral = $provimento_total}}
                                    </div>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">Sem Provimento.</td>
                                </tr>
                                @endforelse
                                <td><strong>TOTAL DE PROVIMENTOS</strong></td>
                                <td></td>
                                <td class="text-center"><strong>{{ $provimento_matutino }}</strong></td>
                                <td class="text-center"><strong>{{ $provimento_vespertino }}</strong></td>
                                <td class="text-center"><strong>{{ $provimento_noturno }}</strong></td>
                                <td class="text-center"><strong>{{ $provimento_total }}</strong></td>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <h5>EM TRÂMITE</h5>
                        <table class="table table-sm table-bordered">
                            <div hidden>
                                {{ $provimento_matutino = 0 }}
                                {{ $provimento_vespertino = 0 }}
                                {{ $provimento_noturno = 0 }}
                                {{ $provimento_total = 0 }}
                            </div>
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle; width: 48%;">DISCIPLINA</th>
                                    <th class="text-center" style="vertical-align: middle; width: 30%;">MOVIMENTAÇÃO</th>
                                    <th class="text-center">MAT</th>
                                    <th class="text-center">VESP</th>
                                    <th class="text-center">NOT</th>
                                    <th class="text-center">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ueeDetailProvimentoTramite as $provimento)
                                <tr>
                                    <td>{{ $provimento->disciplina }}</td>
                                    <td class="text-center">{{ $provimento->tipo_movimentacao }}</td>
                                    <td class="text-center">{{ $provimento->total_suprimento_matutino}}</td>
                                    <td class="text-center">{{ $provimento->total_suprimento_vespertino  }}</td>
                                    <td class="text-center">{{ $provimento->total_suprimento_noturno }}</td>
                                    <td class="text-center">{{ $provimento->total_suprimento }}</td>
                                    <div hidden>
                                        {{ $provimento_matutino = $provimento_matutino + $provimento->total_suprimento_matutino}}
                                        {{ $provimento_vespertino = $provimento_vespertino + $provimento->total_suprimento_vespertino}}
                                        {{ $provimento_noturno = $provimento_noturno + $provimento->total_suprimento_noturno}}
                                        {{ $provimento_total = $provimento_total + $provimento->total_suprimento}}
                                    </div>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">Sem Provimento.</td>
                                </tr>
                                @endforelse
                                <td><strong>TOTAL DE PROVIMENTOS EM TRÂMITE</strong></td>
                                <td></td>
                                <td class="text-center"><strong>{{ $provimento_matutino }}</strong></td>
                                <td class="text-center"><strong>{{ $provimento_vespertino }}</strong></td>
                                <td class="text-center"><strong>{{ $provimento_noturno }}</strong></td>
                                <td class="text-center"><strong>{{ $provimento_total }}</strong></td>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-1">
                    <div class="pt-2 mb-0 d-flex justify-content-center card-title">
                        <h5>VAGAS A SUPRIR</h5>
                    </div>
                    <div class="card-body">
                        <h5>REAL</h5>
                        <table class="table table-sm table-bordered">
                            <div hidden>
                                {{ $carencia_matutino = 0 }}
                                {{ $carencia_vespertino = 0 }}
                                {{ $carencia_noturno = 0 }}
                                {{ $carencia_total = 0 }}
                            </div>
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle; width: 48%;">DISCIPLINA</th>
                                    <th style="vertical-align: middle; width: 30%;">ÁREA</th>
                                    <th class="text-center">MAT</th>
                                    <th class="text-center">VESP</th>
                                    <th class="text-center">NOT</th>
                                    <th class="text-center">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ueeDetailsCarenciaReal as $carencia)
                                <tr>
                                    <td>{{ $carencia->disciplina }}</td>
                                    <td>{{ $carencia->area }}</td>
                                    <td class="text-center">{{ $carencia->total_carencia_matutino  }}</td>
                                    <td class="text-center">{{ $carencia->total_carencia_vespertino }}</td>
                                    <td class="text-center">{{ $carencia->total_carencia_noturno }}</td>
                                    <td class="text-center">{{ $carencia->total_carencia }}</td>
                                    <div hidden>
                                        {{ $carencia_matutino = $carencia_matutino + $carencia->total_carencia_matutino }}
                                        {{ $carencia_vespertino = $carencia_vespertino + $carencia->total_carencia_vespertino }}
                                        {{ $carencia_noturno = $carencia_noturno + $carencia->total_carencia_noturno}}
                                        {{ $carencia_total = $carencia_total + $carencia->total_carencia }}
                                    </div>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">Sem Carência.</td>
                                </tr>
                                @endforelse
                                <td><strong>TOTAL DE CARÊNCIA REAL</strong></td>
                                <td></td>
                                <td class="text-center"><strong>{{ $carencia_matutino }}</strong></td>
                                <td class="text-center"><strong>{{ $carencia_vespertino }}</strong></td>
                                <td class="text-center"><strong>{{ $carencia_noturno }}</strong></td>
                                <td class="text-center"><strong>{{ $carencia_total }}</strong></td>
                                <div hidden>
                                    {{ $carencia_total_real = $carencia_total }}
                                </div>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <h5>
                            TEMPORÁRIA
                        </h5>
                        <table class="table table-sm table-bordered">
                            <div hidden>
                                {{ $carencia_matutino = 0 }}
                                {{ $carencia_vespertino = 0 }}
                                {{ $carencia_noturno = 0 }}
                                {{ $carencia_total = 0 }}
                            </div>
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle; width: 48%;">DISCIPLINA</th>
                                    <th style="vertical-align: middle; width: 30%;">ÁREA</th>
                                    <th class="text-center">MAT</th>
                                    <th class="text-center">VESP</th>
                                    <th class="text-center">NOT</th>
                                    <th class="text-center">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ueeDetailsCarenciaTemp as $carencia)
                                <tr>
                                    <td>{{ $carencia->disciplina }}</td>
                                    <td>{{ $carencia->area }}</td>
                                    <td class="text-center">{{ $carencia->total_carencia_matutino }}</td>
                                    <td class="text-center">{{ $carencia->total_carencia_vespertino }}</td>
                                    <td class="text-center">{{ $carencia->total_carencia_noturno }}</td>
                                    <td class="text-center">{{ $carencia->total_carencia}}</td>
                                    <div hidden>
                                        {{ $carencia_matutino = $carencia_matutino + $carencia->total_carencia_matutino }}
                                        {{ $carencia_vespertino = $carencia_vespertino + $carencia->total_carencia_vespertino }}
                                        {{ $carencia_noturno = $carencia_noturno + $carencia->total_carencia_noturno}}
                                        {{ $carencia_total = $carencia_total + $carencia->total_carencia }}
                                    </div>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">Sem Carência.</td>
                                </tr>
                                @endforelse
                                <td><strong>TOTAL DE CARÊNCIA TEMPORÁRIA</strong></td>
                                <td></td>
                                <td class="text-center"><strong>{{ $carencia_matutino }}</strong></td>
                                <td class="text-center"><strong>{{ $carencia_vespertino }}</strong></td>
                                <td class="text-center"><strong>{{ $carencia_noturno }}</strong></td>
                                <td class="text-center"><strong>{{ $carencia_total }}</strong></td>
                                <div hidden>
                                    {{ $carencia_total_temp = $carencia_total }}
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="sintese-content">
                    <div class="mt-1 card sintese">
                        <div class="pt-2 mb-0 d-flex justify-content-center card-title">
                            <h5>SÍNTESE</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">CARÊNCIA</th>
                                        <th class="text-center" scope="col">QTD. HORAS</th>
                                        <th class="text-center" scope="col">QTD. PROF REDA 20h</th>
                                        <th class="text-center"></th>
                                        <th class="text-center" scope="col">QTD. PROF EFETIVO 20h</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>REAL</td>
                                        <td class="text-center">{{$carencia_total_real}}</td>
                                        <td class="text-center">{{ number_format($carencia_total_real/16, 1, ',', '.') }}</td>
                                        <th class="text-center">OU</th>
                                        <td class="text-center">{{ number_format($carencia_total_real/13, 1, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>TEMPORARIA</td>
                                        <td class="text-center">{{$carencia_total_temp}}</td>
                                        <td class="text-center">{{ number_format($carencia_total_temp/16, 1, ',', '.') }}
                                        <td class="text-center"></td>
                                        <td class="text-center">{{ number_format($carencia_total_temp/13, 1, ',', '.') }}
                                    </tr>
                                </tbody>
                                <div hidden>
                                    {{ $provimentoTotal = $provimento_total_geral + $provimento_total}}
                                    {{ $totalGeral = $provimentoTotal + $carencia_total_temp + $carencia_total_real}}
                                    @if ($totalGeral != 0)
                                        @php
                                            $porcentagem = ($provimentoTotal / $totalGeral) * 100;
                                        @endphp
                                    @else
                                        @php
                                            $porcentagem = 0;
                                        @endphp
                                    @endif
                                    {{ $percentual_formatado = number_format($porcentagem, 1);}}
                                    <input id="totalGeral" value="{{ $totalGeral }}" hidden>
                                    <input id="percentual_formatado" value="{{ $percentual_formatado }}" hidden>
                                    <input id="provimentoTotal" value="{{ $provimentoTotal }}" hidden>
                                </div>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer d-flex justify-content-end mt-4 mr-3">
        <span>
            <script>
                var data = new Date();

                var dia = data.getDate().toString().padStart(2, '0');
                var mes = (data.getMonth() + 1).toString().padStart(2, '0');
                var ano = data.getFullYear();

                var dataFormatada = dia + '/' + mes + '/' + ano;

                document.write(dataFormatada);
            </script>
        </span>
    </div>

    <script>
        const carencial_total = document.getElementById("carencial_total")
        const provimento_total = document.getElementById("provimento_total")
        const provimentoTotal = document.getElementById("provimentoTotal")
        const percentual_formatado = document.getElementById("percentual_formatado")
        const provimento_porc = document.getElementById("provimento_porc")
        const totalGeral = document.getElementById("totalGeral")
        const pendente_total = document.getElementById("pendente_total")
        const pendente_porc = document.getElementById("pendente_porc")

        carencial_total.innerHTML = totalGeral.value
        provimento_total.innerHTML = provimentoTotal.value
        provimento_porc.innerHTML = percentual_formatado.value
        pendente_total.innerHTML = totalGeral.value - provimentoTotal.value
        pendente_porc.innerHTML = 100 - percentual_formatado.value
    </script>

    <script>
        // Script para imprimir automaticamente quando a página for carregada
        window.print();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.min.js"></script>

</body>

</html>