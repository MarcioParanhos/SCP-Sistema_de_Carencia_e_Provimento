@extends('layout.main')

@section('title', 'SCP - Logs')

@section('content')

    <style>
        .icon-tabler-search,
        .icon-tabler-trash,
        .icon-tabler-replace {
            width: 16px;
            height: 16px;
        }

        .btn {
            padding: 6px !important;
        }
    </style>

    @if (session('msg'))
        <input id="session_message" value="{{ session('msg') }}" type="text" hidden>
    @endif

    <!-- <div class="mb-2 print-btn">
                        <a class="mb-2 btn bg-primary text-white" href="" data-toggle="modal" data-target="#addNewUser"><i class="ti-plus"></i> ADICIONAR</a>
                        <a id="active_filters" class="mb-2 btn bg-primary text-white" onclick="active_filters_provimento()">FILTROS <i class='far fa-eye'></i></a>
                    </div> -->
    <div class="bg-primary card text-white card_title">
        <h4 class=" title_show_carencias">LOG DE ATIVIDADES</h4>
    </div>
    <div class="table-responsive">
        <table id="consultarCarencias" class="table-bordered table-sm table">
            <thead class="bg-primary text-white">
                <tr class="text-center subheader">
                    <th>FONTE</th>
                    <th>ID</th>
                    <th>SERVIÇO</th>
                    <th>DATA</th>
                    <th>USUÁRIO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td class="text-center text-uppercase">{{ $log->module }}</td>
                        @if ($log->module === 'Carência')
                            <td class="text-center">
                                <a href="/detalhar_carencia/{{ $log->carencia_id }}">
                                    {{ $log->carencia_id }}
                                </a>
                            </td>
                        @else
                            <td class="text-center">
                                <a href="/provimento/detalhes_provimento/{{ $log->provimento_id }}">
                                    {{ $log->provimento_id }}
                                </a>
                            </td>
                        @endif
                        @if ($log->action === 'Inclusion')
                            <td class="text-center subheader text-uppercase">INCLUSÃO</td>
                        @else
                            <td class="text-center subheader text-uppercase">ATUALIZAÇÃO</td>
                        @endif
                        <td class="text-center">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="text-center text-uppercase">
                            {{ $log->user ? $log->user->name : 'Usuário não encontrado' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <h4 class="mt-5 mb-3 text-uppercase fs-5">Provimento: Horas/Aula Incluídas por Usuário a partir de 27/05/2025.</h4>
        <div class="table-responsive">
            <table id="consultarCarencias" class="table table-bordered table-sm">
                <thead class="bg-primary text-white">
                    <tr class="text-center">
                        <th>USUÁRIO</th>
                        <th>TOTAL MATUTINO</th>
                        <th>TOTAL VESPERTINO</th>
                        <th>TOTAL NOTURNO</th>
                        <th>TOTAL GERAL</th>
                    </tr>
                </thead>
                @php
                    // Inicializa a variável para armazenar o total geral para provimento
                    $totalGeralProvimento = 0;
                @endphp

                <tbody>
                    @foreach ($usuariosProvimento as $user)
                        @php
                            // Calcula o total para o usuário atual
                            $totalUsuarioProvimento =
                                $user['total_matutino'] + $user['total_vespertino'] + $user['total_noturno'];
                            // Adiciona o total do usuário atual ao total geral de provimento
                            $totalGeralProvimento += $totalUsuarioProvimento;
                        @endphp
                        <tr class="text-center">
                            <td class="text-uppercase">{{ $user['nome'] }}</td>
                            <td>{{ $user['total_matutino'] }}</td>
                            <td>{{ $user['total_vespertino'] }}</td>
                            <td>{{ $user['total_noturno'] }}</td>
                            <td>
                                {{ $totalUsuarioProvimento }} {{-- Exibe o total do usuário atual --}}
                            </td>
                        </tr>
                    @endforeach
                    {{-- Linha para exibir o total geral de provimento --}}
                    <tr>
                        <td class="bg-primary text-center border-0"></td> {{-- Alterei a cor para diferenciar, ajuste conforme necessário --}}
                        <td class="bg-primary text-center border-0"></td>
                        <td class="bg-primary text-center border-0"></td>
                        <td class="bg-primary text-center border-0 font-weight-bold text-white">TOTAL GERAL PROVIMENTO:</td>
                        <td class="bg-primary text-center text-white border-0">
                            <strong>{{ $totalGeralProvimento }}</strong> {{-- Exibe o total geral acumulado para provimento --}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <h4 class="mt-5 mb-3 text-uppercase fs-5">Carência: Horas/Aula Incluídas por Usuário a partir de 27/05/2025.</h4>
            <div class="table-responsive">
                <table id="consultarCarencias" class="table table-bordered table-sm">
                    <thead class="bg-primary text-white text-center">
                        <tr>
                            <th>USUÁRIO</th>
                            <th>TOTAL MATUTINO</th>
                            <th>TOTAL VESPERTINO</th>
                            <th>TOTAL NOTURNO</th>
                            <th>TOTAL GERAL</th>
                        </tr>
                    </thead>
                    @php
                        // Inicializa a variável para armazenar o total geral
                        $totalGeral = 0;
                    @endphp

                    <tbody>
                        @foreach ($usuariosCarencia as $user)
                            @php
                                // Calcula o total para o usuário atual
                                $totalUsuario =
                                    $user['total_matutino'] + $user['total_vespertino'] + $user['total_noturno'];
                                // Adiciona o total do usuário atual ao total geral
                                $totalGeral += $totalUsuario;
                            @endphp
                            <tr class="text-center">
                                <td class="text-uppercase">{{ $user['nome'] }}</td>
                                <td>{{ $user['total_matutino'] }}</td>
                                <td>{{ $user['total_vespertino'] }}</td>
                                <td>{{ $user['total_noturno'] }}</td>
                                <td>
                                    {{ $totalUsuario }} {{-- Exibe o total do usuário atual --}}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="bg-primary text-center border-0"></td>
                            <td class="bg-primary text-center border-0"></td>
                            <td class="bg-primary text-center border-0"></td>
                            <td class="bg-primary text-center border-0 font-weight-bold text-white">TOTAL GERAL CARÊNCIA:</td>
                            {{-- Adicionando um label para clareza --}}
                            <td class="bg-primary text-center text-white border-0">
                                <strong>{{ $totalGeral }}</strong> {{-- Exibe o total geral acumulado --}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
