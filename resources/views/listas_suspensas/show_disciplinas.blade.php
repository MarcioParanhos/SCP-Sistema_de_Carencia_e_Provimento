@extends('layout.main')

@section('title', 'SCP - Disciplinas')

@section('content')

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
</style>

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif

<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias ">Disciplinas</h3>
</div>
<div class="mb-2 d-flex justify-content-end " style="gap: 3px;">
    <a  class="mb-2 btn bg-primary text-white" href="" data-toggle="modal" data-target="#addNewDisciplina">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5l0 14" />
            <path d="M5 12l14 0" />
        </svg>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Voltar" class="mb-2 btn bg-primary text-white" href="{{ route('listas_suspensas.index') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back-up">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M9 14l-4 -4l4 -4" />
            <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
        </svg>
    </a>
</div>

<div class="table-responsive">
    <table id="consultarCarencias" class=" table table-sm table-hover table-bordered">
        <caption class="mt-2">Configuração de Disciplinas</caption>
        <thead class="">
            <tr class="text-center" style="vertical-align: middle;">
                <th class="bg-primary text-white" scope="col" style="width: 5%;">ID</th>
                <th class="bg-primary text-white" scope="col">DISCIPLINA</th>
                <th class="bg-primary text-white" scope="col" style="width: 5%;">AÇÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($disciplinas as $disciplina)
            <tr>
                <td class="text-center">{{ $disciplina -> id }}</td>
                <td class="text-center">{{ $disciplina -> nome }}</td>
                <td class="text-center">
                    <!-- <a title="Editar" id="" class="ml-1 btn-show-carência btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></a> -->
                    <!-- <a title="Excluir" id="" onclick="destroyDisciplina('{{ $disciplina -> id }}')" class="ml-1 btn-show-carência btn btn-sm btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 7l16 0" />
                            <path d="M10 11l0 6" />
                            <path d="M14 11l0 6" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                    </a> -->
                    <a data-toggle="tooltip" data-placement="top" title="Excluir" title="Excluir" id="" onclick="destroyDisciplina('{{ $disciplina -> id }}')" class="ml-1 btn btn-danger">
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


<script>
    const administracao = document.getElementById("administracao")
    const administracao_collapse = document.getElementById("administracao_collapse")
    administracao.classList.add('show')
    administracao_collapse.classList.add('active')
</script>
@endsection

<!-- Modal para adcicionar nova disciplina -->
<div class="modal fade" id="addNewDisciplina" tabindex="-1" role="dialog" aria-labelledby="addNewDisciplina" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewDisciplina">ADICIONAR NOVA DISCIPLINA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <form class="forms-sample" id="InsertDisciplina" action="{{ route('discipline.create') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="disciplina">DISCIPLINA</label>
                                    <input value="" name="disciplina" id="disciplina" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const session_message = document.getElementById("session_message");

        if (session_message) {
            if (session_message.value === "error") {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'A exclusão desta disciplina não é viável devido à existência de carências e provimentos a ela.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Disciplina adicionada com sucesso!',
                })
            } else if (session_message.value === "delete_success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Disciplina excluida com sucesso!',
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