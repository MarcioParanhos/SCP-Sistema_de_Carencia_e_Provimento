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

    $arquivo = 'ENCAMINHAMENTOS.xls';

    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="23"><b><font size="4">ENCAMINHAMENTOS EFETIVOS</font></b></td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td bgcolor="##9cc3e6" align="center" colspan="5"><b><font size="3">SERVIDOR ENCAMINHADO</font></b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="4"><b><font size="3">UNIDADE DE ENCAMINHAMENTO</font></b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="10"><b><font size="3">SERVIDOR SUBISTITUIDO</font></b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center" colspan="4"><b><font size="3">INFORMAÇÕES GERAIS</font></b></td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>NOME DO SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>CPF</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DISCIPLINA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>COD. UNIDADE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>UNIDADE ESCOLAR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MATRICULA 1º SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>NOME 1º SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>REGIME 1º SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>VINCULO 1º SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>SITUAÇÃO 1º SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MATRICULA 2º SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>NOME 2º SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>REGIME 2º SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>VINCULO 2º SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>SITUAÇÃO 2º SERVIDOR</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DATA DE ENCAMINHAMENTO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>DATA DE ASSUNÇÃO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>PCH</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>OBSERVAÇÕES</b></td>';
    $html .= '</tr>';

    foreach ($provimentosOrdenados as $provimentosOrdenado) {
        $html .= '<tr>';
        if ($provimentosOrdenado->servidorEncaminhado->nte < 10) {
            $html .= '<td align="center">' . ' 0' . $provimentosOrdenado->servidorEncaminhado->nte . '</td>';
        }
        if ($provimentosOrdenado->servidorEncaminhado->nte >= 10) {
            $html .= '<td align="center">' . $provimentosOrdenado->servidorEncaminhado->nte . '</td>';
        }
        $html .= '<td align="center">' . $provimentosOrdenado->servidorEncaminhado->municipio . '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->servidorEncaminhado->nome . '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->servidorEncaminhado->cpf . '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->servidorEncaminhado->formacao . '</td>';
        if ($provimentosOrdenado->uee->nte < '10') {
            $html .= '<td align="center">' . ' 0' . $provimentosOrdenado->uee->nte . '</td>';
        }
        if ($provimentosOrdenado->uee->nte >= '10') {
            $html .= '<td align="center">' . $provimentosOrdenado->uee->nte . '</td>';
        }
        $html .= '<td align="center">' . $provimentosOrdenado->uee->municipio . '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->uee->cod_unidade . '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->uee->unidade_escolar . '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->servidorSubstituido->cadastro . '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->servidorSubstituido->nome . '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->servidorSubstituido->regime . '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->servidorSubstituido->vinculo . '</td>';
        if ($provimentosOrdenado->server_1_situation == 3) {
            $html .= '<td align="center">' . 'REAPROVEITADO NA UEE' . '</td>';
        } elseif ($provimentosOrdenado->server_1_situation == 1) {
            $html .= '<td align="center">' . 'EXCEDENTE' . '</td>';
        } elseif ($provimentosOrdenado->server_1_situation == 2) {
            $html .= '<td align="center">' . 'PROVIMENTO INCORRETO' . '</td>';
        } elseif ($provimentosOrdenado->server_1_situation == 4) {
            $html .= '<td align="center">' . 'EFETIVO EM LICENÇA' . '</td>';
        } elseif ($provimentosOrdenado->server_1_situation == 5) {
            $html .= '<td align="center">' . 'REDA DESLIGAMENTO' . '</td>';
        } elseif ($provimentosOrdenado->server_1_situation == 6) {
            $html .= '<td align="center">' . 'APOSENTADORIA' . '</td>';
        } elseif ($provimentosOrdenado->server_1_situation == 7) {
            $html .= '<td align="center">' . 'DEIXAR HORAS EXTRAS' . '</td>';
        } elseif ($provimentosOrdenado->server_1_situation == 8) {
            $html .= '<td align="center">' . 'COORD. PEDAGÓGICO' . '</td>';
        } elseif ($provimentosOrdenado->server_1_situation == 9) {
            $html .= '<td align="center">' . 'VAGA REAL' . '</td>';
        } else {
            $html .= '<td align="center">' . '-' . '</td>';
        }

        if ($provimentosOrdenado->segundoServidorSubstituido) {
            $html .= '<td align="center">' . $provimentosOrdenado->segundoServidorSubstituido->cadastro . '</td>';
            $html .= '<td align="center">' . $provimentosOrdenado->segundoServidorSubstituido->nome . '</td>';
            $html .= '<td align="center">' . $provimentosOrdenado->segundoServidorSubstituido->regime . '</td>';
            $html .= '<td align="center">' . $provimentosOrdenado->segundoServidorSubstituido->vinculo . '</td>';
            if ($provimentosOrdenado->server_2_situation == 3) {
                $html .= '<td align="center">' . 'REAPROVEITADO NA UEE' . '</td>';
            } elseif ($provimentosOrdenado->server_2_situation == 1) {
                $html .= '<td align="center">' . 'EXCEDENTE' . '</td>';
            } elseif ($provimentosOrdenado->server_2_situation == 2) {
                $html .= '<td align="center">' . 'PROVIMENTO INCORRETO' . '</td>';
            }  elseif ($provimentosOrdenado->server_2_situation == 4) {
                $html .= '<td align="center">' . 'EFETIVO EM LICENÇA' . '</td>';
            } elseif ($provimentosOrdenado->server_2_situation == 5) {
                $html .= '<td align="center">' . 'REDA DESLIGAMENTO' . '</td>';
            } elseif ($provimentosOrdenado->server_2_situation == 6) {
                $html .= '<td align="center">' . 'APOSENTADORIA' . '</td>';
            } elseif ($provimentosOrdenado->server_2_situation == 7) {
                $html .= '<td align="center">' . 'DEIXAR HORAS EXTRAS' . '</td>';
            } elseif ($provimentosOrdenado->server_2_situation == 8) {
                $html .= '<td align="center">' . 'COORD. PEDAGÓGICO' . '</td>';
            } elseif ($provimentosOrdenado->server_2_situation == 9) {
                $html .= '<td align="center">' . 'VAGA REAL' . '</td>';
            } else {
                $html .= '<td align="center">' . '-' . '</td>';
            }
        } else {
            $html .= '<td align="center">' . '-' . '</td>';
            $html .= '<td align="center">' . '-' . '</td>';
            $html .= '<td align="center">' . '-' . '</td>';
            $html .= '<td align="center">' . '-' . '</td>';
            $html .= '<td align="center">' . '-' . '</td>';
        }
        $html .= '<td align="center">' . \Carbon\Carbon::parse($provimentosOrdenado->data_encaminhamento)->format('d/m/Y') . '</td>';
        $html .= '<td align="center">';
        if (!empty($provimentosOrdenado->data_assuncao)) {
            $html .= \Carbon\Carbon::parse($provimentosOrdenado->data_assuncao)->format('d/m/Y');
        }
        $html .= '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->pch . '</td>';
        $html .= '<td align="center">' . $provimentosOrdenado->obs . '</td>';
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