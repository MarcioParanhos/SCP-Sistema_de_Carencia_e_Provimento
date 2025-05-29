<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index()
    {
        $anoRef = session()->get('ano_ref');

        $logs = Log::with('user')->where('ano_ref', $anoRef)->get();

        $usuariosProvimento = Log::with('user')
            ->where('module', 'Provimento')
            ->whereNotNull('provimento_id')
            ->where('ano_ref', $anoRef)
            ->get()
            ->groupBy('user.name')
            ->map(function ($logs, $userName) {
                $provimentoIds = $logs->pluck('provimento_id')->unique();

                $totais = DB::table('provimentos')
                    ->whereIn('id', $provimentoIds)
                    ->selectRaw('
                SUM(provimento_matutino) as total_matutino,
                SUM(provimento_vespertino) as total_vespertino,
                SUM(provimento_noturno) as total_noturno
            ')
                    ->first();

                return [
                    'nome' => $userName,
                    'total_matutino' => $totais->total_matutino ?? 0,
                    'total_vespertino' => $totais->total_vespertino ?? 0,
                    'total_noturno' => $totais->total_noturno ?? 0,
                ];
            })
            ->values();

        // Usuários que realizaram ações de carência (apenas 'Inclusion')
        $usuariosCarencia = Log::with('user')
            ->where('module', 'Carência')
            ->where('action', 'Inclusion')
            ->whereNotNull('carencia_id')
            ->where('ano_ref', $anoRef)
            ->get()
            ->groupBy('user.name')
            ->map(function ($logs, $userName) {
                $carenciaIds = $logs->pluck('carencia_id')->unique();

                $totais = DB::table('carencias')
                    ->whereIn('id', $carenciaIds)
                    ->selectRaw('
                SUM(matutino) as total_matutino,
                SUM(vespertino) as total_vespertino,
                SUM(noturno) as total_noturno
            ')
                    ->first();

                return [
                    'nome' => $userName,
                    'total_matutino' => $totais->total_matutino ?? 0,
                    'total_vespertino' => $totais->total_vespertino ?? 0,
                    'total_noturno' => $totais->total_noturno ?? 0,
                ];
            })
            ->values();

        return view('logs.show_logs', compact('logs', 'usuariosProvimento', 'usuariosCarencia'));
    }
}
