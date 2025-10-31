@extends('layout.main')

@section('title', 'Detalhes do Bloco de Reserva')

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
            border-radius: 5px !important;
        }
    </style>

    @if (session('msg'))
        <input id="session_message" value="{{ session('msg') }}" type="text" hidden>
    @endif
    <div class="container-fluid">
        <!-- Cabeçalho da Página -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <div>
                <h3 class="fw-bold text-primary">Detalhes do Bloco de Reserva</h3>
                <p class="text-muted mb-0">ID do Bloco: <span class="fw-semibold">{{ $blocoId }}</span></p>
            </div>

            <div class="d-print-none">
                <a id="btn_back_to_list" title="Voltar" data-toggle="tooltip" data-placement="top"
                    class="btn btn-sm btn-primary me-2" href="{{ route('reserva.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back-up">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                </a>
                <a title="Imprimir/Salvar em PDF" data-toggle="tooltip" data-placement="top" class="btn btn-sm btn-primary"
                    href="/reservas/bloco/{{ $blocoId }}/imprimir-termo" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                        <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="d-flex justify-content-end align-items-center mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-plus">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                <path d="M16 3v4" />
                <path d="M8 3v4" />
                <path d="M4 11h16" />
                <path d="M16 19h6" />
                <path d="M19 16v6" />
            </svg>
            <span class="ml-1 small">{{ $bloco_created_at }}</span>
        </div>

        <!-- Tabela com as Carências do Bloco -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-body">
                <h5 class="card-title text-primary mb-3">Carências Incluídas no Bloco ({{ count($reservas) }} vagas)</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>ID Carência</th>
                                <th>NTE</th>
                                <th>Município</th>
                                <th>Unidade Escolar</th>
                                <th>Disciplina</th>
                                <th>Servidor</th>
                                <th>MAT</th>
                                <th>VESP</th>
                                <th>NOT</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservas as $reserva)
                                <tr class="text-center" data-carencia-id="{{ optional($reserva->carencia)->id }}">
                                    <td>{{ optional($reserva->carencia)->id }}</td>
                                    <td>{{ str_pad(optional($reserva->carencia)->nte, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td class="text-start">{{ optional($reserva->carencia)->municipio }}</td>
                                    <td class="text-start">{{ optional($reserva->carencia)->unidade_escolar }}</td>
                                    <td class="text-start">{{ optional($reserva->carencia)->disciplina }}</td>
                                    <td class="text-start">{{ optional($reserva->carencia)->servidor ?: 'N/A' }}</td>
                                    <td class="text-start">{{ optional($reserva->carencia)->cadastro ?: 'N/A' }}</td>
                                    <td>{{ optional($reserva->carencia)->matutino }}</td>
                                    <td>{{ optional($reserva->carencia)->vespertino }}</td>
                                    <td>{{ optional($reserva->carencia)->noturno }}</td>
                                    <td class="fw-bold">{{ optional($reserva->carencia)->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Formulário para Futuro Provimento -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-3">Dados para Provimento do Bloco</h5>
                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button"
                        aria-expanded="false" aria-controls="collapseExample">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                        </svg>
                        Associar um Servidor
                    </a>
                </div>

                <p class="card-subtitle mb-3">Preencha as informações abaixo para vincular a todas as carências deste bloco.
                </p>

                <form action="{{ route('reserva.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="bloco_id" value="{{ $blocoId }}">
                    <div class="form-row">
                        <div class="col-md-2">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="num_cop">Nº DO COP</label>
                                <select name="num_cop" id="num_cop" class="form-control form-control-sm select2">
                                    <option value="">Selecione um COP</option>

                                    {{-- Itera sobre a lista completa de COPs enviada pelo controller --}}
                                    @foreach ($lista_num_cop as $cop)
                                        <option value="{{ $cop->num }}" {{-- Compara o valor da opção atual com o valor salvo --}}
                                            @if ($cop->num == $num_cop_atual) selected @endif>
                                            {{ $cop->num }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group_disciplina">
                                <label for="num_sei" class="">Nº PROCESSO SEI</label>
                                <input name="num_sei" id="num_sei" type="number" class="form-control form-control-sm"
                                    value="{{ $num_sei_atual ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="mt-4 col-12 text-end">
                            {{-- <button type="submit" class="btn btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                </svg>
                                Atualizar
                            </button> --}}
                            <div id="buttons" class="buttons">
                                <button id="" class="button" type="submit">
                                    <span class="button__text">Atualizar</span>
                                    <span class="button__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="collapse @if ($cpf_servidor) show @endif" id="collapseExample">
                    <hr>
                    <div class="">
                        <!-- Seção de Reserva de Vaga -->
                        <div class=" mt-4">
                            <h5 class="card-title text-primary mb-3">Associar um servidor</h5>
                            <div class="card-body">
                                @if (Auth::user()->profile === 'cpm_tecnico' || Auth::user()->profile === 'administrador')
                                    <form action="{{ route('reserva.update_servidor') }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="bloco_id" value="{{ $blocoId }}">
                                        <input value="{{ Auth::user()->id }}" id="user_id" name="user_id"
                                            type="text" class="form-control form-control-sm" hidden>

                                        <div class="form-row">
                                            <div class="col-md-2">
                                                <div class="form-group_disciplina">
                                                    <label for="matricula_cpf" class="">MATRÍCULA/CPF</label>
                                                    <input value="{{ $cpf_servidor }}" name="matricula_cpf"
                                                        id="matricula_cpf" type="text"
                                                        class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group_disciplina">
                                                    <label for="nome_servidor" class="">nome do servidor</label>
                                                    <input value="{{ $nome_servidor }}" name="nome_servidor"
                                                        id="nome_servidor" type="text"
                                                        class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group_disciplina">
                                                    <label for="data_indicacao">Data da Indicação</label>
                                                    <input type="date" value="{{ $data_indicacao }}"
                                                        class="form-control form-control-sm" id="data_indicacao"
                                                        name="data_indicacao">
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="motivo_vaga_row">
                                                <div class="form-group_disciplina">
                                                    <label class="control-label" for="checked_diploma">Diploma válido?
                                                    </label>
                                                    <select name="checked_diploma" id="checked_diploma"
                                                        class="form-control select2">
                                                        <option value="{{ $checked_diploma }}">{{ $checked_diploma }}
                                                        </option>
                                                        <option value="SIM">SIM</option>
                                                        <option value="NÃO">NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-3" id="motivo_vaga_row">
                                                <div class="form-group_disciplina">
                                                    <label class="control-label" for="forma_suprimento">FORMA DE
                                                        SUPRIMENTO</label>
                                                    <select name="forma_suprimento" id="forma_suprimento"
                                                        class="form-control select2">
                                                        <option value="{{ $forma_suprimento }}" selected>
                                                            {{ $forma_suprimento }}</option>
                                                        <option value="REDA EMERGENCIAL">REDA EMERGENCIAL</option>
                                                        <option value="REDA SELECAO INDIGENA">REDA SEL. INDIGENA</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="motivo_vaga_row">
                                                <div class="form-group_disciplina">
                                                    <label class="control-label" for="tipo_movimentacao">TIPO de
                                                        movimentação</label>
                                                    <select name="tipo_movimentacao" id="tipo_movimentacao"
                                                        class="form-control select2">
                                                        <option value="{{ $tipo_movimentacao }}" selected>
                                                            {{ $tipo_movimentacao }}</option>
                                                        <option value="INGRESSO">INGRESSO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="motivo_vaga_row">
                                                <div class="form-group_disciplina">
                                                    <label class="control-label" for="tipo_aula">Tipo de Aula </label>
                                                    <select name="tipo_aula" id="tipo_aula" class="form-control select2">
                                                        <option value="{{ $tipo_aula }}" selected>
                                                            {{ $tipo_aula }}</option>
                                                        <option value="NORMAL">NORMAL</option>
                                                        <option value="EXTRA">EXTRA</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="data_assuncao_row" class="col-md-12">
                                                <div class="form-group_disciplina">
                                                    <label for="justificativa">Justificativa <i
                                                            class="ti-pencil"></i></label>
                                                    <textarea class="form-control" name="justificativa" id="justificativa" rows="5">{{ $justificativa }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end mt-3">
                                            <div id="buttons" class="buttons">
                                                <button id="" class="button" type="submit">
                                                    <span class="button__text">SALVAR</span>
                                                    <span class="button__icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                            <path d="M14 4l0 4l-6 0l0 -4" />
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                            @if ($checked_diploma === 'SIM' && $num_cop_atual)
                                                <div id="buttons" class="buttons">
                                                    <button id="btnAbrirModalEncaminhamento" class="ml-2 button"
                                                        type="button">
                                                        <span class="button__text">Prover</span>
                                                        <span class="button__icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-up">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                                                <path d="M19 22v-6" />
                                                                <path d="M22 19l-3 -3l-3 3" />
                                                            </svg>
                                                        </span>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para data de assunção -->
    <div class="modal fade" id="modalProver" tabindex="-1" aria-labelledby="modalProverLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formProverModal" method="POST" action="{{ route('reserva.createProvimento') }}">
                <div class="modal-content shadow-lg rounded">
                    <!-- Este input receberá os IDs coletados -->
                    <input type="hidden" name="carencia_ids" id="modalCarenciaIds">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalProverLabel">
                            <i class="fas fa-calendar-check mr-2"></i> Confirmar Encaminhamento
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-column">
                            <div class="form-group">
                                <label for="data_assuncao" class="font-weight-bold">Data de Encaminhamento</label>
                                <input type="date" class="form-control" name="data_assuncao" id="data_assuncao"
                                    required>
                            </div>
                            <p class="text-muted small">Você está encaminhando <strong id="totalSelecionado"></strong>
                                carências.</p>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"
                                style="vertical-align: middle; margin-right: 4px;">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M14 4l0 4l-6 0l0 -4" />
                            </svg>
                            Confirmar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pages/reserva/reserva.js') }}" defer></script>
    <script src="{{ asset('js/alerts.js') }}" defer></script>
@endpush
