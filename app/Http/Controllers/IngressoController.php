<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('ingresso.index');
    }
}
