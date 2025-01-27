<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="/vendors/feather/feather.css">
    <link rel="stylesheet" href="/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="/vendors/css/vendor.bundle.base.css">

    <link rel="stylesheet" href="/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">

    <link rel="stylesheet" href="/css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="/css/styles.css">

    <link rel="shortcut icon" href="../images/Faviconn.png" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/css/multi-select-tag.css">
</head>

<body class="">

    <?php

    use Carbon\Carbon;

    $data_atual = Carbon::now();
    $ano_atual = $data_atual->year;
    ?>
    <div class="container-scroller ">
        <!-- Nav logo | Notificações | Usuario -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row print-hidden">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="/"><img src="/images/logo.svg" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="/"><img src="/images/logo.svg" alt="logo" /></a>

            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <!-- <div class="d-flex justify-content-center">
                    <button class="d-flex justify-content-center align-items-center navbar-toggler navbar-toggler " type="button" data-toggle="minimize">
                        <i class="ti-angle-double-left"></i>&nbsp;
                        <span id="recolhermenu"></span>
                    </button>
                </div> -->
                <span class="mobile-hidden subheader"><strong><span class="">ANO DE REFERÊNCIA - </span>{{ session('ano_ref') }}</strong></span>

                <ul class="d-flex align-items-center navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <span class="name_profile subheader"><strong>{{ Auth::user()->name }}</strong></span>
                            <i class="ti-angle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                            <a href="/profile" class="dropdown-item"><i class="ti-settings text-primary"></i>Configurações</a>
                            <form id="logout-form" action="/logout" method="POST">
                                @csrf
                                <a class="dropdown-item" onclick="logout()"><i class="ti-power-off text-primary"></i>Sair</a>
                            </form>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar shadow rounded sidebar-offcanvas print-hidden" id="sidebar">
                <ul class="nav">
                    @if ((Auth::user()->profile != "cad_tecnico") && (Auth::user()->profile != "cgi_tecnico") && (Auth::user()->name != "Usuario CPG" && Auth::user()->name != "Usuario CPM"))

                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="icon-grid menu-icon"></i>
                            <span id="new-venda" class="menu-title">Home</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-square-plus menu-icon"></i>
                            <span class="menu-title">CARÊNCIA</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse subheader" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                                @if ( (session('ano_ref') == $ano_atual) || (Auth::user()->profile === "administrador"))
                                <li class="nav-item"><i class="ti-plus"></i><a class="nav-link sub-title" href="" data-toggle="modal" data-target="#ExemploModalCentralizado">Incluir</a></li>
                                @endif
                                @endif
                                <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title" href="/carencias/all_carencias">Buscar</a></li>
                                <!-- <li class="nav-item"><i class="ti-plus"></i><a class="nav-link sub-title" href="{{ route('carencia.real.apoioPedagogico') }}">A. PEDAGÓGICO</a></li> -->
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
                            <i class="ti-user menu-icon"></i>
                            <span class="menu-title">Provimento</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse subheader" id="form-elements">
                            <ul class="nav flex-column sub-menu">
                                @if ( (Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_coordenador") || (Auth::user()->profile === "cpg_tecnico"))
                                @if ( (session('ano_ref') == $ano_atual) || (Auth::user()->profile === "administrador"))
                                <li class="nav-item"><i class="ti-plus"></i><a class="nav-link sub-title" href="{{ route('provimentos.add') }}">Incluir</a></li>
                                @endif
                                @endif
                                <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title" href="/buscar/provimento/all_provimentos">Buscar</a></li>
                                @if ( (Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_coordenador"))
                                <li class="nav-item"><i class="ti-download"></i><a class="nav-link sub-title" href="{{ route('provimentos.servidores_encaminhamento') }}">TERMOS</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#encaminhamentos" aria-expanded="false" aria-controls="encaminhamentos">
                            <i class="ti-share menu-icon"></i>
                            <span class="menu-title">Encaminhamento</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse subheader" id="encaminhamentos">
                            <ul class="nav flex-column sub-menu">
                                @if ( (Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_coordenador"))
                                <li class="nav-item"><i class="ti-plus"></i><a class="nav-link sub-title" href="" data-toggle="modal" data-target="#incluirProvimentoEfetivo">Incluir</a></li>
                                @endif
                                <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title" href="/provimento/efetivo/show">Buscar</a></li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if (Auth::user()->profile != "cgi_tecnico")
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#aposentadoria" aria-expanded="false" aria-controls="encaminhamentos">
                            <i class="ti-files menu-icon"></i>
                            <span class="menu-title">Afastamentos</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse subheader" id="aposentadoria">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><i class="ti-zoom-in"></i><a class="nav-link sub-title" href="/aposentadorias">Definitivo</a></li>
                                <!-- <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title" href="/aposentadorias">Temporário</a></li> -->
                            </ul>
                        </div>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#regfuncional" aria-expanded="false" aria-controls="regfuncional">
                            <i class="ti-layers-alt menu-icon"></i>
                            <span class="menu-title">Reg. Funcional</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse subheader" id="regfuncional">
                            <ul class="nav flex-column sub-menu">
                            @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                                <li class="nav-item">
                                    <i class="ti-plus"></i>
                                    <a class="nav-link sub-title" href="{{ route('regularizacao_funcional.create') }}">Incluir</a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <i class="ti-search"></i>
                                    <a class="nav-link sub-title" href="{{ route('regularizacao_funcional.show') }}">Buscar</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @if ((Auth::user()->profile != "cad_tecnico") && (Auth::user()->profile != "cgi_tecnico") && (Auth::user()->name != "Usuario CPG" && Auth::user()->name != "Usuario CPM"))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#relatorios" aria-expanded="false" aria-controls="relatorios">
                            <i class="icon-paper menu-icon"></i>
                            <span class="menu-title">Relatórios</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse subheader" id="relatorios">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title" href="/search/uee/nota_tecnica">NOTA TÉCNICA</a></li>
                                @if ( (Auth::user()->profile === "cpm_tecnico") || (Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_coordenador"))
                                <!-- <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title" href="/provimento/view/data">STATUS POR ÁREA</a></li> -->
                                @endif
                                @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador") || (Auth::user()->profile === "cpm_tecnico"))
                                <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title" href="/provimento/status/tramite">STATUS EM TRAM.</a></li>
                                @endif
                                <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title" href="/status/diario">STATUS DIÁRIO</a></li>
                                @if ( (Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                                <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title" href="/status/digitacao">DIGITAÇÃO</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                            <i class="icon-bar-graph menu-icon"></i>
                            <span class="menu-title">Gerencial</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse subheader" id="charts">
                            <ul class="nav flex-column sub-menu">
                                @if ((Auth::user()->profile === "cpg_tecnico") || (Auth::user()->profile === "administrador"))
                                <li class="nav-item"><i class="fa-solid fa-school"></i><a class="nav-link sub-title" href="/uees/all_uees">Unidades</a></li>
                                @endif
                                <li class="nav-item"><i class="fa-solid fa-user-graduate"></i><a class="nav-link sub-title" href="{{ route('servidores.show') }}">Servidores</a></li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if (Auth::user()->profile === "administrador")
                    <li class="nav-item" id="administracao_collapse">
                        <a class="nav-link" data-toggle="collapse" href="#administracao" aria-expanded="false" aria-controls="administracao">
                            <i class="ti-settings menu-icon"></i>
                            <span class="menu-title">Administração</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse subheader" id="administracao">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><i class="fa-solid fa-users"></i><a class="nav-link sub-title" href="{{ route('users.show') }}">Usuários</a></li>
                                <li class="nav-item"><i class="fa-solid fa-bars-staggered"></i><a class="nav-link sub-title" href="{{ route('listas_suspensas.index') }}">Listas Susp.</a></li>
                                <!-- <li class="nav-item"><i class="fa-solid fa-user-slash"></i><a class="nav-link sub-title" href="{{ route('motivo_vagas.show') }}">Motivo Vagas</a></li> -->
                            </ul>
                        </div>
                    </li>
                    @endif
                </ul>
            </nav>
            <!-- FINAL DO MENU LATERAL -->
            <div class="main-panel">
                <div class="content-wrapper">

                    @yield('content')

                </div>
                <footer class="footer d-flex justify-content-sm-between print-hidden">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023 -
                            <script>
                                document.write(new Date().getFullYear());
                            </script> SUDEPE/DIPES/CGI.
                        </span>
                    </div>
                    <span>Version 1.1</span>
                </footer>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/9a828e0b97.js" crossorigin="anonymous"></script>

    <script src="/vendors/js/vendor.bundle.base.js"></script>

    <script src="/vendors/chart.js/Chart.min.js"></script>
    <script src="/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="/js/dataTables.select.min.js"></script>

    <script src="/js/off-canvas.js"></script>
    <script src="/js/hoverable-collapse.js"></script>
    <script src="/js/template.js"></script>
    <script src="/js/settings.js"></script>
    <script src="/js/todolist.js"></script>

    <script src="/js/dashboard.js"></script>
    <script src="/js/Chart.roundedBarCharts.js"></script>

    <script src="/js/scriptsAddCarencia.js"></script>
    <script src="/js/scriptsAddProvimento.js"></script>
    <script src="/js/scriptsConsultarCarencia.js"></script>
    <script src="/js/scripts.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.lordicon.com/ritcuqlt.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/js/multi-select-tag.js"></script>

    <script>
        new MultiSelectTag('search_disciplina', {
            placeholder: 'Buscar',
        })
    </script>

    <script>
        new MultiSelectTag('areas', {
            placeholder: 'Buscar',
        })
    </script>
    <script>
        new MultiSelectTag('search_codigo', {
            placeholder: 'Buscar',
        })
    </script>


    <script>
        $(document).ready(function() {

            $('.select2').select2({
                placeholder: "Selecione...",
                allowClear: true,
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#consultarCarencias').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "serverSide": false,
                "language": {
                    "lengthMenu": "Exibindo _MENU_ Registros por página",
                    "zeroRecords": "Nenhum registro encontrado",
                    "info": "Mostrando pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "Nenhum Registro Disponivel",
                    "infoFiltered": "(Filtrado _MAX_ Registros no Total)",
                    "sSearch": "Pesquisa Dinâmica dos Filtros (Não é filtro)",
                    "oPaginate": {
                        "sFirst": "Primeira",
                        "sNext": "Proxima",
                        "sPrevious": "Anterior",
                        "sLast": "Ultima",
                    }
                },
            });
        });
    </script>


</body>
<!-- Modal Provimento Efetivo -->
<div class="modal fade" id="incluirProvimentoEfetivo" tabindex="-1" role="dialog" aria-labelledby="incluirProvimentoEfetivo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incluirProvimentoEfetivo">SELECIONE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body divEducEspecial">
                <a id="btn_temp" class="buttonEducEspecial btn btn-primary" href="/provimento/efetivo/prover">EFETIVO</a>
            </div>
        </div>
    </div>
</div>
<!-- Modal Selecionar Carencia -->
<div class="modal fade" id="ExemploModalCentralizado" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalCentralizado">INCLUIR CARÊNCIA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a id="btn_real" class="btn btn-primary" href="{{ route('carencia.real') }}">REAL</a>
                <a id="btn_temp" class="btn btn-primary" href="{{ route('carencia.temp') }}">TEMPORARIA</a>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="TitulommodalDelete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style=" margin-top: 0px; margin-bottom: 0px;">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h4 class="modal-title text-center text-dark" id="TitulommodalDelete"><strong>Excluir Dados</strong></h4>
            </div>
            <div class="modal-body modal-destroy">
                <h4><strong>Tem certeza?</strong></h4>
                <h4><strong>O registro sera excluido permanentemente !</strong></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Fechar</button>
                <a title="Excluir Carência"><button id="btn-delete" type="button" class="btn float-right btn-danger"><i class="fas fa-trash-alt"></i> Excluir</button></a>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete Provimento-->
<div class="modal fade" id="modalDeleteProvimento" tabindex="-1" role="dialog" aria-labelledby="TituloModalDeleteProvimento aria-hidden=" true">
    <div class="modal-dialog modal-dialog-centered" role="document" style=" margin-top: 0px; margin-bottom: 0px;">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h4 class="modal-title text-center text-dark" id="TituloModalDeleteProvimento"><strong>Excluir Dados</strong></h4>
            </div>
            <div class="modal-body modal-destroy">
                <h4><strong>Tem certeza?</strong></h4>
                <h4><strong>O registro sera excluido permanentemente e a carência de origem será atualizada!</strong></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                <a title="Excluir Provimento"><button id="btn_delete_provimento" type="button" class="btn float-right btn-danger"><i class="fas fa-trash-alt"></i> Excluir</button></a>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete Vacancy Pedagogical-->
<div class="modal fade" id="ModalDeletvacancyPedagogical" tabindex="-1" role="dialog" aria-labelledby="TituloModalDeletvacancyPedagogical" aria-hidden=" true">
    <div class="modal-dialog modal-dialog-centered" role="document" style=" margin-top: 0px; margin-bottom: 0px;">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h4 class="modal-title text-center text-dark" id="TituloModalDeletvacancyPedagogical"><strong>Excluir Dados</strong></h4>
            </div>
            <div class="modal-body modal-destroy">
                <h4><strong>Tem certeza?</strong></h4>
                <h4><strong>O registro sera excluido permanentemente!</strong></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                <a title="Excluir Provimento"><button id="btn_delete_provimento" type="button" class="btn float-right btn-danger"><i class="fa-solid fa-trash"></i> Excluir</button></a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete Provimentos Efetivos-->
<div class="modal fade" id="ModalDeleteProvimentosEfetivos" tabindex="-1" role="dialog" aria-labelledby="TituloModalDeleteProvimentosEfetivos" aria-hidden=" true">
    <div class="modal-dialog modal-dialog-centered" role="document" style=" margin-top: 0px; margin-bottom: 0px;">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h4 class="modal-title text-center text-dark" id="TituloModalDeleteProvimentosEfetivos"><strong>Excluir Dados</strong></h4>
            </div>
            <div class="modal-body modal-destroy">
                <h4><strong>Tem certeza?</strong></h4>
                <h4><strong>O registro sera excluido permanentemente!</strong></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                <a title="Excluir Encaminhamento"><button id="btn_delete_provimento" type="button" class="btn float-right btn-danger"><i class="fa-solid fa-trash"></i> Excluir</button></a>
            </div>
        </div>
    </div>
</div>

</html>