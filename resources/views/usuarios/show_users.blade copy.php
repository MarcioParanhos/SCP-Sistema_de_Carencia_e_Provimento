@extends('layout.main')

@section('title', 'SCP - Usuarios')

@section('content')

<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias">Lista de Usuarios</h3>
</div>
<div class="table-responsive">
    <table id="consultarCarencias" class="table table-sm table-bordered">
        <thead class="bg-primary text-white">
            <tr class="text-center">
                <th>ID</th>
                <th>NOME</th>
                <th>EMAIL</th>
                <th>SETOR</th>
                <th>PERFIL</th>
                <th>ÚLTIMO LOGIN</th>
                <th>AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td class="text-center">{{ $user->id }}</td>
                <td class="text-center">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-center">{{ $user->setor }}</td>
                <td class="text-center">{{ $user->profile }}</td>
                <td class="text-center">{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d/m/Y') : 'N/A' }}</td>
                <td>
                    <div class="d-flex justify-content-center" style="gap: 10px;">
                        <a title="Editar" href="/detalhar_user/{{ $user -> id }}"><button id="" class="btn-show-carência btn btn-sm btn-primary"><i class="ti-pencil"></i></button></a>
                        <a title="Resetar Senha"><button id="" onclick="resetPass('<?php echo $user->id; ?>')" class="btn-show-carência btn btn-sm btn-info"><i class="ti-key"></i></button></a>
                        <form class="" action='/users/destroy/{{ $user->id }}' method='post'>
                            @csrf
                            @method('DELETE')
                            <a title="Excluir"><button id="" type="submit" class="btn btn-danger"><i class="ti-trash"></i></button></a>
                        </form>
                    </div>

                </td>
                <td class="text-center">
                    <div class="btn-group dropleft">
                        <button type="button" class="btn btn-primary  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        </button>
                        <div class="dropdown-menu">
                            <a class="text-primary dropdown-item" href="#"><i class="fas fa-edit"></i> Editar</a>
                            <a class="text-danger dropdown-item" href="#"><i class="far fa-trash-alt"></i> Excluir</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection