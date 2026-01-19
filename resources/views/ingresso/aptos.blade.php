@extends('layout.main')

@section('title', 'SCP - Candidatos Aptos para Ingresso')

@section('content')

<div class="container-fluid ingresso-vh full-panel">
    <div class="card">
        <style>
            /* Reuse ingresso table styling for consistent layout */
            #aptosTable th, #aptosTable td { text-align: center !important; vertical-align: middle !important; }
            #aptosTable .btn-detalhar { border-radius: 5px !important; padding: 0.3rem 0.3rem !important; font-size: 0.72rem !important; line-height: 1 !important; }
            #aptosTable .btn-group { gap: 0.3rem; }
            #aptosTable .btn-group a { margin: 0 !important; display: inline-flex; align-items: center; }
            #aptosTable .badge { border-radius: 5px !important; color: #ffffff !important; font-weight: 700 !important; padding: 0.35em 0.55em !important; }
        </style>

        <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">ENCAMINHAMENTO DE CANDIDATOS APTOS PARA INGRESSO</h4>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-end mb-3 top-action" style="gap:8px; align-items:center;">
                <a href="/ingresso/dashboard" class="btn btn-dark btn-icon btn-sm" title="Dashboard" aria-label="Ver todos os convocados" style="width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;border-radius:5px;background:#2f3b4a;border:0;padding:2px;color:#fff;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-layout-dashboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2zm0 12a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2zm10 -4a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2zm0 -8a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2z" /></svg>
                </a>

                <button id="btn-toggle-filters" class="btn btn-dark btn-icon btn-sm" title="Filtros" aria-label="Filtros" style="width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;border-radius:5px;background:#2f3b4a;border:0;padding:2px;color:#fff;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-filter"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 3h-16a1 1 0 0 0 -1 1v2.227l.008 .223a3 3 0 0 0 .772 1.795l4.22 4.641v8.114a1 1 0 0 0 1.316 .949l6 -2l.108 -.043a1 1 0 0 0 .576 -.906v-6.586l4.121 -4.12a3 3 0 0 0 .879 -2.123v-2.171a1 1 0 0 0 -1 -1z" /></svg>
                </button>

                <a href="{{ route('ingresso.export.csv') }}" class="btn btn-dark btn-icon btn-sm" title="Exportar CSV" aria-label="Exportar CSV" style="width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;border-radius:5px;background:#2f3b4a;border:0;padding:2px;color:#fff;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-csv"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" /><path d="M7 16.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0" /><path d="M10 20.25c0 .414 .336 .75 .75 .75h1.25a1 1 0 0 0 1 -1v-1a1 1 0 0 0 -1 -1h-1a1 1 0 0 1 -1 -1v-1a1 1 0 0 1 1 -1h1.25a.75 .75 0 0 1 .75 .75" /><path d="M16 15l2 6l2 -6" /></svg>
                </a>
            </div>

            <div class="table-responsive">
                <table id="aptosTable" class="table table-hover table-bordered table-sm" style="width:100%">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col">Nº DA INSCRIÇÃO</th>
                            <th scope="col">NOME</th>
                            <th scope="col">CPF</th>
                            <th scope="col">NTE</th>
                            <th scope="col">NOTA</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">ENCAMINHAMENTO</th>
                            <th scope="col">TERMO ENC.</th>
                            <th scope="col">AÇÃO</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (!(window.jQuery && $.fn.dataTable)) return;

    const ingressoBaseUrl = '{{ url('/ingresso') }}';
    const termoBaseUrl = '{{ url('/provimentos/encaminhamento') }}';
        const dtCols = [
        { data: 'num_inscricao', name: 'num_inscricao', render: function(d){ return d || '-'; } },
        { data: 'name', name: 'name', render: function(d){ return d || '-'; } },
        { data: 'cpf', name: 'cpf', render: function(d){ return d || '-'; } },
        { data: 'nte', name: 'nte', render: function(data){ if(!data) return '-'; var n=parseInt(data); return isNaN(n)?String(data):(n>9?String(n):('0'+String(n))); } },
        { data: 'nota', name: 'nota', render: function(d){ return d || '-'; } },
        {
            data: 'status', name: 'status', orderable:false, searchable:false,
            render: function(data, type, row) {
                var statusText = (row && row.status) ? String(row.status) : (data?String(data):'');
                if (!statusText || statusText.trim()==='') {
                    if (row && (row.documentos_validados===1 || String(row.documentos_validados)==='1' || row.documentos_validados===true)) statusText = 'Documentos Validados';
                    else statusText = 'Documentos Pendentes';
                }
                var cls = 'bg-secondary text-white';
                var st = String(statusText).toLowerCase();
                if (st.indexOf('nao assumiu')!==-1 || st.indexOf('não assumiu')!==-1 || st.indexOf('nao-assumiu')!==-1) { cls='bg-danger text-white'; statusText='Não Assumiu'; }
                else if (st.indexOf('valid')!==-1 || st.indexOf('apto')!==-1 || st === 'documentos validados') cls='bg-success text-white';
                else if (st.indexOf('pend')!==-1) cls='bg-warning text-dark';
                return '<span class="badge '+cls+'">'+statusText+'</span>';
            }
        },
        {
            data: 'encaminhamento_status', name: 'encaminhamento_status', orderable:false, searchable:false,
            render: function(data, type, row){
                var txt = data || '';
                if (!txt || String(txt).trim()==='') return '<span class="badge bg-secondary text-white">Sem Encaminhamento</span>';
                var st = String(txt).toLowerCase();
                var cls = 'bg-secondary text-white';
                if (/validad|valid/i.test(st)) cls = 'bg-success text-white';
                else if (/pend/i.test(st)) cls = 'bg-warning text-dark';
                else if (/retir|cancel|remov/i.test(st)) cls = 'bg-danger text-white';
                return '<span class="badge '+cls+'">'+txt+'</span>';
            }
        },
        {
            data: 'encaminhamento_id', name: 'encaminhamento_id', orderable:false, searchable:false,
            render: function(data, type, row){
                var status = (row && row.encaminhamento_status) ? String(row.encaminhamento_status) : '';
                if (!status || !/validad/i.test(status)) return '<span class="text-muted">-</span>';
                var id = data || row.encaminhamento_id || null;
                if (!id) return '<span class="text-muted">-</span>';
                var href = termoBaseUrl + '/' + id;
                return '<a href="'+href+'" target="_blank" rel="noopener" title="Abrir Termo" class="btn btn-light btn-sm" style="padding:4px 6px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;">'
                    + '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-file-invoice" aria-hidden="true"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005zm4 15h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0 -2m0 -4h-8a1 1 0 0 0 0 2h8a1 1 0 0 0 0 -2m-7 -7h-1a1 1 0 1 0 0 2h1a1 1 0 1 0 0 -2" /><path d="M19 7h-4l-.001 -4.001z" /></svg>'
                    + '</a>';
            }
        },
        {
            data: null, name: 'actions', orderable:false, searchable:false,
            render: function(data, type, row){
                var id = row.id || row.num_inscricao || '';
                if (!id) return '-';
                return '<div class="btn-group" role="group">'
                    + '<a href="' + ingressoBaseUrl + '/' + id + '/encaminhar' + '" class="btn btn-primary btn-sm btn-detalhar" title="Encaminhar" aria-label="Encaminhar">'
                        + '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-share">'
                            + '<path stroke="none" d="M0 0h24v24H0z" fill="none"/>'
                            + '<path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />'
                            + '<path d="M6 21v-2a4 4 0 0 1 4 -4h3" />'
                            + '<path d="M16 22l5 -5" />'
                            + '<path d="M21 21.5v-4.5h-4.5" />'
                        + '</svg>'
                    + '</a>'
                    + '</div>';
            }
        }
    ];

    const $overlay = $('<div id="dt-processing-overlay" style="display:none;position:absolute;inset:0;align-items:center;justify-content:center;background:rgba(0,0,0,0.10);z-index:1100;"></div>');
    $('#aptosTable').parent().css('position','relative').prepend($overlay);

    $('#aptosTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('ingresso.data') }}',
            type: 'GET',
            data: function(d){ d.filter_status = 'Apto para ingresso'; }
        },
        columns: dtCols,
        ordering: false,
        lengthMenu: [10,25,50,100],
        language: {
            decimal: ',',
            thousands: '.',
            processing: '',
            search: 'Pesquisar:',
            lengthMenu: 'Mostrar _MENU_ registros',
            info: 'Mostrando _START_ até _END_ de _TOTAL_ registros',
            infoEmpty: 'Mostrando 0 até 0 de 0 registros',
            infoFiltered: '(filtrado de _MAX_ registros no total)',
            loadingRecords: 'Carregando...',
            zeroRecords: 'Nenhum registro encontrado',
            emptyTable: 'Nenhum dado disponível na tabela',
            paginate: { first: 'Primeiro', previous: 'Anterior', next: 'Próximo', last: 'Último' }
        },
        drawCallback: function(){ if (window.jQuery && $.fn.tooltip) $('[data-toggle="tooltip"]').tooltip({container:'body'}); },
        initComplete: function(){ var table=this.api(); $('#dt-processing-overlay').remove(); }
    }).on('processing.dt', function(e, settings, processing){ if (processing) $overlay.fadeIn(120); else $overlay.fadeOut(120); });
});
</script>
@endpush

@endsection
