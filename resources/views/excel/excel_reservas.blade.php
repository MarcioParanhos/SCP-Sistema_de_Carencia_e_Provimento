<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    @php
        $arquivo = 'RELACAO_DE_CARENCIA_RESERVADAS.xls';
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
        header('Pragma: no-cache');
        header('Expires: 0');
    @endphp

    <table border="1">
        <thead>
            <tr>
                <td bgcolor="#9cc2e5" align="center" colspan="12">
                    <b>
                        <font size="4">RELAÇÃO DE VAGAS RESERVADAS</font>
                    </b>
                </td>
            </tr>
            <tr>
                <th bgcolor="#9cc2e5" align="center">IDENTIFICADOR DO BLOCO</th>
                <th bgcolor="#9cc2e5" align="center">NTE</th>
                <th bgcolor="#9cc2e5" align="center">MUNICÍPIO</th>
                <th bgcolor="#9cc2e5" align="center">UNIDADE ESCOLAR</th>
                <th bgcolor="#9cc2e5" align="center">COD. UNIDADE ESCOLAR</th>
                <th bgcolor="#9cc2e5" align="center">DISCIPLINA</th>
                <th bgcolor="#9cc2e5" align="center">MAT</th>
                <th bgcolor="#9cc2e5" align="center">VESP</th>
                <th bgcolor="#9cc2e5" align="center">NOT</th>
                <th bgcolor="#9cc2e5" align="center">TOTAL</th>
                <th bgcolor="#9cc2e5" align="center">Nº DO COP</th>
                <th bgcolor="#9cc2e5" align="center">Nº DO PROCESSO SEI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservas_agrupadas as $blocoId => $reservasDoBloco)
                @php
                    $rowspan = count($reservasDoBloco);
                    $totalMat = $reservasDoBloco->sum(fn($r) => optional($r->carencia)->matutino ?? 0);
                    $totalVesp = $reservasDoBloco->sum(fn($r) => optional($r->carencia)->vespertino ?? 0);
                    $totalNot = $reservasDoBloco->sum(fn($r) => optional($r->carencia)->noturno ?? 0);
                    $totalGeral = $reservasDoBloco->sum(fn($r) => optional($r->carencia)->total ?? 0);
                @endphp

                @foreach ($reservasDoBloco as $i => $reserva)
                    <tr>
                        @if ($i === 0)
                            <td align="center" rowspan="{{ $rowspan }}">{{ $blocoId }}</td>
                        @endif

                        <td align="center">{{ str_pad(optional($reserva->carencia)->nte, 2, '0', STR_PAD_LEFT) }}</td>
                        <td align="center">{{ optional($reserva->carencia)->municipio }}</td>
                        <td align="center">{{ optional($reserva->carencia)->unidade_escolar }}</td>
                        <td align="center">{{ optional($reserva->carencia)->cod_ue }}</td>
                        <td align="center">{{ optional($reserva->carencia)->disciplina }}</td>
                        <td align="center">{{ optional($reserva->carencia)->matutino ?? 0 }}</td>
                        <td align="center">{{ optional($reserva->carencia)->vespertino ?? 0 }}</td>
                        <td align="center">{{ optional($reserva->carencia)->noturno ?? 0 }}</td>
                        <td align="center">{{ optional($reserva->carencia)->total ?? 0 }}</td>
                        <td align="center">{{ $reserva->num_cop ?? 'N/D' }}</td>
                        <td align="center">{{ $reserva->num_sei ?? 'N/D' }}</td>
                    </tr>
                @endforeach

          
                <tr style="background-color:#f2f2f2; font-weight:bold;">
                    <td colspan="6" align="right">TOTAL DO BLOCO {{ $blocoId }}</td>
                    <td align="center">{{ $totalMat }}</td>
                    <td align="center">{{ $totalVesp }}</td>
                    <td align="center">{{ $totalNot }}</td>
                    <td align="center">{{ $totalGeral }}</td>
                    <td colspan="2"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php exit; @endphp




</body>

</html>
