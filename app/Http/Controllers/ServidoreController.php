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

    public function detalhesServidorAnuencia($cadastro)
    {
        $anoRef = session()->get('ano_ref');

        // Busca o servidor na tabela servidores
        $servidor = Servidore::where('cadastro', $cadastro)->first();
        
        // Busca provimentos do servidor
        $provimentosByServidor = Provimento::where('cadastro', $cadastro)
            ->where('ano_ref', $anoRef)
            ->get();
        $num_cop = NumCop::all();
        $provimento = Provimento::where('cadastro', $cadastro)->first();

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