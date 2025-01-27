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
<div class="card shadow rounded">
    <div class="shadow bg-primary text-white card_title">
        <h4 class=" title_show_carencias">servidor detalhado</h4>
        <a class="mr-2" title="Voltar" href="/servidores">
            <button>
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                    <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
                </svg>
                <span>VOLTAR</span>
            </button>
        </a>
    </div>
    <form class="p-4" action="/servidores/update/{{ $servidor->id }}" method="post">
        @csrf
        @method ('PUT')
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group_disciplina">
                    <label class="control-label" for="nome">NOME COMPLETO</label>
                    <input value="{{ $servidor->nome }}" name="nome" id="nome" type="text" class="form-control form-control-sm" required>
                    <input value="{{ $servidor->nome }}" name="nome_old" id="nome_old" type="text" class="form-control form-control-sm" hidden required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="cpf">CPF</label>
                    <input value="{{ $servidor->cpf }}" name="cpf" id="cpf" type="text" class="form-control form-control-sm" maxlength="11">
                </div>
            </div>
            @if ($servidor->cadastro === $servidor->cpf)
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="cadastro">MATRICULA</label>
                    <input value="" name="cadastro" id="cadastro" type="text" class="form-control form-control-sm" placeholder="INSIRA A MATRICULA PARA ATUALIZAÇÃO">
                </div>
            </div>
            @endif
            @if ($servidor->cadastro != $servidor->cpf)
            <div class="col-md-3">
                <div class="form-group_disciplina">
                    <label class="control-label" for="cadastro">MATRICULA</label>
                    <input value="{{ $servidor->cadastro }}" name="cadastro" id="cadastro" type="text" class="form-control form-control-sm" placeholder="INSIRA A MATRICULA PARA ATUALIZAÇÃO">
                </div>
            </div>
            @endif
            <div class="col-md-2">
                <div class="form-group_disciplina">
                    <label class="control-label" for="vinculo">VINCULO</label>
                    <select name="vinculo" id="vinculo" class="form-control select2" required>
                        <option value="{{ $servidor->vinculo }}">{{ $servidor->vinculo }}</option>
                        <option value="REDA">REDA</option>
                        <option value="EFETIVO">EFETIVO</option>
                        <option value="MUNICIPAL">MUNICIPAL</option>
                        <option value="MILITAR">MILITAR</option>
                    </select>
                </div>
            </div>
            <div class="mb-2 col-md-1">
                <div class="form-group_disciplina">
                    <label class="control-label" for="regime">REGIME</label>
                    <select name="regime" id="regime" class="form-control select2" required>
                        @if ($servidor->regime === 20)
                        <option value="{{ $servidor->regime }}">20</option>
                        <option value="40">40</option>
                        @endif
                        @if ($servidor->regime === 40)
                        <option value="{{ $servidor->regime }}">40</option>
                        <option value="20">20</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <button id="" type="submit" class="btn btn-sm btn-primary"><i class="ti-reload"></i> ATUALIZAR</button>
    </form>
</div>

@endsection