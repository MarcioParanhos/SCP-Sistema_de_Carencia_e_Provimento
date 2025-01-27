<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Area;
use App\Models\Carencia;
use App\Models\Eixo_curso;
use App\Models\ComponenteEspecial;
use App\Models\Forma_suprimento;

class ListasSuspensasController extends Controller
{
    public function index()
    {

        return view('listas_suspensas.index');
    }

    public function index_disciplinas()
    {

        $disciplinas = Disciplina::orderBy('nome')->get();

        return view('listas_suspensas.show_disciplinas', [
            'disciplinas' => $disciplinas,
        ]);
    }

    public function index_areas()
    {

        $areas = Area::orderBy('nome')->get();

        return view('listas_suspensas.show_areas', [
            'areas' => $areas,
        ]);
    }

    public function relatorios()
    {
        return view('relatorios.show_relatorios');
    }

    public function create_areas(Request $request)
    {

        $area = new Area;

        $area->nome = $request->area;

        if ($area->save()) {
            return  redirect()->to(url()->previous())->with('msg', 'success');
        }
    }

    public function destroy_area($id)
    {

        $area = Area::where('id', $id)->first();
        $verifyCarenciaByArea = Carencia::where('area', $area->nome)->exists();

        if ($verifyCarenciaByArea) {
            return back()->with('msg', 'error');
        } else {
            Area::findOrFail($id)->delete();
            return back()->with('msg', 'delete_success');
        }
    }

    public function index_cursos()
    {

        $cursos = Eixo_curso::orderBy('curso')->get();
        $eixos = Eixo_curso::select('eixo')->orderBy('eixo')->groupBy('eixo')->get();

        return view('listas_suspensas.show_eixo_cursos', [
            'cursos' => $cursos,
            'eixos' => $eixos,
        ]);
    }

    public function create_cursos(Request $request)
    {

        $curso = new Eixo_curso();

        $curso->curso = $request->curso;
        $curso->eixo = $request->eixo;

        if ($curso->save()) {
            return  redirect()->to(url()->previous())->with('msg', 'success');
        }
    }

    public function destroy_curso($id)
    {

        $curso = Eixo_curso::where('id', $id)->first();
        $verifyCarenciaByCurso = Carencia::where('curso', $curso->curso)->exists();

        if ($verifyCarenciaByCurso) {
            return back()->with('msg', 'error');
        } else {
            Eixo_curso::findOrFail($id)->delete();
            return back()->with('msg', 'delete_success');
        }
    }

    public function index_componente_especial()
    {

        $componentes_especiais = ComponenteEspecial::orderBy('nome')->get();

        return view('listas_suspensas.show_componente_especial', [
            'componentes_especiais' => $componentes_especiais,
        ]);
    }

    public function create_componente_especial(Request $request)
    {

        $componente_especial = new ComponenteEspecial();

        $componente_especial->nome = $request->componente_especial;

        if ($componente_especial->save()) {
            return  redirect()->to(url()->previous())->with('msg', 'success');
        }
    }

    public function destroy_componente_especial($id)
    {

        $componente_especial = ComponenteEspecial::where('id', $id)->first();
        $verifyCarenciaByComponenteEspecial = Carencia::where('disciplina', $componente_especial->nome)->exists();

        if ($verifyCarenciaByComponenteEspecial) {
            return back()->with('msg', 'error');
        } else {
            ComponenteEspecial::findOrFail($id)->delete();
            return back()->with('msg', 'delete_success');
        }
    }

    public function index_forma_suprimento()
    {

        $formas_suprimentos = Forma_suprimento::orderBy('forma')->get();

        return view('listas_suspensas.show_forma_suprimento', [
            'formas_suprimentos' => $formas_suprimentos,
        ]);
    }
}
