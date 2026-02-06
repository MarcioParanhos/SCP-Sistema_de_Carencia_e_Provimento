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
        <!-- Tabs: Servidores geral + Candidatos Aptos -->
        <ul class="nav nav-tabs mb-3" id="servidoresTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-servidores" data-toggle="tab" href="#pane-servidores" role="tab" aria-controls="pane-servidores" aria-selected="true">Servidores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-aptos" data-toggle="tab" href="#pane-aptos" role="tab" aria-controls="pane-aptos" aria-selected="false">REDAS Ingressados</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="pane-servidores" role="tabpanel" aria-labelledby="tab-servidores">
                <div class="print-btn mb-2 d-flex flex-row-reverse bd-highlight">
                    <a class="mb-2 btn bg-primary text-white" data-toggle="modal" data-target="#ExemploModalCentralizado55"><i class="ti-plus"></i> ADICIONAR</a>
                </div>
                <div class="table-responseive">
                    <table id="servidoresTable" class="table table-hover table-bordered table-sm" style="width:100%">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th scope="col">SERVIDOR</th>
                                <th class="text-center" scope="col">CPF</th>
                                <th class="text-center" scope="col">MATRÍCULA</th>
                                <th class="text-center" scope="col">VINCULO</th>
                                <th class="text-center" scope="col">REGIME</th>
                                <th class="text-center" scope="col">DATA DO CADASTRO</th>
                                <th class="text-center" scope="col">AÇÃO</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pane-aptos" role="tabpanel" aria-labelledby="tab-aptos">
                <div class="table-responseive">
                    <table id="ingressoAptosTable" class="table table-hover table-bordered table-sm" style="width:100%">
                        <thead class="bg-secondary text-white">
                                    <tr>
                                    <th>Inscrição</th>
                                    <th>Nome</th>
                                    <th class="text-center">CPF</th>
                                    <th class="text-center">NTE</th>
                                    <th class="text-center">Unidade escolar</th>
                                    <th class="text-center">Código unidade</th>
                                    <th class="text-center">Matrícula</th>
                                    <th class="text-center">Disciplina</th>
                                    <th class="text-center">Ação</th>
                                </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable with server-side processing
        if (window.jQuery && $.fn.dataTable) {
            $('#servidoresTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('servidores.data') }}',
                    type: 'GET'
                },
                columns: [
                    { data: 'nome', name: 'nome' },
                    { data: 'cpf', name: 'cpf', className: 'text-center' },
                    { data: 'cadastro', name: 'cadastro', className: 'text-center' },
                    { data: 'vinculo', name: 'vinculo', className: 'text-center' },
                    { data: 'regime', name: 'regime', className: 'text-center' },
                    { data: 'created_at', name: 'created_at', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                ordering: false,
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

            // Initialize ingresso aptos DataTable once (prevents reinitialisation errors)
                var aptosTable;
            if (window.jQuery && $.fn.dataTable) {
                // expose on window so other scripts (outside this scope) can access it
                window.aptosTable = $('#ingressoAptosTable').DataTable({
                    processing: true,
                    serverSide: true,
                    dom:
            '<"d-flex flex-column"<"buttons-container text-start mb-2"B><"d-flex justify-content-between align-items-center"<"length-container"l><"search-container"f>>>' +
            "rt" +
            '<"d-flex justify-content-between align-items-center mt-2"<"info-container"i><"pagination-container"p>>',
                    buttons: [
                        {
                        extend: 'excelHtml5',
                        text: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-xls"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" /><path d="M4 15l4 6" /><path d="M4 21l4 -6" /><path d="M17 20.25c0 .414 .336 .75 .75 .75h1.25a1 1 0 0 0 1 -1v-1a1 1 0 0 0 -1 -1h-1a1 1 0 0 1 -1 -1v-1a1 1 0 0 1 1 -1h1.25a.75 .75 0 0 1 .75 .75" /><path d="M11 15v6h3" /></svg>',
                        className: 'btn btn-sm btn-primary dt-btn-excel',
                        exportOptions: { columns: ':visible', modifier: { search: 'applied', order: 'applied' } }
                    }
                    ],
                    ajax: {
                        url: '{{ route('ingresso.data') }}',
                        type: 'GET',
                        data: function(d) {
                            d.filter_ingresso = 'apto';
                        }
                    },
                    columns: [
                        { data: 'num_inscricao', name: 'num_inscricao' },
                        { data: 'name', name: 'name' },
                        { data: 'cpf', name: 'cpf', className: 'text-center' },
                        { data: 'nte', name: 'nte', className: 'text-center' },
                        { data: 'uee_name', name: 'uee_name', className: 'text-center', defaultContent: '' },
                        { data: 'uee_code', name: 'uee_code', className: 'text-center', defaultContent: '' },
                        { data: 'matricula', name: 'matricula', className: 'text-center', defaultContent: '' },
                        { data: 'disciplina', name: 'disciplina', className: 'text-center' },
                        { data: null, orderable: false, searchable: false, className: 'text-center', render: function(data, type, row) {
                                    var id = row.id || row.ID || '';
                                    var label = row.matricula ? 'Editar' : 'Adicionar';
                                    var classes = 'btn btn-sm btn-primary edit-matricula action-btn';
                                    if (label === 'Adicionar') {
                                        // include provided plus SVG for Adicionar (icon-only, with tooltip)
                                        var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>';
                                        return '<button class="' + classes + '" data-id="' + id + '" data-matricula="' + (row.matricula || '') + '" title="Adicionar matrícula" aria-label="Adicionar matrícula">' + svg + '</button>';
                                    }
                                    var editSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415" /><path d="M16 5l3 3" /></svg>';
                                    return '<button class="' + classes + '" data-id="' + id + '" data-matricula="' + (row.matricula || '') + '" title="Editar matrícula" aria-label="Editar matrícula">' + editSvg + '</button>';
                        }},
                    ],
                    ordering: false,
                    language: {
                        decimal: ',',
                        thousands: '.',
                        processing: 'Processando...',
                        search: 'Pesquisar:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando _START_ até _END_ de _TOTAL_ registros',
                        infoEmpty: 'Mostrando 0 até 0 de 0 registros',
                        infoFiltered: '(filtrado de _MAX_ registros no total)',
                        loadingRecords: 'Carregando...',
                        zeroRecords: 'Nenhum registro encontrado',
                        emptyTable: 'Nenhum dado disponível na tabela',
                        paginate: { first: 'Primeiro', previous: 'Anterior', next: 'Próximo', last: 'Último' }
                    }
                });

                // keep local reference too
                aptosTable = window.aptosTable;

                // reload aptos table when its tab is shown
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    var target = $(e.target).attr('href');
                    if (target === '#pane-aptos' && aptosTable) {
                        aptosTable.ajax.reload();
                    }
                });
            }
        } else {
            console.warn('jQuery or DataTables not loaded');
        }
    });
</script>
<!-- Modal para editar/definir matrícula de candidato -->
<div class="modal fade" id="matriculaModal" tabindex="-1" role="dialog" aria-labelledby="matriculaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="matriculaModalLabel">Definir Matrícula</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="matriculaForm">
                    <input type="hidden" id="mat_candidate_id" name="candidate_id" value="">
                    <div class="form-group">
                        <label for="matricula_input">Número da Matrícula</label>
                        <input type="text" class="form-control" id="matricula_input" name="matricula" maxlength="50" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" id="saveMatriculaBtn" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

            <!-- Styles for action buttons -->
            <style>
                .action-btn {
                    padding: 5px !important;
                    border-radius: 5px !important;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                }
                .action-btn svg {
                    display: inline-block;
                    vertical-align: middle;
                }
                .edit-matricula.action-btn {
                    padding-left: 8px !important;
                    padding-right: 8px !important;
                }
            </style>

<script>
    // handler para abrir modal quando clica em editar/adicionar matrícula
    $(document).on('click', '.edit-matricula', function (e) {
        e.preventDefault();
        var btn = $(this);
        var id = btn.data('id');
        var matricula = btn.data('matricula') || '';
        $('#mat_candidate_id').val(id);
        $('#matricula_input').val(matricula);
        $('#matriculaModal').modal('show');
    });

    // handler para salvar via AJAX
    $('#saveMatriculaBtn').on('click', function () {
        var id = $('#mat_candidate_id').val();
        var matricula = $('#matricula_input').val().trim();
        if (!id) {
            Swal.fire({ icon: 'error', title: 'Erro', text: 'ID do candidato não encontrado' });
            return;
        }
        var token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/ingresso/' + encodeURIComponent(id) + '/matricula',
            method: 'POST',
            data: { matricula: matricula },
            headers: { 'X-CSRF-TOKEN': token },
            success: function (res) {
                if (res && res.success) {
                    $('#matriculaModal').modal('hide');
                    // robustly reload the DataTable instance
                    try {
                        var dt = $('#ingressoAptosTable').DataTable();
                        if (dt && dt.ajax) dt.ajax.reload(null, false);
                    } catch (e) {
                        // ignore reload failures
                    }
                    var msg = res.message || 'Matrícula salva com sucesso';
                    var icon = (res.message && res.message.toLowerCase().includes('nenhuma alteração')) ? 'info' : 'success';
                    Swal.fire({ icon: icon, title: icon === 'success' ? 'Salvo' : 'Sem alterações', text: msg, timer: 3000, showConfirmButton: false });
                    // If the servidor record was created, show a confirmation
                    if (res.servidor_saved) {
                        Swal.fire({ icon: 'success', title: 'Servidor criado', text: res.servidor_message || 'Servidor criado com sucesso', timer: 3000, showConfirmButton: false });
                    } else if (res.servidor_message && !res.servidor_saved) {
                        // If there was a note about servidor but it wasn't created, show it as info
                        Swal.fire({ icon: 'info', title: 'Servidor', text: res.servidor_message, timer: 3000, showConfirmButton: false });
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Erro ao salvar', text: res.message || 'Erro ao salvar matrícula' });
                }
            },
            error: function (xhr) {
                var msg = 'Erro ao salvar matrícula';
                try { msg = xhr.responseJSON.message || msg; } catch (e) {}
                Swal.fire({ icon: 'error', title: 'Erro', text: msg });
            }
        });
    });
</script>
@endpush