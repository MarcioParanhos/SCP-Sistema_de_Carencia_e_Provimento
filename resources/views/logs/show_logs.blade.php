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
    <!-- Admin summary cards -->
    <style>
        /* Card tweaks for a cleaner professional look */
        .kpi-card { border: 0; border-radius: .6rem; box-shadow: 0 6px 18px rgba(36,41,46,.08); min-height: 170px; }
        .kpi-icon { width:48px; height:48px; flex: 0 0 48px; border-radius:.5rem; display:flex; align-items:center; justify-content:center; color:#fff; }
        .kpi-list { /* aumentar para evitar barra de rolagem */ max-height: none; overflow: visible; padding-right:6px; }
        .kpi-item { font-size:.88rem; gap:.5rem; align-items:center; display:flex; justify-content:space-between; padding:.28rem .2rem; border-radius:.35rem; }
        .kpi-item + .kpi-item { margin-top:.25rem; }
        .kpi-progress { height:6px; border-radius:6px; background: rgba(0,0,0,.06); overflow:hidden; }
        .kpi-progress > i { display:block; height:100%; background:linear-gradient(90deg, rgba(255,255,255,.15), rgba(255,255,255,.02)); }
        /* nomes de usuário menores para caber */
        .kpi-user-name { font-size:.80rem; max-width:110px; display:inline-block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; vertical-align:middle; }
        @media (max-width:575.98px) {
            .kpi-icon { width:42px; height:42px; flex:0 0 42px; }
            .kpi-card { min-height: auto; }
            .kpi-user-name { max-width:80px; font-size:.78rem; }
        }
    </style>

    <div class="row mb-3">
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="kpi-icon bg-primary mr-3" aria-hidden="true">
                        <!-- list icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6h16M4 12h10M4 18h16" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <h6 class="mb-0 text-muted">Total de Registros</h6>
                                <p class="h3 mb-0">{{ $totalLogs ?? 0 }}</p>
                            </div>
                        </div>
                        <small class="text-muted">Entradas no período atual</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-2">
                        <div class="kpi-icon bg-success mr-3" aria-hidden="true">
                            <!-- activity icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 12h3l3 8 4-16 3 8h4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <h6 class="mb-0">Ações (por tipo)</h6>
                            <small class="text-muted">Contagem por ação</small>
                        </div>
                    </div>

                    <div class="kpi-list">
                        @php
                            $actionMap = [
                                'create'  => 'Criado',
                                'created' => 'Criado',
                                'store'   => 'Criado',
                                'inclusion' => 'Inclusão',
                                'update'  => 'Atualização',
                                'updated' => 'Atualização',
                                'edit'    => 'Atualização',
                                'delete'  => 'Excluído',
                                'deleted' => 'Excluído',
                                'destroy' => 'Excluído',
                                'restore' => 'Restaurado',
                                'login'   => 'Login',
                                'logout'  => 'Logout',
                                'import'  => 'Importado',
                                'export'  => 'Exportado'
                            ];

                            // Agrupar por rótulo (label) somando os totais para evitar duplicatas como "Atualização" duplicado
                            $grouped = [];
                            if (!empty($actionsCount) && is_iterable($actionsCount)) {
                                foreach ($actionsCount as $act) {
                                    $raw = strtolower(trim((string) ($act->action ?? '')));
                                    $label = $actionMap[$raw] ?? (strlen($act->action ?? '') ? ucfirst($act->action) : 'Outro');
                                    $count = isset($act->total) ? (int) $act->total : 0;
                                    if (isset($grouped[$label])) {
                                        $grouped[$label] += $count;
                                    } else {
                                        $grouped[$label] = $count;
                                    }
                                }
                                // ordenar por total decrescente (opcional)
                                arsort($grouped);
                            }
                        @endphp

                        @if(!empty($grouped) && count($grouped))
                            @foreach($grouped as $label => $cnt)
                                @php
                                    $pct = ($totalLogs && $totalLogs>0) ? round(min(100, ($cnt / $totalLogs) * 100), 0) : 0;
                                @endphp
                                <div class="kpi-item">
                                    <div class="text-truncate" style="max-width:140px;">{{ $label }}</div>
                                    <div class="text-right ml-2" style="min-width:64px;">
                                        <strong>{{ $cnt }}</strong>
                                        <div class="kpi-progress mt-1" aria-hidden="true">
                                            <i style="width:{{ $pct }}%; background: linear-gradient(90deg,#28a745,#8fd19e);"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-muted small">Nenhuma ação registrada</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-2">
                        <div class="kpi-icon bg-info mr-3" aria-hidden="true">
                            <!-- layers icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l9 5-9 5-9-5 9-5z"/><path d="M3 12l9 5 9-5"/><path d="M3 19l9 5 9-5"/></svg>
                        </div>
                        <div>
                            <h6 class="mb-0">Módulos (top)</h6>
                            <small class="text-muted">Origem dos logs</small>
                        </div>
                    </div>

                    <div class="kpi-list">
                        @if(!empty($logsByModule) && count($logsByModule))
                            @foreach($logsByModule as $m)
                                @php
                                    $pct = ($totalLogs && $totalLogs>0) ? round(min(100, ($m->total / $totalLogs) * 100), 0) : 0;
                                @endphp
                                <div class="kpi-item">
                                    <div class="text-truncate" style="max-width:140px;">{{ $m->module }}</div>
                                    <div style="min-width:68px;" class="text-right">
                                        <strong>{{ $m->total }}</strong>
                                        <div class="kpi-progress mt-1" aria-hidden="true">
                                            <i style="width:{{ $pct }}%; background: linear-gradient(90deg,#17a2b8,#7fd3df);"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-muted small">Nenhum módulo encontrado</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card kpi-card h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-2">
                <div class="kpi-icon bg-warning mr-3" aria-hidden="true" style="color:#212529;">
                    <!-- users icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <h6 class="mb-0">Top Usuários</h6>
                    <small class="text-muted">Mais ativos no período</small>
                </div>
                </div>

                <div class="kpi-list">
                @if(!empty($topUsers) && count($topUsers))
                    @foreach($topUsers as $u)
                    @php
                        $totalUser = $u['total'] ?? 0;
                        $pct = ($totalLogs && $totalLogs>0) ? round(min(100, ($totalUser / $totalLogs) * 100), 0) : 0;
                        $fullName = trim((string)($u['name'] ?? '—'));
                        // compute first and last name
                        if ($fullName === '' || $fullName === '—') {
                        $shortName = '—';
                        } else {
                        $parts = preg_split('/\s+/', $fullName);
                        $first = $parts[0] ?? '';
                        $last = count($parts) > 1 ? $parts[count($parts)-1] : '';
                        $shortName = $first . ($last && $last !== $first ? ' ' . $last : '');
                        }
                    @endphp
                    <div class="kpi-item">
                        <div class="kpi-user-name" title="{{ $fullName }}" style="font-size:.72rem; max-width:110px;">{{ $shortName }}</div>
                        <div style="min-width:68px;" class="text-right">
                        <strong>{{ $totalUser }}</strong>
                        <div class="kpi-progress mt-1" aria-hidden="true">
                            <i style="width:{{ $pct }}%; background: linear-gradient(90deg,#ffc107,#ffdea3);"></i>
                        </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-muted small">Nenhum usuário ativo</div>
                @endif
                </div>
            </div>
            </div>
        </div>
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
