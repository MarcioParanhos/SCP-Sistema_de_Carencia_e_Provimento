@extends('layout.main')

@section('title', 'SCP - Usuários')

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
    <h4 class=" title_show_carencias">LISTA DE USUÁRIOS</h4>
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
        @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
        <a class="mb-2 btn bg-primary text-white" data-toggle="modal" data-target="#addNewUser" title="Adicionar Novo" href="{{ route('regularizacao_funcional.create') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 5l0 14" />
                <path d="M5 12l14 0" />
            </svg>
        </a>
        @endif
        <a class="mb-2 btn bg-primary text-white" target="_blank" href="/excel/carencias" data-toggle="tooltip" data-placement="top" title="Download em Excel">
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
                <th>ID</th>
                <th>NOME</th>
                <th>EMAIL</th>
                <th>SETOR</th>
                <th>PERFIL</th>
                <th>ÚLTIMO LOGIN</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td class="text-center">{{ $user->id }}</td>
                <td class="text-center">{{ $user->name }}</td>
                <td class="text-center">{{ $user->email }}</td>
                <td class="text-center">{{ $user->setor }}</td>
                <td class="text-center">{{ $user->profile }}</td>
                <td class="text-center">{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d/m/Y') : 'N/A' }}</td>
                <td class="text-center">
                    <div class="btn-group dropleft">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu">
                            <a class="text-primary dropdown-item" href="/detalhar_user/{{ $user -> id }}"><i class="fas fa-edit"></i> Editar</a>
                            <a class="text-info dropdown-item" onclick="resetPass('<?php echo $user->id; ?>')"><i class="fas fa-key"></i> Resetar Senha</a>
                            <a class="text-danger dropdown-item" href="#"><i class="far fa-trash-alt"></i> Excluir</a>
                        </div>
                    </div>
                </td>
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