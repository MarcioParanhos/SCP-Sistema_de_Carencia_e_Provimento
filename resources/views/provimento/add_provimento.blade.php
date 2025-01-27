@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="card shadow rounded">
        <div class="card_title_form">
            <h4 class="card-title">INCLUIR PROVIMENTO</h4>
        </div>
        <div class="card-body">
            <form class="forms-sample" id="InsertForm">
                @csrf
                <input value="" id="tipo_vaga" name="tipo_vaga" type="text" class="form-control form-control-sm" hidden>
                <input value="Real" id="tipo_carencia" name="tipo_carencia" type="text" class="form-control form-control-sm" hidden>
                <div class="form-row">
                    <div class=" col-md-2">
                        <div class="display_btn position-relative form-group">
                            <div>
                                <label for="cod_unidade_provimento" class="">buscar Cod. UE</label>
                                <input value="" minlength="8" maxlength="9" name="cod_unidade_provimento" id="cod_unidade_provimento" type="number" class="form-control form-control-sm">
                            </div>
                            <div class="btn_carencia_seacrh">
                                <button id="btn-cadastro" class="btn_search_carencia btn btn-sm btn-primary" type="button" onclick="addNewProvimento()" required>
                                    <i class="ti-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('keydown', function(e) {
                        if (e.keyCode === 13) {
                            e.preventDefault(); // Impede o comportamento padrão de envio do formulário
                            document.getElementById('btn-cadastro').click(); // Chama manualmente o evento de clique no botão
                        }
                    });
                </script>
            </form>
        </div>
    </div>
</div>

@endsection