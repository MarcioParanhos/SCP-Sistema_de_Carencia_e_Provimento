<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $anoRef = session()->get('ano_ref');

        $logs = Log::with('user')->where('ano_ref', $anoRef)->get();
        return view('logs.show_logs', compact('logs'));
    }
}