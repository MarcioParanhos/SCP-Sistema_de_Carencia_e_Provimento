@extends('layout.main')

@section('title', 'SCP - Áreas de conhecimento')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif

<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias ">ÁREAS DE CONHECIMENTO</h3>
</div>
<div class="print-btn mb-4">
    <a class="mb-2 btn bg-primary text-white" href="" data-toggle="modal" data-target="#addNewDisciplina"><i class="ti-plus"></i> ADICIONAR</a>
    <a class="mb-2 btn bg-primary text-white" href="{{ route('listas_suspensas.index') }}"><i class="fa-solid fa-arrow-left"></i> VOLTAR</a>
</div>

<div class="table-responsive">
    <table id="consultarCarencias" class=" table table-sm table-hover table-bordered">
        <caption class="mt-2">Configuração de Disciplinas</caption>
        <thead class="">
            <tr class="text-center" style="vertical-align: middle;">
                <th class="bg-primary text-white" scope="col">ID</th>
                <th class="bg-primary text-white" scope="col">ÁREA DE CONHECIMENTO</th>
                <th class="bg-primary text-white" scope="col">AÇÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($areas as $area)
            <tr>
                <td class="text-center">{{ $area->id }}</td>
                <td class="text-center">{{ $area->nome }}</td>
                <td class="text-center">
                    <!-- <a title="Editar" id="" class="ml-1 btn-show-carência btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></a> -->
                    <a title="Excluir" id="" onclick="destroyArea('{{ $area -> id }}')" class="ml-1 btn-show-carência btn btn-sm btn-danger"><i class="ti-trash"></i></a>
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
                <h5 class="modal-title" id="addNewDisciplina">ADICIONAR NOVA ÁREA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <form class="forms-sample" id="InsertArea" action="{{ route('areas.create') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="area">ÁREA</label>
                                    <input value="" name="area" id="area" type="text" class="form-control form-control-sm">
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
                    text: 'A exclusão área de conhecimento não é viável devido à existência de carências e provimentos a ela.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Área de conhecimento adicionada com sucesso!',
                })
            } else if (session_message.value === "delete_success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Área de conhecimento excluida com sucesso!',
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