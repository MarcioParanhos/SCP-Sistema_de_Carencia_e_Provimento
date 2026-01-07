<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Carencia;
use App\Mail\AtendimentoCanceladoMail;
use Illuminate\Support\Facades\Mail;

class DisciplinaController extends Controller
{
    public function searchDisciplinas(Request $request) {
        // Return list filtered by query 'q' for Select2 autocomplete
        $q = $request->input('q');
        $query = Disciplina::query();
        if (!empty($q)) {
            $query->where('nome', 'like', '%' . $q . '%');
        }
        $data = $query->orderBy('nome', 'asc')->limit(60)->get();
        return response()->json($data);
    }

    public function create (Request $request) {

        $disciplina = new Disciplina;

        $disciplina->nome = $request->disciplina;

        if ($disciplina->save()) {
            return  redirect()->to(url()->previous())->with('msg', 'success');
        }

    }

    public function destroy($id)
    {

        $disciplina = Disciplina::where('id', $id)->first();
        $verifyCarenciaByDisciplina = Carencia::where('disciplina', $disciplina->nome)->exists();

        if ($verifyCarenciaByDisciplina) {
            return back()->with('msg', 'error');
        } else {
            disciplina::findOrFail($id)->delete();
            $detalhesAtendimento = [
                'usuario' => "Marcio Paranhos",
                'profissional' => "Testando",
                'data' => "Testando",
            ];
            
            Mail::to("marcio.naga@gmail.com")->send(new AtendimentoCanceladoMail($detalhesAtendimento));
            return back()->with('msg', 'delete_success');
        }
    }
}