<?php

namespace App\Http\Controllers;

use App\Models\VagaReserva;
use App\Models\Servidore;
use App\Models\Provimento;
use App\Models\Carencia;
use App\Models\NumCop;
use Illuminate\Support\Facades\DB;   // Essencial para usar transações
use Illuminate\Support\Facades\Log;   // Para registrar erros
use Illuminate\Support\Facades\Auth; // Para obter o usuário logado
use Illuminate\Support\Str;
use App\Models\Forma_suprimento;

use Illuminate\Http\Request;
use Carbon\Carbon;

class VagareservaController extends Controller
{

    public function create(Request $request)
    {


        $servidor = Servidore::where('cadastro', 'LIKE', '%' . $request->cadastro . '%')->first();

        $servidor_id = $servidor->id;


        $vaga_reserva = new VagaReserva;

        $vaga_reserva->carencia_id = $request->carencia_id;
        $vaga_reserva->data_inicio = $request->reserva_inicio;
        $vaga_reserva->data_fim = $request->reserva_fim;
        $vaga_reserva->justificativa = $request->justificativa_reserva;
        $vaga_reserva->servidor_id = $servidor_id;
        $vaga_reserva->user_id = $request->user_id;
        $vaga_reserva->forma_suprimento = $request->forma_suprimento;
        $vaga_reserva->tipo_movimentacao = $request->tipo_movimentacao;
        $vaga_reserva->tipo_aula = $request->tipo_aula;


        if ($vaga_reserva->save()) {
            return  redirect()->to(url()->previous())->with('msg', 'Vaga reservada com sucesso!');
        }
    }

    public function index(Request $request)
    {
        // ETAPA 1: INICIA A CONSTRUÇÃO DA QUERY
        $query = VagaReserva::query()->with(['servidor', 'carencia']);

        // Detecta se o request trouxe filtros
        $hasFilters = $request->filled('nte_seacrh')
            || $request->filled('municipio_search')
            || $request->filled('search_uee')
            || $request->filled('search_num_cop');

        // Considera inputs do form que não são apenas _token/_method/page (para detectar submit vazio)
        $nonEssentialInputs = $request->except(['page', '_token', '_method']);

        // ETAPA 2: APLICAÇÃO CONDICIONAL DOS FILTROS (aplica apenas se vierem no request)
        if ($hasFilters) {
            if ($request->filled('nte_seacrh')) {
                $query->whereHas('carencia', function ($subQuery) use ($request) {
                    $subQuery->where('nte', $request->nte_seacrh);
                });
            }

            if ($request->filled('municipio_search')) {
                $query->whereHas('carencia', function ($subQuery) use ($request) {
                    $subQuery->where('municipio', $request->municipio_search);
                });
            }


            if ($request->filled('search_uee')) {
                $query->whereHas('carencia', function ($subQuery) use ($request) {
                    $subQuery->where('unidade_escolar', $request->search_uee);
                });
            }

            if ($request->filled('search_num_cop')) {
                $numCop = trim($request->input('search_num_cop'));
                if ($numCop === 'N/D') {
                    $query->whereNull('num_cop');
                } else {
                    $query->where('num_cop', $numCop);
                }
            }

            // Executa a query com os filtros e grava no session (sobrescreve)
            $vagas_reservadas = $query->get();
            session()->put('vagas_reservadas', $vagas_reservadas);
        } else {
            // Se não vieram filtros no request, tenta reutilizar o que já está em session
            $vagas_reservadas = session('vagas_reservadas');

            // Se não houver nada na session, busca tudo e grava
            if (!$vagas_reservadas) {
                $vagas_reservadas = $query->get();
                session()->put('vagas_reservadas', $vagas_reservadas);
            }
        }

        // Preparação do resumo a partir do conjunto (sem alterar a lógica existente)
        $resumo = [];
        foreach ($vagas_reservadas as $reserva) {
            if (!$reserva->carencia || empty($reserva->bloco)) {
                continue;
            }

            $grouping_key = $reserva->bloco;

            if (!isset($resumo[$grouping_key])) {
                $resumo[$grouping_key] = [
                    'servidor' => $reserva->servidor,
                    'carencia' => $reserva->carencia,
                    'bloco_id' => $reserva->bloco,
                    'num_cop' => $reserva->num_cop,
                    'num_sei' => $reserva->num_sei,
                    'mat' => 0,
                    'vesp' => 0,
                    'not' => 0,
                    'total' => 0,
                    'disciplinas' => [],
                    'unidades_escolares' => [],
                    'carencia_ids' => [],
                ];
            }

            $resumo[$grouping_key]['mat'] += $reserva->carencia->matutino ?? 0;
            $resumo[$grouping_key]['vesp'] += $reserva->carencia->vespertino ?? 0;
            $resumo[$grouping_key]['not'] += $reserva->carencia->noturno ?? 0;
            $resumo[$grouping_key]['total'] += $reserva->carencia->total ?? 0;

            if (!empty($reserva->carencia->unidade_escolar)) {
                $resumo[$grouping_key]['unidades_escolares'][] = $reserva->carencia->unidade_escolar;
            }

            if (!empty($reserva->carencia->disciplina)) {
                $resumo[$grouping_key]['disciplinas'][] = $reserva->carencia->disciplina;
            }
            $resumo[$grouping_key]['carencia_ids'][] = $reserva->carencia->id;
        }

        foreach ($resumo as &$item) {
            $item['disciplinas'] = array_unique($item['disciplinas']);
            $item['unidades_escolares'] = array_unique($item['unidades_escolares']); // Adicionado
            $item['carencia_ids'] = array_unique($item['carencia_ids']);
        }


        // ETAPA 4: PREPARAÇÃO DE DADOS ADICIONAIS PARA OS FILTROS DA VIEW
        $unidades_escolares = VagaReserva::query()->with('carencia')
            ->get()->pluck('carencia.unidade_escolar')->filter()->unique()->sort()->values();
        $numero_do_cop = NumCop::all();

        // ETAPA 5: ENVIO PARA A VIEW
        return view("provimento.reserva_vaga", [
            'resumo_reservas' => $resumo,
            'numero_do_cop' => $numero_do_cop,
            'unidades_escolares' => $unidades_escolares,
            'input' => $request->all() // Envia os valores dos filtros de volta para a view
        ]);
    }

    public function createProvimento(Request $request)
    {

        $user = Auth::user();
        $carencia_ids = array_map('trim', explode(',', $request->input('carencia_ids')));

        // Buscando as carências associadas aos IDs
        $carencias = Carencia::whereIn('id', $carencia_ids)->get();


        foreach ($carencias as $carencia) {

            // Buscar a reserva de vaga relacionada a esta carência
            $vaga_reserva = VagaReserva::where('carencia_id', $carencia->id)->first();

            // Criando o provimento e salvando as informações da carência, reserva e servidor
            $provimento = new Provimento();
            $provimento->nte = $carencia->nte;
            $provimento->municipio = $carencia->municipio;
            $provimento->unidade_escolar = $carencia->unidade_escolar;
            $provimento->cod_unidade = $carencia->cod_ue;
            $provimento->ano_ref = $carencia->ano_ref;
            $provimento->provimento_matutino = $carencia->matutino;
            $provimento->provimento_vespertino = $carencia->vespertino;
            $provimento->provimento_noturno = $carencia->noturno;
            $provimento->total = $carencia->total;
            $provimento->id_carencia = $carencia->id;
            $provimento->disciplina = $carencia->disciplina;
            $provimento->usuario = $user->name;
            $provimento->data_encaminhamento = $request->data_assuncao;
            $provimento->pch = "PENDENTE";
            $provimento->metodo = "RESERVA";
            $provimento->num_cop = $vaga_reserva->num_cop;
            $provimento->num_sei = $vaga_reserva->num_sei;
            $provimento->situacao = "DESBLOQUEADO";
            $provimento->situacao_provimento = "tramite";
            $provimento->obs = "Vaga provida mediante a reserva de vaga";
            $provimento->tipo_carencia_provida = $carencia->tipo_carencia;

            // Adicionando informações do servidor e da reserva

            $provimento->servidor = $vaga_reserva->nome_servidor;
            $provimento->cadastro = $vaga_reserva->matricula_cpf;
            $provimento->vinculo = "REDA";
            $provimento->regime = "20";

            // Campos da reserva
            $provimento->tipo_movimentacao = $vaga_reserva->tipo_movimentacao;
            $provimento->tipo_aula = $vaga_reserva->tipo_aula;
            $provimento->forma_suprimento = $vaga_reserva->forma_suprimento;


            // Salvando o provimento
            if ($provimento->save()) {
                // Zerando os campos da carência
                $carencia->matutino = 0;
                $carencia->vespertino = 0;
                $carencia->noturno = 0;
                $carencia->total = 0;
                $carencia->save();

                // Excluindo a vaga reserva vinculada
                if ($vaga_reserva) {
                    $vaga_reserva->delete();
                }
            }
        }

        return redirect()->route('reserva.index')->with('msg', 'success_provimento_de_reserva');
    }

    public function receberCarenciasParaReserva(Request $request)
    {
        // 1. Validação: Garante que 'carencia_ids' foi enviado e é um array com pelo menos um item.
        $validatedData = $request->validate([
            'carencia_ids'   => 'required|array|min:1',
            'carencia_ids.*' => 'integer|exists:carencias,id' // Garante que cada ID existe na tabela 'carencias'
        ]);

        $idsParaReservar = $validatedData['carencia_ids'];

        // 2. Transação de Banco de Dados: Garante a consistência dos dados.
        // Se ocorrer um erro em qualquer uma das inserções, todas as anteriores são desfeitas (rollback).
        DB::beginTransaction();

        try {

            $blocoId = time() . '-' . Str::random(5);
            // 3. Loop para criar uma reserva para cada ID recebido.
            foreach ($idsParaReservar as $carenciaId) {

                // Previne a criação de uma reserva duplicada para a mesma carência
                $reservaExistente = VagaReserva::where('carencia_id', $carenciaId)->first();
                if ($reservaExistente) {
                    // Se já existir, podemos pular ou lançar um erro.
                    // Lançar um erro é mais seguro para parar a transação.
                    throw new \Exception("A carência com ID {$carenciaId} já está reservada.");
                }

                $reserva = new VagaReserva();
                $reserva->carencia_id = $carenciaId;
                $reserva->bloco = $blocoId; // Atribui o mesmo ID de bloco para todas as reservas deste lote.

                // **PASSO FUTURO:** Adicionar as outras informações virá aqui.
                // Por enquanto, podemos deixar os outros campos como nulos (se o seu banco de dados permitir)
                // ou com valores padrão.
                // Exemplo:
                // $reserva->servidor_id = $request->servidor_id; // virá do request futuramente
                // $reserva->data_inicio = $request->data_inicio;

                // Exemplo de como pegar o ID do usuário autenticado:
                // $reserva->user_id = Auth::id();

                $reserva->save();
            }

            // 4. Efetiva a Transação: Se o loop terminou sem erros, salva tudo no banco.
            DB::commit();

            // 5. Retorna uma resposta de sucesso para o JavaScript.
            return response()->json([
                'success' => true,
                'message' => count($idsParaReservar) . ' carência(s) reservada(s) com sucesso!'
            ]);
        } catch (\Exception $e) {
            // 6. Desfaz a Transação: Se qualquer erro ocorreu, desfaz tudo.
            DB::rollBack();

            // Registra o erro para depuração
            Log::error('Erro ao salvar reservas em bloco: ' . $e->getMessage());

            // 7. Retorna uma resposta de erro para o JavaScript.
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar as reservas: ' . $e->getMessage()
            ], 500); // HTTP 500: Internal Server Error
        }
    }

    public function detalharBloco($blocoId)
    {
        $reservasDoBloco = VagaReserva::where('bloco', $blocoId)
            ->with('carencia')
            ->get();
        $forma_suprimentos = Forma_suprimento::all();

        if ($reservasDoBloco->isEmpty()) {
            abort(404, 'Bloco de reserva não encontrado.');
        }

        // 1. Pega os valores atuais a partir da primeira reserva do bloco.
        // Como todos no bloco devem ter os mesmos valores, pegar o primeiro é suficiente.
        $primeiraReserva = $reservasDoBloco->first();
        $numCopAtual = $primeiraReserva->num_cop;
        $numSeiAtual = $primeiraReserva->num_sei; // Pega o 'num_sei' que já está salvo
        $cpf_servidor = $primeiraReserva->matricula_cpf;
        $nome_servidor = $primeiraReserva->nome_servidor;
        $data_indicacao = $primeiraReserva->data_indicacao;
        $tipo_movimentacao = $primeiraReserva->tipo_movimentacao;
        $tipo_aula = $primeiraReserva->tipo_aula;
        $forma_suprimento = $primeiraReserva->forma_suprimento;
        $justificativa = $primeiraReserva->justificativa;
        $checked_diploma = $primeiraReserva->checked_diploma;

        // 1.a: Determina a data de criação do bloco a partir da primeira reserva (fallback defensivo)
        $createdAtRaw = $primeiraReserva->created_at ?? ($reservasDoBloco->first()->created_at ?? null);
        $bloco_created_at = null;
        if ($createdAtRaw) {
            // O banco guarda com +3 horas; ajustar subtraindo 3 horas e formatar no padrão brasileiro
            $bloco_created_at = Carbon::parse($createdAtRaw)->subHours(3)->format('d/m/Y H:i');
        }

        // 2. Busca a lista completa de números de COP para preencher o select.
        $listaNumCop = NumCop::orderBy('num')->get();

        // ... (cálculo de totais, se houver) ...

        // 3. Envia todos os dados necessários para a view.
        return view('reserva_de_vaga.show_reserva', [
            'reservas' => $reservasDoBloco,
            'blocoId' => $blocoId,
            'num_cop_atual' => $numCopAtual,
            'num_sei_atual' => $numSeiAtual,
            'lista_num_cop' => $listaNumCop,
            'cpf_servidor' => $cpf_servidor,
            'nome_servidor' => $nome_servidor,
            'data_indicacao' => $data_indicacao,
            'tipo_movimentacao' => $tipo_movimentacao,
            'tipo_aula' => $tipo_aula,
            'forma_suprimentos' => $forma_suprimentos,
            'forma_suprimento' => $forma_suprimento,
            'justificativa' => $justificativa,
            'checked_diploma' => $checked_diploma,
            'bloco_created_at' => $bloco_created_at,
        ]);
    }

    public function imprimirTermo($blocoId)
    {

        $reservasDoBloco = VagaReserva::where('bloco', $blocoId)
            ->with('carencia')
            ->get();
        $num_cop = NumCop::all();

        // 2. Validação: Se nenhuma reserva for encontrada para este bloco,
        // é uma boa prática retornar um erro 404 (Não Encontrado).
        if ($reservasDoBloco->isEmpty()) {
            abort(404, 'Bloco de reserva não encontrado.');
        }

        // 3. Envia os dados encontrados para a view.
        // A view 'show_reserva' agora terá acesso à coleção '$reservasDoBloco' e ao '$blocoId'.
        return view('relatorios.termo_reserva', [
            'reservas' => $reservasDoBloco,
            'blocoId' => $blocoId,
            'num_cop' => $num_cop
        ]);
    }

    public function update(Request $request)
    {
        // ETAPA 1: VALIDAÇÃO DOS DADOS DE ENTRADA
        $validatedData = $request->validate([
            'bloco_id' => 'required|string|exists:vagas_reservas,bloco',
            'num_cop' => 'nullable|string|max:255',
            'num_sei' => 'nullable|string|max:255',
        ]);

        try {
            // ETAPA 2: TRANSAÇÃO ATÔMICA PARA GARANTIR INTEGRIDADE

            $blocoId = $validatedData['bloco_id'];
            $numCopNovo = $validatedData['num_cop'];
            $numSeiNovo = $validatedData['num_sei'];

            // 2.1: Busca a reserva atual.
            $reservaAtual = VagaReserva::where('bloco', $blocoId)->firstOrFail();
            $numCopAntigo = $reservaAtual->num_cop;

            // 2.2: A lógica de atualização da quantidade só é executada se houver mudança.
            if ($numCopAntigo !== $numCopNovo) {

                // Se existia um COP antigo, devolvemos a unidade ao estoque.
                if ($numCopAntigo) {
                    NumCop::where('num', $numCopAntigo)->increment('quantidade');
                }

                // Se um novo COP foi atribuído, tentamos subtrair do estoque.
                if (!empty($numCopNovo)) {
                    $cop = NumCop::where('num', $numCopNovo)->firstOrFail();

                    // Valores de bloqueio específicos por número de COP
                    $blockingLimits = [
                        '365/2025' => 100,
                        '247/2025' => 1280,
                    ];

                    if (isset($blockingLimits[$cop->num]) && intval($cop->quantidade) === $blockingLimits[$cop->num]) {
                        return redirect()->back()->with('msg', 'error_update_reserva');
                    }

                    // Protege contra decremento abaixo de zero
                    if (intval($cop->quantidade) <= 0) {
                        return redirect()->back()->with('msg', 'error_update_reserva');
                    }

                    $cop->decrement('quantidade');
                }
            }

            // 2.3: OPERAÇÃO DE ATUALIZAÇÃO PRINCIPAL
            VagaReserva::where('bloco', $blocoId)
                ->update([
                    'num_cop' => $numCopNovo,
                    'num_sei' => $numSeiNovo,
                ]);


            // ETAPA 3: FEEDBACK DE SUCESSO
            return redirect()->back()->with('msg', 'success_update_reserva');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Erro específico se o NumCop não for encontrado
            return redirect()->back()->with('error', "Operação cancelada: O número de COP informado não é válido.");
        } catch (\Exception $e) {

            // ETAPA 4: TRATAMENTO DE ERROS GERAIS
            Log::error('Falha ao atualizar bloco de reservas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar a sua solicitação.');
        }
    }

    public function updateServidor(Request $request)
    {
        $validatedData = $request->validate([
            'bloco_id' => 'required|string|exists:vagas_reservas,bloco',
            'matricula_cpf' => 'nullable|string',
            'data_indicacao' => 'nullable|date',
            'forma_suprimento' => 'nullable|string',
            'tipo_movimentacao' => 'nullable|string',
            'tipo_aula' => 'nullable|string',
            'nome_servidor' => 'nullable|string',
            'justificativa' => 'nullable|string',
            'checked_diploma' => 'nullable|string',
        ]);

        try {


            VagaReserva::where('bloco', $validatedData['bloco_id'])
                ->update([
                    'matricula_cpf' => $validatedData['matricula_cpf'],
                    'nome_servidor' => $validatedData['nome_servidor'],
                    'data_indicacao' => $validatedData['data_indicacao'],
                    'forma_suprimento' => $validatedData['forma_suprimento'],
                    'tipo_movimentacao' => $validatedData['tipo_movimentacao'],
                    'tipo_aula' => $validatedData['tipo_aula'],
                    'justificativa' => $validatedData['justificativa'],
                    'checked_diploma' => $validatedData['checked_diploma'],
                ]);

            return redirect()->back()->with('msg', 'success_update_servidor');
        } catch (\Exception $e) {
            Log::error('Falha ao associar servidor ao bloco: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao tentar associar o servidor.');
        }
    }

    public function excel_reservas()
    {
        $vagas_reservadas = session()->get('vagas_reservadas');

        if (!$vagas_reservadas || $vagas_reservadas->isEmpty()) {
            return redirect()->back()->with('error', 'Não há dados para exportar.');
        }


        $reservas_agrupadas = $vagas_reservadas->groupBy('bloco')->sortKeys();

        return view('excel.excel_reservas', [
            'reservas_agrupadas' => $reservas_agrupadas
        ]);
    }

    public function destroyBloco($blocoId)
    {
        try {
            // A transação garante que a devolução do COP e a exclusão das reservas
            // ocorram de forma atômica, mantendo a integridade dos dados.
            DB::transaction(function () use ($blocoId) {

                // 1. Busca a primeira reserva do bloco para validação e para obter o Nº COP.
                // Usar first() é mais eficiente do que carregar toda a coleção com get().
                $primeiraReserva = VagaReserva::where('bloco', $blocoId)->first();

                // 2. Validação: Se não houver nenhuma reserva, o bloco é inválido.
                if (!$primeiraReserva) {
                    throw new \Exception('Nenhum bloco de reserva encontrado para exclusão.');
                }

                // 3. Lógica de Negócio: Devolve a quantidade ao estoque do COP, se houver.
                $numCopDoBloco = $primeiraReserva->num_cop;
                if ($numCopDoBloco) {
                    // O método increment() é atômico e seguro.
                    NumCop::where('num', $numCopDoBloco)->increment('quantidade');
                }

                // 4. Exclusão em Massa: Remove todas as reservas associadas ao bloco.
                VagaReserva::where('bloco', $blocoId)->delete();
            });

            // 5. Feedback de Sucesso: Redireciona para a listagem com uma mensagem clara.
            // **Lembre-se de substituir 'nome.da.sua.rota.index' pelo nome real da sua rota de listagem.**
            return redirect()->route('reserva.index')->with('msg', 'delete_reserva');
        } catch (\Exception $e) {
            // 6. Tratamento de Erros: A transação é revertida automaticamente em caso de falha.
            Log::error("Falha ao excluir o bloco de reserva '{$blocoId}': " . $e->getMessage());

            return redirect()->back()->with('error', 'Ocorreu um erro ao tentar excluir o bloco de reserva.');
        }
    }
}
