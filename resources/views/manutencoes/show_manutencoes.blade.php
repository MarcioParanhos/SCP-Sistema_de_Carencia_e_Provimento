@extends('layout.main')

@section('title', 'SCP - Manutenções')

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

<div class="mb-2 d-flex justify-content-end " style="gap: 3px;">
    <a class="mb-2 btn bg-primary text-white" data-toggle="tooltip" href="{{ route('manutencao.create') }}" data-placement="top" title="Nova Manutenção">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5l0 14" />
            <path d="M5 12l14 0" />
        </svg>
    </a>
    <a id="active_filters" class="mb-2 btn bg-primary text-white" data-toggle="tooltip" data-placement="top" title="Filtros Personalizaveis" onclick="active_filters()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
        </svg>
    </a>
    <a class="mb-2 btn bg-primary text-white" target="_blank" href="/excel/carencias" data-toggle="tooltip" data-placement="top" title="Download em Excel">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-download">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
            <path d="M12 17v-6" />
            <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
        </svg>
    </a>
    <a class="mb-2 btn bg-info text-white" data-toggle="modal" data-target="#modalInfo">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
            <path d="M12 19l0 .01" />
        </svg>
    </a>
</div>

<div class="table-responsive">
    <table id="consultarCarencias" class="table-bordered table-sm table">
        <thead class="bg-primary text-white">

            <tr class="text-center">
                <th>USUÁRIO</th>
                <th>TIPO DE MANUTENÇÃO</th>
                <th>NTE</th>
                <th>UNIDADE ESCOLAR</th>
                <th>COD. UEE</th>
                <th>DETALHES</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td class="text-center text-uppercase">MARCIO PARANHOS</td>
                <td class="text-center">AJUSTE DE HORAS EXTRAS</td>
                <td class="text-center text-uppercase">01</td>
                <td class="text-center">COL. ESTADUAL SÃO SEBASTIÃO</td>
                <td class="text-center text-uppercase">1178324</td>
                <td class="text-center text-uppercase">
                    <a data-toggle="tooltip" data-placement="top" title="Detalhes" title="Detalhar" href=""><button id="" class=" btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </button>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Excluir" title="Excluir"  class="ml-1 btn btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 7l16 0" />
                                <path d="M10 11l0 6" />
                                <path d="M14 11l0 6" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </a
                </td>
            </tr>

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
                    text: 'Não é possível excluir esse motivo porque existem carências associadas.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Usuario excluido com sucesso!',
                })
            } else if (session_message.value === "success_create") {
                Swal.fire({
                    icon: 'success',
                    text: 'Usuario adicionado com sucesso!',
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
    });
</script>