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

    @if (session('msg'))
        <input id="session_message" value="{{ session('msg') }}" type="text" hidden>
    @endif

    <!-- <div class="mb-2 print-btn">
                        <a class="mb-2 btn bg-primary text-white" href="" data-toggle="modal" data-target="#addNewUser"><i class="ti-plus"></i> ADICIONAR</a>
                        <a id="active_filters" class="mb-2 btn bg-primary text-white" onclick="active_filters_provimento()">FILTROS <i class='far fa-eye'></i></a>
                    </div> -->
    <div class="bg-primary card text-white card_title">
        <h4 class=" title_show_carencias">LOG DE ATIVIDADES</h4>
    </div>
    <div class="table-responsive">
        <table id="logsTable" class="table-bordered table-sm table">
            <thead class="bg-primary text-white">
                <tr class="text-center subheader">
                    <th>FONTE</th>
                    <th>ID</th>
                    <th>SERVIÇO</th>
                    <th>DATA</th>
                    <th>USUÁRIO</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                {{-- data loaded by DataTables server-side AJAX --}}
            </tbody>
        </table>

    </div>

    <!-- Modal: Alterações -->
    <div class="modal fade" id="changesModal" tabindex="-1" role="dialog" aria-labelledby="changesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="changesModalLabel">Visualizar Alterações</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="changesSummary" class="mb-2 small-muted"></div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="changesTable">
                            <thead>
                                <tr>
                                    <th>Campo</th>
                                    <th>Antes</th>
                                    <th>Depois</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            function decodeBase64Json(b64) {
                try {
                    var json = atob(b64);
                    return JSON.parse(json);
                } catch(e) { return null; }
            }

            function escapeHtml(str) {
                if (str === null || str === undefined) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            document.addEventListener('click', function(e){
                var btn = e.target.closest('.btn-view-changes');
                if (!btn) return;
                var oldB64 = btn.getAttribute('data-old');
                var newB64 = btn.getAttribute('data-new');
                var module = btn.getAttribute('data-module') || '';
                var id = btn.getAttribute('data-id') || '';

                var oldObj = decodeBase64Json(oldB64) || {};
                var newObj = decodeBase64Json(newB64) || {};

                // try to extract disciplina name from common fields or nested objects
                function extractDisciplinaName(obj) {
                    if (!obj) return null;
                    var candidates = [
                        'disciplina', 'disciplina_nome', 'nome_disciplina', 'nome', 'name'
                    ];
                    for (var i = 0; i < candidates.length; i++) {
                        var key = candidates[i];
                        if (obj.hasOwnProperty(key) && obj[key]) {
                            var val = obj[key];
                            if (typeof val === 'string' && val.trim() !== '') return val;
                            if (typeof val === 'object') {
                                if (val.nome) return val.nome;
                                if (val.name) return val.name;
                            }
                        }
                    }
                    // also check for nested disciplina object
                    if (obj.disciplina && typeof obj.disciplina === 'object') {
                        if (obj.disciplina.nome) return obj.disciplina.nome;
                        if (obj.disciplina.name) return obj.disciplina.name;
                    }
                    return null;
                }

                var disciplinaName = extractDisciplinaName(newObj) || extractDisciplinaName(oldObj) || '';

                // gather keys
                var keys = Object.keys(oldObj).concat(Object.keys(newObj)).filter(function(v,i,a){ return a.indexOf(v)===i; });

                var tbody = document.querySelector('#changesTable tbody');
                tbody.innerHTML = '';
                var changes = 0;
                keys.forEach(function(key){
                    var oldVal = oldObj.hasOwnProperty(key) ? oldObj[key] : null;
                    var newVal = newObj.hasOwnProperty(key) ? newObj[key] : null;
                    var oldStr = (typeof oldVal === 'object') ? JSON.stringify(oldVal, null, 2) : (oldVal === null ? '' : String(oldVal));
                    var newStr = (typeof newVal === 'object') ? JSON.stringify(newVal, null, 2) : (newVal === null ? '' : String(newVal));
                    if (oldStr !== newStr) {
                        changes++;
                        var tr = document.createElement('tr');
                        var tdKey = document.createElement('td'); tdKey.innerHTML = '<strong>'+escapeHtml(key)+'</strong>';
                        var tdOld = document.createElement('td'); tdOld.style.whiteSpace = 'pre-wrap'; tdOld.style.maxWidth = '45%'; tdOld.innerHTML = escapeHtml(oldStr) || '<span class="text-muted">NULL</span>';
                        var tdNew = document.createElement('td'); tdNew.style.whiteSpace = 'pre-wrap'; tdNew.style.maxWidth = '45%'; tdNew.innerHTML = escapeHtml(newStr) || '<span class="text-muted">NULL</span>';
                        tr.appendChild(tdKey); tr.appendChild(tdOld); tr.appendChild(tdNew);
                        tbody.appendChild(tr);
                    }
                });

                var summary = document.getElementById('changesSummary');
                var disciplinaPart = disciplinaName ? ' · Disciplina: ' + disciplinaName : '';
                summary.innerText = 'Módulo: ' + module + (id ? ' · ID: ' + id : '') + disciplinaPart + ' · Campos alterados: ' + changes;

                // show modal (requires bootstrap/jQuery)
                if (window.jQuery && jQuery.fn.modal) {
                    jQuery('#changesModal').modal('show');
                } else {
                    // fallback: simple alert
                    alert('Alterações: ' + changes + ' campos (sem modal, jQuery não disponível)');
                }
            });
        })();
    </script>

    <!-- DataTables (server-side) includes and initialization -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        (function(){
            // initialize DataTable with server-side processing
            var table = $('#logsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('logs.data') }}',
                    type: 'GET'
                },
                pageLength: 10,
                order: [[3, 'desc']],
                language: {
                    decimal: ',',
                    thousands: '.',
                    emptyTable: 'Nenhum registro encontrado',
                    info: 'Mostrando de _START_ até _END_ de _TOTAL_ registros',
                    infoEmpty: 'Mostrando 0 até 0 de 0 registros',
                    infoFiltered: '(Filtrado de _MAX_ registros)',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    loadingRecords: 'Carregando...',
                    processing: 'Processando...',
                    search: 'Pesquisar:',
                    zeroRecords: 'Nenhum registro encontrado',
                    paginate: {
                        first: 'Primeiro',
                        previous: 'Anterior',
                        next: 'Próximo',
                        last: 'Último'
                    },
                    aria: {
                        sortAscending: ': Ordenar colunas de forma ascendente',
                        sortDescending: ': Ordenar colunas de forma descendente'
                    }
                },
                columns: [
                    { data: 'module', className: 'text-center text-uppercase' },
                    { data: 'id_html', className: 'text-center', orderable: false, searchable: false },
                    { data: 'action', className: 'text-center subheader text-uppercase' },
                    { data: 'created_at', className: 'text-center' },
                    { data: 'user_name', className: 'text-center text-uppercase' },
                    { data: 'actions', className: 'text-center', orderable: false, searchable: false }
                ],
                createdRow: function(row, data, dataIndex) {
                    // inject HTML for id and actions (returned as HTML strings)
                    if (data.id_html) {
                        $('td', row).eq(1).html(data.id_html);
                    }
                    if (data.actions) {
                        $('td', row).eq(5).html(data.actions);
                    }
                }
            });

            // redraw table when filter form or global interactions are added (optional)
        })();
    </script>

@endsection
