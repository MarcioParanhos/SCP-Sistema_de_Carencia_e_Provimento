<?php
$arquivo = 'CARENCIA_DETALHADA.xls';

// Captura as colunas selecionadas pelo usuário
$colunasSelecionadas = isset($_GET['columns']) ? explode(',', $_GET['columns']) : [];

// Lista completa de colunas disponíveis no relatório
$colunasDisponiveis = [
    'nte' => 'NTE',
    'municipio' => 'Município',
    'unidade_escolar' => 'UEE',
    'cod_ue' => 'Cod. Unidade',
    'tipo_carencia' => 'Tipo',
    'eixo' => 'Eixo',
    'curso' => 'Curso',
    'area' => 'Área',
    'motivo_vaga' => 'Motivo da Vaga',
    'disciplina' => 'Disciplina',
    'matutino' => 'MAT',
    'servidor' => 'Servidor',
    'cadastro' => 'Cadastro',
    'vespertino' => 'VESP',
    'noturno' => 'NOT',
    'total' => 'Total',
    'inicio_vaga' => 'Inicio da Vaga',
    'usuario' => 'Usuário',
    'created_at' => 'Data de Lançamento',
];

// Se o usuário não selecionar nada, incluir todas as colunas por padrão
if (empty($colunasSelecionadas)) {
    $colunasSelecionadas = array_keys($colunasDisponiveis);
}

// $colunaObrigatoria = 'usuario';

// // Verifica se a coluna obrigatória NÃO está na lista de colunas que o usuário selecionou.
// if (!in_array($colunaObrigatoria, $colunasSelecionadas)) {
//     // Adiciona a coluna obrigatória no início da lista.
//     // Usar array_unshift() é uma boa prática para colocar campos-chave no começo do relatório.
//     array_unshift($colunasSelecionadas, $colunaObrigatoria);
// }

$html = '';
$html .= '<table border="1">';
$html .= '<tr>';
$html .= '<td bgcolor="#9cc2e5" align="center" colspan="' . count($colunasSelecionadas) . '"><b><font size="4">RELATÓRIO GERAL DE CARÊNCIAS</font></b></td>';
$html .= '</tr>';

// Cabeçalho da tabela baseado nas colunas escolhidas
$html .= '<tr>';
foreach ($colunasSelecionadas as $coluna) {
    if (isset($colunasDisponiveis[$coluna])) {
        $html .= '<td bgcolor="#9cc2e5" align="center"><b>' . $colunasDisponiveis[$coluna] . '</b></td>';
    }
}
$html .= '</tr>';

// Variáveis para armazenar subtotais
$subtotal = array_fill_keys(['matutino', 'vespertino', 'noturno', 'total'], 0);
$nteAtual = null;

// Percorre os dados
foreach ($carencias as $carencia) {
    if ($nteAtual !== null && $nteAtual !== $carencia->nte) {
        // Exibe subtotal quando muda o NTE
        $html .= '<tr style="font-weight:bold;">';
        $html .= '<td colspan="' . (count($colunasSelecionadas) - 4) . '" align="right" style="background-color:#d3d3d3; font-weight:bold;">SUBTOTAL NTE ' . ($nteAtual < 10 ? '0' . $nteAtual : $nteAtual) . ':</td>';
        foreach (['matutino', 'vespertino', 'noturno', 'total'] as $campo) {
            if (in_array($campo, $colunasSelecionadas)) {
                $html .= '<td align="center" style="background-color:#d3d3d3;">' . $subtotal[$campo] . '</td>';
            }
        }
        $html .= '</tr>';

        // Reseta os subtotais
        $subtotal = array_fill_keys(['matutino', 'vespertino', 'noturno', 'total'], 0);
    }

    $nteAtual = $carencia->nte;

    // Atualiza subtotais
    foreach (['matutino', 'vespertino', 'noturno', 'total'] as $campo) {
        if (isset($carencia->$campo)) {
            $subtotal[$campo] += $carencia->$campo;
        }
    }

    // Adiciona os dados da linha conforme colunas escolhidas
    $html .= '<tr>';
    foreach ($colunasSelecionadas as $coluna) {
        // Verifica se o valor da coluna existe; se não, insere uma célula vazia
        $valor = isset($carencia->$coluna) ? htmlspecialchars($carencia->$coluna) : ''; 
        $html .= '<td align="center">' . $valor . '</td>';
    }
    $html .= '</tr>';
}

// Exibe subtotal final do último NTE
if ($nteAtual !== null) {
    $html .= '<tr style="font-weight:bold;">';
    $html .= '<td colspan="' . (count($colunasSelecionadas) - 4) . '" align="right" style="background-color:#d3d3d3; font-weight:bold;">SUBTOTAL NTE ' . ($nteAtual < 10 ? '0' . $nteAtual : $nteAtual) . ':</td>';
    foreach (['matutino', 'vespertino', 'noturno', 'total'] as $campo) {
        if (in_array($campo, $colunasSelecionadas)) {
            $html .= '<td align="center" style="background-color:#d3d3d3;">' . $subtotal[$campo] . '</td>';
        }
    }
    $html .= '</tr>';
}

$html .= '</table>';

// Configura os headers para o download do Excel
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename={$arquivo}");
header("Pragma: no-cache");
header("Expires: 0");

// Exibe a planilha gerada
echo utf8_decode($html);
exit;
?>
