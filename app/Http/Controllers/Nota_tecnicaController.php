<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Models
use App\Models\Carencia;
use App\Models\Provimento;
use App\Models\Uee;
use Illuminate\Support\Facades\DB;


class Nota_tecnicaController extends Controller
{
    public function searchUeeForNota_tecnica()
    {
        return view('nota_tecnica.search_nota_tecnica');
    }

    public function notaTecnica(Request $request)
    {
        $anoRef = session()->get('ano_ref');

        $ueeDetails = Uee::where('cod_unidade', $request->cod_unidade_nota_tecnica)->first();

        $sumTotalProvimentoReal = Provimento::where('cod_unidade', $request->cod_unidade_nota_tecnica)
            ->where('tipo_carencia_provida', 'Real')
            ->where('ano_ref', $anoRef)
            ->sum('total');

        $ueeDetailsCarenciaTemp = Carencia::select(
            'disciplina',
            'area',
            DB::raw('SUM(matutino) as total_carencia_matutino'),
            DB::raw('SUM(vespertino) as total_carencia_vespertino'),
            DB::raw('SUM(noturno) as total_carencia_noturno'),
            DB::raw('SUM(total) as total_carencia')
        )
            ->where('cod_ue', $request->cod_unidade_nota_tecnica)
            ->where('tipo_carencia', 'Temp')
            ->where('total', '!=', '0')
            ->whereDate('fim_vaga', '>=', date('Y-m-d'))
            ->where('ano_ref', $anoRef)
            ->groupBy('disciplina', 'area')
            ->get();

        $ueeDetailsCarenciaReal = Carencia::select(
            'disciplina',
            'area',
            DB::raw('SUM(matutino) as total_carencia_matutino'),
            DB::raw('SUM(vespertino) as total_carencia_vespertino'),
            DB::raw('SUM(noturno) as total_carencia_noturno'),
            DB::raw('SUM(total) as total_carencia')
        )
            ->where('cod_ue', $request->cod_unidade_nota_tecnica)
            ->where('tipo_carencia', 'Real')
            ->where('total', '!=', '0')
            ->where('ano_ref', $anoRef)
            ->groupBy('disciplina', 'area')
            ->get();

        $ueeDetailProvimentoSuprido = Provimento::select(
            'disciplina',
            'tipo_movimentacao',
            DB::raw('SUM(provimento_matutino) as total_suprimento_matutino'),
            DB::raw('SUM(provimento_vespertino) as total_suprimento_vespertino'),
            DB::raw('SUM(provimento_noturno) as total_suprimento_noturno'),
            DB::raw('SUM(total) as total_suprimento')
        )
            ->where('cod_unidade', $request->cod_unidade_nota_tecnica)
            ->where('situacao_provimento', 'provida')
            ->where('ano_ref', $anoRef)
            ->groupBy('disciplina', 'tipo_movimentacao')
            ->get();

        $ueeDetailProvimentoTramite = Provimento::select(
            'disciplina',
            'tipo_movimentacao',
            DB::raw('SUM(provimento_matutino) as total_suprimento_matutino'),
            DB::raw('SUM(provimento_vespertino) as total_suprimento_vespertino'),
            DB::raw('SUM(provimento_noturno) as total_suprimento_noturno'),
            DB::raw('SUM(total) as total_suprimento')
        )
            ->where('cod_unidade', $request->cod_unidade_nota_tecnica)
            ->where('situacao_provimento', 'tramite')
            ->where('ano_ref', $anoRef)
            ->groupBy('disciplina', 'tipo_movimentacao')
            ->get();

        if (!$ueeDetails) {
            return  redirect()->to(url()->previous())->with('msg', 'Nenhuma unidade escolar encontrada!');
        } else {
            return view('relatorios.nota_tecnica', compact('sumTotalProvimentoReal', 'ueeDetailProvimentoTramite', 'ueeDetailsCarenciaReal', 'ueeDetailProvimentoSuprido', 'ueeDetails', 'ueeDetailsCarenciaTemp'));
        }
    }
}
