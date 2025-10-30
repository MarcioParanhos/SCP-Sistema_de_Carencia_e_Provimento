@extends('layout.main')

@section('title', 'SCP - Provimento')

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
    <style>
        .btn {
            padding: 6px !important;
        }

        .icon-tabler-search,
        .icon-tabler-trash,
        .icon-tabler-replace {
            width: 16px;
            height: 16px;
        }

        td span {
            font-size: 10px !important;
            font-weight: 900 !important;
            border-radius: 50% !important;
        }

        .col-1,
        .col-2,
        .col-3,
        .col-4,
        .col-5,
        .col-6,
        .lightGallery .image-tile,
        .col-7,
        .col-8,
        .col-9,
        .col-10,
        .col-11,
        .col-12,
        .col,
        .col-auto,
        .col-sm-1,
        .col-sm-2,
        .col-sm-3,
        .col-sm-4,
        .col-sm-5,
        .col-sm-6,
        .col-sm-7,
        .col-sm-8,
        .col-sm-9,
        .col-sm-10,
        .col-sm-11,
        .col-sm-12,
        .col-sm,
        .col-sm-auto,
        .col-md-1,
        .col-md-2,
        .col-md-3,
        .col-md-4,
        .col-md-5,
        .col-md-6,
        .col-md-7,
        .col-md-8,
        .col-md-9,
        .col-md-10,
        .col-md-11,
        .col-md-12,
        .col-md,
        .col-md-auto,
        .col-lg-1,
        .col-lg-2,
        .col-lg-3,
        .col-lg-4,
        .col-lg-5,
        .col-lg-6,
        .col-lg-7,
        .col-lg-8,
        .col-lg-9,
        .col-lg-10,
        .col-lg-11,
        .col-lg-12,
        .col-lg,
        .col-lg-auto,
        .col-xl-1,
        .col-xl-2,
        .col-xl-3,
        .col-xl-4,
        .col-xl-5,
        .col-xl-6,
        .col-xl-7,
        .col-xl-8,
        .col-xl-9,
        .col-xl-10,
        .col-xl-11,
        .col-xl-12,
        .col-xl,
        .col-xl-auto {
            padding-right: 2px !important;
            padding-left: 2px !important;
        }
    </style>

    @if (session('msg'))
        <input id="session_message" value="{{ session('msg') }}" type="text" hidden>
    @endif

    <div class="bg-primary card text-white card_title">
        <h4 class=" title_show_carencias">LISTA DE VAGAS RESERVADAS</h4>
    </div>
    <div id="regularizacao_container" class="d-flex mb-4 justify-content-between">
        <div id="count-infos" class="d-flex col-md-10">
            <div class="d-flex col-md-4">
                <div class="col-md-12">
                    <table class="table-bordered">
                        <tr>
                            <th colspan="1" class="p-1 text-center pl-2 bg-primary subheader text-white">Nº COP</th>
                            <th colspan="1" class="p-1 text-center pl-2 bg-primary subheader text-white">COTAS RESTANTES
                            </th>
                        </tr>
                        @foreach ($numero_do_cop as $cop)
                            <tr>
                                <td class="pl-2 text-center subheader"><b>{{ $cop->num }}</b></td>
                                <td style="width: 50%;" class="text-center subheader"><b>{{ $cop->quantidade }}</b></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-2 d-flex justify-content-end " style="gap: 3px;">
        <a class="mb-2 btn bg-primary text-white" title="Filtros Personalizaveis" data-toggle="collapse"
            href="#collapseExample" role="button" ria-expanded="false" aria-controls="collapseExample">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
            </svg>
        </a>
        <a class="mb-2 btn bg-primary text-white" target="_blank" href="{{ route('reservas.excel') }}" data-toggle="tooltip"
            data-placement="top" title="Download em Excel">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-file-download">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M12 17v-6" />
                <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
            </svg>
        </a>
    </div>
    <div class="collapse mb-4" id="collapseExample">
        <div class="card card-body border shadow bg-light rounded pt-3 pl-3 pr-3">
            <form id="active_form" action="{{ route('reservas.index') }}" method="post">
                @csrf
                <div class="form-row">
                    <div class="col-md-1">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="nte_seacrh">NTE</label>
                            {{-- Adicionamos a lógica para manter o valor selecionado --}}
                            <select name="nte_seacrh" id="nte_seacrh" class="form-control form-control-sm select2">
                                <option value=""></option>
                                @for ($i = 1; $i <= 27; $i++)
                                    <option value="{{ $i }}" @if (isset($input['nte_seacrh']) && $input['nte_seacrh'] == $i)  @endif>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="municipio_search">MUNICIPIO</label>
                            <select name="municipio_search" id="municipio_search"
                                class="form-control form-control-sm select2">

                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="search_uee">NOME DA UNIDADE ESCOLAR</label>
                            <select name="search_uee" id="search_uee" class="form-control form-control-sm select2">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group_disciplina">
                            <label for="search_num_cop" class="">Nº do COP</label>
                            <input name="search_num_cop" id="search_num_cop" type="text"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div id="buttons" class="mt-3 buttons d-flex align-items-center">
                    <button id="" class="button" type="submit">
                        <span class="button__text">BUSCAR</span>
                        <span class="button__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </span>
                    </button>
                    <button id="" class="ml-2 button" type="button" onclick="clearForm()">
                        <span class="button__text text-danger">LIMPAR</span>
                        <span class="button__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="red" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eraser">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3" />
                                <path d="M18 13.3l-6.3 -6.3" />
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <hr>
    <div class="table-responsive">
        <table id="consultarCarencias" class="table-bordered table-sm table">
            <thead class="bg-primary text-white">
                <tr class="text-center">
                    <th>NTE</th>
                    <th>MUNICIPIO</th>
                    <th>UNIDADE ESCOLAR</th>
                    <th>MAT</th>
                    <th>VESP</th>
                    <th>NOT</th>
                    <th>TOTAL</th>
                    <th>DISCIPLINAS</th>
                    <th>CARÊNCIA ID(s)</th>
                    <th>Nº DO COP</th>
                    <th>PROCESSO SEI</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resumo_reservas as $reserva)
                    <tr>
                        <td class="text-center">{{ str_pad($reserva['carencia']->nte, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="text-center">{{ $reserva['carencia']->municipio }}</td>
                        <td class="text-center">{{ implode(', ', $reserva['unidades_escolares']) }}</td>
                        <td class="text-center">{{ $reserva['mat'] }}</td>
                        <td class="text-center">{{ $reserva['vesp'] }}</td>
                        <td class="text-center">{{ $reserva['not'] }}</td>
                        <td class="text-center">{{ $reserva['total'] }}</td>
                        <td class="text-center">
                            {{ implode(', ', $reserva['disciplinas']) }}
                        </td>
                        <td class="text-center">
                            {{ implode(', ', $reserva['carencia_ids']) }} <!-- Exibindo os IDs das carências -->
                        </td>
                        <td class="text-center">{{ $reserva['num_cop'] ?? 'N/D' }}</td>
                        <td class="text-center">{{ $reserva['num_sei'] ?? 'N/D' }}</td>
                        <td class="d-flex justify-content-center align-items-center">

                            {{-- Botão 1: Detalhar (Adicionado btn-sm para consistência) --}}
                            <a href="{{ route('reservas.detalharBloco', ['blocoId' => $reserva['bloco_id']]) }}"
                                class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Detalhar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                            </a>

                            {{-- Botão 2: Excluir (O ml-1 cria o espaçamento e o btn-sm alinha o tamanho) --}}
                            <a data-toggle="tooltip" data-placement="top" title="Excluir"
                                class="ml-1 btn btn-danger"
                                onclick="destroyReserva('{{ $reserva['bloco_id'] }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-trash">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" />
                                    <path
                                        d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="ModalDeleteReserva" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteReserva"
        aria-hidden=" true">
        <div class="modal-dialog modal-dialog-centered" role="document" style=" margin-top: 0px; margin-bottom: 0px;">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <h4 class="modal-title text-center text-dark" id="TituloModalDeleteProvimentosEfetivos">
                        <strong>Excluir Dados</strong>
                    </h4>
                </div>
                <div class="modal-body modal-destroy">
                    <h4 class="subheader"><strong>Tem certeza?</strong></h4>
                    <h4 class="subheader"><strong>O registro sera excluido permanentemente!</strong></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                    <a title="Excluir Encaminhamento"><button id="btn_delete_provimento" type="button"
                            class="btn float-right btn-danger">Excluir</button></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pages/reserva/reserva.js') }}" defer></script>
    <script src="{{ asset('js/alerts.js') }}" defer></script>
@endpush


<script>
    document.querySelectorAll('.btnAbrirModal').forEach(button => {
        button.addEventListener('click', function() {
            const carenciaIds = this.getAttribute('data-carencias');
            document.getElementById('modalCarenciaIds').value = carenciaIds;
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const session_message = document.getElementById("session_message");

        if (session_message) {

            if (session_message.value === "error") {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Não é possível excluir esse motivo porque existem carências associadas.',
                })

            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Vaga reservada provida com sucesso!',
                })
            }
        }
    });
</script>
