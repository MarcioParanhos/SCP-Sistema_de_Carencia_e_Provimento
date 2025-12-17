<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="/vendors/feather/feather.css">
    <link rel="stylesheet" href="/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="/vendors/css/vendor.bundle.base.css">

    <link rel="stylesheet" href="/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">

    <link rel="stylesheet" href="/css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/sidebar-fixed.css">

    <link rel="shortcut icon" href="../images/Faviconn.png" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/css/multi-select-tag.css">



</head>

<body class="sidebar-fixed">

    <?php
    
    use Carbon\Carbon;
    
    $data_atual = Carbon::now();
    $ano_atual = $data_atual->year;
    ?>
    <div class="container-scroller ">

        {{-- Navbar --}}
        @include('layout.partials.navbar')

        <div class="container-fluid page-body-wrapper">

            {{-- Sidebar --}}
            @include('layout.partials.sidebar')

            {{-- Main --}}
            <div class="main-panel">
                <div class="content-wrapper">

                    @yield('content')

                </div>

                {{-- Footer --}}
                @include('layout.partials.footer')

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
    <script src="/js/datatables.js"></script>
    <script src="/js/scriptsAddProvimento.js"></script>
    <script src="/js/scriptsConsultarCarencia.js"></script>
    <script src="/js/scripts.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.lordicon.com/ritcuqlt.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/js/multi-select-tag.js"></script>




    <!-- Extensão Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <!-- Biblioteca SheetJS para gerar o arquivo .xlsx real -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

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

    @stack('scripts')

</body>

{{-- Modals --}}
@include('layout.partials.modals')

</html>
