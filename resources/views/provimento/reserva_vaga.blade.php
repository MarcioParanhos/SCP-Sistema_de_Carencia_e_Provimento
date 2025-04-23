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

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif

<div class="bg-primary card text-white card_title">
    <h4 class=" title_show_carencias">LISTA DE VAGAS RESERVADAS</h4>
</div>
<!-- <div class="mb-2 print-btn">
    <a class="mb-2 btn bg-primary text-white" href="" data-toggle="modal" data-target="#addNewUser"><i class="ti-plus"></i> ADICIONAR</a>
    <a id="active_filters" class="mb-2 btn bg-primary text-white" onclick="active_filters_provimento()">FILTROS <i class='far fa-eye'></i></a>
</div> -->

<div class="mb-2 d-flex justify-content-end " style="gap: 3px;">
    <a id="active_filters" class="mb-2 btn bg-primary text-white" data-toggle="tooltip" data-placement="top" title="Filtros Personalizaveis" onclick="active_filters()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
        </svg>
    </a>
    <a class="mb-2 btn bg-primary text-white" href="#" data-toggle="tooltip" data-placement="top" title="Download em Excel">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-download">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
            <path d="M12 17v-6" />
            <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
        </svg>
    </a>
</div>
<hr>
<div class="table-responsive">
    <table id="consultarCarencias" class="table-bordered table-sm table">
        <thead class="bg-primary text-white">
            <tr class="text-center">
                <th>NTE</th>
                <th>MUNICIPIO</th>
                <th>UNIDADE ESCOLAR</th>
                <th>SERVIDOR</th>
                <th>MATRICULA</th>
                <th>VINCULO</th>
                <th>MAT</th>
                <th>VESP</th>
                <th>NOT</th>
                <th>TOTAL</th>
                <th>DISCIPLINAS</th>
                <th>CARÊNCIA ID(s)</th> <!-- Nova coluna para o ID da carência -->
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resumo_reservas as $reserva)
            <tr>
                <td class="text-center">{{ str_pad($reserva['carencia']->nte, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="text-center">{{ $reserva['carencia']->municipio }}</td>
                <td class="text-center">{{ $reserva['carencia']->unidade_escolar }}</td>
                <td class="text-center">{{ $reserva['servidor']->nome }}</td>
                <td class="text-center">{{ $reserva['servidor']->cadastro }}</td>
                <td class="text-center">{{ $reserva['servidor']->vinculo }}</td>
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
                <td class="text-center">
                    <button type="button" data-toggle="modal" data-target="#modalProver" data-carencias="{{ implode(',', $reserva['carencia_ids']) }}" class="ml-1 btn btn-info btnAbrirModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-replace">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 3m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                            <path d="M15 15m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                            <path d="M21 11v-3a2 2 0 0 0 -2 -2h-6l3 3m0 -6l-3 3" />
                            <path d="M3 13v3a2 2 0 0 0 2 2h6l-3 -3m0 6l3 -3" />
                        </svg>
                    </button>

                    <a data-toggle="tooltip" data-placement="top" title="Excluir" class="ml-1 btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 7l16 0" />
                            <path d="M10 11l0 6" />
                            <path d="M14 11l0 6" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal para data de assunção -->
<div class="modal fade" id="modalProver" tabindex="-1" aria-labelledby="modalProverLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formProverModal" method="POST" action="{{ route('reserva.createProvimento') }}" class="modal-content shadow-lg rounded">
            @csrf
            <input type="hidden" name="carencia_ids" id="modalCarenciaIds">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProverLabel">
                    <i class="fas fa-calendar-check mr-2"></i> Confirmar Assunção
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group col-10">
                    <label for="data_assuncao" class="font-weight-bold">Data de Assunção</label>
                    <input type="date" class="form-control" name="data_assuncao" id="data_assuncao" required>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger subheader" data-dismiss="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18 6l-12 12" />
                        <path d="M6 6l12 12" />
                    </svg>
                    Cancelar
                </button>
                <button type="submit" class="btn btn-success subheader">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4l0 4l-6 0l0 -4" />
                    </svg>
                    Confirmar Provimento
                </button>
            </div>
        </form>
    </div>
</div>


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

@endsection