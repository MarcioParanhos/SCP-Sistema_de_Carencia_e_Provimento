@extends('layout.main')

@section('title', 'SCP - Nota Tecnica')

@section('content')
@if(session('msg'))
<div class="col-12">
    <div class="alert text-center text-white bg-danger container alert-success alert-dismissible fade show" role="alert" style="min-width: 100%">
        <strong>{{ session('msg')}}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif
<div class="col-12 grid-margin stretch-card">
    <div class="card shadow rounded">
        <div class="card_title_form">
            <h4 class="card-title">BUSCAR UNIDADE ESCOLAR PARA IMPRESSÃO DE NOTA TECNICA</h4>
        </div>
        <div class="card-body">
            <form class="forms-sample" action="/consultarNotaTecnica" method="post" target="_blank"> 
                @csrf
                <input value="" id="tipo_vaga" name="tipo_vaga" type="text" class="form-control form-control-sm" hidden>
                <input value="Real" id="tipo_carencia" name="tipo_carencia" type="text" class="form-control form-control-sm" hidden>
                <div class="form-row">
                    <div class=" col-md-2">
                        <div class="display_btn position-relative form-group">
                            <div>
                                <label for="cod_unidade_nota_tecnica" class="">buscar Cod. UE</label>
                                <input value="" minlength="8" maxlength="9" name="cod_unidade_nota_tecnica" id="cod_unidade_nota_tecnica" type="number" class="form-control form-control-sm">
                            </div>
                            <div class="btn_carencia_seacrh">
                                <a target="_blank"><button id="btn-cadastro" class="btn_search_carencia btn btn-sm btn-primary" type="submit" required>
                                    <i class="ti-search"></i>
                                </button></a>
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
<script>
    const active_relatorios = document.getElementById("active_relatorios")
    active_relatorios.classList.add('active')
</script>
@endsection

