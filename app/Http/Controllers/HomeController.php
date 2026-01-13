<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;

use App\Models\Carencia;
use App\Models\Provimento;
use App\Models\Status_diario;
use Illuminate\Support\Facades\DB;




class HomeController extends Controller
{
    public function index()
    {
        // NTE users should see the standard home dashboard; do not redirect to ingresso.dashboard
        $actualDate = Carbon::now();
        $formattedDate = $actualDate->format('Y-m-d');
        $anoRef = session()->get('ano_ref');

        $user = auth()->user();
        $userNte = intval($user->nte ?? 0);
        $isRestrictedNteUser = $user && $userNte > 0 && intval($user->profile_id ?? 0) !== 4;

        $carenciasBasicaReal = Carencia::where('tipo_carencia', 'Real')
            ->where('tipo_vaga', 'Basica')
            ->where('ano_ref', $anoRef)
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $carenciasProfiReal = Carencia::where('tipo_carencia', 'Real')
            ->where('tipo_vaga', 'Profissionalizante')
            ->where('ano_ref', $anoRef)
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $carenciasBasicaTemp = Carencia::where('tipo_carencia', 'temp')
            ->where('tipo_vaga', 'Basica')
            ->where('fim_vaga', '>', $formattedDate)
            ->where('ano_ref', $anoRef)
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $carenciasProfiTemp = Carencia::where('tipo_carencia', 'temp')
            ->where('tipo_vaga', 'Profissionalizante')
            ->where('fim_vaga', '>', $formattedDate)
            ->where('ano_ref', $anoRef)
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $carenciasRealEdEspecial = Carencia::where('tipo_carencia', 'Real')
            ->where('tipo_vaga', 'Especial')
            ->where('ano_ref', $anoRef)
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $carenciasTempEdEspecial = Carencia::where('tipo_carencia', 'temp')
            ->where('tipo_vaga', 'Especial')
            ->where('fim_vaga', '>', $formattedDate)
            ->where('ano_ref', $anoRef)
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $provimentosReal = Provimento::where('situacao_provimento', 'provida')
            ->where('ano_ref', $anoRef)
            ->where('tipo_carencia_provida', 'Real')
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $provimentosTemp = Provimento::where('situacao_provimento', 'provida')
            ->where('ano_ref', $anoRef)
            ->where('tipo_carencia_provida', 'Temp')
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $provimentosTramiteReal = Provimento::where('situacao_provimento', 'tramite')
            ->where('ano_ref', $anoRef)
            ->where('tipo_carencia_provida', 'real')
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $provimentosTramiteTemp = Provimento::where('situacao_provimento', 'tramite')
            ->where('ano_ref', $anoRef)
            ->where('tipo_carencia_provida', 'temp')
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $provimentosPCH = Provimento::where('pch', 'OK')
            ->where('ano_ref', $anoRef)
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $totalUnitsAnexos = DB::table('uees')
            ->where('tipo', 'ANEXO')
            ->where('situacao', 'HOMOLOGADA')
            ->where(function ($query) {
                $query->whereNull('desativation_situation')
                    ->orWhere('desativation_situation', '!=', 'Desativada');
            })
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->count();

        $totalUnitsSedes = DB::table('uees')
            ->where('tipo', 'SEDE')
            ->where('situacao', 'HOMOLOGADA')
            ->where(function ($query) {
                $query->whereNull('desativation_situation')
                    ->orWhere('desativation_situation', '!=', 'Desativada');
            })
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->count();

        $totalUnitsCemits = DB::table('uees')
            ->where('tipo', 'CEMIT')
            ->where('situacao', 'HOMOLOGADA')
            ->where(function ($query) {
                $query->whereNull('desativation_situation')
                    ->orWhere('desativation_situation', '!=', 'Desativada');
            })
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->count();
        $totalCarencia = Carencia::where('ano_ref', $anoRef)
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $disciplinas = DB::table('carencias')
            ->select('disciplina', DB::raw('sum(total) as total'))
            ->where(function ($query) use ($formattedDate, $anoRef) {
                $query->where('fim_vaga', '>', $formattedDate)
                    ->orWhereNull('fim_vaga');
            })
            ->where('ano_ref', $anoRef)
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })
            ->groupBy('disciplina')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $uees = DB::table('carencias')
            ->select('unidade_escolar', 'cod_ue', 'nte', 'municipio', DB::raw('sum(total) as total'))
            ->where(function ($query) use ($anoRef) {
                $query->where('ano_ref', $anoRef)
                    ->orWhereNull('ano_ref');
            })
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })
            ->groupBy('unidade_escolar', 'cod_ue', 'nte', 'municipio')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();


        $actualDate = Carbon::now()->format('Y-m-d'); // Formata a data para incluir apenas ano, mês e dia
        $existingStatus = Status_diario::where('data', $actualDate)->first();
        $anoRef = session()->get('ano_ref');
        $carenciasBasicaReal = Carencia::where('tipo_carencia', 'Real')
            ->where('tipo_vaga', 'Basica')
            ->where('ano_ref', $anoRef)
            ->where('motivo_vaga', '!=', "MEDIADOR EMITEC")
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $carenciasProfiReal = Carencia::where('tipo_carencia', 'Real')
            ->where('tipo_vaga', 'Profissionalizante')
            ->where('ano_ref', $anoRef)
            ->where('motivo_vaga', '!=', "MEDIADOR EMITEC")
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $carenciasBasicaTemp = Carencia::where('tipo_carencia', 'temp')
            ->where('tipo_vaga', 'Basica')
            ->where('fim_vaga', '>', $actualDate)
            ->where('ano_ref', $anoRef)
            ->where('motivo_vaga', '!=', "MEDIADOR EMITEC")
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $carenciasProfiTemp = Carencia::where('tipo_carencia', 'temp')
            ->where('tipo_vaga', 'Profissionalizante')
            ->where('fim_vaga', '>', $actualDate)
            ->where('ano_ref', $anoRef)
            ->where('motivo_vaga', '!=', "MEDIADOR EMITEC")
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $carenciasRealEdEspecial = Carencia::where('tipo_carencia', 'Real')
            ->where('tipo_vaga', 'Especial')
            ->where('ano_ref', $anoRef)
            ->where('motivo_vaga', '!=', "MEDIADOR EMITEC")
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $carenciasTempEdEspecial = Carencia::where('tipo_carencia', 'temp')
            ->where('tipo_vaga', 'Especial')
            ->where('fim_vaga', '>', $actualDate)
            ->where('ano_ref', $anoRef)
            ->where('motivo_vaga', '!=', "MEDIADOR EMITEC")
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $provimentosReal = Provimento::where('situacao_provimento', 'provida')
            ->where('ano_ref', $anoRef)
            ->where('tipo_carencia_provida', 'Real')
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $provimentosTemp = Provimento::where('situacao_provimento', 'provida')
            ->where('ano_ref', $anoRef)
            ->where('tipo_carencia_provida', 'Temp')
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $provimentosTramiteReal = Provimento::where('situacao_provimento', 'tramite')
            ->where('ano_ref', $anoRef)
            ->where('tipo_carencia_provida', 'real')
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $provimentosTramiteTemp = Provimento::where('situacao_provimento', 'tramite')
            ->where('ano_ref', $anoRef)
            ->where('tipo_carencia_provida', 'temp')
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        $vagaEmitec = Carencia::where('tipo_carencia', 'Real')
            ->where('ano_ref', $anoRef)
            ->where('tipo_vaga', 'Emitec')
            ->where('motivo_vaga', "MEDIADOR EMITEC")
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->sum('total');

        if (!$existingStatus) {
            $actualDate = Carbon::now();
            $formattedDate = $actualDate->format('Y');
            $status_diario = new Status_diario;
            $status_diario->data = $actualDate;
            $status_diario->carenciaProfReal = $carenciasProfiReal;
            $status_diario->carenciaBasicaReal = $carenciasBasicaReal;
            $status_diario->carenciaBasicaTemp = $carenciasBasicaTemp;
            $status_diario->carenciaProfiTemp = $carenciasProfiTemp;
            $status_diario->carenciaRealEdEspecial = $carenciasRealEdEspecial;
            $status_diario->carenciaTempEdEspecial = $carenciasTempEdEspecial;
            $status_diario->provimentoReal = $provimentosReal;
            $status_diario->provimentoTemp = $provimentosTemp;
            $status_diario->provimentoTramiteReal = $provimentosTramiteReal;
            $status_diario->provimentoTramiteTemp = $provimentosTramiteTemp;
            $status_diario->ano_ref = $formattedDate;
            $status_diario->save();
        }

        $totalUnitsSedesAll = DB::table('uees')
            ->where('tipo', 'SEDE')
            ->where('situacao', 'HOMOLOGADA')
            ->where(function ($query) {
                $query->whereNull('desativation_situation')
                    ->orWhere('desativation_situation', '!=', 'Desativada');
            })
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->count();

        $totalUnitsAnexosAll = DB::table('uees')
            ->where('tipo', 'ANEXO')
            ->where('situacao', 'HOMOLOGADA')
            ->where(function ($query) {
                $query->whereNull('desativation_situation')
                    ->orWhere('desativation_situation', '!=', 'Desativada');
            })
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->count();

        $totalUnitsCemitAll = DB::table('uees')
            ->where('tipo', 'CEMIT')
            ->where('situacao', 'HOMOLOGADA')
            ->where(function ($query) {
                $query->whereNull('desativation_situation')
                    ->orWhere('desativation_situation', '!=', 'Desativada');
            })
            ->when($isRestrictedNteUser, function ($q) use ($userNte) {
                $q->where('nte', $userNte);
            })->count();


        $percentSedes = $totalUnitsSedesAll > 0 ? ($totalUnitsSedes / $totalUnitsSedesAll) * 100 : 0;
        $percentAnexos = $totalUnitsAnexosAll > 0 ? ($totalUnitsAnexos / $totalUnitsAnexosAll) * 100 : 0;
        $percentCemits = $totalUnitsCemitAll > 0 ? ($totalUnitsCemits / $totalUnitsCemitAll) * 100 : 0;

        $pendingUnitsCemits = $totalUnitsCemitAll - $totalUnitsCemits;
        $percentPendingCemits = $totalUnitsCemitAll > 0
            ? ($pendingUnitsCemits / $totalUnitsCemitAll) * 100
            : 0;

        $pendingUnitsAnexos = $totalUnitsAnexosAll - $totalUnitsAnexos;
        $percentPendingAnexos = $totalUnitsAnexosAll > 0
            ? ($pendingUnitsAnexos / $totalUnitsAnexosAll) * 100
            : 0;

        $pendingUnitsSedes = $totalUnitsSedesAll - $totalUnitsSedes;
        $percentPendingSedes = $totalUnitsSedesAll > 0
            ? ($pendingUnitsSedes / $totalUnitsSedesAll) * 100
            : 0;

        return view('home.home', compact(
            'pendingUnitsSedes',
            'percentPendingSedes',
            'pendingUnitsAnexos',
            'percentPendingAnexos',
            'pendingUnitsCemits',
            'percentPendingCemits',
            'totalUnitsSedesAll',
            'totalUnitsAnexosAll',
            'totalUnitsCemitAll',
            'totalUnitsCemits',
            'totalUnitsSedes',
            'provimentosPCH',
            'carenciasBasicaReal',
            'carenciasProfiReal',
            'carenciasBasicaTemp',
            'carenciasProfiTemp',
            'carenciasRealEdEspecial',
            'carenciasTempEdEspecial',
            'provimentosReal',
            'provimentosTemp',
            'disciplinas',
            'totalUnitsAnexos',
            'provimentosTramiteTemp',
            'provimentosTramiteReal',
            'totalCarencia',
            'uees',
            'anoRef',
            'vagaEmitec',
            'percentSedes',
            'percentCemits',
            'percentAnexos'
        ));
    }

    public function AddAnoref($ano)
    {
        $anoRef = $ano;

        session()->put('ano_ref', $anoRef);
        return response()->json(['success' => true]);
    }

    public function statusDiario()
    {
        $anoRef = session()->get('ano_ref');
        $status_diarios = Status_diario::orderBy('data', 'desc')
            ->where('ano_ref', $anoRef)
            ->get();

        return view('relatorios.status_diario', [
            'status_diarios' => $status_diarios,
        ]);
    }
}
