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
        <img class="img-logo" src="/images/SCP.png" alt="people">
        <h5 class="text-center">RELATÓRIO DE CARÊNCIA DE APOIO PEDAGÓGICO</h5>
        <img class="img-logo" src="/images/educacao_logo.png" alt="">
    </div>
    <div hidden>
    {{$total = 0}}
    </div>
    @foreach ($vagas_apoio_pedagocigo as $nte => $vagas_apoio_pedagocigo)
    <table class="table table-sm table-bordered table-hover">
        <thead class="bg-secondary text-white">
            <tr class="text-center">
            <th scope="col">NTE</th>
                <th scope="col">MUNICIPIO</th>
                <th scope="col">UNIDADE ESCOLAR</th>
                <th scope="col">COD.UE</th>
                <th scope="col">PROFISSIONAL</th>
                <th scope="col">REGIME</th>
                <th scope="col">TURNO</th>
                <th scope="col">QUANTIDADE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vagas_apoio_pedagocigo as $vagas)
            <tr>
                @if ($nte >= 10)
                <td class="text-center" style="width: 5%;">{{ $nte }}</td>
                @endif
                @if ($nte < 10)
                <td class="text-center" style="width: 5%;">0{{ $nte }}</td>
                @endif
                <td class="text-center" style="vertical-align: middle; width: 14%;">{{ $vagas -> municipio }}</td>
                <td class="text-center" style="vertical-align: middle; width: 30%;">{{ $vagas -> unidade_escolar }}</td>
                <td class="text-center" style="vertical-align: middle; width: 10%;">{{ $vagas -> cod_unidade }}</td>
                <td class="text-center" style="vertical-align: middle; width: 11%;">{{ $vagas -> profissional }}</td>
                <td class="text-center" style="vertical-align: middle; width: 5%;">{{ $vagas -> regime }}</td>
                <td class="text-center" style="vertical-align: middle; width: 10%;">{{ $vagas -> turno }}</td>
                <td class="text-center" style="vertical-align: middle; width: 5%;">{{ $vagas -> qtd }}</td>
            </tr>
            <div hidden>
            {{ $total = $total + $vagas -> qtd  }}
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
            <td class="text-center border-0"><strong>TOTAL</strong></td>
            <td class="text-center"><strong>{{ $total  }}</strong></td>
        </tr>
    </table>
    <div hidden>
    {{$total = 0}}
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