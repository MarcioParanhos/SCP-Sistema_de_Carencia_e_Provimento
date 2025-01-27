@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')

@if(session('msg'))
<div class="col-12">
    <div class="alert text-center text-white bg-success container alert-success alert-dismissible fade show" role="alert" style="min-width: 100%">
        <strong>{{ session('msg')}}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif
<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias">servidores com provimentos para encaminhamento de termos</h3>
</div>
<div class="form_content mb-0">

</div>
<!-- <div class="print-btn">
    <a class="mb-2 btn bg-primary text-white" target="_blank" href="{{ route('provimentos.relatorio') }}"><i class="ti-printer"></i> IMPRIMIR</a>
    <a class="mb-2 btn bg-primary text-white" target="_blank" href="{{ route('provimentos.excel') }}"><i class="ti-export"></i> EXCEL</a>
    <a class="mb-2 btn bg-primary text-white" target="_blank" href="/provimentos/anuencia"><i class="ti-export"></i> ANUENCIA</a>
</div> -->
<div class="table-responsive">
    <table id="consultarCarencias" class="table table-sm table-hover table-bordered">
        <caption class="mt-2">SERVIDORES COM PROVIMENTO</caption>
        <thead class="bg-primary text-white">
            <tr>
                <th scope="col">SERVIDOR</th>
                <th class="text-center" scope="col">MATRICULA / CPF</th>
                <th class="text-center" scope="col">VINCULO</th>
                <th class="text-center" scope="col">REGIME</th>

                <th class="text-center" scope="col">AÇÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($servidores as $servidor)
            <tr>
                <td>{{ $servidor->servidor }}</td>
                <td class="text-center">{{ $servidor->cadastro }}</td>
                <td class="text-center">{{ $servidor->vinculo }}</td>
                <td class="text-center">{{ $servidor->regime }}h</td>
                <td class="text-center">
                    <a title="Detalhar" href="/detalhes_servidor/{{ $servidor->cadastro }}" button id="" class="btn-show-carência btn btn-sm btn-primary"><i class="ti-search"></i></button></a>
                </td>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection