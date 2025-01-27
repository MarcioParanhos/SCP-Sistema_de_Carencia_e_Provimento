<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $total = 0;
    $totalReal = 0;
    $totalTemp = 0;

    if ($dataCarenciasPorAreaByNte) {

        $arquivo = 'CARENCIAS POR AREA.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td align="center" colspan="8"><b><font size="4">RELATORIO GERAL DE CARÊNCIAS POR ÁREA</font></b></tr>';
        $html .= '</tr>';


        $html .= '<tr>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>ÁREA</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>CH TEMPORARIA</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>QTD PROF. TEMP.</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>CH REAL</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>QTD PROF. REAL.</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>CH TOTAL</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>QTD PROF. TOTAL</b></td>';

        foreach ($dataCarenciasPorAreaByNte  as $item) {
            $html .= '<tr>';
            if ($item['nte'] < 10) {
                $html .= '<td align="center">' . $item['nte'] . '</td>';
            }
            if ($item['nte'] >= 10) {
                $html .= '<td align="center">' . $item['nte'] . '</td>';
            }
            if ($item['area'] === 'AREA TECNICA') {
                $html .= '<td align="center">' . $item['area'] . ' - PROFISSIONALIZANTE' . '</td>';
            } else {
                $html .= '<td align="center">' . $item['area'] . '</td>';
            }
            $html .= '<td align="center">' . $item['total_temp'] . ' h' . '</td>';
            $html .= '<td align="center">' . number_format($item['total_temp'] / 16, 1) . '</td>';
            $html .= '<td align="center">' . $item['total_real'] . ' h' . '</td>';
            $html .= '<td align="center">' . number_format($item['total_real'] / 16, 1) . '</td>';
            $html .= '<td align="center">' . $item['total'] . ' h' . '</td>';
            $html .= '<td align="center">' . number_format($item['total'] / 16, 1) . '</td>';
            $html .= '</tr>';

            $total = $total + $item['total'];
            $totalReal = $totalReal + $item['total_real'];
            $totalTemp = $totalTemp + $item['total_temp'];
        }
        $html .= '<tr>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center" colspan="2">' . 'TOTAL' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . $totalTemp . 'h' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . number_format($totalTemp / 16, 1) . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . $totalReal . 'h' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . number_format($totalReal / 16, 1) . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . $total . 'h' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . number_format($total / 16, 1) . '</td>';
        $html .= '</tr>';
    } else if ($dataCarenciasPorAreaByMunicipio) {

        $arquivo = 'CARENCIAS POR AREA.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td align="center" colspan="8"><b><font size="4">RELATORIO GERAL DE CARÊNCIAS POR ÁREA</font></b></tr>';
        $html .= '</tr>';


        $html .= '<tr>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>ÁREA</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>CH TEMPORARIA</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>QTD PROF. TEMP.</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>CH REAL</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>QTD PROF. REAL.</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>CH TOTAL</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>QTD PROF. TOTAL</b></td>';

        foreach ($dataCarenciasPorAreaByMunicipio  as $item) {
            $html .= '<tr>';
            if ($item->nte  < 10) {
                $html .= '<td align="center">' . $item->nte . '</td>';
            }
            if ($item->nte  >= 10) {
                $html .= '<td align="center">' . $item->nte  . '</td>';
            }
            $html .= '<td align="center">' . $item->municipio . '</td>';
            if ($item->area === 'AREA TECNICA') {
                $html .= '<td align="center">' . $item->area . ' - PROFISSIONALIZANTE' . '</td>';
            } else {
                $html .= '<td align="center">' . $item->area . '</td>';
            }
            $html .= '<td align="center">' . $item->total_temp . ' h' . '</td>';
            $html .= '<td align="center">' . number_format($item->total_temp / 16, 1) . '</td>';
            $html .= '<td align="center">' . $item->total_real . ' h' . '</td>';
            $html .= '<td align="center">' . number_format($item->total_real / 16, 1) . '</td>';
            $html .= '<td align="center">' . $item->total . ' h' . '</td>';
            $html .= '<td align="center">' . number_format($item->total / 16, 1) . '</td>';
            $html .= '</tr>';

            $total = $total + $item->total;
            $totalReal = $totalReal + $item->total_real;
            $totalTemp = $totalTemp + $item->total_temp;
        }

        $html .= '<tr>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center" colspan="3">' . 'TOTAL' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . $totalTemp . 'h' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . number_format($totalTemp / 16, 1) . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . $totalReal . 'h' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . number_format($totalReal / 16, 1) . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . $total . 'h' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . number_format($total / 16, 1) . '</td>';
        $html .= '</tr>';
    } else if ($dataCarenciasPorAreaByUnidadeEscolar) {

        $arquivo = 'CARENCIAS POR AREA.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td align="center" colspan="8"><b><font size="4">RELATORIO GERAL DE CARÊNCIAS POR ÁREA</font></b></tr>';
        $html .= '</tr>';


        $html .= '<tr>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>UNIDADE ESCOLAR</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>ÁREA</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>CH TEMPORARIA</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>QTD PROF. TEMP.</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>CH REAL</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>QTD PROF. REAL.</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>CH TOTAL</b></td>';
        $html .= '<td bgcolor="##9cc2e5" align="center"><b>QTD PROF. TOTAL</b></td>';

        foreach ($dataCarenciasPorAreaByUnidadeEscolar  as $item) {
            $html .= '<tr>';
            if ($item->nte  < 10) {
                $html .= '<td align="center">' . $item->nte . '</td>';
            }
            if ($item->nte  >= 10) {
                $html .= '<td align="center">' . $item->nte  . '</td>';
            }
            $html .= '<td align="center">' . $item->municipio . '</td>';
            $html .= '<td align="center">' . $item->unidade_escolar . '</td>';
            if ($item->area === 'AREA TECNICA') {
                $html .= '<td align="center">' . $item->area . ' - PROFISSIONALIZANTE' . '</td>';
            } else {
                $html .= '<td align="center">' . $item->area . '</td>';
            }
            $html .= '<td align="center">' . $item->total_temp . ' h' . '</td>';
            $html .= '<td align="center">' . number_format($item->total_temp / 16, 1) . '</td>';
            $html .= '<td align="center">' . $item->total_real . ' h' . '</td>';
            $html .= '<td align="center">' . number_format($item->total_real / 16, 1) . '</td>';
            $html .= '<td align="center">' . $item->total . ' h' . '</td>';
            $html .= '<td align="center">' . number_format($item->total / 16, 1) . '</td>';
            $html .= '</tr>';

            $total = $total + $item->total;
            $totalReal = $totalReal + $item->total_real;
            $totalTemp = $totalTemp + $item->total_temp;
        }

        $html .= '<tr>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center" colspan="4">' . 'TOTAL' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . $totalTemp . ' h' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . number_format($totalTemp / 16, 1) . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . $totalReal . ' h' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . number_format($totalReal / 16, 1) . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . $total . ' h' . '</td>';
        $html .= '<td font size="4" bgcolor="##9cc2e5" align="center">' . number_format($total / 16, 1) . '</td>';
        $html .= '</tr>';
    }


    // Configurações header para forçar o download
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/x-msexcel");
    header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
    header("Content-Description: PHP Generated Data");
    // Envia o conteúdo do arquivo
    echo $html;
    exit; ?>
</body>

</html>