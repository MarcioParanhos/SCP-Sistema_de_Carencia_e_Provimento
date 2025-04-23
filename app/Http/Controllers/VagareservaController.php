<?php

namespace App\Http\Controllers;

use App\Models\VagaReserva;
use App\Models\Servidore;
use App\Models\Provimento;
use App\Models\Carencia;
use Illuminate\Support\Facades\Auth;

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
        $vaga_reserva->forma_suprimento = $request->forma_suprimento;
        $vaga_reserva->tipo_movimentacao = $request->tipo_movimentacao;
        $vaga_reserva->tipo_aula = $request->tipo_aula;


        if ($vaga_reserva->save()) {
            return  redirect()->to(url()->previous())->with('msg', 'Vaga reservada com sucesso!');
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
                    'carencia_ids' => [], // Nova chave para armazenar os IDs das carências
                ];
            }

            $resumo[$cadastro]['mat'] += $reserva->carencia->matutino ?? 0;
            $resumo[$cadastro]['vesp'] += $reserva->carencia->vespertino ?? 0;
            $resumo[$cadastro]['not'] += $reserva->carencia->noturno ?? 0;
            $resumo[$cadastro]['total'] += $reserva->carencia->total ?? 0;

            if (!empty($reserva->carencia->disciplina)) {
                $resumo[$cadastro]['disciplinas'][] = $reserva->carencia->disciplina;
                // Adicionar o ID da carência associada à disciplina
                $resumo[$cadastro]['carencia_ids'][] = $reserva->carencia->id;
            }
        }

        // Remover disciplinas duplicadas e carência_ids duplicados
        foreach ($resumo as &$reserva) {
            $reserva['disciplinas'] = array_unique($reserva['disciplinas']);
            $reserva['carencia_ids'] = array_unique($reserva['carencia_ids']);
        }

        return view("provimento.reserva_vaga", [
            'resumo_reservas' => $resumo,
        ]);
    }


    public function createProvimento(Request $request)
    {

        $user = Auth::user();
        $carencia_ids = array_map('trim', explode(',', $request->input('carencia_ids')));

        // Buscando as carências associadas aos IDs
        $carencias = Carencia::whereIn('id', $carencia_ids)->get();

        foreach ($carencias as $carencia) {
            // Buscar a reserva de vaga relacionada a esta carência
            $vaga_reserva = VagaReserva::where('carencia_id', $carencia->id)->first();

            // Verifica se a reserva de vaga existe e carrega o servidor
            if ($vaga_reserva) {
                $servidor = $vaga_reserva->servidor; // Acessando o servidor diretamente
            }

            // Criando o provimento e salvando as informações da carência, reserva e servidor
            $provimento = new Provimento();
            $provimento->nte = $carencia->nte;
            $provimento->municipio = $carencia->municipio;
            $provimento->unidade_escolar = $carencia->unidade_escolar;
            $provimento->cod_unidade = $carencia->cod_ue;
            $provimento->ano_ref = $carencia->ano_ref;
            $provimento->provimento_matutino = $carencia->matutino;
            $provimento->provimento_vespertino = $carencia->vespertino;
            $provimento->provimento_noturno = $carencia->noturno;
            $provimento->total = $carencia->total;
            $provimento->id_carencia = $carencia->id;
            $provimento->disciplina = $carencia->disciplina;
            $provimento->usuario = $user->name;
            $provimento->data_assuncao = $request->data_assuncao;
            $provimento->data_encaminhamento = $request->data_assuncao;
            $provimento->pch = "PENDENTE";
            $provimento->situacao = "DESBLOQUEADO";
            $provimento->situacao_provimento = "provida";
            $provimento->obs = "Vaga provida mediante a reserva de vaga";
            $provimento->tipo_carencia_provida = $carencia->tipo_carencia;

            // Adicionando informações do servidor e da reserva
            if (isset($servidor)) {
                $provimento->servidor = $servidor->nome;
                $provimento->cadastro = $servidor->cadastro;
                $provimento->vinculo = $servidor->vinculo;
                $provimento->regime = $servidor->regime;

                // Campos da reserva
                $provimento->tipo_movimentacao = $vaga_reserva->tipo_movimentacao;
                $provimento->tipo_aula = $vaga_reserva->tipo_aula;
                $provimento->forma_suprimento = $vaga_reserva->forma_suprimento;
            }

            // Salvando o provimento
            if ($provimento->save()) {
                // Zerando os campos da carência
                $carencia->matutino = 0;
                $carencia->vespertino = 0;
                $carencia->noturno = 0;
                $carencia->total = 0;
                $carencia->save();

                // Excluindo a vaga reserva vinculada
                if ($vaga_reserva) {
                    $vaga_reserva->delete();
                }
            }
        }

        return redirect()->route('reserva.index')->with('msg', 'success');
    }
}