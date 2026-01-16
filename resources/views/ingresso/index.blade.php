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
    <style>
        /* Centraliza todas as células da tabela ingresso */
        #ingressoTable th, #ingressoTable td { text-align: center !important; vertical-align: middle !important; }

        /* Botões menores e com radius de 5px */
        #ingressoTable .btn-detalhar {
            border-radius: 5px !important;
            padding: 0.3rem 0.3rem !important;
            font-size: 0.72rem !important;
            line-height: 1 !important;
        }

        /* Ícone interno menor */
        #ingressoTable .btn-group svg {
            width: 18px !important;
            height: 18px !important;
        }

        /* Espaçamento entre botões do grupo reduzido e remover margens extras */
        #ingressoTable .btn-group { gap: 0.3rem; }
        #ingressoTable .btn-group a { margin: 0 !important; display: inline-flex; align-items: center; }

        /* STATUS badge: radius 5px, texto branco em negrito */
        #ingressoTable .badge {
            border-radius: 5px !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            padding: 0.35em 0.55em !important;
        }

        /* DataTables custom processing overlay */
        #dt-processing-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.10);
            z-index: 1100;
        }
        .dt-overlay-card {
            display: flex;
            gap: 12px;
            align-items: center;
            background: #ffffff;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .dt-overlay-spinner {
            width: 28px;
            height: 28px;
            border: 3px solid rgba(0,0,0,0.10);
            border-top-color: #fff;
            border-right-color: #007bff;
            border-radius: 50%;
            box-sizing: border-box;
            animation: dt-spin 1s linear infinite;
        }
        .dt-overlay-text { font-weight:700; color:#222; font-size:0.95rem; }
        @keyframes dt-spin { to { transform: rotate(360deg); } }
    </style>
    <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">INGRESSO - CANDIDATOS</h4>
    </div>
    <div class="card-body">
        <div class="print-btn mb-2 d-flex pb-4">
            <div class="d-flex" style="gap:4px;">
                <a href="{{ route('ingresso.dashboard') }}" class="btn btn-primary btn-sm" title="Dashboard" style="border-radius:5px;padding:5px !important;display:inline-flex;align-items:center;justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-layout-dashboard" style="width:24px;height:24px;">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 3a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2zm0 12a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2zm10 -4a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2zm0 -8a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2z" />
                    </svg>
                </a>

                <button id="btn-export-csv" class="btn btn-primary btn-sm" title="Exportar CSV" style="border-radius:5px;padding:5px !important;display:inline-flex;align-items:center;justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-csv" style="width:24px;height:24px;">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                        <path d="M7 16.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0" />
                        <path d="M10 20.25c0 .414 .336 .75 .75 .75h1.25a1 1 0 0 0 1 -1v-1a1 1 0 0 0 -1 -1h-1a1 1 0 0 1 -1 -1v-1a1 1 0 0 1 1 -1h1.25a.75 .75 0 0 1 .75 .75" />
                        <path d="M16 15l2 6l2 -6" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Filtro collapse (inicialmente oculto) -->
        <div id="filterPanel" class="border rounded bg-light p-3 mb-3" style="display:none;">
            <form id="filterForm" class="form-row align-items-end">
                @php
                    $isNteUser = optional(Auth::user())->profile_id == 1 && optional(Auth::user())->sector_id == 7;
                @endphp
                @if($isNteUser)
                    <input type="hidden" id="filter_nte" value="{{ Auth::user()->nte ?? '' }}">
                @else
                <div class="col-md-2">
                    <label for="filter_nte">NTE</label>
                    <select id="filter_nte" class="form-control form-control-sm">
                        <option value="">(Todos)</option>
                        @if(!empty($ntes))
                            @foreach($ntes as $nteVal)
                                <option value="{{ $nteVal }}">{{ str_pad($nteVal, 2, '0', STR_PAD_LEFT) }}</option>
                            @endforeach
                        @elseif(optional(Auth::user())->nte)
                            <option value="{{ Auth::user()->nte }}">{{ Auth::user()->nte }}</option>
                        @endif
                    </select>
                </div>
                @endif
                <div class="col-md-2">
                    <label for="filter_status">Status</label>
                    <select id="filter_status" class="form-control form-control-sm">
                        <option value="">(Todos)</option>
                        <option>Documentos Validados</option>
                        <option>Documentos Pendentes</option>
                        <option>Apto para ingresso</option>
                        <option>Corrigir documentação</option>
                        <option value="Nao Assumiu">Não Assumiu</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" id="applyFilters" class="btn btn-primary">Aplicar</button>
                    <button type="button" id="clearFilters" class="btn btn-secondary ml-2">Limpar</button>
                </div>
            </form>
        </div>
        <div class="table-responsive" style="position:relative">
            <!-- Custom processing overlay for nicer UX -->
            <div id="dt-processing-overlay" style="display:none;">
                <div class="dt-overlay-card">
                    <div class="dt-overlay-spinner" aria-hidden="true"></div>
                    <div class="dt-overlay-text">Processando...</div>
                </div>
            </div>
            @php
                // For now show only two columns: num_inscricao and name
            @endphp
            <table id="ingressoTable" class="table table-hover table-bordered table-sm" style="width:100%">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Nº DA INSCRIÇÃO</th>
                        <th scope="col">NOME</th>
                        <th scope="col">CPF</th>
                        <th scope="col">NTE</th>
                        <th scope="col">Class. Ampla</th>
                        <th scope="col">Class. PNE</th>
                        <th scope="col">Class. Racial</th>
                        <th scope="col">NOTA</th>
                        <th scope="col">Nº PROCESSO SEI</th>
                        <th scope="col">STATUS</th>
                        <th scope="col">DOCS.</th>
                        <th scope="col">AÇÃO</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable with server-side processing for two columns only
        if (window.jQuery && $.fn.dataTable) {
                const dtCols = [
                {
                    data: 'num_inscricao', name: 'num_inscricao',
                    render: function(data, type, row) {
                        return (data === null || data === undefined || data === '') ? '-' : data;
                    }
                },
                {
                    data: 'name', name: 'name',
                    render: function(data, type, row) {
                        return (data === null || data === undefined || data === '') ? '-' : data;
                    }
                },
                {
                    data: 'cpf', name: 'cpf',
                    render: function(data, type, row) {
                        return (data === null || data === undefined || data === '') ? '-' : data;
                    }
                },
                {
                    data: 'nte', name: 'nte',
                    render: function(data, type, row) {
                        // render with zero-pad for single-digit NTEs
                        if (data === null || data === undefined || data === '') return '-';
                        var n = parseInt(data);
                        return isNaN(n) ? String(data) : (n > 9 ? String(n) : ('0' + String(n)));
                    }
                },
                {
                    data: 'classificacao_ampla', name: 'classificacao_ampla',
                    render: function(data, type, row) {
                        if (data === null || data === undefined || data === '') return '-';
                        var s = String(data).trim();
                        if (s === '' || s === '-') return '-';
                        // only append degree symbol when string contains a digit
                        if (/\d/.test(s) && s.indexOf('º') === -1) s = s + 'º';
                        return s;
                    }
                },
                {
                    data: 'classificacao_quota_pne', name: 'classificacao_quota_pne',
                    render: function(data, type, row) {
                        if (data === null || data === undefined || data === '') return '-';
                        var s = String(data).trim();
                        if (s === '' || s === '-') return '-';
                        if (/\d/.test(s) && s.indexOf('º') === -1) s = s + 'º';
                        return s;
                    }
                },
                {
                    data: 'classificacao_racial', name: 'classificacao_racial',
                    render: function(data, type, row) {
                        if (data === null || data === undefined || data === '') return '-';
                        var s = String(data).trim();
                        if (s === '' || s === '-') return '-';
                        if (/\d/.test(s) && s.indexOf('º') === -1) s = s + 'º';
                        return s;
                    }
                },
                {
                    data: 'nota', name: 'nota',
                    render: function(data, type, row) {
                        return (data === null || data === undefined || data === '') ? '-' : data;
                    }
                }
                ,{
                    data: 'sei_number', name: 'sei_number',
                    render: function(data, type, row) {
                        return (data === null || data === undefined || data === '') ? '-' : data;
                    }
                }
                ,{
                    data: 'status', name: 'status',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        // prefer explicit row.status when present
                        var statusText = (row && row.status) ? String(row.status) : (data ? String(data) : '');
                        // fallback to documentos_validados flag if no status text
                        if (!statusText || statusText.trim() === '') {
                            if (row && (row.documentos_validados === 1 || String(row.documentos_validados) === '1' || row.documentos_validados === true)) {
                                statusText = 'Documentos Validados';
                            } else {
                                statusText = 'Documentos Pendentes';
                            }
                        }

                                var cls = 'bg-secondary text-white';
                                var st = String(statusText || '').toLowerCase();
                                // Detect explicit "Não Assumiu" (with or without accent or hyphen)
                                if (st.indexOf('nao assumiu') !== -1 || st.indexOf('não assumiu') !== -1 || st.indexOf('nao-assumiu') !== -1) {
                                    cls = 'bg-danger text-white';
                                    statusText = 'Não Assumiu';
                                } else if (st.indexOf('valid') !== -1 || st.indexOf('apto') !== -1 || st === 'documentos validados') {
                                    cls = 'bg-success text-white';
                                } else if (st.indexOf('pendente') !== -1 || st.indexOf('pendentes') !== -1) {
                                    cls = 'bg-warning text-dark';
                                } else if (st.indexOf('documentos') !== -1) {
                                    cls = 'bg-secondary text-white';
                                }
                                return '<span class="badge '+cls+'">'+statusText+'</span>';
                    }
                },{
                    data: null,
                    name: 'pending_validate',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var id = row.id || row.num_inscricao || '';
                        if (!id) return '-';

                        // Determine status text similarly to the status column renderer
                        var statusText = (row && row.status) ? String(row.status) : (data ? String(data) : '');
                        if (!statusText || statusText.trim() === '') {
                            if (row && (row.documentos_validados === 1 || String(row.documentos_validados) === '1' || row.documentos_validados === true)) {
                                statusText = 'Documentos Validados';
                            } else {
                                statusText = 'Documentos Pendentes';
                            }
                        }
                        var st = String(statusText || '').toLowerCase();
                        var isPending = (st.indexOf('pendente') !== -1 || st.indexOf('aguardando') !== -1 || st.indexOf('corrig') !== -1);

                        if (!isPending) return '-';

                        return ''
                            + '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" data-id="'+id+'" title="Ver pendências" class="btn-pending-docs text-warning" style="vertical-align:middle;width:24px;height:24px;cursor:pointer;">'
                            + '<path stroke="none" d="M0 0h24v24H0z" fill="none"/>'
                            + '<path d="M12 1.67c.955 0 1.845 .467 2.39 1.247l.105 .16l8.114 13.548a2.914 2.914 0 0 1 -2.307 4.363l-.195 .008h-16.225a2.914 2.914 0 0 1 -2.582 -4.2l.099 -.185l8.11 -13.538a2.914 2.914 0 0 1 2.491 -1.403zm.01 13.33l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -7a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z" />'
                            + '</svg>';
                    }
                },{
                    data: null,
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var id = row.id || row.num_inscricao || '';
                        var btnDetalhar = '<a href="#" class="mr-1" title="Detalhes"><button type="button" class="btn btn-primary btn-sm btn-detalhar" data-id="'+id+'">'
                            + '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-search">'
                            + '<path stroke="none" d="M0 0h24v24H0z" fill="none" />'
                            + '<path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />'
                            + '<path d="M21 21l-6 -6" />'
                            + '</svg>'
                            + '</button></a>';

                        // deletion removed for safety — only keep details action
                        var btnExcluir = '';
                        return '<div class="btn-group" role="group">'+btnDetalhar+btnExcluir+'</div>';
                    }
                }
            ];

                var ingressoTable = $('#ingressoTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('ingresso.data') }}',
                    type: 'GET',
                    data: function(d) {
                        d.filter_nte = $('#filter_nte').val();
                        d.filter_status = $('#filter_status').val();
                    }
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
                    processing: '',
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

            // Show/hide custom processing overlay (nicer than default text)
            (function(){
                var $overlay = $('#dt-processing-overlay');
                if ($overlay.length) {
                    $('#ingressoTable').on('processing.dt', function(e, settings, processing){
                        if (processing) {
                            $overlay.stop(true,true).fadeIn(120);
                        } else {
                            $overlay.stop(true,true).fadeOut(120);
                        }
                    });
                }
            })();

            // Base URL for ingresso actions
            const ingressoBaseUrl = '{{ url('/ingresso') }}';
            const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
            // current user role (used to hide actions from NTE)
            const currentUserSector = @json(optional(Auth::user())->sector_id);
            const currentUserProfile = @json(optional(Auth::user())->profile_id);
            const isNteUser = (currentUserSector == 7 && currentUserProfile == 1);

            // CSV export using DataTables search filter
            var exportBtn = document.getElementById('btn-export-csv');
            if (exportBtn) {
                exportBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    var search = (typeof ingressoTable !== 'undefined' && ingressoTable) ? ingressoTable.search() : '';
                    // Request 'cpf' and 'name' columns for this export (CPF first)
                    var url = '{{ route('ingresso.export.csv') }}' + '?search=' + encodeURIComponent(search || '') + '&cols=cpf,name,data_nascimento,rg,orgao_emissor,data_emissao,data_emissao,uf_rg,sexo,num_titulo,zona,secao,uf_titulo,data_emissao_titulo,pis_pasep,data_pis,uf_nascimento,naturalidade,cnh,categoria_cnh,data_emissao_cnh,validade_cnh,estado_civil,nacionalidade,grau_instrução,formacao,logradouro,complemento,bairro,cep,municipio,uf,pais,tel_contato,tel_celular,email,nome_pai,nome_mae,num_certificado_militar,especie_certificado_militar,categoria_certificado_militar,orgao_certificado,num_inscricao';
                    window.open(url, '_blank');
                });
            }

            // Delegate detail click -> navigate to show page
            $(document).on('click', '.btn-detalhar', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                if (!id) return;
                window.location.href = ingressoBaseUrl + '/' + encodeURIComponent(id);
            });

            // Deletion action removed from the UI — server-side deletions remain possible via administrative tools.

            // Delegate pending-docs click -> fetch documents and show modal listing pending items
            $(document).on('click', '.btn-pending-docs', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                if (!id) return;

                function ensureSwal(){
                    return new Promise(function(resolve,reject){
                        if (typeof Swal !== 'undefined') return resolve(window.Swal);
                        var s = document.createElement('script');
                        s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                        s.onload = function(){ resolve(window.Swal); };
                        s.onerror = function(){ reject(); };
                        document.head.appendChild(s);
                    });
                }

                ensureSwal().then(function(swal){
                    swal.fire({ title: 'Carregando...', didOpen: () => { swal.showLoading(); } });

                    // determine row/status: when status contains 'corrig' we show ONLY reported documents
                    var row = (typeof ingressoTable !== 'undefined' && ingressoTable) ? ingressoTable.row($(e.currentTarget).closest('tr')).data() : null;
                    var statusTextRow = (row && row.status) ? String(row.status) : '';
                    var onlyReportedMode = (String(statusTextRow || '').toLowerCase().indexOf('corrig') !== -1);

                    fetch(ingressoBaseUrl + '/' + encodeURIComponent(id) + '/documentos', {
                        method: 'GET',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    }).then(function(response){
                        return response.text();
                    }).then(function(text){
                        var list = [];
                        var reported = [];
                        // try parse json
                        try {
                            var json = JSON.parse(text);
                            // New format: { list: [{key,label},...], existing: { key: true/false }, reports: { key: {report:1, report_description:''} } }
                            if (json && Array.isArray(json.list)) {
                                var existing = json.existing || {};
                                function normalizeKey(k){ return (''+(k||'')).toLowerCase().replace(/[^a-z0-9]/g,'').trim(); }
                                var existingNorm = {};
                                Object.keys(existing).forEach(function(k){ existingNorm[normalizeKey(k)] = existing[k]; });

                                // Map static list keys for presence check
                                var presentNorm = {};
                                json.list.forEach(function(d){
                                    var key = d.key || d.documento_key || d.k || '';
                                    var label = d.label || d.documento_label || d.l || key || 'Documento não identificado';
                                    var nk = normalizeKey(key || label);
                                    presentNorm[nk] = label;
                                    var validated = existingNorm[nk] === true || existingNorm[nk] === 1 || existingNorm[nk] === '1';
                                    // include item when not validated (either missing in DB or present but validated==false)
                                    if (!validated && !onlyReportedMode) list.push(label);
                                });

                                // Also include any documents present in DB (existing) that are not in the static list
                                Object.keys(existing).forEach(function(k){
                                    var nk = normalizeKey(k);
                                    if (!presentNorm[nk]) {
                                        var validated = existing[k] === true || existing[k] === 1 || existing[k] === '1';
                                        if (!validated && !onlyReportedMode) {
                                            // use the raw key as label if no better label is provided
                                            list.push(k || 'Documento não identificado');
                                        }
                                    }
                                });

                                // dedupe
                                list = list.filter(function(v,i,a){ return a.indexOf(v) === i; });

                                // collect reported documents (with descriptions) when present
                                if (json.reports && typeof json.reports === 'object') {
                                    Object.keys(json.reports).forEach(function(k){
                                        var rep = json.reports[k] || {};
                                        var hasReport = (rep.report === true || rep.report === 1 || rep.report === '1');
                                        if (hasReport) {
                                            var nk = normalizeKey(k);
                                            var label = presentNorm[nk] || k || 'Documento não identificado';
                                            var desc = rep.report_description || rep.description || '';
                                            reported.push({ label: label, description: desc });
                                        }
                                    });
                                }
                            } else if (Array.isArray(json)) {
                                // older format: array of docs
                                json.forEach(function(d){ if (!d.validated || d.validated == 0) list.push(d.documento_label || d.documento_key || d.label || 'Documento não identificado'); });
                            } else if (json && Array.isArray(json.documents)) {
                                json.documents.forEach(function(d){ if (!d.validated || d.validated == 0) list.push(d.documento_label || d.documento_key || d.label || 'Documento não identificado'); });
                            }
                        } catch(err) {
                            // not json -> parse HTML
                            var tmp = document.createElement('div'); tmp.innerHTML = text;
                            var inputs = tmp.querySelectorAll('input[type="checkbox"], input[type="radio"]');
                            if (inputs && inputs.length) {
                                inputs.forEach(function(inp){
                                    try{
                                        var checked = inp.checked || inp.getAttribute('checked') !== null;
                                    }catch(e){ var checked = false; }
                                    if (!checked) {
                                        // try find label
                                        var label = '';
                                        if (inp.id) {
                                            var lab = tmp.querySelector('label[for="'+inp.id+'"]');
                                            if (lab) label = lab.textContent.trim();
                                        }
                                        if (!label) {
                                            var p = inp.closest('label');
                                            if (p) label = p.textContent.trim();
                                        }
                                        if (!label) {
                                            // fallback to parent text
                                            label = inp.parentElement ? inp.parentElement.textContent.trim() : 'Documento não identificado';
                                        }
                                        if (label) list.push(label.replace(/\s{2,}/g,' ').trim());
                                    }
                                });
                            }
                        }

                        var hasPending = (list && list.length);
                        var hasReported = (reported && reported.length);
                        if (!hasPending && !hasReported) {
                            if (onlyReportedMode) {
                                swal.fire({ icon: 'info', title: 'Nenhum documento reportado', text: 'Não foram encontrados documentos reportados para este candidato.' });
                            } else {
                                swal.fire({ icon: 'info', title: 'Nenhum documento pendente', text: 'Não foram encontrados documentos pendentes para este candidato.' });
                            }
                            try {
                                var $td = $(e.currentTarget).closest('td');
                                if (typeof ingressoTable !== 'undefined' && ingressoTable && $td.length) {
                                    ingressoTable.cell($td).data('-').draw(false);
                                } else if ($td.length) {
                                    $td.text('-');
                                }
                            } catch(err) { console.warn('Failed to update cell after empty pending list', err); }
                        } else {
                            var html = '';
                            if (!onlyReportedMode) {
                                if (hasPending) {
                                    html += '<h4 style="text-align:left;margin:0 0 .25rem 0;">Documentos pendentes</h4>';
                                    html += '<ul style="text-align:left; margin:0; padding-left:1.2rem;">' + list.map(function(it){ return '<li>'+it+'</li>'; }).join('') + '</ul>';
                                }
                                if (hasReported) {
                                    html += '<h4 style="text-align:left;margin:8px 0 4px 0;">Documentos reportados</h4>';
                                    html += '<ul style="text-align:left; margin:0; padding-left:1.2rem;">' + reported.map(function(it){
                                        var d = it.description ? '<div style="font-size:0.85em;color:#333;margin-top:4px;">' + (it.description) + '</div>' : '';
                                        return '<li><strong>'+it.label+'</strong>' + d + '</li>';
                                    }).join('') + '</ul>';
                                }
                            } else {
                                // only reported mode: show only reported list
                                if (hasReported) {
                                    html += '<h4 style="text-align:left;margin:0 0 .25rem 0;">Documentos reportados</h4>';
                                    html += '<ul style="text-align:left; margin:0; padding-left:1.2rem;">' + reported.map(function(it){
                                        var d = it.description ? '<div style="font-size:0.85em;color:#333;margin-top:4px;">' + (it.description) + '</div>' : '';
                                        return '<li><strong>'+it.label+'</strong>' + d + '</li>';
                                    }).join('') + '</ul>';
                                }
                            }
                            swal.fire({ title: 'Documentos', html: html, width: 700 });
                        }
                    }).catch(function(){
                        swal.fire({ icon: 'error', title: 'Erro', text: 'Não foi possível obter a lista de documentos.' });
                    });
                }).catch(function(){ console.warn('Swal load failed'); alert('Erro ao abrir modal'); });
            });
            // Filter panel toggle and controls
            (function(){
                // add toggle button near export button if not present
                var exportBtn = document.getElementById('btn-export-csv');
                if (exportBtn) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.id = 'btn-toggle-filters';
                    btn.className = 'btn btn-primary btn-sm';
                    btn.title = 'Filtros';
                    btn.setAttribute('style', 'border-radius:5px;padding:5px !important;display:inline-flex;align-items:center;justify-content:center;');
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-filter"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 3h-16a1 1 0 0 0 -1 1v2.227l.008 .223a3 3 0 0 0 .772 1.795l4.22 4.641v8.114a1 1 0 0 0 1.316 .949l6 -2l.108 -.043a1 1 0 0 0 .576 -.906v-6.586l4.121 -4.12a3 3 0 0 0 .879 -2.123v-2.171a1 1 0 0 0 -1 -1z" /></svg>';
                    exportBtn.parentNode.insertBefore(btn, exportBtn);
                }

                function setFilterButtonOpen(isOpen){
                    var btn = document.getElementById('btn-toggle-filters');
                    if (!btn) return;
                    if (isOpen) {
                        btn.className = 'btn btn-danger btn-sm';
                        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>';
                        btn.setAttribute('aria-pressed','true');
                    } else {
                        btn.className = 'btn btn-primary btn-sm';
                        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-filter"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 3h-16a1 1 0 0 0 -1 1v2.227l.008 .223a3 3 0 0 0 .772 1.795l4.22 4.641v8.114a1 1 0 0 0 1.316 .949l6 -2l.108 -.043a1 1 0 0 0 .576 -.906v-6.586l4.121 -4.12a3 3 0 0 0 .879 -2.123v-2.171a1 1 0 0 0 -1 -1z" /></svg>';
                        btn.setAttribute('aria-pressed','false');
                    }
                    btn.setAttribute('style', 'border-radius:5px;padding:5px !important;display:inline-flex;align-items:center;justify-content:center;');
                }

                $(document).on('click', '#btn-toggle-filters', function(e){
                    e.preventDefault();
                    $('#filterPanel').slideToggle(150, function(){
                        setFilterButtonOpen($(this).is(':visible'));
                    });
                });

                // ensure initial state (panel closed)
                setFilterButtonOpen(false);

                $(document).on('click', '#applyFilters', function(e){
                    e.preventDefault();
                    if (typeof ingressoTable !== 'undefined' && ingressoTable) ingressoTable.ajax.reload();
                });

                $(document).on('click', '#clearFilters', function(e){
                    e.preventDefault();
                    $('#filter_nte').val('');
                    $('#filter_status').val('');
                    if (typeof ingressoTable !== 'undefined' && ingressoTable) ingressoTable.ajax.reload();
                });
            })();
        } else {
            console.warn('jQuery or DataTables not loaded');
        }
    });
</script>
@endpush
