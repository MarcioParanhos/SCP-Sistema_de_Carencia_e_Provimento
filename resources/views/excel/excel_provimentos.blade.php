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

    $provimentosMatutino = 0;
    $provimentosVespertino = 0;
    $provimentosNoturno = 0;
    $provimentosTotal = 0;

    $arquivo = 'SUPRIMENTO_DETALHADO.xls';

    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="24"><b><font size="4">VAGAS SUPRIDAS</font></b></tr>';
    $html .= '</tr>';


    $html .= '<tr>';
    $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>UEE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>COD.UNIDADE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DISCIPLINA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MAT</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>VESP</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>NOT</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TOTAL</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MATRICULA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>VINCULO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>REGIME</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TIPO DA CARÊNCIA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>FORMA DE SUPRIMENTO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TIPO DE MOVIMENTO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TIPO DE AULA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>SITUAÇÂO DO PROVIMENTO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DATA ENCAMINHAMENTO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>ASSUNÇÃO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DATA FIM DA VAGA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>SITUAÇÃO PCH</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>OBSERVAÇÕES CPM</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>OBSERVAÇÃO ADICIONADA POR:</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>OBSERVAÇÕES CPG</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DATA DE LANÇAMENTO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DATA DE ATUALIZAÇÃO</b></td>';
    foreach ($provimentosOrdenados as $provimento) {

        $provimentosMatutino = $provimentosMatutino + $provimento->provimento_matutino;
        $provimentosVespertino = $provimentosVespertino + $provimento->provimento_vespertino;
        $provimentosNoturno = $provimentosNoturno + $provimento->provimento_noturno;
        $provimentosTotal = $provimentosTotal + $provimento->total;

        $html .= '<tr>';
        $html .= '<td align="center">' . $provimento->nte . '</td>';
        $html .= '<td align="center">' . $provimento->municipio . '</td>';
        $html .= '<td align="center">' . $provimento->unidade_escolar . '</td>';
        $html .= '<td align="center">' . $provimento->cod_unidade . '</td>';
        $html .= '<td align="center">' . $provimento->disciplina . '</td>';
        $html .= '<td align="center">' . $provimento->provimento_matutino . '</td>';
        $html .= '<td align="center">' . $provimento->provimento_vespertino . '</td>';
        $html .= '<td align="center">' . $provimento->provimento_noturno . '</td>';
        $html .= '<td align="center">' . $provimento->total . '</td>';
        $html .= '<td align="center">' . $provimento->cadastro . '</td>';
        $html .= '<td align="center">' . $provimento->servidor . '</td>';
        $html .= '<td align="center">' . $provimento->vinculo . '</td>';
        $html .= '<td align="center">' . $provimento->regime . '</td>';
        $html .= '<td align="center">' . $provimento->tipo_carencia_provida . '</td>';
        $html .= '<td align="center">' . $provimento->forma_suprimento . '</td>';
        $html .= '<td align="center">' . $provimento->tipo_movimentacao . '</td>';
        $html .= '<td align="center">' . $provimento->tipo_aula . '</td>';
        if ($provimento->situacao_provimento === "tramite") {
            $html .= '<td align="center">EM TRÂMITE</td>';
        } else if ($provimento->situacao_provimento === "provida") {
            $html .= '<td align="center">PROVIDO</td>';
        }
        $html .= '<td align="center">' . $provimento->data_encaminhamento . '</td>';
        if (!$provimento->data_assuncao) {
            $html .= '<td align="center">PENDENTE</td>';
        }
        if ($provimento->data_assuncao) {
            $html .= '<td align="center">' . $provimento->data_assuncao . '</td>';
        }
        $html .= '<td align="center">' . $provimento->data_fim_by_temp . '</td>';
        $html .= '<td align="center">' . $provimento->pch . '</td>';
        $html .= '<td align="center">' . $provimento->obs . '</td>';

        // Dividindo pelo separador ' - ' e mantendo apenas o primeiro nome
        $obsParts = explode(' - ', $provimento->obs_cpg);
        $nomeUsuario = $obsParts[0];
        $observacao = implode(' - ', array_slice($obsParts, 1)); // Mantém o restante como observação

        $html .= '<td align="center">' . $nomeUsuario . '</td>';
        $html .= '<td align="center">' . $observacao . '</td>';

        $html .= '<td align="center">' . $provimento->created_at->subHours(3)->format('Y-m-d H:i:s') . '</td>';
        $html .= '<td align="center">' . $provimento->updated_at->subHours(3)->format('Y-m-d H:i:s') . '</td>';
        $html .= '</tr>';
    }

    $html .= '<tr>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TOTAL</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>' . $provimentosMatutino . '</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>' . $provimentosVespertino . '</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>' . $provimentosNoturno . '</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>' . $provimentosTotal . '</b></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '<td align="center"></td>';
    $html .= '</tr>';


    // Configurações header para forçar o download
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
    header("Content-Description: PHP Generated Data");
    // Envia o conteúdo do arquivo
    echo $html;
    exit; ?>
</body>

</html>