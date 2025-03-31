<?php
$arquivo = 'CARENCIA_DETALHADA.xls';

$html = '';
$html .= '<table border="1">';
$html .= '<tr>';
$html .= '<td bgcolor="#9cc2e5" align="center" colspan="13"><b><font size="4">RELATORIO GERAL DE CARÊNCIAS</font></b></tr>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>NTE</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>MUNICIPIO</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>UEE</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>COD.UNIDADE</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>TIPO</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>EIXO</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>CURSO</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>AREA</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>DISCIPLINA</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>MAT</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>VESP</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>NOT</b></td>';
$html .= '<td bgcolor="#9cc2e5" align="center"><b>TOTAL</b></td>';
$html .= '</tr>';

$subtotalMatutino = 0;
$subtotalVespertino = 0;
$subtotalNoturno = 0;
$subtotalTotal = 0;
$nteAtual = null;

foreach ($carencias as $carencia) {
    if ($nteAtual !== null && $nteAtual !== $carencia->nte) {
        // Exibe subtotal quando muda o NTE
        $html .= '<tr style="font-weight:bold;">';
        $html .= '<td colspan="9" align="right" style="background-color:#d3d3d3; font-weight:bold;">SUBTOTAL NTE ' . ($nteAtual < 10 ? '0' . $nteAtual : $nteAtual) . ':</td>';
        $html .= '<td align="center" style="background-color:#d3d3d3;">' . $subtotalMatutino . '</td>';
        $html .= '<td align="center" style="background-color:#d3d3d3;">' . $subtotalVespertino . '</td>';
        $html .= '<td align="center" style="background-color:#d3d3d3;">' . $subtotalNoturno . '</td>';
        $html .= '<td align="center" style="background-color:#d3d3d3;">' . $subtotalTotal . '</td>';
        $html .= '</tr>';
    
        // Reseta os subtotais
        $subtotalMatutino = 0;
        $subtotalVespertino = 0;
        $subtotalNoturno = 0;
        $subtotalTotal = 0;
    }
    

    $nteAtual = $carencia->nte;

    $subtotalMatutino += $carencia->matutino;
    $subtotalVespertino += $carencia->vespertino;
    $subtotalNoturno += $carencia->noturno;
    $subtotalTotal += $carencia->total;

    $html .= '<tr>';
    $html .= '<td align="center">' . $carencia->nte . '</td>';
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
    $html .= '</tr>';
}

// Exibe subtotal final do último NTE
if ($nteAtual !== null) {
    $html .= '<tr style="font-weight:bold;">';
        $html .= '<td colspan="9" align="right" style="background-color:#d3d3d3; font-weight:bold;">SUBTOTAL NTE ' . ($nteAtual < 10 ? '0' . $nteAtual : $nteAtual) . ':</td>';
        $html .= '<td align="center" style="background-color:#d3d3d3;">' . $subtotalMatutino . '</td>';
        $html .= '<td align="center" style="background-color:#d3d3d3;">' . $subtotalVespertino . '</td>';
        $html .= '<td align="center" style="background-color:#d3d3d3;">' . $subtotalNoturno . '</td>';
        $html .= '<td align="center" style="background-color:#d3d3d3;">' . $subtotalTotal . '</td>';
        $html .= '</tr>';
}

$html .= '</table>';

header('Content-Type: text/html; charset=UTF-8');
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename={$arquivo}");
header("Pragma: no-cache");
header("Expires: 0");
echo utf8_decode($html); // Converte para um formato mais compatível com Excel
exit;
