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

    $arquivo = 'STATUS DE DIGITAÇÃO.xls';

    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="11"><b><font size="4">STATUS DE DIGITAÇÃO - 2024</font></b></tr>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td colspan="5" font size="4" bgcolor="##9cc2e5" align="center"><b>DADOS DA UNIDADE ESCOLAR</b></td>';
    $html .= '<td colspan="2" font size="4" bgcolor="##9cc2e5" align="center"><b>DIGITAÇÃO</b></td>';
    $html .= '<td colspan="2" font size="4" bgcolor="##9cc2e5" align="center"><b>CONCLUSÃO</b></td>';
    $html .= '<td colspan="1" rowspan="2" font size="4" bgcolor="##9cc2e5" align="center"><b>HOMOLOGAÇÃO</b></td>';
    $html .= '<td colspan="1" rowspan="2" font size="4" bgcolor="##9cc2e5" align="center"><b>OBSERVAÇÃO</b></td>';
    $html .= '</tr>';


    $html .= '<tr>';
    $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>UNIDADE ESCOLAR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>COD.UNIDADE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TIPO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>INICIOU</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MOTIVO DO NÃO INICIO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>CONCLUIU</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MOTIVO DA NÃO CONCLUSÃO</b></td>';
    $html .= '</tr>';

    foreach ($uees as $uee) {
        $html .= '<tr>';
        $html .= '<td align="center">' . $uee->nte . '</td>';  
        $html .= '<td align="center">' . $uee->municipio . '</td>';  
        $html .= '<td align="center">' . $uee->unidade_escolar . '</td>';  
        $html .= '<td align="center">' . $uee->cod_unidade . '</td>';  
        $html .= '<td align="center">' . $uee->tipo . '</td>';
        if ($uee->typing_started === "SIM")
        $html .= '<td align="center">' . "SIM" . '</td>';
        else {
            $html .= '<td align="center">' . "NÃO" . '</td>';  
        }
        if ($uee->description_typing_started)
        $html .= '<td align="center">' . $uee->description_typing_started . '</td>';
        else {
            $html .= '<td align="center">' . "-" . '</td>';  
        }
        if ($uee->finished_typing === "SIM")
        $html .= '<td align="center">' . "SIM" . '</td>';
        else {
            $html .= '<td align="center">' . "NÃO" . '</td>';  
        }
        if ($uee->finished_typing_description)
        $html .= '<td align="center">' . $uee->finished_typing_description . '</td>';
        else {
            $html .= '<td align="center">' . "-" . '</td>';  
        }
        $html .= '<td align="center">' . $uee->situacao . '</td>';
        $html .= '<td align="center">' . $uee->obs_cpg . '</td>';
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