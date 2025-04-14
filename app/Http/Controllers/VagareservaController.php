<?php

namespace App\Http\Controllers;

use App\Models\VagaReserva;
use App\Models\Servidore;

use Illuminate\Http\Request;

class VagareservaController extends Controller
{

    public function create(Request $request)
    {


        $servidor = Servidore::where('cadastro', 'LIKE', '%' . $request->cadastro . '%')->first();

        $servidor_id = $servidor->id;


        $vaga_reserva = new VagaReserva;

        $vaga_reserva->carencia_id = $request->carencia_id;
        $vaga_reserva->data_inicio = $request->reserva_inicio;
        $vaga_reserva->data_fim = $request->reserva_fim;
        $vaga_reserva->justificativa = $request->justificativa_reserva;
        $vaga_reserva->servidor_id = $servidor_id;
        $vaga_reserva->user_id = $request->user_id;


        if ($vaga_reserva->save()) {
            return  redirect()->to(url()->previous())->with('msg', 'success');
        }
    }

    public function index()
    {
        $vaga_reserva = VagaReserva::with(['servidor', 'carencia'])->get();
    
        $resumo = [];
    
        foreach ($vaga_reserva as $reserva) {
            $cadastro = $reserva->servidor->cadastro;
    
            if (!isset($resumo[$cadastro])) {
                $resumo[$cadastro] = [
                    'servidor' => $reserva->servidor,
                    'carencia' => $reserva->carencia, // mesma unidade
                    'mat' => 0,
                    'vesp' => 0,
                    'not' => 0,
                    'total' => 0,
                    'disciplinas' => [],
                ];
            }
    
            $resumo[$cadastro]['mat'] += $reserva->carencia->matutino ?? 0;
            $resumo[$cadastro]['vesp'] += $reserva->carencia->vespertino ?? 0;
            $resumo[$cadastro]['not'] += $reserva->carencia->noturno ?? 0;
            $resumo[$cadastro]['total'] += $reserva->carencia->total ?? 0;
    
            if (!empty($reserva->carencia->disciplina)) {
                $resumo[$cadastro]['disciplinas'][] = $reserva->carencia->disciplina;
            }
        }
    
        // Remover disciplinas duplicadas
        foreach ($resumo as &$reserva) {
            $reserva['disciplinas'] = array_unique($reserva['disciplinas']);
        }
    
        return view("provimento.reserva_vaga", [
            'resumo_reservas' => $resumo,
        ]);
    }
    
    
}
