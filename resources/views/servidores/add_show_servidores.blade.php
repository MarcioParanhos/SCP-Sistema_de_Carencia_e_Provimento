@extends('layout.main')

@section('title', 'SCP - Servidores Cadastrados')

@section('content')


@if(session('msg'))
<div class="col-12">
    <div class="alert text-center text-white bg-success container alert-success alert-dismissible fade show" role="alert" style="min-width: 100%">
        <strong>{{ session('msg')}}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif
<div class="card">
    <div class="bg-primary text-white card-header">
        <h4>CADASTRAR SERVIDORES</h4>
    </div>
    <div class="card-body">
        <div class="print-btn mb-2 d-flex flex-row-reverse bd-highlight">
            <a class="mb-2 btn bg-primary text-white" data-toggle="modal" data-target="#ExemploModalCentralizado55"><i class="ti-plus"></i> ADICIONAR</a>
        </div>
        <div class="table-responseive">
            <table id="consultarCarencias" class="table table-hover table-bordered table-sm">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">SERVIDOR</th>
                        <th class="text-center" scope="col">CPF</th>
                        <th class="text-center" scope="col">MATRICULA</th>
                        <th class="text-center" scope="col">VINCULO</th>
                        <th class="text-center" scope="col">REGIME</th>
                        <th class="text-center" scope="col">DATA DO CADASTRO</th>
                        <th class="text-center" scope="col">AÇÃO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($servidores as $servidores)
                    <tr>
                        <td>{{$servidores->nome}}</td>
                        <td class="text-center">{{$servidores->cpf}}</td>
                        @if ($servidores->cadastro === $servidores->cpf)
                        <td class="text-center">PENDENTE</td>
                        @elseif ($servidores->cadastro !== $servidores->cpf)
                        <td class="text-center">{{ $servidores->cadastro }}</td>
                        @endif
                        <td class="text-center">{{$servidores->vinculo}}</td>
                        <td class="text-center">{{$servidores->regime}}h</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($servidores->created_at)->format('d/m/Y') }}</td>
                        <td class="text-center d-flex align-items-center justify-content-center">
                            <a href="/servidor/detalhes/{{ $servidores->id }}" class="mr-2"><button id="" type="submit" class="btn-show-carência btn btn-primary"><i class="ti-pencil"></i></button></a>
                            <form class="" action='/servidor/destroy/{{ $servidores->id }}' method='post'>
                                @csrf
                                @method('DELETE')
                                <a title="Excluir"><button id="" type="submit" class="btn-show-carência btn btn-danger"><i class="ti-trash"></i></button></a>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ExemploModalCentralizado55" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalCentralizado">NOVO SERVIDOR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form style="width: 100%;" action="{{ route('servidores.add') }}" method="post">
                    @csrf
                    <input value="cadastrado" name="tipo" id="tipo" type="text" class="form-control form-control-sm" hidden>
                    <input value="PENDENTE" name="cadastro" id="cadastro" type="text" class="form-control form-control-sm" hidden>
                    @if (Auth::user()->profile === "cpm_tecnico")
                    <input value="cpm_tecnico" name="profile" id="profile" type="text" class="form-control form-control-sm" hidden>
                    @endif
                    @if (Auth::user()->profile === "cpg_tecnico")
                    <input value="cpg_tecnico" name="profile" id="profile" type="text" class="form-control form-control-sm" hidden>
                    @endif
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="nome">NOME DO SERVIDOR</label>
                                <input value="" name="nome" id="nome" type="text" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row mb-2">
                        <div class="col-md-6">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="cpf">CPF</label>
                                <input value="" name="cpf" id="cpf" type="text" class="form-control form-control-sm" maxlength="11" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="cadastro">MATRÍCULA</label>
                                <input value="" name="cadastro" id="cadastro" type="text" class="form-control form-control-sm" maxlength="11">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="vinculo">VINCULO</label>
                                <select name="vinculo" id="vinculo" class="form-control form-control-sm " required>
                                    <option></option>
                                    <option value="REDA">REDA</option>
                                    <option value="EFETIVO">EFETIVO</option>
                                    <option value="MUNICIPAL">MUNICIPAL</option>
                                    <option value="MILITAR">MILITAR</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group_disciplina">
                                <label class="control-label" for="vinculo">REGIME</label>
                                <select name="regime" id="regime" class="form-control form-control-sm " required>
                                    <option></option>
                                    <option value="40">40h</option>
                                    <option value="20">20h</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">CADASTRAR</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection