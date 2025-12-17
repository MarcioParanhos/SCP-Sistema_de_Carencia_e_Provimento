@extends('layout.main')

@section('title', 'SCP - Ingresso')

@section('content')

@if(session('status'))
<div class="col-12">
    <div class="alert text-center text-white bg-success container alert-success alert-dismissible fade show" role="alert" style="min-width: 100%">
        <strong>{{ session('status')}}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif

<div class="card">
    <div class="bg-primary text-white card-header">
        <h4>INGRESSO - CANDIDATOS</h4>
    </div>
    <div class="card-body">
        <div class="print-btn mb-2 d-flex flex-row-reverse bd-highlight">
            <!-- Optionally add actions here -->
        </div>
        <div class="table-responseive">
            @if(empty($columns))
                <div class="alert alert-warning">A tabela <strong>ingresso_candidatos</strong> não foi encontrada ou não possui colunas definidas.</div>
            @else
                <table id="ingressoTable" class="table table-hover table-bordered table-sm" style="width:100%">
                    <thead class="bg-primary text-white">
                        <tr>
                            @foreach($columns as $col)
                                <th scope="col">{{ strtoupper(str_replace('_', ' ', $col)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable with server-side processing
        if (window.jQuery && $.fn.dataTable) {
            const columns = @json($columns ?? []);
            if (!columns.length) {
                console.warn('ingresso: no columns defined');
                return;
            }

            const dtCols = columns.map(function(c){ return { data: c, name: c }; });

            $('#ingressoTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('ingresso.data') }}',
                    type: 'GET'
                },
                columns: dtCols,
                ordering: false,
                lengthMenu: [10, 25, 50, 100],
                drawCallback: function(settings) {
                    if (window.jQuery && $.fn.tooltip) {
                        $('[data-toggle="tooltip"]').tooltip({container: 'body'});
                    }
                },
                language: {
                    decimal: ',',
                    thousands: '.',
                    processing: 'Processando...',
                    search: 'Pesquisar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ até _END_ de _TOTAL_ registros',
                    infoEmpty: 'Mostrando 0 até 0 de 0 registros',
                    infoFiltered: '(filtrado de _MAX_ registros no total)',
                    infoPostFix: '',
                    loadingRecords: 'Carregando...',
                    zeroRecords: 'Nenhum registro encontrado',
                    emptyTable: 'Nenhum dado disponível na tabela',
                    paginate: {
                        first: 'Primeiro',
                        previous: 'Anterior',
                        next: 'Próximo',
                        last: 'Último'
                    },
                    aria: {
                        sortAscending: ': ativar para ordenar a coluna de forma crescente',
                        sortDescending: ': ativar para ordenar a coluna de forma decrescente'
                    }
                }
            });
        } else {
            console.warn('jQuery or DataTables not loaded');
        }
    });
</script>
@endpush
