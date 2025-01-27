<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Relatorio para Impressão</title>
    <style>
        .footer {
            display: flex;
            align-items: center;
            justify-content: end;
        }

        .tipo_carencia {
            display: flex;
            gap: 10px;
        }

        .img-logo {
            width: 200px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .border-right-0 {
            border: 0 !important;
        }

        th {
            font-size: 12px;
            color: #fbf8f3;
        }

        td {
            font-size: 12px;
        }

        span {
            font-size: 12px;
        }

        @media print {

            body {
                padding: 5px !important;
            }

            .title_relatorio {
                font-size: 16px;
            }

            .total {
                color: #232323;
            }

            .img-logo {
                width: 150px;
            }

            th {
                font-size: 8px;
                color: #232323;
            }

            td {
                font-size: 8px;
            }

            span {
                font-size: 8px;
            }
            table {
                padding-top: 50px;
            }

            .border-right-0 {
                border: 0 !important;
            }

            @page {
                size: landscape;
                margin: 10px !important;
                margin-top: 5px !important;
                padding: 10px !important;
            }
        }
    </style>
</head>

<body class="p-3">
    <div class="header">
        <img class="img-logo" src="../images/SCP.png" alt="people">
        <h5 class="title_relatorio text-center">RELATÓRIO DE CARÊNCIA</h5>
        <img class="img-logo" src="../images/educacao_logo.png" alt="">
    </div>
    <div hidden>
        {{$total = 0}}
        {{$matutino = 0}}
        {{$vespertino = 0}}
        {{$noturno = 0}}
    </div>
    @foreach ($carencias as $nte => $carencias)
    <table class="table table-sm table-bordered table-hover">
        <thead class="bg-secondary text-white">
            <tr class="text-center">
                <th scope="col">NTE</th>
                <th scope="col">MUNICIPIO</th>
                <th scope="col">UNIDADE ESCOLAR</th>
                <th scope="col">COD. UE</th>
                <th scope="col">TIPO</th>
                <th scope="col">MOTIVO</th>
                <th scope="col">DISCIPLINA</th>
                <th scope="col">SERVIDOR</th>
                <th scope="col">MATRICULA</th>
                <th class="text-center" scope="col">MAT</th>
                <th scope="col">VESP</th>
                <th scope="col">NOT</th>
                <th scope="col">TOTAL</th>
                <th scope="col">LANÇAMENTO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($carencias as $carencia)
            <tr>
                @if ($nte >= 10)
                <td class="text-center" style="vertical-align: middle; width: 3%;">{{ $nte }}</td>
                @endif
                @if ($nte < 10) <td class="text-center" style="vertical-align: middle; width: 3%;">0{{ $nte }}</td>
                    @endif
                    <td class="text-center" style="vertical-align: middle; width: 10%;">{{ $carencia -> municipio }}</td>
                    <td style="vertical-align: middle; width: 35%;">{{ $carencia -> unidade_escolar }}</td>
                    <td class="text-center" style="vertical-align: middle; width: 5%;">{{ $carencia -> cod_ue }}</td>
                    @if ($carencia->tipo_carencia === "Real")
                    <td class="text-center" style="vertical-align: middle; width: 5%;">REAL</td>
                    @endif
                    @if ($carencia->tipo_carencia === "Temp")
                    <td class="text-center" style="vertical-align: middle; width: 5%;">TEMPORÁRIA</td>
                    @endif
                    <td style="vertical-align: middle; width: 15%;">{{ $carencia -> motivo_vaga }}</td>
                    <td style="vertical-align: middle; width: 16%;">{{ $carencia -> disciplina }}</td>
                    <td style="vertical-align: middle; width: 16%;">{{ $carencia -> servidor }}</td>
                    <td class="text-center" style="vertical-align: middle; width: 16%;">{{ $carencia -> cadastro }}</td>
                    <td class="text-center" style="vertical-align: middle; width: 2%;">{{ $carencia -> matutino }}</td>
                    <td class="text-center" style="vertical-align: middle; width: 2%">{{ $carencia -> vespertino }}</td>
                    <td class="text-center" style="vertical-align: middle; width: 2%">{{ $carencia -> noturno }}</td>
                    <td class="text-center" style="vertical-align: middle; width: 2%">{{ $carencia -> total }}</td>
                    <td class="text-center" style="vertical-align: middle; width: 2%">
                        @php
                        $formattedDateTime = \Carbon\Carbon::parse($carencia->created_at)->subHours(3)->format('d/m/Y H:i:s');
                        echo $formattedDateTime;
                        @endphp
                    </td>
            </tr>
            <div hidden>
                {{ $total = $total + $carencia -> total  }}
                {{ $matutino = $matutino + $carencia -> matutino }}
                {{ $vespertino = $vespertino + $carencia -> vespertino }}
                {{ $noturno = $noturno + $carencia -> noturno }}
            </div>
            @endforeach
        </tbody>
        <tr class="total">
            <td class="border-right-0"></td>
            <td class="border-right-0"></td>
            <td class="border-right-0"></td>
            <td class="border-right-0"></td>
            <td class="border-right-0"></td>
            <td class="border-right-0"></td>
            <td class="border-right-0"></td>
            <td class="border-right-0"></td>
            <td class="text-center"><strong>TOTAL</strong></td>
            <td class="text-center"><strong>{{ $matutino }}</strong></td>
            <td class="text-center"><strong>{{ $vespertino }}</strong></td>
            <td class="text-center"><strong>{{ $noturno }}</strong></td>
            <td class="text-center"><strong>{{ $total }}</strong></td>

        </tr>
    </table>
    <div hidden>
        {{$total = 0}}
        {{$matutino = 0}}
        {{$vespertino = 0}}
        {{$noturno = 0}}
    </div>
    @endforeach
    <div class="footer mt-4">
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
        // Script para imprimir automaticamente quando a página for carregada
        window.print();
    </script>
</body>

</html>