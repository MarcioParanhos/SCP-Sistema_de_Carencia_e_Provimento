<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Termo de Reserva</title>

    <style>
        body {
            max-width: 100vw;
            max-height: 100vh;
        }

        * {
            padding: 0;
            margin: 0;
        }

        .header {
            display: flex !important;
            justify-content: space-between !important;
            padding: 80px 80px 15px 80px !important;
        }

        .header img {
            width: 60px;
        }

        .inputs {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .radio_buttons {
            display: flex;
            gap: 10px;
        }

        * {
            padding: 0 !important;
            margin: 0 !important;
        }

        hr {
            margin-left: 10% !important;
            width: 90% !important;
        }

        .title-header {
            padding: 0 80px !important;
        }

        .date {
            margin-top: 40px !important;
            display: flex !important;
            justify-content: end !important;
            align-items: center !important;
            padding-right: 80px !important;
        }

        .date p {
            font-size: 18px !important;
        }

        .after_date {
            margin-top: 40px !important;
            padding-left: 80px !important;
        }

        .unidade_escolar {
            margin-top: 20px !important;
            padding-left: 80px !important;
            font-size: 20px !important;
        }

        main {
            margin-top: 20px !important;
            padding-left: 80px !important;
            padding-right: 68px !important;
            font-size: 18px !important;
        }

        .main-content {
            font-size: 20px !important;
            margin-top: 20px !important;
            text-align: justify !important;
        }

        table {
            margin-top: 40px !important;
        }

        th {
            font-weight: 400 !important;
            padding: 0 8px !important;
        }

        
      

        .main_content_two {
            font-size: 20px !important;
            margin-top: 40px !important;
            text-align: justify !important;
        }

        .tipo_vaga {
            font-size: 20px !important;
            margin-top: 40px !important;
            padding-left: 80px !important;
        }

        .yoursSincerely {
            font-size: 18px !important;
            margin-top: 100px !important;
            padding-left: 80px !important;
        }

        .responsavel {
            font-size: 20px !important;
            margin-top: 80px !important;
            padding-left: 80px !important;
        }

        @media print {
            * {
                padding: 0 !important;
                margin: 0 !important;
            }

            hr {
                margin-left: 10% !important;
                width: 90% !important;
            }

            .header {
                display: flex !important;
                justify-content: space-between !important;
                padding: 80px 80px 15px 80px !important;
            }

            .title-header {
                padding: 0 80px !important;
            }

            .date {
                margin-top: 40px !important;
                display: flex !important;
                justify-content: end !important;
                align-items: center !important;
                padding-right: 80px !important;
            }

            .date p {
                font-size: 18px !important;
            }

            .after_date {
                font-size: 20px !important;
                margin-top: 40px !important;
                padding-left: 80px !important;
            }

            .unidade_escolar {
                margin-top: 20px !important;
                padding-left: 80px !important;
                font-size: 20px !important;
            }

            main {
                margin-top: 20px !important;
                padding-left: 80px !important;
                padding-right: 68px !important;
                font-size: 18px !important;
            }

            .main-content {
                font-size: 20px !important;
                margin-top: 20px !important;
                text-align: justify !important;
            }

          

            th {
                font-weight: 400 !important;
                padding: 0 8px !important;
            }

          

            .main_content_two {
                font-size: 20px !important;
                margin-top: 40px !important;
                text-align: justify !important;
            }

            .tipo_vaga {
                font-size: 20px !important;
                margin-top: 40px !important;
                padding-left: 80px !important;
            }

            .yoursSincerely {
                font-size: 18px !important;
                margin-top: 60px !important;
                padding-left: 80px !important;
            }

            .responsavel {
                font-size: 20px !important;
                margin-top: 100px !important;
                padding-left: 80px !important;
            }

            @page {
                size: portrait;
                margin: 0;
            }
        }
    </style>

</head>

<body>
    <header>
        <div class="header border mb-2">
            <img src="/images/teste.png" alt="">
            
        </div>
        <div class="title-header">
            <p>
                GOVERNO DO ESTADO DA BAHIA<br>
                SECRETARIA DA EDUCAÇÃO - SEC<br>
                SUPERINTENDÊNCIA DE PESSOAL – SUDEPE<br>
                DIRETORIA DE RECURSOS HUMANOS – DIREH<br>
                COORDENAÇÃO DE PROVIMENTO E MOVIMENTAÇÃO – CPM
            </p>
        </div>
    </header>
    <section class="date">
        <div class="date_anuencia">
        <p>Salvador, <span id="date"></span></p>
        </div>
    </section>
    <section class="after_date">
        <p><strong>OF. CIRC CPM</strong></p>
    </section>
    <section class="unidade_escolar">
    <p> - .</p>
    </section>
    <main>
        <p>Senhor (a) Diretor (a): </p>
        <p class="main-content">
            Encaminhamos o bloco de vagas reservadas, que formaliza a solicitação para os devidos procedimentos de provimento de um(a) professor(a) para suprir a carência. Este processo engloba o recebimento da documentação e a efetivação da contratação para as vagas detalhadas a seguir.
        </p>
        <div class="table">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="table-light">
                        <tr class="text-center">
      
                            <th>Disciplina</th>
                            <th>MAT</th>
                            <th>VESP</th>
                            <th>NOT</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservas as $reserva)
                            {{-- O 'optional()' é uma segurança para evitar erros caso a carência tenha sido deletada --}}
                            <tr class="">
        
                                <td class="text-start">{{ optional($reserva->carencia)->disciplina }}</td>
                                <td class="text-center">{{ optional($reserva->carencia)->matutino }}</td>
                                <td class="text-center">{{ optional($reserva->carencia)->vespertino }}</td>
                                <td class="text-center">{{ optional($reserva->carencia)->noturno }}</td>
                                <td class="text-center" class="fw-bold">{{ optional($reserva->carencia)->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <p class="main_content_two">
        </p>
    </main>
    <section class="tipo_vaga">

    </section>
    <section class="yoursSincerely">
        <p>Atenciosamente, </p>
    </section>
    <section class="responsavel">
        <p><strong>MANOEL COLAÇO</strong></p>
        <p>COORDENADOR TÉCNICO</p>
    </section>

    <script>
        // Script para imprimir automaticamente quando a página for carregada
        window.print();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.min.js"></script>

</body>

</html>