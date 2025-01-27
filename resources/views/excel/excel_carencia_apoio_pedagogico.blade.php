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

    $arquivo = 'CARENCIA_DE_APOIO_PEDAGOGICO_DETALHADO.xls';

    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="8"><b><font size="4">RELATORIO GERAL DE CARÊNCIAS PEDAGÓGICAS</font></b></tr>';
    $html .= '</tr>';


    $html .= '<tr>';
    $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>UEE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>COD.UNIDADE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>PROFISSIONAL</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>REGIME</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TURNO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>QUANTIDADE</b></td>';

    foreach ($vagas_apoio_pedagocigo as $vagas) {
        $html .= '<tr>';
        if ($vagas->nte < 10){
          $html .= '<td align="center">' . $vagas->nte . '</td>';
        }
        if ($vagas->nte >= 10) {
            $html .= '<td align="center">' . $vagas->nte . '</td>';  
        }
        $html .= '<td align="center">' . $vagas->municipio . '</td>';
        $html .= '<td align="center">' . $vagas->unidade_escolar . '</td>';
        $html .= '<td align="center">' . $vagas->cod_unidade . '</td>';
        $html .= '<td align="center">' . $vagas->profissional . '</td>';
        $html .= '<td align="center">' . $vagas->regime . '</td>';
        $html .= '<td align="center">' . $vagas->turno . '</td>';
        $html .= '<td align="center">' . $vagas->qtd . '</td>';
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