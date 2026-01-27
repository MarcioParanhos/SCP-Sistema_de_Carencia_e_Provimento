<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Termo de Assunção</title>

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
            font-size: 16px !important;
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

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px !important;
            color: #222;
        }

        table {
            margin-top: 30px !important;
            border-collapse: collapse !important;
            width: 100% !important;
            table-layout: fixed;
            font-size: 13px !important;
        }

        /* Clean table: only horizontal separators, no vertical grid */
        .oficio-table thead th {
            background: #e9e9e9 !important;
            text-align: center !important;
            vertical-align: middle !important;
            padding: 8px !important;
            font-weight: 700 !important;
            border-top: 1px solid #bbb !important;
            border-bottom: 1px solid #444 !important;
        }

        .oficio-table tbody td {
            padding: 10px 8px !important;
            border-bottom: 1px solid #ddd !important;
        }

        .oficio-table td.disciplina {
            text-align: left !important;
            padding-left: 12px !important;
            width: 66.66% !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .oficio-table td.turno {
            width: 11% !important;
            text-align: center !important;
        }

        /* header small labels */
        .oficio-table thead tr:first-child th[colspan] {
            font-size: 12px !important;
            text-transform: uppercase !important;
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
            .oficio-table thead th {
                background: #d9d9d9 !important;
                text-align: center !important;
                vertical-align: middle !important;
                padding: 8px !important;
                font-weight: 600 !important;
                border-bottom: 1px solid #333 !important;
            }
            font-size: 20px !important;
            margin-top: 80px !important;
            padding-left: 80px !important;
        }

            .oficio-table tbody td {
                padding: 10px 8px !important;
                border-bottom: 1px solid #333 !important;
            }
        @media print {
            * {
                padding: 0 !important;
                margin: 0 !important;
            }

            /* No vertical borders between cells - only discipline column visually separated by padding */
            .oficio-table td.disciplina {
                text-align: left !important;
                padding-left: 12px !important;
                width: 78% !important;
            }
            /* Force full grid borders when printing */
            .oficio-table, .oficio-table th, .oficio-table td {
                border: 1px solid #000 !important;
            }
            /* Ensure header has gray background and black borders when printing */
            .oficio-table thead th {
                background: #e9e9e9 !important;
                border-top: 1px solid #000 !important;
                border-bottom: 1px solid #000 !important;
            }
            .oficio-table {
                border-collapse: collapse !important;
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
                font-size: 16px !important;
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
            /* Force grayscale printing and preserve background colors when printing to PDF */
            html, body {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                filter: grayscale(100%) !important;
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
                SUPERINTENDÊNCIA DE RECURSOS HUMANOS<br>
            </p>
        </div>
    </header>
    <section class="date">
        <div class="date_anuencia">
            <p>Salvador, {{ now()->format('d/m/Y') }}</p>
        </div>
    </section>
    <section class="unidade_escolar">
        <p>
            @php
                $unidadeName = $candidate['unidade_escolar'] ?? $candidate['uee_name'] ?? null;
                $unidadeCode = $candidate['cod_unidade'] ?? $candidate['uee_code'] ?? null;
                if ((empty($unidadeName) || empty($unidadeCode)) && isset($encaminhamentos) && count($encaminhamentos)) {
                    $firstEnc = is_array($encaminhamentos) ? ($encaminhamentos[0] ?? null) : ($encaminhamentos->first() ?? null);
                    if ($firstEnc) {
                        $unidadeName = $unidadeName ?? ($firstEnc->uee_name ?? $firstEnc->uee ?? null);
                        $unidadeCode = $unidadeCode ?? ($firstEnc->uee_code ?? $firstEnc->cod_unidade ?? null);
                    }
                }
            @endphp
            UNIDADE ESCOLAR: {{ $unidadeName ?? '-' }}<br>
            CÓDIGO: {{ $unidadeCode ?? '-' }}
        </p>
    </section>
    <main>
        <p>Senhor(a) Diretor(a): </p>
        <p class="main-content">
            Pelo presente termo, informamos que o servidor(a) {{ $candidate['name'] ?? $candidate['nome'] ?? '-' }}, CPF nº {{ $candidate['cpf'] ?? '-' }}, professor(a), assumiu suas atividades em   ____ de ____________ de 2026. </p>.
        </p>
        <div class="table">
            <div class="table-responsive">
                <table class="oficio-table" style="table-layout: fixed;">
                    <colgroup>
                        <col style="width:66.66%" />
                        <col style="width:11%" />
                        <col style="width:11%" />
                        <col style="width:11%" />
                    </colgroup>
                    <thead>
                        <tr>
                            <th rowspan="2" style="text-align:left; padding-left:10px;">DISCIPLINA</th>
                            <th colspan="3">TURNO/CH</th>
                        </tr>
                        <tr>
                            <th>MAT</th>
                            <th>VESP</th>
                            <th>NOT</th>
                        </tr>
                    </thead>
                    @php
                        $mat_total = 0;
                        $vesp_total = 0;
                        $not_total = 0;
                    @endphp

                    <tbody>
                        @if (isset($encaminhamentos) && count($encaminhamentos))
                            @foreach ($encaminhamentos as $enc)
                                @php
                                    $disc = $enc->disciplina_name ?? $enc->disciplina_nome ?? $enc->disciplina ?? '-';
                                    $mat = intval($enc->quant_matutino ?? $enc->matutino ?? 0);
                                    $ves = intval($enc->quant_vespertino ?? $enc->vespertino ?? 0);
                                    $not = intval($enc->quant_noturno ?? $enc->noturno ?? 0);
                                    $mat_total += $mat;
                                    $vesp_total += $ves;
                                    $not_total += $not;
                                @endphp
                                <tr>
                                    <td class="disciplina" title="{{ $disc }}">{{ $disc }}</td>
                                    <td class="turno text-center" aria-label="Matutino">{{ $mat > 0 ? $mat : '' }}</td>
                                    <td class="turno text-center" aria-label="Vespertino">{{ $ves > 0 ? $ves : '' }}</td>
                                    <td class="turno text-center" aria-label="Noturno">{{ $not > 0 ? $not : '' }}</td>
                                </tr>
                            @endforeach

                            {{-- Apenas linhas com disciplinas (não acrescentar linhas vazias) --}}
                        @else
                            {{-- Espaço em branco com 7 linhas para preenchimento manual --}}
                            @for ($i = 0; $i < 7; $i++)
                                <tr>
                                    <td class="disciplina" title="">&nbsp;</td>
                                    <td class="turno text-center" aria-label="Matutino">&nbsp;</td>
                                    <td class="turno text-center" aria-label="Vespertino">&nbsp;</td>
                                    <td class="turno text-center" aria-label="Noturno">&nbsp;</td>
                                </tr>
                            @endfor
                        @endif
                    </tbody>

                    @if (count($encaminhamentos))
                        {{-- total row removed as requested --}}
                    @endif
                </table>
            </div>
        </div>
        <p class="main_content_two">
        </p>
    </main>
    <section class="tipo_vaga">

    </section>
    <section class="yoursSincerely">
        @php
            $motivos = collect($encaminhamentos ?? [])->map(function($e) {
                // prefer 'tipo_carencia' column as requested, fallback to older motivo fields
                $raw = $e->tipo_carencia ?? $e->tipo_carencia_name ?? $e->motivo ?? $e->motivo_name ?? $e->motivo_encaminhamento ?? null;
                if (empty($raw)) {
                    return null;
                }
                $norm = strtolower(trim((string) $raw));
                $norm = str_replace([' ', '-'], ['_', '_'], $norm);

                if ($norm === 'vaga_real') {
                    return 'Vaga Real';
                }
                if ($norm === 'vaga_temporaria' || $norm === 'vaga_temporária') {
                    return 'Vaga Temporária';
                }
                return 'Substituição de REDA';
            })->filter()->unique()->values()->all();
        @endphp
        <p>MOTIVO: {{ count($motivos) ? implode('; ', $motivos) : '-' }}</p>
    </section>
    <section class="yoursSincerely">
        <p> ____ de ____________ de 2026. </p>
    </section>
    <section class="responsavel">

        <p>___________________________________________ <br>Assinatura </p>
    </section>

    <script>
        // Script para imprimir automaticamente quando a página for carregada
        window.print();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.min.js"></script>

</body>

</html>
