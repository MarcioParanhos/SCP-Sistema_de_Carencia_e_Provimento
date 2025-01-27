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

    $arquivo = 'PROCESSOS TRAMITADOS PARA APOSENTADORIA.xls';

    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td style="width: 70%;" class="text-center" rowspan="2" colspan="7" bgcolor="#9cc2e5"><b><font size="3">INFORMAÇÕES CAD - PROCESSOS ENVIADOS PARA ATO - APOSENTADORIA SUPREV</font></b></td>';
    $html .= '<td style="width: 20%;" class="text-center" colspan="3" bgcolor="#9cc2e5"><b><font size="3">ANÁLISE CPG</font></b></td>';
    $html .= '<td style="width: 10%;" class="text-center" colspan="1" bgcolor="#9cc2e5"><b><font size="3">ANÁLISE CPM</font></b></td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td style="width: 8%;" class="text-center" rowspan="2" colspan="1" bgcolor="#9cc2e5"><b><font size="3">CARÊNCIA</font></b></td>';
    $html .= '<td class="text-center" colspan="2" bgcolor="#9cc2e5"><b><font size="3">LOCAL DA CARÊNCIA</font></b></td>';
    $html .= '<td style="width: 2%;" class="text-center" rowspan="2" colspan="1" bgcolor="#9cc2e5"><b><font size="3">FORMA DE SUPRIMENTO</font></b></td>';
    $html .= '</tr>';

    $html .= '<tr class="text-center">';
    $html .= '<td style="width: 12%;" bgcolor="#9cc2e5"><b><font size="3">PROCESSO SEI</font></b></td>';
    $html .= '<td bgcolor="#9cc2e5"><b><font size="3">NTE</font></b></td>';
    $html .= '<td bgcolor="#9cc2e5"><b><font size="3">MUNICIPIO</font></b></td>';
    $html .= '<td bgcolor="#9cc2e5"><b><font size="3">MATRÍCULA</font></b></td>';
    $html .= '<td bgcolor="#9cc2e5"><b><font size="3">SERVIDOR</font></b></td>';
    $html .= '<td bgcolor="#9cc2e5"><b><font size="3">TIPO</font></b></td>';
    $html .= '<td bgcolor="#9cc2e5"><b><font size="3">CONCLUSÃO</font></b></td>';
    $html .= '<td bgcolor="#9cc2e5"><b><font size="3">LOTAÇÃO</font></b></td>';
    $html .= '<td bgcolor="#9cc2e5"><b><font size="3">COMP.</font></b></td>';
    $html .= '</tr>';

    foreach ($processos as $processo) {
        $html .= '<tr>';
        $html .= '<td align="center">' . $processo->num_process . '</td>';
        $html .= '<td align="center">' . $processo->nte . '</td>';
        $html .= '<td align="center">' . $processo->municipio . '</td>';
        $html .= '<td align="center">' . $processo->matricula . '</td>';
        $html .= '<td align="center">' . $processo->servidor . '</td>';
        $html .= '<td align="center">' . $processo->situacao_processo . '</td>';
        if ($processo->conclusao === null) {
            $html .= '<td align="center">PENDENTE</td>';
        }else {
            $html .= '<td align="center">' . $processo->conclusao . '</td>';
        }
        if ($processo->carencia === null) {
            if ($processo->conclusao == null) {
                $html .= '<td align="center">PENDENTE ANALISE CPG</td>';
            } else {
                $html .= '<td align="center"> - </td>'; 
            }
        } elseif ($processo->carencia === 'Sim') {
            $html .= '<td align="center">SIM</td>';
        } else {
            $html .= '<td align="center">NÃO</td>';
        }
        if ($processo->carencia_lot === 'Sim') {
            $html .= '<td align="center">X</td>';
        } elseif ($processo->carencia_lot === null) {
            $html .= '<td align="center"> - </td>';
        }
        if ($processo->carencia_comp === 'Sim') {
            $html .= '<td align="center">X</td>';
        } elseif ($processo->carencia_comp === null) {
            $html .= '<td align="center"> - </td>';
        }
        if ($processo->forma_suprimento === null) {
            if (($processo->conclusao != "DESISTÊNCIA") && ( $processo->carencia === "Sim")) {
                $html .= '<td align="center">PENDENTE ANALISE CPM</td>';
            } else {
                $html .= '<td align="center"> - </td>';
            }
        } else  {
            $html .= '<td align="center">' . $processo->forma_suprimento . '</td>';
        }
        $html .= '</tr>';
    }

    $html .= '</table>';

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