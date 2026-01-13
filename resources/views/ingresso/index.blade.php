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
        #ingressoTable .btn-detalhar,
        #ingressoTable .btn-excluir {
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
        <a href="{{ route('ingresso.dashboard') }}" class="btn btn-light btn-sm">Voltar ao Dashboard</a>
    </div>
    <div class="card-body">
        <div class="print-btn mb-2 d-flex">
            <div class="d-flex" style="gap:8px;">
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
                        <th scope="col">CLASSIFICAÇÃO (AMPLA)</th>
                        <th scope="col">CLASSIFICAÇÃO (QUOTA PNE)</th>
                        <th scope="col">CLASSIFICAÇÃO RACIAL</th>
                        <th scope="col">NOTA</th>
                        <th scope="col">Nº PROCESSO SEI</th>
                        <th scope="col">STATUS</th>
                        <th scope="col">OFÍCIO</th>
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
                        return (data === null || data === undefined || data === '') ? '-' : data;
                    }
                },
                {
                    data: 'classificacao_quota_pne', name: 'classificacao_quota_pne',
                    render: function(data, type, row) {
                        return (data === null || data === undefined || data === '') ? '-' : data;
                    }
                },
                {
                    data: 'classificacao_racial', name: 'classificacao_racial',
                    render: function(data, type, row) {
                        return (data === null || data === undefined || data === '') ? '-' : data;
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
                        if (st.indexOf('valid') !== -1 || st === 'documentos validados') {
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
                    name: 'oficio',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var id = row.id || row.num_inscricao || '';
                        // Only allow oficio when status is explicitly 'Ingresso Validado' (case-insensitive exact match)
                        var statusText = (row && row.status) ? String(row.status) : '';
                        var validated = false;
                        if (statusText && statusText.toLowerCase().trim() === 'ingresso validado') {
                            validated = true;
                        }
                        if (!validated) return '-';
                        var url = ingressoBaseUrl + '/' + encodeURIComponent(id) + '/oficio' + '?print=1';
                        return '<a href="'+url+'" target="_blank" title="Abrir Ofício" style="display:inline-flex; align-items:center; justify-content:center;">'
                            + '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-file-description" style="vertical-align:middle;">'
                            + '<path stroke="none" d="M0 0h24v24H0z" fill="none"/>'
                            + '<path d="M14 3v4a1 1 0 0 0 1 1h4" />'
                            + '<path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />'
                            + '<path d="M9 17h6" />'
                            + '<path d="M9 13h6" />'
                            + '</svg></a>';
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

                        // Excluir uses the same visual format as Detalhar (anchor + button)
                        var btnExcluir = '';
                        if (!isNteUser) {
                            btnExcluir = '<a href="#" class="ml-1" title="Excluir"><button type="button" class="btn btn-danger btn-sm btn-excluir" data-id="'+id+'">'
                                + '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-trash">'
                                + '<path stroke="none" d="M0 0h24v24H0z" fill="none" />'
                                + '<path d="M4 7l16 0" />'
                                + '<path d="M10 11l0 6" />'
                                + '<path d="M14 11l0 6" />'
                                + '<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />'
                                + '<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />'
                                + '</svg>'
                                + '</button></a>';
                        }

                        return '<div class="btn-group" role="group">'+btnDetalhar+btnExcluir+'</div>';
                    }
                }
            ];

                var ingressoTable = $('#ingressoTable').DataTable({
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

            // Delegate delete click -> confirm and AJAX DELETE
            $(document).on('click', '.btn-excluir', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                if (!id) return;
                if (!confirm('Confirma exclusão deste candidato?')) return;

                fetch(ingressoBaseUrl + '/' + encodeURIComponent(id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                }).then(function(response){
                    return response.json().then(function(json){
                        return {status: response.status, body: json};
                    }).catch(function(){
                        return {status: response.status, body: {success: false, message: 'Resposta inválida'}};
                    });
                }).then(function(result){
                    if (result.status >= 200 && result.status < 300 && result.body && result.body.success) {
                        $('#ingressoTable').DataTable().ajax.reload(null, false);
                        (function(){
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
                            ensureSwal().then(function(swal){ swal.fire({ icon: 'success', title: 'Sucesso', text: 'Registro excluído com sucesso.' }); }).catch(function(){ console.warn('Swal load failed'); });
                        })();
                    } else {
                        (function(){
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
                            ensureSwal().then(function(swal){ swal.fire({ icon: 'error', title: 'Erro', text: result.body && result.body.message ? result.body.message : 'Erro ao excluir registro.' }); }).catch(function(){ console.warn('Swal load failed'); });
                        })();
                    }
                }).catch(function(){
                    (function(){
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
                        ensureSwal().then(function(swal){ swal.fire({ icon: 'error', title: 'Erro', text: 'Erro ao conectar-se ao servidor.' }); }).catch(function(){ console.warn('Swal load failed'); });
                    })();
                });
            });
        } else {
            console.warn('jQuery or DataTables not loaded');
        }
    });
</script>
@endpush
