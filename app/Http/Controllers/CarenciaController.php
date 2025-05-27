<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Carencia;
use App\Models\Eixo_curso;
use App\Models\Disciplina;
use App\Models\Uee;
use App\Models\Motivo_vaga;
use App\Models\Provimento;
use App\Models\Area;
use App\Models\Log;
use App\Models\ComponenteEspecial;
use App\Models\VagaReserva;
use App\Models\Forma_suprimento;

class CarenciaController extends Controller
{
    public function newCarenciaReal()
    {
        $eixo_cursos = Eixo_curso::distinct()->get(['curso']);
        $disciplinas = Disciplina::orderBy('nome', 'asc')->get();
        $motivo_vagas = Motivo_vaga::where('tipo', 'Real')->orderBy('motivo', 'asc')->get();
        $areas = Area::orderBy('nome', 'asc')->get();
        $componentes = ComponenteEspecial::orderBy('nome', 'asc')->get();

        return view('carencia.add_carenciaReal', compact(
            'eixo_cursos',
            'disciplinas',
            'motivo_vagas',
            'areas',
            'componentes'
        ));
    }

    public function newCarenciaTemp()
    {
        $eixo_cursos = Eixo_curso::distinct()->get(['curso']);
        $disciplinas = Disciplina::orderBy('nome', 'asc')->get();
        $motivo_vagas = Motivo_vaga::where('tipo', 'Temp')->orderBy('motivo', 'asc')->get();
        $areas = Area::orderBy('nome', 'asc')->get();
        $componentes = ComponenteEspecial::orderBy('nome', 'asc')->get();

        return view('carencia.add_carenciaTemp', compact(
            'eixo_cursos',
            'disciplinas',
            'motivo_vagas',
            'areas',
            'componentes',
        ));
    }

    public function addCarencia(Request $request)
    {

        $actualDate = Carbon::now();
        $formattedDate = $actualDate->format('Y');
        $anoRef = session()->get('ano_ref');

        $carencias = new Carencia;
        $carencias->cod_ue = $request->cod_ue;
        $carencias->uee_id = $request->unidade_id;
        $carencias->nte = $request->nte;
        $carencias->municipio = $request->municipio;
        $carencias->unidade_escolar = $request->unidade_escolar;
        $carencias->cadastro = $request->cadastro;
        $carencias->servidor = $request->servidor;
        $carencias->vinculo = $request->vinculo;
        $carencias->regime = $request->regime;
        $carencias->motivo_vaga = $request->motivo_vaga;
        $carencias->inicio_vaga = $request->inicio_vaga;
        $carencias->fim_vaga = $request->fim_vaga;
        $carencias->matutino = $request->matutino;
        $carencias->vespertino = $request->vespertino;
        $carencias->noturno = $request->noturno;
        $carencias->total = $request->total;
        $carencias->tipo_vaga = $request->tipo_vaga;
        $carencias->tipo_carencia = $request->tipo_carencia;
        $carencias->eixo = $request->eixo;
        $carencias->curso = $request->curso;
        $carencias->usuario = $request->usuario;
        $carencias->hml = 'NÃO';
        $carencias->num_rim = $request->num_rim;
        $carencias->area = $request->area;
        $carencias->ano_ref = $anoRef;
        $carencias->obs_cpg = $request->obs_cpg;
        if ($request->disciplina) {
            $carencias->disciplina = $request->disciplina;
        } else {
            $carencias->disciplina = $request->disciplina_especial;
        }
        if ($carencias->save()) {
            $log = new Log;
            $log->user_id = $request->user_id;
            $log->action = "Inclusion";
            $log->module = "Carência";
            $log->carencia_id = $carencias->id;
            $log->ano_ref = $anoRef;
            $log->save();
        }
    }

    public function searchDeficienciesByTypeAndUnitCode($unitCode, $deficiencyType)
    {
        $anoRef = session()->get('ano_ref');
        $data = Carencia::where('cod_ue', $unitCode)
            ->where('tipo_carencia', $deficiencyType)
            ->where('ano_ref', $anoRef)
            ->where('total', '>', '0')
            ->get();

        return $data->isNotEmpty() ? response()->json($data) : abort(404);
    }

    public function showCarencias($tipo)
    {

        $actualDate = Carbon::now();
        $formattedDate = $actualDate->format('Y-m-d');
        $anoRef = session()->get('ano_ref');

        if ($tipo === "all_carencias") {
            $filteredCarencias = Carencia::with('vagaReserva')->where('total', '>', 0)
                ->where(function ($query) use ($formattedDate) {
                    $query->where('fim_vaga', '>=', $formattedDate)
                        ->orWhereNull('fim_vaga');
                })
                ->where('ano_ref', $anoRef)
                ->where('nte', 30)
                ->orderBy('nte', 'asc')
                ->orderBy('municipio', 'asc')
                ->orderBy('unidade_escolar', 'asc')
                ->get();

            session()->put('carencias', $filteredCarencias);
            $disciplinas = Disciplina::orderBy('nome', 'asc')->get();
            $eixo_cursos = Eixo_curso::distinct()->get(['eixo']);
            $cursos = Eixo_curso::select("curso")->get();
            $motivo_vagas = Motivo_vaga::orderBy('motivo', 'asc')->get();
            $unidadesEscolares = Uee::select('cod_unidade')->get();
            $areas = Area::orderBy('nome', 'asc')->get();

            $carenciasMat = $filteredCarencias->sum('matutino');
            $carenciasVesp = $filteredCarencias->sum('vespertino');
            $carenciasNot = $filteredCarencias->sum('noturno');
            $carenciasTotal = $filteredCarencias->sum('total');

            return view('carencia.show_carencia', [
                'filteredCarencias' => $filteredCarencias,
                'carenciasMat' => $carenciasMat,
                'carenciasVesp' => $carenciasVesp,
                'carenciasNot' => $carenciasNot,
                'carenciasTotal' => $carenciasTotal,
                'disciplinas' => $disciplinas,
                'eixo_cursos' => $eixo_cursos,
                'cursos' => $cursos,
                'motivo_vagas' => $motivo_vagas,
                'unidadesEscolares' => $unidadesEscolares,
                'areas' => $areas,
            ]);
        } else if ($tipo === "filter_carencias") {

            $carencias = session()->get('carencias');
            $novasCarencias = [];

            foreach ($carencias as $carencia) {
                $dbCarencia = DB::table('carencias')->where('id', $carencia->id)->first();

                if ($dbCarencia && $dbCarencia->updated_at != $carencia->updated_at) {
                    // Se a linha existe no banco de dados e a data de atualização é diferente,
                    // consideramos que houve alteração, então adicionamos os novos dados à matriz $novasCarencias
                    $novasCarencias[] = $dbCarencia;
                } elseif (!$dbCarencia) {
                    // Se a linha não existe mais no banco de dados, consideramos que foi excluída,
                    // então não a incluímos na matriz $novasCarencias
                    // Isso é equivalente a verificar se houve exclusão.
                } else {
                    // Mantém os dados antigos na matriz $novasCarencias
                    $novasCarencias[] = $carencia;
                }
            }

            $carencias = $novasCarencias;
            session()->put('carencias', $carencias); // Corrigido 'provimentos' para 'carencias'


            $filteredCarencias  = collect($carencias);

            $disciplinas = Disciplina::orderBy('nome', 'asc')->get();
            $eixo_cursos = Eixo_curso::distinct()->get(['eixo']);
            $cursos = Eixo_curso::select("curso")->get();
            $areas = Area::orderBy('nome', 'asc')->get();
            $carenciasMat = $filteredCarencias->sum('matutino');
            $carenciasVesp = $filteredCarencias->sum('vespertino');
            $carenciasNot = $filteredCarencias->sum('noturno');
            $carenciasTotal = $filteredCarencias->sum('total');
            $motivo_vagas = Motivo_vaga::orderBy('motivo', 'asc')->get();
            $unidadesEscolares = Uee::select('cod_unidade')->get();

            return view('carencia.show_carencia', [
                'filteredCarencias' => $filteredCarencias,
                'carenciasMat' => $carenciasMat,
                'carenciasVesp' => $carenciasVesp,
                'carenciasNot' => $carenciasNot,
                'carenciasTotal' => $carenciasTotal,
                'disciplinas' => $disciplinas,
                'eixo_cursos' => $eixo_cursos,
                'cursos' => $cursos,
                'motivo_vagas' => $motivo_vagas,
                'unidadesEscolares' => $unidadesEscolares,
                'areas' => $areas,
            ]);
        }
    }

    public function searchCursos($curso)
    {

        $data = Eixo_curso::select('eixo')->where('curso', $curso)->get();
        return Response()->json($data);
    }

    public function showCarenciaByForm(Request $request)
    {

        $anoRef = session()->get('ano_ref');
        $actualDate = Carbon::now();
        $formattedDate = $actualDate->format('Y-m-d');

        $carencias = Carencia::query();
        $carenciasByCetegory = Uee::query();
        $disciplinas = Disciplina::query();
        $eixo_cursos = Eixo_curso::query();
        $carenciasMat = 0;
        $carenciasVesp = 0;
        $carenciasNot = 0;
        $carenciasTotal = 0;
        // Verifica se os campos foram preenchidos

        if ($request->filled('nte_seacrh')) {
            $carencias = $carencias->where('nte', $request->nte_seacrh);
        }

        if ($request->filled('search_turno')) {
            if ($request->search_turno === "mat") {
                $carencias = $carencias->where('matutino', '>', '0');
            }
            if ($request->search_turno === "vesp") {
                $carencias = $carencias->where('vespertino', '>', '0');
            }
            if ($request->search_turno === "not") {
                $carencias = $carencias->where('noturno', '>', '0');
            }
        }

        if ($request->filled('search_codigo')) {
            $escolasSelecionadas = $request->input('search_codigo');

            if (is_array($escolasSelecionadas)) {
                $carencias = $carencias->whereIn('cod_ue', $escolasSelecionadas);
            }
        }

        if ($request->filled('search_categoria')) {
            $categoria = $request->search_categoria;
            $uees = $carenciasByCetegory->whereRaw("categorias LIKE '%$categoria%'")->pluck('cod_unidade');
            $carencias = $carencias->whereIn('cod_ue', $uees);
        }

        if ($request->filled('municipio_search')) {
            $carencias = $carencias->where('municipio', $request->municipio_search);
        }

        if ($request->filled('search_motivo')) {
            $carencias = $carencias->where('motivo_vaga', $request->search_motivo);
        }

        if ($request->filled('search_uee')) {
            $carencias = $carencias->where('unidade_escolar', $request->search_uee);
        }

        if ($request->filled('search_disciplina')) {
            $disciplinasSelecionadas = $request->input('search_disciplina');

            if (is_array($disciplinasSelecionadas)) {
                $carencias = $carencias->whereIn('disciplina', $disciplinasSelecionadas);
            }
        }

        if ($request->filled('search_eixo')) {
            $carencias = $carencias->where('eixo', $request->search_eixo);
        }

        if ($request->filled('search_matricula_servidor')) {
            $carencias = $carencias->where('cadastro', $request->search_matricula_servidor);
        }

        if ($request->filled('search_curso')) {
            $carencias = $carencias->where('curso', $request->search_curso);
        }

        if ($request->filled('search_situacao_homologacao')) {
            $carencias = $carencias->where('hml', $request->search_situacao_homologacao);
        }

        if ($request->filled('search_tipo_vaga')) {
            $carencias = $carencias->where('tipo_vaga', $request->search_tipo_vaga);
        }

        if ($request->filled('areas')) {
            $areas = $request->input('areas');
            $carencias = $carencias->whereIn('area', $areas);
        }

        if ($request->filled('area_unit')) {
            $carencias = $carencias->where('area', $request->area_unit);
        }


        if ($request->filled('search_tipo')) {
            $searchOptions = [
                'Temp' => 'Temp',
                'Real' => 'Real'
            ];
            $searchValue = $searchOptions[$request->search_tipo] ?? null;
            if ($searchValue) {
                $carencias = $carencias->where('tipo_carencia', $searchValue);
            }
        }

        if ($request->check === 'on') {

            $filteredCarencias = $carencias
                ->where('total', '>', 0)
                ->where('fim_vaga', '<', $formattedDate)
                ->where('ano_ref', $anoRef)
                ->orderBy('nte', 'asc')
                ->orderBy('municipio', 'asc')
                ->orderBy('unidade_escolar', 'asc')
                ->get();
        } else {

            $filteredCarencias = $carencias
                ->where('total', '>', 0)
                ->where(function ($query) use ($formattedDate) {
                    $query->where('fim_vaga', '>=', $formattedDate)
                        ->orWhereNull('fim_vaga');
                })
                ->where('ano_ref', $anoRef)
                ->orderBy('nte', 'asc')
                ->orderBy('municipio', 'asc')
                ->orderBy('unidade_escolar', 'asc')
                ->orderBy('servidor', 'asc')
                ->get();
        }



        session()->put('carencias', $filteredCarencias);
        $disciplinas = Disciplina::orderBy('nome', 'asc')->get();
        $eixo_cursos = $eixo_cursos->distinct()->get(['eixo']);
        $motivo_vagas = Motivo_vaga::orderBy('motivo', 'asc')->get();
        $areas = Area::orderBy('nome', 'asc')->get();
        $cursos = Eixo_curso::select("curso")->get();
        $carenciasMat = $filteredCarencias->sum('matutino');
        $carenciasVesp = $filteredCarencias->sum('vespertino');
        $carenciasNot = $filteredCarencias->sum('noturno');
        $carenciasTotal = $filteredCarencias->sum('total');
        $unidadesEscolares = Uee::select('cod_unidade')->get();

        return view('carencia.show_carencia', [
            'filteredCarencias' => $filteredCarencias,
            'carenciasMat' => $carenciasMat,
            'carenciasVesp' => $carenciasVesp,
            'carenciasNot' => $carenciasNot,
            'carenciasTotal' => $carenciasTotal,
            'disciplinas' => $disciplinas,
            'eixo_cursos' => $eixo_cursos,
            'cursos' => $cursos,
            'motivo_vagas' => $motivo_vagas,
            'unidadesEscolares' => $unidadesEscolares,
            'areas' => $areas,
        ]);
    }

    public function showCarenciaDetalhada(Carencia $carencia)
    {

        $detailUeeHomologacao = Uee::select('situacao')->where('cod_unidade', $carencia->cod_ue)->first();
        $detailProvimentos = Provimento::where('id_carencia', $carencia->id)->get();
        $disciplinas = Disciplina::orderBy('nome', 'asc')->get();
        $motivo_vagaReal = Motivo_vaga::where('tipo', 'Real')->get();
        $motivo_vagaTemp = Motivo_vaga::where('tipo', 'Temp')->get();
        $eixo_cursos = Eixo_curso::distinct()->get(['curso']);
        $forma_suprimentos = Forma_suprimento::all();

        $vaga_reserva = VagaReserva::with(['servidor', 'carencia'])->where("carencia_id", $carencia->id)->get();

        return view('carencia.detail_carencia', compact('forma_suprimentos', 'vaga_reserva', 'carencia', 'detailUeeHomologacao', 'detailProvimentos', 'motivo_vagaReal', 'disciplinas', 'motivo_vagaTemp', 'eixo_cursos'));
    }

    public function destroy(Carencia $carencia)
    {
        // Verifica se existe algum registro em "provimentos" com o id_carencia igual ao ID da carência a ser excluída
        $provimento = Provimento::where('id_carencia', $carencia->id)->first();

        if ($provimento) {

            return redirect('/carencias/filter_carencias')->with('msg', 'error');
        }

        // Caso não existam provimentos associados, você pode prosseguir com a exclusão da carência.
        $carencia->delete();



        return redirect('/carencias/filter_carencias')->with('msg', 'Carência Excluída com sucesso!');
    }

    public function printoutTable()
    {

        $carencias = session()->get('carencias')
            ->groupBy('nte')
            ->sortBy(function ($group) {
                return (int) $group->first()->nte;
            });

        $carenciasMat = $carencias->sum('matutino');
        $carenciasVesp = $carencias->sum('vespertino');
        $carenciasNot = $carencias->sum('noturno');
        $carenciasTotal = $carencias->sum('total');

        return view('relatorios.relatorio_carencia', compact('carencias', 'carenciasMat', 'carenciasVesp', 'carenciasNot', 'carenciasTotal'));
    }

    public function generateExcel()
    {

        $carencias = session()->get('carencias');
        $carenciasMat = $carencias->sum('matutino');
        $carenciasVesp = $carencias->sum('vespertino');
        $carenciasNot = $carencias->sum('noturno');
        $carenciasTotal = $carencias->sum('total');
        return view('excel.excel_carencia', compact('carencias', 'carenciasMat', 'carenciasVesp', 'carenciasNot', 'carenciasTotal'));
    }

    public function update(Request $request)
    {
        $anoRef = session()->get('ano_ref');
        // Encontra o registro ou falha
        $carencia = Carencia::findOrFail($request->id);

        // Armazena o registro antigo
        $oldRecord = $carencia->toJson();

        // Exclui os campos 'user_id' e 'carencia_id' do request data
        $data = $request->except(['user_id', 'carencia_id']);

        // Atualiza o registro com os dados restantes
        if ($carencia->update($data)) {
            // Armazena o novo registro
            $newRecord = $carencia->fresh()->toJson();

            // Cria um novo log
            $log = new Log;
            $log->user_id = $request->user_id;
            $log->action = "Update";
            $log->module = "Carência";
            $log->carencia_id = $request->id;
            $log->old_record = $oldRecord;
            $log->new_record = $newRecord;
            $log->ano_ref = $anoRef;
            $log->save();

            return redirect()->to(url()->previous())->with('msg', 'Registros Alterados com Sucesso!');
        }

        return redirect()->to(url()->previous())->with('msg', 'Falha ao alterar os registros!');
    }

    public function motivo_vagas()
    {

        $motivos = Motivo_vaga::all();

        return view('listas_suspensas.show_motivo_vagas', [
            'motivos' => $motivos,
        ]);
    }

    public function createMotivoDeVagas(Request $request)
    {

        $motivo = new Motivo_vaga;
        $motivo->motivo = $request->motivo;
        $motivo->tipo = $request->tipo;

        if ($motivo->save()) {
            return  redirect()->to(url()->previous())->with('msg', 'Motivo adicionado com Sucesso!');
        }
    }

    public function destroyMotivo($id)
    {

        $motivo = Motivo_vaga::where('id', $id)->first();
        $verifyCarenciaByMotivo = Carencia::where('motivo_vaga', $motivo->motivo)->exists();

        if ($verifyCarenciaByMotivo) {
            return back()->with('msg', 'error');
        } else {
            Motivo_vaga::findOrFail($id)->delete();
            return back()->with('msg', 'success');
        }
    }
}
