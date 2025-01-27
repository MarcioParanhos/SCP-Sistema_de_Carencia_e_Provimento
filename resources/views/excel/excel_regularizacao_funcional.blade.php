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

    $arquivo = 'RELAÇÃO DE REGULARIZAÇÕES FUNCIONAIS.xls';

    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="19"><b><font size="4">RELAÇÃO DE REGULARIZAÇÕES FUNCIONAIS</font></b></tr>';
    $html .= '</tr>';


    $html .= '<tr>';
    $html .= '<td bgcolor="##9cc3e6" align="center" colspan="3"><b><font size="3">SERVIDOR</font></b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="4"><b><font size="3">UNIDADE DE ORIGEM</font></b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="4"><b><font size="3">UNIDADE DE DESTINO</font></b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="8  "><b><font size="3">INFORMAÇÕES GERAIS</font></b></td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NOME</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MATRICULA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>VINCULO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>NTE DE ORIGEM</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO DE ORIGEM</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>UNIDADE ESCOLAR DE ORIGEM</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>COD. UNIDADE DE ORIGEM</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>NTE DE DESTINO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO DE DESTINO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>UNIDADE ESCOLAR DE DESTINO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>COD UNIDADE DE DESTINO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TIPO DA REGULARIZAÇÃO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DATA DA ASSUNÇÃO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>ETAPA CPM</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>OBSERVAÇÃO CPM</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>ETAPA CGI</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>OBSERVAÇÃO CGI</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>ETAPA CPG</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>OBSERVAÇÃO CPG</b></td>';
    $html .= '</tr>';


    foreach ($regularizacões_funcionais as $regularizacão_funcional) {
        $html .= '<tr>';
        $html .= '<td align="center">' . $regularizacão_funcional->servidor->nome . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->servidor->cadastro . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->servidor->vinculo . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->ueeOrigem->nte . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->ueeOrigem->municipio . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->ueeOrigem->unidade_escolar . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->ueeOrigem->cod_unidade . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->ueeDestino->nte . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->ueeDestino->municipio . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->ueeDestino->unidade_escolar . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->ueeDestino->cod_unidade . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->tipo_regularizacao . '</td>';
        $html .= '<td align="center">' . \Carbon\Carbon::parse($regularizacão_funcional->data)->format('d/m/Y') . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->situacao_cpm . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->obs_cpm . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->situacao_cgi . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->obs_cgi . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->situacao_cpg . '</td>';
        $html .= '<td align="center">' . $regularizacão_funcional->obs_cpg . '</td>';
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