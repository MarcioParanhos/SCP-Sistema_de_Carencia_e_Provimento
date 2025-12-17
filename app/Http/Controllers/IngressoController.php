<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class IngressoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function authorizeUser()
    {
        $user = Auth::user();
        // Keep same access rule used in sidebar: only sector 7 and profile 1
        return $user && $user->sector_id == 7 && $user->profile_id == 1;
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

        return view('ingresso.index', ['columns' => $columns]);
    }

    /**
     * Dashboard overview with summary stats (fake data for now)
     */
    public function dashboard()
    {
        if (! $this->authorizeUser()) {
            abort(403);
        }

        // Fake data for visualization
        $stats = [
            'total_candidates' => 1240,
            'ingressados' => 520,
            'pendencia_documentos' => 120,
            'documentos_validados' => 600,
        ];

        $nte_breakdown = [
            ['nte' => 'NTE 01', 'count' => 240],
            ['nte' => 'NTE 02', 'count' => 180],
            ['nte' => 'NTE 03', 'count' => 310],
            ['nte' => 'NTE 04', 'count' => 150],
            ['nte' => 'NTE 05', 'count' => 60],
        ];

        return view('ingresso.dashboard', [
            'stats' => $stats,
            'nte_breakdown' => $nte_breakdown,
        ]);
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
            $searchValue = $request->input('search.value');

            // total records
            $totalRecords = DB::table('ingresso_candidatos')->count();

            // filtered
            $filteredQuery = DB::table('ingresso_candidatos');
            if ($searchValue && count($columns)) {
                $filteredQuery->where(function ($q) use ($columns, $searchValue) {
                    foreach ($columns as $col) {
                        $q->orWhere($col, 'like', "%{$searchValue}%");
                    }
                });
            }

            $recordsFiltered = $filteredQuery->count();

            // ordering
            $order = $request->get('order', []);
            if (!empty($order) && isset($order[0]['column'])) {
                $orderColIndex = intval($order[0]['column']);
                $orderDir = $order[0]['dir'] === 'desc' ? 'desc' : 'asc';
                if (isset($columns[$orderColIndex])) {
                    $filteredQuery->orderBy($columns[$orderColIndex], $orderDir);
                }
            }

            // pagination and fetch
            $data = $filteredQuery->offset($start)->limit($length)->get();

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
}
