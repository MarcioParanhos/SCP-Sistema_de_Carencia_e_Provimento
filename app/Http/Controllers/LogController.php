<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index()
    {
        $anoRef = session()->get('ano_ref');

        // base logs query
        $baseQuery = Log::where('ano_ref', $anoRef);

        $logs = Log::with('user')->where('ano_ref', $anoRef)->limit(100)->orderBy('created_at', 'desc')->get();

        // Metrics for admin cards
        $totalLogs = $baseQuery->count();

        $logsByModule = $baseQuery->select('module', DB::raw('count(*) as total'))
            ->groupBy('module')
            ->orderByDesc('total')
            ->get();

        $actionsCount = $baseQuery->select('action', DB::raw('count(*) as total'))
            ->groupBy('action')
            ->get();

        // top users by number of log entries (efficient count then fetch users)
        $topUsersCounts = DB::table('logs')
            ->select('user_id', DB::raw('count(*) as total'))
            ->where('ano_ref', $anoRef)
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topUserIds = $topUsersCounts->pluck('user_id')->filter()->all();
        $users = User::whereIn('id', $topUserIds)->get()->keyBy('id');
        $topUsers = $topUsersCounts->map(function ($r) use ($users) {
            $user = $users->get($r->user_id);
            return [
                'id' => $r->user_id,
                'name' => $user ? $user->name : 'Usuário #' . $r->user_id,
                'total' => $r->total,
            ];
        });

        // activity last 7 days
        $fromDate = Carbon::now()->subDays(6)->startOfDay();
        $activityLast7 = DB::table('logs')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('ano_ref', $anoRef)
            ->where('created_at', '>=', $fromDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

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

        return view('logs.show_logs', compact(
            'logs', 'usuariosProvimento', 'usuariosCarencia',
            'totalLogs', 'logsByModule', 'actionsCount', 'topUsers', 'activityLast7'
        ));
    }

    /**
     * Server-side DataTables data provider for logs
     */
    public function data(Request $request)
    {
        $anoRef = session()->get('ano_ref');

        $query = Log::with('user')->where('ano_ref', $anoRef);

        $recordsTotal = $query->count();

        // search
        $search = $request->input('search.value');
        if (!empty($search)) {
            $query = $query->where(function ($q) use ($search) {
                $q->where('module', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('provimento_id', 'like', "%{$search}%")
                    ->orWhere('carencia_id', 'like', "%{$search}%")
                    ->orWhere('created_at', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $recordsFiltered = $query->count();

        // ordering
        $orderColIndex = $request->input('order.0.column', 3);
        $orderDir = $request->input('order.0.dir', 'desc');

        // map DataTables column index to DB column
        $columnsMap = [
            0 => 'module',
            1 => 'carencia_id', // will be used for sort; provimento_id may be null
            2 => 'action',
            3 => 'created_at',
            4 => 'user_id',
        ];

        $orderColumn = $columnsMap[$orderColIndex] ?? 'created_at';

        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        $rows = $query->with('user')->orderBy($orderColumn, $orderDir)->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $log) {
            // prepare old/new payloads (same logic as view)
            $oldJson = '';
            $newJson = '';
            if (!empty($log->old_record) && !empty($log->new_record)) {
                $oldJson = is_string($log->old_record) ? $log->old_record : json_encode($log->old_record, JSON_UNESCAPED_UNICODE);
                $newJson = is_string($log->new_record) ? $log->new_record : json_encode($log->new_record, JSON_UNESCAPED_UNICODE);
            } else {
                if (!empty($log->new_record)) {
                    $oldJson = is_string($log->new_record) ? $log->new_record : json_encode($log->new_record, JSON_UNESCAPED_UNICODE);
                    $newJson = json_encode(method_exists($log, 'toArray') ? $log->toArray() : (array) $log, JSON_UNESCAPED_UNICODE);
                } else {
                    $oldJson = '';
                    $newJson = json_encode(method_exists($log, 'toArray') ? $log->toArray() : (array) $log, JSON_UNESCAPED_UNICODE);
                }
            }

            $hasChanges = !empty($oldJson) && (($log->action ?? '') !== 'Inclusion');

            $idValue = $log->carencia_id ?? $log->provimento_id ?? '';

            $idHtml = '';
            if ($log->module === 'Carência' && $log->carencia_id) {
                $idHtml = "<a href=\"/detalhar_carencia/{$log->carencia_id}\">{$log->carencia_id}</a>";
            } elseif ($log->provimento_id) {
                $idHtml = "<a href=\"/provimento/detalhes_provimento/{$log->provimento_id}\">{$log->provimento_id}</a>";
            }

            $actionsHtml = '-';
            if ($hasChanges) {
                $dataOld = base64_encode($oldJson);
                $dataNew = base64_encode($newJson);
                $moduleEsc = e($log->module);
                $idEsc = e($idValue);
                $actionsHtml = '<button type="button" class="btn btn-sm btn-warning btn-view-changes" '
                    . 'data-old="' . $dataOld . '" '
                    . 'data-new="' . $dataNew . '" '
                    . 'data-module="' . $moduleEsc . '" '
                    . 'data-id="' . $idEsc . '">Alterações</button>';
            }

            $data[] = [
                'module' => strtoupper($log->module),
                'id_html' => $idHtml,
                'action' => ($log->action === 'Inclusion') ? 'INCLUSÃO' : 'ATUALIZAÇÃO',
                'created_at' => \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i'),
                'user_name' => $log->user ? strtoupper($log->user->name) : 'Usuário não encontrado',
                'actions' => $actionsHtml,
            ];
        }

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
