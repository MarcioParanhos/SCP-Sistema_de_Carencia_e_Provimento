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

    $arquivo = 'RELAÇÃO DE PROVIMENTOS EM TRAMITE.xls';

    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="9"><b><font size="4">RELAÇÃO DE PROVIMENTOS EM TRÂMITE EM DIAS</font></b></tr>';
    $html .= '</tr>';


    $html .= '<tr>';
    $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>UNIDADE ESCOLAR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>COD.UNIDADE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>SERVIDOR / CPF</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MATRICULA / CPF</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>SITUAÇÃO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>ENCAMINHAMENTO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DIAS SEM ASSUNÇÃO</b></td>';

    foreach ($provimentos as $provimento) {
        $html .= '<tr>';
        if ($provimento->nte < 10){
          $html .= '<td align="center">'.' 0' . $provimento->nte . '</td>';
        }
        if ($provimento->nte >= 10) {
            $html .= '<td align="center">' . $provimento->nte . '</td>';  
        }
        $html .= '<td align="center">' . $provimento->municipio . '</td>';
        $html .= '<td align="center">' . $provimento->unidade_escolar . '</td>';
        $html .= '<td align="center">' . $provimento->cod_unidade . '</td>';
        $html .= '<td align="center">' . $provimento->servidor  . '</td>';
        $html .= '<td align="center">' . $provimento->cadastro . '</td>';
        $html .= '<td align="center">' . $provimento->situacao_provimento . '</td>';
        $html .= '<td align="center">' . \Carbon\Carbon::parse($provimento->data_encaminhamento)->format('d/m/Y') . '</td>';
        $html .= '<td align="center">' . \Carbon\Carbon::parse($provimento->data_encaminhamento)->diffInDays(\Carbon\Carbon::now()) . '</td>';
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