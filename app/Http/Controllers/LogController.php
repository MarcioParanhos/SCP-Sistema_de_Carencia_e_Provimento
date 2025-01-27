<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::with('user')->get(); // Assumindo que você tem uma relação user no modelo Log
        return view('logs.show_logs', compact('logs'));
    }
}