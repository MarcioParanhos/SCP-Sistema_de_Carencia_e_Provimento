<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servidore;
use App\Models\Provimento;
use App\Models\ServidoresEncaminhado;
use App\Models\NumCop;
use Illuminate\Support\Facades\Auth;

class ServidoreController extends Controller
{
    public function searchServidor($cadastro_servidor)
    {

        $data = Servidore::where('cadastro', 'LIKE', '%' . $cadastro_servidor . '%')->get();


        if ($data) {
            return Response()->json($data);
        } else {
            return Response("Testando");
        }
    }

    public function addShowServidores()
    {
        $currentYear = date('Y');

        if (Auth::user()->profile === "cpg_tecnico") {
            $servidores = Servidore::where('tipo', '=', 'cadastrado')
                ->where('profile', 'cpg_tecnico')
                ->whereYear('created_at', $currentYear)
                ->get();
        }

        if ((Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "cpm_coordenador")) {
            $servidores = Servidore::where('tipo', '=', 'cadastrado')
                ->whereYear('created_at', $currentYear)
                ->get();
        }

        if ((Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpg_tecnico")) {
            $servidores = Servidore::where('tipo', '=', 'cadastrado')
                ->whereYear('created_at', $currentYear)
                ->get();
        }



        return view('servidores.add_show_servidores', compact('servidores'));
    }

    /**
     * Server-side endpoint for DataTables
     */
    public function data(Request $request)
    {
        $currentYear = date('Y');

        // use a query builder so we can apply where/orderBy/offset/limit
        $query = Servidore::query();

        // total records (before filtering)
        $totalData = $query->count();

        // Search
        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                    ->orWhere('cpf', 'LIKE', "%{$search}%")
                    ->orWhere('cadastro', 'LIKE', "%{$search}%")
                    ->orWhere('vinculo', 'LIKE', "%{$search}%");
            });
        }

        $recordsFiltered = $query->count();

        // Ordering
        $columns = ['nome', 'cpf', 'cadastro', 'vinculo', 'regime', 'created_at'];
        $orderColIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColIndex] ?? 'nome';

        // Pagination
        $start = intval($request->input('start', 0));
        $length = intval($request->input('length', 10));

        $data = $query->orderBy($orderColumn, $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        $rows = [];
        foreach ($data as $row) {
            $editUrl = url('/servidor/detalhes/' . $row->id);
            $deleteUrl = url('/servidor/destroy/' . $row->id);
            $csrf = csrf_token();

            // Old-style action buttons (edit + form delete) with Portuguese titles
            $actions = "<a href='" . $editUrl . "' class='mr-2' title='Editar'>";
            $actions .= "<button type='button' class='btn-show-carência btn btn-primary'><i class='ti-pencil'></i></button></a>";

            $actions .= "<form style='display:inline-block' method='POST' action='" . $deleteUrl . "' onsubmit=\"return confirm('Confirmar exclusão?');\">";
            $actions .= "<input type='hidden' name='_token' value='" . $csrf . "'>";
            $actions .= "<input type='hidden' name='_method' value='DELETE'>";
            $actions .= "<button type='submit' class='btn-show-carência btn btn-danger' title='Excluir'><i class='ti-trash'></i></button>";
            $actions .= "</form>";

            $rows[] = [
                'nome' => $row->nome,
                'cpf' => $row->cpf,
                'cadastro' => $row->cadastro === $row->cpf ? 'PENDENTE' : $row->cadastro,
                'vinculo' => $row->vinculo,
                'regime' => $row->regime . 'h',
                'created_at' => \Carbon\Carbon::parse($row->created_at)->format('d/m/Y'),
                'action' => $actions,
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows,
        ]);
    }

    public function addShowServidoresByForm(Request $request)
    {

        $servidores = new Servidore;
        $servidores->nome = $request->nome;
        $servidores->cpf = $request->cpf;
        $servidores->tipo = $request->tipo;
        $servidores->vinculo = $request->vinculo;
        $servidores->regime = $request->regime;
        if ($request->filled('cadastro')) {
            $servidores->cadastro = $request->cadastro;
        } else {
            $servidores->cadastro = $request->cpf;
        }
        $servidores->profile = $request->profile;
        $servidores->save();
        return  redirect()->back()->with('msg', 'Servidor cadastrado com Sucesso!');
    }

    public function detalharServidor(Servidore $servidor)
    {

        return view('servidores.detail_servidor', compact('servidor'));
    }

    public function update(Request $request)
    {

        if (($request->filled('nome')) && (!$request->cadastro)) {

            Provimento::where('cadastro', $request->cpf)->update([
                'servidor' => $request->nome,
            ]);
        } else if (($request->filled('nome')) && ($request->cadastro)) {

            Provimento::where('cadastro', $request->cadastro)->update([
                'servidor' => $request->nome,
            ]);
        }

        if (!$request->cadastro) {

            Servidore::findOrFail($request->id)->update([
                'nome' => $request->nome,
                'cpf' => $request->cpf,
                'cadastro' => $request->cpf,
                'vinculo' => $request->vinculo,
                'regime' => $request->regime,
            ]);

            Provimento::where('servidor', $request->nome_old)
                ->update([
                    'cadastro' => $request->cpf,
                    'servidor' => $request->nome,
                    'regime' => $request->regime,
                    'vinculo' => $request->vinculo,
                ]);

            return  redirect()->to(url()->previous())->with('msg', 'Registros Alterados com Sucesso!');
        } else {

            Servidore::findOrFail($request->id)->update($request->all());

            if ($request->cadastro === $request->cpf) {
                dd("QUARTO IF IF");
                Provimento::where('cadastro', $request->cpf)
                    ->update([
                        'cadastro' => $request->cadastro,
                        'servidor' => $request->nome,
                        'regime' => $request->regime,
                        'vinculo' => $request->vinculo,
                    ]);
            } else if ($request->cadastro != $request->cpf) {

                Provimento::where('servidor', $request->nome_old)
                    ->update([
                        'cadastro' => $request->cadastro,
                        'servidor' => $request->nome,
                        'regime' => $request->regime,
                        'vinculo' => $request->vinculo,
                    ]);
            }

            return redirect()->back()->with('msg', 'Registros do servidor e provimentos pelo mesmo atualizados com sucesso!');
        }
    }

    public function destroy($id)
    {
        Servidore::findOrFail($id)->delete();
        return redirect('/servidores')->with('msg', 'Registro excluído com sucesso!');
    }

    public function detalhesServidorAnuencia(Request $request, $cadastro)
    {
        $anoRef = session()->get('ano_ref');

        // Busca o servidor na tabela servidores
        $servidor = Servidore::where('cadastro', $cadastro)->first();

        $provimentoQuery = Provimento::where('cadastro', $cadastro)
            ->where('ano_ref', $anoRef);

        $provimento = Provimento::where('cadastro', $cadastro)->first();

        $provimentoId = $request->query('provimento_id');

        if ($provimentoId) {
            $selectedProv = Provimento::find($provimentoId);
            if ($selectedProv) {
                // Preferir filtragem por cod_unidade (mais consistente). Caso não exista, usar unidade_escolar normalizada.
                if (!empty($selectedProv->cod_unidade)) {
                    $provimentoQuery->where('cod_unidade', $selectedProv->cod_unidade);
                } else if (!empty($selectedProv->unidade_escolar)) {
                    $ue = trim(mb_strtolower($selectedProv->unidade_escolar));
                    $provimentoQuery->whereRaw('LOWER(TRIM(unidade_escolar)) = ?', [$ue]);
                }
                // Use the selected provimento as the representative provimento for the view/form
                $provimento = $selectedProv;
            }
        }

        $provimentosByServidor = $provimentoQuery->get();

        $num_cop = NumCop::all();

        // Se servidor não existir na tabela servidores, criar objeto com dados do provimento
        if (!$servidor && $provimento) {
            $servidor = (object) [
                'nome' => $provimento->servidor ?? 'Nome não informado',
                'cadastro' => $provimento->cadastro ?? $cadastro,
                'vinculo' => $provimento->vinculo ?? 'Não informado',
                'regime' => $provimento->regime ?? '0',
                'nte' => $provimento->nte ?? 0,
                'unidade_escolar' => $provimento->unidade_escolar ?? 'Não informado',
                'cod_unidade' => $provimento->cod_unidade ?? 'Não informado',
                'disciplina' => $provimento->disciplina ?? 'Não informado',
                'provimento_matutino' => $provimento->provimento_matutino ?? 0,
                'provimento_vespertino' => $provimento->provimento_vespertino ?? 0,
                'provimento_noturno' => $provimento->provimento_noturno ?? 0,
                'total' => $provimento->total ?? 0,
                'forma_suprimento' => $provimento->forma_suprimento ?? 'Não informado',
                'tipo_movimentacao' => $provimento->tipo_movimentacao ?? 'Não informado',
                'situacao_provimento' => $provimento->situacao_provimento ?? 'Não informado',
                'data_encaminhamento' => $provimento->data_encaminhamento ?? null,
            ];
        } elseif (!$servidor && !$provimento) {
            // Se nem servidor nem provimento existem, criar objeto vazio para evitar erros
            $servidor = (object) [
                'nome' => 'Servidor não encontrado',
                'cadastro' => $cadastro,
                'vinculo' => 'Não informado',
                'regime' => '0',
                'nte' => 0,
                'unidade_escolar' => 'Não informado',
                'cod_unidade' => 'Não informado',
                'disciplina' => 'Não informado',
                'provimento_matutino' => 0,
                'provimento_vespertino' => 0,
                'provimento_noturno' => 0,
                'total' => 0,
                'forma_suprimento' => 'Não informado',
                'tipo_movimentacao' => 'Não informado',
                'situacao_provimento' => 'Não informado',
                'data_encaminhamento' => null,
            ];
        }

        return view('servidores.detalhes_servidor_anuencia', compact('provimentosByServidor', 'servidor', 'num_cop', 'provimento'));
    }

    public function consultarEfetivo($cpf)
    {

        $data = ServidoresEncaminhado::where('cpf', 'LIKE', '%' . $cpf . '%')->get();


        if ($data) {
            return Response()->json($data);
        } else {
            return Response("Testando");
        }
    }

    public function addShowServidoresEncaminhamentoByForm(Request $request)
    {

        $servidores = new ServidoresEncaminhado;
        $servidores->nome = $request->nome;
        $servidores->cpf = $request->cpf;
        $servidores->cargo = $request->vinculo;
        $servidores->nte = $request->nte;
        $servidores->situacao = "CADASTRADO";
        $servidores->save();
        return  redirect()->back()->with('msg', 'Servidor cadastrado com Sucesso!');
    }
}