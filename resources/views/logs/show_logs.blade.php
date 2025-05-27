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

@if(session('msg'))
<input id="session_message" value="{{ session('msg')}}" type="text" hidden>
@endif

<!-- <div class="mb-2 print-btn">
    <a class="mb-2 btn bg-primary text-white" href="" data-toggle="modal" data-target="#addNewUser"><i class="ti-plus"></i> ADICIONAR</a>
    <a id="active_filters" class="mb-2 btn bg-primary text-white" onclick="active_filters_provimento()">FILTROS <i class='far fa-eye'></i></a>
</div> -->

<div class="table-responsive">
    <table id="consultarCarencias" class="table-bordered table-sm table">
        <thead class="bg-primary text-white">

            <tr class="text-center">
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
                @if($log->module === "Carência")
                <td class="text-center">
                    <a href="/detalhar_carencia/{{ $log->carencia_id }}">
                    {{ $log->carencia_id }}
                    </a>
                </td>
                @else
                <td class="text-center">
                    <a href="/provimento/detalhes_provimento/{{ $log->provimento_id  }}">
                    {{ $log->provimento_id }}
                    </a>
                </td>
                @endif
                @if ($log->action === "Inclusion")
                <td class="text-center text-uppercase">INCLUSÃO</td>
                @else
                <td class="text-center text-uppercase">ATUALIZAÇÃO</td>
                @endif
                <td class="text-center">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                <td class="text-center text-uppercase">{{ $log->user ? $log->user->name : 'Usuário não encontrado' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="modal fade" id="addNewUser" tabindex="-1" role="dialog" aria-labelledby="addNewUser" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewUser">NOVO USUARIO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <form class="forms-sample" id="newUser" action="{{ route('users.create') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="user_name">NOME</label>
                                    <input value="" name="user_name" id="user_name" type="text" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="user_email">E-MAIL</label>
                                    <input value="" name="user_email" id="user_email" type="email" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="user_sector">SETOR</label>
                                    <select name="user_sector" id="user_sector" class="form-control form-control-sm" required>
                                        <option>Selecione....</option>
                                        <option value="PROGRAMAÇÂO - CPG">PROGRAMAÇÂO - CPG</option>
                                        <option value="PROVIMENTO - CPM">PROVIMENTO - CPM</option>
                                        <option value="ADMINISTRADOR - ADM">ADMINISTRADOR - ADM</option>
                                        <option value="GESTÃO DE INFORMAÇÃO - CGI">GESTÃO DE INFORMAÇÃO - CGI</option>
                                        <option value="AFASTAMENTO DEFINITIVO - CAD">AFASTAMENTO DEFINITIVO - CAD</option>
                                        <option value="CONSULTA">CONSULTA</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group_disciplina">
                                    <label class="control-label" for="user_profile">PERFIL</label required>
                                    <select name="user_profile" id="user_profile" class="form-control form-control-sm">
                                        <option>Selecione....</option>
                                        <option value="administrador">ADMINISTRADOR</option>
                                        <option value="cpg_tecnico">TÉCNICO CPG</option>
                                        <option value="cpm_tecnico">TÉCNICO CPM</option>
                                        <option value="cad_tecnico">TÉCNICO CAD</option>
                                        <option value="cgi_tecnico">TÉCNICO CGI</option>
                                        <option value="cpm_coordenador">COORDENADOR CPM</option>
                                        <option value="consulta">CONSULTA</option>
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