@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')
<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias">STATUS EM DIAS de PROVIMENTOS EM TRÂMITE</h3>
</div>
<h4>Foram encontrados um total de {{ $totalRegistros }} registros.</h4>
<div class="mb-2 print-btn">
        <a class="mb-2 btn bg-primary text-white" target="_blank" href="{{ route('provimento.statusInTramite') }}"><i class="ti-download"></i> EXCEL</a>
    </div>
<div class="table-responsive">
    <table id="consultarCarencias" class="table table-sm table-hover table-bordered">
        <thead>
            <tr class="bg-primary text-white">
                <th class="text-center" scope="col">NTE</th>
                <th class="text-center" scope="col">MUNICIPIO</th>
                <th class="text-center" scope="col">UNIDADE ESCOLAR</th>
                <th class="text-center" scope="col">COD.</th>
                <th class="text-center" scope="col">SERVIDOR</th>
                <th class="text-center" scope="col">MATRICULA/CPF</th>
                <th class="text-center" scope="col">SITUACAO</th>
                <th class="text-center" scope="col">ENCAMINHAMENTO</th>
                <th class="text-center" scope="col">DIAS SEM ASSUNÇÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($provimentos as $provimento)
            <tr>
                @if ( $provimento -> nte < 10) <td class="text-center" scope="row">0{{ $provimento -> nte }}</td>
                    @endif
                    @if ( $provimento -> nte >= 10)
                    <td class="text-center" scope="row">{{ $provimento -> nte }}</td>
                    @endif
                    <td>{{ $provimento->municipio }}</td>
                    <td>{{ $provimento->unidade_escolar }}</td>
                    <td>{{ $provimento->cod_unidade }}</td>
                    <td>{{ $provimento->servidor }}</td>
                    <td>{{ $provimento->cadastro }}</td>
                    @if ($provimento->situacao_provimento === "tramite")
                    <td class="text-center" >TRÂMITE</td>
                    @endif
                    <td class="text-center">{{ \Carbon\Carbon::parse($provimento->data_encaminhamento)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($provimento->data_encaminhamento)->diffInDays(\Carbon\Carbon::now()) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    const active_relatorios = document.getElementById("active_relatorios")
    active_relatorios.classList.add('active')
</script>
@endsection