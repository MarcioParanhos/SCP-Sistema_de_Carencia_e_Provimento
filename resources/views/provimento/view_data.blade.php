@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')
<style>
    .footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .footer h5 {
        font-size: 16px;
        font-weight: 400;
    }

    .total {
        background-color: #CED4DA;
    }

    .header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: -5px;
    }

    .title_nte {
        display: flex !important;
        align-items: center !important;
        justify-content: start !important;
        margin: 15px 0 -10px 0;
        border: 1px solid #CED4DA;
        border-radius: 5px;
        padding: 5px;
        background-color: #fff;
    }

    h6 {
        font-size: 14px;
    }

    th {
        background-color: #36425A;
        color: #fbf8f3;
    }
</style>

<div class="header">
    <h3 class="text-center">STATUS DE NECESSIDADE DE SERVIDORES POR ÁREA</h3>
</div>
<div class="form_content mb-0">
    <div class="mb-2 print-btn">
        <a id="active_filters" class="mb-2 btn bg-primary text-white" onclick="active_filters_provimento()">FILTROS <i class='far fa-eye'></i></a>
        <!-- <a class="mb-2 btn bg-primary text-white" href="#"><i class="ti-printer"></i> IMPRIMIR</a> -->
        <a class="mb-2 btn bg-primary text-white" href="{{ route('data.excel') }}"><i class="ti-download"></i> EXCEL</a>
    </div>
    <form id="active_form" class="pr-4 pl-4" action="{{ route('provimentos.viewData') }}" method="post" hidden>
        @csrf
        <div class="form-row mb-4">
            <div class="col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="nte_seacrh">NTE</label>
                    <select name="nte_seacrh" id="nte_seacrh" class="form-control form-control-sm select2">
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
                    <label class="control-label" for="municipio_search">MUNICIPIO</label>
                    <select name="municipio_search" id="municipio_search" class="form-control form-control-sm select2">
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group_disciplina">
                    <label class="control-label" for="search_uee">UNIDADE ESCOLAR</label>
                    <select name="search_uee" id="search_uee" class="form-control form-control-sm select2">
                        <option></option>
                    </select>
                </div>
            </div>
            <!-- <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label for="codigo_unidade_escolar" class="">COD. UE</label>
                    <input value="" name="search_codigo_unidade_escolar" id="search_codigo_unidade_escolar" type="text" class="form-control form-control-sm">
                </div>
            </div> -->
        </div>
        <button type="submit" class="mb-4 btn btn-primary">BUSCAR</button>
    </form>
    <hr>
</div>

<div class="header">
    <h5 class="text-center">GERAL NTE</h5>
</div>
<div class="table-responsive">
    @forelse ($dadosSeparadosNte as $nte => $dados)
    <div class="title_nte">
        @if($nte > 9)
        <h6>NTE {{ $nte }}</h6>
        @else
        <h6>NTE 0{{ $nte }}</h6>
        @endif
    </div>
    <div hidden>
        {{$total = 0}}
        {{$chReal = 0}}
        {{$chTemp = 0}}
        {{$redasReal = 0}}
        {{$redasTemp = 0}}
        {{$redasTotal = 0}}
    </div>
    <table class="table table-sm table-bordered table-hover">
        <thead>
            <tr>
                <th class="border-0 text-center" style="vertical-align: middle; width: 30%;" scope="col">ÁREA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TEMPORÁRIA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. TEMP</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TOTAL (REAL + TEMP)</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD TOTAL DE REDAS</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dados as $linha)
            <tr>
                @if ($linha->area === 'AREA TECNICA')
                <td>{{ $linha->area }} - PROFISSIONALIZANTE</td>
                @else
                <td>{{ $linha->area }}</td>
                @endif
                <td class="text-center">{{ $linha->total_real }} h</td>
                <td class="text-center">{{ round($linha->total_real / 16, 1, PHP_ROUND_HALF_UP) }}</td>
                <td class="text-center">{{ $linha->total_temp }} h</td>
                <td class="text-center">{{ round($linha->total_temp / 16, 1, PHP_ROUND_HALF_UP) }}</td>
                <td class="text-center">{{ $linha->total }} h</td>
                <td class="text-center">{{ round($linha->total_temp / 16, 1, PHP_ROUND_HALF_UP) + round($linha->total_real / 16, 1, PHP_ROUND_HALF_UP) }}</td>
            </tr>
            <div hidden>
                {{$total = $total + $linha->total}}
                {{$chReal = $chReal + $linha->total_real}}
                {{$chTemp = $chTemp + $linha->total_temp}}
                {{$redasReal = $redasReal + round($linha->total_real / 16, 1, PHP_ROUND_HALF_UP)}}
                {{$redasTemp = $redasTemp + round($linha->total_temp / 16, 1, PHP_ROUND_HALF_UP)}}
                {{$redasTotal = $redasTotal + round($linha->total / 16, 1, PHP_ROUND_HALF_UP)}}
            </div>
            @empty
            <tr>
                <td colspan="7" class="text-center">Não existem registros</td>
            </tr>
            @endforelse
            <tr class="total">
                <td class="text-center border-0"><strong>TOTAL</strong></td>
                <td class="text-center border-0"><strong>{{ $chReal }} h</strong></td>
                <td class="border-0 text-center"><strong>{{ $redasReal }}</strong></td>
                <td class="border-0 text-center"><strong>{{ $chTemp }} h</strong></td>
                <td class="border-0 text-center"><strong>{{ $redasTemp }}</strong></td>
                <td class="border-0 text-center"><strong>{{ $total }} h</strong></td>
                <td class="border-0 text-center"><strong>{{ $redasTotal }}</strong></td>
            </tr>
        </tbody>
    </table>
    @empty
    <div class="title_nte">
        @if($nte > 9)
        <h6>NTE {{ $nte }}</h6>
        @else
        <h6>NTE 0{{ $nte }}</h6>
        @endif
    </div>
    <table class="table table-sm table-bordered table-hover">
        <thead>
            <tr>
                <th class="border-0 text-center" style="vertical-align: middle; width: 30%;" scope="col">ÁREA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TEMPORÁRIA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. TEMP</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TOTAL (REAL + TEMP)</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD TOTAL DE REDAS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center" colspan="7">NTE SEM CARÊNCIA</td>
            </tr>
            <tr class="total">
                <td class="text-center border-0"><strong>TOTAL</strong></td>
                <td class="text-center border-0"><strong>0 h</strong></td>
                <td class="border-0 text-center"><strong>0</strong></td>
                <td class="border-0 text-center"><strong>0 h</strong></td>
                <td class="border-0 text-center"><strong>0</strong></td>
                <td class="border-0 text-center"><strong>0 h</strong></td>
                <td class="border-0 text-center"><strong>0</strong></td>
            </tr>
        </tbody>
    </table>
    @endforelse
</div>

<!-- TABLE VIEW POR MUNICIPIO -->
@if ($statusForMunicipioView === true)
<hr>
<div class="header">
    <h5 class="text-center">GERAL MUNICÍPIO</h5>
</div>
<div class="table-responsive">
    @forelse ($dadosSeparadosNteMunicipio as $nte => $municipios)
    @forelse ($municipios as $municipio => $areas)
    <div class="title_nte">
        <h6>MUNICÍPIO - {{ $municipio }}</h6>
    </div>
    <div hidden>
        {{$total = 0}}
        {{$chReal = 0}}
        {{$chTemp = 0}}
        {{$redasReal = 0 }}
        {{$redasTemp = 0 }}
        {{$redasTotal= 0 }}
    </div>
    <table class="table table-sm table-bordered table-hover">
        <thead>
            <tr>
                <th class="border-0 text-center" style="vertical-align: middle; width: 30%;" scope="col">ÁREA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TEMPORÁRIA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. TEMP</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TOTAL (REAL + TEMP)</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD TOTAL DE REDAS</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($areas as $linha)
            <tr>
                @if ($linha->area === 'AREA TECNICA')
                <td>{{ $linha->area }} - PROFISSIONALIZANTE</td>
                @else
                <td>{{ $linha->area }}</td>
                @endif
                <td class="text-center">{{ $linha->total_real }} h</td>
                <td class="text-center">{{ round($linha->total_real / 16, 1, PHP_ROUND_HALF_UP) }}</td>
                <td class="text-center">{{ $linha->total_temp }} h</td>
                <td class="text-center">{{ round($linha->total_temp / 16, 1, PHP_ROUND_HALF_UP) }}</td>
                <td class="text-center">{{ $linha->total }} h</td>
                <td class="text-center">{{ round($linha->total_temp / 16, 1, PHP_ROUND_HALF_UP) + round($linha->total_real / 16, 1, PHP_ROUND_HALF_UP) }}</td>
            </tr>
            <div hidden>
                {{$total = $total + $linha->total}}
                {{$chReal = $chReal + $linha->total_real}}
                {{$chTemp = $chTemp + $linha->total_temp}}
                {{$redasReal = $redasReal + round($linha->total_real / 16, 1, PHP_ROUND_HALF_UP)}}
                {{$redasTemp = $redasTemp + round($linha->total_temp / 16, 1, PHP_ROUND_HALF_UP)}}
                {{$redasTotal = $redasTotal + round($linha->total / 16, 1, PHP_ROUND_HALF_UP)}}
            </div>
            @empty
            <tr>
                <td colspan="7" class="text-center">Não existem registros</td>
            </tr>
            @endforelse
            <tr class="total">
                <td class="text-center border-0"><strong>TOTAL</strong></td>
                <td class="text-center border-0"><strong>{{ $chReal }} h</strong></td>
                <td class="border-0 text-center"><strong>{{ $redasReal }}</strong></td>
                <td class="border-0 text-center"><strong>{{ $chTemp }} h</strong></td>
                <td class="border-0 text-center"><strong>{{ $redasTemp }}</strong></td>
                <td class="border-0 text-center"><strong>{{ $total }} h</strong></td>
                <td class="border-0 text-center"><strong>{{ $redasTotal }}</strong></td>
            </tr>
        </tbody>
    </table>
    @empty
    <table class="table table-sm table-bordered table-hover">
        <thead>
            <tr>
                <th class="border-0 text-center" style="vertical-align: middle; width: 30%;" scope="col">ÁREA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TEMPORÁRIA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. TEMP</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TOTAL (REAL + TEMP)</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD TOTAL DE REDAS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">Não existem registros</td>
            </tr>
        </tbody>
    </table>
    @endforelse
    @empty
    <div class="title_nte">
        <h6>MUNICÍPIO - {{ $municipio }}</h6>
    </div>
    <table class="table table-sm table-bordered table-hover">
        <thead>
            <tr>
                <th class="border-0 text-center" style="vertical-align: middle; width: 30%;" scope="col">ÁREA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TEMPORÁRIA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. TEMP</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TOTAL (REAL + TEMP)</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD TOTAL DE REDAS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" class="text-center">MUNICÍPIO SEM CARÊNCIA</td>
            </tr>
            <tr class="total">
                <td class="text-center border-0"><strong>TOTAL</strong></td>
                <td class="text-center border-0"><strong>0 h</strong></td>
                <td class="border-0 text-center"><strong>0</strong></td>
                <td class="border-0 text-center"><strong>0h</strong></td>
                <td class="border-0 text-center"><strong>0</strong></td>
                <td class="border-0 text-center"><strong>0 h</strong></td>
                <td class="border-0 text-center"><strong>0</strong></td>
            </tr>
        </tbody>
    </table>
    @endforelse
</div>
@endif

@if ($statusForUeeView === true)
<hr>
<div class="header">
    <h5 class="text-center">GERAL UNIDADE ESCOLAR</h5>
</div>
<div class="table-responsive">
    @forelse ($dadosSeparadosNteMunicipioUnidadeEscolar as $nte => $municipios)
    @forelse ($municipios as $municipio => $unidades)
    @forelse ($unidades as $unidade => $cod_unidades)
    @forelse ($cod_unidades as $cod_unidade => $linhas)
    <div class="title_nte">
        <h6>UNIDADE ESCOLAR - {{ $unidade }} ( {{ $cod_unidade }} )</h6>
    </div>

    <div hidden>
        {{$total = 0}}
        {{$chReal = 0}}
        {{$chTemp = 0}}
        {{$redasReal = 0 }}
        {{$redasTemp = 0 }}
        {{$redasTotal= 0 }}
    </div>
    <table class="table table-sm table-bordered table-hover">
        <thead>
            <tr>
                <th class="border-0 text-center" style="vertical-align: middle; width: 30%;" scope="col">ÁREA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TEMPORÁRIA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. TEMP</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TOTAL (REAL + TEMP)</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD TOTAL DE REDAS</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($linhas as $linha)
            @php
            $linhaArray = (array) $linha;
            @endphp
            <tr>
                @if ($linhaArray['area'] === 'AREA TECNICA')
                <td>{{ $linhaArray['area'] }} - PROFISSIONALIZANTE</td>
                @else
                <td>{{ $linhaArray['area'] }}</td>
                @endif
                <td class="text-center">{{ $linhaArray['total_real'] }} h</td>
                <td class="text-center">{{ round($linhaArray['total_real'] / 16, 1, PHP_ROUND_HALF_UP) }}</td>
                <td class="text-center">{{ $linhaArray['total_temp'] }} h</td>
                <td class="text-center">{{ round($linhaArray['total_temp'] / 16, 1, PHP_ROUND_HALF_UP) }}</td>
                <td class="text-center">{{ $linhaArray['total'] }} h</td>
                <td class="text-center">{{ round($linhaArray['total_temp'] / 16, 1, PHP_ROUND_HALF_UP) + round($linhaArray['total_real'] / 16, 1, PHP_ROUND_HALF_UP) }}</td>
            </tr>
            <div hidden>
                {{$total = $total + $linhaArray['total']}}
                {{$chReal = $chReal + $linhaArray['total_real']}}
                {{$chTemp = $chTemp + $linhaArray['total_temp']}}
                {{$redasReal = $redasReal + round($linhaArray['total_real'] / 16, 1, PHP_ROUND_HALF_UP)}}
                {{$redasTemp = $redasTemp + round($linhaArray['total_temp'] / 16, 1, PHP_ROUND_HALF_UP)}}
                {{$redasTotal = $redasTotal + round($linhaArray['total'] / 16, 1, PHP_ROUND_HALF_UP)}}
            </div>
            @empty
            <tr>
                <td colspan="7" class="text-center">Não existem registros</td>
            </tr>
            @endforelse
            <tr class="total">
                <td class="text-center border-0"><strong>TOTAL</strong></td>
                <td class="text-center border-0"><strong>{{ $chReal }} h</strong></td>
                <td class="border-0 text-center"><strong>{{ $redasReal }}</strong></td>
                <td class="border-0 text-center"><strong>{{ $chTemp }} h</strong></td>
                <td class="border-0 text-center"><strong>{{ $redasTemp }}</strong></td>
                <td class="border-0 text-center"><strong>{{ $total }} h</strong></td>
                <td class="border-0 text-center"><strong>{{ $redasTotal }}</strong></td>
            </tr>
        </tbody>
    </table>
    @empty
    <table class="table table-sm table-bordered table-hover">
        <tr>
            <td class="text-center">Não existem registros</td>
        </tr>
    </table>
    @endforelse
    @empty
    <table class="table table-sm table-bordered table-hover">
        <tr>
            <td class="text-center">Não existem registros</td>
        </tr>
    </table>
    @endforelse
    @empty
    <table class="table table-sm table-bordered table-hover">
        <tr>
            <td class="text-center">Não existem registros</td>
        </tr>
    </table>
    @endforelse
    @empty
    <div class="title_nte">
        <h6>UNIDADE ESCOLAR - {{ $unidade }} ( {{$cod_ue}} )</h6>
    </div>
    <table class="table table-sm table-bordered table-hover">
        <thead>
            <tr>
                <th class="border-0 text-center" style="vertical-align: middle; width: 30%;" scope="col">ÁREA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. REAL</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TEMPORÁRIA</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD PROF. TEMP</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">CH TOTAL (REAL + TEMP)</th>
                <th class="border-0 text-center" style="vertical-align: middle; width: 10%;" scope="col">QTD TOTAL DE REDAS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" class="text-center">UNIDADE ESCOLAR SEM CARÊNCIA</td>
            </tr>
            <tr class="total">
                <td class="text-center border-0"><strong>TOTAL</strong></td>
                <td class="text-center border-0"><strong>0 h</strong></td>
                <td class="border-0 text-center"><strong>0</strong></td>
                <td class="border-0 text-center"><strong>0h</strong></td>
                <td class="border-0 text-center"><strong>0</strong></td>
                <td class="border-0 text-center"><strong>0 h</strong></td>
                <td class="border-0 text-center"><strong>0</strong></td>
            </tr>
        </tbody>
    </table>
    @endforelse
</div>
@endif

@endsection