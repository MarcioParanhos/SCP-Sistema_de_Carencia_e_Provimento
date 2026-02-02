<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

use App\Models\Uee;
use App\Models\Carencia;
use App\Models\Provimento;
use App\Models\Eixo_curso;
use App\Models\Disciplina;
use App\Models\Servidore;
use App\Models\Forma_suprimento;
use App\Models\ProvimentosEncaminhado;
use App\Models\ServidoresEncaminhado;
use App\Models\Log;
use App\Models\NumCop;


use Illuminate\Http\Request;

class ProvimentoController extends Controller
{
    /**
     * Converte string de tamanho de arquivo para KB
     */
    private function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])) / 1024);
        } else {
            return round($size / 1024);
        }
    }

    public function newProvimento()
    {

        return view('provimento.add_provimento');
    }

    public function searchUeeProvimento($codigo_unidade_provimento)
    {

        $data = Uee::where('cod_unidade', $codigo_unidade_provimento)->get();

        if ($data) {

            return Response()->json($data);
        }
    }

    public function newProvimentoByUee($codigo_unidade_provimento)
    {
        $anoRef = session()->get('ano_ref');
        $actualDate = Carbon::now();
        $formattedDate = $actualDate->format('Y-m-d');
        $carencias = Carencia::where('cod_ue', $codigo_unidade_provimento)
            ->where('ano_ref', $anoRef)
            ->where('total', '>', 0)
            ->where(function ($query) use ($formattedDate) {
                $query->whereNull('fim_vaga')
                    ->orWhere('fim_vaga', '>', $formattedDate);
            })
            ->doesntHave('vagaReserva')
            ->get();

        $provimentos = Provimento::where('cod_unidade', $codigo_unidade_provimento)->where('ano_ref', $anoRef)->get();
        $num_cop = NumCop::all();

        $uee = Uee::where('cod_unidade', $codigo_unidade_provimento)->firstOrFail();
        $forma_suprimentos = Forma_suprimento::all();

        return view('provimento.show_uee_provimento', [
            'provimentos' => $provimentos,
            'carencias' => $carencias,
            'uee' => $uee,
            'forma_suprimentos' => $forma_suprimentos,
            'num_cop' => $num_cop,
        ]);
    }

    public function addNewProvimento(Request $request)
    {
        // O set_time_limit(0) é uma medida de segurança, mas a otimização abaixo
        // é a solução real para o problema de performance.
        set_time_limit(0);

        // A sua lógica para prevenir submissões duplicadas é uma excelente prática de UX.
        $lastExecution = $request->session()->get('last_execution');
        if ($lastExecution && Carbon::now()->diffInSeconds($lastExecution) < 5) {
            return redirect()->to(url()->previous());
        }
        $request->session()->put('last_execution', Carbon::now());

        $data = $request->session()->get('data');
        if (empty($data)) {
            return redirect()->to(url()->previous())->with('msg', 'carência inexistente');
        }

        // Validação de arquivo/data quando marcar como PROVIDA
        if ($request->input('situacao_provimento') === 'provida') {
            $request->validate([
                'arquivo_comprobatorio' => '|file|mimes:pdf,jpeg,jpg|max:5120', // 5MB
                'data_assuncao' => 'required|date',
            ], [
                'arquivo_comprobatorio.mimes' => 'O arquivo deve ser do tipo PDF, JPEG ou JPG.',
                'arquivo_comprobatorio.max' => 'O arquivo não pode ser maior que 5MB.',
                'data_assuncao.required' => 'A data de assunção é obrigatória quando a situação for PROVIDA.',
            ]);
        }

        // Usar uma transação é crucial para garantir a integridade dos dados.
        // Se qualquer parte falhar, tudo é revertido.
        try {
            DB::transaction(function () use ($request, $data) {
                $anoRef = session()->get('ano_ref');

                // Prepare file once (same file will be attached to all created provimentos)
                $uploadedFile = null;
                $storedFilename = null;
                if ($request->hasFile('arquivo_comprobatorio')) {
                    $uploadedFile = $request->file('arquivo_comprobatorio');
                    $storedFilename = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
                    // store file now (will overwrite if multiple provimentos created)
                    $uploadedFile->storeAs('provimentos', $storedFilename, 'public');
                }

                foreach ($data as $carenciaId) {
                    $matutino = $request->provimento_matutino[$carenciaId] ?? 0;
                    $vespertino = $request->provimento_vespertino[$carenciaId] ?? 0;
                    $noturno = $request->provimento_noturno[$carenciaId] ?? 0;
                    $total = $matutino + $vespertino + $noturno;

                    // Pula para a próxima iteração se nenhum provimento foi feito para esta carência.
                    if ($total <= 0) {
                        continue;
                    }

                    // Busca a carência. findOrFail é mais limpo e seguro.
                    $carencia = Carencia::findOrFail($carenciaId);

                    // Cria o provimento usando Mass Assignment (requer a propriedade $fillable no Model).
                    $provimento = Provimento::create([
                        'nte' => $request->nte,
                        'cod_unidade' => $request->cod_unidade,
                        'unidade_escolar' => $request->unidade_escolar,
                        'municipio' => $request->municipio,
                        'cadastro' => $request->cadastro,
                        'servidor' => $request->servidor,
                        'vinculo' => $request->vinculo,
                        'regime' => $request->regime,
                        'forma_suprimento' => $request->forma_suprimento,
                        'tipo_movimentacao' => $request->tipo_movimentacao,
                        'tipo_aula' => $request->tipo_aula,
                        'num_cop' => $request->num_cop,
                        'data_assuncao' => $request->data_assuncao,
                        'data_encaminhamento' => $request->data_encaminhamento,
                        'provimento_matutino' => $matutino,
                        'provimento_vespertino' => $vespertino,
                        'provimento_noturno' => $noturno,
                        'total' => $total,
                        'id_carencia' => $carencia->id,
                        'obs' => $request->obs,
                        'ano_ref' => $anoRef,
                        'pch' => $request->profile === "cpg_tecnico" ? "OK" : "PENDENTE",
                        'tipo_carencia_provida' => $carencia->tipo_carencia,
                        'data_fim_by_temp' => $carencia->tipo_carencia === "Temp" ? $carencia->fim_vaga : null,
                        'disciplina' => $carencia->disciplina,
                        'situacao_provimento' => $request->forma_suprimento === "RESERVA DE VAGA" ? "tramite" : $request->situacao_provimento,
                        'situacao' => $request->situacao_provimento === "tramite" ? "DESBLOQUEADO" : "BLOQUEADO",
                        'usuario' => $request->usuario,
                    ]);

                    // Cria o log.
                    Log::create([
                        'user_id' => $request->user_id,
                        'action' => "Inclusion",
                        'module' => "Provimento",
                        'provimento_id' => $provimento->id,
                        'ano_ref' => $anoRef,
                    ]);

                    // Se houve upload e a situação foi marcada como PROVIDA, atualiza o registro com o arquivo e data de assunção
                    if ($storedFilename && ($request->input('situacao_provimento') === 'provida')) {
                        $provimento->arquivo_comprobatorio = $storedFilename;
                        $provimento->situacao_provimento = 'provida';
                        // data_assuncao já pode ter sido informada no request
                        $provimento->data_assuncao = $request->input('data_assuncao');
                        $provimento->save();
                    }

                    // OTIMIZAÇÃO: Atualiza a carência com uma única query atômica.
                    $carencia->update([
                        'matutino'   => DB::raw("matutino - {$matutino}"),
                        'vespertino' => DB::raw("vespertino - {$vespertino}"),
                        'noturno'    => DB::raw("noturno - {$noturno}"),
                        'total'      => DB::raw("total - {$total}"),
                    ]);
                }

                // Atualiza o status da UEE após o loop.
                $carenciaExists = Carencia::where('cod_ue', $request->cod_unidade)->where('ano_ref', $anoRef)->where('total', '>', 0)->exists();
                Uee::where('cod_unidade', $request->cod_unidade)->update([
                    'carencia' => $carenciaExists ? 'SIM' : 'NÃO',
                ]);
            });

            return redirect()->to(url()->previous())->with('msg', 'Vaga suprida com Sucesso!');
        } catch (\Exception $e) {
            // Se qualquer coisa dentro da transação falhar, o erro é capturado aqui.

            return redirect()->to(url()->previous())->with('error', 'Ocorreu um erro ao processar o provimento: ' . $e->getMessage());
        }
    }

    public function processData(Request $request)
    {

        $data = $request->data;
        $request->session()->put('data', $data);
    }

    public function showProvimentos($tipo)
    {

        session()->put('ref_rota', '');
        session()->put('ref_rota', 'provimento');
        $anoRef = session()->get('ano_ref');

        if ($tipo === "all_provimentos") {
            $eixo_cursos = Eixo_curso::distinct()->get(['eixo']);
            $cursos = Eixo_curso::select("curso")->get();
            $disciplinas = Disciplina::orderBy('nome', 'asc')->get();
            $numero_do_cop = NumCop::all();
            // if (Auth::user()->profile === "cpg_tecnico") {
            //     $provimentos = Provimento::where('situacao_provimento', 'provida')->orderBy('nte', 'asc')->orderBy('municipio', 'asc')->get();
            // } else {
            $provimentos = Provimento::where('ano_ref', $anoRef)
                ->orderBy('nte', 'asc')
                ->orderBy('municipio', 'asc')
                ->where('nte', 30)
                ->get();
            // }
            session()->put('provimentos', $provimentos);
            $provimentosMat = $provimentos->sum('provimento_matutino');
            $provimentosVesp = $provimentos->sum('provimento_vespertino');
            $provimentosNot = $provimentos->sum('provimento_noturno');
            $provimentosTotal = $provimentos->sum('total');

            return view('provimento.show_provimentos', [
                'provimentos' => $provimentos,
                'provimentosMat' => $provimentosMat,
                'provimentosVesp' => $provimentosVesp,
                'provimentosNot' => $provimentosNot,
                'provimentosTotal' => $provimentosTotal,
                'disciplinas' => $disciplinas,
                'eixo_cursos' => $eixo_cursos,
                'cursos' => $cursos,
                'numero_do_cop' => $numero_do_cop,
            ]);
        } else if ($tipo === "filter_provimentos") {

            session()->put('ref_rota', '');
            $provimentos = session()->get('provimentos');
            $numero_do_cop = NumCop::all();
            $novosProvimentos = [];

            foreach ($provimentos as $provimento) {
                // Use Eloquent to retrieve the current record so we return a Provimento model
                // (not a stdClass) and can access relations like `servidore` safely.
                $dbProvimento = Provimento::with('servidore')->find($provimento->id);

                if ($dbProvimento && $dbProvimento->updated_at != $provimento->updated_at) {
                    // Se a linha existe no banco de dados e a data de atualização é diferente,
                    // consideramos que houve alteração, então adicionamos os novos dados à matriz
                    $novosProvimentos[] = $dbProvimento;
                } elseif (!$dbProvimento) {
                    // Se a linha não existe mais no banco de dados, consideramos que foi excluída,
                    // então não a incluímos na matriz
                } else {
                    // Mantém os dados antigos na matriz
                    $novosProvimentos[] = $provimento;
                }
            }
            // Adiciona os novos dados à matriz $provimentos
            $provimentos = $novosProvimentos;
            session()->put('provimentos', $provimentos);
            $provimentosCollection = collect($provimentos);
            $eixo_cursos = Eixo_curso::distinct()->get(['eixo']);
            $cursos = Eixo_curso::select("curso")->get();
            $disciplinas = Disciplina::orderBy('nome', 'asc')->get();
            $provimentosMat = $provimentosCollection->sum('matutino');
            $provimentosVesp = $provimentosCollection->sum('vespertino');
            $provimentosNot = $provimentosCollection->sum('noturno');
            $provimentosTotal = $provimentosCollection->sum('total');

            return view('provimento.show_provimentos', [
                'provimentos' => $provimentos,
                'provimentosMat' => $provimentosMat,
                'provimentosVesp' => $provimentosVesp,
                'provimentosNot' => $provimentosNot,
                'provimentosTotal' => $provimentosTotal,
                'disciplinas' => $disciplinas,
                'eixo_cursos' => $eixo_cursos,
                'cursos' => $cursos,
                'numero_do_cop' => $numero_do_cop,
            ]);
        }
    }

    public function showProvimentoByForm(Request $request)
    {

        $anoRef = session()->get('ano_ref');
        $provimentos = Provimento::query();
        $disciplinas = Disciplina::query();
        $eixo_cursos = Eixo_curso::query();
        $numero_do_cop = NumCop::all();

        // Verifica se os campos foram preenchidos
        if ($request->filled('nte_seacrh')) {
            $provimentos = $provimentos->where('nte', $request->nte_seacrh);
        }

        if ($request->filled('search_servidor_matricula')) {
            if ($request->search_servidor_matricula === "sim") {
                $provimentos = $provimentos->where(function ($query) {
                    $query->where(function ($q) {
                        // Condição: cadastros que começam com '92' e têm exatamente 8 caracteres
                        $q->where('cadastro', 'like', '92%')
                            ->whereRaw('LENGTH(cadastro) = 8');
                    })
                        ->orWhere(function ($q) {
                            // Condição: cadastros que começam com '11' e têm 8 ou 9 caracteres
                            $q->where('cadastro', 'like', '11%')
                                ->where(function ($subQuery) {
                                    $subQuery->whereRaw('LENGTH(cadastro) = 8')
                                        ->orWhereRaw('LENGTH(cadastro) = 9');
                                });
                        })
                        ->orWhere(function ($q) {
                            // Condição: cadastros que começam com '85' e têm exatamente 8 caracteres
                            $q->where('cadastro', 'like', '85%')
                                ->whereRaw('LENGTH(cadastro) = 8');
                        });
                });
            } else {
                $provimentos = $provimentos->whereNot(function ($query) {
                    $query->where(function ($q) {
                        // Excluir cadastros que começam com '92' e têm 8 caracteres
                        $q->where('cadastro', 'like', '92%')
                            ->whereRaw('LENGTH(cadastro) = 8');
                    })
                        ->orWhere(function ($q) {
                            // Excluir cadastros que começam com '11' e têm 8 ou 9 caracteres
                            $q->where('cadastro', 'like', '11%')
                                ->where(function ($subQuery) {
                                    $subQuery->whereRaw('LENGTH(cadastro) = 8')
                                        ->orWhereRaw('LENGTH(cadastro) = 9');
                                });
                        })
                        ->orWhere(function ($q) {
                            // Excluir cadastros que começam com '85' e têm exatamente 8 caracteres
                            $q->where('cadastro', 'like', '85%')
                                ->whereRaw('LENGTH(cadastro) = 8');
                        });
                });
            }
        }

        if ($request->filled('search_created')) {
            // Aplica a condição para buscar carências criadas a partir da data informada.
            $provimentos->whereDate('created_at', '>=', $request->search_created);
        }

        if ($request->filled('search_codigo_unidade_escolar')) {
            $provimentos = $provimentos->where('cod_unidade', $request->search_codigo_unidade_escolar);
        }

        if ($request->filled('municipio_search')) {
            $provimentos = $provimentos->where('municipio', $request->municipio_search);
        }

        if ($request->filled('search_uee')) {
            $provimentos = $provimentos->where('unidade_escolar', $request->search_uee);
        }

        if ($request->filled('search_disciplina')) {
            $disciplinasSelecionadas = $request->input('search_disciplina');

            if (is_array($disciplinasSelecionadas)) {
                $provimentos = $provimentos->whereIn('disciplina', $disciplinasSelecionadas);
            }
        }

        if ($request->filled('search_pch')) {
            $valorBusca = $request->search_pch;

            $statusEspeciais = ['NO ACOMPANHAMENTO', 'EM SUBSTITUICAO', 'SEM INICIO DAS ATIVIDADES', 'NAO ASSUMIU'];

            if ($valorBusca === 'PENDENTE') {
                $provimentos->where('pch', 'PENDENTE')
                    ->where(function ($query) {
                        $query->whereNull('situacao_programacao')
                            ->orWhere('situacao_programacao', '');
                    });
            } elseif (in_array($valorBusca, $statusEspeciais)) {

                $provimentos->where('situacao_programacao', $valorBusca)
                    ->where('pch', '!=', 'OK');
            } else {

                $provimentos->where('pch', $valorBusca);
            }
        }

        if ($request->filled('search_eixo')) {
            $provimentos = $provimentos->where('eixo', $request->search_eixo);
        }

        if ($request->filled('search_reserva')) {
            $provimentos = $provimentos->where('metodo', $request->search_reserva);
        }

        if ($request->filled('search_num_cop')) {
            $provimentos = $provimentos->where('num_cop', $request->search_num_cop);
        }

        if ($request->filled('search_forma')) {
            $provimentos = $provimentos->where('forma_suprimento', $request->search_forma);
        }

        if ($request->filled('search_tipo_movimentacao')) {
            $provimentos = $provimentos->where('tipo_movimentacao', $request->search_tipo_movimentacao);
        }

        if ($request->filled('search_vinculo')) {
            $provimentos = $provimentos->where('vinculo', $request->search_vinculo);
        }

        if ($request->filled('search_matricula_servidor')) {
            $provimentos = $provimentos->where('cadastro', $request->search_matricula_servidor);
        }

        if ($request->filled('search_situacao_provimento')) {
            $searchValue = $request->search_situacao_provimento;
            $provimentos = $searchValue == 'tramite'
                ? $provimentos->where('situacao_provimento', 'like', '%tramite%')
                : $provimentos->where('situacao_provimento', $searchValue);
        }

        if ($request->filled('search_tipo')) {
            $searchOptions = [
                'Temp' => 'Temp',
                'Real' => 'Real'
            ];
            $searchValue = $searchOptions[$request->search_tipo] ?? null;
            if ($searchValue) {
                $provimentos = $provimentos->where('tipo_carencia_provida', $searchValue);
            }
        }

        // $userProfile = Auth::user()->profile;
        // $provimentos = $userProfile === "cpg_tecnico"
        //     ? $provimentos->where('situacao_provimento', 'provida')
        //     : $provimentos->where('situacao_provimento', 'tramite');

        $provimentos = $provimentos->orderBy('nte', 'asc')
            ->orderBy('municipio', 'asc')
            ->orderBy('servidor', 'asc')
            ->where('ano_ref', $anoRef)
            ->get();
        session()->put('provimentos', $provimentos);

        $disciplinas = Disciplina::orderBy('nome', 'asc')->get();
        $eixo_cursos = $eixo_cursos->distinct()->get(['eixo']);
        $cursos = Eixo_curso::select("curso")->get();
        $provimentosMat = $provimentos->sum('provimento_matutino');
        $provimentosVesp = $provimentos->sum('provimento_vespertino');
        $provimentosNot = $provimentos->sum('provimento_noturno');
        $provimentosTotal = $provimentos->sum('total');

        return view('provimento.show_provimentos', [
            'provimentos' => $provimentos,
            'provimentosMat' => $provimentosMat,
            'provimentosVesp' => $provimentosVesp,
            'provimentosNot' => $provimentosNot,
            'provimentosTotal' => $provimentosTotal,
            'disciplinas' => $disciplinas,
            'eixo_cursos' => $eixo_cursos,
            'cursos' => $cursos,
            'numero_do_cop' => $numero_do_cop,
        ]);
    }

    public function imprimirTabela()
    {

        $provimentos = collect(session()->get('provimentos'))
            ->groupBy('nte')
            ->sortBy(function ($group) {
                return (int) $group->first()->nte;
            });


        $provimentosMat = $provimentos->sum('provimento_matutino');
        $provimentosVesp = $provimentos->sum('provimento_vespertino');
        $provimentosNot = $provimentos->sum('provimento_noturno');
        $provimentosTotal = $provimentos->sum('total');

        return view('relatorios.relatorio_provimento', compact('provimentos', 'provimentosMat', 'provimentosVesp', 'provimentosNot', 'provimentosTotal'));
    }

    public function gerarExcel()
    {

        $provimentos = collect(session()->get('provimentos'));
        $provimentosOrdenados = $provimentos->sortBy([
            ['nte', 'asc'],
            ['municipio', 'asc'],
            ['unidade_escolar', 'asc'],
            ['servidor', 'asc']
        ]);

        $provimentosMat = $provimentos->sum('matutino');
        $provimentosVesp = $provimentos->sum('vespertino');
        $provimentosNot = $provimentos->sum('noturno');
        $provimentosTotal = $provimentos->sum('total');

        return view('excel.excel_provimentos', compact('provimentosOrdenados', 'provimentosMat', 'provimentosVesp', 'provimentosNot', 'provimentosTotal'));
    }

    public function detailProvimento(Provimento $provimento)
    {
        session()->put('ref_rota', '');
        session()->put('ref_rota', 'provimento');

        // Calcular limite máximo de arquivo
        $maxFileSize = min(
            $this->parseSize(ini_get('upload_max_filesize')),
            $this->parseSize(ini_get('post_max_size')),
            5120 // 2MB em KB
        );
        
        $maxFileSizeMB = round($maxFileSize / 1024, 1);

        return view('provimento.detail_provimento', compact('provimento', 'maxFileSizeMB'));
    }

    public function update(Request $request)
    {
        $anoRef = session()->get('ano_ref');

        // Verificar limitações do servidor antes da validação
        $maxFileSize = min(
            $this->parseSize(ini_get('upload_max_filesize')),
            $this->parseSize(ini_get('post_max_size')),
            5120 // 2MB em KB
        );

        // Validação para arquivo quando situacao_provimento for 'provida'
        if ($request->situacao_provimento === 'provida') {
            $request->validate([
                'arquivo_comprobatorio' => "required|file|mimes:pdf,jpeg,jpg|max:{$maxFileSize}",
            ], [
                'arquivo_comprobatorio.required' => 'O arquivo comprobatório é obrigatório quando a situação for PROVIDA.',
                'arquivo_comprobatorio.mimes' => 'O arquivo deve ser do tipo PDF, JPEG ou JPG.',
                'arquivo_comprobatorio.max' => "O arquivo não pode ser maior que " . ($maxFileSize / 1024) . "MB.",
            ]);
        }

        if (($request->profile_cpg_update === 'cpm_tecnico') || ($request->profile_cpg_update === 'cpm_coordenador')) {
            $requestData = $request->all();

            // Handle file upload with detailed error checking
            if ($request->hasFile('arquivo_comprobatorio') && $request->situacao_provimento === 'provida') {
                $arquivo = $request->file('arquivo_comprobatorio');
                
                // Verificações adicionais
                if (!$arquivo->isValid()) {
                    return redirect()->back()->withErrors(['arquivo_comprobatorio' => 'Arquivo inválido ou corrompido.']);
                }
                
                $fileSize = $arquivo->getSize() / 1024; // em KB
                if ($fileSize > $maxFileSize) {
                    return redirect()->back()->withErrors([
                        'arquivo_comprobatorio' => "Arquivo muito grande ({$fileSize}KB). Máximo permitido: {$maxFileSize}KB."
                    ]);
                }
                
                // Gerar nome único e organizar por data
                $year = date('Y');
                $month = date('m');
                $filename = $request->id . '_' . time() . '_' . uniqid() . '.' . $arquivo->getClientOriginalExtension();
                
                try {
                    $path = $arquivo->storeAs("provimentos/{$year}/{$month}", $filename, 'public');
                    $requestData['arquivo_comprobatorio'] = "{$year}/{$month}/{$filename}";
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors([
                        'arquivo_comprobatorio' => 'Erro ao salvar arquivo: ' . $e->getMessage()
                    ]);
                }
            }

            Provimento::findOrFail($request->id)->update($requestData);
        } else if (($request->profile_cpg_update === "cpg_tecnico") || ($request->profile_cpg_update === "administrador")) {
            $provimento = Provimento::findOrFail($request->id);
            $requestData = $request->all();

            // Handle file upload
            if ($request->hasFile('arquivo_comprobatorio') && $request->situacao_provimento === 'provida') {
                $arquivo = $request->file('arquivo_comprobatorio');
                $filename = time() . '_' . uniqid() . '.' . $arquivo->getClientOriginalExtension();
                $path = $arquivo->storeAs('provimentos', $filename, 'public');
                $requestData['arquivo_comprobatorio'] = $filename;

                // Remove old file if exists
                if ($provimento->arquivo_comprobatorio && Storage::disk('public')->exists('provimentos/' . $provimento->arquivo_comprobatorio)) {
                    Storage::disk('public')->delete('provimentos/' . $provimento->arquivo_comprobatorio);
                }
            }

            $observacao = $request->obs_cpg;
            $usuario = $request->user_cpg_update;

            // Verifica se a observação foi preenchida
            if (!empty($observacao)) {
                // Constrói o prefixo que estamos procurando (ex: "ERICA DE OLIVEIRA - ")
                $prefixo = $usuario . ' - ';

                // VERIFICAÇÃO PRINCIPAL:
                // Se a observação NÃO começa com o prefixo do usuário, nós o adicionamos.
                // str_starts_with() é a função moderna e ideal para isso (PHP 8.0+).
                if (!str_starts_with($observacao, $prefixo)) {
                    // Concatena o nome do usuário apenas se ele não estiver lá
                    $requestData['obs_cpg'] = $prefixo . $observacao;
                } else {
                    // Se já começa com o nome, apenas usa o valor como está, sem adicionar de novo
                    $requestData['obs_cpg'] = $observacao;
                }
            } else {
                // Se a observação estiver vazia, apenas atribui o valor vazio
                $requestData['obs_cpg'] = $observacao; // ou simplesmente $requestData['obs_cpg'] = '';
            }

            if ($request->situacao_programacao != "NAO ASSUMIU" && $request->situacao_programacao != "SEM INICIO DAS ATIVIDADES") {
                // Este código agora só será executado se a situação for DIFERENTE de ambas as opções.
                $requestData['situacao_carencia_existente'] = '';
            }

            if ($provimento->update($requestData)) {
                $log = new Log;
                $log->user_id = $request->user_id;
                $log->action = "Update";
                $log->module = "Provimento";
                $log->provimento_id = $request->provimento_id;
                $log->ano_ref = $anoRef;
                $log->save();
            }
        }

        return  redirect()->to(url()->previous())->with('msg', 'Registros Alterados com Sucesso!');
    }

    public function provide($id, $cod_ue)
    {
        $uee = Uee::where('cod_unidade', $cod_ue)->first();
        $carencias = Carencia::where('id', $id)->get();
        $forma_suprimentos = Forma_suprimento::all();

        return view('provimento.prover_vaga_by_id', compact('carencias', 'forma_suprimentos', 'uee'));
    }

    public function destroy(Provimento $provimento)
    {
        $id_carencia = $provimento->id_carencia;

        // Recupera a carência para atualizar seus valores
        $carencias = Carencia::where('id', $id_carencia)->first();


        if ($provimento->situacao_carencia_existente === "SIM" || is_null($provimento->situacao_carencia_existente)) {

            // Atualiza os valores da carência
            Carencia::where('id', $id_carencia)
                ->update([
                    'matutino' => $carencias->matutino + $provimento->provimento_matutino,
                    'vespertino' => $carencias->vespertino + $provimento->provimento_vespertino,
                    'noturno' => $carencias->noturno + $provimento->provimento_noturno,
                    'total' => $carencias->total + $provimento->total,
                ]);
        }

        // Apaga os registros da tabela logs associados ao provimento antes de excluir o provimento
        Log::where('provimento_id', $provimento->id)->delete();

        // Exclui o provimento
        $provimento->delete();

        return redirect('/buscar/provimento/filter_provimentos')
            ->with('msg', 'Provimento excluído e carência atualizada com sucesso!');
    }

    public function gerarAnuencia($cadastro)
    {

        $servidor = Servidore::where('cadastro', $cadastro)->first();
        $provimentosByServidor = Provimento::where('cadastro', $cadastro)->get();
        $provimentosMat = $provimentosByServidor->sum('provimento_matutino');
        $provimentosVesp = $provimentosByServidor->sum('provimento_vespertino');
        $provimentosNot = $provimentosByServidor->sum('provimento_noturno');

        return view('relatorios.termo_anuencia', compact('provimentosByServidor', 'servidor', 'provimentosMat', 'provimentosVesp', 'provimentosNot'));
    }

    public function provimentosForAnuencia()
    {

        $anoRef = session()->get('ano_ref');
        $servidores = Provimento::select('servidor', 'cadastro', 'vinculo', 'regime')->where('ano_ref', $anoRef)->distinct()->get();

        return view('provimento.servidores_for_anuencia', compact('servidores'));
    }

    public function validarProvimento($id, $situacao)
    {

        $provimento = Provimento::findOrFail($id);

        switch ($situacao) {
            case 'OK':
                $provimento->update([
                    'pch' => 'OK',
                ]);
                return response()->json([
                    'message' => 'Provimento validado com sucesso',
                    'id' => $provimento->id,
                ], 200);
                break;

            case 'PENDENTE':
                $provimento->update([
                    'pch' => 'PENDENTE',
                ]);
                return response()->json([
                    'message' => 'Validação retirada',
                    'id' => $provimento->id,
                ], 200);
                break;

            default:
                return response()->json([
                    'message' => 'Situação inválida',
                ], 400);
                break;
        }
    }

    public function viewData()
    {

        $actualDate = Carbon::now();
        $formattedDate = $actualDate->format('Y-m-d');
        $data = Carencia::query();
        $dadosSeparadosNteMunicipio = [];
        $dadosSeparadosNte = [];
        $dadosSeparadosNteMunicipioUnidadeEscolar = [];


        $data = $data->select('nte', 'area', DB::raw('MAX(municipio) as municipio'), DB::raw('SUM(CASE WHEN tipo_carencia = "Temp" THEN total ELSE 0 END) as total_temp, SUM(CASE WHEN tipo_carencia = "Real" THEN total ELSE 0 END) as total_real, SUM(total) as total'))
            ->where(function ($query) use ($formattedDate) {
                $query->where('fim_vaga', '>=', $formattedDate)
                    ->orWhereNull('fim_vaga');
            })
            ->where('total', '>', 0)
            ->orderBy('nte', 'asc')
            ->orderBy('municipio', 'asc')
            ->groupBy('nte', 'area')
            ->get();

        //Faz a separação por NTE

        foreach ($data as $linha) {
            $nte = $linha->nte;

            if (!isset($dadosSeparadosNte[$nte])) {
                $dadosSeparadosNte[$nte] = [];
            }

            $dadosSeparadosNte[$nte][] = $linha;
        }

        $statusForMunicipioView = false;
        $statusForUeeView = false;

        session()->put('dataCarenciasPorAreaByNte', $data);
        session()->put('dataCarenciasPorAreaByMunicipio', '');
        session()->put('dataCarenciasPorAreaByUnidadeEscolar', '');

        return view("provimento.view_data", compact('dadosSeparadosNte', 'dadosSeparadosNteMunicipio', 'dadosSeparadosNteMunicipioUnidadeEscolar', 'statusForMunicipioView', 'statusForUeeView'));
    }

    public function viewDataByForm(Request $request)
    {
        $actualDate = Carbon::now();
        $formattedDate = $actualDate->format('Y-m-d');
        //Prepara a Query
        $nte = $request->nte_seacrh;
        $municipio = $request->municipio_search;
        $unidade = $request->search_uee;
        $cod_ue = '';
        $data = Carencia::query();
        $dadosSeparadosNteMunicipio = [];
        $dadosSeparadosNte = [];
        $dadosSeparadosNteMunicipioUnidadeEscolar = [];

        // POR NTE
        //Faz a busca quando o input select vem preenchido
        if ($request->filled('nte_seacrh')) {
            $data = $data->select('nte', 'area', DB::raw('MAX(municipio) as municipio'), DB::raw('SUM(CASE WHEN tipo_carencia = "Temp" THEN total ELSE 0 END) as total_temp, SUM(CASE WHEN tipo_carencia = "Real" THEN total ELSE 0 END) as total_real, SUM(total) as total'))
                ->where(function ($query) use ($formattedDate) {
                    $query->where('fim_vaga', '>=', $formattedDate)
                        ->orWhereNull('fim_vaga');
                })
                ->where('total', '>', 0)
                ->where('nte', $request->nte_seacrh)
                ->orderBy('nte', 'asc')
                ->groupBy('nte', 'area')
                ->get();
            //Faz a separação por NTE

            foreach ($data as $linha) {
                $nte = $linha->nte;

                if (!isset($dadosSeparadosNte[$nte])) {
                    $dadosSeparadosNte[$nte] = [];
                }

                $dadosSeparadosNte[$nte][] = $linha;
            }
            $statusForMunicipioView = false;
            $statusForUeeView = false;
            session()->put('dataCarenciasPorAreaByNte', $data);
            session()->put('dataCarenciasPorAreaByMunicipio', '');
            session()->put('dataCarenciasPorAreaByUnidadeEscolar', '');
        } else {
            $data = $data->select('nte', 'area', DB::raw('MAX(municipio) as municipio'), DB::raw('SUM(CASE WHEN tipo_carencia = "Temp" THEN total ELSE 0 END) as total_temp, SUM(CASE WHEN tipo_carencia = "Real" THEN total ELSE 0 END) as total_real, SUM(total) as total'))
                ->where(function ($query) use ($formattedDate) {
                    $query->where('fim_vaga', '>=', $formattedDate)
                        ->orWhereNull('fim_vaga');
                })
                ->where('total', '>', 0)
                ->orderBy('nte', 'asc')
                ->groupBy('nte', 'area')
                ->get();
            //Faz a separação por NTE

            foreach ($data as $linha) {
                $nte = $linha->nte;

                if (!isset($dadosSeparadosNte[$nte])) {
                    $dadosSeparadosNte[$nte] = [];
                }

                $dadosSeparadosNte[$nte][] = $linha;
            }

            $statusForMunicipioView = false;
            $statusForUeeView = false;
            session()->put('dataCarenciasPorAreaByNte', $data);
            session()->put('dataCarenciasPorAreaByMunicipio', '');
            session()->put('dataCarenciasPorAreaByUnidadeEscolar', '');
        }

        if ($request->filled('municipio_search')) {
            $data = DB::table('carencias') // Substitua 'nome_da_tabela' pelo nome correto da tabela no seu banco de dados
                ->select('nte', 'municipio', 'area', DB::raw('SUM(CASE WHEN tipo_carencia = "Temp" THEN total ELSE 0 END) as total_temp, SUM(CASE WHEN tipo_carencia = "Real" THEN total ELSE 0 END) as total_real, SUM(total) as total'))
                ->where(function ($query) use ($formattedDate) {
                    $query->where('fim_vaga', '>=', $formattedDate)
                        ->orWhereNull('fim_vaga');
                })
                ->where('total', '>', 0)
                ->where('nte', $nte)
                ->where('municipio', $request->municipio_search)
                ->orderBy('nte', 'asc')
                ->groupBy('nte', 'municipio', 'area');

            // Obter os resultados da consulta
            $data = $data->get();
            session()->put('dataCarenciasPorAreaByMunicipio', $data);
            session()->put('dataCarenciasPorAreaByNte', '');
            session()->put('dataCarenciasPorAreaByUnidadeEscolar', '');
            // Faz a separação por NTE e município
            $dadosSeparadosNteMunicipio = [];

            foreach ($data as $linha) {
                $nte = $linha->nte;
                $municipio = $linha->municipio;

                if (!isset($dadosSeparadosNteMunicipio[$nte])) {
                    $dadosSeparadosNteMunicipio[$nte] = [];
                }

                if (!isset($dadosSeparadosNteMunicipio[$nte][$municipio])) {
                    $dadosSeparadosNteMunicipio[$nte][$municipio] = [];
                }

                $dadosSeparadosNteMunicipio[$nte][$municipio][] = $linha;
            }

            $statusForMunicipioView = true;
            $statusForUeeView = false;
        }

        if ($request->filled('search_uee')) {
            $search_uee = $request->search_uee;
            $data = DB::table('carencias')
                ->select('nte', 'municipio', 'area', 'unidade_escolar', 'cod_ue', DB::raw('SUM(CASE WHEN tipo_carencia = "Temp" THEN total ELSE 0 END) as total_temp, SUM(CASE WHEN tipo_carencia = "Real" THEN total ELSE 0 END) as total_real, SUM(total) as total'))
                ->where(function ($query) use ($formattedDate) {
                    $query->where('fim_vaga', '>=', $formattedDate)
                        ->orWhereNull('fim_vaga');
                })
                ->where('total', '>', 0)
                ->where('nte', $nte)
                ->where('unidade_escolar', $request->search_uee)
                ->orderBy('nte', 'asc')
                ->orderBy('municipio', 'asc')
                ->orderBy('unidade_escolar', 'asc')
                ->groupBy('nte', 'municipio', 'area', 'unidade_escolar', 'cod_ue');

            $data = $data->get();
            session()->put('dataCarenciasPorAreaByUnidadeEscolar', $data);
            session()->put('dataCarenciasPorAreaByNte', '');
            session()->put('dataCarenciasPorAreaByMunicipio', '');

            $cod_ue = DB::table('uees')
                ->where('unidade_escolar', $search_uee)
                ->value('cod_unidade');

            $dadosSeparadosNteMunicipioUnidadeEscolar = [];

            foreach ($data as $linha) {
                $nte = $linha->nte;
                $municipio = $linha->municipio;
                $unidade_escolar = $linha->unidade_escolar;
                $cod_unidade = $linha->cod_ue;

                if (!isset($dadosSeparadosNteMunicipioUnidadeEscolar[$nte])) {
                    $dadosSeparadosNteMunicipioUnidadeEscolar[$nte] = [];
                }

                if (!isset($dadosSeparadosNteMunicipioUnidadeEscolar[$nte][$municipio][$unidade_escolar][$cod_unidade])) {
                    $dadosSeparadosNteMunicipioUnidadeEscolar[$nte][$municipio][$unidade_escolar][$cod_unidade] = [];
                }

                $dadosSeparadosNteMunicipioUnidadeEscolar[$nte][$municipio][$unidade_escolar][$cod_unidade][] = $linha;
            }

            $statusForMunicipioView = true;
            $statusForUeeView = true;
        }


        // Retorna na view os dados pesquisados
        return view("provimento.view_data", compact('dadosSeparadosNte', 'dadosSeparadosNteMunicipio', 'dadosSeparadosNteMunicipioUnidadeEscolar', 'nte', 'municipio', 'statusForMunicipioView', 'statusForUeeView', 'unidade', 'cod_ue'));
    }

    public function gerarExcelPorAreas()
    {

        $dataCarenciasPorAreaByNte = session()->get('dataCarenciasPorAreaByNte');
        $dataCarenciasPorAreaByMunicipio = session()->get('dataCarenciasPorAreaByMunicipio');
        $dataCarenciasPorAreaByUnidadeEscolar = session()->get('dataCarenciasPorAreaByUnidadeEscolar');

        return view('excel.areas_carencias', compact('dataCarenciasPorAreaByNte', 'dataCarenciasPorAreaByMunicipio', 'dataCarenciasPorAreaByUnidadeEscolar'));
    }

    public function statusProvimentoTramite()
    {
        $anoRef = session()->get('ano_ref');
        $provimentos = Provimento::select('servidor', 'nte', 'municipio', 'unidade_escolar', 'cod_unidade', 'cadastro', 'data_encaminhamento', 'situacao_provimento', DB::raw('COUNT(*) as total'))
            ->orderBy('data_encaminhamento', 'asc')
            ->where('situacao_provimento', 'tramite')
            ->where('ano_ref', $anoRef)
            ->groupBy('servidor', 'nte', 'municipio', 'unidade_escolar', 'cod_unidade', 'cadastro', 'data_encaminhamento', 'situacao_provimento')
            ->get();

        $totalRegistros = $provimentos->count();
        session()->put('provimentosEmTramiteEmDias', $provimentos);

        return view('provimento.status_provimento', compact('provimentos', 'totalRegistros'));
    }

    public function excelForDaysInTramite()
    {

        $provimentos = session()->get('provimentosEmTramiteEmDias');

        return view('excel.excel_provimentos_days_in_tramite', compact('provimentos'));
    }

    public function createProvimentoEfetivo()
    {
        $uees = Uee::leftJoin('unidades_organizacionais', 'uees.cod_unidade', '=', 'unidades_organizacionais.cod_sec')
            ->where(function ($query) {
                $query->where('uees.desativation_situation', 'Ativa')
                    ->orWhereNull('uees.desativation_situation');
            })
            ->get();;
        $disciplinas = Disciplina::orderBy('nome', 'asc')->get();

        return view('provimento.create_provimento_efetivo', compact([
            'disciplinas',
            'uees',
        ]));
    }

    public function addNewProvimentoEfetivo(Request $request)
    {
        $anoRef = session()->get('ano_ref');

        // Determina o uee_id a partir de possíveis campos do form (mesma lógica usada para salvar)
        $ueeId = $request->input('unidade_id') ?: $request->input('uee_id');
        if (!$ueeId && $request->filled('cod_ue')) {
            // Primeiro tenta encontrar pela coluna 'cod_unidade' na tabela uees
            $uee = Uee::where('cod_unidade', $request->cod_ue)->first();
            if (!$uee) {
                // Se não estiver direto na tabela uees, procura na tabela unidades_organizacionais
                $codSec = DB::table('unidades_organizacionais')->where('uo', $request->cod_ue)->value('cod_sec');
                if ($codSec) {
                    $uee = Uee::where('cod_unidade', $codSec)->first();
                }
            }
            if ($uee) $ueeId = $uee->id;
        }

        // Verifica se já existe um encaminhamento para esse servidor na mesma unidade (usa o uee_id determinado acima)
        if ($request->filled('servidor_id')) {
            $verify_servidor = ProvimentosEncaminhado::where('ingresso_candidato_id', $request->servidor_id)
                ->where('uee_id', $ueeId)
                ->first();

            if ($verify_servidor) {
                return redirect()->to(url()->previous())->with('msg', 'error');
            }
        }

        // Persistência em transação
        DB::beginTransaction();
        try {
            // Recebe as disciplinas e turnos (arrays)
            $disciplinas = $request->input('disciplinas', []);
            $matutino = $request->input('matutino', []);
            $vespertino = $request->input('vespertino', []);
            $noturno = $request->input('noturno', []);

            // Se os valores de disciplinas vierem como IDs, converte para nomes
            $discNames = [];
            foreach ($disciplinas as $d) {
                if (is_numeric($d)) {
                    $disc = Disciplina::find($d);
                    $discNames[] = $disc ? $disc->nome : $d;
                } else {
                    $discNames[] = $d;
                }
            }

            // Salva tipo de carência se enviado (aplicável a todos os registros criados abaixo)
            $tipoCarencia = $request->filled('carencia_tipo') ? $request->carencia_tipo : null;

            // Atualiza formação no servidor encaminhado quando aplicável (mantém comportamento anterior)
            $servidor_encaminhado = ServidoresEncaminhado::find($request->servidor_id);
            if ($servidor_encaminhado) {
                if ($request->filled('disciplina_efetivo')) {
                    $servidor_encaminhado->formacao = $request->disciplina_efetivo;
                } elseif (!empty($discNames)) {
                    $servidor_encaminhado->formacao = implode(', ', $discNames);
                }
                $servidor_encaminhado->save();
            }

            // Caso haja mais de uma disciplina, crie um registro por disciplina
            if (count($discNames) > 1) {
                foreach ($discNames as $i => $dName) {
                    $row = new ProvimentosEncaminhado();
                    $row->ingresso_candidato_id = $request->servidor_id;
                    $row->uee_id = $ueeId;
                    $row->data_encaminhamento = $request->data_encaminhamento ?: null;
                    $row->data_assuncao = $request->data_assuncao ?: null;
                    $row->obs = $request->obs ?: null;
                    $row->ano_ref = $anoRef;
                    $row->user_id = $request->usuario ?: auth()->id();
                    $row->servidor_substituido_id = $request->servidor_subistituido ?: null;
                    if ($request->id_segundo_servidor_subistituido) {
                        $row->segundo_servidor_substituido = $request->id_segundo_servidor_subistituido;
                    }
                    $row->disciplina = $dName;
                    // Use per-discipline turnos when disponíveis, senão repete os totais agregados
                    $row->matutino = isset($matutino[$i]) ? $matutino[$i] : (is_array($matutino) && count($matutino) ? implode(', ', $matutino) : null);
                    $row->vespertino = isset($vespertino[$i]) ? $vespertino[$i] : (is_array($vespertino) && count($vespertino) ? implode(', ', $vespertino) : null);
                    $row->noturno = isset($noturno[$i]) ? $noturno[$i] : (is_array($noturno) && count($noturno) ? implode(', ', $noturno) : null);
                    if ($tipoCarencia) $row->tipo_carencia = $tipoCarencia;
                    $row->save();

                    // Log per-row
                    Log::create([
                        'user_id' => $request->user_id,
                        'action' => "Inclusion",
                        'module' => "Provimento",
                        'provimento_id' => $row->id,
                        'ano_ref' => $anoRef,
                    ]);
                }
            } else {
                // Mantém comportamento anterior: grava tudo em uma linha única quando houver apenas uma disciplina
                $provimentos_encaminhados = new ProvimentosEncaminhado();
                $provimentos_encaminhados->ingresso_candidato_id = $request->servidor_id;
                $provimentos_encaminhados->uee_id = $ueeId;
                $provimentos_encaminhados->data_encaminhamento = $request->data_encaminhamento ?: null;
                $provimentos_encaminhados->data_assuncao = $request->data_assuncao ?: null;
                $provimentos_encaminhados->obs = $request->obs ?: null;
                $provimentos_encaminhados->ano_ref = $anoRef;
                $provimentos_encaminhados->user_id = $request->usuario ?: auth()->id();
                $provimentos_encaminhados->servidor_substituido_id = $request->servidor_subistituido ?: null;
                if ($request->id_segundo_servidor_subistituido) {
                    $provimentos_encaminhados->segundo_servidor_substituido = $request->id_segundo_servidor_subistituido;
                }
                $provimentos_encaminhados->disciplina = implode(', ', $discNames);
                $provimentos_encaminhados->matutino = implode(', ', $matutino);
                $provimentos_encaminhados->vespertino = implode(', ', $vespertino);
                $provimentos_encaminhados->noturno = implode(', ', $noturno);
                if ($tipoCarencia) $provimentos_encaminhados->tipo_carencia = $tipoCarencia;
                $provimentos_encaminhados->save();

                Log::create([
                    'user_id' => $request->user_id,
                    'action' => "Inclusion",
                    'module' => "Provimento",
                    'provimento_id' => $provimentos_encaminhados->id,
                    'ano_ref' => $anoRef,
                ]);
            }

            DB::commit();
            return redirect()->to(url()->previous())->with('msg', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Erro ao salvar Provimento Efetivo: ' . $e->getMessage());
            return redirect()->to(url()->previous())->with('msg', 'error');
        }
    }

    public function showProvimentoEfetivo()
    {

        $actualDate = Carbon::now();
        $formattedDate = $actualDate->format('Y-m-d');
        $anoRef = session()->get('ano_ref');

        $provimentos_encaminhados = ProvimentosEncaminhado::where('ano_ref', $anoRef)->get();
        $quantidadeRegistros = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->count('ingresso_candidato_id');
        $quantidadeRegistrosPCH = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->where('pch', 'OK')->count('ingresso_candidato_id');
        $quantidadeRegistrosError = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->where('pch', 'INCONSISTENCIA')->count('ingresso_candidato_id');
        $quantidadeRegistrosErrorOK = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->where('pch', 'INCONSISTENCIA')->where('inconsistencia', 'OK')->count('ingresso_candidato_id');

        $quantidadeRegistrosComAssuncao = ProvimentosEncaminhado::where('ano_ref', $anoRef)->whereNotNull('data_assuncao')->count();

        $quantidadeRegistrosDataNula = ProvimentosEncaminhado::where('ano_ref', $anoRef)
            ->whereNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
            ->whereNotNull('data_encaminhamento') // Garante que data_encaminhamento não seja nulo
            ->whereRaw("DATEDIFF(?, data_encaminhamento) < 2", [Carbon::now()->format('Y-m-d')]) // Diferença de 2 ou mais dias
            ->distinct('ingresso_candidato_id') // Considera apenas registros únicos por ingresso_candidato_id
            ->count('ingresso_candidato_id'); // Conta os registros distintos

        $quantidadeRegistrosAtrasados = ProvimentosEncaminhado::where('ano_ref', $anoRef)
            ->whereNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
            ->whereNotNull('data_encaminhamento') // Garante que data_encaminhamento não seja nulo
            ->whereRaw("DATEDIFF(?, data_encaminhamento) >= 2", [Carbon::now()->format('Y-m-d')]) // Diferença de 2 ou mais dias
            ->distinct('ingresso_candidato_id') // Considera apenas registros únicos por ingresso_candidato_id
            ->count('ingresso_candidato_id'); // Conta os registros distintos


        $disciplinas = ServidoresEncaminhado::select('formacao')
            ->distinct()
            ->groupBy('formacao')
            ->where('formacao', '<>', '0')
            ->where('formacao', '<>', '#N/D')
            ->whereNotNull('formacao')
            ->whereRaw('formacao != ""')
            ->get();

        session()->put('provimentos_encaminhados', $provimentos_encaminhados);

        return view('provimento.show_provimento_efetivo', [
            'provimentos_encaminhados' => $provimentos_encaminhados,
            'disciplinas' => $disciplinas,
            'quantidadeRegistros' => $quantidadeRegistros,
            'quantidadeRegistrosPCH' => $quantidadeRegistrosPCH,
            'quantidadeRegistrosError' => $quantidadeRegistrosError,
            'quantidadeRegistrosErrorOK' => $quantidadeRegistrosErrorOK,
            'quantidadeRegistrosDataNula' => $quantidadeRegistrosDataNula,
            'quantidadeRegistrosAtrasados' => $quantidadeRegistrosAtrasados,
            'quantidadeRegistrosComAssuncao' => $quantidadeRegistrosComAssuncao,
        ]);
    }

    public function showProvimentoEfetivoFilter()
    {
        $actualDate = Carbon::now();
        $formattedDate = $actualDate->format('Y-m-d');
        $anoRef = session()->get('ano_ref');

        $provimentos_encaminhados = ProvimentosEncaminhado::where('ano_ref', $anoRef)->get();
        $quantidadeRegistros = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->count('ingresso_candidato_id');
        $quantidadeRegistrosPCH = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->where('pch', 'OK')->count('ingresso_candidato_id');
        $quantidadeRegistrosError = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->where('pch', 'INCONSISTENCIA')->count('ingresso_candidato_id');
        $quantidadeRegistrosErrorOK = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->where('pch', 'INCONSISTENCIA')->where('inconsistencia', 'OK')->count('ingresso_candidato_id');

        $quantidadeRegistrosComAssuncao = ProvimentosEncaminhado::where('ano_ref', $anoRef)
            ->whereNotNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
            ->whereNotNull('data_encaminhamento') // Garante que data_encaminhamento não seja nulo
            ->distinct('ingresso_candidato_id') // Considera apenas registros únicos por ingresso_candidato_id
            ->count('ingresso_candidato_id'); // Conta os registros distintos

        $quantidadeRegistrosDataNula = ProvimentosEncaminhado::where('ano_ref', $anoRef)
            ->whereNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
            ->whereNotNull('data_encaminhamento') // Garante que data_encaminhamento não seja nulo
            ->whereRaw("DATEDIFF(?, data_encaminhamento) < 2", [Carbon::now()->format('Y-m-d')]) // Diferença de 2 ou mais dias
            ->distinct('ingresso_candidato_id') // Considera apenas registros únicos por ingresso_candidato_id
            ->count('ingresso_candidato_id'); // Conta os registros distintos

        $quantidadeRegistrosAtrasados = ProvimentosEncaminhado::where('ano_ref', $anoRef)
            ->whereNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
            ->whereNotNull('data_encaminhamento') // Garante que data_encaminhamento não seja nulo
            ->whereRaw("DATEDIFF(?, data_encaminhamento) >= 2", [Carbon::now()->format('Y-m-d')]) // Diferença de 2 ou mais dias
            ->distinct('ingresso_candidato_id') // Considera apenas registros únicos por ingresso_candidato_id
            ->count('ingresso_candidato_id'); // Conta os registros distintos

        $disciplinas = ServidoresEncaminhado::select('formacao')
            ->distinct()
            ->groupBy('formacao')
            ->where('formacao', '<>', '0')
            ->where('formacao', '<>', '#N/D')
            ->whereNotNull('formacao')
            ->whereRaw('formacao != ""')
            ->get();

        // $provimentos_encaminhados = session()->get('provimentos_encaminhados');
        // Obter os IDs dos provimentos encaminhados da sessão
        $provimentosEncaminhadosIDs = session('provimentos_encaminhados')->pluck('id');
        $provimentos_encaminhados = ProvimentosEncaminhado::whereIn('id', $provimentosEncaminhadosIDs)->get();
        session(['provimentos_encaminhados' => $provimentos_encaminhados]);

        return view('provimento.show_provimento_efetivo', [
            'provimentos_encaminhados' => $provimentos_encaminhados,
            'disciplinas' => $disciplinas,
            'quantidadeRegistros' => $quantidadeRegistros,
            'quantidadeRegistrosPCH' => $quantidadeRegistrosPCH,
            'quantidadeRegistrosError' => $quantidadeRegistrosError,
            'quantidadeRegistrosErrorOK' => $quantidadeRegistrosErrorOK,
            'quantidadeRegistrosDataNula' => $quantidadeRegistrosDataNula,
            'quantidadeRegistrosAtrasados' => $quantidadeRegistrosAtrasados,
            'quantidadeRegistrosComAssuncao' => $quantidadeRegistrosComAssuncao,
        ]);
    }

    public function showProvimentoEfetivoByForm(Request $request)
    {


        $anoRef = session()->get('ano_ref');
        $provimentos_encaminhados = ProvimentosEncaminhado::query();
        $quantidadeRegistros = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->count('ingresso_candidato_id');
        $quantidadeRegistrosPCH = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->where('pch', 'OK')->count('ingresso_candidato_id');
        $quantidadeRegistrosError = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->where('pch', 'INCONSISTENCIA')->count('ingresso_candidato_id');
        $quantidadeRegistrosErrorOK = ProvimentosEncaminhado::where('ano_ref', $anoRef)->distinct('ingresso_candidato_id')->where('pch', 'INCONSISTENCIA')->where('inconsistencia', 'OK')->count('ingresso_candidato_id');

        $quantidadeRegistrosComAssuncao = ProvimentosEncaminhado::where('ano_ref', $anoRef)
            ->whereNotNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
            ->whereNotNull('data_encaminhamento') // Garante que data_encaminhamento não seja nulo
            ->distinct('ingresso_candidato_id') // Considera apenas registros únicos por ingresso_candidato_id
            ->count('ingresso_candidato_id'); // Conta os registros distintos

        $quantidadeRegistrosDataNula = ProvimentosEncaminhado::where('ano_ref', $anoRef)
            ->whereNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
            ->whereNotNull('data_encaminhamento') // Garante que data_encaminhamento não seja nulo
            ->whereRaw("DATEDIFF(?, data_encaminhamento) < 2", [Carbon::now()->format('Y-m-d')]) // Diferença de 2 ou mais dias
            ->distinct('ingresso_candidato_id') // Considera apenas registros únicos por ingresso_candidato_id
            ->count('ingresso_candidato_id'); // Conta os registros distintos

        $quantidadeRegistrosAtrasados = ProvimentosEncaminhado::where('ano_ref', $anoRef)
            ->whereNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
            ->whereNotNull('data_encaminhamento') // Garante que data_encaminhamento não seja nulo
            ->whereRaw("DATEDIFF(?, data_encaminhamento) >= 2", [Carbon::now()->format('Y-m-d')]) // Diferença de 2 ou mais dias
            ->distinct('ingresso_candidato_id') // Considera apenas registros únicos por ingresso_candidato_id
            ->count('ingresso_candidato_id'); // Conta os registros distintos

        if ($request->filled('search_nte_provimento_efetivos')) {
            $provimentos_encaminhados = $provimentos_encaminhados->whereHas('uee', function ($query) use ($request) {
                $query->where('nte', $request->search_nte_provimento_efetivos);
            });
        }

        if ($request->filled('search_situation_efetivo')) {
            $provimentos_encaminhados = $provimentos_encaminhados->where(function ($query) use ($request) {
                $query->where('server_1_situation', $request->search_situation_efetivo)
                    ->orWhere('server_2_situation', $request->search_situation_efetivo);
            });
        }

        if ($request->filled('search_municipio_provimento_efetivos')) {
            $provimentos_encaminhados = $provimentos_encaminhados->whereHas('uee', function ($query) use ($request) {
                $query->where('municipio', $request->search_municipio_provimento_efetivos);
            });
        }

        if ($request->filled('search_pch_efetivo')) {
            $provimentos_encaminhados = $provimentos_encaminhados->where('pch', $request->search_pch_efetivo);
        }

        if ($request->filled('search_uee_provimento_efetivos')) {
            $provimentos_encaminhados = $provimentos_encaminhados->whereHas('uee', function ($query) use ($request) {
                $query->where('unidade_escolar', $request->search_uee_provimento_efetivos);
            });
        }

        if ($request->filled('search_codigo_unidade_escolar_efetivo')) {
            $provimentos_encaminhados = $provimentos_encaminhados->whereHas('uee', function ($query) use ($request) {
                $query->where('cod_unidade', $request->search_codigo_unidade_escolar_efetivo);
            });
        }

        if ($request->filled('search_cpf_servidor_efetivo')) {
            $provimentos_encaminhados = $provimentos_encaminhados->whereHas('servidorEncaminhado', function ($query) use ($request) {
                $query->where('cpf', $request->search_cpf_servidor_efetivo);
            });
        }

        if ($request->filled('search_assuncao_efetivo')) {
            $provimentos_encaminhados = $provimentos_encaminhados->whereHas('servidorEncaminhado', function ($query) use ($request) {
                if ($request->search_assuncao_efetivo === "PRAZO VENCIDO") {
                    $query->whereNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
                        ->whereNotNull('data_encaminhamento') // Garante que data_encaminhamento não seja nulo
                        ->whereRaw("DATEDIFF(?, data_encaminhamento) >= 2", [Carbon::now()->format('Y-m-d')]); // Diferença de 2 ou mais dias;
                } elseif ($request->search_assuncao_efetivo === "DENTRO DO PRAZO") {
                    $query->whereNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
                        ->whereNotNull('data_encaminhamento') // Garante que data_encaminhamento não seja nulo
                        ->whereRaw("DATEDIFF(?, data_encaminhamento) < 2", [Carbon::now()->format('Y-m-d')]); // Diferença de 2 ou mais dias
                } else {
                    $query->whereNotNull('data_assuncao') // Filtra registros onde data_assuncao é nulo
                        ->whereNotNull('data_encaminhamento'); // Garante que data_encaminhamento não seja nulo
                }
            });
        }


        if ($request->filled('search_disciplina_efetivo')) {
            $disciplinasSelecionadas = $request->input('search_disciplina_efetivo');

            if (is_array($disciplinasSelecionadas)) {
                $provimentos_encaminhados = $provimentos_encaminhados->whereHas('servidorEncaminhado', function ($query) use ($request) {
                    $query->where('formacao', $request->search_disciplina_efetivo);
                });
            }
        }

        $provimentos_encaminhados = $provimentos_encaminhados->where('ano_ref', $anoRef)->get();



        session()->put('provimentos_encaminhados', $provimentos_encaminhados);

        return view('provimento.show_provimento_efetivo', [
            'provimentos_encaminhados' => $provimentos_encaminhados,
            'quantidadeRegistros' => $quantidadeRegistros,
            'quantidadeRegistrosPCH' => $quantidadeRegistrosPCH,
            'quantidadeRegistrosError' => $quantidadeRegistrosError,
            'quantidadeRegistrosErrorOK' => $quantidadeRegistrosErrorOK,
            'quantidadeRegistrosDataNula' => $quantidadeRegistrosDataNula,
            'quantidadeRegistrosAtrasados' => $quantidadeRegistrosAtrasados,
            'quantidadeRegistrosComAssuncao' => $quantidadeRegistrosComAssuncao,
        ]);
    }

    public function gerarExcelEncaminhamentoEfetivos()
    {

        $provimentos_encaminhados = collect(session()->get('provimentos_encaminhados'));
        $provimentosOrdenados = $provimentos_encaminhados->sortBy([
            ['uee.nte', 'asc'],
            ['uee.municipio', 'asc'],
            ['uee.unidade_escolar', 'asc'],
        ]);

        // dd($provimentosOrdenados->toArray());

        return view('excel.excel_provimento_efetivos', compact('provimentosOrdenados'));
    }

    public function detailProvimentoEfetivo($id)
    {

        // Se recebeu um ingresso_candidato_id (vários registros), carregue todos os encaminhamentos desse servidor
        $group = ProvimentosEncaminhado::with(['servidorEncaminhado', 'uee', 'servidorSubstituido', 'segundoServidorSubstituido'])
            ->where('ingresso_candidato_id', $id)
            ->get();

        if ($group->isNotEmpty()) {
            // Usa o primeiro registro como base para o formulário e também passa o grupo completo
            $provimento_efetivo = $group->first();
            // Para compatibilidade com a view, passe instâncias de ProvimentosEncaminhado
            $servidor_encaminhado = $provimento_efetivo; // possui relacionamento servidorEncaminhado
            $unidade_encaminhamento = $provimento_efetivo; // possui relacionamento uee
            $servidor_subistituido = $provimento_efetivo; // possui relacionamento servidorSubstituido
            $segundo_servidor_subistituido = $provimento_efetivo; // possui relacionamento segundoServidorSubstituido

            return view('provimento.detail_provimento_efetivo', [
                'servidor_encaminhado' => $servidor_encaminhado,
                'unidade_encaminhamento' => $unidade_encaminhamento,
                'servidor_subistituido' => $servidor_subistituido,
                'segundo_servidor_subistituido' => $segundo_servidor_subistituido,
                'provimento_efetivo' => $provimento_efetivo,
                'provimentos_group' => $group,
            ]);
        }

        // Caso contrário, tenta carregar por id de provimento (comportamento anterior)
        $servidor_encaminhado = ProvimentosEncaminhado::with('servidorEncaminhado')->where('id', $id)->first();
        $unidade_encaminhamento = ProvimentosEncaminhado::with('uee')->where('id', $id)->first();
        $servidor_subistituido = ProvimentosEncaminhado::with('servidorSubstituido')->where('id', $id)->first();
        $segundo_servidor_subistituido = ProvimentosEncaminhado::with('segundoServidorSubstituido')->where('id', $id)->first();
        $provimento_efetivo = ProvimentosEncaminhado::where('id', $id)->first();

        return view('provimento.detail_provimento_efetivo', [
            'servidor_encaminhado' => $servidor_encaminhado,
            'unidade_encaminhamento' => $unidade_encaminhamento,
            'servidor_subistituido' => $servidor_subistituido,
            'segundo_servidor_subistituido' => $segundo_servidor_subistituido,
            'provimento_efetivo' => $provimento_efetivo,
        ]);
    }

    public function updateProvimentoEfetivo(Request $request)
    {
        $encaminhamento = ProvimentosEncaminhado::findOrFail($request->id);

        // Prevent accidental mass-assignment of array fields that don't exist in DB
        $data = $request->except(['user_id', 'disciplinas', 'matutino', 'vespertino', 'noturno']);

        // Map carencia_tipo (form field) to tipo_carencia (DB column)
        if ($request->filled('carencia_tipo')) {
            $encaminhamento->tipo_carencia = $request->carencia_tipo;
            if (array_key_exists('carencia_tipo', $data)) {
                unset($data['carencia_tipo']);
            }
        }

        // Handle disciplines array: if multiple disciplines submitted, keep first on current row
        // and create additional ProvimentosEncaminhado rows for the remaining disciplines.
        $disciplines = $request->input('disciplinas', []);
        $matutino = $request->input('matutino', []);
        $vespertino = $request->input('vespertino', []);
        $noturno = $request->input('noturno', []);

        DB::beginTransaction();
        try {
            if (is_array($disciplines) && count($disciplines) > 0) {
                // set first discipline on existing record
                $firstDisc = $disciplines[0];
                $encaminhamento->disciplina = $firstDisc;

                // map shift values for first row
                if (is_array($matutino)) {
                    $encaminhamento->matutino = $matutino[0] ?? (count($matutino) ? implode(', ', $matutino) : null);
                }
                if (is_array($vespertino)) {
                    $encaminhamento->vespertino = $vespertino[0] ?? (count($vespertino) ? implode(', ', $vespertino) : null);
                }
                if (is_array($noturno)) {
                    $encaminhamento->noturno = $noturno[0] ?? (count($noturno) ? implode(', ', $noturno) : null);
                }

                // Apply scalar updates to the current row
                $encaminhamento->fill($data);
                $encaminhamento->save();

                // For any additional disciplines, replicate the current row and set the discipline/turnos
                for ($i = 1; $i < count($disciplines); $i++) {
                    $dName = $disciplines[$i];
                    $new = $encaminhamento->replicate();
                    $new->disciplina = $dName;
                    $new->matutino = is_array($matutino) ? ($matutino[$i] ?? ($matutino[0] ?? null)) : $matutino;
                    $new->vespertino = is_array($vespertino) ? ($vespertino[$i] ?? ($vespertino[0] ?? null)) : $vespertino;
                    $new->noturno = is_array($noturno) ? ($noturno[$i] ?? ($noturno[0] ?? null)) : $noturno;
                    // Ensure timestamps are fresh
                    $new->created_at = now();
                    $new->updated_at = now();
                    $new->save();
                }
            } else {
                // No disciplines array — perform a normal update
                $encaminhamento->update($data);
            }

            DB::commit();
            return redirect()->to(url()->previous())->with('msg', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Erro ao atualizar Provimento Efetivo: ' . $e->getMessage());
            return redirect()->to(url()->previous())->with('msg', 'error');
        }
    }

    public function destroyProvimentoEfetivo(ProvimentosEncaminhado $provimentosEncaminhado)
    {

        $provimentosEncaminhado->delete();

        return redirect('/provimento/efetivo/show')->with('msg', 'success');
    }

    public function update_situation_server1($situation, $id)
    {
        $idDoUsuarioLogado = auth()->user()->id;


        if (($situation == 404) || ($situation == null)) {
            $encaminhamento = ProvimentosEncaminhado::findOrFail($id);
            $pch = "PENDENTE";
            if ($encaminhamento->update([
                'server_1_situation' => $situation,
                'pch' => $pch,
            ])) {
                $value = 0;
                return Response()->json($value);
            }
        } elseif ($situation == 2) {

            $encaminhamento = ProvimentosEncaminhado::findOrFail($id);

            $pch = "INCONSISTENCIA";

            if ($encaminhamento->update([
                'server_1_situation' => $situation,
                'pch' => $pch,

            ])) {
                return response()->json(['value' => 0]);
            }
        } else {

            $encaminhamento = ProvimentosEncaminhado::findOrFail($id);
            $pch = "OK";
            if ($encaminhamento->update([
                'server_1_situation' => $situation,
                'user_update_id' => $idDoUsuarioLogado,
                'pch' => $pch,
            ])) {
                $value = 1;
                return Response()->json($value);
            }
        }
    }

    public function update_situation_server2($situation, $id)
    {

        if ($situation == 404) {
            $encaminhamento = ProvimentosEncaminhado::findOrFail($id);
            $pch = "PENDENTE";
            if ($encaminhamento->update([
                'server_2_situation' => $situation,
                'pch' => $pch,
            ])) {
                $value = 0;
                return Response()->json($value);
            }
        } elseif ($situation == 2) {
            $encaminhamento = ProvimentosEncaminhado::findOrFail($id);
            $pch = "INCONSISTENCIA";
            if ($encaminhamento->update([
                'server_2_situation' => $situation,
                'pch' => $pch,
            ])) {
                $value = 0;
                return Response()->json($value);
            }
        } else {
            $encaminhamento = ProvimentosEncaminhado::findOrFail($id);
            $pch = "OK";
            if ($encaminhamento->update([
                'server_2_situation' => $situation,
                'pch' => $pch,
            ])) {
                $value = 1;
                return Response()->json($value);
            }
        }
    }

    public function update_inconsistencia($id)
    {
        $arg1 = "OK";
        $arg2 = "";

        $encaminhamento = ProvimentosEncaminhado::findOrFail($id);

        // Verifica o valor atual do campo 'inconsistencia'
        $valorAtual = $encaminhamento->inconsistencia;

        // Lógica de atualização com base nos valores existentes
        if ($valorAtual === $arg1) {
            $encaminhamento->update(['inconsistencia' => $arg2]);
        } elseif (($valorAtual === $arg2) || is_null($valorAtual)) {
            $encaminhamento->update(['inconsistencia' => $arg1]);
        }

        $encaminhamento = ProvimentosEncaminhado::findOrFail($id);

        return response()->json(['message' => $encaminhamento]);
    }

    public function gerarEncaminhamento($encaminhamento)
    {


        $provimentos_encaminhado = ProvimentosEncaminhado::where('id', $encaminhamento)
            ->first();

        // Verifica se há dados antes de calcular
        if ($provimentos_encaminhado) {
            $provimentos_encaminhado->total_matutino = array_sum(array_map('intval', explode(',', $provimentos_encaminhado->matutino)));
            $provimentos_encaminhado->total_vespertino = array_sum(array_map('intval', explode(',', $provimentos_encaminhado->vespertino)));
            $provimentos_encaminhado->total_noturno = array_sum(array_map('intval', explode(',', $provimentos_encaminhado->noturno)));
        }

        // If not found in provimentos_encaminhados, try ingresso_encaminhamentos table
        if (! $provimentos_encaminhado) {
            $ing = DB::table('ingresso_encaminhamentos')->where('id', $encaminhamento)->first();
            if ($ing) {
                // map ingresso_encaminhamentos row into an object compatible with the termo view
                $obj = new \stdClass();
                $obj->id = $ing->id;
                $obj->data_encaminhamento = $ing->data_encaminhamento ?? $ing->created_at ?? null;
                $obj->disciplina = $ing->disciplina_name ?? $ing->disciplina_nome ?? $ing->disciplina ?? ($ing->disciplina_code ?? '');
                $obj->total_matutino = intval($ing->quant_matutino ?? $ing->total_matutino ?? 0);
                $obj->total_vespertino = intval($ing->quant_vespertino ?? $ing->total_vespertino ?? 0);
                $obj->total_noturno = intval($ing->quant_noturno ?? $ing->total_noturno ?? 0);
                // uee mapping
                $uee = new \stdClass();
                $uee->unidade_escolar = $ing->uee_name ?? $ing->uee_nome ?? $ing->uee ?? '';
                $uee->cod_unidade = $ing->uee_code ?? $ing->uee_codigo ?? $ing->cod_unidade ?? '';
                $obj->uee = $uee;
                // servidor (candidate) mapping - try to fetch IngressoCandidato
                $ingressoCandidate = null;
                if (! empty($ing->ingresso_candidato_id)) {
                    try { $ingressoCandidate = \App\Models\IngressoCandidato::find($ing->ingresso_candidato_id); } catch (\Throwable $e) { $ingressoCandidate = null; }
                }
                $obj->servidorEncaminhado = $ingressoCandidate;
                // preserve servidor_id from ingresso_encaminhamentos (used for substituted server)
                $obj->servidor_id = $ing->servidor_id ?? null;
                $obj->servidor_substituido_id = $ing->servidor_id ?? null;
                if (! empty($obj->servidor_id)) {
                    try { $obj->servidorSubstituido = \App\Models\Servidore::find($obj->servidor_id); } catch (\Throwable $e) { $obj->servidorSubstituido = null; }
                }
                $provimentos_encaminhado = $obj;
            }
        }

        // Try to load ingresso_encaminhamentos for this candidate and pass to view
        $encaminhamentos = collect();
        try {
            $candidateId = $provimentos_encaminhado->ingresso_candidato_id ?? null;
            // fallback: use related servidorEncaminhado model id if available
            if (empty($candidateId) && isset($provimentos_encaminhado->servidorEncaminhado)) {
                $candidateId = optional($provimentos_encaminhado->servidorEncaminhado)->id ?? $candidateId;
            }
            if ($candidateId) {
                $encaminhamentos = DB::table('ingresso_encaminhamentos')
                    ->where('ingresso_candidato_id', $candidateId)
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
            // temporary debug log to help trace cases where only one row is returned
            \Illuminate\Support\Facades\Log::info('gerarEncaminhamento: found encaminhamentos', ['enc_count' => is_object($encaminhamentos) ? count($encaminhamentos) : 0, 'candidateId' => $candidateId]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('gerarEncaminhamento: failed to load encaminhamentos', ['exception' => $e->getMessage()]);
            $encaminhamentos = collect();
        }

        return view('relatorios.termo_encaminhamento', [
            'provimentos_encaminhado' => $provimentos_encaminhado,
            'encaminhamentos' => $encaminhamentos,
        ]);
    }

    public function validarDocs(Request $request)
    {
        $anoRef = session()->get('ano_ref');

        $query = Provimento::query();

        $query->select(
            DB::raw('MIN(id) as provimento_id'),
            'servidor',
            'cadastro',
            'vinculo',
            'situacao_provimento',
            'num_cop',
            'nte',
            'municipio',
            'unidade_escolar',
            'cod_unidade',
            'arquivo_comprobatorio'
        );

        $query->where('ano_ref', $anoRef)
            ->groupBy('servidor', 'cadastro', 'vinculo', 'situacao_provimento', 'num_cop', 'nte', 'municipio', 'unidade_escolar', 'cod_unidade', 'arquivo_comprobatorio');

        // filtros simples via query string (GET)
        if ($request->filled('nte_seacrh')) {
            $query->where('nte', $request->get('nte_seacrh'));
        }

        if ($request->filled('municipio_search')) {
            $query->where('municipio', 'like', '%' . $request->get('municipio_search') . '%');
        }

        if ($request->filled('search_uee')) {
            $query->where('unidade_escolar', 'like', '%' . $request->get('search_uee') . '%');
        }

        if ($request->filled('search_servidor_matricula')) {
            $term = $request->get('search_servidor_matricula');
            $query->where(function ($q) use ($term) {
                $q->where('servidor', 'like', '%' . $term . '%')
                    ->orWhere('cadastro', 'like', '%' . $term . '%');
            });
        }

        if ($request->filled('search_situacao_provimento')) {
            $query->where('situacao_provimento', $request->get('search_situacao_provimento'));
        }

        // Ordenação por NTE
        $query->orderBy('nte', 'asc');

        $servidores = $query->distinct()->get();

        return view('provimento.validar_docs', compact('servidores'));
    }

    public function update_cop(Request $request)
    {
        // AVISO: A validação foi removida a pedido, mas é crucial para produção.

        try {
            // Usar a closure de DB::transaction garante que o commit e rollback
            // sejam tratados automaticamente, mantendo a integridade dos dados.
            $affectedRows = DB::transaction(function () use ($request) {
                $servidorCadastro = $request->servidor_cadastro;
                $numCopNovo = $request->num_cop;

                // 1. Encontra o estado atual para saber qual era o COP antigo.
                $primeiroProvimento = Provimento::where('cadastro', $servidorCadastro)->first();

                // Se não houver provimentos para este servidor, não há o que atualizar.
                if (!$primeiroProvimento) {
                    return 0;
                }

                $numCopAntigo = $primeiroProvimento->num_cop;

                // 2. Lógica de atualização da quantidade (só executa se houver mudança).
                if ($numCopAntigo !== $numCopNovo) {
                    // Se existia um COP antigo, devolvemos a unidade ao estoque.
                    if ($numCopAntigo) {
                        NumCop::where('num', $numCopAntigo)->increment('quantidade');
                    }

                    // Se um novo COP foi atribuído, removemos a unidade do estoque.
                    if ($numCopNovo) {
                        // firstOrFail() lança uma exceção se o COP não for encontrado,
                        // o que reverte a transação de forma segura.
                        $copParaDebitar = NumCop::where('num', $numCopNovo)->firstOrFail();
                        $copParaDebitar->decrement('quantidade');
                    }
                }

                // 3. Operação de Atualização em Massa principal.
                return Provimento::where('cadastro', $servidorCadastro)
                    ->update(['num_cop' => $numCopNovo]);
            });

            // FEEDBACK DE SUCESSO
            return redirect()->back()->with('msg', "O Nº COP foi atualizado para {$affectedRows} provimento(s) do servidor.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Captura o erro específico se o NumCop não for encontrado.
            Log::error('Falha ao atualizar Nº COP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Operação cancelada: O número de COP informado não é válido.');
        } catch (\Exception $e) {
            // Captura qualquer outro erro que possa ocorrer durante a transação.
            Log::error('Falha ao atualizar Nº COP do servidor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao tentar atualizar o Nº COP.');
        }
    }

    public function update_assuncao(Request $request)
    {
        $situacao = $request->situacao_provimento;
        $servidorCadastro = $request->servidor_cadastro;
        $updatedCount = 0;
        // Decide quais provimentos atualizar: por padrão todos do cadastro,
        // mas se `cod_unidade` ou `provimento_id` vier no request, limitamos a atualização.
        $provimentosQuery = Provimento::where('cadastro', $servidorCadastro);

        // If a provimento_id was provided, try to limit by its unidade (cod_unidade or unidade_escolar)
        if ($request->filled('provimento_id')) {
            $selected = Provimento::find($request->provimento_id);
            if ($selected) {
                if (!empty($selected->cod_unidade)) {
                    $provimentosQuery->where('cod_unidade', $selected->cod_unidade);
                } elseif (!empty($selected->unidade_escolar)) {
                    $ue = trim(mb_strtolower($selected->unidade_escolar));
                    $provimentosQuery->whereRaw('LOWER(TRIM(unidade_escolar)) = ?', [$ue]);
                }
            }
        } elseif ($request->filled('cod_unidade')) {
            $provimentosQuery->where('cod_unidade', $request->cod_unidade);
        } elseif ($request->filled('unidade_escolar')) {
            $ue = trim(mb_strtolower($request->unidade_escolar));
            $provimentosQuery->whereRaw('LOWER(TRIM(unidade_escolar)) = ?', [$ue]);
        }

        // Trata cenário PROVIDA: exige arquivo e salva data_assuncao.
        if ($situacao === 'provida') {
            $request->validate([
                'arquivo_comprobatorio' => '|file|mimes:pdf,jpeg,jpg|max:5120', // 5MB
                'data_assuncao' => 'required|date',
            ], [
                'arquivo_comprobatorio.mimes' => 'O arquivo deve ser do tipo PDF, JPEG ou JPG.',
                'arquivo_comprobatorio.max' => 'O arquivo não pode ser maior que 5MB.',
                'data_assuncao.required' => 'A data de assunção é obrigatória quando a situação for PROVIDA.',
            ]);

            $arquivo = $request->file('arquivo_comprobatorio');
            $filename = time() . '_' . uniqid() . '.' . $arquivo->getClientOriginalExtension();
            // Salva o novo arquivo
            $arquivo->storeAs('provimentos', $filename, 'public');

            $dataAssuncao = $request->data_assuncao;

            $provimentos = $provimentosQuery->get();

            foreach ($provimentos as $provimento) {
                // Remove arquivo antigo se existir
                if ($provimento->arquivo_comprobatorio && Storage::disk('public')->exists('provimentos/' . $provimento->arquivo_comprobatorio)) {
                    Storage::disk('public')->delete('provimentos/' . $provimento->arquivo_comprobatorio);
                }

                $provimento->arquivo_comprobatorio = $filename;
                $provimento->situacao_provimento = 'provida';
                $provimento->data_assuncao = $dataAssuncao;
                // Mantém data_encaminhamento como estava (não sobrescrever), a menos que queira alterar explicitamente
                if ($provimento->save()) {
                    $updatedCount++;
                }
            }

            // Redirect back to the detalhes view, preserving the provimento_id filter when possible
            $redirectUrl = "/detalhes_servidor/{$servidorCadastro}";
            if ($request->filled('provimento_id')) {
                $redirectUrl .= '?provimento_id=' . $request->provimento_id;
            } elseif ($request->filled('cod_unidade')) {
                $rep = Provimento::where('cadastro', $servidorCadastro)->where('cod_unidade', $request->cod_unidade)->first();
                if ($rep) $redirectUrl .= '?provimento_id=' . $rep->id;
            }
            return redirect($redirectUrl);
        }

        // Trata cenário TRAMITE: grava situacao_provimento = 'tramite', seta data_encaminhamento e limpa data_assuncao e arquivo.
        if ($situacao === 'tramite') {
            $request->validate([
                'data_encaminhamento' => 'required|date',
            ], [
                'data_encaminhamento.required' => 'A data de encaminhamento é obrigatória quando a situação for TRAMITE.',
            ]);

            $dataEncaminhamento = $request->data_encaminhamento;

            $provimentos = $provimentosQuery->get();

            foreach ($provimentos as $provimento) {
                // Remove arquivo antigo se existir
                if ($provimento->arquivo_comprobatorio && Storage::disk('public')->exists('provimentos/' . $provimento->arquivo_comprobatorio)) {
                    Storage::disk('public')->delete('provimentos/' . $provimento->arquivo_comprobatorio);
                }

                $provimento->arquivo_comprobatorio = null;
                $provimento->situacao_provimento = 'tramite';
                $provimento->data_encaminhamento = $dataEncaminhamento;
                $provimento->data_assuncao = null;

                if ($provimento->save()) {
                    $updatedCount++;
                }
            }

            // Redirect back to the detalhes view, preserving the provimento_id filter when possible
            $redirectUrl = "/detalhes_servidor/{$servidorCadastro}";
            if ($request->filled('provimento_id')) {
                $redirectUrl .= '?provimento_id=' . $request->provimento_id;
            } elseif ($request->filled('cod_unidade')) {
                $rep = Provimento::where('cadastro', $servidorCadastro)->where('cod_unidade', $request->cod_unidade)->first();
                if ($rep) $redirectUrl .= '?provimento_id=' . $rep->id;
            }
            return redirect($redirectUrl);
        }

        // Se não for PROVIDA nem TRAMITE, não faz nada
        return 0;
    }

    public function viewArquivo($filename)
    {
        // Suporta tanto nomes simples quanto caminhos completos (YYYY/MM/arquivo)
        $path = storage_path('app/public/provimentos/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'Arquivo não encontrado');
        }

        // Verificação de tamanho para evitar problemas de memória
        $fileSize = filesize($path);
        if ($fileSize > 10 * 1024 * 1024) { // 10MB
            abort(413, 'Arquivo muito grande para visualização');
        }

        // Verifica se o usuário tem permissão para ver o arquivo
        if (!auth()->check()) {
            abort(403, 'Acesso não autorizado');
        }

        // Get file info
        $mimeType = mime_content_type($path);
        $filename = basename($path);

        // Return file response with proper headers for cache
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'public, max-age=31536000', // Cache por 1 ano
            'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000)
        ]);
    }
}
