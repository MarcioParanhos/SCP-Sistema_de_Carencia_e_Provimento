<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Uee;
use App\Models\Unidade_organizacional;
use App\Models\Carencia;
use App\Models\Provimento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UeeController extends Controller
{
    public function searchUnidade($cod_unidade)
    {
        $data = Uee::where('cod_unidade', $cod_unidade)->orderBy('unidade_escolar', 'asc')->first();

        if ($data) {
            $data->uo_cod = Unidade_organizacional::select('uo')->where('cod_sec', $cod_unidade)->get();
            return response()->json([$data]);
        } else {
            return response()->json(["error" => "Unidade não encontrada"], 404);
        }
    }

    public function searchMunicipio($searchMunicipio)
    {

        $data = Uee::select('municipio')->where('nte', $searchMunicipio)->orderBy('municipio', 'asc')->distinct()->get();
        return Response()->json($data);
    }

    public function searchUees($search_municipio)
    {

        $data = Uee::select('unidade_escolar')->where('municipio', $search_municipio)->distinct()->get();
        return Response()->json($data);
    }

    public function showUees($tipo)
    {
        if ($tipo === "filter_uees") {

            $uees = session()->get('uees');

            $updateUees = [];

            foreach ($uees as $uee) {

                $dbUee = DB::table('uees')->where('id', $uee->id)->first();

                if ($dbUee && $dbUee->updated_at != $uee->updated_at) {

                    $updateUees[] = $dbUee;
                } else {

                    // Mantém os dados antigos na matriz $updateUees
                    $updateUees[] = $uee;
                }
            }

            $uees = $updateUees;
            session()->put('uees', $uees);

            $totalUnits = DB::table('uees')->where('situacao', 'HOMOLOGADA')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnits2Pch = DB::table('uees')->where('programming_stage', 2)->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnits3Pch = DB::table('uees')->where('programming_stage', 3)->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnits4Pch = DB::table('uees')->where('programming_stage', 4)->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnitsAnexos = DB::table('uees')->where('tipo', 'ANEXO')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnitsSedes = DB::table('uees')->where('tipo', 'SEDE')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnitsCemits = DB::table('uees')->where('tipo', 'CEMIT')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalStartDigitationSede = DB::table('uees')->where('tipo', 'SEDE')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->where('typing_started', 'SIM')->count();
            $totalStartDigitationAnexo = DB::table('uees')->where('tipo', 'ANEXO')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->where('typing_started', 'SIM')->count();
            $totalStartDigitationCemits = DB::table('uees')->where('tipo', 'CEMIT')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->where('typing_started', 'SIM')->count();

            Session::put('previous_url', 'https://scp.educacao.ba.gov.br/uees/filter_uees');

            return view('uees.show_uees', compact(
                'uees',
                'totalUnits',
                'totalUnits2Pch',
                'totalUnits3Pch',
                'totalUnits4Pch',
                'totalUnitsAnexos',
                'totalUnitsSedes',
                'totalUnitsCemits',
                'totalStartDigitationSede',
                'totalStartDigitationAnexo',
                'totalStartDigitationCemits'
            ));
        } else if ($tipo === "all_uees") {

            $totalUnits = DB::table('uees')->where('situacao', 'HOMOLOGADA')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnits2Pch = DB::table('uees')->where('programming_stage', 2)->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnits3Pch = DB::table('uees')->where('programming_stage', 3)->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnits4Pch = DB::table('uees')->where('programming_stage', 4)->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnitsAnexos = DB::table('uees')->where('tipo', 'ANEXO')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnitsSedes = DB::table('uees')->where('tipo', 'SEDE')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalUnitsCemits = DB::table('uees')->where('tipo', 'CEMIT')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->count();
            $totalStartDigitationSede = DB::table('uees')->where('tipo', 'SEDE')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->where('typing_started', 'SIM')->count();
            $totalStartDigitationAnexo = DB::table('uees')->where('tipo', 'ANEXO')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->where('typing_started', 'SIM')->count();
            $totalStartDigitationCemits = DB::table('uees')->where('tipo', 'CEMIT')->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })->where('typing_started', 'SIM')->count();

            $uees = Uee::orderBy('nte', 'asc')
                ->select('id', 'nte', 'municipio', 'unidade_escolar', 'cod_unidade', 'tipo', 'programming_stage', 'situacao', 'categorias', 'carencia')
                ->where(function ($query) {
                    $query->where('desativation_situation', 'Ativa')
                        ->orWhereNull('desativation_situation');
                })
                ->get();

            session()->put('uees', $uees);
            Session::put('previous_url', url()->current());

            return view('uees.show_uees', compact(
                'uees',
                'totalUnits',
                'totalUnits2Pch',
                'totalUnits3Pch',
                'totalUnits4Pch',
                'totalUnitsAnexos',
                'totalUnitsSedes',
                'totalUnitsCemits',
                'totalStartDigitationSede',
                'totalStartDigitationAnexo',
                'totalStartDigitationCemits'
            ));
        }
    }

    public function homologarUnidade($new_cod, $situacao)
    {
        if ($situacao === "HOMOLOGADA") {
            try {

                $anoRef = session()->get('ano_ref');

                $carencia = Carencia::where('cod_ue', $new_cod)->where('ano_ref', $anoRef)->where('total', '>', '0')->exists();
                $provimento_em_tramite = Provimento::where('cod_unidade', $new_cod)->where('situacao_provimento', 'tramite')->where('ano_ref', $anoRef)->where('total', '>', '0')->exists();


                $uee = Uee::where('cod_unidade', $new_cod)->firstOrFail();
                $uee->update([
                    'situacao' => 'HOMOLOGADA',
                ]);

                if (($carencia) || ($provimento_em_tramite)) {

                    $uee->update([
                        'carencia' => 'SIM',
                    ]);
                } else {
                    $uee->update([
                        'carencia' => 'NÃO',
                    ]);
                }

                Carencia::where('cod_ue', $new_cod)->update([
                    'hml' => 'SIM',
                ]);

                return response()->json([
                    'message' => 'Unidade homologada com sucesso',
                    'id' => $uee->id,
                ], 200);
            } catch (\Exception $e) {
                dd($e->getMessage());
                return response()->json([
                    'message' => 'Ocorreu um erro ao homologar a unidade',
                ], 500);
            }
        } else if ($situacao === "NAO_HOMOLOGADA") {
            try {
                $uee = Uee::where('cod_unidade', $new_cod)->firstOrFail();
                $uee->update([
                    'situacao' => 'PENDENTE',
                ]);

                Carencia::where('cod_ue', $new_cod)->update([
                    'hml' => 'NÃO',
                ]);

                return response()->json([
                    'message' => 'Unidade homologada com sucesso',
                    'id' => $uee->id,
                ], 200);
            } catch (\Exception $e) {
                dd($e->getMessage());
                return response()->json([
                    'message' => 'Ocorreu um erro ao homologar a unidade',
                ], 500);
            }
        }
    }

    public function showUeesByForm(Request $request)
    {

        session()->put('ueesCategorias', "");
        $uees = Uee::query();

        if ($request->filled('nte_seacrh')) {
            $uees = $uees->where('nte', $request->nte_seacrh);
        }

        if ($request->filled('search_categoria')) {
            $categoria = $request->search_categoria;
            $uees = $uees->whereRaw("categorias LIKE '%$categoria%'");
            $uees->each(function ($uee) use ($request) {
                $uee->categorias = $request->search_categoria;
            });

            session()->put('ueesCategorias', $request->search_categoria);
        }

        if ($request->filled('municipio_search')) {
            $uees = $uees->where('municipio', $request->municipio_search);
        }

        if ($request->filled('search_uee')) {
            $uees = $uees->where('unidade_escolar', $request->search_uee);
        }

        if ($request->filled('search_carencia')) {
            $uees = $uees->where('carencia', $request->search_carencia);
        }

        if ($request->filled('search_codigo_unidade_escolar')) {
            $uees = $uees->where('cod_unidade', $request->search_codigo_unidade_escolar);
        }

        if ($request->filled('search_situacao_homologacao')) {
            $uees = $uees->where('situacao', $request->search_situacao_homologacao);
        }

        if ($request->filled('search_etapa_pch')) {
            if ($request->search_etapa_pch == 0) {
                $uees = $uees->where(function ($query) {
                    $query->where('programming_stage', 0)
                        ->orWhereNull('programming_stage');
                });
            } else {
                $uees = $uees->where('programming_stage', $request->search_etapa_pch);
            }
        }

        if ($request->filled('search_tipologia')) {
            $tipologiasSelecionadas = $request->input('search_tipologia');

            if (is_array($tipologiasSelecionadas)) {
                $uees = $uees->whereIn('tipo', $tipologiasSelecionadas);
            }
        }

        $totalUnits = DB::table('uees')->where('situacao', 'HOMOLOGADA')->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->count();
        $totalUnits2Pch = DB::table('uees')->where('programming_stage', 2)->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->count();
        $totalUnits3Pch = DB::table('uees')->where('programming_stage', 3)->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->count();
        $totalUnits4Pch = DB::table('uees')->where('programming_stage', 4)->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->count();
        $totalUnitsAnexos = DB::table('uees')->where('tipo', 'ANEXO')->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->count();
        $totalUnitsSedes = DB::table('uees')->where('tipo', 'SEDE')->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->count();
        $totalUnitsCemits = DB::table('uees')->where('tipo', 'CEMIT')->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->count();
        $uees = $uees->orderBy('nte', 'asc')->orderBy('municipio', 'asc')->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->get();
        $totalStartDigitationSede = DB::table('uees')->where('tipo', 'SEDE')->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->where('typing_started', 'SIM')->count();
        $totalStartDigitationAnexo = DB::table('uees')->where('tipo', 'ANEXO')->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->where('typing_started', 'SIM')->count();
        $totalStartDigitationCemits = DB::table('uees')->where('tipo', 'CEMIT')->where(function ($query) {
            $query->where('desativation_situation', 'Ativa')
                ->orWhereNull('desativation_situation');
        })->where('typing_started', 'SIM')->count();

        session()->put('uees', $uees);
        Session::put('previous_url', 'https://scp.educacao.ba.gov.br/uees/filter_uees');

        return view('uees.show_uees', [
            'uees' => $uees,
            'totalUnits' => $totalUnits,
            'totalUnits2Pch' => $totalUnits2Pch,
            'totalUnits3Pch' => $totalUnits3Pch,
            'totalUnits4Pch' => $totalUnits4Pch,
            'totalUnitsAnexos' => $totalUnitsAnexos,
            'totalUnitsSedes' => $totalUnitsSedes,
            'totalUnitsCemits' => $totalUnitsCemits,
            'totalStartDigitationSede' => $totalStartDigitationSede,
            'totalStartDigitationAnexo' => $totalStartDigitationAnexo,
            'totalStartDigitationCemits' => $totalStartDigitationCemits
        ]);
    }

    public function gerarExcel()
    {
        $uees = session()->get('uees');
        $ueesCateroriasForExcel = session()->get('ueesCategorias');

        return view('excel.excel_uees', compact('uees', 'ueesCateroriasForExcel'));
    }

    public function detailUee($id)
    {

        $uee = Uee::where('id', $id)->first();


        return view('uees.detail_uee', compact('uee'));
    }

    public function update(Request $request)
    {


        if ($request->filled('typing_started')) {

            if ($request->typing_started == "SIM") {
                $uee = Uee::findOrFail($request->id);
                $uee->typing_started = "SIM";
                $uee->description_typing_started = null;
                $uee->save();
            } else if ($request->typing_started == "NÃO") {
                $uee = Uee::findOrFail($request->id);
                $uee->typing_started = "NÃO";
                $uee->finished_typing = "";
                $uee->description_typing_started = $request->description_typing_started;
                $uee->finished_typing_description = null;
                $uee->save();
            }

            if ($request->finished_typing == "SIM") {
                $uee->finished_typing_description = null;
                $uee->save();
            }
        } else if (!$request->filled('typing_started')) {
            $uee = Uee::findOrFail($request->id);
            $uee->typing_started = null;
            $uee->finished_typing = null;
            $uee->description_typing_started = null;
            $uee->finished_typing_description = null;
            $uee->save();
        }

        if ($request->filled('categorias')) {
            $categoriasSelecionadas = $request->input('categorias');

            if (is_array($categoriasSelecionadas)) {
                $uee = Uee::findOrFail($request->id);
                $uee->categorias = json_encode($categoriasSelecionadas);
                $uee->save();
            }
        } else {

            $uee = Uee::findOrFail($request->id);
            $uee->categorias = null;
            $uee->save();
        }

        if (($request->check_2_pch === "on") && ($request->check_3_pch === null) && ($request->check_4_pch === null)) {

            $uee = Uee::findOrFail($request->id);
            $uee->fill($request->except('check_2_pch'));
            $uee->programming_stage = 2;
            $uee->save();
        } else if (($request->check_2_pch === "on") && ($request->check_3_pch === "on") && ($request->check_4_pch === null)) {

            $uee = Uee::findOrFail($request->id);
            $uee->fill($request->except('check_2_pch', 'check_3_pch'));
            $uee->programming_stage = 3;
            $uee->save();
        } else if (($request->check_2_pch === "on") && ($request->check_3_pch === "on") && ($request->check_4_pch === "on")) {

            $uee = Uee::findOrFail($request->id);
            $uee->fill($request->except('check_2_pch', 'check_3_pch', 'check_4_pch'));
            $uee->programming_stage = 4;
            $uee->save();
        } else if (($request->check_2_pch === null) && ($request->check_3_pch === "on") && ($request->check_4_pch === null)) {

            $uee = Uee::findOrFail($request->id);
            $uee->fill($request->except('check_2_pch', 'check_3_pch'));
            $uee->programming_stage = 3;
            $uee->save();
        } else if (($request->check_2_pch === null) && ($request->check_3_pch === null) && ($request->check_4_pch === "on")) {

            $uee = Uee::findOrFail($request->id);
            $uee->fill($request->except('check_2_pch', 'check_3_pch', 'check_4_pch'));
            $uee->programming_stage = 4;
            $uee->save();

        } else {
            
            $uee = Uee::findOrFail($request->id);
            $uee->fill($request->except('check_2_pch'));
            $uee->programming_stage = 0;
        }

        // Recupera todas as carências e provimentos que correspondem ao cod_ue fornecido
        $carenciaParaAtualizarUnidadeEscolar = Carencia::where('cod_ue', $request->cod_unidade)->get();
        $provimentoParaAtualizarUnidadeEscolar = Provimento::where('cod_unidade', $request->cod_unidade)->get();

        // Itera sobre cada carência e atualiza o campo unidade_escolar
        foreach ($carenciaParaAtualizarUnidadeEscolar as $carencia) {
            
            $carencia->unidade_escolar = $request->unidade_escolar;
            $carencia->save();  // Salva as alterações
        }
        // Itera sobre cada Provimento e atualiza o campo unidade_escolar
        foreach ($provimentoParaAtualizarUnidadeEscolar as $provimento) {
            $provimento->unidade_escolar = $request->unidade_escolar;
            $provimento->save();  // Salva as alterações
        }

        return  redirect()->to(url()->previous())->with('msg', 'Registros Alterados com Sucesso!');
    }

    public function destroy($id)
    {
        Uee::findOrFail($id)->delete();
        return redirect('/uees/filter_uees')->with('msg', 'Registro excluído com sucesso!');
    }

    public function statusDigitacao()
    {
        $uees = Uee::orderBy('nte', 'asc')
            ->select(
                'id',
                'nte',
                'municipio',
                'unidade_escolar',
                'cod_unidade',
                'typing_started',
                'finished_typing',
                'tipo',
                'description_typing_started',
                'finished_typing_description',
                'situacao'
            )
            ->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })
            ->where('tipo', '!=', 'NTE')
            ->get();



        $nteCountsSedes = $this->getNteCountsByType('SEDE');
        $nteCountsAnexos = $this->getNteCountsByType('ANEXO');
        $nteCountsCemits = $this->getNteCountsByType('CEMIT');

        $nteCountsStartsDigitationSedes = $this->getNteStartDigitationCountsByType('SEDE');
        $nteCountsStartsDigitationAnexos = $this->getNteStartDigitationCountsByType('ANEXO');
        $nteCountsStartsDigitationCemits = $this->getNteStartDigitationCountsByType('CEMIT');

        $nteCountsFinishedDigitationSedes = $this->getNteFinishedDigitationCountsByType('SEDE');
        $nteCountsFinishedDigitationAnexos = $this->getNteFinishedDigitationCountsByType('ANEXO');
        $nteCountsFinishedDigitationCemits = $this->getNteFinishedDigitationCountsByType('CEMIT');


        $nteCountsTotal = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotal[$i] = $nteCountsSedes[$i] + $nteCountsAnexos[$i] + $nteCountsCemits[$i];
        }

        $nteCountsTotalStartsDigitation = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotalStartsDigitation[$i] = $nteCountsStartsDigitationSedes[$i] + $nteCountsStartsDigitationAnexos[$i] + $nteCountsStartsDigitationCemits[$i];
        }

        $nteCountsTotalFinishedDigitation = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotalFinishedDigitation[$i] = $nteCountsFinishedDigitationSedes[$i] + $nteCountsFinishedDigitationAnexos[$i] + $nteCountsFinishedDigitationCemits[$i];
        }

        Session::put('previous_url', url()->current());
        Session::put('uees', $uees);

        return view('uees.status_uees', [
            'uees' => $uees,
            'nteCountsSedes' => $nteCountsSedes,
            'nteCountsAnexos' => $nteCountsAnexos,
            'nteCountsCemits' => $nteCountsCemits,
            'nteCountsStartsDigitationSedes' => $nteCountsStartsDigitationSedes,
            'nteCountsStartsDigitationAnexos' => $nteCountsStartsDigitationAnexos,
            'nteCountsStartsDigitationCemits' => $nteCountsStartsDigitationCemits,
            'nteCountsFinishedDigitationSedes' => $nteCountsFinishedDigitationSedes,
            'nteCountsFinishedDigitationAnexos' => $nteCountsFinishedDigitationAnexos,
            'nteCountsFinishedDigitationCemits' => $nteCountsFinishedDigitationCemits,
            'nteCountsTotal' => $nteCountsTotal,
            'nteCountsTotalStartsDigitation' => $nteCountsTotalStartsDigitation,
            'nteCountsTotalFinishedDigitation' => $nteCountsTotalFinishedDigitation,
        ]);
    }

    private function getNteCountsByType($type)
    {
        $nteCounts = array_fill(1, 27, 0);

        $ueesCounts = Uee::orderBy('nte', 'asc')
            ->select('nte', 'tipo')
            ->where('tipo', $type)
            ->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })
            ->get();


        foreach ($ueesCounts as $ueesCount) {
            $nteCounts[$ueesCount->nte]++;
        }

        return $nteCounts;
    }

    private function getNteStartDigitationCountsByType($type)
    {
        $nteCounts = array_fill(1, 27, 0);

        $ueesCounts = Uee::orderBy('nte', 'asc')
            ->select('nte', 'tipo')
            ->where('tipo', $type)
            ->where('typing_started', 'SIM')
            ->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })
            ->get();


        foreach ($ueesCounts as $ueesCount) {
            $nteCounts[$ueesCount->nte]++;
        }

        return $nteCounts;
    }

    private function getNteFinishedDigitationCountsByType($type)
    {
        $nteCounts = array_fill(1, 27, 0);

        $ueesCounts = Uee::orderBy('nte', 'asc')
            ->select('nte', 'tipo')
            ->where('tipo', $type)
            ->where('finished_typing', 'SIM')
            ->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })
            ->get();


        foreach ($ueesCounts as $ueesCount) {
            $nteCounts[$ueesCount->nte]++;
        }

        return $nteCounts;
    }

    public function createDesativationUee(Request $request)
    {

        $uee = Uee::findOrFail($request->uee_id);
        $uee->desativation_status_date = $request->desativation_date;

        if ($request->desativation_reason != "reativacao") {
            $uee->desativation_reason = $request->desativation_reason;
            $uee->desativation_situation = "Desativada";
        } else {
            $uee->desativation_reason = null;
            $uee->desativation_situation = "Ativa";
        }

        $uee->save();

        return  redirect()->to(url()->previous())->with('msg', 'Registros Alterados com Sucesso!');
    }

    public function buscarUnidadesPorInicioDeDigitacao(Request $request)
    {

        $uees = Uee::query();

        if ($request->filled('nte_seacrh')) {
            $uees = $uees->where('nte', $request->nte_seacrh);
        }

        if ($request->filled('search_tipologia')) {
            $uees = $uees->where('tipo', $request->search_tipologia);
        }

        if ($request->filled('situacao_homologacao')) {
            $uees = $uees->where('situacao', $request->situacao_homologacao);
        }

        if ($request->filled('municipio_search')) {
            $uees = $uees->where('municipio', $request->municipio_search);
        }

        if ($request->filled('search_codigo_unidade_escolar')) {
            $uees = $uees->where('cod_unidade', $request->search_codigo_unidade_escolar);
        }

        if ($request->filled('search_uee')) {
            $uees = $uees->where('unidade_escolar', $request->search_uee);
        }

        if ($request->filled('started_digitation')) {

            if ($request->started_digitation === "SIM") {
                $uees = $uees->where('typing_started', $request->started_digitation);
            } else if ($request->started_digitation === "NAO") {
                $uees = $uees->where(function ($query) {
                    $query->whereNull('typing_started')
                        ->orWhere('typing_started', 'NAO');
                });
            }
        }

        if ($request->filled('finish_digitation')) {

            if ($request->finish_digitation === "SIM") {
                $uees = $uees->where('finished_typing', $request->finish_digitation);
            } else if ($request->finish_digitation === "NAO") {
                $uees = $uees->where(function ($query) {
                    $query->whereNull('finished_typing')
                        ->orWhere('finished_typing', 'NAO');
                });
            }
        }

        $uees = $uees->orderBy('nte', 'asc')
            ->select(
                'id',
                'nte',
                'municipio',
                'unidade_escolar',
                'cod_unidade',
                'typing_started',
                'finished_typing',
                'tipo',
                'description_typing_started',
                'finished_typing_description',
                'obs_cpg',
                'situacao'
            )
            ->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })
            ->where('tipo', '!=', 'NTE')
            ->get();

        $nteCountsSedes = $this->getNteCountsByType('SEDE');
        $nteCountsAnexos = $this->getNteCountsByType('ANEXO');
        $nteCountsCemits = $this->getNteCountsByType('CEMIT');

        $nteCountsStartsDigitationSedes = $this->getNteStartDigitationCountsByType('SEDE');
        $nteCountsStartsDigitationAnexos = $this->getNteStartDigitationCountsByType('ANEXO');
        $nteCountsStartsDigitationCemits = $this->getNteStartDigitationCountsByType('CEMIT');

        $nteCountsFinishedDigitationSedes = $this->getNteFinishedDigitationCountsByType('SEDE');
        $nteCountsFinishedDigitationAnexos = $this->getNteFinishedDigitationCountsByType('ANEXO');
        $nteCountsFinishedDigitationCemits = $this->getNteFinishedDigitationCountsByType('CEMIT');


        $nteCountsTotal = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotal[$i] = $nteCountsSedes[$i] + $nteCountsAnexos[$i] + $nteCountsCemits[$i];
        }

        $nteCountsTotalStartsDigitation = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotalStartsDigitation[$i] = $nteCountsStartsDigitationSedes[$i] + $nteCountsStartsDigitationAnexos[$i] + $nteCountsStartsDigitationCemits[$i];
        }

        $nteCountsTotalFinishedDigitation = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotalFinishedDigitation[$i] = $nteCountsFinishedDigitationSedes[$i] + $nteCountsFinishedDigitationAnexos[$i] + $nteCountsFinishedDigitationCemits[$i];
        }

        Session::put('previous_url', '/status/digitacao/filter');
        Session::put('uees', $uees);

        return view('uees.status_uees', [
            'uees' => $uees,
            'nteCountsSedes' => $nteCountsSedes,
            'nteCountsAnexos' => $nteCountsAnexos,
            'nteCountsCemits' => $nteCountsCemits,
            'nteCountsStartsDigitationSedes' => $nteCountsStartsDigitationSedes,
            'nteCountsStartsDigitationAnexos' => $nteCountsStartsDigitationAnexos,
            'nteCountsStartsDigitationCemits' => $nteCountsStartsDigitationCemits,
            'nteCountsFinishedDigitationSedes' => $nteCountsFinishedDigitationSedes,
            'nteCountsFinishedDigitationAnexos' => $nteCountsFinishedDigitationAnexos,
            'nteCountsFinishedDigitationCemits' => $nteCountsFinishedDigitationCemits,
            'nteCountsTotal' => $nteCountsTotal,
            'nteCountsTotalStartsDigitation' => $nteCountsTotalStartsDigitation,
            'nteCountsTotalFinishedDigitation' => $nteCountsTotalFinishedDigitation,
        ]);
    }

    public function statusDigitacaoExcel()
    {

        $uees = session()->get('uees');

        return view('excel.status_digitation',  [
            'uees' => $uees,
        ]);
    }

    public function statusDigitacaoFilter()
    {

        $uees = session()->get('uees');

        $nteCountsSedes = $this->getNteCountsByType('SEDE');
        $nteCountsAnexos = $this->getNteCountsByType('ANEXO');
        $nteCountsCemits = $this->getNteCountsByType('CEMIT');

        $nteCountsStartsDigitationSedes = $this->getNteStartDigitationCountsByType('SEDE');
        $nteCountsStartsDigitationAnexos = $this->getNteStartDigitationCountsByType('ANEXO');
        $nteCountsStartsDigitationCemits = $this->getNteStartDigitationCountsByType('CEMIT');

        $nteCountsFinishedDigitationSedes = $this->getNteFinishedDigitationCountsByType('SEDE');
        $nteCountsFinishedDigitationAnexos = $this->getNteFinishedDigitationCountsByType('ANEXO');
        $nteCountsFinishedDigitationCemits = $this->getNteFinishedDigitationCountsByType('CEMIT');


        $nteCountsTotal = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotal[$i] = $nteCountsSedes[$i] + $nteCountsAnexos[$i] + $nteCountsCemits[$i];
        }

        $nteCountsTotalStartsDigitation = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotalStartsDigitation[$i] = $nteCountsStartsDigitationSedes[$i] + $nteCountsStartsDigitationAnexos[$i] + $nteCountsStartsDigitationCemits[$i];
        }

        $nteCountsTotalFinishedDigitation = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotalFinishedDigitation[$i] = $nteCountsFinishedDigitationSedes[$i] + $nteCountsFinishedDigitationAnexos[$i] + $nteCountsFinishedDigitationCemits[$i];
        }

        Session::put('previous_url', '/status/digitacao/filter');
        Session::put('uees', $uees);

        return view('uees.status_uees', [
            'uees' => $uees,
            'nteCountsSedes' => $nteCountsSedes,
            'nteCountsAnexos' => $nteCountsAnexos,
            'nteCountsCemits' => $nteCountsCemits,
            'nteCountsStartsDigitationSedes' => $nteCountsStartsDigitationSedes,
            'nteCountsStartsDigitationAnexos' => $nteCountsStartsDigitationAnexos,
            'nteCountsStartsDigitationCemits' => $nteCountsStartsDigitationCemits,
            'nteCountsFinishedDigitationSedes' => $nteCountsFinishedDigitationSedes,
            'nteCountsFinishedDigitationAnexos' => $nteCountsFinishedDigitationAnexos,
            'nteCountsFinishedDigitationCemits' => $nteCountsFinishedDigitationCemits,
            'nteCountsTotal' => $nteCountsTotal,
            'nteCountsTotalStartsDigitation' => $nteCountsTotalStartsDigitation,
            'nteCountsTotalFinishedDigitation' => $nteCountsTotalFinishedDigitation,
        ]);
    }

    public function statusUnidadesEscolares()
    {


        $uees = Uee::orderBy('nte', 'asc')
            ->select(
                'id',
                'nte',
                'municipio',
                'unidade_escolar',
                'cod_unidade',
                'typing_started',
                'finished_typing',
                'tipo',
                'description_typing_started',
                'finished_typing_description',
                'situacao'
            )
            ->where(function ($query) {
                $query->where('desativation_situation', 'Ativa')
                    ->orWhereNull('desativation_situation');
            })
            ->where('tipo', '!=', 'NTE')
            ->get();



        $nteCountsSedes = $this->getNteCountsByType('SEDE');
        $nteCountsAnexos = $this->getNteCountsByType('ANEXO');
        $nteCountsCemits = $this->getNteCountsByType('CEMIT');

        $nteCountsStartsDigitationSedes = $this->getNteStartDigitationCountsByType('SEDE');
        $nteCountsStartsDigitationAnexos = $this->getNteStartDigitationCountsByType('ANEXO');
        $nteCountsStartsDigitationCemits = $this->getNteStartDigitationCountsByType('CEMIT');

        $nteCountsFinishedDigitationSedes = $this->getNteFinishedDigitationCountsByType('SEDE');
        $nteCountsFinishedDigitationAnexos = $this->getNteFinishedDigitationCountsByType('ANEXO');
        $nteCountsFinishedDigitationCemits = $this->getNteFinishedDigitationCountsByType('CEMIT');


        $nteCountsTotal = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotal[$i] = $nteCountsSedes[$i] + $nteCountsAnexos[$i] + $nteCountsCemits[$i];
        }

        $nteCountsTotalStartsDigitation = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotalStartsDigitation[$i] = $nteCountsStartsDigitationSedes[$i] + $nteCountsStartsDigitationAnexos[$i] + $nteCountsStartsDigitationCemits[$i];
        }

        $nteCountsTotalFinishedDigitation = [];
        for ($i = 1; $i <= 27; $i++) {
            $nteCountsTotalFinishedDigitation[$i] = $nteCountsFinishedDigitationSedes[$i] + $nteCountsFinishedDigitationAnexos[$i] + $nteCountsFinishedDigitationCemits[$i];
        }

        Session::put('previous_url', url()->current());
        Session::put('uees', $uees);

        return view('relatorios.relatorio_qtd_unidades_escolares', [
            'uees' => $uees,
            'nteCountsSedes' => $nteCountsSedes,
            'nteCountsAnexos' => $nteCountsAnexos,
            'nteCountsCemits' => $nteCountsCemits,
            'nteCountsStartsDigitationSedes' => $nteCountsStartsDigitationSedes,
            'nteCountsStartsDigitationAnexos' => $nteCountsStartsDigitationAnexos,
            'nteCountsStartsDigitationCemits' => $nteCountsStartsDigitationCemits,
            'nteCountsFinishedDigitationSedes' => $nteCountsFinishedDigitationSedes,
            'nteCountsFinishedDigitationAnexos' => $nteCountsFinishedDigitationAnexos,
            'nteCountsFinishedDigitationCemits' => $nteCountsFinishedDigitationCemits,
            'nteCountsTotal' => $nteCountsTotal,
            'nteCountsTotalStartsDigitation' => $nteCountsTotalStartsDigitation,
            'nteCountsTotalFinishedDigitation' => $nteCountsTotalFinishedDigitation,
        ]);
    }
}
