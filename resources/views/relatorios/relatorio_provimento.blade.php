<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Relatorio para Impressão</title>
    <style>
        .footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer h5 {
            font-size: 16px;
            font-weight: 400;
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

        td {
            font-size: 14px;
        }

        .title_nte {
            display: flex;
            align-items: center;
            justify-content: start;
            padding: 5px;
        }

        tbody th {
            font-weight: 400;
        }

        @media print {
            * {
                padding: 5px !important;
                margin: 5px !important;
            }

            .header h5 {
                font-size: 14px !important;
            }

            .footer h5 {
                font-size: 10px;
                font-weight: 400;
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
                font-size: 8px !important;
            }

            span {
                font-size: 10px;
            }

            .title_nte {
                display: flex;
                align-items: center;
                height: 8px !important;
            }

            h6 {
                height: 100%;
                font-size: 10px;
                color: #232323;
            }

            .teste {
                margin-top: 10px !important;
            }

            @page {
                size: landscape;
                margin: 0;
            }
        }
    </style>
</head>

<body class="p-2">
    <div class="header">
        <img class="img-logo" src="../images/SCP.png" alt="people">
        <h5 class="text-center">RELATÓRIO DE PROVIMENTO</h5>
        <img class="img-logo" src="../images/educacao_logo.png" alt="">
    </div>
    <div hidden>
        {{$total = 0}}
        {{$matutino = 0}}
        {{$vespertino = 0}}
        {{$noturno = 0}}
    </div>
    @foreach ($provimentos as $nte => $provimentos)
    <div class="text-white bg-secondary">
        <!-- @if ($nte >= 10)
        <div class="title_nte">
            <h6>NTE {{ $nte }}</h6>
        </div>  
        @endif
        @if ( $nte < 10 ) 
        <div class="title_nte">
            <h6>NTE 0{{ $nte }}</h6>
        </div>
        @endif -->
    </div>
    <table class="table table-sm table-bordered table-hover">
        <thead class="bg-secondary text-white">
            <tr class="text-center">
                <th scope="col">NTE</th>
                <th scope="col">MUNICIPIO</th>
                <th scope="col">UNIDADE ESCOLAR</th>
                <th scope="col">COD.UE</th>
                <th scope="col">SERVIDOR</th>
                <th scope="col">MATRICULA/CPF</th>
                <th scope="col">MOVIMENTO</th>
                <th scope="col">FORMA</th>
                <th scope="col">SITUAÇÂO</th>
                <th class="text-center" scope="col">ENCAM.</th>
                <th class="text-center" scope="col">ASSUNÇÃO</th>
                <th scope="col">DISCIPLINA</th>
                <th class="text-center" scope="col">MAT</th>
                <th scope="col">VESP</th>
                <th scope="col">NOT</th>
                <th scope="col">TOTAL</th>
                <th scope="col">PCH</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($provimentos as $provimento)
            <tr>
                @if ($nte >= 10)
                <td class="text-center">{{ $nte }}</td>
                @endif
                @if ($nte < 10) <td class="text-center">0{{ $nte }}</td>
                    @endif
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> municipio }}</td>
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> unidade_escolar }}</td>
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> cod_unidade }}</td>
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> servidor }}</td>
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> cadastro }}</td>
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> tipo_movimentacao }}</td>
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> forma_suprimento }}</td>
                    @if ($provimento->situacao_provimento === "provida")
                    <td class="text-center" style="vertical-align: middle;">PROVIDO</td>
                    @endif
                    @if ($provimento->situacao_provimento === "tramite")
                    <td class="text-center" style="vertical-align: middle;">EM TRÂMITE</td>
                    @endif
                    <td class="text-center" style="vertical-align: middle;">{{ \Carbon\Carbon::parse($provimento->data_encaminhamento)->format('d/m/Y') }}</td>
                    @if (!$provimento->data_assuncao)
                    <td class="text-center" style="vertical-align: middle;">PENDENTE</td>
                    @endif
                    @if ($provimento->data_assuncao)
                    <td class="text-center" style="vertical-align: middle;">{{ \Carbon\Carbon::parse($provimento->data_assuncao)->format('d/m/Y') }}</td>
                    @endif
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> disciplina }}</td>
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> provimento_matutino }}</td>
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> provimento_vespertino }}</td>
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> provimento_noturno }}</td>
                    <td class="text-center" style="vertical-align: middle;">{{ $provimento -> total }}</td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if ($provimento -> pch === "OK")
                        PROGRAMADO
                        @else
                        PENDENTE
                        @endif
                    </td>
            </tr>
            <div hidden>
                {{ $total = $total + $provimento->total  }}
                {{ $matutino = $matutino + $provimento->provimento_matutino }}
                {{ $vespertino = $vespertino + $provimento->provimento_vespertino }}
                {{ $noturno = $noturno + $provimento->provimento_noturno }}
            </div>
            @endforeach
        </tbody>
        <tr class="total">
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="text-center"><strong>{{ $matutino }}</strong></td>
            <td class="text-center"><strong>{{ $vespertino }}</strong></td>
            <td class="text-center"><strong>{{ $noturno }}</strong></td>
            <td class="text-center"><strong>{{ $total  }}</strong></td>
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
        <h5>{{ Auth::user()->name }}</h5>
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