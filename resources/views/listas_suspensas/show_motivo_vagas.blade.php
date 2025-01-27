@extends('layout.main')

@section('title', 'SCP - Usuarios')

@section('content')

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif

<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias">MOTIVO DE VAGAS</h3>
</div>
<div class="print-btn mb-4">
    <a class="mb-2 btn bg-primary text-white" href="" data-toggle="modal" data-target="#ReasonVacanciesModal"><i class="ti-plus"></i> ADICIONAR</a>
    <a class="mb-2 btn bg-primary text-white" href="{{ route('listas_suspensas.index') }}"><i class="fa-solid fa-arrow-left"></i> VOLTAR</a>
</div>
<div class="table-responsive">
    <table id="consultarCarencias" class=" table table-sm table-hover table-bordered">
        <thead class="bg-primary text-white">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">MOTIVO DA VAGA</th>
                <th scope="col">TIPO</th>
                <th scope="col">AÇÃO</th>
        </thead>
        <tbody>
            @foreach ($motivos as $motivo)
            <tr>
                <td class="text-center" scope="row">{{ $motivo->id }}</td>
                <td class="text-center" scope="row">{{ $motivo->motivo }}</td>
                @if ($motivo->tipo === 'Real')
                <td class="text-center">REAL</td>
                @else
                <td class="text-center">TEMPORÁRIA</td>
                @endif
                <td class="d-flex justify-content-center text-center" style="gap: 10px;">
                    <!-- <a title="Excluir" href="/motivo_de_vagas/destroy/{{ $motivo->id }}" class="ml-1 btn-show-carência btn btn-danger"><i class="ti-trash"></i></a> -->
                    <form class="" action='/motivo_de_vagas/destroy/{{ $motivo->id }}' method='post'>
                        @csrf
                        @method('DELETE')
                        <a title="Excluir"><button id="" type="submit" class="btn-show-carência btn btn-sm btn-danger"><i class="ti-trash"></i></button></a>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal para adcicionar novo motivo de uma vaga -->
<div class="modal fade" id="ReasonVacanciesModal" tabindex="-1" role="dialog" aria-labelledby="ReasonVacanciesModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ReasonVacanciesModal">NOVO MOTIVO DE VAGA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <form class="forms-sample" id="InsertReasonVacancies" action="{{ route('motivo_vagas.create') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="motivo">MOTIVO DA VAGA</label>
                                    <input value="" name="motivo" id="motivo" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="type">TIPO</label>
                                    <select name="tipo" id="tipo" class="form-control form-control-sm">
                                        <option>Selecione....</option>
                                        <option value="Real">REAL</option>
                                        <option value="Temp">TEMPORÁRIA</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center text-center flex-row">
                            <button style="width: 30%;" type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                            <button style="width: 30%;" type="submit" class="btn btn-primary">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const administracao = document.getElementById("administracao")
    const administracao_collapse = document.getElementById("administracao_collapse")
    administracao.classList.add('show')
    administracao_collapse.classList.add('active')
</script>
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
                    text: 'Motivo de vaga excluido com sucesso!',
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