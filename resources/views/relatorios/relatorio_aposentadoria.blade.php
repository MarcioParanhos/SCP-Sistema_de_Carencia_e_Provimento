<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="shortcut icon" href="../../images/Faviconn.png" />
    <title>Processo Tramitado</title>

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
                        <p>COORDENAÇÃO DE AFASTAMENTO DEFINITIVO - CAD</p>
                        <p>COORDENAÇÃO DE PROGRAMAÇÂO ESCOLAR - CPG</p>
                        <p>COORDENAÇÃO DE PROVIMENTO E MOVIMENTO- CPM</p>
                    </div>
                    <img class="img-logo" src="../../images/SCP.png" alt="people">
                    <!-- <img class="img-logo" src="../images/educacao_logo.png" alt=""> -->
                </div>
                <h5 class="pt-5 mb-5 title_relatorio text-center">PROCESSO Nº - {{ $aposentadoria->num_process }}</h5>

                <div class=" mb-0 card">
                    <div class="pt-5 mb-0 d-flex justify-content-center card-title">
                        <h5>INFORMAÇÕES GERAIS</h5>
                    </div>
                    <div class="card-body mb-0">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle; width: 20%;">Nº DO PROCESSO</th>
                                    <th class="text-center" style="vertical-align: middle; width: 30%;">TIPO DO PROCESSO</th>
                                    <th class="text-center">CONCLUSÃO DO PROCESSO</th>
                                    <th class="text-center">POSSUI CARÊNCIA?</th>
                                    <th class="text-center">LOCAL DA CARÊNCIA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td class="text-center">{{ $aposentadoria->num_process }}</td>
                                <td class="text-center">{{ $aposentadoria->situacao_processo }}</td>
                                <td class="text-center">{{ $aposentadoria->conclusao }}</td>
                                <td class="text-uppercase text-center">{{ $aposentadoria->carencia }}</td>
                                @if (($aposentadoria->carencia_lot == "Sim") && ($aposentadoria->carencia_comp == null))
                                <td class="text-uppercase text-center">LOTAÇÃO</td>
                                @elseif (($aposentadoria->carencia_lot == null) && ($aposentadoria->carencia_comp == "Sim"))
                                <td class="text-uppercase text-center">COMPLEMENTAÇÃO</td>
                                @elseif (($aposentadoria->carencia_lot == "Sim") && ($aposentadoria->carencia_comp == "Sim"))
                                <td class="text-uppercase text-center">AMBOS (LOTAÇÃO + COMPLEMENTAÇÃO)</td>
                                @else
                                <td class="text-uppercase text-center">-</td>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-1">
                    <div class="pt-2 mb-0 d-flex justify-content-center card-title">
                        <h5>SERVIDOR</h5>
                    </div>
                    <div class="card-body mb-0">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle; width: 50%;">NOME</th>
                                    <th class="text-center" style="vertical-align: middle; width: 20%;">MATRICULA</th>
                                    <th class="text-center">REGIME</th>
                                    <th class="text-center" style="vertical-align: middle; width: 10%;">VINCULO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td class="text-center">{{ $aposentadoria->servidor }}</td>
                                <td class="text-center">{{ $aposentadoria->matricula }}</td>
                                <td class="text-center">{{ $aposentadoria->regime }}h</td>
                                <td class="text-center">{{ $aposentadoria->vinculo }}</td>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-1">
                    <div class="pt-2 mb-0 d-flex justify-content-center card-title">
                        <h5>UNIDADE DE LOTAÇÃO</h5>
                    </div>
                    <div class="card-body mb-0">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle; width: 30%;">NTE</th>
                                    <th class="text-center">MUNICIPIO</th>
                                    <th class="text-center" style="vertical-align: middle; width: 40%;">UNIDADE ESCOLAR</th>
                                    <th class="text-center" style="vertical-align: middle; width: 15%;">COD. UNIDADE ESCOLAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td class="text-center">{{ $aposentadoria->nte }}</td>
                                <td class="text-center">{{ $aposentadoria->municipio }}</td>
                                <td class="text-center">{{ $aposentadoria->unidade_escolar }}</td>
                                <td class="text-center">{{ $aposentadoria->cod_unidade }}</td>
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($aposentadoria->unidade_complementar)
                <div class="card mt-1">
                    <div class="pt-2 mb-0 d-flex justify-content-center card-title">
                        <h5>UNIDADE DE COMPLEMENTAÇÃO</h5>
                    </div>
                    <div class="card-body mb-0">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle; width: 30%;">NTE</th>
                                    <th class="text-center">MUNICIPIO</th>
                                    <th class="text-center" style="vertical-align: middle; width: 40%;">UNIDADE ESCOLAR</th>
                                    <th class="text-center" style="vertical-align: middle; width: 15%;">COD. UNIDADE ESCOLAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td class="text-center">{{ $aposentadoria->nte }}</td>
                                <td class="text-center">{{ $aposentadoria->municipio }}</td>
                                <td class="text-center">{{ $aposentadoria->unidade_complementar }}</td>
                                <td class="text-center">{{ $aposentadoria->cod_unidade_complementar}}</td>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="p-3 footer">
        <div class="d-flex justify-content-between">
            <span>
                SISTEMA DE CARÊNCIA E PROVIMENTO - SCP
            </span>
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
    </div>
    <script>
        // Script para imprimir automaticamente quando a página for carregada
        window.print();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.min.js"></script>

</body>

</html>