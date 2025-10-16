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

        $servidor = Servidore::where('cadastro', $cadastro)->first();
        $provimentosByServidor = Provimento::where('cadastro', $cadastro)->get();
        $num_cop = NumCop::all();
        $provimento = Provimento::where('cadastro', $cadastro)->first();
        return view('servidores.detalhes_servidor_anuencia', compact('provimentosByServidor', 'servidor','num_cop','provimento'));
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
