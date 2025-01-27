@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')


<div class="card p-2 shadow">
    <div class="bg-primary d-flex flex-row card text-white card_title">
        <h3 class=" title_show_carencias">DETALHES DO SUPRIMENTO DO SERVIDOR</h3>
        <a class="mr-2" title="Voltar" href="/provimentos/servidoresAnuencia">
        <button>
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                    <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
                </svg>
                <span>VOLTAR</span>
            </button>
        </a>
    </div>
    <div class="card mt-1">
        <div class="card-body">
            <div>
                <h5 class="card-title">{{ $servidor->nome }}</h5>
                <h5 class="card-text">MATRÍCULA / CPF: {{ $servidor->cadastro }}</h5>
                <h5 class="card-text">VINCULO: {{ $servidor->vinculo }}</h5>
                <h5 class="card-text">REGIME: {{ $servidor->regime }}h</h5>
            </div>
            <div class="print-btn">
            <!-- <a class="mb-2 btn bg-primary text-white"  href="#"><i class="ti-download"></i> ANUÊNCIA</a> -->
                <a class="mb-2 btn bg-primary text-white" target="_blank" href="/provimentos/anuencia/{{ $servidor->cadastro }}"><i class="ti-download"></i> ENCAMINHAMENTO</a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered">
            <caption class="mt-2">Provimentos para este servidor.</caption>
            <thead class="bg-primary text-white">
                <tr>
                    <th class="text-center" scope="col">NTE</th>
                    <th scope="col">UNIDADE ESCOLAR</th>
                    <th class="text-center" scope="col">COD.UE</th>
                    <th scope="col">DISCIPLINA</th>
                    <th class="text-center" scope="col">MAT</th>
                    <th class="text-center" scope="col">VESP</th>
                    <th class="text-center" scope="col">NOT</th>
                    <th class="text-center" scope="col">TOTAL</th>
                    <th class="text-center" scope="col">FORMA DE PROVIMENTO</th>
                    <th class="text-center" scope="col">TIPO DE MOVIMENTO</th>
                    <th class="text-center" scope="col">SITUAÇÃO</th>
                    <th class="text-center" scope="col">DATA DE ENCAMINHAMENTO</th>
                    <th class="text-center" scope="col">ASSUNÇÃO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($provimentosByServidor as $servidor)
                <tr>
                    @if($servidor->nte >= 10)
                        <td class="text-center">{{ $servidor->nte }}</td>
                    @endif
                    @if($servidor->nte < 10)
                        <td class="text-center">0{{ $servidor->nte }}</td>
                    @endif
                    <td>{{ $servidor->unidade_escolar }}</td>
                    <td class="text-center">{{ $servidor->cod_unidade }}</td>
                    <td>{{ $servidor->disciplina }}</td>
                    <td class="text-center">{{ $servidor->provimento_matutino }}</td>
                    <td class="text-center">{{ $servidor->provimento_vespertino }}</td>
                    <td class="text-center">{{ $servidor->provimento_noturno }}</td>
                    <td class="text-center">{{ $servidor->total }}</td>
                    <td class="text-center">{{ $servidor->forma_suprimento}}</td>
                    <td class="text-center">{{ $servidor->tipo_movimentacao}}</td>
                    @if ($servidor->situacao_provimento === "provida")
                    <td class="text-center">PROVIDO</td>
                    @endif
                    @if ($servidor->situacao_provimento === "tramite")
                    <td class="text-center">TRÂMITE</td>
                    @endif
                    <td class="text-center">{{ \Carbon\Carbon::parse($servidor->data_encaminhamento)->format('d/m/Y') }}</td>
                    @if ($servidor->situacao_provimento === "tramite")
                    <td class="text-center">PENDENTE</td>
                    @endif
                    @if ($servidor->situacao_provimento === "provida")
                    <td class="text-center">{{ \Carbon\Carbon::parse($servidor->data_assuncao)->format('d/m/Y') }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection