<?php

namespace App\Http\Controllers;

use App\Models\RegularizacaoFuncional;
use App\Models\Uee;
use App\Models\Servidore;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class RegularizacaoFuncionalController extends Controller
{
    public function create()
    {

        return view('regularizacao_funcional.regularizacao_create');
    }

    public function store(Request $request)
    {
        $anoRef = session()->get('ano_ref');
        
        $uee = Uee::select('id')->where('cod_unidade', $request->cod_ue_origem)->first();
        $ueeDestino = Uee::select('id')->where('cod_unidade', $request->cod_ue_destino)->first();
        $servidor = Servidore::select('id')->where('cadastro', $request->cadastro)->first();

        $regularizacao_funcional = new RegularizacaoFuncional;
        $regularizacao_funcional->uee_origem_id = $uee->id;
        $regularizacao_funcional->uee_destino_id = $ueeDestino->id;
        $regularizacao_funcional->servidor_id = $servidor->id;
        $regularizacao_funcional->tipo_regularizacao = $request->tipo_regularizacao;
        $regularizacao_funcional->data = $request->data;
        $regularizacao_funcional->uo_origem = $request->uo_origem;
        $regularizacao_funcional->uo_destino = $request->uo_destino;
        $regularizacao_funcional->ano_ref = $anoRef;
        $regularizacao_funcional->create_user = $request->create_user;
        $regularizacao_funcional->tipo_tornar_sem_efeito = $request->tipo_tornar_sem_efeito;
        $regularizacao_funcional->tipo_retificacao = $request->tipo_retificacao;
        $regularizacao_funcional->situacao_cpm = "PENDENTE";
        $regularizacao_funcional->save();

        return  redirect()->to(url()->previous())->with('msg', 'success');
    }

    public function show()
    {

        $anoRef = session()->get('ano_ref');
        $regularizacões_funcionais = RegularizacaoFuncional::where('ano_ref', $anoRef)->where('situacao_cpg', '!=' , 'PROGRAMADO')->get();
        $quantidade_de_registros = $regularizacões_funcionais->count();

        // CPM
        $quantidade_de_registros_pendente_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'PENDENTE');
            })->count();
        $quantidade_de_registros_emAnalise_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'EM ANÁLISE');
            })->count();
        $quantidade_de_registros_servidorAfastado_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'SERVIDOR AFASTADO');
            })->count();
        $quantidade_de_registros_pendenteCPG_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'PENDENTE CPG');
            })->count();
        $quantidade_de_registros_atoEmAprovacao_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'ATO EM APROVAÇÃO');
            })->count();
        $quantidade_de_registros_regularizado_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'REGULARIZADA');
            })->count();

        // CGI    
        $quantidade_de_registros_pendente_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cgi', 'PENDENTE');
        })
            ->count();
        $quantidade_de_registros_emAnalise_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'EM ANÁLISE');
            })->count();
        $quantidade_de_registros_pendenteCPM_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'PENDENTE CPM');
            })->count();
        $quantidade_de_registros_atoEmAprovacao_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'ATO EM APROVAÇÃO');
            })->count();
        $quantidade_de_registros_aguardandoIntegracao_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'AGUARDANDO INTEGRAÇÃO');
            })->count();
        $quantidade_de_registros_regularizado_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'REGULARIZADA');
            })->count();


        // CPG
        $quantidade_de_registros_pendente_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'PENDENTE');
        })
            ->count();
        $quantidade_de_registros_pch_ok = RegularizacaoFuncional::where('ano_ref', $anoRef)->where('situacao_cpg', "PROGRAMADO")->count();
        $quantidade_de_registros_emAnalise_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'EM ANÁLISE');
        })
            ->count();
        $quantidade_de_registros_corrigirDados_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'CORRIGIR REGULARIZAÇÃO');
        })
            ->count();
        $quantidade_de_registros_pendenteCPM_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'PENDENTE CPM');
        })
            ->count();
        $quantidade_de_registros_pendenteCGI_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'PENDENTE CGI');
        })
            ->count();

        session()->put('regularizacões_funcionais', $regularizacões_funcionais);


        return view('regularizacao_funcional.regularizacao_show', [
            'regularizacões_funcionais' => $regularizacões_funcionais,
            'quantidade_de_registros' => $quantidade_de_registros,
            'quantidade_de_registros_pendente_cpm' => $quantidade_de_registros_pendente_cpm,
            'quantidade_de_registros_emAnalise_cpm' => $quantidade_de_registros_emAnalise_cpm,
            'quantidade_de_registros_servidorAfastado_cpm' => $quantidade_de_registros_servidorAfastado_cpm,
            'quantidade_de_registros_pendenteCPG_cpm' => $quantidade_de_registros_pendenteCPG_cpm,
            'quantidade_de_registros_atoEmAprovacao_cpm' => $quantidade_de_registros_atoEmAprovacao_cpm,
            'quantidade_de_registros_regularizado_cpm' => $quantidade_de_registros_regularizado_cpm,

            'quantidade_de_registros_pendente_cgi' => $quantidade_de_registros_pendente_cgi,
            'quantidade_de_registros_emAnalise_cgi' => $quantidade_de_registros_emAnalise_cgi,
            'quantidade_de_registros_pendenteCPM_cgi' => $quantidade_de_registros_pendenteCPM_cgi,
            'quantidade_de_registros_atoEmAprovacao_cgi' => $quantidade_de_registros_atoEmAprovacao_cgi,
            'quantidade_de_registros_aguardandoIntegracao_cgi' => $quantidade_de_registros_aguardandoIntegracao_cgi,
            'quantidade_de_registros_regularizado_cgi' => $quantidade_de_registros_regularizado_cgi,

            'quantidade_de_registros_pendente_cpg' => $quantidade_de_registros_pendente_cpg,
            'quantidade_de_registros_pch_ok' => $quantidade_de_registros_pch_ok,
            'quantidade_de_registros_emAnalise_cpg' => $quantidade_de_registros_emAnalise_cpg,
            'quantidade_de_registros_corrigirDados_cpg' => $quantidade_de_registros_corrigirDados_cpg,
            'quantidade_de_registros_pendenteCPM_cpg' => $quantidade_de_registros_pendenteCPM_cpg,
            'quantidade_de_registros_pendenteCGI_cpg' => $quantidade_de_registros_pendenteCGI_cpg,
        ]);
    }

    public function detail($id)
    {

        $regularizacao = RegularizacaoFuncional::where('id', $id)->first();

        $nome_uee = [];
        $servidor = [];
        $nome_uee_destino = [];

        $uee_id = $regularizacao->uee_origem_id;
        $uee_id_destino = $regularizacao->uee_destino_id;
        $servidor_id = $regularizacao->servidor_id;

        $nome_uee = Uee::select('unidade_escolar', 'nte', 'municipio', 'cod_unidade')->where('id', $uee_id)->first();
        $nome_uee_destino = Uee::select('unidade_escolar', 'nte', 'municipio', 'cod_unidade')->where('id', $uee_id_destino)->first();
        $servidor = Servidore::select('id', 'nome', 'cadastro', 'vinculo', 'regime')->where('id', $servidor_id)->first();

        $nome_uee_origem[$regularizacao->id] = $nome_uee;
        $nome_uee_destino[$regularizacao->id] = $nome_uee_destino;
        $servidor_regularizacao[$regularizacao->id] = $servidor;

        return view('regularizacao_funcional.detail_regularização_funcional', [
            'regularizacao' => $regularizacao,
            'nome_uee_origem' => $nome_uee_origem,
            'nome_uee_destino' => $nome_uee_destino,
            'servidor_regularizacao' => $servidor_regularizacao,
        ]);
    }

    public function update(Request $request)
    {


        $regularizacao = RegularizacaoFuncional::findOrFail($request->regularizacao_id);

        if ($request->situacao_cpm === "REGULARIZADA") {

            $regularizacao->update([

                'situacao_cgi' => 'PENDENTE',
                'situacao_cpm' => 'REGULARIZADA',
                'situacao_cpg' => '',
                'obs_cpm' => $request->obs_cpm,
                'data_ato' => $request->data_ato,
                'num_ato' => $request->num_ato,
            ]);
        } elseif ($request->situacao_cpm === "EM ANÁLISE") {

            $regularizacao->update([

                'situacao_cgi' => '',
                'situacao_cpg' => '',
                'situacao_cpm' => 'EM ANÁLISE',
                'obs_cpm' => $request->obs_cpm,
                'data_ato' => $request->data_ato,
                'num_ato' => $request->num_ato,
            ]);
        } elseif ($request->situacao_cpm === "ATO EM APROVAÇÃO") {

            $regularizacao->update([

                'situacao_cgi' => '',
                'situacao_cpm' => 'ATO EM APROVAÇÃO',
                'situacao_cpg' => '',
                'obs_cpm' => $request->obs_cpm,
                'data_ato' => $request->data_ato,
                'num_ato' => $request->num_ato,
            ]);
        } elseif ($request->situacao_cpm === "PENDENTE") {

            $regularizacao->update([

                'situacao_cgi' => '',
                'situacao_cpm' => 'PENDENTE',
                'situacao_cpg' => '',
                'obs_cpm' => $request->obs_cpm,
                'data_ato' => $request->data_ato,
                'num_ato' => $request->num_ato,
            ]);
        } elseif ($request->situacao_cpm === "PENDENTE CPG") {

            $regularizacao->update([

                'situacao_cgi' => '',
                'situacao_cpm' => 'PENDENTE CPG',
                'situacao_cpg' => 'CORRIGIR REGULARIZAÇÃO',
                'obs_cpm' => $request->obs_cpm,
                'data_ato' => $request->data_ato,
                'num_ato' => $request->num_ato,
            ]);
        } elseif ($request->situacao_cpm === "SERVIDOR AFASTADO") {

            $regularizacao->update([

                'situacao_cgi' => '',
                'situacao_cpm' => 'SERVIDOR AFASTADO',
                'situacao_cpg' => '',
                'obs_cpm' => $request->obs_cpm,
                'data_ato' => $request->data_ato,
                'num_ato' => $request->num_ato,
            ]);
        } elseif ($request->situacao_cgi === "PENDENTE CPM") {

            $regularizacao->update([

                'situacao_cgi' => 'PENDENTE CPM',
                'situacao_cpm' => 'PENDENTE',
                'situacao_cpg' => '',
                'obs_cgi' => $request->obs_cgi,
            ]);
        } elseif ($request->situacao_cgi === "ATO EM APROVAÇÃO") {

            $regularizacao->update([

                'situacao_cgi' => 'ATO EM APROVAÇÃO',
                'situacao_cpm' => 'REGULARIZADO',
                'situacao_cpg' => '',
                'obs_cgi' => $request->obs_cgi,
            ]);
        } elseif ($request->situacao_cgi === "EM ANÁLISE") {

            $regularizacao->update([

                'situacao_cgi' => 'EM ANÁLISE',
                'situacao_cpm' => 'REGULARIZADA',
                'situacao_cpg' => '',
                'obs_cgi' => $request->obs_cgi,
            ]);
        } elseif ($request->situacao_cgi === "REGULARIZADA") {

            $regularizacao->update([
                'situacao_cpm' => 'REGULARIZADA',
                'situacao_cgi' => 'REGULARIZADA',
                'situacao_cpg' => 'PENDENTE',
                'obs_cgi' => $request->obs_cgi,
            ]);
        } elseif ($request->situacao_cgi === "PENDENTE") {

            $regularizacao->update([
                'situacao_cpm' => 'REGULARIZADA',
                'situacao_cgi' => 'PENDENTE',
                'situacao_cpg' => '',
                'obs_cgi' => $request->obs_cgi,
            ]);
        } elseif ($request->situacao_cgi === "AGUARDANDO INTEGRAÇÃO") {

            $regularizacao->update([
                'situacao_cpm' => 'REGULARIZADA',
                'situacao_cgi' => 'AGUARDANDO INTEGRAÇÃO',
                'situacao_cpg' => '',
                'obs_cgi' => $request->obs_cgi,
            ]);
        } elseif ($request->situacao_cpg === "PENDENTE") {

            $regularizacao->update([
                'situacao_cpm' => 'REGULARIZADA',
                'situacao_cgi' => 'REGULARIZADA',
                'situacao_cpg' => 'PENDENTE',
                'obs_cpg' => $request->obs_cpg,
            ]);
        } elseif ($request->situacao_cpg === "EM ANÁLISE") {

            $regularizacao->update([
                'situacao_cpm' => 'REGULARIZADA',
                'situacao_cgi' => 'REGULARIZADA',
                'situacao_cpg' => 'EM ANÁLISE',
                'obs_cpg' => $request->obs_cpg,
            ]);
        } elseif ($request->situacao_cpg === "REGULARIZADA") {

            $regularizacao->update([
                'situacao_cpm' => 'REGULARIZADA',
                'situacao_cgi' => 'REGULARIZADA',
                'situacao_cpg' => 'PROGRAMADO',
                'obs_cpg' => $request->obs_cpg,
            ]);
        } elseif ($request->situacao_cpg === "PENDENTE CPM") {

            $regularizacao->update([
                'situacao_cpm' => 'PENDENTE',
                'situacao_cgi' => '',
                'situacao_cpg' => 'PENDENTE CPM',
                'obs_cpg' => $request->obs_cpg,
            ]);
        } elseif ($request->situacao_cpg === "PENDENTE CGI") {

            $regularizacao->update([
                'situacao_cpm' => 'REGULARIZADA',
                'situacao_cgi' => 'PENDENTE',
                'situacao_cpg' => 'PENDENTE CGI',
                'obs_cpg' => $request->obs_cpg,
            ]);
        } elseif ($request->situacao_cpg === "CORRIGIR REGULARIZAÇÃO") {

            $regularizacao->update([
                'situacao_cpm' => 'PENDENTE CPG',
                'situacao_cgi' => '',
                'situacao_cpg' => 'CORRIGIR REGULARIZAÇÃO',
                'obs_cpg' => $request->obs_cpg,
            ]);
        }

        if ($request->update_user_cpm) {
            $regularizacao->update([
                'update_user_cpm' => $request->update_user_cpm,
            ]);
        }

        if ($request->update_user_cgi) {
            $regularizacao->update([
                'update_user_cgi' => $request->update_user_cgi,
            ]);
        }

        if ($request->update_user_cpg) {
            $regularizacao->update([
                'update_user_cpg' => $request->update_user_cpg,
            ]);
        }

        if ($request->data) {
            $regularizacao->update([
                'data' => $request->data,
            ]);
        }

        if ($request->tipo_regularizacao) {
            $regularizacao->update([
                'tipo_regularizacao' => $request->tipo_regularizacao,
            ]);
        }

        return  redirect()->to(url()->previous())->with('msg', 'success');
    }

    public function filtered(Request $request)
    {

        $regularizacões_funcionais = RegularizacaoFuncional::query();

        $anoRef = session()->get('ano_ref');
        $quantidade_de_registros = $regularizacões_funcionais->count();
        // CPM
        $quantidade_de_registros_pendente_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'PENDENTE');
            })->count();
        $quantidade_de_registros_emAnalise_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'EM ANÁLISE');
            })->count();
        $quantidade_de_registros_servidorAfastado_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'SERVIDOR AFASTADO');
            })->count();
        $quantidade_de_registros_pendenteCPG_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'PENDENTE CPG');
            })->count();
        $quantidade_de_registros_atoEmAprovacao_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'ATO EM APROVAÇÃO');
            })->count();
        $quantidade_de_registros_regularizado_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'REGULARIZADA');
            })->count();

        // CGI    
        $quantidade_de_registros_pendente_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cgi', 'PENDENTE');
        })
            ->count();
        $quantidade_de_registros_emAnalise_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'EM ANÁLISE');
            })->count();
        $quantidade_de_registros_pendenteCPM_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'PENDENTE CPM');
            })->count();
        $quantidade_de_registros_atoEmAprovacao_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'ATO EM APROVAÇÃO');
            })->count();
        $quantidade_de_registros_aguardandoIntegracao_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'AGUARDANDO INTEGRAÇÃO');
            })->count();
        $quantidade_de_registros_regularizado_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'REGULARIZADA');
            })->count();
        $quantidade_de_registros_pendente_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'PENDENTE');
        })
            ->count();
        $quantidade_de_registros_pch_ok = RegularizacaoFuncional::where('ano_ref', $anoRef)->where('situacao_cpg', "PROGRAMADO")->count();
        $quantidade_de_registros_emAnalise_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'EM ANÁLISE');
        })
            ->count();
        $quantidade_de_registros_corrigirDados_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'CORRIGIR REGULARIZAÇÃO');
        })
            ->count();
        $quantidade_de_registros_pendenteCPM_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'PENDENTE CPM');
        })
            ->count();
        $quantidade_de_registros_pendenteCGI_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'PENDENTE CGI');
        })
            ->count();

        if ($request->filled('cpm_seacrh')) {
            $regularizacões_funcionais = $regularizacões_funcionais->where('situacao_cpm', $request->cpm_seacrh);
        }

        if ($request->filled('cgi_seacrh')) {
            $regularizacões_funcionais = $regularizacões_funcionais->where('situacao_cgi', $request->cgi_seacrh);
        }

        if ($request->filled('cpg_seacrh')) {
            $regularizacões_funcionais = $regularizacões_funcionais->where('situacao_cpg', $request->cpg_seacrh);
        }

        if ($request->filled('tipo_regularizacao_search')) {
            $regularizacões_funcionais = $regularizacões_funcionais->where('tipo_regularizacao', $request->tipo_regularizacao_search);
        }

        if ($request->filled('nte_seacrh')) {
            $regularizacões_funcionais = $regularizacões_funcionais->whereHas('ueeDestino', function ($query) use ($request) {
                $query->where('nte', $request->nte_seacrh);
            });
        }

        if ($request->filled('municipio_search')) {
            $regularizacões_funcionais = $regularizacões_funcionais->whereHas('ueeDestino', function ($query) use ($request) {
                $query->where('municipio', $request->municipio_search);
            });
        }

        if ($request->filled('search_uee')) {
            $regularizacões_funcionais = $regularizacões_funcionais->whereHas('ueeDestino', function ($query) use ($request) {
                $query->where('unidade_escolar', $request->search_uee);
            });
        }

        if ($request->filled('search_cadastro')) {
            $regularizacões_funcionais = $regularizacões_funcionais->whereHas('servidor', function ($query) use ($request) {
                $query->where('cadastro', $request->search_cadastro);
            });
        }

        $regularizacões_funcionais = $regularizacões_funcionais->where('ano_ref', $anoRef)->get();



        session()->put('regularizacões_funcionais', $regularizacões_funcionais);


        return view('regularizacao_funcional.regularizacao_show', [

            'regularizacões_funcionais' => $regularizacões_funcionais,
            'quantidade_de_registros' => $quantidade_de_registros,
            'quantidade_de_registros_pendente_cpm' => $quantidade_de_registros_pendente_cpm,
            'quantidade_de_registros_emAnalise_cpm' => $quantidade_de_registros_emAnalise_cpm,
            'quantidade_de_registros_servidorAfastado_cpm' => $quantidade_de_registros_servidorAfastado_cpm,
            'quantidade_de_registros_pendenteCPG_cpm' => $quantidade_de_registros_pendenteCPG_cpm,
            'quantidade_de_registros_atoEmAprovacao_cpm' => $quantidade_de_registros_atoEmAprovacao_cpm,
            'quantidade_de_registros_regularizado_cpm' => $quantidade_de_registros_regularizado_cpm,

            'quantidade_de_registros_pendente_cgi' => $quantidade_de_registros_pendente_cgi,
            'quantidade_de_registros_emAnalise_cgi' => $quantidade_de_registros_emAnalise_cgi,
            'quantidade_de_registros_pendenteCPM_cgi' => $quantidade_de_registros_pendenteCPM_cgi,
            'quantidade_de_registros_atoEmAprovacao_cgi' => $quantidade_de_registros_atoEmAprovacao_cgi,
            'quantidade_de_registros_aguardandoIntegracao_cgi' => $quantidade_de_registros_aguardandoIntegracao_cgi,
            'quantidade_de_registros_regularizado_cgi' => $quantidade_de_registros_regularizado_cgi,

            'quantidade_de_registros_pendente_cpg' => $quantidade_de_registros_pendente_cpg,
            'quantidade_de_registros_pch_ok' => $quantidade_de_registros_pch_ok,
            'quantidade_de_registros_emAnalise_cpg' => $quantidade_de_registros_emAnalise_cpg,
            'quantidade_de_registros_corrigirDados_cpg' => $quantidade_de_registros_corrigirDados_cpg,
            'quantidade_de_registros_pendenteCPM_cpg' => $quantidade_de_registros_pendenteCPM_cpg,
            'quantidade_de_registros_pendenteCGI_cpg' => $quantidade_de_registros_pendenteCGI_cpg,
        ]);
    }

    public function excel_regularizacao_funcional()
    {

        $regularizacões_funcionais = session()->get('regularizacões_funcionais');
        $nomes_uee = session()->get('nomes_uee');
        $nomes_uee_destino = session()->get('nomes_uee_destino');
        $nomes_uee_origem = session()->get('nomes_uee_origem');
        $servidores = session()->get('servidores');

        return view('excel.excel_regularizacao_funcional', [
            'regularizacões_funcionais' => $regularizacões_funcionais,
            'nomes_uee' => $nomes_uee,
            'nomes_uee_destino' => $nomes_uee_destino,
            'nomes_uee_origem' => $nomes_uee_origem,
            'servidores' => $servidores,
        ]);
    }

    public function destroy_regularizacao($id)
    {

        RegularizacaoFuncional::findOrFail($id)->delete();
        return redirect('/regularizacao_funcional/view')->with('msg', 'delete_success');
    }

    public function filter()
    {
        // Recuperar dados da sessão
        $regularizacões_funcionais = session()->get('regularizacões_funcionais');
        $anoRef = session()->get('ano_ref');

        // Verificar se os dados da sessão estão desatualizados
        $dados_atualizados = RegularizacaoFuncional::where('ano_ref', $anoRef)->get();

        // Se houver dados na sessão e houver diferença entre os dados da sessão e os dados atualizados no banco de dados
        if ($regularizacões_funcionais && $dados_atualizados->isNotEmpty()) {
            $dados_atualizados->each(function ($item, $key) use ($regularizacões_funcionais) {
                $registro_sessao = $regularizacões_funcionais->where('id', $item->id)->first();
                if ($registro_sessao) {
                    if ($item->updated_at > $registro_sessao->updated_at) {
                        // Atualizar registro na sessão
                        $registro_sessao->fill($item->toArray())->save();
                    }
                }
            });
        }

        $quantidade_de_registros = $dados_atualizados->count();
        // CPM
        $quantidade_de_registros_pendente_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'PENDENTE');
            })->count();
        $quantidade_de_registros_emAnalise_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'EM ANÁLISE');
            })->count();
        $quantidade_de_registros_servidorAfastado_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'SERVIDOR AFASTADO');
            })->count();
        $quantidade_de_registros_pendenteCPG_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'PENDENTE CPG');
            })->count();
        $quantidade_de_registros_atoEmAprovacao_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'ATO EM APROVAÇÃO');
            })->count();
        $quantidade_de_registros_regularizado_cpm = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cpm', 'REGULARIZADA');
            })->count();

        // CGI    
        $quantidade_de_registros_pendente_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cgi', 'PENDENTE');
        })
            ->count();
        $quantidade_de_registros_emAnalise_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'EM ANÁLISE');
            })->count();
        $quantidade_de_registros_pendenteCPM_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'PENDENTE CPM');
            })->count();
        $quantidade_de_registros_atoEmAprovacao_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'ATO EM APROVAÇÃO');
            })->count();
        $quantidade_de_registros_aguardandoIntegracao_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'AGUARDANDO INTEGRAÇÃO');
            })->count();
        $quantidade_de_registros_regularizado_cgi = RegularizacaoFuncional::where('ano_ref', $anoRef)
            ->where(function ($query) {
                $query->where('situacao_cgi', 'REGULARIZADA');
            })->count();
        $quantidade_de_registros_pendente_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'PENDENTE');
        })
            ->count();
        $quantidade_de_registros_pch_ok = RegularizacaoFuncional::where('ano_ref', $anoRef)->where('situacao_cpg', "PROGRAMADO")->count();
        $quantidade_de_registros_emAnalise_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'EM ANÁLISE');
        })
            ->count();
        $quantidade_de_registros_corrigirDados_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'CORRIGIR REGULARIZAÇÃO');
        })
            ->count();
        $quantidade_de_registros_pendenteCPM_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
            $query->where('situacao_cpg', 'PENDENTE CPM');
        })
            ->count();
            $quantidade_de_registros_pendenteCGI_cpg = RegularizacaoFuncional::where('ano_ref', $anoRef)->where(function ($query) {
                $query->where('situacao_cpg', 'PENDENTE CGI');
            })
                ->count();


        // Retornar a visualização com os dados atualizados
        return view('regularizacao_funcional.regularizacao_show', [
            'regularizacões_funcionais' => $regularizacões_funcionais,
            'quantidade_de_registros' => $quantidade_de_registros,
            'quantidade_de_registros_pendente_cpm' => $quantidade_de_registros_pendente_cpm,
            'quantidade_de_registros_emAnalise_cpm' => $quantidade_de_registros_emAnalise_cpm,
            'quantidade_de_registros_servidorAfastado_cpm' => $quantidade_de_registros_servidorAfastado_cpm,
            'quantidade_de_registros_pendenteCPG_cpm' => $quantidade_de_registros_pendenteCPG_cpm,
            'quantidade_de_registros_atoEmAprovacao_cpm' => $quantidade_de_registros_atoEmAprovacao_cpm,
            'quantidade_de_registros_regularizado_cpm' => $quantidade_de_registros_regularizado_cpm,

            'quantidade_de_registros_pendente_cgi' => $quantidade_de_registros_pendente_cgi,
            'quantidade_de_registros_emAnalise_cgi' => $quantidade_de_registros_emAnalise_cgi,
            'quantidade_de_registros_pendenteCPM_cgi' => $quantidade_de_registros_pendenteCPM_cgi,
            'quantidade_de_registros_atoEmAprovacao_cgi' => $quantidade_de_registros_atoEmAprovacao_cgi,
            'quantidade_de_registros_aguardandoIntegracao_cgi' => $quantidade_de_registros_aguardandoIntegracao_cgi,
            'quantidade_de_registros_regularizado_cgi' => $quantidade_de_registros_regularizado_cgi,

            'quantidade_de_registros_pendente_cpg' => $quantidade_de_registros_pendente_cpg,
            'quantidade_de_registros_pch_ok' => $quantidade_de_registros_pch_ok,
            'quantidade_de_registros_emAnalise_cpg' => $quantidade_de_registros_emAnalise_cpg,
            'quantidade_de_registros_corrigirDados_cpg' => $quantidade_de_registros_corrigirDados_cpg,
            'quantidade_de_registros_pendenteCPM_cpg' => $quantidade_de_registros_pendenteCPM_cpg,
            'quantidade_de_registros_pendenteCGI_cpg' => $quantidade_de_registros_pendenteCGI_cpg,
        ]);
    }
}
