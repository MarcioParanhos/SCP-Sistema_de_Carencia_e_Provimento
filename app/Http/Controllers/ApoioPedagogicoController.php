<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Apoio_pedagogico_vaga;


class ApoioPedagogicoController extends Controller

{
    public function newCarenciaRealApoioPedagogico()
    {   
        $sumVacancyPedagogical = Apoio_pedagogico_vaga::sum('qtd');
        $vagas_apoio_pedagocigo = Apoio_pedagogico_vaga::orderBy('nte', 'asc')->orderBy('municipio', 'asc')->get();
        session()->put('vagas_apoio_pedagocigo', $vagas_apoio_pedagocigo);
        return view('carencia.add_apoio_pedagogico' , compact('vagas_apoio_pedagocigo', 'sumVacancyPedagogical'));
    }

    public function addCarenciaPedagogica(Request $request)
    {
        $apoio_pedagogico = new Apoio_pedagogico_vaga;
        $apoio_pedagogico->nte = $request->nte_seacrh;
        $apoio_pedagogico->municipio = $request->municipio_search;
        $apoio_pedagogico->unidade_escolar = $request->search_uee;
        $apoio_pedagogico->cod_unidade = $request->cod_ue;
        $apoio_pedagogico->profissional = $request->profissional;
        $apoio_pedagogico->regime = $request->regime;
        $apoio_pedagogico->turno = $request->turno;
        $apoio_pedagogico->qtd = $request->qtd;
        $apoio_pedagogico->save();

        return  redirect()->to(url()->previous())->with('msg', 'VAGA DE APOIO PEDAGOGICO CADASTRADA COM SUCESSO!');
    }

    public function destroyVacancyPedagogical($id)
    {
        $apoio_pedagogico_vaga = Apoio_pedagogico_vaga::findOrFail($id);
        $apoio_pedagogico_vaga->delete();
        return  redirect()->to(url()->previous())->with('msg', 'EXCLUIDO COM SUCESSO!');
    }

    public function showCarenciaApoioPedagogicoByForm(Request $request) {

        $vagas_apoio_pedagocigo = Apoio_pedagogico_vaga::query();
        $sumVacancyPedagogical = 0;
        // Verifica se os campos foram preenchidos
        if ($request->filled('nte_ap')) {
            $vagas_apoio_pedagocigo = $vagas_apoio_pedagocigo->where('nte', $request->nte_ap);
        }

        if ($request->filled('municipio_ap')) {
            $vagas_apoio_pedagocigo = $vagas_apoio_pedagocigo->where('municipio', $request->municipio_ap);
        }

        if ($request->filled('uee_ap')) {
            $vagas_apoio_pedagocigo = $vagas_apoio_pedagocigo->where('unidade_escolar', $request->uee_ap);
        }

        if ($request->filled('cod_ue_ap')) {
            $vagas_apoio_pedagocigo = $vagas_apoio_pedagocigo->where('cod_unidade', $request->cod_ue_ap);
        }

        if ($request->filled('profissional_search')) {
            $vagas_apoio_pedagocigo = $vagas_apoio_pedagocigo->where('profissional', $request->profissional_search);
        }

        if ($request->filled('regime_search')) {
            $vagas_apoio_pedagocigo = $vagas_apoio_pedagocigo->where('regime', $request->regime_search);
        }

        if ($request->filled('turno_search')) {
            $vagas_apoio_pedagocigo = $vagas_apoio_pedagocigo->where('area', $request->turno_search);
        }

        $vagas_apoio_pedagocigo = $vagas_apoio_pedagocigo->orderBy('nte', 'asc')->orderBy('municipio', 'asc')->get();
        $sumVacancyPedagogical = $vagas_apoio_pedagocigo->sum('qtd');
        session()->put('vagas_apoio_pedagocigo', $vagas_apoio_pedagocigo);

        return view('carencia.add_apoio_pedagogico', [
            'vagas_apoio_pedagocigo' => $vagas_apoio_pedagocigo,
            'sumVacancyPedagogical' => $sumVacancyPedagogical

        ]);

    }

    public function printoutTable()
    {
        $vagas_apoio_pedagocigo = session()->get('vagas_apoio_pedagocigo')
                  ->groupBy('nte')
                  ->sortBy(function ($group) {
                      return (int) $group->first()->nte;
                  });
        $sumVacancyPedagogical = $vagas_apoio_pedagocigo->sum('qtd');

        return view('relatorios.relatorio_carencia_apoioPedagogico', compact('sumVacancyPedagogical', 'vagas_apoio_pedagocigo'));
    }

    public function generateExcel()
    {

        $vagas_apoio_pedagocigo = session()->get('vagas_apoio_pedagocigo');
        $sumVacancyPedagogical = $vagas_apoio_pedagocigo->sum('qtd');
        return view('excel.excel_carencia_apoio_pedagogico', compact('sumVacancyPedagogical', 'vagas_apoio_pedagocigo'));
    }

    
}
