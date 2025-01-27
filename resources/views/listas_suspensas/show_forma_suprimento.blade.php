@extends('layout.main')

@section('title', 'SCP - Forma de Suprimento')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif

<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias ">FORMA DE SUPRIMENTO</h3>
</div>
<div class="print-btn mb-4">
    <a class="mb-2 btn bg-primary text-white" href="" data-toggle="modal" data-target="#addNewDisciplina"><i class="ti-plus"></i> ADICIONAR</a>
    <a class="mb-2 btn bg-primary text-white" href="/listas_suspensas"><i class="fa-solid fa-arrow-left"></i> VOLTAR</a>
</div>

<div class="table-responsive">
    <table id="consultarCarencias" class=" table table-sm table-hover table-bordered">
        <caption class="mt-2">Configuração de Formas de Suprimentos</caption>
        <thead class="">
            <tr class="text-center" style="vertical-align: middle;">
                <th class="bg-primary text-white" scope="col">ID</th>
                <th class="bg-primary text-white" scope="col">FORMA DE SUPRIMENTO</th>
                <th class="bg-primary text-white" scope="col">AÇÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($formas_suprimentos as $forma_suprimento)
            <tr>
                <td class="text-center">{{ $forma_suprimento->id }}</td>
                <td class="text-center">{{ $forma_suprimento->forma }}</td>
                <td class="text-center">
                    <!-- <a title="Editar" id="" class="ml-1 btn-show-carência btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></a> -->
                    <a title="Excluir" id="" onclick="destroyComponenteEspecial('{{ $forma_suprimento -> id }}')" class="ml-1 btn-show-carência btn btn-sm btn-danger"><i class="ti-trash"></i></a>
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
                <h5 class="modal-title" id="addNewDisciplina">ADICIONAR NOVO COMPONENTE ESPECIAL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <form class="forms-sample" id="InsertArea" action="{{ route('componente_especial.create') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="componente_especial">NOME</label>
                                    <input value="" name="componente_especial" id="componente_especial" type="text" class="form-control form-control-sm">
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
                    text: 'A exclusão do componente especial não é viável devido à existência de carências e provimentos a ela.',
                })
            } else if (session_message.value === "success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Componente Especial adicionado com sucesso!',
                })
            } else if (session_message.value === "delete_success") {
                Swal.fire({
                    icon: 'success',
                    text: 'Componente especial excluido com sucesso!',
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