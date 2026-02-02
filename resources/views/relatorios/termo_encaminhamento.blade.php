<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Termo de Encaminhamento</title>

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

        .table {
            display: flex !important;
            justify-content: end !important;
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

            table {
                margin-top: 40px !important;
            }

            th {
                font-weight: 400 !important;
                padding: 0 8px !important;
            }

            .table {
                display: flex !important;
                justify-content: end !important;
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
    @php
            // Prefer relation 'servidorSubstituido', but fall back to explicit 'servidor_id' column if present
            $sSub = $provimentos_encaminhado->servidorSubstituido ?? null;
            if (empty($sSub) && !empty($provimentos_encaminhado->servidor_id)) {
                $sSub = \App\Models\Servidore::find($provimentos_encaminhado->servidor_id);
            }
            $sSubName = optional($sSub)->nome ?? optional($sSub)->name ?? optional($provimentos_encaminhado)->servidor_substituido ?? '-';
            $sSubCadastro = optional($sSub)->cadastro ?? optional($provimentos_encaminhado)->servidor_substituido_id ?? ($provimentos_encaminhado->servidor_id ?? '');
            $sSubCpf = optional($sSub)->cpf ?? '';
            $sSubVinculo = optional($sSub)->vinculo ?? '';
        @endphp
    <header>
        <div class="header border mb-2">
            <img src="/images/teste.png" alt="">
            {{-- <div class="inputs">
                <div class="radio_buttons">
                    <input type="checkbox">
                    <label for="">INGRESSO</label>
                </div>
                <div class="radio_buttons">
                    <input type="checkbox">
                    <label for="">COMPLEMENTAÇÃO</label>
                </div>
            </div> --}}
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
        @php
            $dateToShow = optional($provimentos_encaminhado)->data_encaminhamento ? \Carbon\Carbon::parse($provimentos_encaminhado->data_encaminhamento)->format('d/m/Y') : \Carbon\Carbon::now()->format('d/m/Y');
        @endphp
        <p><span id="date">{{ $dateToShow }}</span></p>
        </div>
    </section>
    <section class="after_date">
        <p><strong>OF. CIRC CPM</strong></p>
    </section>
    <section class="unidade_escolar">
    <p>{{ optional($provimentos_encaminhado->uee)->unidade_escolar ?? '-' }} - {{ optional($provimentos_encaminhado->uee)->cod_unidade ?? '' }}.</p>
    </section>
    <main>
        <p>Senhor (a) Diretor (a): </p>
        @php
            $server = $provimentos_encaminhado->servidorEncaminhado ?? null;
            $serverName = optional($server)->nome ?? optional($server)->name ?? optional($provimentos_encaminhado)->servidor ?? '-';
            $serverCpf = optional($server)->cpf ?? '-';
            $cargo = optional($server)->cargo ?? optional($provimentos_encaminhado)->vinculo ?? null;
        @endphp
        <p class="main-content">
            Encaminhamos o(a) Professor(a) {{ $serverName }},
            CPF {{ $serverCpf }} com carga horária de 20hs,@if(!empty($sSubName) && $sSubName !== '-')
            Subistituindo o(a) servidor(a): {{ $sSubName }}, Matrícula: {{ $sSubCadastro ?: '-' }}, Vínculo: {{ $sSubVinculo ?: '-' }}
        @endif, para atuar nos turnos abaixo indicados, na(s) disciplina(s)
            @php
                // prefer explicit $encaminhamentos (rows from ingresso_encaminhamentos) when available
                $discList = [];
                if (isset($encaminhamentos) && !empty($encaminhamentos) && count($encaminhamentos)) {
                    foreach ($encaminhamentos as $r) {
                        $name = $r->disciplina_name ?? $r->disciplina_nome ?? $r->disciplina ?? null;
                        if ($name) $discList[] = trim((string) $name);
                    }
                }
                if (empty($discList)) {
                    // fallback to single field on provimentos_encaminhado
                    $fallback = $provimentos_encaminhado->disciplina ?? null;
                    if ($fallback) $discList = array_map('trim', explode(',', (string) $fallback));
                }
            @endphp
            <strong>{{ count($discList) ? implode('; ', $discList) : '-' }}</strong>.
        </p>
        @php
            $m_total = 0;
            $v_total = 0;
            $n_total = 0;
            if (isset($encaminhamentos) && !empty($encaminhamentos) && count($encaminhamentos)) {
                foreach ($encaminhamentos as $r) {
                    $m_total += intval($r->quant_matutino ?? $r->matutino ?? 0);
                    $v_total += intval($r->quant_vespertino ?? $r->vespertino ?? 0);
                    $n_total += intval($r->quant_noturno ?? $r->noturno ?? 0);
                }
            } else {
                $m_total = intval(optional($provimentos_encaminhado)->total_matutino ?? 0);
                $v_total = intval(optional($provimentos_encaminhado)->total_vespertino ?? 0);
                $n_total = intval(optional($provimentos_encaminhado)->total_noturno ?? 0);
            }
        @endphp

        <div class="table">
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th>MATUTINO</th>
                        <th>VESPERTINO</th>
                        <th>NOTURNO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">{{ $m_total }}</td>
                        <td class="text-center">{{ $v_total }}</td>
                        <td class="text-center">{{ $n_total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="main_content_two">Informamos que a assunção deverá ser entregue em 02 (Duas) vias, no prazo de 48 horas.
        </p>
    </main>
    <section class="tipo_vaga">

    </section>
    <section class="yoursSincerely">
        <p>Atenciosamente, </p>
    </section>
    @if(intval(optional($provimentos_encaminhado->uee)->nte ?? 0) === 26)
    <section class="responsavel">
        <p><strong>Manoel Vidal Colaço Neto</strong></p>
        <p>COORDENADOR TÉCNICO</p>
    </section>
    @else
    <section class="responsavel">
        <p><strong>NÚCLEO TERRITORIAL DE EDUCAÇÃO</strong></p>
    </section>
    @endif

    <script>
        // Script para imprimir automaticamente quando a página for carregada
        window.print();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.min.js"></script>

</body>

</html>