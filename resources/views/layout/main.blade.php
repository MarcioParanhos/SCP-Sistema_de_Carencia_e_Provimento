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
    <link rel="stylesheet" type="text/css" href="/js/select.dataTables.min.css">

    <link rel="stylesheet" href="/css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/sidebar-fixed.css">

    <link rel="shortcut icon" href="../images/Faviconn.png" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/css/multi-select-tag.css">



</head>


<style>
  /* Sidebar responsive layout
     - Default: participate in normal flow (not fixed)
     - Desktop (>=992px): fixed at left with margin for content
     - Mobile (<992px): hidden off-canvas by default and shown when `.sidebar-offcanvas.active` is set */

  /* Default (mobile-first): sidebar participates in document flow */
  #sidebar {
    position: relative;
    width: 100%;
    overflow-y: auto;
    background: inherit;
    transform: none;
  }

  /* Desktop: fix sidebar to the left and leave room for content */
  @media (min-width: 992px) {
    #sidebar {
      position: fixed;
      top: 56px; /* height of navbar.fixed-top */
      left: 0;
      bottom: 0;
      width: 260px;
      overflow-y: auto;
      z-index: 1000;
      background: inherit;
    }

    .container-fluid.page-body-wrapper {
      margin-left: 19px; /* leave room for sidebar on desktop */
    }

    /* Support template minimization classes */
    body.sidebar-mini #sidebar { width: 185px; }
    body.sidebar-mini .container-fluid.page-body-wrapper { margin-left: 185px; }
    body.sidebar-icon-only #sidebar { width: 70px; }
    body.sidebar-icon-only .container-fluid.page-body-wrapper { margin-left: 70px; }
  }

  /* Mobile: off-canvas behavior when using .sidebar-offcanvas */
    @media (max-width: 991.98px) {
    .container-fluid.page-body-wrapper { margin-left: 0; }

    /* keep sidebar out of view by default (will not appear fixed) */
    #sidebar.sidebar-offcanvas {
      position: fixed;
      top: 56px;
      right: 0;
      bottom: 0;
      width: 260px;
      overflow-y: auto;
      z-index: 1000; /* lower than navbar so it stays behind on mobile */
      background: #ffffff; /* white background on mobile */
      box-shadow: -6px 0 18px rgba(0,0,0,0.08);
      border-left: 1px solid rgba(0,0,0,0.06);
      transform: translateX(100%);
      transition: transform .25s ease-out;
      will-change: transform;
    }

    /* when active, slide into view */
    #sidebar.sidebar-offcanvas.active { transform: translateX(0); }

    /* overlay that appears when sidebar is open; stays behind navbar */
    .mobile-sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 1010; }
    .mobile-sidebar-overlay.active { display: block; }
  }
</style>

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
        if (document.getElementById('search_disciplina')) {
            new MultiSelectTag('search_disciplina', { placeholder: 'Buscar' })
        }
    </script>

    <script>
        if (document.getElementById('areas')) {
            new MultiSelectTag('areas', { placeholder: 'Buscar' })
        }
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
