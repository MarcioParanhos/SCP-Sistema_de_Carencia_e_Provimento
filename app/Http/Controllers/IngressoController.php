<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\Uee;
use App\Models\IngressoDocumento;
use App\Models\ProvimentosEncaminhado;

class IngressoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function authorizeUser()
    {
        $user = Auth::user();
        // Allow users with profile_id == 1 in either sector 7 (NTE) or sector 2 (CPM)
        // Also allow profile_id == 4 in sector 3 (e.g., specific admin/ope role)
        if (! $user || ! isset($user->profile_id) || ! isset($user->sector_id)) {
            return false;
        }

        if ($user->profile_id == 1 && in_array($user->sector_id, [7, 2])) {
            return true;
        }

        if ($user->profile_id == 4 && $user->sector_id == 3) {
            return true;
        }

        return false;
    }

    public function index()
    {
        if (! $this->authorizeUser()) {
            abort(403);
        }

        // Attempt to get columns from the ingresso_candidatos table to build the datatable
        $columns = [];
        if (Schema::hasTable('ingresso_candidatos')) {
            $columns = Schema::getColumnListing('ingresso_candidatos');
        }

        // Build list of available NTEs for the filter select
        $ntes = [];
            try {
            if (Schema::hasTable('ingresso_candidatos') && in_array('nte', $columns)) {
                try {
                    $ntes = DB::table('ingresso_candidatos')
                        ->select('nte')
                        ->whereNotNull('nte')
                        ->distinct()
                        ->orderByRaw("CAST(nte AS SIGNED) ASC")
                        ->pluck('nte')
                        ->toArray();
                } catch (\Throwable $ex) {
                    $ntes = DB::table('ingresso_candidatos')
                        ->select('nte')
                        ->whereNotNull('nte')
                        ->distinct()
                        ->orderBy('nte')
                        ->pluck('nte')
                        ->toArray();
                }
            } elseif (Schema::hasTable('uees')) {
                try {
                    $ntes = DB::table('uees')
                        ->select('nte')
                        ->whereNotNull('nte')
                        ->distinct()
                        ->orderByRaw("CAST(nte AS SIGNED) ASC")
                        ->pluck('nte')
                        ->toArray();
                } catch (\Throwable $ex) {
                    $ntes = DB::table('uees')
                        ->select('nte')
                        ->whereNotNull('nte')
                        ->distinct()
                        ->orderBy('nte')
                        ->pluck('nte')
                        ->toArray();
                }
            } elseif (class_exists(\App\Models\Uee::class)) {
                try {
                    $ntes = Uee::select('nte')->whereNotNull('nte')->distinct()->orderByRaw("CAST(nte AS SIGNED) ASC")->pluck('nte')->toArray();
                } catch (\Throwable $ex) {
                    $ntes = Uee::select('nte')->whereNotNull('nte')->distinct()->orderBy('nte')->pluck('nte')->toArray();
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to fetch NTE list for ingresso.index', ['exception' => $e->getMessage()]);
        }

        return view('ingresso.index', ['columns' => $columns, 'ntes' => $ntes]);
    }

    /**
     * Dashboard overview with summary stats (fake data for now)
     */
    public function dashboard()
    {
        if (! $this->authorizeUser()) {
            abort(403);
        }
        // Build real stats from DB, defensively checking available columns
        $table = 'ingresso_candidatos';
        $available = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];

        // Prepare a base query which may be scoped to the authenticated NTE user below
        $total = 0;
        $docsValidated = 0;
        $ingressados = 0;
        $pendencia = 0;
        $corrigirDocumentacao = 0;

        if (Schema::hasTable($table)) {
            $baseQuery = DB::table($table);
            // If current user is an NTE user, scope the baseQuery to their NTE
            try {
                $u = Auth::user();
                if ($u && isset($u->profile_id) && $u->profile_id == 1 && isset($u->sector_id) && $u->sector_id == 7) {
                    $userNte = $u->nte ?? null;
                    if ($userNte) {
                        if (in_array('nte', $available)) {
                            $baseQuery->where('nte', $userNte);
                        } elseif (in_array('uee_code', $available)) {
                            $baseQuery->where('uee_code', $userNte);
                        } elseif (in_array('uee_name', $available)) {
                            $baseQuery->where('uee_name', $userNte);
                        }
                    }
                }
            } catch (\Throwable $e) {
                // ignore scoping failures and continue with unscoped baseQuery
            }

            // Restrict dashboard to candidates that have been encaminhados (exist in provimentos_encaminhados)
                // NOTE: previously results were restricted to candidates that have been
                // encaminhados (exist in provimentos_encaminhados). That filter was
                // removed to allow listing all candidates while still scoping results
                // to the current user's NTE above.
                // The following code is commented out to remove the restriction.
                /*
                try {
                    if (Schema::hasTable('provimentos_encaminhados')) {
                        $encQuery = DB::table('provimentos_encaminhados')
                            ->select('ingresso_candidato_id')
                            ->whereNotNull('ingresso_candidato_id')
                            ->distinct();
                        $baseQuery->whereIn('id', $encQuery);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to apply encaminhados filter in ingresso dashboard', ['exception' => $e->getMessage()]);
                }
                */

            // now compute stats from scoped (and filtered) baseQuery using clones to avoid mutating it
            $total = (clone $baseQuery)->count();

            // Determine documentos validados count.
            // Prefer explicit `status` = 'Documentos Validados' when the column exists (exact match),
            // then fall back to `documentos_validados` boolean column, and finally to
            // ingresso_documentos summary only if neither column is present.
            try {
                if (in_array('status', $available)) {
                    $docsValidated = (clone $baseQuery)->whereRaw("LOWER(TRIM(status)) = 'documentos validados'")->count();
                } elseif (in_array('documentos_validados', $available)) {
                    $docsValidated = (clone $baseQuery)->where('documentos_validados', 1)->count();
                } elseif (Schema::hasTable('ingresso_documentos')) {
                    $sub = DB::table('ingresso_documentos')
                        ->select('ingresso_candidato_id', DB::raw('SUM(validated) as validated_count'), DB::raw('COUNT(*) as total'))
                        ->groupBy('ingresso_candidato_id');

                    $qbValid = (clone $baseQuery)
                        ->joinSub($sub, 'ds', function($join) {
                            $join->on('ingresso_candidatos.id', '=', 'ds.ingresso_candidato_id');
                        })
                        ->whereRaw('ds.total > 0 AND ds.validated_count = ds.total');

                    $docsValidated = $qbValid->count();
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to compute documentos_validados from ingresso_documentos, falling back', ['exception' => $e->getMessage()]);
                if (in_array('status', $available)) {
                    $docsValidated = (clone $baseQuery)->whereRaw("LOWER(TRIM(status)) = 'documentos validados'")->count();
                } elseif (in_array('documentos_validados', $available)) {
                    $docsValidated = (clone $baseQuery)->where('documentos_validados', 1)->count();
                }
            }

            if (in_array('status', $available)) {
                $ingressados = (clone $baseQuery)->whereRaw("LOWER(status) = 'apto para encaminhamento'")->count();

                // Count candidates whose status indicates they need document correction
                try {
                    $corrigirDocumentacao = (clone $baseQuery)->whereRaw("LOWER(status) LIKE '%corrig%'")->count();
                } catch (\Throwable $e) {
                    Log::warning('Failed to compute corrigir_documentacao count', ['exception' => $e->getMessage()]);
                    $corrigirDocumentacao = 0;
                }

                // Count candidates marked as 'Não Assumiu' (robust to accents/variants)
                try {
                    $naoAssumiu = (clone $baseQuery)
                        ->where(function($q){
                            $q->whereRaw("LOWER(status) LIKE '%nao assumiu%'")
                              ->orWhereRaw("LOWER(status) LIKE '%não assumiu%'")
                              ->orWhereRaw("LOWER(status) LIKE '%nao-assumiu%'");
                        })->count();
                } catch (\Throwable $e) {
                    Log::warning('Failed to compute nao_assumiu count', ['exception' => $e->getMessage()]);
                    $naoAssumiu = 0;
                }
            }

            // Compute pendência de documentos.
            // Prefer explicit `ingresso_documentos` table when present because it provides per-document validation state.
            $pendencia = 0;
            try {
                if (Schema::hasTable('ingresso_documentos')) {
                    $subPending = DB::table('ingresso_documentos')
                        ->select('ingresso_candidato_id', DB::raw('SUM(validated) as validated_count'), DB::raw('COUNT(*) as total'))
                        ->groupBy('ingresso_candidato_id');

                    // Left join so we include candidates that have no documents recorded (treat as pending)
                    $qbPend = (clone $baseQuery)
                        ->leftJoinSub($subPending, 'docsum', function($join) {
                            $join->on('ingresso_candidatos.id', '=', 'docsum.ingresso_candidato_id');
                        })
                        ->where(function($q){
                            $q->whereNull('docsum.total')
                              ->orWhereRaw('docsum.validated_count < docsum.total');
                        });

                    $pendencia = $qbPend->distinct('ingresso_candidatos.id')->count('ingresso_candidatos.id');
                } else {
                    if (in_array('documentos_validados', $available)) {
                        $pendencia = (clone $baseQuery)->where(function($q){
                            $q->whereNull('documentos_validados')->orWhere('documentos_validados', '<>', 1);
                        })->count();
                    } else {
                        if (in_array('status', $available)) {
                            // Treat as pending when status is not exactly 'Documentos Validados' (or is NULL)
                            $pendencia = (clone $baseQuery)->whereRaw("(status IS NULL OR LOWER(TRIM(status)) != 'documentos validados')")->count();
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to compute pendencia_documentos, falling back', ['exception' => $e->getMessage()]);
                // fallback to previous logic
                if (in_array('documentos_validados', $available)) {
                    $pendencia = (clone $baseQuery)->where(function($q){
                        $q->whereNull('documentos_validados')->orWhere('documentos_validados', '<>', 1);
                    })->count();
                } elseif (in_array('status', $available)) {
                    $pendencia = (clone $baseQuery)->whereRaw("(status IS NULL OR LOWER(TRIM(status)) != 'documentos validados')")->count();
                }
            }

            // Compute pending-for-CPM count. Prefer explicit `status` column when available.
            $pendenteConfirmacaoCpm = 0;
            try {
                if (in_array('status', $available)) {
                    $pendenteConfirmacaoCpm = (clone $baseQuery)
                        ->whereRaw("LOWER(status) LIKE '%aguard%'")
                        ->whereRaw("LOWER(status) LIKE '%cpm%'")
                        ->count();
                } elseif (Schema::hasTable('ingresso_documentos')) {
                    // count candidates where all documents are validated in ingresso_documentos
                    // but candidate.documentos_validados is not set to 1 yet
                    $sub = DB::table('ingresso_documentos')
                        ->select('ingresso_candidato_id', DB::raw('SUM(validated) as validated_count'), DB::raw('COUNT(*) as total'))
                        ->groupBy('ingresso_candidato_id');

                    $qb = (clone $baseQuery)
                        ->joinSub($sub, 'ds', function($join) {
                            $join->on('ingresso_candidatos.id', '=', 'ds.ingresso_candidato_id');
                        })
                        ->whereRaw('ds.total > 0 AND ds.validated_count = ds.total');

                    if (in_array('documentos_validados', $available)) {
                        $qb->where(function($q){
                            $q->whereNull('ingresso_candidatos.documentos_validados')->orWhere('ingresso_candidatos.documentos_validados', '<>', 1);
                        });
                    }

                    $pendenteConfirmacaoCpm = $qb->count();
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to compute pendente_confirmacao_cpm', ['exception' => $e->getMessage()]);
                $pendenteConfirmacaoCpm = 0;
            }
        }

        $stats = [
            'total_candidates' => $total,
            'ingressados' => $ingressados,
            'corrigir_documentacao' => $corrigirDocumentacao,
            'nao_assumiu' => $naoAssumiu ?? 0,
            'pendencia_documentos' => $pendencia,
            'documentos_validados' => $docsValidated,
            'pendente_confirmacao_cpm' => $pendenteConfirmacaoCpm ?? 0,
        ];

        // Helper to compute stats for a given status_convocacao value
        $computeStatsForConv = function($statusConv) use ($table, $available) {
            try {
                $cols = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
                $base = DB::table($table);
                // apply NTE scoping if authenticated user has nte
                try {
                    $u = Auth::user();
                    $userNte = $u->nte ?? null;
                    if ($userNte) {
                        if (in_array('nte', $cols)) $base->where('nte', $userNte);
                        elseif (in_array('uee_code', $cols)) $base->where('uee_code', $userNte);
                        elseif (in_array('uee_name', $cols)) $base->where('uee_name', $userNte);
                    }
                } catch (\Throwable $e) {}

                if (in_array('status_convocacao', $cols)) {
                    $base->where('status_convocacao', $statusConv);
                }

                $total = (clone $base)->count();

                $ingressados = 0; $corrigir = 0; $naoAssumiu = 0; $pendencia = 0; $docsValidated = 0; $pendenteCpm = 0;

                if (in_array('status', $cols)) {
                    $ingressados = (clone $base)->whereRaw("LOWER(status) = 'apto para encaminhamento'")->count();
                    try { $corrigir = (clone $base)->whereRaw("LOWER(status) LIKE '%corrig%'")->count(); } catch (\Throwable $e) { $corrigir = 0; }
                    try {
                        $naoAssumiu = (clone $base)->where(function($q){
                            $q->whereRaw("LOWER(status) LIKE '%nao assumiu%'")
                              ->orWhereRaw("LOWER(status) LIKE '%não assumiu%'")
                              ->orWhereRaw("LOWER(status) LIKE '%nao-assumiu%'");
                        })->count();
                    } catch (\Throwable $e) { $naoAssumiu = 0; }
                }

                // documentos validados preference: exact status, then documentos_validados column, then ingresso_documentos summary
                if (in_array('status', $cols)) {
                    $docsValidated = (clone $base)->whereRaw("LOWER(TRIM(status)) = 'documentos validados'")->count();
                } elseif (in_array('documentos_validados', $cols)) {
                    $docsValidated = (clone $base)->where('documentos_validados', 1)->count();
                } elseif (Schema::hasTable('ingresso_documentos')) {
                    $sub = DB::table('ingresso_documentos')
                        ->select('ingresso_candidato_id', DB::raw('SUM(validated) as validated_count'), DB::raw('COUNT(*) as total'))
                        ->groupBy('ingresso_candidato_id');
                    $qbValid = (clone $base)
                        ->joinSub($sub, 'ds', function($join){ $join->on('ingresso_candidatos.id', '=', 'ds.ingresso_candidato_id'); })
                        ->whereRaw('ds.total > 0 AND ds.validated_count = ds.total');
                    $docsValidated = $qbValid->count();
                }

                // pendencia: status not equal to exact 'Documentos Validados' or documentos_validados != 1
                if (in_array('documentos_validados', $cols)) {
                    $pendencia = (clone $base)->where(function($q){ $q->whereNull('documentos_validados')->orWhere('documentos_validados','<>',1); })->count();
                } elseif (in_array('status', $cols)) {
                    $pendencia = (clone $base)->whereRaw("(status IS NULL OR LOWER(TRIM(status)) != 'documentos validados')")->count();
                }

                // pendente_confirmacao_cpm: status contains aguard and cpm
                if (in_array('status', $cols)) {
                    try { $pendenteCpm = (clone $base)->whereRaw("LOWER(status) LIKE '%aguard%'")->whereRaw("LOWER(status) LIKE '%cpm%'")->count(); } catch (\Throwable $e) { $pendenteCpm = 0; }
                } elseif (Schema::hasTable('ingresso_documentos')) {
                    $subPending = DB::table('ingresso_documentos')
                        ->select('ingresso_candidato_id', DB::raw('SUM(validated) as validated_count'), DB::raw('COUNT(*) as total'))
                        ->groupBy('ingresso_candidato_id');
                    $qbPend = (clone $base)->leftJoinSub($subPending, 'docsum', function($join){ $join->on('ingresso_candidatos.id', '=', 'docsum.ingresso_candidato_id'); })->where(function($q){ $q->whereNull('docsum.total')->orWhereRaw('docsum.validated_count < docsum.total'); });
                    $pendenteCpm = $qbPend->distinct('ingresso_candidatos.id')->count('ingresso_candidatos.id');
                }

                return [
                    'total_candidates' => $total,
                    'ingressados' => $ingressados,
                    'corrigir_documentacao' => $corrigir,
                    'nao_assumiu' => $naoAssumiu,
                    'pendencia_documentos' => $pendencia,
                    'documentos_validados' => $docsValidated,
                    'pendente_confirmacao_cpm' => $pendenteCpm,
                ];
            } catch (\Throwable $e) {
                Log::warning('computeStatsForConv failed', ['exception' => $e->getMessage()]);
                return [
                    'total_candidates' => 0,
                    'ingressados' => 0,
                    'corrigir_documentacao' => 0,
                    'nao_assumiu' => 0,
                    'pendencia_documentos' => 0,
                    'documentos_validados' => 0,
                    'pendente_confirmacao_cpm' => 0,
                ];
            }
        };

        // Build NTE breakdown by choosing an available grouping column
        $groupCol = null;
        foreach (['nte','unidade_organizacional','uee_municipio','uee_code','uee_name'] as $c) {
            if (in_array($c, $available)) { $groupCol = $c; break; }
        }

        $nte_breakdown = [];
        if ($groupCol) {
            $qb = DB::table($table)
                ->select($groupCol . ' as nte', DB::raw('COUNT(*) as count'))
                ->groupBy($groupCol);

            // If current user is an NTE user, restrict the breakdown to their NTE
            try {
                $u = Auth::user();
                if ($u && isset($u->profile_id) && $u->profile_id == 1 && isset($u->sector_id) && $u->sector_id == 7) {
                    $userNte = $u->nte ?? null;
                    if ($userNte) {
                        $cols = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
                        if (in_array('nte', $cols)) {
                            $qb->where('nte', $userNte);
                        } elseif (in_array('uee_code', $cols)) {
                            $qb->where('uee_code', $userNte);
                        } elseif (in_array('uee_name', $cols)) {
                            $qb->where('uee_name', $userNte);
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to scope NTE breakdown to user NTE', ['exception' => $e->getMessage()]);
            }

            // NOTE: previously the NTE breakdown was restricted to candidates
            // that have entries in `provimentos_encaminhados`. That filter
            // prevented some NTEs from appearing. We remove it to show all
            // NTEs (the per-user NTE scoping above still applies for NTE users).
            // Try numeric order (1,2,3...) when column holds numeric codes, fallback to alphabetical
            $colSafe = str_replace('`', '', $groupCol);
            try {
                $rows = (clone $qb)->orderByRaw("CAST(`$colSafe` AS SIGNED) ASC")->get();
            } catch (\Throwable $e) {
                $rows = (clone $qb)->orderBy('nte', 'asc')->get();
            }
            $nte_breakdown = $rows->map(function($r){ return ['nte' => $r->nte ?? '—', 'count' => intval($r->count)]; })->toArray();
        }

        // Prepare aptos lists split by status_convocacao (1 and 2) for dashboard tabs
        $aptos_conv1 = collect();
        $aptos_conv2 = collect();
        try {
            $table = 'ingresso_candidatos';
            $availableCols = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
            if (Schema::hasTable($table) && in_array('status_convocacao', $availableCols)) {
                $baseCols = ['id','name','nome','cpf','nte','matricula','num_inscricao','status'];
                $selectCols = array_values(array_intersect($baseCols, $availableCols));
                if (empty($selectCols)) $selectCols = ['id'];

                $u = Auth::user();
                $userNte = $u->nte ?? null;

                $q1 = DB::table($table)->select($selectCols)->where('status_convocacao', 1);
                $q2 = DB::table($table)->select($selectCols)->where('status_convocacao', 2);

                if ($userNte) {
                    if (in_array('nte', $availableCols)) {
                        $q1->where('nte', $userNte);
                        $q2->where('nte', $userNte);
                    } elseif (in_array('uee_code', $availableCols)) {
                        $q1->where('uee_code', $userNte);
                        $q2->where('uee_code', $userNte);
                    } elseif (in_array('uee_name', $availableCols)) {
                        $q1->where('uee_name', $userNte);
                        $q2->where('uee_name', $userNte);
                    }
                }

                $aptos_conv1 = $q1->orderBy('name','asc')->get();
                $aptos_conv2 = $q2->orderBy('name','asc')->get();
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to build aptos convocation lists', ['exception' => $e->getMessage()]);
        }

        // Compute per-convocation stats and NTE breakdowns
        $stats_conv1 = $computeStatsForConv(1);
        $stats_conv2 = $computeStatsForConv(2);

        $nte_breakdown_conv1 = $nte_breakdown_conv2 = [];
        if (!empty($groupCol)) {
            try {
                $cols = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
                $qb1 = DB::table($table)->select($groupCol . ' as nte', DB::raw('COUNT(*) as count'))->where('status_convocacao', 1)->groupBy($groupCol);
                $qb2 = DB::table($table)->select($groupCol . ' as nte', DB::raw('COUNT(*) as count'))->where('status_convocacao', 2)->groupBy($groupCol);

                // apply user NTE scoping if present
                try {
                    $u = Auth::user();
                    $userNte = $u->nte ?? null;
                    if ($userNte) {
                        if (in_array('nte', $cols)) { $qb1->where('nte', $userNte); $qb2->where('nte', $userNte); }
                        elseif (in_array('uee_code', $cols)) { $qb1->where('uee_code', $userNte); $qb2->where('uee_code', $userNte); }
                        elseif (in_array('uee_name', $cols)) { $qb1->where('uee_name', $userNte); $qb2->where('uee_name', $userNte); }
                    }
                } catch (\Throwable $e) {}

                try { $rows1 = (clone $qb1)->orderByRaw("CAST(`$groupCol` AS SIGNED) ASC")->get(); } catch (\Throwable $e) { $rows1 = (clone $qb1)->orderBy($groupCol,'asc')->get(); }
                try { $rows2 = (clone $qb2)->orderByRaw("CAST(`$groupCol` AS SIGNED) ASC")->get(); } catch (\Throwable $e) { $rows2 = (clone $qb2)->orderBy($groupCol,'asc')->get(); }

                $nte_breakdown_conv1 = $rows1->map(function($r){ return ['nte' => $r->nte ?? '—', 'count' => intval($r->count)]; })->toArray();
                $nte_breakdown_conv2 = $rows2->map(function($r){ return ['nte' => $r->nte ?? '—', 'count' => intval($r->count)]; })->toArray();
            } catch (\Throwable $e) {
                Log::warning('Failed to compute NTE breakdown per convocation', ['exception' => $e->getMessage()]);
            }
        }

        Log::info('ingresso.dashboard stats', $stats);
        // Prepare COP summary for dashboard (moved from view)
        $copNumber = null; $copQuantity = 0; $candidatesCount = 0; $copFree = 0;
        try {
            $cols = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
            // Find most common COP in ingresso_candidatos when column exists
            if (in_array('cop', $cols)) {
                $most = DB::table('ingresso_candidatos')
                    ->select('cop', DB::raw('COUNT(*) as cnt'))
                    ->whereNotNull('cop')
                    ->groupBy('cop')
                    ->orderByDesc('cnt')
                    ->first();
                $copNumber = $most->cop ?? null;
            }

            // If the specific COP 389/2025 exists in num_cop, prefer it
            if (Schema::hasTable('num_cop')) {
                try {
                    $exists389 = DB::table('num_cop')->where('num', '389/2025')->exists();
                    if ($exists389) {
                        $copNumber = '389/2025';
                    }
                } catch (\Throwable $__e) {
                    // ignore
                }

                // fallback to first available num in num_cop table if still null
                if (!$copNumber) {
                    $copNumber = DB::table('num_cop')->value('num');
                }
            }

            // fetch quantity from num_cop using correct column 'num'
            if ($copNumber && Schema::hasTable('num_cop')) {
                $numcop = DB::table('num_cop')->where('num', $copNumber)->first();
                if ($numcop) {
                    // support possible column names
                    $copQuantity = intval($numcop->quantidade ?? $numcop->quantity ?? $numcop->qtd ?? $numcop->quantidade_atual ?? 0);
                }
            }

            // Candidatos atribuídos: use total count of rows in ingresso_candidatos
            // The view applies the -18 adjustment when showing "Candidatos atribuídos"
            if (Schema::hasTable('ingresso_candidatos')) {
                $candidatesCount = DB::table('ingresso_candidatos')->count();
            }

            $copFree = max(0, $copQuantity - $candidatesCount);
        } catch (\Throwable $e) {
            Log::warning('Failed to compute COP summary for dashboard', ['exception' => $e->getMessage()]);
        }

        return view('ingresso.dashboard', [
            'stats' => $stats,
            'nte_breakdown' => $nte_breakdown,
            'aptos_conv1' => $aptos_conv1,
            'aptos_conv2' => $aptos_conv2,
            'stats_conv1' => $stats_conv1,
            'stats_conv2' => $stats_conv2,
            'nte_breakdown_conv1' => $nte_breakdown_conv1,
            'nte_breakdown_conv2' => $nte_breakdown_conv2,
            'copNumber' => $copNumber,
            'copQuantity' => $copQuantity,
            'candidatesCount' => $candidatesCount,
            'copFree' => $copFree,
        ]);
    }

    /**
     * Página que lista candidatos aptos para ingresso
     */
    public function aptos(Request $request)
    {
        if (! $this->authorizeUser()) {
            abort(403);
        }

        $table = 'ingresso_candidatos';
        $available = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];

        if (! Schema::hasTable($table)) {
            return redirect()->route('ingresso.dashboard')->with('status', 'Tabela de candidatos indisponível.');
        }

        $query = DB::table($table);

        // Se for usuário NTE, faça scope para o NTE dele
        try {
            $u = Auth::user();
            if ($u && isset($u->profile_id) && $u->profile_id == 1 && isset($u->sector_id) && $u->sector_id == 7) {
                $userNte = $u->nte ?? null;
                if ($userNte) {
                    if (in_array('nte', $available)) {
                        $query->where('nte', $userNte);
                    } elseif (in_array('uee_code', $available)) {
                        $query->where('uee_code', $userNte);
                    } elseif (in_array('uee_name', $available)) {
                        $query->where('uee_name', $userNte);
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore scoping failures
        }

        // Determine convocacao filter: prefer explicit query param, then session
        try {
            $filterConv = null;
            $reqConv = $request->query('filter_convocacao', null);
            if ($reqConv !== null && $reqConv !== '') {
                if (is_numeric($reqConv)) $reqConv = intval($reqConv);
                $filterConv = $reqConv;
                // persist user's choice in session so other pages can reuse it
                session(['filter_convocacao' => $filterConv]);
            } else {
                $sessConv = session('filter_convocacao', null);
                if ($sessConv !== null && $sessConv !== '') {
                    if (is_numeric($sessConv)) $sessConv = intval($sessConv);
                    $filterConv = $sessConv;
                }
            }

            if ($filterConv !== null && in_array('status_convocacao', $available)) {
                $query->where('status_convocacao', $filterConv);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Filtrar candidatos aptos: priorize coluna `status` quando existir
        try {
            if (in_array('status', $available)) {
                $query->whereRaw("LOWER(status) = 'apto para encaminhamento' OR LOWER(status) LIKE '%apto%'");
            } elseif (Schema::hasTable('ingresso_documentos')) {
                // fallback: considerar aptos aqueles com todos os documentos validados
                $sub = DB::table('ingresso_documentos')
                    ->select('ingresso_candidato_id', DB::raw('SUM(validated) as validated_count'), DB::raw('COUNT(*) as total'))
                    ->groupBy('ingresso_candidato_id');

                $query->joinSub($sub, 'ds', function($join) {
                    $join->on('ingresso_candidatos.id', '=', 'ds.ingresso_candidato_id');
                })->whereRaw('ds.total > 0 AND ds.validated_count = ds.total');
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to build aptos query', ['exception' => $e->getMessage()]);
        }

        $desired = ['id','num_inscricao','name','nome','cpf','nte','matricula','status','documentos_validados'];
        $select = array_values(array_intersect($desired, $available));
        if (empty($select)) $select = ['*'];

        $rows = $query->select($select)->orderBy('name')->get();

        return view('ingresso.aptos', ['aptos_ingresso' => $rows]);
    }

    /**
     * Show form to encaminhar (forward) a candidate to a school/discipline
     */
    public function encaminharForm($identifier)
    {
        if (! $this->authorizeUser()) {
            abort(403);
        }

        $candidate = DB::table('ingresso_candidatos')
            ->where('id', $identifier)
            ->orWhere('num_inscricao', $identifier)
            ->first();

        if (! $candidate) {
            return redirect()->route('ingresso.aptos')->with('status', 'Candidato não encontrado');
        }

        // fetch available unidades escolares and disciplinas
        $uees = [];
        $disciplinas = [];
       
                // include NTE and municipio so the view options have data attributes
                $uees = Uee::select('id','unidade_escolar','cod_unidade','nte','municipio')
                    ->orderBy('unidade_escolar')
                    ->get();
     

        try {
            if (class_exists(\App\Models\Disciplina::class)) {
                $disciplinas = \App\Models\Disciplina::select('id','nome')->orderBy('nome')->get();
            }
        } catch (\Throwable $e) { $disciplinas = collect(); }

        // attempt to load the most recent encaminhamento for this candidate (if any)
        $lastEncaminhamento = null;
        try {
            if (Schema::hasTable('ingresso_encaminhamentos')) {
                $lastEncaminhamento = DB::table('ingresso_encaminhamentos')
                    ->where('ingresso_candidato_id', $candidate->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
        } catch (\Throwable $e) {
            $lastEncaminhamento = null;
        }

        // If last encaminhamento exists but lacks nte/municipio, try to enrich it from the Uee record
        try {
            if ($lastEncaminhamento && (empty($lastEncaminhamento->uee_nte) || empty($lastEncaminhamento->uee_municipio))) {
                $ueeCode = $lastEncaminhamento->uee_code ?? $lastEncaminhamento->uee_id ?? null;
                if ($ueeCode) {
                    // try by cod_unidade first then id
                    $uee = Uee::where('cod_unidade', $ueeCode)->orWhere('id', $ueeCode)->first();
                    if ($uee) {
                        if (empty($lastEncaminhamento->uee_nte) && (isset($uee->nte) || isset($uee->nte_nome))) {
                            $lastEncaminhamento->uee_nte = $uee->nte ?? $uee->nte_nome ?? null;
                        }
                        if (empty($lastEncaminhamento->uee_municipio) && (isset($uee->municipio) || isset($uee->municipio_nome))) {
                            $lastEncaminhamento->uee_municipio = $uee->municipio ?? $uee->municipio_nome ?? null;
                        }
                        if (empty($lastEncaminhamento->uee_name) && (isset($uee->unidade_escolar))) {
                            $lastEncaminhamento->uee_name = $uee->unidade_escolar;
                        }
                        if (empty($lastEncaminhamento->uee_codigo) && (isset($uee->cod_unidade))) {
                            $lastEncaminhamento->uee_codigo = $uee->cod_unidade;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore enrichment failures
        }

        return view('ingresso.encaminhar', ['candidate' => $candidate, 'uees' => $uees, 'disciplinas' => $disciplinas, 'last_encaminhamento' => $lastEncaminhamento]);
    }

    /**
     * DataTables server-side endpoint for ingresso_candidatos
     */
    public function data(Request $request)
    {
        try {
            if (! $this->authorizeUser()) {
                Log::warning('IngressoController::data access denied for user', ['user_id' => optional(Auth::user())->id]);
                return response()->json(['message' => 'Forbidden'], 403);
            }

            $columns = Schema::hasTable('ingresso_candidatos') ? Schema::getColumnListing('ingresso_candidatos') : [];

            $draw = intval($request->get('draw'));
            $start = intval($request->get('start', 0));
            $length = intval($request->get('length', 10));
            // robustly extract DataTables search value which may come as array('value' => '...')
            $searchValue = '';
            $searchInput = $request->get('search');
            if (is_array($searchInput)) {
                $searchValue = trim($searchInput['value'] ?? '');
            } else {
                // fallbacks: dotted input or simple query param
                $searchValue = trim($request->input('search.value') ?? $request->query('search') ?? '');
            }

            // total records
            $totalRecords = DB::table('ingresso_candidatos')->count();

            // filtered
            // ensure we explicitly select critical columns but only those that exist in the table
            // include both legacy and current column names for racial quota to be safe
            $desired = ['id','num_inscricao','name','cpf','nte','disciplina','municipio_convocacao','classificacao_ampla','classificacao_quota_pne','classificacao_quota_racial','classificacao_racial','classificacao','nota','sei_number','status','documentos_validados'];
            $available = Schema::hasTable('ingresso_candidatos') ? Schema::getColumnListing('ingresso_candidatos') : [];
            $selectCols = array_values(array_intersect($desired, $available));

            // Build base query and ensure we always return a `classificacao_quota_racial` column
            $filteredQuery = DB::table('ingresso_candidatos');
            if (!empty($selectCols)) {
                $filteredQuery->select($selectCols);
            }

            // Ensure classificacao_quota_racial is present: COALESCE from alternative columns that exist
            $raceCandidates = array_values(array_filter(['classificacao_quota_racial','classificacao_racial','classificacao'], function($c) use ($available){ return in_array($c, $available); }));
            if (count($raceCandidates) > 0) {
                // build safe COALESCE, then alias to classificacao_quota_racial
                $coalesceExpr = implode(', ', array_map(function($c){ return $c; }, $raceCandidates));
                $filteredQuery->addSelect(DB::raw("COALESCE($coalesceExpr, '') as classificacao_quota_racial"));
            } else {
                // no columns available, still add empty alias so frontend gets the key
                $filteredQuery->addSelect(DB::raw("'' as classificacao_quota_racial"));
            }
            // If the authenticated user has an `nte` attribute, restrict results to that NTE.
            // This makes NTE-scoped accounts (users assigned to an NTE) only see their NTE.
            try {
                $u = Auth::user();
                $userNte = $u->nte ?? null;
                if ($userNte) {
                    if (in_array('nte', $columns)) {
                        $filteredQuery->where('nte', $userNte);
                    } elseif (in_array('uee_code', $columns)) {
                        $filteredQuery->where('uee_code', $userNte);
                    } elseif (in_array('uee_name', $columns)) {
                        $filteredQuery->where('uee_name', $userNte);
                    }
                }
            } catch (\Throwable $e) {
                // ignore and continue without NTE scoping
            }

            if ($searchValue && count($columns)) {
                // Prefer searching only the visible/requested DataTables columns (sent in request.columns)
                $requested = $request->get('columns');
                $requestedCols = [];
                if (is_array($requested)) {
                    foreach ($requested as $rc) {
                        if (is_array($rc) && isset($rc['data'])) $requestedCols[] = $rc['data'];
                        elseif (is_object($rc) && isset($rc->data)) $requestedCols[] = $rc->data;
                    }
                }
                $requestedCols = array_values(array_filter($requestedCols));

                // restrict requested columns to those that actually exist in the DB table
                $requestedCols = array_values(array_intersect($requestedCols, $columns));

                // common textual columns used as a safe fallback
                $commonTextCols = ['num_inscricao','name','cpf','sei_number','nota','email','telefone','celular','nome_mae','rg'];

                // Determine which columns to search: prefer intersection of requested columns and textual columns
                $colsToSearch = array_values(array_intersect($requestedCols, $commonTextCols));

                if (empty($colsToSearch)) {
                    // If no textual requested columns, try any requested DB columns
                    $colsToSearch = $requestedCols;
                }
                if (empty($colsToSearch)) {
                    // Next fallback: any common textual columns that exist in DB
                    $colsToSearch = array_values(array_intersect($commonTextCols, $columns));
                }
                if (empty($colsToSearch)) {
                    // Ultimate fallback: search all columns
                    $colsToSearch = $columns;
                }

                $filteredQuery->where(function ($q) use ($colsToSearch, $searchValue) {
                    foreach ($colsToSearch as $col) {
                        $q->orWhere($col, 'like', "%{$searchValue}%");
                    }
                });
            }

            // Apply client-side filters (if provided): filter_nte, filter_status
            try {
                $filterNte = trim((string) ($request->query('filter_nte') ?? ''));
                if ($filterNte !== '') {
                    if (in_array('nte', $columns)) {
                        $filteredQuery->where('nte', $filterNte);
                    } elseif (in_array('uee_code', $columns)) {
                        $filteredQuery->where('uee_code', $filterNte);
                    } elseif (in_array('uee_name', $columns)) {
                        $filteredQuery->where('uee_name', $filterNte);
                    } else {
                        $filteredQuery->where('nte', $filterNte);
                    }
                }

                $filterStatus = trim((string) ($request->query('filter_status') ?? ''));
                if ($filterStatus !== '') {
                    $low = mb_strtolower($filterStatus, 'UTF-8');
                    if (in_array('status', $columns)) {
                        if (mb_strpos($low, 'corrig') !== false) {
                            $filteredQuery->whereRaw("LOWER(status) LIKE '%corrig%'");
                        } elseif (mb_strpos($low, 'apto') !== false || mb_strpos($low, 'ingress') !== false || mb_strpos($low, 'ingresso') !== false) {
                            $filteredQuery->whereRaw("LOWER(status) = 'apto para encaminhamento'");
                        } elseif (mb_strpos($low, 'valid') !== false) {
                            $filteredQuery->whereRaw("LOWER(status) LIKE '%valid%'");
                        } elseif (mb_strpos($low, 'pend') !== false) {
                            $filteredQuery->whereRaw("LOWER(status) LIKE '%pend%'");
                        } else {
                            $filteredQuery->whereRaw('LOWER(status) LIKE ?', ["%{$low}%"]);
                        }
                    } else {
                        // fallback to ingresso_documentos when status column not present
                        if (Schema::hasTable('ingresso_documentos')) {
                            if (mb_strpos($low, 'valid') !== false) {
                                $sub = DB::table('ingresso_documentos')
                                    ->select('ingresso_candidato_id', DB::raw('SUM(validated) as validated_count'), DB::raw('COUNT(*) as total'))
                                    ->groupBy('ingresso_candidato_id');
                                $filteredQuery->joinSub($sub, 'ds', function($join) {
                                    $join->on('ingresso_candidatos.id', '=', 'ds.ingresso_candidato_id');
                                })->whereRaw('ds.total > 0 AND ds.validated_count = ds.total');
                            } elseif (mb_strpos($low, 'pend') !== false) {
                                $subPending = DB::table('ingresso_documentos')
                                    ->select('ingresso_candidato_id', DB::raw('SUM(validated) as validated_count'), DB::raw('COUNT(*) as total'))
                                    ->groupBy('ingresso_candidato_id');
                                $filteredQuery->leftJoinSub($subPending, 'docsum', function($join) {
                                    $join->on('ingresso_candidatos.id', '=', 'docsum.ingresso_candidato_id');
                                })->where(function($q){
                                    $q->whereNull('docsum.total')->orWhereRaw('docsum.validated_count < docsum.total');
                                });
                            }
                        }
                    }
                }
                // Apply convocacao filter (status_convocacao) when provided
                $filterConv = trim((string) ($request->query('filter_convocacao') ?? ''));
                if ($filterConv !== '') {
                    // only apply if the column exists
                    if (in_array('status_convocacao', $columns)) {
                        // accept numeric or string values; cast to int when possible
                        if (is_numeric($filterConv)) {
                            $filteredQuery->where('status_convocacao', intval($filterConv));
                        } else {
                            $filteredQuery->where('status_convocacao', $filterConv);
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to apply client filters in ingresso.data', ['exception' => $e->getMessage()]);
            }

            // NOTE: removed the restriction that limited results to candidates
            // present in `provimentos_encaminhados`. We want to list all
            // candidates (still scoped to the user's NTE above).
            /*
            try {
                if (Schema::hasTable('provimentos_encaminhados')) {
                    $encQuery = DB::table('provimentos_encaminhados')
                        ->select('ingresso_candidato_id')
                        ->whereNotNull('ingresso_candidato_id')
                        ->distinct();
                    $filteredQuery->whereIn('id', $encQuery);
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to apply encaminhados filter in ingresso data endpoint', ['exception' => $e->getMessage()]);
            }
            */

            $recordsFiltered = $filteredQuery->count();

            // ordering: allow client to request a default server-side ordering via `order_by` when
            // no explicit DataTables `order` array is provided. Otherwise, try to map
            // DataTables column index to DB columns (best-effort).
            $order = $request->get('order', []);
            $orderByOverride = trim((string) ($request->query('order_by') ?? $request->input('order_by') ?? ''));
            $orderDirOverride = trim((string) ($request->query('order_dir') ?? $request->input('order_dir') ?? ''));
            if (empty($order) && $orderByOverride !== '') {
                if (in_array($orderByOverride, $available)) {
                    $filteredQuery->orderBy($orderByOverride, ($orderDirOverride === 'desc' ? 'desc' : 'asc'));
                }
            } else {
                if (!empty($order) && isset($order[0]['column'])) {
                    $orderColIndex = intval($order[0]['column']);
                    $orderDir = $order[0]['dir'] === 'desc' ? 'desc' : 'asc';
                    if (isset($columns[$orderColIndex])) {
                        $filteredQuery->orderBy($columns[$orderColIndex], $orderDir);
                    }
                }
            }

            // pagination and fetch
            $data = $filteredQuery->offset($start)->limit($length)->get();

            // compute status per candidate in batch to avoid N+1 queries
            try {
                $rows = collect($data);
                $ids = $rows->pluck('id')->filter()->unique()->values()->all();
                $docSummary = collect();
                if (count($ids) && Schema::hasTable('ingresso_documentos')) {
                    $docSummary = DB::table('ingresso_documentos')
                        ->select('ingresso_candidato_id', DB::raw('SUM(validated) as validated_count'), DB::raw('COUNT(*) as total'))
                        ->whereIn('ingresso_candidato_id', $ids)
                        ->groupBy('ingresso_candidato_id')
                        ->get()
                        ->keyBy('ingresso_candidato_id');
                }

                // Precompute latest ingresso_encaminhamentos.status per candidate to avoid N+1 queries
                $encMap = collect();
                try {
                    if (count($ids) && Schema::hasTable('ingresso_encaminhamentos')) {
                        $encRows = DB::table('ingresso_encaminhamentos')
                            ->whereIn('ingresso_candidato_id', $ids)
                            ->orderBy('created_at', 'desc')
                            ->get()
                            ->groupBy('ingresso_candidato_id')
                            ->map(function($g){
                                // take the newest by created_at
                                return $g->sortByDesc('created_at')->first();
                            });
                        $encMap = $encRows->mapWithKeys(function($v, $k){
                            return [$k => ['id' => $v->id ?? null, 'status' => $v->status ?? null]];
                        });
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to load ingresso_encaminhamentos in data endpoint', ['exception' => $e->getMessage()]);
                }

                $data = $rows->map(function($r) use ($docSummary, $encMap) {
                    $row = (array) $r;
                    $status = null;

                    // Prefer explicit DB status when present
                    if (isset($row['status']) && $row['status']) {
                        $status = $row['status'];
                    } else {
                        // if we have document summary for this candidate, derive status from documents
                        $summary = $docSummary->get($row['id']);
                        if ($summary) {
                            $total = intval($summary->total);
                            $validated = intval($summary->validated_count);
                            if ($total > 0 && $validated === $total) {
                                // all documents uploaded/checked -> awaiting CPM confirmation
                                $status = 'Aguardando Confirmação pela CPM';
                            } else {
                                // Not all documents validated
                                $status = 'Documentos Pendentes';
                            }
                        } else {
                            // no per-document summary; fall back to documentos_validados flag or default pending
                            if (isset($row['documentos_validados']) && ($row['documentos_validados'] == 1 || $row['documentos_validados'] === true)) {
                                $status = 'Aguardando Confirmação pela CPM';
                            } else {
                                $status = 'Documentos Pendentes';
                            }
                        }
                    }

                    $row['status'] = $status;
                    // attach encaminhamento status if available
                    $row['encaminhamento_status'] = null;
                    $row['encaminhamento_id'] = null;
                    try {
                        if (isset($row['id']) && $encMap && isset($encMap[$row['id']])) {
                            $enc = $encMap[$row['id']];
                            $row['encaminhamento_status'] = $enc['status'] ?? null;
                            $row['encaminhamento_id'] = $enc['id'] ?? null;
                        }
                    } catch (\Throwable $e) {}
                    return (object) $row;
                })->values();
            } catch (\Throwable $e) {
                Log::warning('Failed to compute ingresso status in data endpoint', ['exception' => $e->getMessage()]);
            }

            Log::info('IngressoController::data served', [
                'user_id' => optional(Auth::user())->id,
                'draw' => $draw,
                'start' => $start,
                'length' => $length,
                'search' => $searchValue,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
            ]);

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('IngressoController::data error', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    /**
     * Save selected convocacao in session (AJAX)
     */
    public function setConvocacaoSession(Request $request)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $conv = $request->input('convocacao');
        if ($conv === null) {
            return response()->json(['success' => false, 'message' => 'Missing convocacao'], 422);
        }

        // accept numeric strings as ints
        if (is_numeric($conv)) $conv = intval($conv);

        // basic validation (most sites only have 1 or 2)
        if (! in_array($conv, [1,2], true)) {
            // still allow storing other values but be conservative
            return response()->json(['success' => false, 'message' => 'Invalid convocacao'], 422);
        }

        session(['filter_convocacao' => $conv]);

        return response()->json(['success' => true, 'convocacao' => $conv]);
    }

    /**
     * Search ingresso_candidatos by CPF using partial match (contains).
     * Returns first matching candidate (most recent) as JSON.
     */
    public function searchByCpf(Request $request)
    {
        // Allow any authenticated user to perform CPF lookup from the provimento form.
        if (! Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $q = (string) ($request->input('cpf') ?? '');
        $q = preg_replace('/[^0-9]/', '', $q); // normalize digits only
        if ($q === '') {
            return response()->json(['success' => false, 'message' => 'CPF vazio'], 422);
        }

        $table = 'ingresso_candidatos';
        if (!Schema::hasTable($table)) {
            return response()->json(['success' => false, 'message' => 'Tabela não encontrada'], 500);
        }

        // perform partial match on cpf column (contains)
        $query = DB::table($table)->where('cpf', 'like', "%{$q}%");

        // If current user is NTE, scope to their NTE when possible (same logic as other methods)
        try {
            $u = Auth::user();
            if ($u && isset($u->profile_id) && $u->profile_id == 1 && isset($u->sector_id) && $u->sector_id == 7) {
                $userNte = $u->nte ?? null;
                if ($userNte) {
                    $cols = Schema::getColumnListing($table);
                    if (in_array('nte', $cols)) {
                        $query->where('nte', $userNte);
                    } elseif (in_array('uee_code', $cols)) {
                        $query->where('uee_code', $userNte);
                    } elseif (in_array('uee_name', $cols)) {
                        $query->where('uee_name', $userNte);
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $candidate = $query->orderBy('id', 'desc')->first();

        if (! $candidate) {
            return response()->json(['success' => false, 'message' => 'Não encontrado'], 404);
        }

        return response()->json(['success' => true, 'candidate' => [
            'id' => $candidate->id ?? null,
            'name' => $candidate->name ?? ($candidate->nome ?? ''),
            'num_inscricao' => $candidate->num_inscricao ?? null,
            'nte' => $candidate->nte ?? ($candidate->uee_code ?? $candidate->uee_name ?? null),
            'cpf' => $candidate->cpf ?? null,
        ]]);
    }

    /**
     * Export the ingresso listing as CSV honoring simple DataTables filters (search).
     * This exports all DB columns by default (so includes fields not shown in the table).
     */
    public function exportCsv(Request $request)
    {
        if (! $this->authorizeUser()) {
            abort(403);
        }

        $table = 'ingresso_candidatos';
        $available = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
        if (empty($available)) {
            return redirect()->back()->with('status', 'Tabela de candidatos indisponível para exportação.');
        }

        // Build base query
        $query = DB::table($table)->select($available);

        // apply simple search (DataTables uses 'search' param client-side)
        $search = $request->query('search');
        if ($search && is_string($search)) {
            $query->where(function($q) use ($available, $search) {
                foreach ($available as $col) {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
            });
        }

        // (Filter moved below to ensure it is applied after any potential $query reassignment)

        // Optional: allow passing explicit columns list (comma separated) to limit exported columns
        $colsParam = $request->query('cols');
        $cols = $available;
        $syntheticCols = [];
        if ($colsParam && is_string($colsParam)) {
            $desired = array_filter(array_map('trim', explode(',', $colsParam)));
            // Keep requested columns even if they don't exist in DB (they will be treated as synthetic)
            $present = array_values(array_intersect($desired, $available));
            $missing = array_values(array_diff($desired, $available));
            $cols = array_merge($present, $missing);
            if (!empty($present)) {
                // rebuild query selecting only requested present columns
                $query = DB::table($table)->select($present);
            } else {
                // no present columns requested - keep base query (select all) but we'll only output the requested synthetic columns
                $query = DB::table($table)->select($available);
            }
            // mark missing columns as synthetic (will be filled with '-')
            $syntheticCols = $missing;
        }

        // Always include a synthetic column `tipos_servidor` with default value 9
        if (!in_array('tipos_servidor', $cols)) {
            $pos = array_search('estado_civil', $cols, true);
            if ($pos !== false) {
                // insert after estado_civil
                $before = array_slice($cols, 0, $pos + 1);
                $after = array_slice($cols, $pos + 1);
                $cols = array_merge($before, ['tipos_servidor'], $after);
            } else {
                $cols[] = 'tipos_servidor';
            }
        }
        if (!in_array('tipos_servidor', $syntheticCols)) {
            $syntheticCols[] = 'tipos_servidor';
        }

        // Always include a synthetic column `status_do_curso` with default value '00'
        if (!in_array('status_do_curso', $cols)) {
            $pos2 = array_search('formacao', $cols, true);
            if ($pos2 !== false) {
                // insert after formacao
                $before2 = array_slice($cols, 0, $pos2 + 1);
                $after2 = array_slice($cols, $pos2 + 1);
                $cols = array_merge($before2, ['status_do_curso'], $after2);
            } else {
                $cols[] = 'status_do_curso';
            }
        }
        if (!in_array('status_do_curso', $syntheticCols)) {
            $syntheticCols[] = 'status_do_curso';
        }

        // Always include a synthetic column `pais` with default value 'BR'
        if (!in_array('pais', $cols)) {
            $cols[] = 'pais';
        }
        if (!in_array('pais', $syntheticCols)) {
            $syntheticCols[] = 'pais';
        }

        // Always include a synthetic column `raca` after `num_inscricao`, blank for now
        if (!in_array('raca', $cols)) {
            $posR = array_search('num_inscricao', $cols, true);
            if ($posR !== false) {
                $beforeR = array_slice($cols, 0, $posR + 1);
                $afterR = array_slice($cols, $posR + 1);
                $cols = array_merge($beforeR, ['raca'], $afterR);
            } else {
                $cols[] = 'raca';
            }
        }
        if (!in_array('raca', $syntheticCols)) {
            $syntheticCols[] = 'raca';
        }

        // Always include a synthetic column `numero_do_candidato` after `raca`, default 0
        if (!in_array('numero_do_candidato', $cols)) {
            $posN = array_search('raca', $cols, true);
            if ($posN !== false) {
                $beforeN = array_slice($cols, 0, $posN + 1);
                $afterN = array_slice($cols, $posN + 1);
                $cols = array_merge($beforeN, ['numero_do_candidato'], $afterN);
            } else {
                $cols[] = 'numero_do_candidato';
            }
        }
        if (!in_array('numero_do_candidato', $syntheticCols)) {
            $syntheticCols[] = 'numero_do_candidato';
        }

        // Always include a synthetic column `deficiencia_pne` after `email`, blank for now
        if (!in_array('deficiencia_pne', $cols)) {
            $pos3 = array_search('email', $cols, true);
            if ($pos3 !== false) {
                $before3 = array_slice($cols, 0, $pos3 + 1);
                $after3 = array_slice($cols, $pos3 + 1);
                $cols = array_merge($before3, ['deficiencia_pne'], $after3);
            } else {
                $cols[] = 'deficiencia_pne';
            }
        }
        if (!in_array('deficiencia_pne', $syntheticCols)) {
            $syntheticCols[] = 'deficiencia_pne';
        }

        // Always include a synthetic column `profissao` after `nome_mae`, blank for now
        if (!in_array('profissao', $cols)) {
            $pos4 = array_search('nome_mae', $cols, true);
            if ($pos4 !== false) {
                $before4 = array_slice($cols, 0, $pos4 + 1);
                $after4 = array_slice($cols, $pos4 + 1);
                $cols = array_merge($before4, ['profissao'], $after4);
            } else {
                $cols[] = 'profissao';
            }
        }
        if (!in_array('profissao', $syntheticCols)) {
            $syntheticCols[] = 'profissao';
        }

        // Ensure we only export validated candidates: prefer `status='Apto para encaminhamento'`,
        // otherwise fallback to `documentos_validados == 1` when `status` column missing.
        if (Schema::hasColumn($table, 'status')) {
            $query->whereRaw("LOWER(status) = 'apto para encaminhamento'");
        } elseif (Schema::hasColumn($table, 'documentos_validados')) {
            $query->where('documentos_validados', 1);
        }

        // If the current user is an NTE user, restrict exported rows to their NTE
        try {
            $u = Auth::user();
            if ($u && isset($u->profile_id) && $u->profile_id == 1 && isset($u->sector_id) && $u->sector_id == 7) {
                $userNte = $u->nte ?? null;
                if ($userNte) {
                    if (Schema::hasColumn($table, 'nte')) {
                        $query->where('nte', $userNte);
                    } elseif (Schema::hasColumn($table, 'uee_code')) {
                        $query->where('uee_code', $userNte);
                    } elseif (Schema::hasColumn($table, 'uee_name')) {
                        $query->where('uee_name', $userNte);
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore and continue without scoping
        }

        // Restrict exported rows to candidates that have been encaminhados
        try {
            if (Schema::hasTable('provimentos_encaminhados')) {
                $encQuery = DB::table('provimentos_encaminhados')
                    ->select('ingresso_candidato_id')
                    ->whereNotNull('ingresso_candidato_id')
                    ->distinct();
                $query->whereIn('id', $encQuery);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to apply encaminhados filter in ingresso exportCsv', ['exception' => $e->getMessage()]);
        }

        $rows = $query->orderBy('num_inscricao')->get();

        $filename = 'ingresso_export_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=Windows-1252',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($rows, $cols, $syntheticCols) {
            $out = fopen('php://output', 'w');
            // header (allow custom label for tipos_servidor; default to uppercased column key)
            $labelMap = [
                'tipos_servidor' => 'Tipos do Servidor',
                'status_do_curso' => 'Status do Curso',
                'pais' => 'Pais',
                'deficiencia_pne' => 'Deficiencia - PNE',
                'profissao' => 'Profissão',
                'raca' => 'Raça',
                'numero_do_candidato' => 'Numero do Candidato',
            ];
            $header = array_map(function($c) use ($labelMap) {
                if (isset($labelMap[$c])) return $labelMap[$c];
                return strtoupper(str_replace('_',' ', $c));
            }, $cols);
            // convert header encoding
            $header = array_map(function($v){ return mb_convert_encoding((string)$v, 'Windows-1252', 'UTF-8'); }, $header);
            // use semicolon as separator which Excel (pt-BR) recognizes as column separator
            fputcsv($out, $header, ';');
            foreach ($rows as $r) {
                $line = [];
                foreach ($cols as $c) {
                    if (in_array($c, $syntheticCols)) {
                        if ($c === 'tipos_servidor') {
                            $val = '9';
                        } elseif ($c === 'status_do_curso') {
                            // export as two zeros (no apostrophe)
                            $val = '00';
                        } elseif ($c === 'pais') {
                            $val = 'BR';
                        } elseif ($c === 'deficiencia_pne') {
                            // leave blank for now
                            $val = '';
                        } elseif ($c === 'profissao') {
                            // leave blank for now
                            $val = '';
                        } elseif ($c === 'raca') {
                            // leave blank for now
                            $val = '';
                        } elseif ($c === 'numero_do_candidato') {
                            $val = '0';
                        } else {
                            $val = '-';
                        }
                    } else {
                        $raw = isset($r->{$c}) ? $r->{$c} : '';
                        // special mapping for sexo: Masculino -> 1, Feminino -> 2
                        if ($c === 'sexo') {
                            $norm = mb_strtolower(trim((string)$raw));
                            if ($norm === 'masculino' || $norm === 'm' || $norm === '1') {
                                $val = '1';
                            } elseif ($norm === 'feminino' || $norm === 'f' || $norm === '2') {
                                $val = '2';
                            } else {
                                $val = $raw;
                            }
                        } elseif ($c === 'estado_civil') {
                            $norm = mb_strtolower(trim((string)$raw));
                            if ($norm === 'solteiro' || $norm === '0') {
                                $val = '0';
                            } elseif ($norm === 'casado' || $norm === '1') {
                                $val = '1';
                            } elseif ($norm === 'separado' || $norm === '2') {
                                $val = '2';
                            } elseif ($norm === 'divorciado' || $norm === '3') {
                                $val = '3';
                            } elseif ($norm === 'outros' || $norm === 'outro' || $norm === '4') {
                                $val = '4';
                            } else {
                                $val = $raw;
                            }
                        } elseif ($c === 'nacionalidade') {
                            $norm = mb_strtolower(trim((string)$raw));
                            if ($norm === 'brasileira' || $norm === 'brasil' || $norm === 'br') {
                                $val = 'BR';
                            } else {
                                $val = $raw;
                            }
                        } elseif ($c === 'pais') {
                            // always export as BR regardless of DB value
                            $val = 'BR';
                        } else {
                            $val = $raw;
                        }
                    }
                    // normalize to string and convert encoding to Windows-1252
                    if (is_null($val)) $val = '';
                    if (is_array($val) || is_object($val)) {
                        $val = json_encode($val, JSON_UNESCAPED_UNICODE);
                    }
                    $line[] = mb_convert_encoding((string)$val, 'Windows-1252', 'UTF-8');
                }
                fputcsv($out, $line, ';');
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Destroy a candidate (AJAX)
     */
    public function destroy($id)
    {
        try {
            if (! $this->authorizeUser()) {
                Log::warning('IngressoController::destroy access denied', ['user_id' => optional(Auth::user())->id]);
                return response()->json(['message' => 'Forbidden'], 403);
            }

            $deleted = DB::table('ingresso_candidatos')->where('id', $id)->delete();

            if ($deleted) {
                Log::info('IngressoController::destroy deleted', ['user_id' => optional(Auth::user())->id, 'id' => $id]);
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Registro não encontrado'], 404);
        } catch (\Exception $e) {
            Log::error('IngressoController::destroy error', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Erro no servidor'], 500);
        }
    }

    /**
     * Show a single candidate's full data
     */
    public function show($identifier)
    {
        if (! $this->authorizeUser()) {
            abort(403);
        }

        $columns = Schema::hasTable('ingresso_candidatos') ? Schema::getColumnListing('ingresso_candidatos') : [];

        $candidate = DB::table('ingresso_candidatos')
            ->where('id', $identifier)
            ->orWhere('num_inscricao', $identifier)
            ->first();

        // normalize to array so subsequent code can use array access reliably
        if ($candidate) {
            $candidate = (array) $candidate;
        }

        if (! $candidate) {
            return redirect()->route('ingresso.index')->with('status', 'Registro não encontrado');
        }

        // prepare friendly labels and groups for corporate layout
        $labelMap = [
            'num_inscricao' => 'Nº Inscrição',
            'name' => 'Nome',
            'nome' => 'Nome',
            'cpf' => 'CPF',
            'rg' => 'RG',
            'data_nascimento' => 'Data Nascimento',
            'email' => 'E-mail',
            'telefone' => 'Telefone',
            'celular' => 'Celular',
            'nota' => 'Nota',
            'classificacao_ampla' => 'Classificação (Ampla)',
            'classificacao_quota_pne' => 'Classificação (Quota PNE)',
            'classificacao_quota_racial' => 'Classificacao (Quota Racial)',
            'classificacao_racial' => 'Classificação Racial',
            'documentos_validados' => 'Documentos Validados',
            'status' => 'Status',
        ];

        $groups = [
            'Dados Pessoais' => ['num_inscricao', 'name', 'nome', 'cpf', 'rg', 'data_nascimento'],
            'Contato' => ['email', 'telefone', 'celular'],
            'Classificação / Nota' => ['nota', 'classificacao_ampla', 'classificacao_quota_pne', 'classificacao_racial'],
            'Documentos' => ['documentos_validados', 'status'],
        ];

        // fetch unidades escolares for assignment dropdown (if model/table exists)
        $uees = [];
        try {
            $uees = Uee::select('id', 'unidade_escolar', 'cod_unidade')->orderBy('unidade_escolar', 'asc')->get();
        } catch (\Throwable $e) {
            Log::warning('Uee model not available or query failed', ['exception' => $e->getMessage()]);
            $uees = collect();
        }

        // default document list for checklist
        $documentList = [
            ['key' => 'cpf', 'label' => 'CPF'],
            ['key' => 'rg', 'label' => 'RG'],
            ['key' => 'certidao', 'label' => 'Certidão de Nascimento/Casamento'],
            ['key' => 'comprovante_residencia', 'label' => 'Comprovante de Residência'],
            ['key' => 'diploma', 'label' => 'Diploma / Histórico'],
            ['key' => 'dependente_doc', 'label' => 'Certidão de Nascimento ou RG do(s) dependente(s)'],
            
            
            ['key' => 'comprovante_bb', 'label' => 'Comprovante (extrato ou cartão) - Banco do Brasil'],
            ['key' => 'pis_pasep', 'label' => 'Original e cópia do PIS/PASEP (se inscrito)'],
            ['key' => 'carteira_trabalho', 'label' => 'Carteira de Trabalho (original e cópia)'],
            ['key' => 'certificado_reservista', 'label' => 'Certificado de Reservista (para homens)'],
            ['key' => 'ficha_cadastro', 'label' => 'Ficha de Cadastro (original)'],
            ['key' => 'email_confirmacao', 'label' => 'E-mail (confirmação/comprovante)'],
            ['key' => 'declaracao_etnia', 'label' => 'Declaração de Etnia'],
            ['key' => 'declaracao_bens', 'label' => 'Declaração de Bens'],
            ['key' => 'quitacao_eleitoral', 'label' => 'Quitação Eleitoral (fornecida pelo cartório eleitoral)'],
            ['key' => 'declaracao_acumulacao', 'label' => 'Declaração de Acumulação'],
            ['key' => 'declaracao_dependentes', 'label' => 'Declaração de Dependentes'],
            ['key' => 'aso', 'label' => 'Atestado de Saúde Ocupacional - ASO'],
            ['key' => 'certidao_negativa_condenacoes', 'label' => 'Certidão Negativa do Cadastro Nacional de Condenações Civeis'],
            ['key' => 'certidao_negativa_justica_eleitoral', 'label' => 'Certidão Negativa da Justiça Eleitoral'],
            ['key' => 'certidao_negativa_justica_militar_federal', 'label' => 'Certidão Negativa da Justiça Militar Federal'],
            ['key' => 'certidao_negativa_foros_federal_8_anos', 'label' => 'Certidão Negativa - Foros Criminais (Justiça Federal) - estados residiu nos últimos 8 anos'],
            ['key' => 'certidao_negativa_foros_estadual_8_anos', 'label' => 'Certidão Negativa - Foros Criminais (Justiça Estadual) - estados residiu nos últimos 8 anos'],
            ['key' => 'cref13_ba', 'label' => 'Conselho Regional de Educação Fisica da 13º Região - CREF13/BA'],
            ['key' => 'antecedentes_pf_estados_8_anos', 'label' => 'Antecedentes da Polícia Federal (Estados onde residiu nos últimos 8 anos)'],
            ['key' => 'declaracao_beneficio_inss', 'label' => 'Declaração de Benefício do INSS'],
            ['key' => 'comprovante_situacao_cadastral_rf', 'label' => 'Comprovante de Situação Cadastral por CPF - RECEITA FEDERAL'],
            
            
        ];

        // fetch existing checklist if table exists
        $existing = [];
        try {
            if (Schema::hasTable('ingresso_documentos')) {
                $candidateId = $candidate['id'] ?? null;
                if ($candidateId) {
                    $rows = IngressoDocumento::where('ingresso_candidato_id', $candidateId)->get();
                    foreach ($rows as $r) {
                        $item = [
                            'validated' => (bool) $r->validated,
                            'validated_at' => $r->validated_at,
                            'id' => $r->id,
                        ];
                        // include report metadata when columns exist
                        try {
                            if (Schema::hasColumn('ingresso_documentos', 'report')) {
                                $item['report'] = $r->report !== null ? (int) $r->report : 0;
                            } else {
                                $item['report'] = 0;
                            }
                            if (Schema::hasColumn('ingresso_documentos', 'report_description')) {
                                $item['report_description'] = $r->report_description;
                            } else {
                                $item['report_description'] = null;
                            }
                            if (Schema::hasColumn('ingresso_documentos', 'reported_by')) {
                                $item['reported_by'] = $r->reported_by;
                            }
                            if (Schema::hasColumn('ingresso_documentos', 'reported_at')) {
                                $item['reported_at'] = $r->reported_at;
                            }
                        } catch (\Throwable $e) {
                            $item['report'] = 0;
                            $item['report_description'] = null;
                        }

                        $existing[$r->documento_key] = $item;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('ingresso_documentos read failed', ['exception' => $e->getMessage()]);
        }

        // fetch existing encaminhamentos for display
        $encaminhamentos = collect();
        try {
            if (Schema::hasTable('ingresso_encaminhamentos')) {
                $candidateId = $candidate['id'] ?? null;
                if ($candidateId) {
                    $query = DB::table('ingresso_encaminhamentos')->where('ingresso_candidato_id', $candidateId)->orderBy('created_at', 'desc');
                    if (Schema::hasTable('users')) {
                        $query = $query->leftJoin('users', 'ingresso_encaminhamentos.created_by', '=', 'users.id')
                            ->select('ingresso_encaminhamentos.*', 'users.name as created_by_name');
                    }
                    $encaminhamentos = $query->get();
                }
            }
        } catch (\Throwable $e) {
            Log::warning('ingresso_encaminhamentos read failed', ['exception' => $e->getMessage()]);
            $encaminhamentos = collect();
        }

            // If there are no ingresso_encaminhamentos, try to present provimentos_encaminhados
            // and map commonly used fields so the view can render school, discipline and day counts.
            try {
                if ((is_null($encaminhamentos) || $encaminhamentos->isEmpty()) && Schema::hasTable('provimentos_encaminhados')) {
                    $candidateId = $candidate['id'] ?? null;
                    if ($candidateId) {
                        // Join with `uees` to obtain unidade_escolar, nte and municipio when provimentos
                        $provRows = DB::table('provimentos_encaminhados')
                            ->leftJoin('uees', 'provimentos_encaminhados.uee_id', '=', 'uees.id')
                            ->where('provimentos_encaminhados.ingresso_candidato_id', $candidateId)
                            ->select('provimentos_encaminhados.*',
                                'uees.unidade_escolar as uee_name',
                                'uees.nte as uee_nte',
                                'uees.municipio as uee_municipio')
                            ->orderBy('provimentos_encaminhados.created_at', 'desc')
                            ->get();

                        if ($provRows && count($provRows)) {
                            $mapped = collect();
                            foreach ($provRows as $p) {
                                $m = (int) ($p->provimento_matutino ?? $p->matutino ?? 0);
                                $v = (int) ($p->provimento_vespertino ?? $p->vespertino ?? 0);
                                $n = (int) ($p->provimento_noturno ?? $p->noturno ?? 0);
                                $row = new \stdClass();
                                $row->id = $p->id ?? ($p->provimento_id ?? null);
                                $row->created_at = $p->created_at ?? ($p->data_encaminhamento ?? null);
                                $row->uee_name = $p->unidade_escolar ?? ($p->uee_name ?? ($p->unidade ?? null));
                                $row->motivo = $p->motivo ?? ($p->obs ?? null);
                                $row->disciplina_name = $p->disciplina ?? ($p->disciplina_name ?? ($p->disciplina_code ?? null));
                                $row->quant_matutino = $m;
                                $row->quant_vespertino = $v;
                                $row->quant_noturno = $n;
                                $row->total = ($p->total ?? ($m + $v + $n));
                                $row->created_by_name = $p->usuario ?? ($p->created_by ?? null);
                                // prefer joined uee values when available
                                $row->uee_name = $p->uee_name ?? $row->uee_name ?? ($p->unidade_escolar ?? null);
                                $row->nte = $p->uee_nte ?? ($p->nte ?? null);
                                $row->municipio = $p->uee_municipio ?? ($p->municipio ?? null);
                                $mapped->push($row);
                            }
                            $encaminhamentos = $mapped;
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('provimentos_encaminhados read failed', ['exception' => $e->getMessage()]);
            }

        // Example: allow the view to receive an explicit ordered list of columns
        // to display and custom column labels. Controllers can customize this
        // per-request when needed. If not provided, the view falls back to
        // showing all columns discovered in the DB.
        $visibleColumns = [
            'num_inscricao',
            'name',
            'cpf',
            'data_nascimento',
            'nota',
            'classificacao_quota_racial',
            'rg',
            'orgao_emissor',
            'data_emissao',
            'uf_rg',
            'sexo',
            'num_titulo',
            'zona',
            'secao',
            'uf_titulo',
            'data_emissao_titulo',
            'pis_pasep',
            'data_pis',
            'uf_nascimento',
            'naturalidade',
            'nacionalidade',
            'estado_civil',
            'cnh',
            'categoria_cnh',
            'data_emissao_cnh',
            'validade_cnh',
            'grau_instrução',
            'formacao',
            'num_certificado_militar',
            'especie_certificado_militar',
            'categoria_certificado_militar',
            'orgao_certificado',
            'municipio',
            'bairro',
            'logradouro',
            'complemento',
            'cep',
            'uf',
            'pais',
            'banco',
            'agencia',
            'conta',
            'tel_contato',
            'tel_celular',
            'email',
            'nome_pai',
            'nome_mae',
            'situacao_candidato',
        ];

        $columnNames = [
            'num_inscricao' => 'Nº Inscrição',
            'name' => 'Nome',
            'cpf' => 'CPF',
            'data_nascimento' => 'Data Nascimento',
            'nota' => 'Nota',
            'rg' => 'RG',
            'orgao_emissor' => 'Orgão Emissor',
            'data_emissao' => 'Data de Emissão',
            'uf_rg' => 'UF do RG',
            'sexo' => 'Sexo',
            'num_titulo' => 'Nº Título de Eleitor',
            'zona_titulo' => 'Zona do Título',
            'secao' => 'Seção do Título',
            'uf_titulo' => 'UF do Título',
            'data_emissao_titulo' => 'Data de Emissão do Título',
            'pis_pasep' => 'PIS/PASEP',
            'data_pis' => 'Data de Inscrição no PIS',
            'uf_nascimento' => 'UF de Nascimento',
            'naturalidade' => 'Naturalidade',
            'nacionalidade' => 'Nacionalidade',
            'estado_civil' => 'Estado Civil',
            'cnh' => 'CNH',
            'categoria_cnh' => 'Categoria da CNH',
            'data_emissao_cnh' => 'Data de Emissão da CNH',
            'validade_cnh' => 'Validade da CNH',
            'grau_instrução' => 'Grau de Instrução',
            'formacao' => 'Formação',
            'num_certificado_militar' => 'Nº Certificado Militar',
            'especie_certificado_militar' => 'Espécie do Certificado Militar',
            'categoria_certificado_militar' => 'Categoria do Certificado Militar',
            'orgao_certificado' => 'Orgão Emissor do Certificado Militar',
            'municipio' => 'Município',
            'bairro' => 'Bairro',
            'logradouro' => 'Logradouro',
            'complemento' => 'Complemento',
            'cep' => 'CEP',
            'uf' => 'UF',
            'pais' => 'País',
            'tel_contato' => 'Telefone de Contato',
            'tel_celular' => 'Telefone Celular',
            'email' => 'E-mail',
            'nome_pai' => 'Nome do Pai',
            'nome_mae' => 'Nome da Mãe',
            'situacao_candidato' => 'Situação do Candidato',
            'banco' => 'Banco',
            'agencia' => 'Agência',
            'conta' => 'Conta',
            'classificacao_quota_racial' => 'Classificação (Quota Racial)',
        ];

        // Primary keys to show in the top section (Dados Principais)
        $primaryKeys = [
            'num_inscricao', 'name', 'cpf', 'data_nascimento', 'nota', 'classificacao_ampla', 'classificacao_quota_pne', 'classificacao_quota_racial'
        ];

        return view('ingresso.show', [
            'candidate' => (array) $candidate,
            'columns' => $columns,
            'labelMap' => $labelMap,
            'groups' => $groups,
            'uees' => $uees,
            'documentList' => $documentList,
            'existingDocuments' => $existing,
            'encaminhamentos' => $encaminhamentos,
            'visibleColumns' => $visibleColumns,
            'columnNames' => $columnNames,
            'primaryKeys' => $primaryKeys,
        ]);
    }

    /**
     * Endpoint for CPM to mark the ingresso as validated (final approval).
     */
    public function validateIngresso(Request $request, $identifier)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Ação não autorizada'], 403);
        }

        // only CPM (sector 2 && profile_id 1) may perform final validation
        $user = optional(Auth::user());
        if (!($user && isset($user->sector_id) && isset($user->profile_id) && $user->sector_id == 2 && $user->profile_id == 1)) {
            return response()->json(['success' => false, 'message' => 'Ação permitida apenas para CPM'], 403);
        }

        try {
            $candidateId = DB::table('ingresso_candidatos')->where('id', $identifier)->orWhere('num_inscricao', $identifier)->value('id');
            if (! $candidateId) {
                return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
            }

            $updates = [];
            if (Schema::hasColumn('ingresso_candidatos', 'documentos_validados')) {
                $updates['documentos_validados'] = 1;
            }
            if (Schema::hasColumn('ingresso_candidatos', 'status')) {
                $updates['status'] = 'Apto para encaminhamento';
            }
            if (Schema::hasColumn('ingresso_candidatos', 'status_validated_by')) {
                $updates['status_validated_by'] = optional(Auth::user())->id;
            }
            if (Schema::hasColumn('ingresso_candidatos', 'status_validated_at')) {
                $updates['status_validated_at'] = now();
            }

            if (!empty($updates)) {
                DB::table('ingresso_candidatos')->where('id', $candidateId)->update($updates);
            }

            Log::info('Ingresso final validation by CPM', ['user' => optional(Auth::user())->id, 'candidate' => $candidateId]);

            // return updated candidate for frontend convenience
            $updated = DB::table('ingresso_candidatos')->where('id', $candidateId)->first();
            return response()->json(['success' => true, 'message' => 'Apto para encaminhamento com sucesso.', 'candidate' => $updated]);
        } catch (\Throwable $e) {
            Log::error('Failed to validate ingresso', ['exception' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Erro ao validar ingresso'], 500);
        }
    }

    /**
     * CPM: remove final ingresso validation and revert to 'Documentos Validados'
     */
    public function unvalidateIngresso(Request $request, $identifier)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Ação não autorizada'], 403);
        }

        // only CPM (sector 2 && profile_id 1) may perform final unvalidation
        $user = optional(Auth::user());
        if (!($user && isset($user->sector_id) && isset($user->profile_id) && $user->sector_id == 2 && $user->profile_id == 1)) {
            return response()->json(['success' => false, 'message' => 'Ação permitida apenas para CPM'], 403);
        }

        try {
            $candidateId = DB::table('ingresso_candidatos')->where('id', $identifier)->orWhere('num_inscricao', $identifier)->value('id');
            if (! $candidateId) {
                return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
            }

            $updates = [];
            // keep documentos_validados = 1 (documents remain validated)
            if (Schema::hasColumn('ingresso_candidatos', 'documentos_validados')) {
                $updates['documentos_validados'] = 1;
            }
            if (Schema::hasColumn('ingresso_candidatos', 'status')) {
                $updates['status'] = 'Documentos Validados';
            }
            // clear the final validation metadata
            if (Schema::hasColumn('ingresso_candidatos', 'status_validated_by')) {
                $updates['status_validated_by'] = null;
            }
            if (Schema::hasColumn('ingresso_candidatos', 'status_validated_at')) {
                $updates['status_validated_at'] = null;
            }

            if (!empty($updates)) {
                DB::table('ingresso_candidatos')->where('id', $candidateId)->update($updates);
            }

            Log::info('Ingresso unvalidation by CPM', ['user' => optional(Auth::user())->id, 'candidate' => $candidateId]);

            $updated = DB::table('ingresso_candidatos')->where('id', $candidateId)->first();
            return response()->json(['success' => true, 'message' => 'Validação do ingresso removida.', 'candidate' => $updated]);
        } catch (\Throwable $e) {
            Log::error('Failed to unvalidate ingresso', ['exception' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Erro ao remover validação do ingresso'], 500);
        }
    }

    /**
     * CPM confirms documents -> mark candidate as Documentos Validados
     */
    public function confirmDocumentosCpm(Request $request, $identifier)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Ação não autorizada'], 403);
        }

        // only CPM (sector 2 && profile_id 1) may perform this action
        $user = optional(Auth::user());
        if (!($user && isset($user->sector_id) && isset($user->profile_id) && $user->sector_id == 2 && $user->profile_id == 1)) {
            return response()->json(['success' => false, 'message' => 'Ação permitida apenas para CPM'], 403);
        }

        try {
            $candidateId = DB::table('ingresso_candidatos')->where('id', $identifier)->orWhere('num_inscricao', $identifier)->value('id');
            if (! $candidateId) {
                return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
            }

            $updates = [];
            if (Schema::hasColumn('ingresso_candidatos', 'documentos_validados')) {
                $updates['documentos_validados'] = 1;
            }
            if (Schema::hasColumn('ingresso_candidatos', 'status')) {
                $updates['status'] = 'Documentos Validados';
            }
            if (Schema::hasColumn('ingresso_candidatos', 'status_validated_by')) {
                $updates['status_validated_by'] = optional(Auth::user())->id;
            }
            if (Schema::hasColumn('ingresso_candidatos', 'status_validated_at')) {
                $updates['status_validated_at'] = now();
            }

            if (!empty($updates)) {
                DB::table('ingresso_candidatos')->where('id', $candidateId)->update($updates);
            }

            Log::info('CPM confirmed documentos for candidate', ['user' => optional(Auth::user())->id, 'candidate' => $candidateId]);

            $updated = DB::table('ingresso_candidatos')->where('id', $candidateId)->first();
            return response()->json(['success' => true, 'message' => 'Documentos validados (CPM).', 'candidate' => $updated]);
        } catch (\Throwable $e) {
            Log::error('confirmDocumentosCpm error', ['exception' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Erro ao confirmar documentos'], 500);
        }
    }

    /**
     * Debug endpoint: return the candidate DB row and recent encaminhamentos.
     * Accessible only to authorized users (NTE/CPM profile in sectors 7 or 2).
     */
    public function debugStatus($identifier)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Ação não autorizada'], 403);
        }

        try {
            $candidate = DB::table('ingresso_candidatos')
                ->where('id', $identifier)
                ->orWhere('num_inscricao', $identifier)
                ->first();

            if (! $candidate) {
                return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
            }

            $encaminhamentos = [];
            if (Schema::hasTable('ingresso_encaminhamentos')) {
                $encaminhamentos = DB::table('ingresso_encaminhamentos')
                    ->where('ingresso_candidato_id', $candidate->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            }

            return response()->json(['success' => true, 'candidate' => $candidate, 'encaminhamentos' => $encaminhamentos]);
        } catch (\Throwable $e) {
            Log::error('debugStatus failed', ['exception' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Erro interno'], 500);
        }
    }

    /**
     * Render oficio — supports ?print=1 to return a printable HTML version.
     */
    public function oficio(Request $request, $identifier)
    {
        if (! $this->authorizeUser()) abort(403);

        $candidate = DB::table('ingresso_candidatos')->where('id', $identifier)->orWhere('num_inscricao', $identifier)->first();
        if ($candidate) $candidate = (array) $candidate;

        $encaminhamentos = DB::table('ingresso_encaminhamentos')
            ->where('ingresso_candidato_id', $candidate['id'] ?? 0)
            ->orderBy('created_at','desc')
            ->get();

       

        $encs = collect();
        try {
            // use ingresso_encaminhamentos as the single source for oficio data
            if (Schema::hasTable('ingresso_encaminhamentos') && ($candidate['id'] ?? null)) {
                $encs = DB::table('ingresso_encaminhamentos')
                    ->where('ingresso_candidato_id', $candidate['id'])
                    ->orderBy('created_at','desc')
                    ->selectRaw('*, COALESCE(disciplina_name, disciplina, disciplina_code) as disciplina_resolved')
                    ->get();
            } else {
                $encs = collect();
            }
        } catch (\Throwable $e) {
            Log::warning('oficio read failed', ['exception' => $e->getMessage()]);
            $encs = collect();
        }

        // attach disciplina_resolved property to each item for view compatibility
        try {
            if (is_iterable($encs) && count($encs)) {
                $encs = collect($encs)->map(function ($it) {
                    if (is_object($it)) {
                        $it->disciplina_resolved = $it->disciplina_resolved ?? ($it->disciplina_name ?? $it->disciplina ?? $it->disciplina_code ?? null);
                        return $it;
                    }
                    $arr = (array) $it;
                    $obj = (object) $arr;
                    $obj->disciplina_resolved = $arr['disciplina_resolved'] ?? ($arr['disciplina_name'] ?? $arr['disciplina'] ?? $arr['disciplina_code'] ?? null);
                    return $obj;
                })->values();
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to attach disciplina_resolved', ['err' => $e->getMessage()]);
        }

        // ensure candidate is array and attempt to infer UEE name/code from candidate or first encaminhamento
        if (! is_array($candidate)) {
            $candidate = (array) $candidate;
        }
        try {
            $ueeName = $candidate['unidade_escolar'] ?? $candidate['uee_name'] ?? null;
            $ueeCode = $candidate['cod_unidade'] ?? $candidate['uee_code'] ?? null;
            if (empty($ueeName) && isset($encs) && is_iterable($encs) && count($encs)) {
                $first = $encs->first();
                if (is_object($first)) {
                    // prefer joined/related UEE model if available
                    if (isset($first->uee) && is_object($first->uee)) {
                        $ueeName = $ueeName ?? ($first->uee->unidade_escolar ?? $first->uee->uee_name ?? null);
                    }
                    $ueeName = $ueeName ?? ($first->uee_name ?? $first->uee ?? null);
                }
            }
            if (empty($ueeCode) && isset($encs) && is_iterable($encs) && count($encs)) {
                $first = $encs->first();
                if (is_object($first)) {
                    if (isset($first->uee) && is_object($first->uee)) {
                        $ueeCode = $ueeCode ?? ($first->uee->cod_unidade ?? $first->uee->uee_code ?? null);
                    }
                    $ueeCode = $ueeCode ?? ($first->uee_code ?? $first->cod_unidade ?? null);
                }
            }
            $candidate['uee_name'] = $ueeName;
            $candidate['uee_code'] = $ueeCode;
        } catch (\Throwable $e) {
            // ignore inference failures
        }

         

        if ($request->boolean('print')) {
            return response()->view('ingresso.oficio_print', ['candidate' => $candidate, 'encaminhamentos' => $encaminhamentos]);
        }
        

        return view('ingresso.oficio', ['candidate' => $candidate, 'encaminhamentos' => $encs]);
    }



    /**
     * Return JSON checklist for a candidate
     */
    public function getDocumentChecklist($id)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $list = [
            ['key' => 'cpf', 'label' => 'CPF'],
            ['key' => 'rg', 'label' => 'RG'],
            ['key' => 'certidao', 'label' => 'Certidão de Nascimento/Casamento'],
            ['key' => 'comprovante_residencia', 'label' => 'Comprovante de Residência'],
            ['key' => 'diploma', 'label' => 'Diploma / Histórico'],
            ['key' => 'dependente_doc', 'label' => 'Certidão de Nascimento ou RG do(s) dependente(s)'],
            // 'titulo_eleitor' removed: not required/validated by default
            
            ['key' => 'comprovante_bb', 'label' => 'Comprovante (extrato ou cartão) - Banco do Brasil'],
            ['key' => 'pis_pasep', 'label' => 'Original e cópia do PIS/PASEP (se inscrito)'],
            ['key' => 'carteira_trabalho', 'label' => 'Carteira de Trabalho (original e cópia)'],
            ['key' => 'certificado_reservista', 'label' => 'Certificado de Reservista (para homens)'],
            ['key' => 'ficha_cadastro', 'label' => 'Ficha de Cadastro (original)'],
            ['key' => 'email_confirmacao', 'label' => 'E-mail (confirmação/comprovante)'],
            
            ['key' => 'declaracao_etnia', 'label' => 'Declaração de Etnia'],
            ['key' => 'quitacao_eleitoral', 'label' => 'Quitação Eleitoral (fornecida pelo cartório eleitoral)'],
            ['key' => 'declaracao_bens', 'label' => 'Declaração de Bens'],
            ['key' => 'declaracao_acumulacao', 'label' => 'Declaração de Acumulação'],
            ['key' => 'declaracao_dependentes', 'label' => 'Declaração de Dependentes'],
            ['key' => 'aso', 'label' => 'Atestado de Saúde Ocupacional - ASO'],
            ['key' => 'certidao_negativa_condenacoes', 'label' => 'Certidão Negativa do Cadastro Nacional de Condenações Civeis'],
            ['key' => 'certidao_negativa_justica_eleitoral', 'label' => 'Certidão Negativa da Justiça Eleitoral'],
            ['key' => 'certidao_negativa_justica_militar_federal', 'label' => 'Certidão Negativa da Justiça Militar Federal'],
            ['key' => 'certidao_negativa_foros_federal_8_anos', 'label' => 'Certidão Negativa - Foros Criminais (Justiça Federal) - estados residiu nos últimos 8 anos'],
            ['key' => 'certidao_negativa_foros_estadual_8_anos', 'label' => 'Certidão Negativa - Foros Criminais (Justiça Estadual) - estados residiu nos últimos 8 anos'],
            ['key' => 'antecedentes_pf_estados_8_anos', 'label' => 'Antecedentes da Polícia Federal (Estados onde residiu nos últimos 8 anos)'],
        ];

        $existing = [];
        try {
            if (Schema::hasTable('ingresso_documentos')) {
                // resolve candidate id if $id might be a num_inscricao
                $candidateId = DB::table('ingresso_candidatos')->where('id', $id)->orWhere('num_inscricao', $id)->value('id');
                if ($candidateId) {
                    $rows = IngressoDocumento::where('ingresso_candidato_id', $candidateId)->get();
                    // Build existing map and also append any custom documents to the returned list
                    $presentKeys = array_map(function($it){ return (string)($it['key'] ?? $it['label'] ?? ''); }, $list);
                    $presentNorm = array_map(function($k){ return strtolower(preg_replace('/[^a-z0-9]/','',$k)); }, $presentKeys);
                    $reports = [];
                    foreach ($rows as $r) {
                        $k = (string) ($r->documento_key ?? '');
                        $existing[$k] = (bool) $r->validated;
                        // collect report metadata when present in schema
                        $reports[$k] = [
                            'report' => (bool) ($r->report ?? false),
                            'report_description' => (string) ($r->report_description ?? ''),
                        ];
                        // if this document key/label isn't already in the static list, add it so the UI can show it
                        $label = (string) ($r->documento_label ?? $k ?: 'Documento não identificado');
                        $norm = strtolower(preg_replace('/[^a-z0-9]/','',$k ?: $label));
                        if ($norm !== '' && !in_array($norm, $presentNorm, true)) {
                            $list[] = ['key' => $k ?: $label, 'label' => $label];
                            $presentNorm[] = $norm;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('ingresso_documentos get failed', ['exception' => $e->getMessage()]);
        }

        return response()->json(['list' => $list, 'existing' => $existing, 'reports' => (isset($reports) ? $reports : [])]);
    }

    /**
     * Store/update checklist (expects JSON body { items: [{key, validated}] })
     */
    public function storeDocumentChecklist(Request $request, $id)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $payload = $request->input('items', []);
        if (!is_array($payload)) {
            return response()->json(['message' => 'Invalid payload'], 422);
        }

        try {
            // Support CPM 'issue report' action: { issue: true, key, reason }
            if (boolval($request->input('issue', false))) {
                $candidateId = DB::table('ingresso_candidatos')->where('id', $id)->orWhere('num_inscricao', $id)->value('id');
                if (! $candidateId) {
                    return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
                }
                $key = $request->input('key');
                $reason = trim((string) $request->input('reason', ''));
                if (! $key || $reason === '') {
                    return response()->json(['success' => false, 'message' => 'Chave ou motivo ausente'], 422);
                }

                try {
                    $doc = IngressoDocumento::firstOrNew([
                        'ingresso_candidato_id' => $candidateId,
                        'documento_key' => $key,
                    ]);
                    $rawLabel = $doc->documento_label ?? $request->input('label') ?? $key;
                    $doc->documento_label = is_string($rawLabel) ? \Illuminate\Support\Str::limit(trim(preg_replace('/\s+\s+/',' ', strip_tags($rawLabel))), 255) : $rawLabel;
                    // mark there is a report and store its description
                    if (Schema::hasColumn('ingresso_documentos', 'report')) {
                        $doc->report = 1;
                    }
                    if (Schema::hasColumn('ingresso_documentos', 'report_description')) {
                        $doc->report_description = $reason;
                    }
                    // optional reporter info if columns exist
                    if (Schema::hasColumn('ingresso_documentos', 'reported_by')) {
                        $doc->reported_by = optional(Auth::user())->id;
                    }
                    if (Schema::hasColumn('ingresso_documentos', 'reported_at')) {
                        $doc->reported_at = now();
                    }
                    $doc->save();
                    try { Log::info('ingresso_documentos: issue reported', ['candidate_id' => $candidateId, 'key' => $key, 'user' => optional(Auth::user())->id]); } catch (\Throwable $e) {}
                    return response()->json(['success' => true, 'message' => 'Problema reportado com sucesso']);
                } catch (\Throwable $e) {
                    Log::error('ingresso_documentos: failed to save issue', ['exception' => $e->getMessage(), 'candidate' => $candidateId, 'key' => $key]);
                    return response()->json(['success' => false, 'message' => 'Erro ao salvar report'], 500);
                }
            }

            // Support CPM returning candidate to NTE for corrections: { return_to_nte: true }
            if (boolval($request->input('return_to_nte', false))) {
                $candidateId = DB::table('ingresso_candidatos')->where('id', $id)->orWhere('num_inscricao', $id)->value('id');
                if (! $candidateId) {
                    return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
                }
                // Only CPM users should perform this action
                $user = optional(Auth::user());
                $isCpmUser = ($user && isset($user->sector_id) && isset($user->profile_id) && $user->sector_id == 2 && $user->profile_id == 1);
                if (! $isCpmUser) {
                    return response()->json(['success' => false, 'message' => 'Ação não autorizada'], 403);
                }

                $update = [];
                if (Schema::hasColumn('ingresso_candidatos', 'documentos_validados')) {
                    $update['documentos_validados'] = 0;
                }
                if (Schema::hasColumn('ingresso_candidatos', 'status')) {
                    $update['status'] = 'Corrigir documentação';
                }
                if (Schema::hasColumn('ingresso_candidatos', 'status_validated_by')) {
                    $update['status_validated_by'] = null;
                }
                if (Schema::hasColumn('ingresso_candidatos', 'status_validated_at')) {
                    $update['status_validated_at'] = null;
                }

                try {
                    $affected = 0;
                    if (!empty($update)) {
                        $affected = DB::table('ingresso_candidatos')->where('id', $candidateId)->update($update);
                    }
                    $cleared = 0;
                    // For any documents that were reported, clear their validated flag so NTE must re-validate
                    try {
                        if (Schema::hasTable('ingresso_documentos') && Schema::hasColumn('ingresso_documentos', 'report')) {
                            $upd = ['validated' => 0];
                            if (Schema::hasColumn('ingresso_documentos', 'validated_by')) $upd['validated_by'] = null;
                            if (Schema::hasColumn('ingresso_documentos', 'validated_at')) $upd['validated_at'] = null;
                            $cleared = DB::table('ingresso_documentos')->where('ingresso_candidato_id', $candidateId)->where('report', 1)->update($upd);
                        }
                    } catch (\Throwable $e) {
                        Log::warning('ingresso_documentos: failed to clear validated flags for reported docs', ['exception' => $e->getMessage(), 'candidate' => $candidateId]);
                    }
                    try { Log::info('ingresso_documentos: returned to NTE', ['candidate_id' => $candidateId, 'user' => optional(Auth::user())->id, 'affected_candidates' => $affected, 'cleared_docs' => $cleared]); } catch (\Throwable $e) {}
                    return response()->json(['success' => true, 'message' => 'Ingressos retornado para correção pelo NTE.', 'debug' => ['affected_candidates' => $affected, 'cleared_docs' => $cleared]]);
                } catch (\Throwable $e) {
                    Log::error('ingresso_documentos: failed to return to NTE', ['exception' => $e->getMessage(), 'candidate' => $candidateId]);
                    return response()->json(['success' => false, 'message' => 'Erro ao processar ação'], 500);
                }
            }

            // resolve candidate id in case caller passed num_inscricao
            $candidateId = DB::table('ingresso_candidatos')->where('id', $id)->orWhere('num_inscricao', $id)->value('id');
            if (! $candidateId) {
                return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
            }

            // Debugging: log incoming payload and user/candidate context
            try {
                Log::info('ingresso_documentos: storeDocumentChecklist request', [
                    'candidate_id' => $candidateId,
                    'payload_count' => is_array($payload) ? count($payload) : 0,
                    'payload_sample' => is_array($payload) ? array_slice($payload, 0, 5) : null,
                    'user_id' => optional(Auth::user())->id,
                    'user_sector' => optional(Auth::user())->sector_id,
                    'user_profile' => optional(Auth::user())->profile_id,
                ]);
            } catch (\Throwable $e) {
                // ignore logging failures
            }

            // Prevent NTE users from modifying documents if already validated by CPM
            try {
                $candidateRecord = DB::table('ingresso_candidatos')->where('id', $candidateId)->first();
                $user = optional(Auth::user());
                $isCpmUser = ($user && isset($user->sector_id) && isset($user->profile_id) && $user->sector_id == 2 && $user->profile_id == 1);
                if ($candidateRecord && Schema::hasColumn('ingresso_candidatos', 'documentos_validados')) {
                    $alreadyValidated = !empty($candidateRecord->documentos_validados) && intval($candidateRecord->documentos_validados) === 1;
                    if ($alreadyValidated && ! $isCpmUser) {
                        return response()->json(['success' => false, 'message' => 'Documentos já validados pela CPM. Ação não permitida.'], 403);
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }

            foreach ($payload as $item) {
                if (!isset($item['key'])) continue;
                $key = $item['key'];
                $validated = !empty($item['validated']) ? 1 : 0;

                // If the checkbox was unchecked (validated == 0), remove or update existing record.
                if ($validated === 0) {
                    try {
                        $existingDoc = IngressoDocumento::where('ingresso_candidato_id', $candidateId)
                            ->where('documento_key', $key)
                            ->first();
                        if ($existingDoc) {
                            // If this document was previously reported, keep the row but mark as not validated
                            if (Schema::hasColumn('ingresso_documentos', 'report') && !empty($existingDoc->report)) {
                                $existingDoc->validated = 0;
                                if (Schema::hasColumn('ingresso_documentos', 'validated_by')) $existingDoc->validated_by = null;
                                if (Schema::hasColumn('ingresso_documentos', 'validated_at')) $existingDoc->validated_at = null;
                                $existingDoc->save();
                                try { Log::info('ingresso_documentos: doc updated (kept due to report)', ['candidate_id' => $candidateId, 'key' => $key, 'id' => $existingDoc->id]); } catch (\Throwable $e) {}
                            } else {
                                $deleted = IngressoDocumento::where('ingresso_candidato_id', $candidateId)
                                    ->where('documento_key', $key)
                                    ->delete();
                                try { Log::info('ingresso_documentos: doc deleted', ['candidate_id' => $candidateId, 'key' => $key, 'deleted' => $deleted]); } catch (\Throwable $e) {}
                            }
                        }
                    } catch (\Throwable $e) {
                        try { Log::error('ingresso_documentos: failed to delete/update doc', ['candidate_id' => $candidateId, 'key' => $key, 'exception' => $e->getMessage()]); } catch (\Throwable $ee) {}
                    }
                    continue;
                }

                // For checked items, create/update the record
                $doc = IngressoDocumento::firstOrNew([
                    'ingresso_candidato_id' => $candidateId,
                    'documento_key' => $key,
                ]);
                $rawLabel = $item['label'] ?? $doc->documento_label ?? $key;
                $doc->documento_label = is_string($rawLabel) ? \Illuminate\Support\Str::limit(trim(preg_replace('/\s+\s+/',' ', strip_tags($rawLabel))), 255) : $rawLabel;
                $doc->validated = $validated;
                if ($validated) {
                    $doc->validated_by = optional(Auth::user())->id;
                    $doc->validated_at = now();
                    // If this document had an open report, mark it as resolved by NTE (status 2)
                    try {
                        if (Schema::hasColumn('ingresso_documentos', 'report') && !empty($doc->report)) {
                            $doc->report = 2;
                            if (Schema::hasColumn('ingresso_documentos', 'report_resolved_by')) {
                                $doc->report_resolved_by = optional(Auth::user())->id;
                            }
                            if (Schema::hasColumn('ingresso_documentos', 'report_resolved_at')) {
                                $doc->report_resolved_at = now();
                            }
                        }
                    } catch (\Throwable $e) {
                        // ignore schema/read failures
                    }
                } else {
                    $doc->validated_by = null;
                    $doc->validated_at = null;
                }
                $doc->save();
                try { Log::info('ingresso_documentos: doc saved', ['candidate_id' => $candidateId, 'key' => $key, 'id' => $doc->id]); } catch (\Throwable $e) {}
            }

            // Only update the candidate-level status when the request explicitly indicates
            // this is a confirmation (bulk) action. Individual checkbox autosaves should
            // not flip the candidate status by themselves.
            try {
                $isConfirm = boolval($request->input('confirm', false));
                $isUnvalidate = boolval($request->input('unvalidate', false));
                $debugUpdate = null;
                $debugAffected = null;

                if (( $isConfirm || $isUnvalidate ) && Schema::hasTable('ingresso_documentos')) {
                    $total = IngressoDocumento::where('ingresso_candidato_id', $candidateId)->count();
                    $notValidated = IngressoDocumento::where('ingresso_candidato_id', $candidateId)->where('validated', 0)->count();

                    // determine if current user is CPM (sector_id=2 && profile_id=1)
                    $user = optional(Auth::user());
                    $isCpmUser = ($user && isset($user->sector_id) && isset($user->profile_id) && $user->sector_id == 2 && $user->profile_id == 1);

                    // Debugging: log totals and detected role and available columns
                    try {
                        $availableCols = Schema::hasTable('ingresso_candidatos') ? Schema::getColumnListing('ingresso_candidatos') : [];
                        Log::info('ingresso_documentos: confirm stats', [
                            'candidate_id' => $candidateId,
                            'total_docs' => $total,
                            'not_validated' => $notValidated,
                            'is_cpm_user' => $isCpmUser,
                            'available_columns' => $availableCols,
                        ]);
                    } catch (\Throwable $e) {
                        // ignore logging failures
                    }

                    // Build a single update array to always write status when appropriate
                    $update = [];
                    if ($isUnvalidate) {
                        if (Schema::hasColumn('ingresso_candidatos', 'documentos_validados')) {
                            $update['documentos_validados'] = 0;
                        }
                        if (Schema::hasColumn('ingresso_candidatos', 'status')) {
                            $update['status'] = 'Documentos Pendentes';
                        }
                        if (Schema::hasColumn('ingresso_candidatos', 'status_validated_by')) {
                            $update['status_validated_by'] = null;
                        }
                        if (Schema::hasColumn('ingresso_candidatos', 'status_validated_at')) {
                            $update['status_validated_at'] = null;
                        }
                    } else {
                        if ($isCpmUser) {
                            // CPM user: only finalize when all documents validated
                            if ($total > 0 && $notValidated === 0) {
                                if (Schema::hasColumn('ingresso_candidatos', 'status')) {
                                    $update['status'] = 'Documentos Validados';
                                }
                                if (Schema::hasColumn('ingresso_candidatos', 'documentos_validados')) {
                                    $update['documentos_validados'] = 1;
                                }
                                if (Schema::hasColumn('ingresso_candidatos', 'status_validated_by')) {
                                    $update['status_validated_by'] = optional(Auth::user())->id;
                                }
                                if (Schema::hasColumn('ingresso_candidatos', 'status_validated_at')) {
                                    $update['status_validated_at'] = now();
                                }
                            } else {
                                // CPM attempted confirm but not all docs validated: keep as pending/documentos pendentes
                                if (Schema::hasColumn('ingresso_candidatos', 'documentos_validados')) {
                                    $update['documentos_validados'] = 0;
                                }
                                if (Schema::hasColumn('ingresso_candidatos', 'status')) {
                                    $update['status'] = 'Documentos Pendentes';
                                }
                            }
                        } else {
                                // Non-CPM (e.g., NTE) confirms selected documents -> require SEI number before allowing
                                if (boolval($isConfirm)) {
                                    try {
                                        $candidateRec = DB::table('ingresso_candidatos')->where('id', $candidateId)->first();
                                        $hasSeiColumn = Schema::hasColumn('ingresso_candidatos', 'sei_number');
                                        $seiVal = $candidateRec && $hasSeiColumn ? trim((string)($candidateRec->sei_number ?? '')) : '';
                                        // If current user is not CPM (i.e., NTE) and SEI missing, reject
                                        if (! $isCpmUser && ($hasSeiColumn && $seiVal === '')) {
                                            return response()->json(['success' => false, 'message' => 'Validação pelo NTE requer número do processo SEI'], 422);
                                        }
                                    } catch (\Throwable $e) {
                                        // ignore and proceed conservatively (do not allow NTE to validate without SEI)
                                        if (! $isCpmUser) {
                                            return response()->json(['success' => false, 'message' => 'Validação pelo NTE requer número do processo SEI'], 422);
                                        }
                                    }
                                }

                                // mark awaiting CPM confirmation (documents remain unfinalized until CPM)
                                if (Schema::hasColumn('ingresso_candidatos', 'status')) {
                                    $update['status'] = 'Aguardando Confirmação pela CPM';
                                }
                                // documentos_validados remains unset until CPM confirms
                                if (Schema::hasColumn('ingresso_candidatos', 'status_validated_by')) {
                                    $update['status_validated_by'] = optional(Auth::user())->id;
                                }
                                if (Schema::hasColumn('ingresso_candidatos', 'status_validated_at')) {
                                    $update['status_validated_at'] = now();
                                }
                            }
                    }

                    if (!empty($update)) {
                        Log::info('ingresso_documentos: applying status update', ['candidate_id' => $candidateId, 'update' => $update, 'is_cpm' => $isCpmUser ?? null]);
                        $affected = DB::table('ingresso_candidatos')->where('id', $candidateId)->update($update);
                        Log::info('ingresso_documentos: status update applied', ['candidate_id' => $candidateId, 'affected_rows' => $affected]);
                        $debugUpdate = $update;
                        $debugAffected = isset($affected) ? $affected : null;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to update ingresso_candidatos status after documents save', ['exception' => $e->getMessage()]);
            }

            // Build response payload. If this was a confirmation action, include status info
            $responsePayload = ['success' => true];
            try {
                $isConfirm = boolval($request->input('confirm', false));
                if ($isConfirm && Schema::hasTable('ingresso_documentos')) {
                    $total = IngressoDocumento::where('ingresso_candidato_id', $candidateId)->count();
                    $notValidated = IngressoDocumento::where('ingresso_candidato_id', $candidateId)->where('validated', 0)->count();
                    $user = optional(Auth::user());
                    $isCpmUser = ($user && isset($user->sector_id) && isset($user->profile_id) && $user->sector_id == 2 && $user->profile_id == 1);

                    if ($isCpmUser) {
                        $responsePayload['finalized'] = ($total > 0 && $notValidated === 0) ? true : false;
                        if ($responsePayload['finalized']) {
                            $responsePayload['status'] = 'Documentos Validados';
                        } else {
                            $responsePayload['status'] = ($total > 0 && $notValidated === 0) ? 'Aguardando Confirmação pela CPM' : 'Documentos Pendentes';
                        }
                    } else {
                        // Non-CPM confirmations result in awaiting CPM regardless of all docs validated
                        $responsePayload['finalized'] = false;
                        $responsePayload['status'] = 'Aguardando Confirmação pela CPM';
                    }
                }
            } catch (\Throwable $e) {
                // ignore - response will at least contain success
            }

            // include debug info if available (helps diagnose why DB update may not persist)
            try {
                if (isset($debugUpdate)) $responsePayload['debug_update'] = $debugUpdate;
                if (isset($debugAffected)) $responsePayload['debug_affected_rows'] = $debugAffected;
                // return the current candidate DB row so we can compare persisted state
                try {
                    $current = DB::table('ingresso_candidatos')->where('id', $candidateId)->first();
                    if ($current) $responsePayload['candidate'] = $current;
                } catch (\Throwable $e) {
                    // ignore candidate fetch failures
                }
            } catch (\Throwable $e) {}

            return response()->json($responsePayload);
        } catch (\Throwable $e) {
            Log::error('storeDocumentChecklist error', ['exception' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    /**
     * Mark candidate as 'NAO ASSUMIU' by updating ingresso_candidatos.status.
     */
    public function markNaoAssumiu(Request $request, $id)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        try {
            // Resolve candidate id if $id might be a num_inscricao
            $candidateId = DB::table('ingresso_candidatos')->where('id', $id)->orWhere('num_inscricao', $id)->value('id');
            if (! $candidateId) {
                return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
            }

            $now = date('Y-m-d H:i:s');

            // Update status field if present. Save previous status into `old_status` if column exists.
            if (Schema::hasColumn('ingresso_candidatos', 'status')) {
                // fetch previous status
                $previousStatus = DB::table('ingresso_candidatos')->where('id', $candidateId)->value('status');
                $upd = ['status' => 'NAO ASSUMIU'];
                if (Schema::hasColumn('ingresso_candidatos', 'old_status')) {
                    $upd['old_status'] = $previousStatus;
                }
                if (Schema::hasColumn('ingresso_candidatos', 'updated_at')) {
                    $upd['updated_at'] = $now;
                }
                DB::table('ingresso_candidatos')->where('id', $candidateId)->update($upd);
            } else {
                // Fallback: mark in provimentos_encaminhados if table exists
                if (Schema::hasTable('provimentos_encaminhados')) {
                    $upd2 = [
                        'situacao_programacao' => 'NAO ASSUMIU',
                        'data_assuncao' => null,
                    ];
                    if (Schema::hasColumn('provimentos_encaminhados', 'updated_at')) {
                        $upd2['updated_at'] = $now;
                    }
                    DB::table('provimentos_encaminhados')
                        ->where('ingresso_candidato_id', $candidateId)
                        ->update($upd2);
                } else {
                    return response()->json(['success' => false, 'message' => 'Não há campo para registrar status'], 500);
                }
            }

            try { Log::info('Candidato marcado como NAO ASSUMIU', ['candidate_id' => $candidateId, 'user' => optional(Auth::user())->id]); } catch (\Throwable $e) {}

            return response()->json(['success' => true, 'message' => 'Candidato marcado como NÃO ASSUMIU']);
        } catch (\Throwable $e) {
            Log::error('markNaoAssumiu failed', ['exception' => $e->getMessage(), 'identifier' => $id]);
            return response()->json(['success' => false, 'message' => 'Erro ao marcar candidato'], 500);
        }
    }

    /**
     * Restore candidate status from `old_status` when previously marked as 'NAO ASSUMIU'.
     */
    public function retirarNaoAssumiu(Request $request, $id)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        try {
            $candidateId = DB::table('ingresso_candidatos')->where('id', $id)->orWhere('num_inscricao', $id)->value('id');
            if (! $candidateId) {
                return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
            }

            if (! Schema::hasColumn('ingresso_candidatos', 'old_status')) {
                return response()->json(['success' => false, 'message' => 'Campo old_status não existe'], 500);
            }

            $old = DB::table('ingresso_candidatos')->where('id', $candidateId)->value('old_status');
            if ($old === null || $old === '') {
                return response()->json(['success' => false, 'message' => 'Nenhum status anterior registrado'], 400);
            }

            $now = date('Y-m-d H:i:s');
            $upd = ['status' => $old, 'old_status' => null];
            if (Schema::hasColumn('ingresso_candidatos', 'updated_at')) {
                $upd['updated_at'] = $now;
            }
            DB::table('ingresso_candidatos')->where('id', $candidateId)->update($upd);

            try { Log::info('Retirado NAO ASSUMIU', ['candidate_id' => $candidateId, 'user' => optional(Auth::user())->id]); } catch (\Throwable $e) {}

            return response()->json(['success' => true, 'message' => 'Status restaurado com sucesso']);
        } catch (\Throwable $e) {
            Log::error('retirarNaoAssumiu failed', ['exception' => $e->getMessage(), 'identifier' => $id]);
            return response()->json(['success' => false, 'message' => 'Erro ao restaurar status'], 500);
        }
    }

    /**
     * Handle SEI process registration (simple handler that logs and flashes message).
     */
    public function assign(Request $request, $identifier)
    {
        if (! $this->authorizeUser()) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Ação não autorizada'], 403);
            }
            return redirect()->back()->with('status', 'Ação não autorizada');
        }

        $sei = trim($request->input('sei_number', ''));
        if (empty($sei)) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Informe o número do processo SEI'], 422);
            }
            return redirect()->back()->with('status', 'Informe o número do processo SEI');
        }

        // try resolve candidate id for logging and persist SEI if column exists
        $candidateId = DB::table('ingresso_candidatos')->where('id', $identifier)->orWhere('num_inscricao', $identifier)->value('id');

        try {
            if ($candidateId && Schema::hasColumn('ingresso_candidatos', 'sei_number')) {
                DB::table('ingresso_candidatos')->where('id', $candidateId)->update(['sei_number' => $sei]);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to persist sei_number', ['exception' => $e->getMessage(), 'candidate' => $candidateId]);
        }

        Log::info('SEI assign requested', ['user_id' => optional(Auth::user())->id, 'candidate' => $candidateId ?: $identifier, 'sei' => $sei]);

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Processo SEI registrado: ' . $sei, 'sei_number' => $sei]);
        }
        return redirect()->back()->with('status', 'Processo SEI registrado: ' . $sei);
    }

    /**
     * Forward candidate to a school/discipline (AJAX).
     */
    public function forward(Request $request, $identifier)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Ação não autorizada'], 403);
        }

        // Log incoming payload for debugging
        try {
            Log::info('Encaminhar payload', ['user' => optional(Auth::user())->id, 'candidate_identifier' => $identifier, 'payload' => $request->all()]);
        } catch (\Throwable $e) {
            // ignore logging errors
        }

        // Validate basic scalar fields; disciplines may be submitted as an array
        $validated = $request->validate([
            'uee_code' => 'nullable|string|max:50',
            'uee_name' => 'nullable|string|max:255',
            'motivo' => 'nullable|string|max:255',
            'observacao' => 'nullable|string|max:2000',
            'disciplinas' => 'sometimes|array',
            'disciplinas.*.disciplina_id' => 'nullable',
            'disciplinas.*.disciplina_name' => 'nullable|string|max:255',
            'disciplinas.*.quant_matutino' => 'nullable|integer|min:0',
            'disciplinas.*.quant_vespertino' => 'nullable|integer|min:0',
            'disciplinas.*.quant_noturno' => 'nullable|integer|min:0',
        ]);

        // resolve candidate id
        $candidateId = DB::table('ingresso_candidatos')
            ->where('id', $identifier)
            ->orWhere('num_inscricao', $identifier)
            ->value('id');

        try {
            if (Schema::hasTable('ingresso_encaminhamentos')) {
                // Build insert rows defensively based on existing columns in the table
                $rowsToInsert = [];

                // helper to assemble a single row respecting existing columns
                $assembleRow = function($discipline = null) use ($request, $candidateId) {
                    $row = [
                        'ingresso_candidato_id' => $candidateId,
                        'created_by' => optional(Auth::user())->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // uee fields
                    if (Schema::hasColumn('ingresso_encaminhamentos', 'uee_code')) {
                        $row['uee_code'] = $request->input('uee_code') ?? $request->input('uee_id') ?? null;
                    }
                    if (Schema::hasColumn('ingresso_encaminhamentos', 'uee_name')) {
                        $row['uee_name'] = $request->input('uee_name') ?? null;
                    }

                    // discipline info (either provided via $discipline array or top-level fallback)
                    if ($discipline && is_array($discipline)) {
                        if (Schema::hasColumn('ingresso_encaminhamentos', 'disciplina_code')) {
                            $row['disciplina_code'] = $discipline['disciplina_id'] ?? $discipline['disciplina_code'] ?? null;
                        }
                        if (Schema::hasColumn('ingresso_encaminhamentos', 'disciplina_name')) {
                            $row['disciplina_name'] = $discipline['disciplina_name'] ?? null;
                        }
                        // store quantities in separate columns only if they exist
                        if (Schema::hasColumn('ingresso_encaminhamentos', 'quant_matutino')) {
                            $row['quant_matutino'] = isset($discipline['quant_matutino']) ? intval($discipline['quant_matutino']) : null;
                        }
                        if (Schema::hasColumn('ingresso_encaminhamentos', 'quant_vespertino')) {
                            $row['quant_vespertino'] = isset($discipline['quant_vespertino']) ? intval($discipline['quant_vespertino']) : null;
                        }
                        if (Schema::hasColumn('ingresso_encaminhamentos', 'quant_noturno')) {
                            $row['quant_noturno'] = isset($discipline['quant_noturno']) ? intval($discipline['quant_noturno']) : null;
                        }
                    } else {
                        if (Schema::hasColumn('ingresso_encaminhamentos', 'disciplina_code')) {
                            $row['disciplina_code'] = $request->input('disciplina_code') ?? null;
                        }
                        if (Schema::hasColumn('ingresso_encaminhamentos', 'disciplina_name')) {
                            $row['disciplina_name'] = $request->input('disciplina_name') ?? null;
                        }
                    }

                    if (Schema::hasColumn('ingresso_encaminhamentos', 'observacao')) {
                        $row['observacao'] = $request->input('observacao') ?? null;
                    }

                    // motivo (top-level field) — persist only if column exists
                    // accept either an explicit 'motivo' input or fallback to 'tipo_encaminhamento'
                    if (Schema::hasColumn('ingresso_encaminhamentos', 'motivo')) {
                        $row['motivo'] = $request->input('motivo') ?? $request->input('tipo_encaminhamento') ?? null;
                    }

                        // tipo de encaminhamento (if table has column)
                        if (Schema::hasColumn('ingresso_encaminhamentos', 'tipo_encaminhamento')) {
                            $row['tipo_encaminhamento'] = $request->input('tipo_encaminhamento') ?? null;
                        } elseif (Schema::hasColumn('ingresso_encaminhamentos', 'tipo')) {
                            $row['tipo'] = $request->input('tipo_encaminhamento') ?? null;
                        }

                    return $row;
                };

                // If caller provided a 'disciplinas' array, create one row per discipline
                $disciplinas = $request->input('disciplinas');

                // Check for an existing encaminhamento for this candidate
                $existing = DB::table('ingresso_encaminhamentos')
                    ->where('ingresso_candidato_id', $candidateId)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($existing) {
                    // If multiple disciplines submitted, replace existing row with multiple new rows
                    if (is_array($disciplinas) && count($disciplinas) > 1) {
                        // delete existing single row
                        DB::table('ingresso_encaminhamentos')->where('id', $existing->id)->delete();
                        foreach ($disciplinas as $d) {
                            $rowsToInsert[] = $assembleRow($d);
                        }
                        DB::table('ingresso_encaminhamentos')->insert($rowsToInsert);
                        Log::info('Ingresso encaminhado (replaced with multiple)', ['user' => optional(Auth::user())->id, 'candidate' => $candidateId, 'rows' => count($rowsToInsert)]);
                        return response()->json(['success' => true, 'message' => 'Encaminhamento registrado.']);
                    }

                    // Single-discipline update: update the existing row with new values
                    $first = (is_array($disciplinas) && count($disciplinas)) ? $disciplinas[0] : null;
                    $updateRow = $assembleRow($first);
                    // remove creation-only fields
                    if (isset($updateRow['created_by'])) unset($updateRow['created_by']);
                    if (isset($updateRow['created_at'])) unset($updateRow['created_at']);
                    // ensure updated_at is current
                    $updateRow['updated_at'] = now();
                    DB::table('ingresso_encaminhamentos')->where('id', $existing->id)->update($updateRow);
                    Log::info('Ingresso encaminhado (updated existing)', ['user' => optional(Auth::user())->id, 'candidate' => $candidateId, 'encaminhamento' => $existing->id]);
                    return response()->json(['success' => true, 'message' => 'Encaminhamento atualizado.']);
                }

                // No existing row: perform insert (multiple rows allowed)
                if (is_array($disciplinas) && count($disciplinas)) {
                    foreach ($disciplinas as $d) {
                        $rowsToInsert[] = $assembleRow($d);
                    }
                } else {
                    // fallback: single-row insertion using top-level fields
                    $rowsToInsert[] = $assembleRow(null);
                }

                DB::table('ingresso_encaminhamentos')->insert($rowsToInsert);
                Log::info('Ingresso encaminhado', ['user' => optional(Auth::user())->id, 'candidate' => $candidateId, 'rows' => count($rowsToInsert)]);
                return response()->json(['success' => true, 'message' => 'Encaminhamento registrado.']);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to create ingresso_encaminhamentos', ['exception' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Erro ao registrar encaminhamento'], 500);
        }

        return response()->json(['success' => false, 'message' => 'Recurso de encaminhamento indisponível'], 404);
    }

    /**
     * Set the status field on the most recent encaminhamento for a candidate.
     */
    public function setEncaminhamentoStatus(Request $request, $identifier)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Ação não autorizada'], 403);
        }

        // Allow clearing the status by explicitly sending a JSON null value for `status`.
        // If `status` is not present in the request, treat as invalid input.
        if (! $request->has('status')) {
            return response()->json(['success' => false, 'message' => 'Status inválido'], 422);
        }
        $rawStatus = $request->input('status');
        // null means clear the status
        if (is_null($rawStatus)) {
            $status = null;
        } else {
            $status = trim((string) $rawStatus);
            // empty string is considered invalid
            if ($status === '') {
                return response()->json(['success' => false, 'message' => 'Status inválido'], 422);
            }
        }

        try {
            $candidateId = DB::table('ingresso_candidatos')->where('id', $identifier)->orWhere('num_inscricao', $identifier)->value('id');
            if (! $candidateId) {
                return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
            }

            if (! Schema::hasTable('ingresso_encaminhamentos')) {
                return response()->json(['success' => false, 'message' => 'Recurso de encaminhamentos indisponível'], 404);
            }

            $enc = DB::table('ingresso_encaminhamentos')
                ->where('ingresso_candidato_id', $candidateId)
                ->orderBy('created_at', 'desc')
                ->first();

            if (! $enc) {
                return response()->json(['success' => false, 'message' => 'Nenhum encaminhamento encontrado para este candidato'], 404);
            }

            if (! Schema::hasColumn('ingresso_encaminhamentos', 'status')) {
                return response()->json(['success' => false, 'message' => 'Coluna status inexistente na tabela de encaminhamentos'], 400);
            }

            $upd = ['updated_at' => now()];
            if (is_null($status)) {
                $upd['status'] = null;
            } else {
                $upd['status'] = mb_substr($status, 0, 255);
            }
            DB::table('ingresso_encaminhamentos')->where('id', $enc->id)->update($upd);
            Log::info('Encaminhamento status atualizado', ['user' => optional(Auth::user())->id, 'candidate' => $candidateId, 'encaminhamento' => $enc->id, 'status' => $status]);

            return response()->json(['success' => true, 'message' => is_null($status) ? 'Status removido' : 'Status atualizado', 'status' => $status]);
        } catch (\Throwable $e) {
            Log::error('setEncaminhamentoStatus failed', ['exception' => $e->getMessage(), 'identifier' => $identifier]);
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar status'], 500);
        }
    }

    /**
     * Delete a single encaminhamento (CPM only).
     */
    public function destroyEncaminhamento(Request $request, $identifier, $encaminhamentoId)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Ação não autorizada'], 403);
        }

        // only CPM (sector 2 && profile_id 1) may delete encaminhamentos via this UI
        $user = optional(Auth::user());
        if (!($user && isset($user->sector_id) && isset($user->profile_id) && $user->sector_id == 2 && $user->profile_id == 1)) {
            return response()->json(['success' => false, 'message' => 'Ação permitida apenas para CPM'], 403);
        }

        try {
            $candidateId = DB::table('ingresso_candidatos')->where('id', $identifier)->orWhere('num_inscricao', $identifier)->value('id');
            if (! $candidateId) {
                return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
            }

            if (! Schema::hasTable('ingresso_encaminhamentos')) {
                return response()->json(['success' => false, 'message' => 'Recurso de encaminhamentos indisponível'], 404);
            }

            $enc = DB::table('ingresso_encaminhamentos')->where('id', $encaminhamentoId)->first();
            if (! $enc) {
                return response()->json(['success' => false, 'message' => 'Encaminhamento não encontrado'], 404);
            }

            if (isset($enc->ingresso_candidato_id) && intval($enc->ingresso_candidato_id) !== intval($candidateId)) {
                return response()->json(['success' => false, 'message' => 'Encaminhamento não pertence a este candidato'], 403);
            }

            $deleted = DB::table('ingresso_encaminhamentos')->where('id', $encaminhamentoId)->delete();
            if ($deleted) {
                Log::info('Encaminhamento excluído', ['user' => optional(Auth::user())->id, 'candidate' => $candidateId, 'encaminhamento' => $encaminhamentoId]);
                return response()->json(['success' => true, 'message' => 'Encaminhamento excluído']);
            }

            return response()->json(['success' => false, 'message' => 'Falha ao excluir encaminhamento'], 500);
        } catch (\Throwable $e) {
            Log::error('destroyEncaminhamento error', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Erro no servidor'], 500);
        }
    }

    /**
     * Update candidate main fields (Dados Principais).
     */
    public function updateCandidate(Request $request, $identifier)
    {
        if (! $this->authorizeUser()) {
            return response()->json(['success' => false, 'message' => 'Ação não autorizada'], 403);
        }

        try {
            $candidateId = DB::table('ingresso_candidatos')->where('id', $identifier)->orWhere('num_inscricao', $identifier)->value('id');
            if (! $candidateId) {
                return response()->json(['success' => false, 'message' => 'Candidato não encontrado'], 404);
            }

            $available = Schema::hasTable('ingresso_candidatos') ? Schema::getColumnListing('ingresso_candidatos') : [];

            // Build updates from the submitted payload by intersecting with actual table columns.
            // Exclude primary id and timestamps to avoid accidental overwrite.
            $exclude = ['id', 'created_at', 'updated_at'];
            $columns = array_values(array_diff($available, $exclude));

            $updates = [];
            foreach ($columns as $k) {
                if ($request->has($k)) {
                    $val = $request->input($k);

                    // Normalize empty date-like fields to NULL
                    if ($val === '' && (stripos($k, 'data') !== false || stripos($k, 'date') !== false || substr($k, -3) === '_at')) {
                        $val = null;
                    }

                    // Accept localized dates in DD/MM/YYYY or DD/MM/YYYY HH:MM(:SS) and convert to YYYY-MM-DD[ HH:MM:SS]
                    if (is_string($val)) {
                        if (preg_match('/^\s*(\d{2})\/(\d{2})\/(\d{4})(?:\s+(\d{2}:\d{2}(?::\d{2})?))?\s*$/', $val, $m)) {
                            $date = $m[3] . '-' . $m[2] . '-' . $m[1];
                            if (!empty($m[4])) {
                                $time = $m[4];
                                if (preg_match('/^\d{2}:\d{2}$/', $time)) $time .= ':00';
                                $val = $date . ' ' . $time;
                            } else {
                                $val = $date;
                            }
                        }
                    }

                    $updates[$k] = $val;
                }
            }

            if (!empty($updates)) {
                DB::table('ingresso_candidatos')->where('id', $candidateId)->update($updates);
                $updated = DB::table('ingresso_candidatos')->where('id', $candidateId)->first();
                return response()->json(['success' => true, 'message' => 'Dados atualizados', 'candidate' => $updated]);
            }

            return response()->json(['success' => false, 'message' => 'Nenhum campo para atualizar'], 400);
        } catch (\Throwable $e) {
            Log::error('updateCandidate error', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Erro no servidor'], 500);
        }
    }
}
