<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manutencao;

class ManutencaoController extends Controller
{
    public function index () {

        $manutencoes = Manutencao::all();

        return view('manutencoes.show_manutencoes', [
            'manutencoes' => $manutencoes,
        ]);

    }

    public function create()
    {

        return view('manutencoes.create_manutencao');
    }
}