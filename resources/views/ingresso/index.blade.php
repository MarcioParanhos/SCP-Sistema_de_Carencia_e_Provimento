@extends('layout.main')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Ingresso</h2>
            <p class="text-muted">Área para gerenciamento do fluxo de convocação / ingresso.</p>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <p>Bem-vindo ao módulo de Ingresso. Apenas usuários autorizados (setor 7 / perfil 1) podem acessar esta tela.</p>

        </div>
    </div>
</div>
@endsection
