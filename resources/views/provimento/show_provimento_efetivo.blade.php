@extends('layout.main')

@section('title', 'SCP - Encaminhamento')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif
<style>
    .mult-select-tag .body {
        display: flex;
        border: 1px solid #AAAAAA !important;
        background: #fff !important;
        min-height: 2.15rem;
        width: 100%;
        min-width: 14rem;
        border-radius: 5px !important;
    }

    .mult-select-tag .item-container {
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fbf8f3 !important;
        padding: .2rem .4rem;
        margin: .2rem;
        font-weight: 500;
        border: 1px solid #fbf8f3 !important;
        background: #36425a !important;
        border-radius: 5px !important;
    }

    .mult-select-tag .item-label {
        max-width: 100%;
        line-height: 1;
        font-size: .75rem;
        font-weight: 400;
        flex: 0 1 auto;
        color: #fbf8f3 !important;
    }
</style>

<div class="bg-primary card text-white card_title">
    <h4 class=" title_show_carencias">Encaminhamento de servidores efetivos - CONCURSADOS 2023</h4>
</div>
<div class="d-flex justify-content-between mb-4">
    <div class="col-3">
        <table class="table-bordered">
            <tr>
                <td class="pl-2 subheader"><b>Servidores efetivos encaminhados</b></td>
                <td style="width: 20%;" class="text-center"><b>{{ $quantidadeRegistros }}</b></td>
            </tr>
            <tr>
                <td class="pl-2 subheader"><b>Encaminhamentos com inconsistência</b></td>
                <td style="width: 20%;" class="text-center"><b>{{ ($quantidadeRegistrosError - $quantidadeRegistrosErrorOK)}}</b></td>
            </tr>
            <tr>
                <td class="pl-2 subheader"><b>Inconsistências ajustadas (CPM)</b></td>
                <td style="width: 20%;" class="text-center"><b>{{ $quantidadeRegistrosErrorOK}}</b></td>
            </tr>
            <tr>
                <td class="pl-2 subheader"><b>PCH - Programado</b></td>
                <td style="width: 20%;" class="text-center"><b>{{ $quantidadeRegistrosPCH }}</b></td>
            </tr>
            <tr>
                <td class="pl-2 subheader"><b>PENDENTES ANÁLISE (CPG)</b></td>
                <td style="width: 20%;" class="text-center"><b>{{ ($quantidadeRegistros - $quantidadeRegistrosPCH) - ($quantidadeRegistrosError - $quantidadeRegistrosErrorOK)}}</b></td>
            </tr>
        </table>
    </div>

    <div class="mb-2 ">
        <a id="active_filters" class="mb-2 btn bg-primary text-white" onclick="active_filters_provimento()">FILTROS <i class='far fa-eye'></i></a>
        <a class="mb-2 btn bg-primary text-white" target="_blank" href="/provimento/efetivo/excel"><i class="ti-download"></i> EXCEL</a>
    </div>
</div>
<hr>
<form id="active_form" class="pr-4 pl-4" action="{{ route('provimento_efetivo.showByForm') }}" method="post" hidden>
    @csrf
    <div class="form-row">
        <div class="col-md-1">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_nte_provimento_efetivos">NTE</label>
                <select name="search_nte_provimento_efetivos" id="nte_seacrh" class="form-control form-control-sm select2">
                    <option></option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                    <option>9</option>
                    <option>10</option>
                    <option>11</option>
                    <option>12</option>
                    <option>13</option>
                    <option>14</option>
                    <option>15</option>
                    <option>16</option>
                    <option>17</option>
                    <option>18</option>
                    <option>19</option>
                    <option>20</option>
                    <option>21</option>
                    <option>22</option>
                    <option>23</option>
                    <option>24</option>
                    <option>25</option>
                    <option>26</option>
                    <option>27</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_municipio_provimento_efetivos">MUNICIPIO</label>
                <select name="search_municipio_provimento_efetivos" id="municipio_search" class="form-control form-control-sm select2">

                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_uee_provimento_efetivos">NOME DA UNIDADE ESCOLAR</label>
                <select name="search_uee_provimento_efetivos" id="search_uee" class="form-control form-control-sm select2">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="search_codigo_unidade_escolar_efetivo" class="">COD. UE</label>
                <input value="" name="search_codigo_unidade_escolar_efetivo" id="search_codigo_unidade_escolar" type="text" class="form-control form-control-sm">
            </div>
        </div>
    </div>
    <div class="form-row mb-4">
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label for="search_cpf_servidor_efetivo" class="">CPF</label>
                <input value="" name="search_cpf_servidor_efetivo" id="search_matricula_servidor" type="number" class="form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_disciplina_efetivo">SELECIONE UMA OU MAIS DISCIPLINAS</label>
                <select name="search_disciplina_efetivo[]" class="form-control form-control-sm" id="search_disciplina" multiple>
                    @foreach ($disciplinas as $disciplina)
                    <option value="{{ $disciplina->formacao }}">{{ $disciplina->formacao }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_situation_efetivo">SITUAÇÃO</label>
                <select name="search_situation_efetivo" id="search_situation_efetivo" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="3">REAPROVEITADO NA UEE</option>
                    <option value="1">EXCEDENTE</option>
                    <option value="2">PROVIMENTO INCORRETO</option>
                    <option value="5">REDA DESLIGAMENTO</option>
                    <option value="4">EFETIVO EM LICENÇA</option>
                    <option value="6">APOSENTADORIA</option>
                    <option value="7">DEIXAR HORAS EXTRAS</option>
                    <option value="8">COORD. PEDAGÓGICO</option>
                    <option value="9">VAGA REAL</option>
                    <option value="10">VAGA DE CARGO</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group_disciplina">
                <label class="control-label" for="search_pch_efetivo">PCH</label>
                <select name="search_pch_efetivo" id="search_pch_efetivo" class="form-control form-control-sm select2">
                    <option></option>
                    <option value="OK">PROGRAMADO</option>
                    <option value="PENDENTE">PENDENTE</option>
                </select>
            </div>
        </div>
    </div>
    <button type="submit" class="mb-4 btn btn-primary">BUSCAR</button>
    <hr>
</form>
<div class="table-responsive">
    <table id="consultarCarencias" class="table-bordered table-sm table">
        <thead class="bg-primary text-white">
            <tr class="text-center">
                <td colspan="5"><strong>SERVIDOR ENCAMINHADO</strong></td>
                <td colspan="6"><strong>UNIDADE DE ENCAMINHAMENTO</strong></td>
            </tr>
            <tr class="text-center">
                <th>NTE</th>
                <th>MUNICIPIO</th>
                <th>NOME</th>
                <th>CPF</th>
                <th>DISCIPLINA</th>
                <th>NTE</th>
                <th>MUNICIPIO</th>
                <th>COD.UEE</th>
                <th>UNIDADE ESCOLAR</th>
                <th>PCH</th>
                <th>AÇÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($provimentos_encaminhados as $provimentos_encaminhado)
            <tr>
                @if ($provimentos_encaminhado->servidorEncaminhado->nte > 9)
                <td class="text-center">{{ $provimentos_encaminhado->servidorEncaminhado->nte }}</td>
                @else
                <td class="text-center">0{{ $provimentos_encaminhado->servidorEncaminhado->nte }}</td>
                @endif
                <td class="text-center">{{ $provimentos_encaminhado->servidorEncaminhado->municipio }}</td>
                <td class="text-center">{{ $provimentos_encaminhado->servidorEncaminhado->nome }}</td>
                <td class="text-center">{{ $provimentos_encaminhado->servidorEncaminhado->cpf }}</td>
                <td class="text-center">{{ $provimentos_encaminhado->servidorEncaminhado->formacao }}</td>
                @if ($provimentos_encaminhado->uee->nte > 9)
                <td class="text-center">{{ $provimentos_encaminhado->uee->nte }}</td>
                @else
                <td class="text-center">0{{ $provimentos_encaminhado->uee->nte }}</td>
                @endif
                <td class="text-center">{{ $provimentos_encaminhado->uee->municipio }}</td>
                <td class="text-center">{{ $provimentos_encaminhado->uee->cod_unidade }}</td>
                <td class="text-center">{{ $provimentos_encaminhado->uee->unidade_escolar }}</td>
                @if ($provimentos_encaminhado->pch === "OK")
                @if (($provimentos_encaminhado->server_1_situation != 2) && ($provimentos_encaminhado->server_2_situation != 2))
                <td class="d-flex justify-content-center align-items-center">
                    <span class="bg-success tipo_carencia"><i class="ti-check"></i></span>
                </td>
                @else
                <td class="d-flex justify-content-center align-items-center">
                    <a class="bg-danger tipo_carencia info"><i class="fas fa-exclamation-triangle"></i></a>
                </td>
                @endif
                @elseif (($provimentos_encaminhado->server_1_situation == 2) || ($provimentos_encaminhado->server_2_situation == 2))
                @if ($provimentos_encaminhado->inconsistencia === "OK")
                <td class="d-flex justify-content-center align-items-center">
                    <a class="bg-success tipo_carencia info"><i class="fas fa-exclamation-triangle"></i></a>
                </td>
                @else
                <td class="d-flex justify-content-center align-items-center">
                    <a class="bg-danger tipo_carencia info"><i class="fas fa-exclamation-triangle"></i></a>
                </td>
                @endif
                @else
                <td class="">
                </td>
                @endif
                <td class="text-center">
                    <div class="btn-group dropleft">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu">
                            <a class="text-primary dropdown-item" href="/provimento/efetivo/detail/{{ $provimentos_encaminhado->id }}"><i class="fas fa-eye"></i> Ver</a>
                            @if ( (Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_coordenador"))
                            @if (($provimentos_encaminhado->pch != "OK") || (Auth::user()->profile === "administrador"))
                            <a title="Excluir" id="" onclick="destroyProvimentoEfetivo('{{ $provimentos_encaminhado->id }}')" class="text-danger dropdown-item"><i class="ti-trash"></i> Excluir</a>
                            @endif
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const session_message = document.getElementById("session_message");

        if (session_message) {
            if (session_message.value === "error") {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Já existe um servidor com esse CPF registrado.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Encaminhamento excluido com sucesso!',
                })
            } else {

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Motivo de vaga adicionado com sucesso!',
                    showConfirmButton: true,
                })
            }
        }

        const elementosInfo = document.querySelectorAll(".info");

        // Adiciona um ouvinte de evento de clique a cada um deles
        elementosInfo.forEach(function(elemento) {
            elemento.addEventListener("click", function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Há inconsistências no encaminhamento (Provimento incorreto).',
                })
            });
        });
    });
</script>