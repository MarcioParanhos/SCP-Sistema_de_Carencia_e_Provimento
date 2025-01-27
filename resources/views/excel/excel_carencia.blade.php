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

    $arquivo = 'CARENCIA_DETALHADA.xls';

    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="21"><b><font size="4">RELATORIO GERAL DE CARÊNCIAS</font></b></tr>';
    $html .= '</tr>';


    $html .= '<tr>';
    $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>UEE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>COD.UNIDADE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TIPO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>EIXO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>CURSO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>AREA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DISCIPLINA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MAT</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>VESP</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>NOT</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TOTAL</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MATRICULA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>NOME</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>VINCULO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MOTIVO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>INICIO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>FIM</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>Nº DO RIM</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DATA DE LANÇAMENTO</b></td>';

    foreach ($carencias as $carencia) {
        $html .= '<tr>';
        if ($carencia->nte < 10){
          $html .= '<td align="center">' . $carencia->nte . '</td>';
        }
        if ($carencia->nte >= 10) {
            $html .= '<td align="center">' . $carencia->nte . '</td>';  
        }
        $html .= '<td align="center">' . $carencia->municipio . '</td>';
        $html .= '<td align="center">' . $carencia->unidade_escolar . '</td>';
        $html .= '<td align="center">' . $carencia->cod_ue . '</td>';
        $html .= '<td align="center">' . $carencia->tipo_carencia . '</td>';
        $html .= '<td align="center">' . $carencia->eixo . '</td>';
        $html .= '<td align="center">' . $carencia->curso . '</td>';
        $html .= '<td align="center">' . $carencia->area . '</td>';
        $html .= '<td align="center">' . $carencia->disciplina . '</td>';
        $html .= '<td align="center">' . $carencia->matutino . '</td>';
        $html .= '<td align="center">' . $carencia->vespertino . '</td>';
        $html .= '<td align="center">' . $carencia->noturno . '</td>';
        $html .= '<td align="center">' . $carencia->total . '</td>';
        $html .= '<td align="center">' . $carencia->cadastro . '</td>';
        $html .= '<td align="center">' . $carencia->servidor . '</td>';
        $html .= '<td align="center">' . $carencia->vinculo . '</td>';
        $html .= '<td align="center">' . $carencia->motivo_vaga . '</td>';
        $html .= '<td align="center">' . $carencia->inicio_vaga . '</td>';
        $html .= '<td align="center">' . $carencia->fim_vaga . '</td>';
        $html .= '<td align="center">' . $carencia->num_rim . '</td>';
        $html .= '<td align="center">' . \Carbon\Carbon::parse($carencia->created_at)->subHours(3)->format('d/m/Y H:i:s') . '</td>';
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