@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')

    @if (session('msg'))
        <div class="col-12">
            <div class="alert text-center text-white bg-success container alert-success alert-dismissible fade show"
                role="alert" style="min-width: 100%">
                <strong>{{ session('msg') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
    <div class="bg-primary card text-white card_title">
        <h3 class=" title_show_carencias">Lista de Servidores Providos</h3>
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
            <thead class="bg-primary text-white">
                <tr>
                    <th class="text-center" scope="col">NTE</th>
                    <th class="text-center" scope="col">MUNICIPIO</th>
                    <th class="text-center" scope="col">UNIDADE ESCOLAR</th>
                    <th class="text-center" scope="col">SERVIDOR</th>
                    <th class="text-center" scope="col">MATRICULA / CPF</th>
                    <th class="text-center" scope="col">VINCULO</th>
                    <th class="text-center" scope="col">SITUAÇÃO</th>
                    <th class="text-center" scope="col">Nº COPE</th>
                    <th class="text-center" scope="col">AÇÃO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servidores as $servidor)
                    <tr>
                        <td class="text-center">{{ $servidor->nte }}</td>
                        <td>{{ $servidor->municipio }}</td>
                        <td>{{ $servidor->unidade_escolar }}</td>
                        <td>{{ $servidor->servidor }}</td>
                        <td class="text-center">{{ $servidor->cadastro }}</td>
                        <td class="text-center">{{ $servidor->vinculo }}</td>
                        <td class="text-center">
                            @if ($servidor->situacao_provimento === 'provida')
                                PROVIDO
                            @else
                                EM TRÂMITE
                            @endif
                        </td>
                        <td class="text-center">{{ $servidor->num_cop }}</td>
                        <td class="text-center">
                            <a title="Detalhar" href="/detalhes_servidor/{{ $servidor->cadastro }}"
                                class=" btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                            </a>
                        </td>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
