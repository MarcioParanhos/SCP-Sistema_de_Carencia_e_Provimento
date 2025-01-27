<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aposentadoria;
use App\Models\Forma_suprimento;
use App\Models\Servidores_completo;

class AposentadoriasController extends Controller
{
    public function show()
    {
        $totalProcess = Aposentadoria::count();
        $ntes = Servidores_completo::all()->pluck('nte')->unique()->sort();
        $municipios = Servidores_completo::pluck('municipio')->unique()->sort();
        $totalCarencia = Aposentadoria::where('carencia', 'Sim')->count();
        $desistencia = Aposentadoria::where('conclusao', 'DESISTÊNCIA')->count();
        $publicacao = Aposentadoria::where('conclusao', 'PUBLICAÇÃO')->count();
        $totalNaoCarencia = Aposentadoria::where('carencia', 'Não')->count();
        $processosFinalizadosConclusao = Aposentadoria::where('conclusao', '!=', null)->count();
        $processosFinalizadosSemCarencia = Aposentadoria::where('carencia', 'Não')->count();
        $totalProcessPendenteAnaliseCpg = Aposentadoria::where('carencia', null)->where('conclusao', null)->count();
        $totalProcessPendenteAnaliseCpm = Aposentadoria::where('carencia', "Sim")->where('forma_suprimento', null)->count();
        $processos = Aposentadoria::orderBy('nte', 'asc')->orderBy('municipio', 'asc')->get();

        session()->put('processos', $processos);

        return view('aposentadoria.view_aposentadorias', [
            'processos' => $processos,
            'totalProcess' => $totalProcess,
            'totalProcessPendenteAnaliseCpg' => $totalProcessPendenteAnaliseCpg,
            'totalProcessPendenteAnaliseCpm' => $totalProcessPendenteAnaliseCpm,
            'totalCarencia' => $totalCarencia,
            'totalNaoCarencia' => $totalNaoCarencia,
            'processosFinalizadosConclusao' => $processosFinalizadosConclusao,
            'processosFinalizadosSemCarencia' => $processosFinalizadosSemCarencia,
            'desistencia' => $desistencia,
            'publicacao' => $publicacao,
            'ntes' => $ntes,
            'municipios' => $municipios,
        ]);
    }

    public function create(Request $request)
    {

        $verify_aposentadoria = Aposentadoria::where('num_process', $request->num_process)->first();

        if ($verify_aposentadoria) {
            return  redirect()->to(url()->previous())->with('msg', 'error_duplicated');
        } else {
            $aposentadoria = new Aposentadoria;
            $aposentadoria->num_process = $request->num_process;
            $aposentadoria->nte = $request->nte;
            $aposentadoria->municipio = $request->municipio;
            $aposentadoria->situacao_processo = $request->process_situation;
            $aposentadoria->matricula = $request->cadastro;
            if($request->servidor === null) {
                return  redirect()->to(url()->previous())->with('msg', 'error_server_null');
            } else {
                $aposentadoria->servidor = $request->servidor;
            }
            
            $aposentadoria->vinculo = $request->vinculo;
            $aposentadoria->regime = $request->regime;
            $aposentadoria->user_create = $request->usuario;
            $aposentadoria->unidade_escolar = $request->unidade_escolar;
            $aposentadoria->cod_unidade = $request->cod_unidade;
            $aposentadoria->cod_unidade = $request->cod_unidade;
            $aposentadoria->unidade_complementar = $request->unidade_complementar;
            $aposentadoria->cod_unidade_complementar = $request->cod_unidade_complementar;
            $aposentadoria->save();
            return  redirect()->back()->with('msg', 'success');
        }
    }

    public function view($id)
    {
        $forma_suprimentos = Forma_suprimento::all();
        $aposentadoria = Aposentadoria::where('id', $id)->first();
        return view('aposentadoria.detail_aposentadoria', [
            'aposentadoria' => $aposentadoria,
            'forma_suprimentos' => $forma_suprimentos
        ]);
    }

    public function update(Request $request)
    {

        $dataToUpdate = $request->except('process_id', 'local_carencia');
        $localCarencia = $request->local_carencia;

        if ($request->conclusao == 'DESISTENCIA') {
            $dataToUpdate['carencia_lot'] = null;
            $dataToUpdate['carencia_comp'] = null;
            $dataToUpdate['forma_suprimento'] = null;
            $dataToUpdate['carencia'] = null;
        } else {
            if ($request->carencia == 'Não') {
                $dataToUpdate['carencia_lot'] = null;
                $dataToUpdate['carencia_comp'] = null;
                $dataToUpdate['forma_suprimento'] = null;
            }

            if ($localCarencia == 'Ambos') {
                $dataToUpdate['carencia_lot'] = "Sim";
                $dataToUpdate['carencia_comp'] = "Sim";
            } else if ($localCarencia == 'Lotacao') {
                $dataToUpdate['carencia_lot'] = "Sim";
                $dataToUpdate['carencia_comp'] = null;
            } else if ($localCarencia == 'Complementacao') {
                $dataToUpdate['carencia_lot'] = null;
                $dataToUpdate['carencia_comp'] = "Sim";
            }
        }

        Aposentadoria::findOrFail($request->process_id)->update($dataToUpdate);

        return  redirect()->back()->with('msg', 'success');
    }

    public function select($id)
    {
        $aposentadoria = Aposentadoria::where('id', $id)->first();

        return response()->json([
            'aposentadoria' => $aposentadoria
        ]);
    }

    public function searchServidor($matricula)
    {

        $data = Servidores_completo::where('matricula', $matricula)->first();

        if ($data) {
            return response()->json([
                'data' => $data
            ]);
        }
    }

    public function filter(Request $request)
    {

        $aposentadorias = Aposentadoria::query();
        $totalProcess = Aposentadoria::count();
        $ntes = Servidores_completo::all()->pluck('nte')->unique()->sort();
        $municipios = Servidores_completo::pluck('municipio')->unique()->sort();
        $totalCarencia = Aposentadoria::where('carencia', 'Sim')->count();
        $desistencia = Aposentadoria::where('conclusao', 'DESISTÊNCIA')->count();
        $publicacao = Aposentadoria::where('conclusao', 'PUBLICAÇÃO')->count();
        $totalNaoCarencia = Aposentadoria::where('carencia', 'Não')->count();
        $processosFinalizadosConclusao = Aposentadoria::where('conclusao', '!=', null)->count();
        $processosFinalizadosSemCarencia = Aposentadoria::where('carencia', 'Não')->count();
        $totalProcessPendenteAnaliseCpg = Aposentadoria::where('carencia', null)->where('conclusao', null)->count();
        $totalProcessPendenteAnaliseCpm = Aposentadoria::where('carencia', "Sim")->where('forma_suprimento', null)->count();

        if ($request->filled('nte_search')) {
            $aposentadorias = $aposentadorias->where('nte', $request->nte_search);
        }
        if ($request->filled('municipio_search')) {
            $aposentadorias = $aposentadorias->where('municipio', $request->municipio_search);
        }
        if ($request->filled('cod_search')) {
            $aposentadorias = $aposentadorias->where('cod_unidade', $request->cod_search);
        }
        if ($request->filled('tipo_processo_search')) {
            $aposentadorias = $aposentadorias->where('situacao_processo', $request->tipo_processo_search);
        }
        if ($request->filled('matricula_search')) {
            $aposentadorias = $aposentadorias->where('matricula', $request->matricula_search);
        }
        if ($request->filled('conclusao_search')) {
            $aposentadorias = $aposentadorias->where('conclusao', $request->conclusao_search);
        }
        if ($request->filled('carencia_search')) {
            $aposentadorias = $aposentadorias->where('carencia', $request->carencia_search);
        }
        if ($request->filled('carencia_pendente_cpg')) {
            $aposentadorias = $aposentadorias->where('carencia', null)->where('conclusao', null);
        }
        if ($request->filled('local_carencia_search')) {
            if ($request->local_carencia_search == "Lotação") {
                $aposentadorias = $aposentadorias->where('carencia_lot', 'Sim');
            } else {
                $aposentadorias = $aposentadorias->where('carencia_comp', 'Sim');
            }
        }
        if ($request->filled('pendencia_cpm')) {
            $aposentadorias = $aposentadorias->where('forma_suprimento', null)->where('carencia', 'sim')->where('conclusao', null);
        }
        if ($request->filled('forma_suprimento_search')) {
            $aposentadorias = $aposentadorias->where('forma_suprimento', $request->forma_suprimento_search);
        }
        if ($request->filled('num_process_search')) {
            $aposentadorias = $aposentadorias->where('num_process', $request->num_process_search);
        }

        $processos = $aposentadorias->orderBy('nte', 'asc')->get();

        session()->put('processos', $processos);

        return view('aposentadoria.view_aposentadorias', [
            'processos' => $processos,
            'totalProcess' => $totalProcess,
            'totalProcessPendenteAnaliseCpg' => $totalProcessPendenteAnaliseCpg,
            'totalProcessPendenteAnaliseCpm' => $totalProcessPendenteAnaliseCpm,
            'totalCarencia' => $totalCarencia,
            'totalNaoCarencia' => $totalNaoCarencia,
            'processosFinalizadosConclusao' => $processosFinalizadosConclusao,
            'processosFinalizadosSemCarencia' => $processosFinalizadosSemCarencia,
            'desistencia' => $desistencia,
            'publicacao' => $publicacao,
            'ntes' => $ntes,
            'municipios' => $municipios,
        ]);
    }

    public function print($id)
    {

        $aposentadoria = Aposentadoria::where('id', $id)->first();

        return view('relatorios.relatorio_aposentadoria', [
            'aposentadoria' => $aposentadoria,
        ]);
    }

    public function destroy($id)
    {

        Aposentadoria::findOrFail($id)->delete();
        return  redirect()->back()->with('msg', 'success_destroy');
    }

    public function excelAposentadorias () {

        $processos = session()->get('processos');
        return view('excel.excel_aposentadorias', compact('processos'));

    }
}