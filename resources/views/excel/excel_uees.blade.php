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

    $categoriasUnicas = [];
    foreach ($uees as $uee) {
        $categorias = trim($uee->categorias, '[]');
        $categoriasArray = explode(',', $categorias);

        foreach ($categoriasArray as $categoria) {
            $categoriaSemAspas = trim($categoria, '"');
            if (!in_array($categoriaSemAspas, $categoriasUnicas)) {
                $categoriasUnicas[] = $categoriaSemAspas;
            }
        }
    }

    if ($ueesCateroriasForExcel) {
        $tdNumber = str_word_count($ueesCateroriasForExcel);
    } else {
        $tdNumber = count($categoriasUnicas); 
    }


    $arquivo = 'RELAÇÃO DE UNIDADES ESCOLARES.xls';

    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= "<td bgcolor='#9cc2e5' align='center' colspan='" . (7 + $tdNumber) . "'><b><font size='5'>RELAÇÃO DE UNIDADES ESCOLARES</font></b></td>";
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td font size="4" bgcolor="##9cc2e5" align="center"><b>NTE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>MUNICIPIO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>UEE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>COD.UNIDADE</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>TIPOLOGIA</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>HOMOLOGAÇÃO</b></td>';
    $html .= '<td bgcolor="##9cc2e5" align="center"><b>POSSUI CARÊNCIA?</b></td>';

    if ($ueesCateroriasForExcel) {
        $categoriaMaiuscula = strtoupper($ueesCateroriasForExcel);
        $html .= '<td bgcolor="#9cc2e5" align="center"><b>' . $categoriaMaiuscula . '</b></td>';
    }
     else {
        $categoriaLabels = [
            "Mediacao Tecnologica" => "MEDIAÇÃO TECNOLÓGICA",
            "Educacao Basica" => "EDUCAÇÃO BÁSICA",
            "Quilombola" => "QUILOMBOLA",
            "Tempo integral" => "TEMPO INTEGRAL",
            "Profissional" => "EDUCAÇÃO PROFISSIONAL",
            "Assentamento" => "ASSENTAMENTO",
            "Prisional" => "PRISIONAL / CASE",
            "Indigena" => "INDÍGENA",
            "Educacao Especial" => "EDUCAÇÃO ESPECIAL",
            "No Campo" => "NO CAMPO",
            "sedeCemit" => "SEDE / CEMIT",
        ];
        
        foreach ($categoriasUnicas as $categoria) {
            $displayCategoria = !empty($categoria) ? $categoria : 'SEM CATEGORIA';
        
            if (isset($categoriaLabels[$categoria])) {
                $label = $categoriaLabels[$categoria];
            } else {
                $label = $displayCategoria;
            }
        
            $html .= '<td bgcolor="##9cc2e5" align="center"><b>' . $label . '</b></td>';
        }
    }
    

    $html .= '</tr>';

    foreach ($uees as $uee) {
        $html .= '<tr>';
        if ($uee->nte < 10) {
            $html .= '<td align="center">' . ' 0' . $uee->nte . '</td>';
        } else {
            $html .= '<td align="center">' . $uee->nte . '</td>';
        }
        $html .= '<td align="center">' . $uee->municipio . '</td>';
        $html .= '<td align="center">' . $uee->unidade_escolar . '</td>';
        $html .= '<td align="center">' . $uee->cod_unidade . '</td>';
        $html .= '<td align="center">' . $uee->tipo . '</td>';
        $html .= '<td align="center">' . $uee->situacao . '</td>';
        if (($uee->carencia === "SIM") && ($uee->situacao === "HOMOLOGADA")) {
            $html .= '<td align="center">SIM</td>';
        } elseif (($uee->carencia != "SIM") && ($uee->situacao === "HOMOLOGADA")) {
            $html .= '<td align="center">NÃO</td>';
        } else {
            $html .= '<td align="center"></td>'; 
        }
        // $html .= '<td align="center">' . $uee->carencia . '</td>';


        if ($ueesCateroriasForExcel) {

            $html .= '<td align="center">' . 'x' . '</td>';
    
        } else {
            foreach ($categoriasUnicas as $categoria) {

                $categoriasUee = array_map(function ($categoria) {
                    return trim(trim($categoria), '"');
                }, explode(',', trim($uee->categorias, '[]')));
    
                $marcaX = in_array($categoria, $categoriasUee) ? 'X' : '';
    
                $html .= '<td align="center">' . $marcaX . '</td>';
            }
        }
        


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